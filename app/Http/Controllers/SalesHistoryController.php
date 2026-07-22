<?php

namespace App\Http\Controllers;

use App\Models\SalesHistoryImport;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class SalesHistoryController extends Controller
{
    /**
     * Halaman utama: upload CSV + ringkasan data + tabel data historis.
     */
    public function index(Request $request)
    {
        // ── Statistik ringkasan ──
        $totalRows      = SalesHistoryImport::count();
        $tanggalPertama = SalesHistoryImport::min('tanggal');
        $tanggalTerakhir = SalesHistoryImport::max('tanggal');
        $produkUnik     = SalesHistoryImport::distinct('nama_barang')->count('nama_barang');
        $totalMatched   = SalesHistoryImport::where('matched_status', 'matched')->count();
        $totalUnmatched = SalesHistoryImport::where('matched_status', 'unmatched')->count();

        $stats = [
            'total_rows'       => $totalRows,
            'tanggal_pertama'  => $tanggalPertama ? Carbon::parse($tanggalPertama)->translatedFormat('d M Y') : '-',
            'tanggal_terakhir' => $tanggalTerakhir ? Carbon::parse($tanggalTerakhir)->translatedFormat('d M Y') : '-',
            'produk_unik'      => $produkUnik,
            'total_matched'    => $totalMatched,
            'total_unmatched'  => $totalUnmatched,
        ];

        // ── Data tabel (paginated, bisa filter) ──
        $query = SalesHistoryImport::query()->orderBy('tanggal', 'desc')->orderBy('nama_barang');

        if ($request->filled('search')) {
            $query->where('nama_barang', 'like', '%' . $request->search . '%');
        }
        if ($request->filled('status')) {
            $query->where('matched_status', $request->status);
        }

        $data = $query->paginate(25)->withQueryString();

        return view('sales-history.index', compact('stats', 'data'));
    }

    /**
     * Proses upload file CSV.
     *
     * Mapping kolom CSV → kolom tabel:
     *   tanggal        → tanggal
     *   Kode Barang    → kode_barang_asal
     *   Nama Barang    → nama_barang
     *   kategori       → kategori
     *   qty_terjual    → qty_terjual
     *   pendapatan     → pendapatan
     *
     * Auto-match product_id berdasarkan nama_barang ↔ products.name.
     * Skip duplikat berdasarkan (tanggal + nama_barang).
     */
    public function upload(Request $request)
    {
        $request->validate([
            'csv_file' => 'required|file|mimes:csv,txt|max:10240',
        ], [
            'csv_file.required' => 'Pilih file CSV terlebih dahulu.',
            'csv_file.mimes'    => 'File harus berformat CSV.',
            'csv_file.max'      => 'Ukuran file maksimal 10 MB.',
        ]);

        $file = $request->file('csv_file');
        $path = $file->getRealPath();

        // Baca isi CSV
        $handle = fopen($path, 'r');
        if (!$handle) {
            return back()->with('error', 'Gagal membaca file CSV.');
        }

        // Baca header
        $header = fgetcsv($handle, 0, ',');
        if (!$header) {
            fclose($handle);
            return back()->with('error', 'File CSV kosong atau tidak valid.');
        }

        // Bersihkan BOM (Byte Order Mark) jika ada
        $header[0] = preg_replace('/\x{FEFF}/u', '', $header[0]);

        // Normalisasi header (lowercase, trim)
        $header = array_map(function ($h) {
            return strtolower(trim($h));
        }, $header);

        // Mapping header CSV → kolom DB
        $mapping = [
            'tanggal'      => 'tanggal',
            'kode barang'  => 'kode_barang_asal',
            'nama barang'  => 'nama_barang',
            'kategori'     => 'kategori',
            'qty_terjual'  => 'qty_terjual',
            'pendapatan'   => 'pendapatan',
        ];

        // Validasi: pastikan semua kolom wajib ada di header
        $requiredHeaders = ['tanggal', 'nama barang', 'qty_terjual', 'pendapatan'];
        $missingHeaders  = [];
        foreach ($requiredHeaders as $rh) {
            if (!in_array($rh, $header)) {
                $missingHeaders[] = $rh;
            }
        }
        if (!empty($missingHeaders)) {
            fclose($handle);
            return back()->with('error', 'Kolom CSV tidak lengkap. Kolom yang hilang: ' . implode(', ', $missingHeaders));
        }

        // Index posisi kolom di CSV
        $colIndex = [];
        foreach ($mapping as $csvCol => $dbCol) {
            $pos = array_search($csvCol, $header);
            if ($pos !== false) {
                $colIndex[$dbCol] = $pos;
            }
        }

        // Preload semua produk untuk auto-matching (case-insensitive)
        $products = Product::select('id', 'name')
            ->get()
            ->keyBy(function ($p) {
                return strtolower(trim($p->name));
            });

        // Preload existing (tanggal + nama_barang) untuk skip duplikat
        $existingKeys = SalesHistoryImport::select('tanggal', 'nama_barang')
            ->get()
            ->map(function ($row) {
                return $row->tanggal->format('Y-m-d') . '|' . strtolower(trim($row->nama_barang));
            })
            ->flip()
            ->toArray();

        $inserted  = 0;
        $skipped   = 0;
        $errors    = 0;
        $batchData = [];
        $lineNum   = 1;

        while (($row = fgetcsv($handle, 0, ',')) !== false) {
            $lineNum++;

            // Skip baris kosong
            if (count($row) <= 1 && empty(trim($row[0] ?? ''))) {
                continue;
            }

            try {
                $tanggal    = trim($row[$colIndex['tanggal']] ?? '');
                $namaBarang = trim($row[$colIndex['nama_barang']] ?? '');
                $qtyTerjual = (int) ($row[$colIndex['qty_terjual']] ?? 0);
                $pendapatan = (float) str_replace(['.', ','], ['', '.'], $row[$colIndex['pendapatan']] ?? '0');

                $kodeBrgAsal = isset($colIndex['kode_barang_asal']) ? trim($row[$colIndex['kode_barang_asal']] ?? '') : null;
                $kategori    = isset($colIndex['kategori']) ? trim($row[$colIndex['kategori']] ?? '') : null;

                // Validasi minimum
                if (empty($tanggal) || empty($namaBarang)) {
                    $errors++;
                    continue;
                }

                // Parse tanggal (support beberapa format)
                $parsedDate = $this->parseTanggal($tanggal);
                if (!$parsedDate) {
                    $errors++;
                    continue;
                }

                // Cek duplikat
                $dupKey = $parsedDate . '|' . strtolower($namaBarang);
                if (isset($existingKeys[$dupKey])) {
                    $skipped++;
                    continue;
                }

                // Auto-match product
                $productId     = null;
                $matchedStatus = 'unmatched';
                $namaLower     = strtolower(trim($namaBarang));

                if ($products->has($namaLower)) {
                    $productId     = $products->get($namaLower)->id;
                    $matchedStatus = 'matched';
                }

                $batchData[] = [
                    'tanggal'          => $parsedDate,
                    'kode_barang_asal' => $kodeBrgAsal,
                    'nama_barang'      => $namaBarang,
                    'kategori'         => $kategori,
                    'qty_terjual'      => $qtyTerjual,
                    'pendapatan'       => $pendapatan,
                    'product_id'       => $productId,
                    'matched_status'   => $matchedStatus,
                    'created_at'       => now(),
                ];

                // Tandai sudah ada supaya baris berikutnya di file yang sama tidak duplikat
                $existingKeys[$dupKey] = true;

            } catch (\Exception $e) {
                $errors++;
                continue;
            }
        }

        fclose($handle);

        // Batch insert (chunk 500 baris per query agar tidak timeout)
        if (!empty($batchData)) {
            foreach (array_chunk($batchData, 500) as $chunk) {
                DB::table('sales_history_import')->insert($chunk);
            }
            $inserted = count($batchData);
        }

        // Buat pesan ringkasan
        $parts = [];
        $parts[] = "{$inserted} baris berhasil diimpor";
        if ($skipped > 0) {
            $parts[] = "{$skipped} baris dilewati (duplikat)";
        }
        if ($errors > 0) {
            $parts[] = "{$errors} baris error (data tidak valid)";
        }
        $message = implode(', ', $parts) . '.';

        return back()->with('success', $message);
    }

    /**
     * Hapus semua data historis (reset untuk re-import).
     */
    public function destroy()
    {
        $count = SalesHistoryImport::count();
        DB::table('sales_history_import')->truncate();

        return back()->with('success', "Berhasil menghapus {$count} baris data historis.");
    }

    /**
     * Parse tanggal dari berbagai format yang mungkin dari Kasir Pintar.
     */
    private function parseTanggal(string $tanggal): ?string
    {
        // Format yang mungkin: Y-m-d, d/m/Y, d-m-Y, m/d/Y
        $formats = ['Y-m-d', 'd/m/Y', 'd-m-Y', 'Y/m/d'];

        foreach ($formats as $format) {
            try {
                $date = Carbon::createFromFormat($format, $tanggal);
                if ($date) {
                    return $date->format('Y-m-d');
                }
            } catch (\Exception $e) {
                continue;
            }
        }

        // Fallback: coba Carbon::parse()
        try {
            return Carbon::parse($tanggal)->format('Y-m-d');
        } catch (\Exception $e) {
            return null;
        }
    }
}
