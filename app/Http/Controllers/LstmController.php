<?php

namespace App\Http\Controllers;

use App\Services\StockStatusService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class LstmController extends Controller
{
    private const TIME_STEP = 7;   // harus sama dengan training & Flask API

    public function __construct(private StockStatusService $stockStatusService)
    {
    }

    // =====================================================================
    // Halaman form prediksi.
    //
    // Kalau diakses dengan query string ?bahan=NamaBahan (misal dari tombol
    // "Lihat prediksi detail" di dashboard-stok.blade.php), langsung jalankan
    // prediksi untuk bahan itu dan tampilkan hasilnya di halaman yang sama --
    // tanpa perlu user pilih dropdown + klik "Hitung Prediksi Besok" lagi.
    // =====================================================================
    public function index(Request $request)
    {
        $daftarBahan = $this->stockStatusService->getSemuaBahan();

        $bahanTerpilih  = $request->query('bahan');
        $detailPrediksi = null;
        $pesanSukses    = null;
        $pesanError     = null;

        if ($bahanTerpilih) {
            $hasil = $this->prosesPrediksi($bahanTerpilih);

            if (isset($hasil['error'])) {
                $pesanError = $hasil['error'];
            } else {
                $detailPrediksi = $hasil['detail'];
                $pesanSukses    = $hasil['pesan'];
            }
        }

        return view('lstm.index', compact(
            'daftarBahan',
            'bahanTerpilih',
            'detailPrediksi',
            'pesanSukses',
            'pesanError'
        ));
    }

    // =====================================================================
    // Dashboard ringkas semua bahan -- tinggal delegasi ke StockStatusService
    // supaya status yang tampil di sini SELALU sama dengan yang dipakai untuk
    // notifikasi topbar.
    // =====================================================================
    public function dashboardStok()
    {
        $hasilDashboard = $this->stockStatusService->getStatusSemuaBahan();

        return view('lstm.dashboard-stok', ['daftarStok' => $hasilDashboard]);
    }

    // =====================================================================
    // Hitung prediksi LSTM untuk 1 bahan (form submit di halaman utama).
    // Sekarang tinggal delegasi ke prosesPrediksi(), lalu flash hasilnya ke
    // session seperti sebelumnya (dipakai saat user submit form manual, JS
    // disabled, dsb).
    // =====================================================================
    public function hitungPrediksi(Request $request)
    {
        $request->validate(['bahan_baku' => 'required|string']);

        $hasil = $this->prosesPrediksi($request->input('bahan_baku'));

        if (isset($hasil['error'])) {
            return back()->with('error', $hasil['error'])->withInput();
        }

        return back()
            ->with('sukses', $hasil['pesan'])
            ->with('detail_prediksi', $hasil['detail']);
    }

    /**
     * Logika inti hitung prediksi untuk 1 bahan baku. Dipakai bersama oleh
     * index() (akses via ?bahan=... langsung dari kartu dashboard) dan
     * hitungPrediksi() (submit form manual), supaya tidak duplikasi logika.
     *
     * @return array{error: string}|array{detail: array, pesan: string}
     */
    private function prosesPrediksi(string $bahanTarget): array
    {
        $step = self::TIME_STEP;

        $bahanRow = DB::table('products')
            ->where('name', $bahanTarget)
            ->where('is_menu', 0)
            ->first();

        if (!$bahanRow) {
            return ['error' => "Bahan baku '{$bahanTarget}' tidak ditemukan."];
        }

        $historyLengkap = $this->stockStatusService->ambilRiwayat($bahanRow->id);

        // Susun daftar tanggal: TIME_STEP hari riwayat + 1 hari besok
        $daftarTanggal = [];
        for ($i = $step - 1; $i >= 0; $i--) {
            $daftarTanggal[] = Carbon::now()->subDays($i)->format('Y-m-d');
        }
        $tanggalBesok    = Carbon::now()->addDay()->format('Y-m-d');
        $daftarTanggal[] = $tanggalBesok;

        // Ambil info kalender dari Flask
        try {
            $responKalender = Http::timeout(10)->post('http://127.0.0.1:5000/api/kalender', [
                'tanggal_list' => $daftarTanggal,
            ]);

            if (!$responKalender->successful()) {
                return ['error' => 'Gagal mengambil info kalender dari server AI.'];
            }

            $kalenderPerTanggal = $responKalender->json()['data'];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return ['error' => 'Server AI (Flask) tidak bisa dihubungi. Pastikan Flask sudah berjalan di port 5000.'];
        }

        // Susun riwayat_7_hari: [pemakaian, is_minggu, is_libur_nasional, is_long_weekend] per hari
        $riwayatNHari = [];
        for ($i = 0; $i < $step; $i++) {
            $tgl             = $daftarTanggal[$i];
            $kalenderHariItu = $kalenderPerTanggal[$tgl] ?? [0, 0, 0];
            $riwayatNHari[]  = array_merge([$historyLengkap[$i]], $kalenderHariItu);
        }

        $kalenderBesok = $kalenderPerTanggal[$tanggalBesok] ?? [0, 0, 0];

        // Kirim ke Flask untuk prediksi
        try {
            $response = Http::timeout(15)->post('http://127.0.0.1:5000/api/predict', [
                'bahan_baku'                 => $bahanTarget,
                'riwayat_' . $step . '_hari' => $riwayatNHari,
                'kalender_besok'             => $kalenderBesok,
            ]);

            if (!$response->successful()) {
                return ['error' => "API Python error (HTTP {$response->status()})."];
            }

            $hasil = $response->json();

            if (!isset($hasil['prediksi_besok'], $hasil['bahan_baku'])) {
                return ['error' => 'Respons dari API Python tidak valid.'];
            }

            $prediksiMentah = $hasil['prediksi_besok'];
            $namaHasil      = $hasil['bahan_baku'];
            $satuan         = $bahanRow->unit ?? 'satuan';
            $prediksi       = $this->stockStatusService->formatAngka($prediksiMentah, $satuan);

            $stokSekarang      = (float) ($bahanRow->stock ?? 0);
            $minStock          = (float) ($bahanRow->min_stock ?? 0);
            $cukupUntukBesok   = $stokSekarang >= $prediksiMentah;
            $rataRataHarian    = array_sum($historyLengkap) / count($historyLengkap);
            $estimasiHariHabis = $rataRataHarian > 0
                ? (int) floor($stokSekarang / $rataRataHarian)
                : null;

            $statusStok = $this->stockStatusService->hitungStatusStok($stokSekarang, $minStock, $prediksiMentah, $estimasiHariHabis);

            $kekurangan = $cukupUntukBesok
                ? 0
                : $this->stockStatusService->formatAngka($prediksiMentah - $stokSekarang, $satuan);

            $labelKalenderBesok = [];
            if ($kalenderBesok[0]) $labelKalenderBesok[] = 'Hari Minggu';
            if ($kalenderBesok[1]) $labelKalenderBesok[] = 'Libur Nasional';
            if ($kalenderBesok[2]) $labelKalenderBesok[] = 'Bagian Long Weekend';

            return [
                'pesan'  => "Prediksi kebutuhan {$namaHasil} besok: {$prediksi} {$satuan}.",
                'detail' => [
                    'bahan'                => $namaHasil,
                    'nilai'                => $prediksi,
                    'satuan'               => $satuan,
                    'history'              => $this->stockStatusService->formatArrayAngka($historyLengkap, $satuan),
                    'stok_sekarang'        => $this->stockStatusService->formatAngka($stokSekarang, $satuan),
                    'min_stock'            => $this->stockStatusService->formatAngka($minStock, $satuan),
                    'cukup_untuk_besok'    => $cukupUntukBesok,
                    'kekurangan'           => $kekurangan,
                    'estimasi_hari_habis'  => $estimasiHariHabis,
                    'status_stok'          => $statusStok,
                    'label_kalender_besok' => $labelKalenderBesok,
                    'time_step'            => $step,
                ],
            ];

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            return ['error' => 'Server AI (Flask) tidak bisa dihubungi. Pastikan Flask sudah berjalan di port 5000.'];
        } catch (\Exception $e) {
            return ['error' => 'Error: ' . $e->getMessage()];
        }
    }
}