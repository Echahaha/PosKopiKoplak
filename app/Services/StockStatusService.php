<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

/**
 * Satu-satunya sumber kebenaran untuk status stok (aman/menipis/kritis/habis).
 *
 * Sebelumnya logika ini terduplikasi di LstmController::dashboardStok() (pakai
 * prediksi LSTM) dan DashboardController::buatNotifikasi() (pakai rata-rata
 * manual) -- dua rumus berbeda menghasilkan status yang beda untuk bahan yang
 * sama. Sekarang KEDUA controller wajib lewat service ini supaya status di
 * halaman "Prediksi Stok" dan di notifikasi topbar selalu sama.
 */
class StockStatusService
{
    private const SATUAN_DISKRIT = ['pcs', 'cup'];
    public const TIME_STEP       = 7;   // harus sama dengan training & Flask API

    /** Cache hasil hitung selama 60 detik supaya polling notifikasi tidak
     *  nembak Flask (kalender + predict-batch) tiap 10 detik. */
    private const CACHE_KEY       = 'stock_status_semua_bahan';
    private const CACHE_KEY_SEMUA = 'stock_status_semua_bahan_termasuk_non_resep';
    private const CACHE_TTL = 60;

    public function formatAngka(float $nilai, string $satuan): float|int
    {
        return in_array($satuan, self::SATUAN_DISKRIT, true)
            ? (int) ceil($nilai)
            : round($nilai, 2);
    }

    public function formatArrayAngka(array $nilaiArray, string $satuan): array
    {
        return array_map(fn ($v) => $this->formatAngka((float) $v, $satuan), $nilaiArray);
    }

    /**
     * Ambil riwayat TIME_STEP hari untuk satu bahan baku.
     *
     * Sumber data:
     *   1. Tabel `transaction_details JOIN recipes` → data pemakaian
     *      yang dihitung langsung dari transaksi POS.
     *   2. Tabel `stock_usage_history` → data historis lama (sebelum
     *      fitur perhitungan otomatis diaktifkan).
     *
     * Untuk menghindari penghitungan ganda (double-counting), kita
     * PRIORITASKAN data transaksi. `stock_usage_history` hanya dipakai
     * sebagai fallback untuk tanggal yang TIDAK memiliki data transaksi.
     */
    public function ambilRiwayat(int $ingredientId): array
    {
        $step         = self::TIME_STEP;
        $tanggalMulai = Carbon::now()->subDays($step - 1)->startOfDay();
        $tanggalAkhir = Carbon::now()->endOfDay();

        // Sumber utama: hitung langsung dari transaksi
        $dariTransaksi = DB::table('transaction_details')
            ->join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->join('recipes', 'transaction_details.product_id', '=', 'recipes.product_id')
            ->where('recipes.ingredient_id', $ingredientId)
            ->whereBetween('transactions.created_at', [$tanggalMulai, $tanggalAkhir])
            ->selectRaw('DATE(transactions.created_at) as tanggal, SUM(transaction_details.quantity * recipes.usage_amount) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        // Sumber fallback: data historis lama
        $dariHistori = DB::table('stock_usage_history')
            ->where('ingredient_id', $ingredientId)
            ->whereBetween('tanggal', [$tanggalMulai->format('Y-m-d'), $tanggalAkhir->format('Y-m-d')])
            ->pluck('jumlah_terpakai', 'tanggal');

        // Gabungkan: prioritaskan data transaksi, fallback ke histori
        $gabungan = [];
        for ($i = $step - 1; $i >= 0; $i--) {
            $tanggal = Carbon::now()->subDays($i)->format('Y-m-d');
            if (isset($dariTransaksi[$tanggal])) {
                $gabungan[$tanggal] = (float) $dariTransaksi[$tanggal];
            } elseif (isset($dariHistori[$tanggal])) {
                $gabungan[$tanggal] = (float) $dariHistori[$tanggal];
            } else {
                $gabungan[$tanggal] = 0;
            }
        }

        return $this->fillMissingDays($gabungan, $step);
    }

    /**
     * Hitung status stok (aman/menipis/kritis/habis) dari stok sekarang,
     * kebutuhan (prediksi LSTM atau rata-rata fallback), dan minimal stok.
     */
    public function hitungStatusStok(float $stokSekarang, float $minStock, float $kebutuhan, ?int $estimasiHariHabis): string
    {
        if ($stokSekarang <= 0) {
            return 'habis';
        }
        if ($stokSekarang < $kebutuhan || $stokSekarang <= $minStock) {
            return 'kritis';
        }
        if ($estimasiHariHabis !== null && $estimasiHariHabis <= 3) {
            return 'menipis';
        }
        return 'aman';
    }

    public function getSemuaBahan()
    {
        return DB::table('products')
            ->join('recipes', 'products.id', '=', 'recipes.ingredient_id')
            ->where('products.is_menu', 0)
            ->select('products.id', 'products.name', 'products.unit', 'products.stock', 'products.min_stock')
            ->distinct()
            ->orderBy('products.name')
            ->get();
    }

    /**
     * Buang cache status stok. Panggil ini setelah ada perubahan yang
     * memengaruhi stok (transaksi POS baru, stok opname, edit manual stok/
     * min_stock) supaya notifikasi & dashboard tidak nunggu sampai 60 detik
     * cache lama habis.
     */
    public function clearCache(): void
    {
        Cache::forget(self::CACHE_KEY);
        Cache::forget(self::CACHE_KEY_SEMUA);
    }

    /**
     * Hitung status stok utk SEMUA bahan baku, pakai prediksi LSTM
     * (/api/predict-batch) dengan fallback ke rata-rata kalau Flask/prediksi
     * gagal. Di-cache 60 detik supaya polling notifikasi (tiap 10 detik)
     * tetap ringan -- tidak setiap poll nembak Flask.
     */
    public function getStatusSemuaBahan(): array
    {
        return Cache::remember(self::CACHE_KEY, self::CACHE_TTL, function () {
            return $this->hitungStatusSemuaBahanTanpaCache();
        });
    }

    /**
     * Sama seperti getStatusSemuaBahan(), TAPI juga menyertakan bahan yang
     * tidak terdaftar di tabel `recipes` -- misal Paper Filter V60, Cup,
     * dan bahan lain yang stoknya dikurangi manual/lewat add-on POS, bukan
     * lewat resep menu. Bahan-bahan ini tidak punya riwayat pemakaian untuk
     * LSTM, jadi statusnya dihitung sederhana (stok vs min_stock):
     *   - stock <= 0                -> habis
     *   - stock < min_stock         -> kritis
     *   - stock < 1.5 * min_stock   -> menipis
     *   - selain itu                -> aman
     *
     * Dipakai khusus untuk notifikasi topbar (DashboardController), supaya
     * semua bahan baku -- baik yang dicover LSTM maupun tidak -- tetap dapat
     * peringatan kalau stoknya menipis/kritis/habis. Halaman "Prediksi Stok"
     * (lstm.dashboard-stok) TETAP pakai getStatusSemuaBahan() biasa, karena
     * memang khusus menampilkan bahan yang bisa diprediksi AI.
     */
    public function getStatusSemuaBahanTermasukNonResep(): array
    {
        return Cache::remember(self::CACHE_KEY_SEMUA, self::CACHE_TTL, function () {
            $statusResep = $this->hitungStatusSemuaBahanTanpaCache();
            $idYangSudahDihitung = array_column($statusResep, 'id');

            $bahanNonResep = DB::table('products')
                ->where('is_menu', 0)
                ->when(!empty($idYangSudahDihitung), fn ($q) => $q->whereNotIn('id', $idYangSudahDihitung))
                ->select('id', 'name', 'unit', 'stock', 'min_stock')
                ->get();

            foreach ($bahanNonResep as $produk) {
                $stock    = (float) $produk->stock;
                $minStock = (float) $produk->min_stock;

                if ($stock <= 0) {
                    $status = 'habis';
                } elseif ($stock < $minStock) {
                    $status = 'kritis';
                } elseif ($stock < 1.5 * $minStock) {
                    $status = 'menipis';
                } else {
                    $status = 'aman';
                }

                $statusResep[] = [
                    'id'                  => $produk->id,
                    'nama'                => $produk->name,
                    'satuan'              => $produk->unit,
                    'stok_sekarang'       => $produk->stock,
                    'min_stock'           => $produk->min_stock,
                    'rata_rata_harian'    => 0,
                    'prediksi_besok'      => 0,
                    'sumber_prediksi'     => 'manual', // tidak tercatat di resep, tidak bisa diprediksi LSTM
                    'estimasi_hari_habis' => null,
                    'status'              => $status,
                ];
            }

            $urutanStatus = ['habis' => 0, 'kritis' => 1, 'menipis' => 2, 'aman' => 3];
            usort($statusResep, fn ($a, $b) => $urutanStatus[$a['status']] <=> $urutanStatus[$b['status']]);

            return $statusResep;
        });
    }

    private function hitungStatusSemuaBahanTanpaCache(): array
    {
        $step       = self::TIME_STEP;
        $semuaBahan = $this->getSemuaBahan();

        if ($semuaBahan->isEmpty()) {
            return [];
        }

        $daftarTanggal = [];
        for ($i = $step - 1; $i >= 0; $i--) {
            $daftarTanggal[] = Carbon::now()->subDays($i)->format('Y-m-d');
        }
        $tanggalBesok    = Carbon::now()->addDay()->format('Y-m-d');
        $daftarTanggal[] = $tanggalBesok;

        $kalenderPerTanggal = [];
        $flaskBisaDiakses   = true;

        try {
            $responKalender = Http::timeout(10)->post('http://127.0.0.1:5000/api/kalender', [
                'tanggal_list' => $daftarTanggal,
            ]);

            if ($responKalender->successful()) {
                $kalenderPerTanggal = $responKalender->json()['data'];
            } else {
                $flaskBisaDiakses = false;
            }
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            $flaskBisaDiakses = false;
        }

        $kalenderBesok = $kalenderPerTanggal[$tanggalBesok] ?? [0, 0, 0];

        $riwayatPerBahan = [];
        $itemsBatch      = [];

        foreach ($semuaBahan as $bahan) {
            $historyLengkap              = $this->ambilRiwayat($bahan->id);
            $riwayatPerBahan[$bahan->id] = $historyLengkap;

            // Bahan yang tidak pernah dipakai sama sekali tidak perlu dikirim
            // ke Flask -- murni efisiensi batch call.
            if (array_sum($historyLengkap) <= 0) {
                continue;
            }

            $riwayatNHari = [];
            for ($i = 0; $i < $step; $i++) {
                $tgl             = $daftarTanggal[$i];
                $kalenderHariItu = $kalenderPerTanggal[$tgl] ?? [0, 0, 0];
                $riwayatNHari[]  = array_merge([$historyLengkap[$i]], $kalenderHariItu);
            }

            $itemsBatch[] = [
                'bahan_baku'                 => $bahan->name,
                'riwayat_' . $step . '_hari' => $riwayatNHari,
                'kalender_besok'             => $kalenderBesok,
            ];
        }

        $prediksiPerNamaBahan = [];

        if ($flaskBisaDiakses && !empty($itemsBatch)) {
            try {
                $responBatch = Http::timeout(30)->post('http://127.0.0.1:5000/api/predict-batch', [
                    'items' => $itemsBatch,
                ]);

                if ($responBatch->successful()) {
                    $hasilBatch = $responBatch->json()['hasil'] ?? [];
                    foreach ($hasilBatch as $satuHasil) {
                        if (($satuHasil['status'] ?? null) === 'success') {
                            $prediksiPerNamaBahan[$satuHasil['bahan_baku']] = $satuHasil['prediksi_besok'];
                        }
                    }
                }
            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::warning('Batch prediction gagal (connection): ' . $e->getMessage());
            }
        }

        $hasilDashboard = [];

        foreach ($semuaBahan as $bahan) {
            $historyLengkap = $riwayatPerBahan[$bahan->id];
            $rataRataHarian = array_sum($historyLengkap) / $step;

            $stokSekarang = (float) ($bahan->stock ?? 0);
            $minStock     = (float) ($bahan->min_stock ?? 0);

            $sumberPrediksi = 'lstm';
            if (array_key_exists($bahan->name, $prediksiPerNamaBahan)) {
                $kebutuhanBesok = (float) $prediksiPerNamaBahan[$bahan->name];
            } else {
                $kebutuhanBesok = $rataRataHarian;
                $sumberPrediksi = 'rata_rata';
            }

            $estimasiHariHabis = $rataRataHarian > 0
                ? (int) floor($stokSekarang / $rataRataHarian)
                : null;

            $status = $this->hitungStatusStok($stokSekarang, $minStock, $kebutuhanBesok, $estimasiHariHabis);

            $hasilDashboard[] = [
                'id'                  => $bahan->id,
                'nama'                => $bahan->name,
                'satuan'              => $bahan->unit,
                'stok_sekarang'       => $this->formatAngka($stokSekarang, $bahan->unit),
                'min_stock'           => $this->formatAngka($minStock, $bahan->unit),
                'rata_rata_harian'    => $this->formatAngka($rataRataHarian, $bahan->unit),
                'prediksi_besok'      => $this->formatAngka($kebutuhanBesok, $bahan->unit),
                'sumber_prediksi'     => $sumberPrediksi,
                'estimasi_hari_habis' => $estimasiHariHabis,
                'status'              => $status,
            ];
        }

        $urutanStatus = ['habis' => 0, 'kritis' => 1, 'menipis' => 2, 'aman' => 3];
        usort($hasilDashboard, fn ($a, $b) => $urutanStatus[$a['status']] <=> $urutanStatus[$b['status']]);

        return $hasilDashboard;
    }

    private function fillMissingDays(array $dataMap, int $jumlahHari): array
    {
        $result = [];
        for ($i = $jumlahHari - 1; $i >= 0; $i--) {
            $tanggal  = Carbon::now()->subDays($i)->format('Y-m-d');
            $result[] = (float) ($dataMap[$tanggal] ?? 0);
        }
        return $result;
    }
}