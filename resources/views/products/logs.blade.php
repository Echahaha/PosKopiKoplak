@extends('dashboard')

@section('title', 'Riwayat Stok')

@section('content')

<style>
    .riwayat-wrapper * {
        box-sizing: border-box;
    }

    .riwayat-wrapper {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 4px 0 32px;
    }

    /* ── PAGE HEADER ── */
    .riwayat-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .riwayat-header h4 {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 2px;
    }

    .riwayat-header p {
        font-size: 13px;
        color: #7a8a7a;
        margin: 0;
    }

    .btn-kembali {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: white;
        border: 1px solid #e4e0d8;
        border-radius: 20px;
        padding: 8px 18px;
        font-size: 13px;
        font-weight: 600;
        color: #3a4a3a;
        text-decoration: none;
        transition: all 0.15s;
    }

    .btn-kembali:hover {
        background: #f0ede6;
        color: #1a1f1a;
        border-color: #d4cfc5;
        text-decoration: none;
    }

    /* ── CARD ── */
    .card-kk {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
    }

    /* ── TABLE ── */
    .table-kk thead th {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9aaa9a;
        font-weight: 700;
        padding: 13px 16px;
        background: #faf9f6;
        border-bottom: 1px solid #f0ede6;
        white-space: nowrap;
    }

    .table-kk tbody td {
        padding: 13px 16px;
        font-size: 13px;
        color: #3a4a3a;
        border-bottom: 1px solid #f5f4f0;
        vertical-align: middle;
    }

    .table-kk tbody tr:last-child td {
        border-bottom: none;
    }

    .table-kk tbody tr:hover td {
        background: #faf9f7;
    }

    /* ── DATE CELL ── */
    .date-day {
        font-size: 11px;
        color: #9aaa9a;
        display: block;
        margin-bottom: 2px;
    }

    .date-time {
        font-size: 14px;
        font-weight: 800;
        color: #1a1f1a;
    }

    /* ── PRODUCT NAME ── */
    .td-name {
        font-weight: 700;
        color: #1a1f1a;
    }

    /* ── BADGES ── */
    .badge-masuk {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 13px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    .badge-keluar {
        background: #fde8e8;
        color: #c53030;
        border: 1px solid #f5b8b8;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 13px;
        border-radius: 20px;
        display: inline-flex;
        align-items: center;
        gap: 5px;
    }

    /* ── AMOUNT ── */
    .amount-masuk {
        font-weight: 800;
        color: #3d7a5e;
        font-size: 14px;
    }

    .amount-keluar {
        font-weight: 800;
        color: #c53030;
        font-size: 14px;
    }

    /* ── KETERANGAN ── */
    .td-keterangan {
        font-size: 12px;
        color: #8a9a8a;
        font-style: italic;
        max-width: 220px;
    }

    /* ── EMPTY ── */
    .empty-row td {
        text-align: center;
        padding: 48px 16px;
        color: #9aaa9a;
        font-size: 13px;
    }

    .empty-icon {
        font-size: 18px;
        margin-bottom: 10px;
        display: block;
        opacity: 0.4;
    }

    /* ── PAGINATION ── */
    .pagination-kk {
        margin-top: 18px;
    }

    .pagination-kk .pagination {
        gap: 4px;
        margin: 0;
    }

    .pagination-kk .page-item {
        margin: 0;
    }

    .pagination-kk .page-link {
        border-radius: 10px;
        border: 1px solid #e4e0d8;
        color: #3d7a5e;
        font-size: 13px;
        font-weight: 600;
        padding: 8px 12px;
        background: white;
        transition: all 0.15s;
        display: flex;
        align-items: center;
        justify-content: center;
        min-width: 36px;
        height: 36px;
        line-height: 1;
    }

    .pagination-kk .page-link:hover {
        background: #e6f4ee;
        border-color: #b2dcc8;
        color: #2d6a4e;
    }

    .pagination-kk .page-item.active .page-link {
        background: #3d7a5e;
        border-color: #3d7a5e;
        color: white;
    }

    .pagination-kk .page-item.disabled .page-link {
        color: #c4cfc4;
        background: #faf9f6;
        border-color: #e4e0d8;
    }
</style>

<div class="riwayat-wrapper">

    {{-- ── PAGE HEADER ── --}}
    <div class="riwayat-header">
        <div>
            <h4>Riwayat Stok</h4>
            <p>Log keluar masuk barang Kopi Koplak</p>
        </div>
        <a href="{{ route('products.index') }}" class="btn-kembali">
            <i class="bi bi-arrow-left"></i> Kembali ke Inventori
        </a>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="card-kk">
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Tanggal & Waktu</th>
                        <th>Nama Produk</th>
                        <th>Tipe</th>
                        <th>Jumlah</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($logs as $log)
                    <tr>
                        <td style="padding-left:22px">
                            <span class="date-day">{{ $log->created_at->format('d M Y') }}</span>
                            <span class="date-time">{{ $log->created_at->format('H:i') }}</span>
                        </td>
                        <td class="td-name">{{ $log->product->name }}</td>
                        <td>
                            @if($log->type == 'in')
                            <span class="badge-masuk">
                                <i class="bi bi-arrow-down-circle-fill" style="font-size:10px"></i> Masuk
                            </span>
                            @else
                            <span class="badge-keluar">
                                <i class="bi bi-arrow-up-circle-fill" style="font-size:10px"></i> Keluar
                            </span>
                            @endif
                        </td>
                        <td>
                            <span class="{{ $log->type == 'in' ? 'amount-masuk' : 'amount-keluar' }}">
                                {{ $log->type == 'in' ? '+' : '−' }}{{ $log->amount }} {{ $log->product->unit }}
                            </span>
                        </td>
                        <td class="td-keterangan">{{ $log->reason ?? '—' }}</td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="5">
                            <span class="empty-icon">📦</span>
                            Belum ada riwayat stok.
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- ── PAGINATION ── --}}
    <div class="pagination-kk d-flex justify-content-center">
        {{ $logs->links('pagination::bootstrap-5') }}
    </div>

</div>

@endsection