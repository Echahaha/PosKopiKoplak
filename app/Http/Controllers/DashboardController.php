<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Transaction;
use App\Models\Expense;
use App\Services\StockStatusService;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DashboardController extends Controller
{
    public function __construct(private StockStatusService $stockStatusService)
    {
    }

    public function index()
    {
        $today     = Carbon::today();
        $thisMonth = Carbon::now()->month;
        $thisYear  = Carbon::now()->year;

        // 1. Total Pendapatan Hari Ini (Uang Masuk)
        $totalPenjualan = Transaction::whereDate('created_at', $today)->sum('total_amount');

        // 1b. Pendapatan Kemarin (untuk perbandingan harian di stat card)
        $yesterday = Carbon::yesterday();
        $pendapatanKemarin = Transaction::whereDate('created_at', $yesterday)->sum('total_amount');

        // Untuk widget Omzet Bulan Ini (digunakan di Dashboard pro tadi)
        $totalOmzet = Transaction::whereMonth('created_at', $thisMonth)
            ->whereYear('created_at', $thisYear)
            ->sum('total_amount');

        // 2. Jumlah Transaksi Hari Ini
        $jumlahTransaksi = Transaction::whereDate('created_at', $today)->count();
        // Untuk widget total transaksi (bisa hari ini atau bulan ini, kita pakai hari ini dulu)
        $totalTransactions = $jumlahTransaksi;

        // 2b. Transaksi Kemarin (untuk perbandingan harian di stat card)
        $transaksiKemarin = Transaction::whereDate('created_at', $yesterday)->count();

        // 3. Status stok SEMUA bahan baku -- baik yang dicover LSTM (pakai
        //    prediksi AI, sama persis dengan halaman Prediksi Stok) MAUPUN
        //    yang tidak terdaftar di resep (Paper Filter V60, Cup, dll --
        //    dihitung pakai fallback stok vs min_stock).
        $statusSemuaBahan = $this->stockStatusService->getStatusSemuaBahanTermasukNonResep();
        $stokMenipis = collect($statusSemuaBahan)
            ->whereIn('status', ['habis', 'kritis', 'menipis'])
            ->count();

        // 4. Data Grafik Penjualan (7 Hari Terakhir) — 1 query, bukan 7
        $startDate = Carbon::today()->subDays(6);
        $salesByDate = Transaction::where('created_at', '>=', $startDate)
            ->select(DB::raw('DATE(created_at) as sale_date'), DB::raw('SUM(total_amount) as total'))
            ->groupBy('sale_date')
            ->pluck('total', 'sale_date');

        $salesData = [];
        $days = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $days[] = $date->translatedFormat('D');
            $salesData[] = (float) ($salesByDate[$date->format('Y-m-d')] ?? 0);
        }

        // 5. Total Pengeluaran Bulan Ini (Untuk Donut Chart)
        $totalExpenses = Expense::whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->sum('amount');

        $expenseData = Expense::whereMonth('date', $thisMonth)
            ->whereYear('date', $thisYear)
            ->select('category', DB::raw('SUM(amount) as total'))
            ->groupBy('category')
            ->get();

        // 6. Transaksi Terbaru (Limit 5)
        $recentTransactions = Transaction::latest()->take(5)->get();

        // 7. Hitung Laba Bersih Hari Ini
        $totalModal = DB::table('transaction_details')
            ->join('transactions', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'products.id', '=', 'transaction_details.product_id')
            ->whereDate('transactions.created_at', $today)
            ->sum(DB::raw('transaction_details.quantity * products.purchase_price'));

        $pengeluaranHariIni = Expense::whereDate('date', $today)->sum('amount');
        $labaBersih = $totalPenjualan - ($totalModal + $pengeluaranHariIni);

        // 8. Cek Status AI/Flask API (untuk widget AI Status di dashboard)
        $aiStatus = $this->cekStatusAI();

        // 9. Notifikasi: gabungan stok menipis/kritis/habis (detail per-item) +
        //    status AI. Dipakai untuk dropdown notifikasi di topbar.
        $notifications = $this->buatNotifikasi($aiStatus, $statusSemuaBahan);

        // Kirim semua variabel ke view (Gunakan nama yang konsisten dengan Blade)
        return view('home', compact(
            'totalPenjualan',
            'pendapatanKemarin',
            'totalOmzet',
            'jumlahTransaksi',
            'transaksiKemarin',
            'totalTransactions',
            'stokMenipis',
            'salesData',
            'days',
            'labaBersih',
            'totalExpenses',
            'expenseData',
            'recentTransactions',
            'aiStatus',
            'notifications'
        ));
    }

    /**
     * Endpoint JSON untuk POLLING dari browser (dipanggil via fetch() tiap
     * beberapa detik oleh JavaScript di dashboard.blade.php).
     *
     * Tetap ringan karena hasilnya di-cache 60 detik -- tidak setiap poll
     * (tiap 10 detik) nembak Flask ulang.
     *
     * Route: GET /api/dashboard/status (lihat routes/web.php)
     */
    public function statusJson()
    {
        // Cache 5 detik: cukup singkat supaya notifikasi tetap fresh untuk
        // demo di depan dosen, tapi mencegah Flask ditembak berkali-kali
        // kalau banyak tab terbuka / polling bersamaan.
        $data = Cache::remember('dashboard_status', 5, function () {
            $aiStatus         = $this->cekStatusAI();
            $statusSemuaBahan = $this->stockStatusService->getStatusSemuaBahanTermasukNonResep();
            $notifications    = $this->buatNotifikasi($aiStatus, $statusSemuaBahan);

            return [
                'aiStatus'      => $aiStatus,
                'notifications' => $notifications,
            ];
        });

        return response()->json($data);
    }

    /**
     * Ping Flask API /api/health untuk cek apakah service AI/LSTM hidup.
     * Timeout sengaja dibuat singkat (2 detik) supaya kalau Flask
     * down/lambat, dashboard Laravel TIDAK ikut hang menunggu respons.
     *
     * @return array{online: bool, total_model: int|null, message: string}
     */
    private function cekStatusAI(): array
    {
        // Cache 5 detik: Flask tidak perlu di-ping setiap kali ada
        // request. Untuk demo skripsi, delay maks 5 detik sudah cukup
        // responsif — dosen tidak akan sadar.
        return Cache::remember('ai_health_status', 5, function () {
            try {
                $response = Http::timeout(2)
                    ->connectTimeout(2)
                    ->get('http://127.0.0.1:5000/api/health');

                if ($response->successful()) {
                    $data = $response->json();
                    return [
                        'online'      => true,
                        'total_model' => $data['total_model'] ?? 0,
                        'message'     => 'Model aktif',
                    ];
                }

                return [
                    'online'      => false,
                    'total_model' => null,
                    'message'     => 'API merespons tapi bermasalah',
                ];

            } catch (\Illuminate\Http\Client\ConnectionException $e) {
                Log::warning('AI Health check gagal (connection): ' . $e->getMessage());
                return [
                    'online'      => false,
                    'total_model' => null,
                    'message'     => 'Service tidak aktif',
                ];
            } catch (\Exception $e) {
                Log::error('AI Health check error: ' . $e->getMessage());
                return [
                    'online'      => false,
                    'total_model' => null,
                    'message'     => 'Gagal memeriksa status',
                ];
            }
        });
    }

    /**
     * Susun daftar notifikasi untuk dropdown di topbar.
     * Sumber saat ini: (1) item stok menipis/kritis/habis (dari
     * StockStatusService -- SAMA PERSIS dengan yang dipakai halaman
     * Prediksi Stok), (2) status AI/Flask.
     *
     * Setiap notifikasi punya struktur:
     *   - type     : 'danger' | 'warning' | 'info' (menentukan warna ikon di UI)
     *   - icon     : nama ikon Tabler Icons (tanpa prefix "ti-")
     *   - title    : judul singkat
     *   - subtitle : detail tambahan
     *   - link     : route tujuan saat notifikasi diklik
     *
     * @param array<int, array<string, mixed>> $statusSemuaBahan hasil dari
     *        StockStatusService::getStatusSemuaBahan(), sudah terurut
     *        berdasarkan prioritas (habis > kritis > menipis > aman).
     * @return array<int, array<string, mixed>>
     */
    private function buatNotifikasi(array $aiStatus, array $statusSemuaBahan): array
    {
        $notifications = [];

        // Ambil bahan yang statusnya bukan 'aman', maksimal 5 untuk dropdown.
        // Sudah terurut prioritas oleh StockStatusService.
        $notifList = array_values(array_filter(
            $statusSemuaBahan,
            fn (array $item) => $item['status'] !== 'aman'
        ));
        $notifList = array_slice($notifList, 0, 5);

        foreach ($notifList as $item) {
            if ($item['status'] === 'habis') {
                $type  = 'danger';
                $icon  = 'alert-triangle';
                $title = "{$item['nama']} habis";
            } elseif ($item['status'] === 'kritis') {
                $type  = 'warning';
                $icon  = 'alert-circle';
                $title = "{$item['nama']} kritis";
            } else { // menipis
                $type  = 'info';
                $icon  = 'package';
                $title = "{$item['nama']} menipis";
            }

            $notifications[] = [
                'type'     => $type,
                'icon'     => $icon,
                'title'    => $title,
                'subtitle' => "Sisa stok: {$item['stok_sekarang']} {$item['satuan']} (min. {$item['min_stock']} {$item['satuan']})",
                'link'     => route('products.index') . '?filter=limit',
            ];
        }

        // ── Status AI/Flask offline ──
        if (!$aiStatus['online']) {
            $notifications[] = [
                'type'     => 'danger',
                'icon'     => 'cpu',
                'title'    => 'Layanan AI tidak aktif',
                'subtitle' => $aiStatus['message'],
                'link'     => null,
            ];
        }

        return $notifications;
    }
}