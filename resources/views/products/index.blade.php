@extends('dashboard')

@section('title', 'Manajemen Stok & Produk')

@section('content')

<style>
    .products-wrapper * {
        box-sizing: border-box;
    }

    /* ── BACKGROUND ── */
    .products-wrapper {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 4px 0 32px;
    }

    /* ── PAGE HEADER ── */
    .prod-page-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 24px;
    }

    .prod-page-header h4 {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 2px;
    }

    .prod-page-header p {
        font-size: 13px;
        color: #7a8a7a;
        margin: 0;
    }

    .btn-tambah-kk {
        background: #3d7a5e;
        color: white;
        border: none;
        border-radius: 24px;
        padding: 9px 22px;
        font-size: 13px;
        font-weight: 700;
        display: flex;
        align-items: center;
        gap: 7px;
        transition: background 0.2s, transform 0.15s;
        cursor: pointer;
    }

    .btn-tambah-kk:hover {
        background: #2d6a4e;
        color: white;
        transform: translateY(-1px);
    }

    /* ── ALERT ── */
    .alert-kk {
        background: #e6f4ee;
        border: 1px solid #b2dcc8;
        border-radius: 14px;
        color: #2d6a4e;
        font-size: 13px;
        font-weight: 600;
        padding: 12px 18px;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 20px;
    }

    .btn-close-kk {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 14px;
        color: #3d7a5e;
        cursor: pointer;
        opacity: 0.7;
    }

    /* ── TABS ── */
    .kk-tabs {
        display: flex;
        gap: 6px;
        background: #eceae1;
        border-radius: 16px;
        padding: 5px;
        margin-bottom: 18px;
        width: fit-content;
    }

    .kk-tab-btn {
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

    .kk-tab-btn .kk-tab-icon {
        width: 24px;
        height: 24px;
        border-radius: 7px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        flex-shrink: 0;
        transition: all 0.18s;
        background: transparent;
        color: inherit;
    }

    .kk-tab-btn.active {
        background: white;
        color: #1a1f1a;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
    }

    .kk-tab-btn.active .kk-tab-icon {
        background: #e6f4ee;
        color: #3d7a5e;
    }

    .kk-tab-btn[data-tab="bahan"].active .kk-tab-icon {
        background: #f0ede6;
        color: #7a6a50;
    }

    .kk-tab-count {
        font-size: 10.5px;
        font-weight: 700;
        background: #f5f4f0;
        color: #9aaa9a;
        border-radius: 20px;
        padding: 1px 8px;
        line-height: 1.5;
    }

    .kk-tab-btn.active .kk-tab-count {
        background: #f5f4f0;
        color: #5a6a5a;
    }

    .kk-tab-alert-dot {
        width: 7px;
        height: 7px;
        border-radius: 50%;
        background: #c53030;
        display: inline-block;
        margin-left: 2px;
    }

    .kk-tab-panel {
        display: none;
    }

    .kk-tab-panel.active {
        display: block;
        animation: kkFadeIn 0.18s ease;
    }

    @keyframes kkFadeIn {
        from {
            opacity: 0;
            transform: translateY(3px);
        }

        to {
            opacity: 1;
            transform: translateY(0);
        }
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
        gap: 10px;
    }

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

    .icon-teal-soft {
        background: #e6f4ee;
        color: #3d7a5e;
    }

    .icon-brown-soft {
        background: #f0ede6;
        color: #7a6a50;
    }

    .card-kk-header h5 {
        font-size: 14px;
        font-weight: 800;
        color: #1a1f1a;
        margin: 0;
    }

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

    .table-kk tbody tr:last-child td {
        border-bottom: none;
    }

    .table-kk tbody tr:hover td {
        background: #faf9f7;
    }

    .td-name {
        font-weight: 700;
        color: #1a1f1a;
    }

    .td-muted {
        color: #8a9a8a;
        font-size: 12px;
    }

    /* ── BADGES ── */
    .badge-menu {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 11px;
        font-weight: 600;
        padding: 4px 11px;
        border-radius: 20px;
    }

    .badge-stok-ok {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .badge-stok-danger {
        background: #fde8e8;
        color: #c53030;
        border: 1px solid #f5b8b8;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    /* ── ACTION BUTTONS ── */
    .btn-aksi {
        width: 32px;
        height: 32px;
        border-radius: 9px;
        border: 1px solid #eceae4;
        background: white;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        cursor: pointer;
        transition: all 0.15s;
        text-decoration: none;
    }

    .btn-aksi-edit {
        color: #3d7a5e;
    }

    .btn-aksi-edit:hover {
        background: #e6f4ee;
        border-color: #b2dcc8;
        color: #2d6a4e;
    }

    .btn-aksi-hapus {
        color: #c53030;
    }

    .btn-aksi-hapus:hover {
        background: #fde8e8;
        border-color: #f5b8b8;
        color: #9b1c1c;
    }

    /* ── MODAL ── */
    .modal-kk .modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.12);
    }

    .modal-kk .modal-header {
        border-bottom: 1px solid #f0ede6;
        padding: 20px 24px 14px;
    }

    .modal-kk .modal-title {
        font-size: 16px;
        font-weight: 800;
        color: #1a1f1a;
    }

    .modal-kk .modal-body {
        padding: 20px 24px;
    }

    .modal-kk .modal-footer {
        border-top: 1px solid #f0ede6;
        padding: 14px 24px 20px;
    }

    .form-label-kk {
        font-size: 11.5px;
        font-weight: 700;
        color: #6a7a6a;
        text-transform: uppercase;
        letter-spacing: 0.3px;
        margin-bottom: 5px;
    }

    .form-control-kk,
    .form-select-kk {
        border: 1px solid #e4e0d8;
        border-radius: 10px;
        padding: 9px 13px;
        font-size: 13px;
        color: #1a1f1a;
        background: #faf9f6;
        transition: border-color 0.15s, box-shadow 0.15s;
    }

    .form-control-kk:focus,
    .form-select-kk:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61, 122, 94, 0.12);
        background: white;
        outline: none;
    }

    .tipe-toggle {
        background: #faf9f6;
        border: 1px solid #eceae4;
        border-radius: 12px;
        padding: 14px 16px;
        margin-bottom: 18px;
    }

    .tipe-toggle label {
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        color: #9aaa9a;
        letter-spacing: 0.4px;
        margin-bottom: 10px;
        display: block;
    }

    .radio-kk {
        display: flex;
        gap: 10px;
    }

    .radio-kk-item {
        flex: 1;
        border: 2px solid #e4e0d8;
        border-radius: 10px;
        padding: 8px 14px;
        cursor: pointer;
        text-align: center;
        font-size: 13px;
        font-weight: 700;
        color: #6a7a6a;
        transition: all 0.15s;
        user-select: none;
    }

    .radio-kk-item.active {
        border-color: #3d7a5e;
        background: #e6f4ee;
        color: #3d7a5e;
    }

    .radio-kk-item input {
        display: none;
    }

    .section-divider {
        border: none;
        border-top: 1px solid #f0ede6;
        margin: 18px 0 14px;
    }

    .resep-label {
        font-size: 13px;
        font-weight: 700;
        color: #1a1f1a;
        margin-bottom: 12px;
    }

    .btn-modal-batal {
        background: #f5f4f0;
        border: 1px solid #e4e0d8;
        border-radius: 20px;
        padding: 8px 22px;
        font-size: 13px;
        font-weight: 700;
        color: #5a6a5a;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-modal-batal:hover {
        background: #eceae4;
    }

    .btn-modal-simpan {
        background: #3d7a5e;
        border: none;
        border-radius: 20px;
        padding: 8px 22px;
        font-size: 13px;
        font-weight: 700;
        color: white;
        cursor: pointer;
        transition: background 0.15s;
    }

    .btn-modal-simpan:hover {
        background: #2d6a4e;
    }

    .btn-add-bahan {
        background: none;
        border: none;
        color: #3d7a5e;
        font-size: 12px;
        font-weight: 700;
        padding: 4px 0;
        cursor: pointer;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .btn-add-bahan:hover {
        color: #2d6a4e;
    }

    .empty-row td {
        text-align: center;
        padding: 32px 16px;
        color: #9aaa9a;
        font-size: 13px;
        font-style: italic;
    }

    /* ── MODAL EDIT HELPERS ── */
    .badge-tipe-produk {
        background: #f0ede6;
        color: #7a6a50;
        font-size: 10px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
        text-transform: uppercase;
        letter-spacing: 0.3px;
    }
    .modal-subtitle {
        color: #9aaa9a;
        font-size: 11px;
    }

    @media (max-width: 576px) {
        .kk-tabs {
            width: 100%;
        }

        .kk-tab-btn {
            flex: 1;
            justify-content: center;
        }
    }
</style>

<div class="products-wrapper">

    {{-- ── PAGE HEADER ── --}}
    <div class="prod-page-header">
        <div>
            <h4>Manajemen Stok & Produk</h4>
            <p>Kelola menu minuman dan pantau ketersediaan bahan baku gudang.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn-tambah-kk" style="background:#d97706" data-bs-toggle="modal" data-bs-target="#modalRestock">
                <i class="bi bi-box-arrow-in-down"></i> Restock
            </button>
        </div>
    </div>

    {{-- ── ALERT SUCCESS ── --}}
    @if(session('success'))
    <div class="alert-kk" id="alertSuccess">
        <i class="bi bi-check-circle-fill"></i>
        {{ session('success') }}
        <button class="btn-close-kk" onclick="document.getElementById('alertSuccess').remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif

    @php
    $menus = $products->where('is_menu', 1);
    // Urutkan bahan baku: yang stok menipis/habis (stock <= min_stock) di atas,
    // lalu yang aman di bawah. Di dalam masing-masing grup, urutkan by nama.
    $ingredients_list = $products->where('is_menu', 0)->sortBy(function ($p) {
        $isLow = $p->stock <= $p->min_stock ? 0 : 1;
        return [$isLow, $p->name];
    });
    $lowStockCount = $ingredients_list->filter(fn($p) => $p->stock <= $p->min_stock)->count();
        @endphp

        {{-- ── TAB SWITCHER ── --}}
        <div class="kk-tabs" role="tablist">
            <button type="button" class="kk-tab-btn active" data-tab="menu" role="tab">
                <span class="kk-tab-icon"><i class="bi bi-cup-hot"></i></span>
                Menu Jual
                <span class="kk-tab-count">{{ $menus->count() }}</span>
            </button>
            <button type="button" class="kk-tab-btn" data-tab="bahan" role="tab">
                <span class="kk-tab-icon"><i class="bi bi-box-seam"></i></span>
                Stok Bahan Baku
                <span class="kk-tab-count">{{ $ingredients_list->count() }}</span>
                @if($lowStockCount > 0)
                <span class="kk-tab-alert-dot" title="{{ $lowStockCount }} bahan menipis"></span>
                @endif
            </button>
        </div>

        {{-- ── PANEL: MENU JUAL ── --}}
        <div class="kk-tab-panel active" data-panel="menu">
            <div class="card-kk mb-4">
                <div class="card-kk-header">
                    <div class="card-kk-header-icon icon-teal-soft">
                        <i class="bi bi-cup-hot"></i>
                    </div>
                    <h5>Daftar Menu Jual</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-kk mb-0">
                        <thead>
                            <tr>
                                <th style="padding-left:22px">Nama Menu</th>
                                <th>Kategori</th>
                                <th>Harga Jual</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($menus as $product)
                            <tr>
                                <td class="td-name" style="padding-left:22px">{{ $product->name }}</td>
                                <td><span class="badge-menu">{{ $product->category->name }}</span></td>
                                <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('recipes.index', $product->id) }}" class="btn-aksi" title="Atur Resep">
                                            <i class="bi bi-journal-text" style="color:#d97706"></i>
                                        </a>
                                        <button class="btn-aksi btn-aksi-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $product->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-aksi btn-aksi-hapus"
                                                onclick="return confirm('Hapus menu ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="4">Belum ada menu yang didaftarkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        {{-- ── PANEL: STOK BAHAN BAKU ── --}}
        <div class="kk-tab-panel" data-panel="bahan">
            <div class="card-kk mb-4">
                <div class="card-kk-header">
                    <div class="card-kk-header-icon icon-brown-soft">
                        <i class="bi bi-box-seam"></i>
                    </div>
                    <h5>Stok Bahan Baku (Gudang)</h5>
                </div>
                <div class="table-responsive">
                    <table class="table table-kk mb-0">
                        <thead>
                            <tr>
                                <th style="padding-left:22px">Nama Bahan</th>
                                <th>Harga Modal</th>
                                <th>Stok Saat Ini</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($ingredients_list as $product)
                            <tr>
                                <td class="td-name" style="padding-left:22px">{{ $product->name }}</td>
                                <td class="td-muted">Rp {{ number_format($product->purchase_price, 0, ',', '.') }}</td>
                                <td>
                                    @if($product->stock <= $product->min_stock)
                                        <span class="badge-stok-danger">
                                            <i class="bi bi-exclamation-triangle-fill me-1" style="font-size:10px"></i>
                                            {{ $product->stock }} {{ $product->unit }}
                                        </span>
                                        @else
                                        <span class="badge-stok-ok">{{ $product->stock }} {{ $product->unit }}</span>
                                        @endif
                                </td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <button class="btn-aksi btn-aksi-edit"
                                            data-bs-toggle="modal"
                                            data-bs-target="#modalEdit{{ $product->id }}">
                                            <i class="bi bi-pencil"></i>
                                        </button>
                                        <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                            @csrf @method('DELETE')
                                            <button type="submit" class="btn-aksi btn-aksi-hapus"
                                                onclick="return confirm('Hapus bahan ini?')">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr class="empty-row">
                                <td colspan="4">Belum ada bahan baku di gudang.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

</div>{{-- end .products-wrapper --}}


{{-- ────────────────────────────────────────
     MODAL TAMBAH
──────────────────────────────────────── --}}
<div class="modal fade modal-kk" id="modalTambah" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                {{-- Tipe Toggle --}}
                <div class="tipe-toggle">
                    <label>Tipe Produk</label>
                    <div class="radio-kk">
                        <label class="radio-kk-item active" id="lbl-bahan">
                            <input type="radio" name="is_menu" value="0" checked>
                            <i class="bi bi-box-seam me-1"></i> Bahan Baku
                        </label>
                        <label class="radio-kk-item" id="lbl-menu">
                            <input type="radio" name="is_menu" value="1">
                            <i class="bi bi-cup-hot me-1"></i> Menu Jual
                        </label>
                    </div>
                </div>

                {{-- Nama --}}
                <div class="mb-3">
                    <label class="form-label-kk" id="label-nama">Nama Bahan Baku</label>
                    <input type="text" name="name" class="form-control form-control-kk"
                        placeholder="Misal: Biji Kopi Gayo" required>
                </div>

                {{-- Harga Jual + Kategori --}}
                <div class="row g-3">
                    <div class="col-md-6" id="group-harga-jual" style="display:none">
                        <label class="form-label-kk">Harga Jual (Rp)</label>
                        <input type="number" name="price" class="form-control form-control-kk" value="0">
                    </div>
                    <div class="col-12" id="group-kategori">
                        <label class="form-label-kk">Kategori</label>
                        <select name="category_id" class="form-select form-control-kk form-select-kk" required>
                            @foreach($categories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                {{-- Section Bahan Baku --}}
                <div id="section-bahan">
                    <hr class="section-divider">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label-kk">Stok Awal</label>
                            <input type="number" name="stock" class="form-control form-control-kk" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-kk">Satuan</label>
                            <select name="unit" class="form-select form-control-kk form-select-kk">
                                <option value="gram">gram (gr)</option>
                                <option value="ml">mililiter (ml)</option>
                                <option value="pcs">pieces (pcs)</option>
                                <option value="cup">cup</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-kk">Harga Modal (Rp)</label>
                            <input type="number" name="purchase_price" class="form-control form-control-kk" placeholder="0">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-kk">Stok Minimum</label>
                            <input type="number" name="min_stock" class="form-control form-control-kk" placeholder="0">
                        </div>
                    </div>
                </div>

                {{-- Section Menu / Resep --}}
                <div id="section-menu" style="display:none">
                    <hr class="section-divider">
                    <p class="resep-label"><i class="bi bi-list-check me-2"></i>Komposisi Resep</p>
                    <div id="resep-container">
                        <div class="row g-2 mb-2 resep-row">
                            <div class="col-7">
                                <select name="ingredients[]" class="form-select form-control-kk form-select-kk" style="padding:7px 12px;font-size:12px">
                                    <option value="">Pilih Bahan...</option>
                                    @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="amounts[]"
                                    class="form-control form-control-kk" style="padding:7px 12px;font-size:12px"
                                    placeholder="Jml" min="0" step="1">
                            </div>
                            <div class="col-2 text-end">
                                <button type="button"
                                    class="btn-aksi btn-aksi-hapus remove-resep w-100"
                                    style="border-radius:9px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </div>
                    <button type="button" class="btn-add-bahan mt-1" id="add-ingredient">
                        <i class="bi bi-plus-circle"></i> Tambah Bahan Baku
                    </button>
                </div>

            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- Modal Edit (partial) --}}
@foreach($products as $product)
@include('products.partials.modal-edit', ['product' => $product])
@endforeach

{{-- Modal Restock --}}
<div class="modal fade modal-kk" id="modalRestock" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.restock') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Restock Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-kk">Pilih Bahan Baku</label>
                    <select name="product_id" id="restock-product-select" class="form-select form-control-kk form-select-kk" required>
                        <option value="">-- Pilih Bahan --</option>
                        @foreach($ingredients as $ing)
                        <option value="{{ $ing->id }}" data-unit="{{ $ing->unit }}" data-price="{{ $ing->purchase_price }}">
                            {{ $ing->name }} ({{ $ing->unit }})
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Jumlah Masuk (<span id="restock-unit-label">satuan</span>)</label>
                        <input type="number" name="restock_amount" id="restock-amount" class="form-control form-control-kk" placeholder="0" min="1" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Total Harga Beli (Rp)</label>
                        <input type="number" name="total_price" id="restock-total-price" class="form-control form-control-kk" placeholder="0" min="0">
                    </div>
                </div>
                <p class="td-muted mt-2 mb-0" style="font-size:11.5px">
                    *Harga modal per satuan akan otomatis dihitung dari Total Harga Beli ÷ Jumlah Masuk. Kosongkan jika harga modal tidak berubah.
                </p>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Restock</button>
            </div>
        </form>
    </div>
</div>

<script>
    // ── TAB SWITCHER (Menu Jual / Stok Bahan Baku) ──
    document.querySelectorAll('.kk-tab-btn').forEach(btn => {
        btn.addEventListener('click', function() {
            const target = this.getAttribute('data-tab');

            document.querySelectorAll('.kk-tab-btn').forEach(b => b.classList.remove('active'));
            this.classList.add('active');

            document.querySelectorAll('.kk-tab-panel').forEach(panel => {
                panel.classList.toggle('active', panel.getAttribute('data-panel') === target);
            });
        });
    });

    // ── TOGGLE TIPE (Tambah) ──
    document.querySelectorAll('input[name="is_menu"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const isMenu = this.value == "1";

            document.getElementById('section-menu').style.display = isMenu ? 'block' : 'none';
            document.getElementById('group-harga-jual').style.display = isMenu ? 'block' : 'none';
            document.getElementById('section-bahan').style.display = isMenu ? 'none' : 'block';
            document.getElementById('label-nama').innerText = isMenu ? 'Nama Minuman/Makanan' : 'Nama Bahan Baku';

            // Kategori: col-6 kalau Menu Jual (sejajar Harga Jual), col-12 kalau Bahan Baku
            const grpKat = document.getElementById('group-kategori');
            grpKat.className = isMenu ? 'col-md-6' : 'col-12';

            document.getElementById('lbl-bahan').classList.toggle('active', !isMenu);
            document.getElementById('lbl-menu').classList.toggle('active', isMenu);
        });
    });

    // ── ADD/REMOVE RESEP (Tambah) ──
    document.getElementById('add-ingredient').addEventListener('click', function() {
        const container = document.getElementById('resep-container');
        const row = container.querySelector('.resep-row').cloneNode(true);
        row.querySelector('select').value = '';
        row.querySelector('input').value = '';
        container.appendChild(row);
    });

    // ── CLEAR ZERO ON FOCUS (Edit Modal) ──
    document.addEventListener('focusin', function(e) {
        if (e.target.matches('input[type="number"]') && e.target.value == '0') {
            e.target.value = '';
        }
    });
    document.addEventListener('focusout', function(e) {
        if (e.target.matches('input[type="number"]') && e.target.value === '') {
            e.target.value = '0';
        }
    });

    document.addEventListener('click', function(e) {
        if (e.target.closest('.remove-resep')) {
            const rows = document.querySelectorAll('.resep-row');
            if (rows.length > 1) e.target.closest('.resep-row').remove();
        }

        // Edit modal resep rows
        if (e.target.closest('.add-ingredient-edit')) {
            const btn = e.target.closest('.add-ingredient-edit');
            const container = document.getElementById(btn.getAttribute('data-target'));
            const existingRow = container.querySelector('.resep-row-edit');

            let row;
            if (existingRow) {
                row = existingRow.cloneNode(true);
                row.querySelector('select').value = '';
                row.querySelector('input').value = '0';
            } else {
                const template = document.getElementById(btn.getAttribute('data-template'));
                row = template.content.cloneNode(true).querySelector('.resep-row-edit');
            }
            container.appendChild(row);
        }

        if (e.target.closest('.remove-resep-edit')) {
            const row = e.target.closest('.resep-row-edit');
            const container = row.parentElement;
            if (container.querySelectorAll('.resep-row-edit').length > 1) {
                row.remove();
            } else {
                alert('Minimal harus ada satu bahan baku!');
            }
        }
    });
    // ── RESTOCK: auto isi satuan saat pilih bahan ──
    document.getElementById('restock-product-select').addEventListener('change', function() {
        const selected = this.options[this.selectedIndex];
        const unit = selected.getAttribute('data-unit') || 'satuan';
        document.getElementById('restock-unit-label').innerText = unit;
    });
</script>

@endsection