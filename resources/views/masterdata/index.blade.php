@extends('dashboard')

@section('title', 'Master Data')

@section('content')

<style>
    .md-wrapper * {
        box-sizing: border-box;
    }

    .md-wrapper {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 4px 0 32px;
    }

    /* ── PAGE HEADER ── */
    .md-page-header {
        margin-bottom: 24px;
    }
    .md-page-header h4 {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 2px;
    }
    .md-page-header p {
        font-size: 13px;
        color: #7a8a7a;
        margin: 0;
    }

    /* ── ALERT ── */
    .alert-kk {
        border-radius: 14px;
        font-size: 13px;
        font-weight: 600;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }
    .alert-kk.success { background: #e6f4ee; border: 1px solid #b2dcc8; color: #2d6a4e; }
    .alert-kk.error { background: #fde8e8; border: 1px solid #f5b8b8; color: #9b1c1c; }
    .btn-close-kk {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 14px;
        cursor: pointer;
        opacity: 0.7;
        color: inherit;
    }

    /* ── TABS ── */
    .md-tabs {
        display: flex;
        gap: 6px;
        background: #eceae1;
        border-radius: 16px;
        padding: 5px;
        margin-bottom: 18px;
        width: fit-content;
        overflow-x: auto;
        max-width: 100%;
    }
    .md-tab-btn {
        display: flex;
        align-items: center;
        gap: 8px;
        border: none;
        background: transparent;
        border-radius: 12px;
        padding: 9px 16px;
        font-size: 13px;
        font-weight: 700;
        color: #7a8a7a;
        cursor: pointer;
        transition: all 0.18s;
        white-space: nowrap;
    }
    .md-tab-btn .md-tab-icon {
        width: 24px;
        height: 24px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        background: transparent;
        color: inherit;
        transition: all 0.18s;
    }
    .md-tab-btn.active {
        background: white;
        color: #1a1f1a;
        box-shadow: 0 2px 8px rgba(0,0,0,0.06);
    }
    .md-tab-btn.active .md-tab-icon { background: #e6f4ee; color: #3d7a5e; }
    .md-tab-count {
        font-size: 10.5px;
        font-weight: 700;
        background: #f5f4f0;
        color: #9aaa9a;
        border-radius: 20px;
        padding: 1px 8px;
        line-height: 1.5;
    }
    .md-tab-btn.active .md-tab-count { background: #f5f4f0; color: #5a6a5a; }

    .md-tab-panel { display: none; }
    .md-tab-panel.active { display: block; animation: mdFadeIn 0.18s ease; }
    @keyframes mdFadeIn {
        from { opacity: 0; transform: translateY(3px); }
        to { opacity: 1; transform: translateY(0); }
    }

    /* ── CARD ── */
    .card-kk {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
    }
    .card-kk-header {
        padding: 18px 22px 14px;
        border-bottom: 1px solid #f0ede6;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        flex-wrap: wrap;
    }
    .card-kk-header-left { display: flex; align-items: center; gap: 10px; }
    .card-kk-header-icon {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 15px;
        flex-shrink: 0;
    }
    .icon-teal-soft { background: #e6f4ee; color: #3d7a5e; }
    .icon-brown-soft { background: #f0ede6; color: #7a6a50; }
    .icon-amber-soft { background: #fff3de; color: #d97706; }
    .icon-blue-soft { background: #e6eefb; color: #2e5fa3; }
    .card-kk-header h5 { font-size: 14px; font-weight: 800; color: #1a1f1a; margin: 0; }

    .btn-tambah-kk {
        background: #3d7a5e;
        color: white;
        border: none;
        border-radius: 24px;
        padding: 8px 18px;
        font-size: 12.5px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 6px;
        cursor: pointer;
        transition: background 0.2s, transform 0.15s;
        text-decoration: none;
    }
    .btn-tambah-kk:hover { background: #2d6a4e; color: white; transform: translateY(-1px); }

    /* ── TABLE ── */
    .table-kk thead th {
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
    .table-kk tbody td {
        padding: 12px 16px;
        font-size: 13px;
        color: #3a4a3a;
        border-bottom: 1px solid #f5f4f0;
        vertical-align: middle;
    }
    .table-kk tbody tr:last-child td { border-bottom: none; }
    .table-kk tbody tr:hover td { background: #faf9f7; }
    .md-cat-row:hover td { background: #e6f4ee; }

    /* ── FILTER KATEGORI AKTIF (chip) ── */
    .md-filter-chip {
        display: none;
        align-items: center;
        gap: 8px;
        background: #e6f4ee;
        border: 1px solid #b2dcc8;
        color: #2d6a4e;
        font-size: 12.5px;
        font-weight: 700;
        padding: 7px 14px;
        border-radius: 20px;
        margin-bottom: 14px;
        width: fit-content;
    }
    .md-filter-chip.show { display: flex; }
    .md-filter-chip button {
        background: none;
        border: none;
        color: #2d6a4e;
        cursor: pointer;
        display: flex;
        align-items: center;
        font-size: 13px;
    }
    .md-filter-select-wrap {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 0;
    }
    .md-filter-select {
        border: 1px solid #e4e0d8;
        border-radius: 10px;
        padding: 7px 12px;
        font-size: 12.5px;
        color: #3a4a3a;
        background: #faf9f6;
    }
    .td-name { font-weight: 700; color: #1a1f1a; }
    .td-muted { color: #8a9a8a; font-size: 12px; }

    .badge-menu {
        background: #e6f4ee; color: #3d7a5e; border: 1px solid #b2dcc8;
        font-size: 11px; font-weight: 600; padding: 4px 11px; border-radius: 20px;
    }
    .badge-stok-ok {
        background: #e6f4ee; color: #3d7a5e; border: 1px solid #b2dcc8;
        font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 20px;
    }
    .badge-stok-danger {
        background: #fde8e8; color: #c53030; border: 1px solid #f5b8b8;
        font-size: 11.5px; font-weight: 700; padding: 4px 12px; border-radius: 20px;
    }
    .badge-role-owner {
        background: #fff3de; color: #d97706; border: 1px solid #f6d860;
        font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 20px;
    }
    .badge-role-barista {
        background: #e6eefb; color: #2e5fa3; border: 1px solid #b8cdf5;
        font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 20px;
    }
    .badge-active { background: #e6f4ee; color: #3d7a5e; border: 1px solid #b2dcc8; font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 20px; }
    .badge-inactive { background: #f5f4f0; color: #9aaa9a; border: 1px solid #e4e0d8; font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 20px; }

    .btn-aksi {
        width: 32px; height: 32px; border-radius: 9px;
        border: 1px solid #eceae4; background: white;
        display: inline-flex; align-items: center; justify-content: center;
        font-size: 13px; cursor: pointer; transition: all 0.15s; text-decoration: none;
    }
    .btn-aksi-edit { color: #3d7a5e; }
    .btn-aksi-edit:hover { background: #e6f4ee; border-color: #b2dcc8; color: #2d6a4e; }
    .btn-aksi-hapus { color: #c53030; }
    .btn-aksi-hapus:hover { background: #fde8e8; border-color: #f5b8b8; color: #9b1c1c; }
    .btn-aksi-resep { color: #d97706; }
    .btn-aksi-resep:hover { background: #fff3de; border-color: #f6d860; }

    .empty-row td { text-align: center; padding: 32px 16px; color: #9aaa9a; font-size: 13px; font-style: italic; }

    /* ── MODAL ── */
    .modal-kk .modal-content { border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
    .modal-kk .modal-header { border-bottom: 1px solid #f0ede6; padding: 20px 24px 14px; }
    .modal-kk .modal-title { font-size: 16px; font-weight: 800; color: #1a1f1a; }
    .modal-kk .modal-body { padding: 20px 24px; }
    .modal-kk .modal-footer { border-top: 1px solid #f0ede6; padding: 14px 24px 20px; }
    .form-label-kk { font-size: 11.5px; font-weight: 700; color: #6a7a6a; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 5px; }
    .form-control-kk, .form-select-kk {
        border: 1px solid #e4e0d8; border-radius: 10px; padding: 9px 13px; font-size: 13px;
        color: #1a1f1a; background: #faf9f6;
        transition: border-color 0.15s, box-shadow 0.15s; width: 100%;
    }
    .form-control-kk:focus, .form-select-kk:focus {
        border-color: #3d7a5e; box-shadow: 0 0 0 3px rgba(61,122,94,0.12); background: white; outline: none;
    }
    .btn-modal-batal {
        background: #f5f4f0; border: 1px solid #e4e0d8; border-radius: 20px; padding: 8px 22px;
        font-size: 13px; font-weight: 700; color: #5a6a5a; cursor: pointer; transition: background 0.15s;
    }
    .btn-modal-batal:hover { background: #eceae4; }
    .btn-modal-simpan {
        background: #3d7a5e; border: none; border-radius: 20px; padding: 8px 22px;
        font-size: 13px; font-weight: 700; color: white; cursor: pointer; transition: background 0.15s;
    }
    .btn-modal-simpan:hover { background: #2d6a4e; }

    @media (max-width: 576px) {
        .md-tabs { width: 100%; }
        .md-tab-btn { flex: 1; justify-content: center; }
    }
</style>

<div class="md-wrapper">

    {{-- ── PAGE HEADER ── --}}
    <div class="md-page-header">
        <h4>Master Data</h4>
        <p>Kelola data inti aplikasi: kategori, bahan baku, produk, resep, dan pengguna.</p>
    </div>

    {{-- ── ALERTS ── --}}
    @if(session('success'))
    <div class="alert-kk success" id="alertSuccess">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
        <button class="btn-close-kk" onclick="document.getElementById('alertSuccess').remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
    @if(session('error'))
    <div class="alert-kk error" id="alertError">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ session('error') }}
        <button class="btn-close-kk" onclick="document.getElementById('alertError').remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif
    @if($errors->any())
    <div class="alert-kk error" id="alertValidasi">
        <i class="bi bi-exclamation-triangle-fill"></i>
        {{ $errors->first() }}
        <button class="btn-close-kk" onclick="document.getElementById('alertValidasi').remove()"><i class="bi bi-x-lg"></i></button>
    </div>
    @endif

    {{-- ── TAB SWITCHER ── --}}
    <div class="md-tabs" role="tablist">
        <button type="button" class="md-tab-btn active" data-tab="kategori" role="tab">
            <span class="md-tab-icon"><i class="bi bi-tags"></i></span>
            Kategori
            <span class="md-tab-count">{{ $categories->count() }}</span>
        </button>
        <button type="button" class="md-tab-btn" data-tab="bahan" role="tab">
            <span class="md-tab-icon"><i class="bi bi-box-seam"></i></span>
            Bahan Baku
            <span class="md-tab-count">{{ $ingredients->count() }}</span>
        </button>
        <button type="button" class="md-tab-btn" data-tab="produk" role="tab">
            <span class="md-tab-icon"><i class="bi bi-cup-hot"></i></span>
            Produk
            <span class="md-tab-count">{{ $menus->count() }}</span>
        </button>
        <button type="button" class="md-tab-btn" data-tab="resep" role="tab">
            <span class="md-tab-icon"><i class="bi bi-journal-text"></i></span>
            Resep
        </button>
        <button type="button" class="md-tab-btn" data-tab="user" role="tab">
            <span class="md-tab-icon"><i class="bi bi-people"></i></span>
            User
            <span class="md-tab-count">{{ $users->count() }}</span>
        </button>
    </div>

    {{-- ════════════════════════════════════
         PANEL: KATEGORI
    ════════════════════════════════════ --}}
    <div class="md-tab-panel active" data-panel="kategori">
        <div class="card-kk mb-4">
            <div class="card-kk-header">
                <div class="card-kk-header-left">
                    <div class="card-kk-header-icon icon-teal-soft"><i class="bi bi-tags"></i></div>
                    <h5>Daftar Kategori</h5>
                </div>
                <button class="btn-tambah-kk" data-bs-toggle="modal" data-bs-target="#modalTambahKategori">
                    <i class="bi bi-plus-lg"></i> Tambah Kategori
                </button>
            </div>
            <div class="table-responsive">
                <table class="table table-kk mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:22px">Nama Kategori</th>
                            <th>Jumlah Item</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($categories as $cat)
                        <tr class="md-cat-row" data-category-id="{{ $cat->id }}" data-category-name="{{ $cat->name }}" style="cursor:pointer">
                            <td class="td-name" style="padding-left:22px">
                                {{ $cat->name }}
                                <i class="bi bi-box-arrow-up-right" style="font-size:10px; color:#bcc9bc; margin-left:6px"></i>
                            </td>
                            <td class="td-muted">{{ $cat->products_count }} item</td>
                            <td class="text-center" onclick="event.stopPropagation()">
                                <div class="d-flex justify-content-center gap-2">
                                    <button class="btn-aksi btn-aksi-edit" data-bs-toggle="modal" data-bs-target="#modalEditKategori{{ $cat->id }}">
                                        <i class="bi bi-pencil"></i>
                                    </button>
                                    <form action="{{ route('categories.destroy', $cat->id) }}" method="POST" class="d-inline">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn-aksi btn-aksi-hapus" onclick="return confirm('Hapus kategori &quot;{{ $cat->name }}&quot;?')">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr class="empty-row"><td colspan="3">Belum ada kategori.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @include('masterdata.partials.panel-bahan')
    @include('masterdata.partials.panel-produk')
    @include('masterdata.partials.panel-resep')
    @include('masterdata.partials.panel-user')

</div>{{-- end .md-wrapper --}}

{{-- ── MODAL TAMBAH KATEGORI ── --}}
<div class="modal fade modal-kk" id="modalTambahKategori" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('categories.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label-kk">Nama Kategori</label>
                <input type="text" name="name" class="form-control-kk" placeholder="Misal: Minuman Kopi" required>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT KATEGORI (per item) ── --}}
@foreach($categories as $cat)
<div class="modal fade modal-kk" id="modalEditKategori{{ $cat->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('categories.update', $cat->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Kategori</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <label class="form-label-kk">Nama Kategori</label>
                <input type="text" name="name" class="form-control-kk" value="{{ $cat->name }}" required>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach

@include('masterdata.partials.scripts')

@endsection
