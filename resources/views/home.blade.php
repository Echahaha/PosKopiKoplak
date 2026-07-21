@extends('dashboard')

@section('title', 'Beranda')

@section('content')

<style>
    .kk-wrap * { box-sizing: border-box; }

    /* ── PAGE HEADER ── */
    .kk-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 10px;
    }
    .kk-header h1 {
        font-size: 20px;
        font-weight: 800;
        color: #1a1f1a;
        margin: 0 0 3px;
    }
    .kk-header p {
        font-size: 12px;
        color: #6b7d6b; /* dipergelap dari #8a9a8a untuk kontras lebih baik */
        margin: 0;
    }
    .kk-date-pill {
        display: flex;
        align-items: center;
        gap: 6px;
        background: #e8f4ef;
        border: 0.5px solid rgba(61, 122, 94, 0.3);
        border-radius: 100px;
        padding: 7px 14px;
        font-size: 12px;
        font-weight: 600;
        color: #3d7a5e;
        white-space: nowrap;
    }

    /* ── STAT CARDS GRID ──
       Sebelumnya pakai .col polos (5 kolom rata otomatis) yang bikin
       sempit/berantakan di layar kecil-medium. Diganti grid responsive
       eksplisit: 1 kolom di HP, 2 di tablet, 3 di laptop, 5 di desktop besar. */
    .kk-stats-grid {
        display: grid;
        grid-template-columns: repeat(1, 1fr);
        gap: 10px;
        margin-bottom: 16px;
    }
    @media (min-width: 480px) {
        .kk-stats-grid { grid-template-columns: repeat(2, 1fr); }
    }
    @media (min-width: 900px) {
        .kk-stats-grid { grid-template-columns: repeat(3, 1fr); }
    }
    @media (min-width: 1200px) {
        .kk-stats-grid { grid-template-columns: repeat(5, 1fr); }
    }

    .kk-stat-card {
        background: white;
        border: 0.5px solid #e8eee8;
        border-radius: 14px;
        padding: 16px 14px 14px;
        position: relative;
        overflow: hidden;
        height: 100%;
        transition: box-shadow 0.18s, transform 0.18s;
    }
    .kk-stat-card:hover {
        box-shadow: 0 4px 16px rgba(61, 122, 94, 0.09);
        transform: translateY(-1px);
    }
    /* Indikator visual untuk card yang clickable: cursor + border lebih jelas saat hover */
    .kk-stat-card-clickable {
        cursor: pointer;
        text-decoration: none;
        display: block;
        color: inherit;
    }
    .kk-stat-card-clickable:hover {
        box-shadow: 0 4px 16px rgba(217, 119, 6, 0.18);
        border-color: rgba(217, 119, 6, 0.35);
    }
    .kk-stat-card-clickable .kk-stat-arrow {
        position: absolute;
        top: 14px;
        right: 14px;
        font-size: 12px;
        color: #d97706;
        opacity: 0.55;
        transition: opacity 0.15s, transform 0.15s;
    }
    .kk-stat-card-clickable:hover .kk-stat-arrow {
        opacity: 1;
        transform: translate(2px, -2px);
    }

    .kk-stat-accent {
        position: absolute;
        top: 0;
        left: 0;
        width: 3px;
        height: 100%;
        border-radius: 3px 0 0 3px;
    }
    .kk-stat-label {
        font-size: 10px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7d6b; /* dipergelap dari #8a9a8a */
        margin: 0 0 7px;
    }
    .kk-stat-value {
        font-size: 18px;
        font-weight: 800;
        color: #1a1f1a;
        line-height: 1.15;
        margin: 0 0 4px;
    }
    .kk-stat-sub {
        font-size: 11px;
        color: #8a9a8a; /* dipergelap dari #b0bfb0 */
        margin: 0;
    }
    .kk-stat-value-success { color: #27704f; }
    .kk-stat-value-danger  { color: #c0392b; }
    .kk-stat-value-amber   { color: #c97a06; }

    /* ── DELTA COMPARISON ── */
    .kk-stat-delta {
        display: inline-flex;
        align-items: center;
        gap: 3px;
        font-size: 11px;
        font-weight: 600;
        margin-top: 2px;
    }
    .kk-delta-up { color: #27704f; }
    .kk-delta-down { color: #c0392b; }
    .kk-delta-neutral { color: #8a9a8a; }

    /* ── CHART & AI CARD HELPERS (mengganti inline styles) ── */
    .kk-chart-wrap { position: relative; height: 230px; }
    .kk-chart-wrap-sm { position: relative; height: 200px; }
    .kk-ai-value {
        font-size: 14px;
        display: flex;
        align-items: center;
        gap: 7px;
        padding-top: 4px;
    }

    /* ── PAYMENT BADGE ── */
    .kk-badge-payment {
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 100px;
        letter-spacing: 0.2px;
    }
    .kk-badge-cash {
        background: #e8f4ef;
        color: #27704f;
    }
    .kk-badge-qris {
        background: #e0f2fe;
        color: #0369a1;
    }
    .kk-badge-debit {
        background: #fef4e0;
        color: #a16800;
    }

    /* ── CARD BASE ── */
    .kk-card {
        background: white;
        border: 0.5px solid #e8eee8;
        border-radius: 14px;
        padding: 18px;
        height: 100%;
    }
    .kk-card-title {
        font-size: 12px;
        font-weight: 700;
        color: #1a1f1a;
        margin: 0 0 14px;
        display: flex;
        align-items: center;
        gap: 7px;
    }
    .kk-title-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #3d7a5e;
        flex-shrink: 0;
    }

    /* ── CHART LEGEND ── */
    .kk-legend {
        display: flex;
        flex-wrap: wrap;
        gap: 12px;
        margin-bottom: 10px;
        font-size: 11px;
        color: #6b7d6b;
    }
    .kk-legend span {
        display: flex;
        align-items: center;
        gap: 5px;
    }
    .kk-legend-sq {
        width: 9px;
        height: 9px;
        border-radius: 2px;
        flex-shrink: 0;
    }

    /* ── CHART STATES: loading & empty ──
       Ditumpuk di atas canvas yang sama, ditampilkan/disembunyikan via JS
       tergantung kondisi data & proses render. */
    .kk-chart-state {
        position: absolute;
        inset: 0;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
        padding: 0 20px;
    }
    .kk-chart-state.kk-hidden { display: none; }

    .kk-skeleton-bar {
        display: flex;
        align-items: flex-end;
        gap: 8px;
        height: 80px;
    }
    .kk-skeleton-bar span {
        width: 16px;
        border-radius: 4px 4px 0 0;
        background: linear-gradient(180deg, #e8f4ef, #d4ecdf);
        animation: kk-skeleton-pulse 1.1s ease-in-out infinite;
    }
    .kk-skeleton-bar span:nth-child(1) { height: 35%; animation-delay: 0s; }
    .kk-skeleton-bar span:nth-child(2) { height: 60%; animation-delay: 0.1s; }
    .kk-skeleton-bar span:nth-child(3) { height: 45%; animation-delay: 0.2s; }
    .kk-skeleton-bar span:nth-child(4) { height: 80%; animation-delay: 0.3s; }
    .kk-skeleton-bar span:nth-child(5) { height: 55%; animation-delay: 0.4s; }
    @keyframes kk-skeleton-pulse {
        0%, 100% { opacity: 0.5; }
        50%      { opacity: 1; }
    }

    .kk-empty-icon {
        font-size: 28px;
        color: #c5d4c5;
    }
    .kk-empty-text {
        font-size: 12px;
        color: #8a9a8a;
        font-weight: 600;
        margin: 0;
    }
    .kk-empty-subtext {
        font-size: 11px;
        color: #b0bfb0;
        margin: 0;
    }

    /* ── TABLE ── */
    .kk-table { width: 100%; border-collapse: collapse; }
    .kk-table thead th {
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7d6b;
        font-weight: 600;
        padding: 0 0 10px;
        border-bottom: 0.5px solid #ecf0ec;
        background: transparent;
        text-align: left;
    }
    .kk-table thead th:last-child { text-align: right; }
    .kk-table tbody tr { border-bottom: 0.5px solid #f4f7f4; }
    .kk-table tbody tr:last-child { border-bottom: none; }
    .kk-table tbody tr:hover { background: #fafcfa; }
    .kk-table tbody td {
        padding: 10px 0;
        font-size: 12px;
        color: #2a3a2a;
        border: none;
    }
    .kk-inv { font-weight: 700; font-size: 12px; color: #1a1f1a; }
    .kk-time { font-size: 11px; color: #7a8a7a; }
    .kk-amount { font-weight: 700; }
    .kk-badge-done {
        background: #e8f4ef;
        color: #27704f;
        font-size: 10px;
        font-weight: 700;
        padding: 3px 10px;
        border-radius: 100px;
        letter-spacing: 0.2px;
    }
    .kk-link-btn {
        font-size: 11px;
        font-weight: 700;
        color: #3d7a5e;
        background: #e8f4ef;
        border: 0.5px solid rgba(61, 122, 94, 0.3);
        border-radius: 100px;
        padding: 5px 14px;
        text-decoration: none;
        transition: background 0.15s;
        white-space: nowrap;
    }
    .kk-link-btn:hover {
        background: #d4ecdf;
        color: #2d6347;
        text-decoration: none;
    }

    /* ── AI STATUS DOT (dinamis: hijau=online, merah=offline) ── */
    .kk-ai-dot {
        width: 7px; height: 7px;
        border-radius: 50%;
        display: inline-block;
        flex-shrink: 0;
    }
    .kk-ai-dot-online {
        background: #3d7a5e;
        animation: kk-pulse 2.2s ease-in-out infinite;
    }
    .kk-ai-dot-offline {
        background: #c0392b;
    }
    @keyframes kk-pulse {
        0%, 100% { opacity: 1; }
        50%       { opacity: 0.35; }
    }
</style>

<div class="kk-wrap">

    {{-- ── HEADER ── --}}
    <div class="kk-header">
        <div>
            <h1>Selamat datang ☕</h1>
            <p>Ringkasan operasional Kopi Koplak hari ini</p>
        </div>
        <div class="kk-date-pill">
            <i class="bi bi-calendar3"></i>
            <span id="kk-live-date">{{ now()->translatedFormat('l, d M Y') }}</span>
        </div>
    </div>

    {{-- ── STAT CARDS ──
         Grid responsive kustom (.kk-stats-grid), bukan kolom Bootstrap polos,
         supaya susunan tetap rapi di semua ukuran layar. --}}
    <div class="kk-stats-grid">

        {{-- Pendapatan --}}
        <div class="kk-stat-card">
            <div class="kk-stat-accent" style="background:#3d7a5e;"></div>
            <p class="kk-stat-label">Pendapatan Hari Ini</p>
            <p class="kk-stat-value">Rp {{ number_format($totalPenjualan, 0, ',', '.') }}</p>
            <p class="kk-stat-sub">
                @if($pendapatanKemarin > 0)
                    @php
                        $deltaP = (($totalPenjualan - $pendapatanKemarin) / $pendapatanKemarin) * 100;
                    @endphp
                    <span class="kk-stat-delta {{ $deltaP >= 0 ? 'kk-delta-up' : 'kk-delta-down' }}">
                        <i class="bi bi-arrow-{{ $deltaP >= 0 ? 'up' : 'down' }}"></i>
                        {{ number_format(abs($deltaP), 1) }}% dari kemarin
                    </span>
                @elseif($pendapatanKemarin == 0 && $totalPenjualan > 0)
                    <span class="kk-stat-delta kk-delta-up">Belum ada data kemarin</span>
                @else
                    <span class="kk-stat-delta kk-delta-neutral">Belum ada transaksi</span>
                @endif
            </p>
        </div>

        {{-- Laba Bersih --}}
        <div class="kk-stat-card">
            <div class="kk-stat-accent" style="background:{{ $labaBersih < 0 ? '#c0392b' : '#27a85a' }};"></div>
            <p class="kk-stat-label">Laba Bersih</p>
            <p class="kk-stat-value @if($labaBersih < 0) kk-stat-value-danger @else kk-stat-value-success @endif">
                Rp {{ number_format($labaBersih, 0, ',', '.') }}
            </p>
            <p class="kk-stat-sub">
                @if($totalPenjualan > 0)
                    Margin {{ number_format(($labaBersih / $totalPenjualan) * 100, 1) }}%
                @else
                    —
                @endif
            </p>
        </div>

        {{-- Transaksi --}}
        <div class="kk-stat-card">
            <div class="kk-stat-accent" style="background:#378add;"></div>
            <p class="kk-stat-label">Transaksi</p>
            <p class="kk-stat-value">{{ $jumlahTransaksi }}</p>
            <p class="kk-stat-sub">
                @if($jumlahTransaksi > 0)
                    Avg Rp {{ number_format($totalPenjualan / $jumlahTransaksi, 0, ',', '.') }}
                    @if($transaksiKemarin > 0)
                        @php
                            $deltaT = (($jumlahTransaksi - $transaksiKemarin) / $transaksiKemarin) * 100;
                        @endphp
                        · <span class="kk-stat-delta {{ $deltaT >= 0 ? 'kk-delta-up' : 'kk-delta-down' }}">
                            <i class="bi bi-arrow-{{ $deltaT >= 0 ? 'up' : 'down' }}"></i>
                            {{ number_format(abs($deltaT), 1) }}%
                        </span>
                    @endif
                @else
                    Belum ada
                @endif
            </p>
        </div>

        {{-- Stok Limit (clickable -> dibuat jelas dengan arrow + hover state) --}}
        <a href="{{ route('products.index') }}?filter=limit" class="kk-stat-card kk-stat-card-clickable">
            <div class="kk-stat-accent" style="background:#d97706;"></div>
            <span class="kk-stat-arrow"><i class="bi bi-arrow-up-right"></i></span>
            <p class="kk-stat-label">Stok Limit</p>
            <p class="kk-stat-value kk-stat-value-amber">{{ $stokMenipis }} item</p>
            <p class="kk-stat-sub">Perlu restok</p>
        </a>

        {{-- AI Status: berdasarkan hasil ping ke Flask /api/health dari sisi server
             ($aiStatus dikirim controller saat load awal halaman).
             Elemen-elemen di bawah diberi ID khusus (aiStatusAccent, aiStatusDot,
             aiStatusLabel, aiStatusSub) supaya JS polling di dashboard.blade.php
             bisa update tampilan ini tiap 10 detik TANPA reload halaman --
             misalnya saat Flask tiba-tiba dimatikan/dihidupkan lagi. --}}
        <div class="kk-stat-card">
            <div class="kk-stat-accent" id="aiStatusAccent" style="background:{{ $aiStatus['online'] ? '#3d7a5e' : '#c0392b' }};"></div>
            <p class="kk-stat-label">AI Status</p>
            <p class="kk-stat-value kk-ai-value" style="color:{{ $aiStatus['online'] ? '#1a1f1a' : '#c0392b' }};">
                <span class="kk-ai-dot {{ $aiStatus['online'] ? 'kk-ai-dot-online' : 'kk-ai-dot-offline' }}" id="aiStatusDot"></span>
                <span id="aiStatusLabel">{{ $aiStatus['online'] ? 'Ready' : 'Offline' }}</span>
            </p>
            <p class="kk-stat-sub" id="aiStatusSub">
                @if($aiStatus['online'])
                    {{ $aiStatus['total_model'] }} model aktif
                @else
                    {{ $aiStatus['message'] }}
                @endif
            </p>
        </div>

    </div>

    {{-- ── CHARTS ── --}}
    <div class="row g-2 mb-3">

        {{-- Line Chart --}}
        <div class="col-md-8">
            <div class="kk-card">
                <p class="kk-card-title">
                    <span class="kk-title-dot"></span>
                    Tren Penjualan 7 Hari Terakhir
                </p>
                <div class="kk-legend">
                    <span><span class="kk-legend-sq" style="background:#3d7a5e;"></span>Pendapatan</span>
                </div>
                <div class="kk-chart-wrap"
                     id="salesContainer"
                     data-days='@json($days)'
                     data-sales='@json($salesData)'>

                    {{-- Loading state: tampil default, disembunyikan JS setelah chart siap --}}
                    <div class="kk-chart-state" id="salesLoading">
                        <div class="kk-skeleton-bar">
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <p class="kk-empty-subtext">Memuat data penjualan...</p>
                    </div>

                    {{-- Empty state: tampil hanya jika semua data 0/kosong --}}
                    <div class="kk-chart-state kk-hidden" id="salesEmpty">
                        <i class="bi bi-graph-up kk-empty-icon"></i>
                        <p class="kk-empty-text">Belum ada data penjualan</p>
                        <p class="kk-empty-subtext">Grafik akan muncul setelah ada transaksi</p>
                    </div>

                    <canvas id="salesChart"
                            class="kk-hidden"
                            role="img"
                            aria-label="Line chart tren penjualan 7 hari terakhir Kopi Koplak">
                        Data penjualan 7 hari terakhir.
                    </canvas>
                </div>
            </div>
        </div>

        {{-- Donut Chart --}}
        <div class="col-md-4">
            <div class="kk-card">
                <p class="kk-card-title">
                    <span class="kk-title-dot"></span>
                    Pengeluaran Bulan Ini
                </p>
                <div class="kk-legend" id="expLegend"></div>
                <div class="kk-chart-wrap-sm"
                     id="expenseContainer"
                     data-expense='@json($expenseData)'>

                    <div class="kk-chart-state" id="expenseLoading">
                        <div class="kk-skeleton-bar">
                            <span></span><span></span><span></span><span></span><span></span>
                        </div>
                        <p class="kk-empty-subtext">Memuat data pengeluaran...</p>
                    </div>

                    <div class="kk-chart-state kk-hidden" id="expenseEmpty">
                        <i class="bi bi-pie-chart kk-empty-icon"></i>
                        <p class="kk-empty-text">Belum ada pengeluaran</p>
                        <p class="kk-empty-subtext">Data muncul setelah ada pencatatan biaya</p>
                    </div>

                    <canvas id="expenseChart"
                            class="kk-hidden"
                            role="img"
                            aria-label="Donut chart kategori pengeluaran hari ini">
                        Distribusi pengeluaran berdasarkan kategori.
                    </canvas>
                </div>
            </div>
        </div>

    </div>

    {{-- ── RECENT TRANSACTIONS ── --}}
    <div class="row">
        <div class="col-12">
            <div class="kk-card">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <p class="kk-card-title mb-0">
                        <span class="kk-title-dot"></span>
                        Aktivitas Transaksi Terbaru
                    </p>
                    <a href="{{ route('reports.index') }}" class="kk-link-btn">Detail Laporan →</a>
                </div>
                <div class="table-responsive">
                    <table class="kk-table">
                        <thead>
                            <tr>
                                <th>Invoice</th>
                                <th>Waktu</th>
                                <th>Total Belanja</th>
                                <th style="text-align:right">Pembayaran</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentTransactions as $trx)
                            <tr>
                                <td class="kk-inv">#{{ $trx->invoice_number }}</td>
                                <td class="kk-time">
                                    @if($trx->created_at->isToday())
                                        {{ $trx->created_at->format('H:i') }}
                                    @else
                                        {{ $trx->created_at->translatedFormat('d M') }} · {{ $trx->created_at->format('H:i') }}
                                    @endif
                                </td>
                                <td class="kk-amount">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</td>
                                <td style="text-align:right">
                                    @php
                                        $method = strtolower($trx->payment_method ?? 'cash');
                                        $badgeClass = match($method) {
                                            'qris' => 'kk-badge-qris',
                                            'debit' => 'kk-badge-debit',
                                            default => 'kk-badge-cash',
                                        };
                                    @endphp
                                    <span class="kk-badge-payment {{ $badgeClass }}">{{ ucfirst($trx->payment_method ?? 'Cash') }}</span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" style="text-align:center; padding:24px 0; color:#7a8a7a; font-size:13px;">
                                    Belum ada transaksi hari ini
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>{{-- .kk-wrap --}}

{{-- ── CHART JS ── --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    const colorTeal   = '#3d7a5e';
    const colorTealBg = 'rgba(61, 122, 94, 0.07)';
    const palette     = ['#3d7a5e', '#6abf96', '#a8d8c2', '#d4edd5', '#b2c9ba'];

    // Helper: tukar antara loading/empty/canvas
    function showState(canvasId, loadingId, emptyId, hasData) {
        document.getElementById(loadingId).classList.add('kk-hidden');
        if (hasData) {
            document.getElementById(emptyId).classList.add('kk-hidden');
            document.getElementById(canvasId).classList.remove('kk-hidden');
        } else {
            document.getElementById(emptyId).classList.remove('kk-hidden');
        }
    }

    // ── LINE CHART ──
    const salesEl   = document.getElementById('salesContainer');
    const labels    = JSON.parse(salesEl.getAttribute('data-days'));
    const dataSales = JSON.parse(salesEl.getAttribute('data-sales'));

    // Cek apakah semua nilai 0/kosong -> tampilkan empty state, bukan chart kosong
    const salesHasData = Array.isArray(dataSales) && dataSales.length > 0
        && dataSales.some(v => Number(v) > 0);

    showState('salesChart', 'salesLoading', 'salesEmpty', salesHasData);

    if (salesHasData) {
        new Chart(document.getElementById('salesChart').getContext('2d'), {
            type: 'line',
            data: {
                labels: labels,
                datasets: [{
                    label: 'Pendapatan',
                    data: dataSales,
                    borderColor: colorTeal,
                    backgroundColor: colorTealBg,
                    borderWidth: 2,
                    tension: 0.4,
                    fill: true,
                    pointRadius: 4,
                    pointBackgroundColor: colorTeal,
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointHoverRadius: 6,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1f1a',
                        titleColor: '#cfd9cf',
                        bodyColor: '#fff',
                        bodyFont: { weight: '700', family: 'Plus Jakarta Sans' },
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: ctx => ' Rp ' + ctx.parsed.y.toLocaleString('id-ID')
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 11, family: 'Plus Jakarta Sans' },
                            color: '#7a8a7a'
                        }
                    },
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)', drawBorder: false },
                        border: { display: false },
                        ticks: {
                            font: { size: 10, family: 'Plus Jakarta Sans' },
                            color: '#7a8a7a',
                            callback: v => 'Rp ' + v.toLocaleString('id-ID')
                        }
                    }
                }
            }
        });
    }

    // ── DONUT CHART ──
    const expEl   = document.getElementById('expenseContainer');
    const expData = JSON.parse(expEl.getAttribute('data-expense'));
    const expLabels = expData.map(d => d.category);
    const expValues = expData.map(d => d.total);
    const expTotal  = expValues.reduce((a, b) => a + b, 0);

    const expenseHasData = expData.length > 0 && expTotal > 0;

    showState('expenseChart', 'expenseLoading', 'expenseEmpty', expenseHasData);

    if (expenseHasData) {
        // Build custom legend
        const legendEl = document.getElementById('expLegend');
        expLabels.forEach((lbl, i) => {
            const pct = Math.round(expValues[i] / expTotal * 100);
            legendEl.innerHTML += `<span>
                <span style="width:9px;height:9px;border-radius:2px;background:${palette[i % palette.length]};flex-shrink:0;display:inline-block;"></span>
                ${lbl} ${pct}%
            </span>`;
        });

        new Chart(document.getElementById('expenseChart').getContext('2d'), {
            type: 'doughnut',
            data: {
                labels: expLabels,
                datasets: [{
                    data: expValues,
                    backgroundColor: palette,
                    borderWidth: 0,
                    hoverOffset: 6
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#1a1f1a',
                        bodyColor: '#fff',
                        padding: 10,
                        cornerRadius: 10,
                        callbacks: {
                            label: ctx => ' Rp ' + ctx.parsed.toLocaleString('id-ID')
                        }
                    }
                },
                cutout: '72%'
            }
        });
    }

});
</script>
@endsection