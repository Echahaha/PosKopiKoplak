@extends('dashboard')

@section('content')

<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    .rpt-wrap * {
        font-family: 'Plus Jakarta Sans', sans-serif !important
    }

    .rpt-wrap {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 2px 0 32px
    }

    /* page header */
    .rpt-ph-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .8px;
        text-transform: uppercase;
        color: #a8a59e;
        margin-bottom: 3px
    }

    .rpt-ph-title {
        font-size: 22px;
        font-weight: 800;
        color: #1c1b18;
        letter-spacing: -.4px;
        margin: 0 0 20px
    }

    /* top bar: filter tabs + export */
    .rpt-topbar {
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
        margin-bottom: 14px;
        flex-wrap: wrap
    }

    /* pill tab group */
    .rpt-tabs {
        display: flex;
        background: #fff;
        border-radius: 12px;
        padding: 4px;
        gap: 3px;
        border: 1px solid rgba(0, 0, 0, .06)
    }

    .rpt-tab {
        display: inline-block;
        padding: 7px 16px;
        border-radius: 9px;
        font-size: 13px;
        font-weight: 600;
        color: #a8a59e;
        text-decoration: none;
        transition: all .15s;
        white-space: nowrap;
        border: none;
        background: transparent;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif !important
    }

    .rpt-tab:hover {
        color: #1c1b18;
        background: #f5f4f0
    }

    .rpt-tab.active {
        background: #3d7a5e;
        color: #fff
    }

    /* export button */
    .rpt-export {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #fff;
        border: 1.5px solid #eeede8;
        border-radius: 11px;
        padding: 8px 16px;
        font-size: 13px;
        font-weight: 600;
        color: #1c1b18;
        text-decoration: none;
        transition: all .15s;
        white-space: nowrap
    }

    .rpt-export:hover {
        border-color: #3d7a5e;
        color: #3d7a5e;
        text-decoration: none
    }

    .rpt-export svg {
        stroke: #3d7a5e;
        flex-shrink: 0
    }

    /* filter custom bar */
    .rpt-filter-bar {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 16px;
        padding: 14px 16px;
        margin-bottom: 18px;
        display: flex;
        gap: 12px;
        flex-wrap: wrap;
        align-items: flex-end;
    }

    .rpt-filter-group {
        display: flex;
        flex-direction: column;
        gap: 5px
    }

    .rpt-filter-group label {
        font-size: 11px;
        font-weight: 700;
        color: #a8a59e;
        text-transform: uppercase;
        letter-spacing: .4px
    }

    .rpt-filter-group input,
    .rpt-filter-group select {
        border: 1px solid #eeede8;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
        color: #1c1b18;
        background: #faf9f7;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .rpt-filter-group input:focus,
    .rpt-filter-group select:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61, 122, 94, .12);
        background: #fff;
        outline: none;
    }

    .rpt-btn-filter {
        background: #3d7a5e;
        color: #fff;
        border: none;
        border-radius: 10px;
        padding: 9px 18px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 6px;
        transition: background .2s;
    }

    .rpt-btn-filter:hover {
        background: #2d6a4e;
        color: #fff
    }

    .rpt-btn-reset {
        background: #f5f4f0;
        color: #706d64;
        border: 1px solid #eeede8;
        border-radius: 10px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        text-decoration: none;
        display: flex;
        align-items: center;
        gap: 6px;
    }

    .rpt-btn-reset:hover {
        background: #eceae4;
        color: #5a6a5a;
        text-decoration: none
    }

    /* card */
    .rpt-card {
        background: #fff;
        border-radius: 20px;
        border: 1px solid rgba(0, 0, 0, .06);
        overflow: hidden
    }

    .rpt-card-body {
        padding: 26px
    }

    /* summary strip */
    .rpt-summary {
        background: #3d7a5e;
        border-radius: 18px;
        padding: 22px 28px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 18px;
        position: relative;
        overflow: hidden
    }

    .rpt-summary::before {
        content: '';
        position: absolute;
        right: -15px;
        top: -30px;
        width: 120px;
        height: 120px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .08)
    }

    .rpt-s-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .7px;
        text-transform: uppercase;
        color: rgba(255, 255, 255, .55);
        margin-bottom: 5px
    }

    .rpt-s-amount {
        font-size: 26px;
        font-weight: 800;
        color: #fff;
        letter-spacing: -.5px;
        line-height: 1
    }

    .rpt-s-icon {
        width: 46px;
        height: 46px;
        background: rgba(255, 255, 255, .15);
        border-radius: 13px;
        display: flex;
        align-items: center;
        justify-content: center;
        position: relative;
        z-index: 1;
        flex-shrink: 0
    }

    /* grid untuk laba bersih breakdown */
    .rpt-summary-grid {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 18px
    }

    @media (max-width:900px) {
        .rpt-summary-grid {
            grid-template-columns: repeat(2, 1fr)
        }
    }

    .rpt-mini-card {
        background: #fff;
        border: 1px solid rgba(0, 0, 0, .06);
        border-radius: 16px;
        padding: 18px 20px
    }

    .rpt-mini-label {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #a8a59e;
        margin-bottom: 6px
    }

    .rpt-mini-amount {
        font-size: 19px;
        font-weight: 800;
        letter-spacing: -.4px;
        line-height: 1.1
    }

    .rpt-mini-amount.positive {
        color: #3d7a5e
    }

    .rpt-mini-amount.negative {
        color: #c0392b
    }

    .rpt-mini-amount.neutral {
        color: #1c1b18
    }

    .rpt-negative {
        color: #c0392b;
    }

    /* table shared */
    .rpt-table {
        width: 100%;
        border-collapse: collapse
    }

    .rpt-table th {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        color: #a8a59e;
        padding: 0 12px 12px;
        border-bottom: 1.5px solid #f5f4f0;
        white-space: nowrap
    }

    .rpt-table th:first-child {
        padding-left: 4px
    }

    .rpt-table th:last-child {
        padding-right: 4px
    }

    .rpt-table td {
        padding: 13px 12px;
        border-bottom: 1px solid #f5f4f0;
        vertical-align: middle;
        font-size: 13.5px;
        color: #1c1b18
    }

    .rpt-table td:first-child {
        padding-left: 4px
    }

    .rpt-table td:last-child {
        padding-right: 4px
    }

    .rpt-table tbody tr:last-child td {
        border-bottom: none
    }

    .rpt-table tbody tr:hover td {
        background: #faf9f7
    }

    /* summary table cells */
    .rpt-date-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-weight: 700;
        color: #1c1b18;
        text-decoration: none;
        font-size: 13.5px
    }

    .rpt-date-link:hover {
        color: #3d7a5e;
        text-decoration: none
    }

    .rpt-date-link svg {
        stroke: #3d7a5e;
        flex-shrink: 0
    }

    .rpt-trx-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        background: #f0efeb;
        color: #706d64
    }

    .rpt-income {
        font-size: 14px;
        font-weight: 700;
        color: #3d7a5e;
        letter-spacing: -.3px
    }

    /* detail table cells */
    .rpt-time {
        font-size: 13.5px;
        font-weight: 700;
        color: #1c1b18
    }

    .rpt-time-sub {
        font-size: 11.5px;
        color: #a8a59e;
        margin-top: 1px
    }

    .rpt-invoice {
        display: inline-block;
        padding: 3px 9px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 700;
        background: #f5f4f0;
        color: #1c1b18;
        cursor: pointer;
        text-decoration: none;
        transition: background .15s
    }

    .rpt-invoice:hover {
        background: #e8f4ef;
        color: #3d7a5e;
        text-decoration: none
    }

    .rpt-pay-badge {
        display: inline-block;
        padding: 3px 10px;
        border-radius: 7px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap
    }

    .pay-cash {
        background: #e6f7f0;
        color: #1a7a52
    }

    .pay-debit {
        background: #e6f4ff;
        color: #1a6fb5
    }

    .pay-qris {
        background: #f2eaff;
        color: #7340c8
    }

    .pay-other {
        background: #f0efeb;
        color: #706d64
    }

    .rpt-total {
        font-size: 14px;
        font-weight: 700;
        color: #1c1b18;
        letter-spacing: -.3px
    }

    .rpt-cashier {
        font-size: 12px;
        color: #a8a59e
    }

    /* action buttons */
    .rpt-act-wrap {
        display: flex;
        gap: 6px;
        align-items: center
    }

    .rpt-btn-act {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid #eeede8;
        background: #faf9f7;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #a8a59e;
        transition: all .15s;
        padding: 0;
        text-decoration: none;
        flex-shrink: 0
    }

    .rpt-btn-act:hover {
        border-color: #b8d9cc;
        background: #e8f4ef;
        color: #3d7a5e;
        text-decoration: none
    }

    .rpt-btn-del {
        width: 32px;
        height: 32px;
        border-radius: 8px;
        border: 1.5px solid #eeede8;
        background: #faf9f7;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        color: #c8c5bc;
        transition: all .15s;
        padding: 0;
        flex-shrink: 0
    }

    .rpt-btn-del:hover {
        background: #fdecea;
        border-color: #f5b8b1;
        color: #c0392b
    }

    /* empty */
    .rpt-empty {
        text-align: center;
        padding: 52px 20px
    }

    .rpt-empty-icon {
        width: 56px;
        height: 56px;
        background: #f5f4f0;
        border-radius: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 12px
    }

    .rpt-empty-text {
        font-size: 13.5px;
        font-weight: 600;
        color: #c8c5bc;
        margin: 0
    }

    /* pagination */
    .rpt-pagination-wrap {
        padding: 16px 4px 0
    }

    .rpt-pagination-wrap nav {
        display: flex;
        justify-content: center
    }
</style>

<div class="rpt-wrap">

    {{-- Page Header --}}
    <div class="rpt-ph-label">Keuangan</div>
    <h1 class="rpt-ph-title">Laporan & Riwayat Transaksi</h1>

    {{-- Top Bar: Tab cepat + Export --}}
    <div class="rpt-topbar">
        <div class="rpt-tabs">
            <a href="{{ route('reports.index', ['filter' => 'today']) }}" class="rpt-tab {{ $activeFilter == 'today'     ? 'active' : '' }}">Hari Ini</a>
            <a href="{{ route('reports.index', ['filter' => 'yesterday']) }}" class="rpt-tab {{ $activeFilter == 'yesterday' ? 'active' : '' }}">Kemarin</a>
            <a href="{{ route('reports.index', ['filter' => 'monthly']) }}" class="rpt-tab {{ $activeFilter == 'monthly'   ? 'active' : '' }}">Bulanan</a>
            <a href="{{ route('reports.index', ['filter' => 'yearly']) }}" class="rpt-tab {{ $activeFilter == 'yearly'    ? 'active' : '' }}">Tahunan</a>
        </div>

        <a href="{{ route('reports.export', request()->query()) }}" class="rpt-export">
            <svg width="15" height="15" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M21 15v4a2 2 0 01-2 2H5a2 2 0 01-2-2v-4" />
                <polyline points="7 10 12 15 17 10" />
                <line x1="12" y1="15" x2="12" y2="3" />
            </svg>
            Export CSV
        </a>
    </div>

    {{-- ─────────────────────────────────────────────── --}}
    {{-- FILTER CUSTOM — hanya muncul saat mode detail (bukan ringkasan bulanan/tahunan) --}}
    {{-- ─────────────────────────────────────────────── --}}
    @if($filter != 'monthly' && $filter != 'yearly')
    <form method="GET" action="{{ route('reports.index') }}" class="rpt-filter-bar">
        <div class="rpt-filter-group">
            <label>Dari Tanggal</label>
            <input type="date" name="from" value="{{ request('from') }}">
        </div>
        <div class="rpt-filter-group">
            <label>Sampai Tanggal</label>
            <input type="date" name="to" value="{{ request('to') }}">
        </div>
        <div class="rpt-filter-group">
            <label>Metode Bayar</label>
            <select name="payment_method">
                <option value="">Semua</option>
                <option value="Cash" {{ request('payment_method') == 'Cash' ? 'selected' : '' }}>Cash</option>
                <option value="Debit" {{ request('payment_method') == 'Debit' ? 'selected' : '' }}>Debit</option>
                <option value="QRIS" {{ request('payment_method') == 'QRIS' ? 'selected' : '' }}>QRIS</option>
            </select>
        </div>
        <div class="rpt-filter-group" style="flex:1; min-width:180px">
            <label>Cari Invoice</label>
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Misal: INV-6A3F...">
        </div>
        <button type="submit" class="rpt-btn-filter">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="currentColor">
                <path d="M3 4a1 1 0 011-1h16a1 1 0 01.8 1.6l-6.3 8.4v5a1 1 0 01-.4.8l-3 2a1 1 0 01-1.6-.8v-7L3.2 4.6A1 1 0 013 4z" />
            </svg>
            Filter
        </button>
        @if($hasCustomFilter)
        <a href="{{ route('reports.index', ['filter' => 'today']) }}" class="rpt-btn-reset">
            <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="18" y1="6" x2="6" y2="18" />
                <line x1="6" y1="6" x2="18" y2="18" />
            </svg>
            Reset
        </a>
        @endif
    </form>
    @endif

    {{-- ─────────────────────────────────────────────── --}}
    {{-- RINGKASAN (Bulanan / Tahunan) --}}
    {{-- ─────────────────────────────────────────────── --}}
    @if($filter == 'monthly' || $filter == 'yearly')

    <div class="rpt-card">
        <div class="rpt-card-body">

            <div style="margin-bottom:18px">
                <div style="font-size:12px;font-weight:700;color:#a8a59e;letter-spacing:.5px;text-transform:uppercase;margin-bottom:2px">
                    {{ $filter == 'monthly' ? 'Ringkasan Harian' : 'Ringkasan Bulanan' }}
                </div>
            </div>

            <div style="overflow-x:auto">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th>{{ $filter == 'monthly' ? 'Tanggal' : 'Bulan' }}</th>
                            <th style="text-align:center">Transaksi</th>
                            <th style="text-align:right">Pendapatan</th>
                            <th style="text-align:right">Laba Bersih</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($reportData as $data)
                        <tr>
                            <td>
                                @if($filter == 'monthly')
                                <a href="{{ route('reports.index', ['filter' => 'today', 'date' => $data->date]) }}" class="rpt-date-link">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                    </svg>
                                    {{ \Carbon\Carbon::parse($data->date)->translatedFormat('d F Y') }}
                                </a>
                                @else
                                <a href="{{ route('reports.index', ['filter' => 'monthly', 'month' => $data->month, 'year' => date('Y')]) }}" class="rpt-date-link">
                                    <svg width="13" height="13" viewBox="0 0 24 24" fill="none" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <circle cx="11" cy="11" r="8" />
                                        <line x1="21" y1="21" x2="16.65" y2="16.65" />
                                    </svg>
                                    {{ \Carbon\Carbon::create(date('Y'), $data->month, 1)->translatedFormat('F Y') }}
                                </a>
                                @endif
                            </td>
                            <td style="text-align:center">
                                <span class="rpt-trx-badge">{{ $data->total_trx }} Trx</span>
                            </td>
                            <td style="text-align:right">
                                <span class="rpt-income">Rp {{ number_format($data->total_income, 0, ',', '.') }}</span>
                            </td>
                            <td style="text-align:right">
                                <span class="{{ $data->laba_bersih >= 0 ? 'rpt-income' : 'rpt-negative' }}" style="font-weight:700;">
                                    Rp {{ number_format($data->laba_bersih, 0, ',', '.') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4">
                                <div class="rpt-empty">
                                    <div class="rpt-empty-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c8c5bc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <rect x="3" y="4" width="18" height="18" rx="2" />
                                            <line x1="16" y1="2" x2="16" y2="6" />
                                            <line x1="8" y1="2" x2="8" y2="6" />
                                            <line x1="3" y1="10" x2="21" y2="10" />
                                        </svg>
                                    </div>
                                    <p class="rpt-empty-text">Tidak ada data untuk periode ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>
    </div>

    {{-- ─────────────────────────────────────────────── --}}
    {{-- DETAIL (Hari Ini / Kemarin / drill-down / filter custom) --}}
    {{-- ─────────────────────────────────────────────── --}}
    @else

    {{-- Summary strip (Pendapatan) — tetap dipertahankan --}}
    <div class="rpt-summary">
        <div>
            <div class="rpt-s-label">
                @if($hasCustomFilter)
                Total Sesuai Filter · {{ $totalTransaksi }} Transaksi
                @elseif($selectedDate)
                Total Pendapatan {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }} · {{ $totalTransaksi }} Transaksi
                @elseif($filter == 'yesterday')
                Total Pendapatan Kemarin · {{ $totalTransaksi }} Transaksi
                @else
                Total Pendapatan Hari Ini · {{ $totalTransaksi }} Transaksi
                @endif
            </div>
            <div class="rpt-s-amount">Rp {{ number_format($totalOmzet, 0, ',', '.') }}</div>
        </div>
        <div class="rpt-s-icon">
            <svg width="22" height="22" viewBox="0 0 24 24" fill="none" stroke="rgba(255,255,255,0.7)" stroke-width="1.8" stroke-linecap="round" stroke-linejoin="round">
                <line x1="12" y1="1" x2="12" y2="23" />
                <path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6" />
            </svg>
        </div>
    </div>

    {{-- Breakdown laba: HPP, Pengeluaran, Laba Kotor, Laba Bersih --}}
    <div class="rpt-summary-grid">
        <div class="rpt-mini-card">
            <div class="rpt-mini-label">Laba Kotor</div>
            <div class="rpt-mini-amount {{ $labaKotor >= 0 ? 'positive' : 'negative' }}">Rp {{ number_format($labaKotor, 0, ',', '.') }}</div>
        </div>
        <div class="rpt-mini-card">
            <div class="rpt-mini-label">Pengeluaran</div>
            <div class="rpt-mini-amount neutral">Rp {{ number_format($totalPengeluaran, 0, ',', '.') }}</div>
        </div>
        <div class="rpt-mini-card">
            <div class="rpt-mini-label">Laba Bersih</div>
            <div class="rpt-mini-amount {{ $labaBersih >= 0 ? 'positive' : 'negative' }}">Rp {{ number_format($labaBersih, 0, ',', '.') }}</div>
        </div>
    </div>

    <div class="rpt-card">
        <div class="rpt-card-body">

            <div style="margin-bottom:18px">
                <div style="font-size:12px;font-weight:700;color:#a8a59e;letter-spacing:.5px;text-transform:uppercase;margin-bottom:2px">
                    @if($hasCustomFilter)
                    Hasil Filter
                    @elseif($selectedDate)
                    Transaksi {{ \Carbon\Carbon::parse($selectedDate)->translatedFormat('d F Y') }}
                    @elseif($filter == 'yesterday')
                    Transaksi Kemarin
                    @else
                    Transaksi Hari Ini
                    @endif
                </div>
            </div>

            <div style="overflow-x:auto">
                <table class="rpt-table">
                    <thead>
                        <tr>
                            <th>Waktu</th>
                            <th>Invoice</th>
                            <th>Kasir</th>
                            <th>Metode</th>
                            <th style="text-align:right">Total</th>
                            <th style="text-align:center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($transactions as $trx)
                        @php
                        $payClass = match($trx->payment_method) {
                        'Cash' => 'pay-cash',
                        'Debit' => 'pay-debit',
                        'QRIS' => 'pay-qris',
                        default => 'pay-other',
                        };
                        @endphp
                        <tr>
                            <td>
                                <div class="rpt-time">{{ $trx->created_at->format('H:i') }}</div>
                                <div class="rpt-time-sub">{{ $trx->created_at->format('d M Y') }}</div>
                            </td>
                            <td>
                                <a href="#" data-bs-toggle="modal" data-bs-target="#detailModal{{ $trx->id }}"
                                    class="rpt-invoice">#{{ $trx->invoice_number }}</a>
                            </td>
                            <td>
                                <span class="rpt-cashier">{{ $trx->user->name ?? '-' }}</span>
                            </td>
                            <td>
                                <span class="rpt-pay-badge {{ $payClass }}">{{ $trx->payment_method }}</span>
                            </td>
                            <td style="text-align:right">
                                <span class="rpt-total">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                            </td>
                            <td>
                                <div class="rpt-act-wrap" style="justify-content:center">
                                    <a href="{{ route('pos.print', $trx->id) }}" target="_blank"
                                        class="rpt-btn-act" title="Print">
                                        <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <polyline points="6 9 6 2 18 2 18 9" />
                                            <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                                            <rect x="6" y="14" width="12" height="8" />
                                        </svg>
                                    </a>
                                    <form action="{{ route('transactions.void', $trx->id) }}" method="POST"
                                        onsubmit="return confirm('Batalkan transaksi ini?')" style="display:contents">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="rpt-btn-del" title="Batalkan">
                                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                <polyline points="3 6 5 6 21 6" />
                                                <path d="M19 6l-1 14a2 2 0 01-2 2H8a2 2 0 01-2-2L5 6" />
                                                <path d="M10 11v6M14 11v6" />
                                                <path d="M9 6V4h6v2" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6">
                                <div class="rpt-empty">
                                    <div class="rpt-empty-icon">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#c8c5bc" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            <path d="M14 2H6a2 2 0 00-2 2v16a2 2 0 002 2h12a2 2 0 002-2V8z" />
                                            <polyline points="14 2 14 8 20 8" />
                                            <line x1="16" y1="13" x2="8" y2="13" />
                                            <line x1="16" y1="17" x2="8" y2="17" />
                                        </svg>
                                    </div>
                                    <p class="rpt-empty-text">Tidak ada transaksi untuk periode ini</p>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{-- Pagination hanya muncul saat mode filter custom (paginate), bukan saat ->get() biasa --}}
            @if($hasCustomFilter && method_exists($transactions, 'hasPages') && $transactions->hasPages())
            <div class="rpt-pagination-wrap">
                {{ $transactions->links() }}
            </div>
            @endif

        </div>
    </div>

    @endif

</div>

{{-- MODALS untuk detail transaksi --}}
@if($transactions->count())
@foreach($transactions as $trx)
<div class="modal fade" id="detailModal{{ $trx->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content" style="border:none;border-radius:16px">
            {{-- Header --}}
            <div class="modal-header" style="border:none;border-bottom:1px solid #f5f4f0;padding:24px 28px;background:#fff">
                <div>
                    <div style="font-size:11px;font-weight:700;letter-spacing:.6px;text-transform:uppercase;color:#a8a59e;margin-bottom:2px">Struk Transaksi</div>
                    <h5 class="modal-title" style="font-size:17px;font-weight:800;color:#1c1b18;margin:0;letter-spacing:-.3px">#{{ $trx->invoice_number }}</h5>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            {{-- Body --}}
            <div class="modal-body" style="padding:24px 28px">

                {{-- Info baris --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f5f4f0">
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#a8a59e;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px">Tanggal & Waktu</div>
                        <div style="font-size:13.5px;font-weight:600;color:#1c1b18">{{ $trx->created_at->translatedFormat('d F Y') }}</div>
                        <div style="font-size:12px;color:#a8a59e;margin-top:2px">{{ $trx->created_at->format('H:i:s') }}</div>
                    </div>
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#a8a59e;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px">Kasir</div>
                        <div style="font-size:13.5px;font-weight:600;color:#1c1b18">{{ $trx->user->name ?? '-' }}</div>
                    </div>
                </div>

                <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;margin-bottom:20px;padding-bottom:20px;border-bottom:1px solid #f5f4f0">
                    <div>
                        <div style="font-size:11px;font-weight:700;color:#a8a59e;letter-spacing:.5px;text-transform:uppercase;margin-bottom:4px">Metode Pembayaran</div>
                        <div style="font-size:13.5px;font-weight:600;color:#1c1b18">{{ $trx->payment_method }}</div>
                    </div>
                </div>

                {{-- Items list --}}
                <div style="margin-bottom:20px">
                    <div style="font-size:11px;font-weight:700;color:#a8a59e;letter-spacing:.5px;text-transform:uppercase;margin-bottom:12px">Item Pesanan</div>

                    @forelse($trx->details as $detail)
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;padding:12px;margin-bottom:8px;background:#faf9f7;border-radius:10px">
                        <div style="flex:1;min-width:0">
                            <div style="font-size:13.5px;font-weight:600;color:#1c1b18;margin-bottom:2px">
                                {{ $detail->product ? $detail->product->name : 'Produk Dihapus' }}
                            </div>
                            <div style="font-size:12px;color:#a8a59e">
                                {{ $detail->quantity }} × Rp {{ number_format($detail->price_at_time, 0, ',', '.') }}
                            </div>
                        </div>
                        <div style="text-align:right;margin-left:12px;white-space:nowrap">
                            <div style="font-size:13.5px;font-weight:700;color:#1c1b18">
                                Rp {{ number_format($detail->quantity * $detail->price_at_time, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                    @empty
                    <p style="font-size:13px;color:#a8a59e;font-style:italic">Tidak ada detail item.</p>
                    @endforelse
                </div>

                {{-- Summary --}}
                <div style="padding-top:20px;border-top:1.5px solid #f5f4f0">
                    <div style="display:flex;justify-content:space-between;margin-bottom:10px">
                        <span style="font-size:13px;color:#a8a59e;font-weight:600">Subtotal</span>
                        <span style="font-size:13px;font-weight:600;color:#1c1b18">Rp {{ number_format($trx->total_amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;margin-bottom:10px">
                        <span style="font-size:13px;color:#a8a59e;font-weight:600">Dibayar</span>
                        <span style="font-size:13px;font-weight:600;color:#1c1b18">Rp {{ number_format($trx->pay_amount, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex;justify-content:space-between;padding-top:10px;border-top:1px solid #f5f4f0">
                        <span style="font-size:13px;color:#a8a59e;font-weight:600">Kembalian</span>
                        <span style="font-size:14px;font-weight:700;color:#3d7a5e">Rp {{ number_format($trx->change_amount, 0, ',', '.') }}</span>
                    </div>
                </div>

            </div>

            {{-- Footer --}}
            <div class="modal-footer" style="border:none;border-top:1px solid #f5f4f0;padding:16px 28px;background:#faf9f7;gap:8px">
                <a href="{{ route('pos.print', $trx->id) }}" target="_blank" class="btn" style="background:#3d7a5e;color:#fff;border:none;border-radius:10px;font-weight:600;padding:10px 20px;text-decoration:none;display:inline-flex;align-items:center;gap:7px">
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <polyline points="6 9 6 2 18 2 18 9" />
                        <path d="M6 18H4a2 2 0 01-2-2v-5a2 2 0 012-2h16a2 2 0 012 2v5a2 2 0 01-2 2h-2" />
                        <rect x="6" y="14" width="12" height="8" />
                    </svg>
                    Cetak Struk
                </a>
                <button type="button" class="btn" style="background:#fff;border:1.5px solid #eeede8;color:#1c1b18;border-radius:10px;font-weight:600;padding:10px 20px" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endforeach
@endif

@endsection