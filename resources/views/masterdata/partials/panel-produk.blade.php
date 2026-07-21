{{-- ════════════════════════════════════
     PANEL: PRODUK / MENU JUAL (is_menu = 1)
════════════════════════════════════ --}}
<div class="md-tab-panel" data-panel="produk">
    <div class="card-kk mb-4">
        <div class="card-kk-header">
            <div class="card-kk-header-left">
                <div class="card-kk-header-icon icon-teal-soft"><i class="bi bi-cup-hot"></i></div>
                <h5>Daftar Produk / Menu Jual</h5>
            </div>
            <div class="d-flex align-items-center gap-2 flex-wrap">
                <div class="md-filter-select-wrap">
                    <select class="md-filter-select" id="filterKategoriProduk" onchange="mdFilterByCategory('produk', this.value)">
                        <option value="">Semua Kategori</option>
                        @foreach($allCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button class="btn-tambah-kk" data-bs-toggle="modal" data-bs-target="#modalTambahProduk">
                    <i class="bi bi-plus-lg"></i> Tambah Produk
                </button>
            </div>
        </div>
        <div style="padding: 14px 22px 0">
            <div class="md-filter-chip" id="chipFilterProduk">
                <i class="bi bi-funnel-fill"></i>
                <span id="chipFilterProdukText">Kategori: -</span>
                <button onclick="mdFilterByCategory('produk', '')"><i class="bi bi-x-lg"></i></button>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Nama Menu</th>
                        <th>Kategori</th>
                        <th>Harga Jual</th>
                        <th>Jml Bahan</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody id="tbodyProduk">
                    @forelse($menus as $product)
                    <tr class="md-data-row" data-category-id="{{ $product->category_id }}">
                        <td class="td-name" style="padding-left:22px">{{ $product->name }}</td>
                        <td><span class="badge-menu">{{ $product->category->name ?? '-' }}</span></td>
                        <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                        <td class="td-muted">{{ $product->recipes->count() }} bahan</td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('recipes.index', $product->id) }}" class="btn-aksi btn-aksi-resep" title="Atur Resep">
                                    <i class="bi bi-journal-text"></i>
                                </a>
                                <button class="btn-aksi btn-aksi-edit" data-bs-toggle="modal" data-bs-target="#modalEditProduk{{ $product->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-aksi-hapus" onclick="return confirm('Hapus menu &quot;{{ $product->name }}&quot;?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="5">Belum ada produk/menu.</td></tr>
                    @endforelse
                    <tr class="empty-row md-empty-filtered" style="display:none"><td colspan="5">Tidak ada produk pada kategori ini.</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── MODAL TAMBAH PRODUK ── --}}
<div class="modal fade modal-kk" id="modalTambahProduk" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.store') }}" method="POST" class="modal-content">
            @csrf
            <input type="hidden" name="is_menu" value="1">
            <div class="modal-header">
                <h5 class="modal-title">Tambah Produk Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-kk">Nama Menu</label>
                    <input type="text" name="name" class="form-control-kk" placeholder="Misal: Es Kopi Susu" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Harga Jual (Rp)</label>
                        <input type="number" name="price" class="form-control-kk" value="0">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Kategori</label>
                        <select name="category_id" class="form-select-kk" required>
                            @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <hr style="border-top:1px solid #f0ede6; margin:18px 0 14px">
                <p style="font-size:13px; font-weight:700; color:#1a1f1a; margin-bottom:12px">
                    <i class="bi bi-list-check me-2"></i>Komposisi Resep (opsional, bisa diatur nanti)
                </p>
                <div id="md-resep-container">
                    <div class="row g-2 mb-2 md-resep-row">
                        <div class="col-7">
                            <select name="ingredients[]" class="form-select-kk" style="padding:7px 12px;font-size:12px">
                                <option value="">Pilih Bahan...</option>
                                @foreach($allIngredients as $ing)
                                <option value="{{ $ing->id }}">{{ $ing->name }} ({{ $ing->unit }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-3">
                            <input type="number" name="amounts[]" class="form-control-kk" style="padding:7px 12px;font-size:12px" placeholder="Jml" min="0" step="1">
                        </div>
                        <div class="col-2 text-end">
                            <button type="button" class="btn-aksi btn-aksi-hapus md-remove-resep w-100" style="border-radius:9px">
                                <i class="bi bi-trash"></i>
                            </button>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn-modal-batal" id="md-add-ingredient" style="border-radius:20px; padding:6px 16px; font-size:12px">
                    <i class="bi bi-plus-circle me-1"></i> Tambah Bahan Baku
                </button>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Data</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT PRODUK (per item, edit info dasar saja — resep diatur di halaman Resep) ── --}}
@foreach($menus as $product)
<div class="modal fade modal-kk" id="modalEditProduk{{ $product->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('products.update', $product->id) }}" method="POST" class="modal-content" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-kk">Nama Menu</label>
                    <input type="text" name="name" class="form-control-kk" value="{{ $product->name }}" required>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Harga Jual (Rp)</label>
                        <input type="number" name="price" class="form-control-kk" value="{{ $product->price }}">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Kategori</label>
                        <select name="category_id" class="form-select-kk" required>
                            @foreach($allCategories as $cat)
                            <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <p style="font-size:12px; color:#9aaa9a; margin-top:14px;">
                    <i class="bi bi-info-circle me-1"></i> Untuk mengatur komposisi resep & HPP, gunakan tombol
                    <i class="bi bi-journal-text" style="color:#d97706"></i> "Atur Resep" pada tabel.
                </p>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>
@endforeach
