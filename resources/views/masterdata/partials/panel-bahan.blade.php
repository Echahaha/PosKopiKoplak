{{-- ════════════════════════════════════
     PANEL: BAHAN BAKU (is_menu = 0)
════════════════════════════════════ --}}
<div class="md-tab-panel" data-panel="bahan">
    <div class="card-kk mb-4">
        <div class="card-kk-header">
            <div class="card-kk-header-left">
                <div class="card-kk-header-icon icon-brown-soft"><i class="bi bi-box-seam"></i></div>
                <h5>Stok Bahan Baku</h5>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="md-filter-select-wrap">
                    <select class="md-filter-select" id="filterKategoriBahan" onchange="mdFilterByCategory('bahan', this.value)">
                        <option value="">Semua Kategori</option>
                        @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn-tambah-kk" data-bs-toggle="modal" data-bs-target="#modalTambahBahan">
                    <i class="bi bi-plus-lg"></i> Tambah Bahan
                </button>
            </div>
        </div>
        <div style="padding: 14px 22px 0">
            <div class="md-filter-chip" id="chipFilterBahan">
                <i class="bi bi-funnel-fill"></i>
                <span id="chipFilterBahanText">Kategori: -</span>
                <button onclick="mdFilterByCategory('bahan', '')"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Nama Bahan</th>
                        <th>Kategori</th>
                        <th>Harga Modal</th>
                        <th>Stok Saat Ini</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodyBahan">
                    @forelse($ingredients as $item)
                    <tr class="md-data-row" data-category-id="{{ $item->category_id }}">
                        <td class="td-name" style="padding-left:22px">{{ $item->name }}</td>
                        <td><span class="badge-menu">{{ $item->category->name ?? '-' }}</span></td>
                        <td class="td-muted">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                        <td>
                            @if($item->stock <= $item->min_stock)
                                <span class="badge-stok-danger">
                                    <i class="bi bi-exclamation-triangle-fill me-1" style="font-size:10px"></i>
                                    {{ $item->stock }} {{ $item->unit }}
                                </span>
                            @else
                                <span class="badge-stok-ok">{{ $item->stock }} {{ $item->unit }}</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-aksi btn-aksi-edit" data-bs-toggle="modal" data-bs-target="#modalEditBahan{{ $item->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-aksi-hapus" onclick="return confirm('Hapus bahan &quot;{{ $item->name }}&quot;?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="5">Belum ada bahan baku.</td></tr>
                    @endforelse
                    <tr class="empty-row md-empty-filtered" style="display:none"><td colspan="5">Tidak ada bahan baku pada kategori ini.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── MODAL TAMBAH BAHAN BAKU ── --}}
<div class="modal fade modal-kk" id="modalTambahBahan" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="is_menu" value="0">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-kk">Nama Bahan Baku</label>
                    <input type="text" name="name" class="form-control-kk" placeholder="Misal: Biji Kopi Gayo" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-kk">Kategori</label>
                    <select name="category_id" class="form-select-kk" required>
                        @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Stok Awal</label>
                        <input type="number" name="stock" class="form-control-kk" placeholder="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Satuan</label>
                        <select name="unit" class="form-select-kk">
                            <option value="gram">gram (gr)</option>
                            <option value="ml">mililiter (ml)</option>
                            <option value="pcs">pieces (pcs)</option>
                            <option value="cup">cup</option>
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Harga Modal (Rp)</label>
                        <input type="number" name="purchase_price" class="form-control-kk" placeholder="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Stok Minimum</label>
                        <input type="number" name="min_stock" class="form-control-kk" placeholder="0">
                    </div>
                </div>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT BAHAN BAKU (per item) ── --}}
@foreach($ingredients as $item)
<div class="modal fade modal-kk" id="modalEditBahan{{ $item->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.update', $item->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Bahan Baku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-kk">Nama Bahan Baku</label>
                    <input type="text" name="name" class="form-control-kk" value="{{ $item->name }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-kk">Kategori</label>
                    <select name="category_id" class="form-select-kk" required>
                        @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}" {{ $item->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Stok</label>
                        <input type="number" name="stock" class="form-control-kk" value="{{ $item->stock }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Satuan</label>
                        <select name="unit" class="form-select-kk">
                            @foreach(['gram','ml','pcs','cup'] as $u)
                            <option value="{{ $u }}" {{ $item->unit == $u ? 'selected' : '' }}>{{ $u }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Harga Modal (Rp)</label>
                        <input type="number" name="purchase_price" class="form-control-kk" value="{{ $item->purchase_price }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Stok Minimum</label>
                        <input type="number" name="min_stock" class="form-control-kk" value="{{ $item->min_stock }}">
                    </div>
                </div>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach
