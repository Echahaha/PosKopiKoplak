@extends('dashboard')

@section('title', 'Import Data Kasir Pintar')

@section('content')

<style>
    .sh-wrapper * { box-sizing: border-box; }

    .sh-wrapper {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 4px 0 32px;
    }

    /* ── PAGE HEADER ── */
    .sh-page-header { margin-bottom: 24px; }
    .sh-page-header h4 {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 2px;
    }
    .sh-page-header p {
        font-size: 13px;
        color: #7a8a7a;
        margin: 0;
    }

    /* ── ALERT ── */
    .alert-sh {
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
        animation: shFadeIn 0.25s ease;
    }
    .alert-sh.success { background: #e6f4ee; border: 1px solid #b2dcc8; color: #2d6a4e; }
    .alert-sh.error { background: #fde8e8; border: 1px solid #f5b8b8; color: #9b1c1c; }
    .btn-close-sh {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 14px;
        cursor: pointer;
        opacity: 0.7;
        color: inherit;
    }

    /* ── STAT CARDS ── */
    .sh-stats {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 14px;
        margin-bottom: 22px;
    }
    .sh-stat-card {
        background: white;
        border-radius: 16px;
        border: 1px solid #eceae4;
        padding: 18px 20px;
        transition: transform 0.2s, box-shadow 0.2s;
    }
    .sh-stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.06);
    }
    .sh-stat-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        margin-bottom: 12px;
    }
    .sh-icon-blue { background: #e6eefb; color: #2e5fa3; }
    .sh-icon-teal { background: #e6f4ee; color: #3d7a5e; }
    .sh-icon-amber { background: #fff3de; color: #d97706; }
    .sh-icon-rose { background: #fce7f3; color: #be185d; }
    .sh-stat-label {
        font-size: 11px;
        font-weight: 700;
        color: #9aaa9a;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 4px;
    }
    .sh-stat-value {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
    }
    .sh-stat-sub {
        font-size: 12px;
        color: #8a9a8a;
        margin-top: 2px;
    }

    /* ── UPLOAD ZONE ── */
    .sh-upload-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
        margin-bottom: 22px;
    }
    .sh-upload-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #f0ede6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .sh-upload-header-left { display: flex; align-items: center; gap: 10px; }
    .sh-upload-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: #e6eefb;
        color: #2e5fa3;
    }
    .sh-upload-header h5 { font-size: 14px; font-weight: 800; color: #1a1f1a; margin: 0; }
    .sh-upload-body { padding: 22px; }

    .sh-dropzone {
        border: 2px dashed #d4d0c8;
        border-radius: 14px;
        padding: 40px 20px;
        text-align: center;
        cursor: pointer;
        transition: all 0.2s;
        background: #faf9f6;
        position: relative;
    }
    .sh-dropzone:hover,
    .sh-dropzone.dragover {
        border-color: #3d7a5e;
        background: #f0f9f4;
    }
    .sh-dropzone-icon {
        width: 56px;
        height: 56px;
        border-radius: 50%;
        background: #e6f4ee;
        color: #3d7a5e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 22px;
        margin: 0 auto 14px;
        transition: transform 0.2s;
    }
    .sh-dropzone:hover .sh-dropzone-icon {
        transform: scale(1.08);
    }
    .sh-dropzone h6 {
        font-size: 14px;
        font-weight: 700;
        color: #1a1f1a;
        margin-bottom: 4px;
    }
    .sh-dropzone p {
        font-size: 12.5px;
        color: #8a9a8a;
        margin: 0;
    }
    .sh-dropzone input[type="file"] {
        position: absolute;
        inset: 0;
        opacity: 0;
        cursor: pointer;
    }
    .sh-file-info {
        display: none;
        align-items: center;
        gap: 12px;
        background: #e6f4ee;
        border: 1px solid #b2dcc8;
        border-radius: 12px;
        padding: 12px 16px;
        margin-top: 14px;
        animation: shFadeIn 0.2s ease;
    }
    .sh-file-info.show { display: flex; }
    .sh-file-info-icon {
        width: 36px;
        height: 36px;
        border-radius: 10px;
        background: #3d7a5e;
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 16px;
        flex-shrink: 0;
    }
    .sh-file-name { font-size: 13px; font-weight: 700; color: #2d6a4e; }
    .sh-file-size { font-size: 11.5px; color: #5a8a6a; }
    .sh-file-remove {
        margin-left: auto;
        background: none;
        border: none;
        color: #c53030;
        cursor: pointer;
        font-size: 16px;
        opacity: 0.7;
        transition: opacity 0.15s;
    }
    .sh-file-remove:hover { opacity: 1; }

    /* ── PREVIEW TABLE ── */
    .sh-preview {
        display: none;
        margin-top: 18px;
        animation: shFadeIn 0.25s ease;
    }
    .sh-preview.show { display: block; }
    .sh-preview-title {
        font-size: 12px;
        font-weight: 700;
        color: #7a8a7a;
        text-transform: uppercase;
        letter-spacing: 0.4px;
        margin-bottom: 10px;
    }
    .sh-preview-table {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0;
        border-radius: 10px;
        overflow: hidden;
        border: 1px solid #eceae4;
        font-size: 12.5px;
    }
    .sh-preview-table thead th {
        background: #faf9f6;
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9aaa9a;
        font-weight: 700;
        padding: 10px 14px;
        border-bottom: 1px solid #eceae4;
        white-space: nowrap;
    }
    .sh-preview-table tbody td {
        padding: 9px 14px;
        color: #3a4a3a;
        border-bottom: 1px solid #f5f4f0;
    }
    .sh-preview-table tbody tr:last-child td { border-bottom: none; }
    .sh-preview-more {
        text-align: center;
        padding: 10px;
        font-size: 12px;
        color: #9aaa9a;
        font-style: italic;
    }

    .sh-btn-actions {
        display: flex;
        gap: 10px;
        margin-top: 18px;
        flex-wrap: wrap;
    }
    .btn-sh-import {
        background: #3d7a5e;
        color: white;
        border: none;
        border-radius: 24px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
    }
    .btn-sh-import:hover { background: #2d6a4e; transform: translateY(-1px); }
    .btn-sh-import:disabled { background: #c4d4cc; cursor: not-allowed; transform: none; }

    .btn-sh-reset {
        background: white;
        color: #c53030;
        border: 1px solid #f5b8b8;
        border-radius: 24px;
        padding: 10px 24px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        transition: all 0.15s;
    }
    .btn-sh-reset:hover { background: #fde8e8; }

    /* ── DATA TABLE ── */
    .sh-data-card {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
    }
    .sh-data-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #f0ede6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .sh-data-header-left { display: flex; align-items: center; gap: 10px; }
    .sh-data-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
        background: #e6f4ee;
        color: #3d7a5e;
    }
    .sh-data-header h5 { font-size: 14px; font-weight: 800; color: #1a1f1a; margin: 0; }

    .sh-filter-bar {
        display: flex;
        gap: 10px;
        padding: 14px 22px;
        background: #faf9f6;
        border-bottom: 1px solid #f0ede6;
        flex-wrap: wrap;
    }
    .sh-filter-input, .sh-filter-select {
        border: 1px solid #e4e0d8;
        border-radius: 10px;
        padding: 7px 12px;
        font-size: 12.5px;
        color: #3a4a3a;
        background: white;
        transition: border-color 0.15s, box-shadow 0.15s;
    }
    .sh-filter-input:focus, .sh-filter-select:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61,122,94,0.12);
        outline: none;
    }
    .sh-filter-input { flex: 1; min-width: 180px; }

    .sh-table thead th {
        font-size: 10.5px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #9aaa9a;
        font-weight: 700;
        padding: 12px 16px;
        background: #faf9f6;
        border-bottom: 1px solid #f0ede6;
        white-space: nowrap;
    }
    .sh-table tbody td {
        padding: 11px 16px;
        font-size: 13px;
        color: #3a4a3a;
        border-bottom: 1px solid #f5f4f0;
        vertical-align: middle;
    }
    .sh-table tbody tr:last-child td { border-bottom: none; }
    .sh-table tbody tr:hover td { background: #faf9f7; }

    .td-name-sh { font-weight: 700; color: #1a1f1a; }

    .badge-matched {
        background: #e6f4ee; color: #3d7a5e; border: 1px solid #b2dcc8;
        font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;
    }
    .badge-unmatched {
        background: #fff3de; color: #d97706; border: 1px solid #f6d860;
        font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;
    }
    .badge-excluded {
        background: #f5f4f0; color: #9aaa9a; border: 1px solid #e4e0d8;
        font-size: 11px; font-weight: 700; padding: 3px 10px; border-radius: 20px;
    }

    .sh-empty {
        text-align: center;
        padding: 40px 20px;
        color: #9aaa9a;
    }
    .sh-empty i {
        font-size: 36px;
        display: block;
        margin-bottom: 12px;
        color: #d4d0c8;
    }
    .sh-empty h6 { font-size: 14px; font-weight: 700; color: #7a8a7a; margin-bottom: 4px; }
    .sh-empty p { font-size: 12.5px; margin: 0; }

    /* ── PAGINATION ── */
    .sh-pagination {
        padding: 14px 22px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-top: 1px solid #f0ede6;
        flex-wrap: wrap;
        gap: 10px;
    }
    .sh-pagination-info {
        font-size: 12.5px;
        color: #8a9a8a;
    }
    .sh-pagination .pagination {
        margin: 0;
    }
    .sh-pagination .page-link {
        border: 1px solid #e4e0d8;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 600;
        color: #5a6a5a;
        padding: 6px 12px;
        margin: 0 2px;
        transition: all 0.15s;
    }
    .sh-pagination .page-link:hover {
        background: #e6f4ee;
        border-color: #b2dcc8;
        color: #3d7a5e;
    }
    .sh-pagination .page-item.active .page-link {
        background: #3d7a5e;
        border-color: #3d7a5e;
        color: white;
    }
    .sh-pagination .page-item.disabled .page-link {
        color: #c4ccc4;
        background: #faf9f6;
    }

    /* ── FORMAT KOLOM ── */
    .sh-format-info {
        background: #f5f4f0;
        border: 1px solid #eceae4;
        border-radius: 12px;
        padding: 14px 18px;
        margin-top: 16px;
    }
    .sh-format-info h6 {
        font-size: 12px;
        font-weight: 700;
        color: #7a8a7a;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 8px;
    }
    .sh-format-cols {
        display: flex;
        flex-wrap: wrap;
        gap: 6px;
    }
    .sh-format-col {
        background: white;
        border: 1px solid #e4e0d8;
        border-radius: 8px;
        padding: 4px 10px;
        font-size: 11.5px;
        font-weight: 600;
        color: #3a4a3a;
        font-family: 'Courier New', monospace;
    }
    .sh-format-col.required {
        border-color: #b2dcc8;
        background: #e6f4ee;
        color: #2d6a4e;
    }

    /* ── MODAL ── */
    .sh-modal-backdrop {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(0,0,0,0.5);
        z-index: 2100;
        align-items: center;
        justify-content: center;
        padding: 20px;
    }
    .sh-modal-backdrop.show { display: flex; }
    .sh-modal-box {
        background: white;
        border-radius: 18px;
        padding: 28px;
        max-width: 380px;
        width: 100%;
        text-align: center;
        animation: shModalIn 0.2s ease-out;
    }
    @keyframes shModalIn {
        from { opacity: 0; transform: scale(0.95); }
        to { opacity: 1; transform: scale(1); }
    }
    .sh-modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #fde8e8;
        color: #c53030;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 14px;
    }
    .sh-modal-title { font-size: 16px; font-weight: 800; color: #1a1f1a; margin-bottom: 6px; }
    .sh-modal-text { font-size: 13px; color: #7a8a7a; margin-bottom: 20px; }
    .sh-modal-actions { display: flex; gap: 10px; }
    .sh-modal-btn {
        flex: 1;
        padding: 10px;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        transition: background 0.2s;
    }
    .sh-modal-btn-cancel { background: #f5f4f0; color: #5a6a5a; }
    .sh-modal-btn-cancel:hover { background: #eceae4; }
    .sh-modal-btn-confirm { background: #c53030; color: white; }
    .sh-modal-btn-confirm:hover { background: #9b1c1c; }

    /* ── SPINNER ── */
    .sh-spinner {
        display: none;
        width: 18px;
        height: 18px;
        border: 2.5px solid rgba(255,255,255,0.3);
        border-top-color: white;
        border-radius: 50%;
        animation: shSpin 0.6s linear infinite;
    }
    @keyframes shSpin {
        to { transform: rotate(360deg); }
    }

    @keyframes shFadeIn {
        from { opacity: 0; transform: translateY(4px); }
        to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 576px) {
        .sh-stats { grid-template-columns: 1fr 1fr; }
        .sh-btn-actions { flex-direction: column; }
    }
</style>

<div class="sh-wrapper">

    {{-- ── PAGE HEADER ── --}}
    <div class="sh-page-header">
        <h4>Import Data Kasir Pintar</h4>
        <p>Upload file CSV data penjualan historis dari aplikasi Kasir Pintar untuk digunakan sebagai dataset prediksi LSTM.</p>
    </div>

    {{-- ── ALERTS ── --}}
    @if(session('success'))
    <div class="alert-sh success" id="alertSuccess">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
        <button class="btn-close-sh" onclick="document.getElementById('alertSuccess').remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert-sh error" id="alertError">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ session('error') }}
        <button class="btn-close-sh" onclick="document.getElementById('alertError').remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert-sh error" id="alertValidasi">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ $errors->first() }}
        <button class="btn-close-sh" onclick="document.getElementById('alertValidasi').remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif

    {{-- ── STAT CARDS ── --}}
    <div class="sh-stats">
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-icon-blue"><i class="bi bi-database"></i></div>
            <div class="sh-stat-label">Total Data</div>
            <div class="sh-stat-value">{{ number_format($stats['total_rows']) }}</div>
            <div class="sh-stat-sub">baris data historis</div>
        </div>
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-icon-amber"><i class="bi bi-calendar-range"></i></div>
            <div class="sh-stat-label">Rentang Tanggal</div>
            <div class="sh-stat-value" style="font-size:16px;">{{ $stats['tanggal_pertama'] }}</div>
            <div class="sh-stat-sub">s/d {{ $stats['tanggal_terakhir'] }}</div>
        </div>
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-icon-rose"><i class="bi bi-cup-hot"></i></div>
            <div class="sh-stat-label">Produk Unik</div>
            <div class="sh-stat-value">{{ $stats['produk_unik'] }}</div>
            <div class="sh-stat-sub">jenis produk</div>
        </div>
        <div class="sh-stat-card">
            <div class="sh-stat-icon sh-icon-teal"><i class="bi bi-link-45deg"></i></div>
            <div class="sh-stat-label">Status Matching</div>
            <div class="sh-stat-value" style="font-size:16px; color:#3d7a5e;">{{ $stats['total_matched'] }} <span style="color:#9aaa9a; font-size:13px; font-weight:600;">matched</span></div>
            <div class="sh-stat-sub">{{ $stats['total_unmatched'] }} belum cocok</div>
        </div>
    </div>

    {{-- ── UPLOAD CARD ── --}}
    <div class="sh-upload-card">
        <div class="sh-upload-header">
            <div class="sh-upload-header-left">
                <div class="sh-upload-header-icon"><i class="bi bi-cloud-arrow-up"></i></div>
                <h5>Upload File CSV</h5>
            </div>
        </div>
        <div class="sh-upload-body">
            <form action="{{ route('sales-history.upload') }}" method="POST" enctype="multipart/form-data" id="uploadForm">
                @csrf

                {{-- Drop zone --}}
                <div class="sh-dropzone" id="dropZone">
                    <div class="sh-dropzone-icon">
                        <i class="bi bi-file-earmark-spreadsheet"></i>
                    </div>
                    <h6>Drag & drop file CSV di sini</h6>
                    <p>atau klik untuk memilih file &middot; Maks. 10 MB &middot; Format: .csv</p>
                    <input type="file" name="csv_file" id="csvFileInput" accept=".csv">
                </div>

                {{-- File info setelah dipilih --}}
                <div class="sh-file-info" id="fileInfo">
                    <div class="sh-file-info-icon"><i class="bi bi-file-earmark-check"></i></div>
                    <div>
                        <div class="sh-file-name" id="fileName">-</div>
                        <div class="sh-file-size" id="fileSize">-</div>
                    </div>
                    <button type="button" class="sh-file-remove" id="fileRemoveBtn" title="Hapus file">
                        <i class="bi bi-x-lg"></i>
                    </button>
                </div>

                {{-- Preview --}}
                <div class="sh-preview" id="previewSection">
                    <div class="sh-preview-title">Preview 5 baris pertama</div>
                    <div class="table-responsive">
                        <table class="sh-preview-table" id="previewTable">
                            <thead id="previewHead"></thead>
                            <tbody id="previewBody"></tbody>
                        </table>
                    </div>
                    <div class="sh-preview-more" id="previewMore"></div>
                </div>

                {{-- Format info --}}
                <div class="sh-format-info">
                    <h6>Format Kolom CSV yang Diharapkan</h6>
                    <div class="sh-format-cols">
                        <span class="sh-format-col required">tanggal *</span>
                        <span class="sh-format-col">Kode Barang</span>
                        <span class="sh-format-col required">Nama Barang *</span>
                        <span class="sh-format-col">kategori</span>
                        <span class="sh-format-col required">qty_terjual *</span>
                        <span class="sh-format-col required">pendapatan *</span>
                    </div>
                </div>

                {{-- Action buttons --}}
                <div class="sh-btn-actions">
                    <button type="submit" class="btn-sh-import" id="importBtn" disabled>
                        <i class="bi bi-cloud-arrow-up" id="importIcon"></i>
                        <span class="sh-spinner" id="importSpinner"></span>
                        <span id="importText">Import ke Database</span>
                    </button>

                    @if($stats['total_rows'] > 0)
                    <button type="button" class="btn-sh-reset" id="resetBtn">
                        <i class="bi bi-trash3"></i>
                        Reset Semua Data ({{ number_format($stats['total_rows']) }})
                    </button>
                    @endif
                </div>
            </form>
        </div>
    </div>

    {{-- ── DATA TABLE ── --}}
    <div class="sh-data-card">
        <div class="sh-data-header">
            <div class="sh-data-header-left">
                <div class="sh-data-header-icon"><i class="bi bi-table"></i></div>
                <h5>Data Penjualan Historis</h5>
            </div>
        </div>

        {{-- Filter --}}
        <form class="sh-filter-bar" method="GET" action="{{ route('sales-history.index') }}">
            <input type="text" name="search" class="sh-filter-input" placeholder="Cari nama barang..." value="{{ request('search') }}">
            <select name="status" class="sh-filter-select" onchange="this.form.submit()">
                <option value="">Semua Status</option>
                <option value="matched" {{ request('status') == 'matched' ? 'selected' : '' }}>Matched</option>
                <option value="unmatched" {{ request('status') == 'unmatched' ? 'selected' : '' }}>Unmatched</option>
                <option value="excluded" {{ request('status') == 'excluded' ? 'selected' : '' }}>Excluded</option>
            </select>
        </form>

        <div class="table-responsive">
            <table class="table sh-table mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Tanggal</th>
                        <th>Nama Barang</th>
                        <th>Kategori</th>
                        <th class="text-center">Qty</th>
                        <th class="text-end">Pendapatan</th>
                        <th class="text-center">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($data as $row)
                    <tr>
                        <td style="padding-left:22px">{{ \Carbon\Carbon::parse($row->tanggal)->format('d M Y') }}</td>
                        <td>
                            <span class="td-name-sh">{{ $row->nama_barang }}</span>
                            @if($row->kode_barang_asal)
                            <br><span style="font-size:11px; color:#9aaa9a;">{{ $row->kode_barang_asal }}</span>
                            @endif
                        </td>
                        <td>{{ $row->kategori ?: '-' }}</td>
                        <td class="text-center" style="font-weight:700;">{{ $row->qty_terjual }}</td>
                        <td class="text-end">Rp {{ number_format($row->pendapatan, 0, ',', '.') }}</td>
                        <td class="text-center">
                            @if($row->matched_status === 'matched')
                                <span class="badge-matched">Matched</span>
                            @elseif($row->matched_status === 'excluded')
                                <span class="badge-excluded">Excluded</span>
                            @else
                                <span class="badge-unmatched">Unmatched</span>
                            @endif
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6">
                            <div class="sh-empty">
                                <i class="bi bi-inbox"></i>
                                <h6>Belum Ada Data</h6>
                                <p>Upload file CSV dari Kasir Pintar untuk mengisi data historis.</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($data->hasPages())
        <div class="sh-pagination">
            <div class="sh-pagination-info">
                Menampilkan {{ $data->firstItem() }}–{{ $data->lastItem() }} dari {{ number_format($data->total()) }} data
            </div>
            {{ $data->links('pagination::bootstrap-5') }}
        </div>
        @endif
    </div>

</div>

{{-- ── MODAL KONFIRMASI RESET ── --}}
<div class="sh-modal-backdrop" id="resetModal">
    <div class="sh-modal-box">
        <div class="sh-modal-icon">
            <i class="bi bi-exclamation-triangle-fill"></i>
        </div>
        <div class="sh-modal-title">Hapus Semua Data?</div>
        <div class="sh-modal-text">
            Seluruh <strong>{{ number_format($stats['total_rows']) }} baris</strong> data historis akan dihapus permanen. Data yang sudah dihapus tidak bisa dikembalikan.
        </div>
        <div class="sh-modal-actions">
            <button type="button" class="sh-modal-btn sh-modal-btn-cancel" id="resetCancelBtn">Batal</button>
            <button type="button" class="sh-modal-btn sh-modal-btn-confirm" id="resetConfirmBtn">Ya, Hapus Semua</button>
        </div>
    </div>
</div>

{{-- Form reset (hidden) --}}
<form action="{{ route('sales-history.destroy') }}" method="POST" id="resetForm" style="display:none;">
    @csrf
    @method('DELETE')
</form>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const dropZone    = document.getElementById('dropZone');
    const fileInput   = document.getElementById('csvFileInput');
    const fileInfo    = document.getElementById('fileInfo');
    const fileName    = document.getElementById('fileName');
    const fileSize    = document.getElementById('fileSize');
    const fileRemove  = document.getElementById('fileRemoveBtn');
    const previewSec  = document.getElementById('previewSection');
    const previewHead = document.getElementById('previewHead');
    const previewBody = document.getElementById('previewBody');
    const previewMore = document.getElementById('previewMore');
    const importBtn   = document.getElementById('importBtn');
    const importIcon  = document.getElementById('importIcon');
    const importSpin  = document.getElementById('importSpinner');
    const importText  = document.getElementById('importText');
    const uploadForm  = document.getElementById('uploadForm');

    // ── Drag & drop ──
    ['dragenter', 'dragover'].forEach(evt => {
        dropZone.addEventListener(evt, function (e) {
            e.preventDefault();
            dropZone.classList.add('dragover');
        });
    });
    ['dragleave', 'drop'].forEach(evt => {
        dropZone.addEventListener(evt, function (e) {
            e.preventDefault();
            dropZone.classList.remove('dragover');
        });
    });
    dropZone.addEventListener('drop', function (e) {
        const files = e.dataTransfer.files;
        if (files.length > 0) {
            fileInput.files = files;
            handleFileSelected(files[0]);
        }
    });

    // ── File input change ──
    fileInput.addEventListener('change', function () {
        if (this.files.length > 0) {
            handleFileSelected(this.files[0]);
        }
    });

    // ── Remove file ──
    fileRemove.addEventListener('click', function () {
        fileInput.value = '';
        fileInfo.classList.remove('show');
        previewSec.classList.remove('show');
        importBtn.disabled = true;
    });

    function handleFileSelected(file) {
        fileName.textContent = file.name;
        fileSize.textContent = formatFileSize(file.size);
        fileInfo.classList.add('show');
        importBtn.disabled = false;
        parseCSVPreview(file);
    }

    function formatFileSize(bytes) {
        if (bytes < 1024) return bytes + ' B';
        if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
        return (bytes / 1048576).toFixed(1) + ' MB';
    }

    // ── CSV Preview (client-side, first 5 rows) ──
    function parseCSVPreview(file) {
        const reader = new FileReader();
        reader.onload = function (e) {
            const text = e.target.result;
            const lines = text.split(/\r?\n/).filter(l => l.trim() !== '');

            if (lines.length < 2) {
                previewSec.classList.remove('show');
                return;
            }

            const headers = parseCSVLine(lines[0]);
            let headHtml = '<tr>';
            headers.forEach(h => { headHtml += '<th>' + escapeHtml(h.trim()) + '</th>'; });
            headHtml += '</tr>';
            previewHead.innerHTML = headHtml;

            let bodyHtml = '';
            const maxRows = Math.min(lines.length - 1, 5);
            for (let i = 1; i <= maxRows; i++) {
                const cols = parseCSVLine(lines[i]);
                bodyHtml += '<tr>';
                cols.forEach(c => { bodyHtml += '<td>' + escapeHtml(c.trim()) + '</td>'; });
                bodyHtml += '</tr>';
            }
            previewBody.innerHTML = bodyHtml;

            const totalDataRows = lines.length - 1;
            if (totalDataRows > 5) {
                previewMore.textContent = '... dan ' + (totalDataRows - 5) + ' baris lainnya';
            } else {
                previewMore.textContent = '';
            }

            previewSec.classList.add('show');
        };
        reader.readAsText(file);
    }

    function parseCSVLine(line) {
        const result = [];
        let current = '';
        let inQuotes = false;
        for (let i = 0; i < line.length; i++) {
            const ch = line[i];
            if (inQuotes) {
                if (ch === '"') {
                    if (i + 1 < line.length && line[i + 1] === '"') {
                        current += '"';
                        i++;
                    } else {
                        inQuotes = false;
                    }
                } else {
                    current += ch;
                }
            } else {
                if (ch === '"') {
                    inQuotes = true;
                } else if (ch === ',') {
                    result.push(current);
                    current = '';
                } else {
                    current += ch;
                }
            }
        }
        result.push(current);
        return result;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.textContent = str;
        return div.innerHTML;
    }

    // ── Form submit → loading state ──
    uploadForm.addEventListener('submit', function () {
        importBtn.disabled = true;
        importIcon.style.display = 'none';
        importSpin.style.display = 'inline-block';
        importText.textContent = 'Mengimpor data...';
    });

    // ── Reset modal ──
    const resetBtn = document.getElementById('resetBtn');
    const resetModal = document.getElementById('resetModal');
    const resetCancel = document.getElementById('resetCancelBtn');
    const resetConfirm = document.getElementById('resetConfirmBtn');
    const resetForm = document.getElementById('resetForm');

    if (resetBtn) {
        resetBtn.addEventListener('click', function () {
            resetModal.classList.add('show');
        });
    }
    if (resetCancel) {
        resetCancel.addEventListener('click', function () {
            resetModal.classList.remove('show');
        });
    }
    if (resetModal) {
        resetModal.addEventListener('click', function (e) {
            if (e.target === resetModal) resetModal.classList.remove('show');
        });
    }
    if (resetConfirm) {
        resetConfirm.addEventListener('click', function () {
            resetForm.submit();
        });
    }
});
</script>
@endpush
