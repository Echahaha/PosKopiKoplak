<div class="modal fade modal-kk" id="modalEdit{{ $product->id }}" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="modal-header">
                    <div class="d-flex align-items-center gap-2">
                        <div class="card-kk-header-icon {{ $product->is_menu ? 'icon-teal-soft' : 'icon-brown-soft' }}">
                            <i class="bi {{ $product->is_menu ? 'bi-cup-hot' : 'bi-box-seam' }}"></i>
                        </div>
                        <div>
                            <h5 class="modal-title">Edit {{ $product->is_menu ? 'Menu Jual' : 'Bahan Baku' }}</h5>
                            <small class="modal-subtitle">{{ $product->name }}</small>
                        </div>
                    </div>
                    <div class="d-flex align-items-center gap-2">
                        <span class="badge-tipe-produk">
                            {{ $product->is_menu ? 'Menu' : 'Bahan Baku' }}
                        </span>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                </div>

                <div class="modal-body">

                    {{-- Nama --}}
                    <div class="mb-3">
                        <label class="form-label-kk">Nama {{ $product->is_menu ? 'Minuman' : 'Bahan' }}</label>
                        <input type="text" name="name" class="form-control form-control-kk"
                            value="{{ $product->name }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label-kk">Foto Menu (opsional)</label>
                        <input type="file" name="image" class="form-control form-control-kk"
                            accept="image/*">
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label-kk">{{ $product->is_menu ? 'Harga Jual (Rp)' : 'Harga Modal (Rp)' }}</label>
                            <input type="number" name="{{ $product->is_menu ? 'price' : 'purchase_price' }}"
                                class="form-control form-control-kk"
                                value="{{ $product->is_menu ? $product->price : $product->purchase_price }}"
                                onfocus="if(this.value=='0')this.value=''"
                                onblur="if(this.value=='')this.value='0'">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label-kk">Kategori</label>
                            <select name="category_id" class="form-select form-control-kk form-select-kk">
                                @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    @if($product->is_menu)
                    {{-- ── SECTION RESEP ── --}}
                    <input type="hidden" name="unit" value="{{ $product->unit }}">
                    <hr class="section-divider">
                    <p class="resep-label"><i class="bi bi-list-check me-2"></i>Edit Komposisi Bahan</p>
                    <div id="resep-container-edit-{{ $product->id }}">
                        @foreach($product->recipes as $recipe)
                        <div class="row g-2 mb-2 resep-row-edit">
                            <div class="col-7">
                                <select name="ingredients[]" class="form-select form-control-kk form-select-kk" style="padding:7px 12px;font-size:12px">
                                    @foreach($ingredients as $ing)
                                    <option value="{{ $ing->id }}" {{ $recipe->ingredient_id == $ing->id ? 'selected' : '' }}>
                                        {{ $ing->name }} ({{ $ing->unit }})
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-3">
                                <input type="number" name="amounts[]"
                                    class="form-control form-control-kk"
                                    style="padding:7px 12px;font-size:12px"
                                    value="{{ $recipe->usage_amount }}"
                                    min="0" step="1"
                                    onfocus="if(this.value=='0')this.value=''"
                                    onblur="if(this.value=='')this.value='0'">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn-aksi btn-aksi-hapus remove-resep-edit w-100" style="border-radius:9px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <template id="resep-template-edit-{{ $product->id }}">
                        <div class="row g-2 mb-2 resep-row-edit">
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
                                    class="form-control form-control-kk"
                                    style="padding:7px 12px;font-size:12px"
                                    value="0" min="0" step="1"
                                    onfocus="if(this.value=='0')this.value=''"
                                    onblur="if(this.value=='')this.value='0'">
                            </div>
                            <div class="col-2">
                                <button type="button" class="btn-aksi btn-aksi-hapus remove-resep-edit w-100" style="border-radius:9px">
                                    <i class="bi bi-trash"></i>
                                </button>
                            </div>
                        </div>
                    </template>
                    <button type="button" class="btn-add-bahan mt-1 add-ingredient-edit"
                        data-target="resep-container-edit-{{ $product->id }}"
                        data-template="resep-template-edit-{{ $product->id }}">
                        <i class="bi bi-plus-circle"></i> Tambah Bahan
                    </button>

                    @else
                    {{-- ── SECTION STOK BAHAN BAKU ── --}}
                    <hr class="section-divider">
                    <p class="resep-label" style="color:#3d7a5e">
                        <i class="bi bi-box-seam me-2"></i>Info Stok Gudang
                    </p>
                    <div class="row g-3">
                        <div class="col-md-4">
                            <label class="form-label-kk">Stok Saat Ini</label>
                            <div class="position-relative">
                                <input type="number" name="stock"
                                    class="form-control form-control-kk"
                                    value="{{ $product->stock }}"
                                    id="stok-{{ $product->id }}"
                                    style="padding-right: 38px"
                                    onfocus="if(this.value=='0')this.value=''"
                                    onblur="if(this.value=='')this.value='0'">
                                <span id="hint-{{ $product->id }}"
                                    style="position:absolute;right:12px;top:50%;transform:translateY(-50%);font-size:11px;color:#9aaa9a;font-weight:600;pointer-events:none">
                                    {{ $product->unit }}
                                </span>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-kk">Satuan</label>
                            <select name="unit" class="form-select form-control-kk form-select-kk"
                                onchange="document.getElementById('hint-{{ $product->id }}').textContent=this.value">
                                <option value="gram" {{ $product->unit == 'gram' ? 'selected' : '' }}>gram</option>
                                <option value="ml" {{ $product->unit == 'ml'   ? 'selected' : '' }}>ml</option>
                                <option value="pcs" {{ $product->unit == 'pcs'  ? 'selected' : '' }}>pcs</option>
                                <option value="cup" {{ $product->unit == 'cup'  ? 'selected' : '' }}>cup</option>
                            </select>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label-kk">Stok Minimum</label>
                            <input type="number" name="min_stock"
                                class="form-control form-control-kk"
                                value="{{ $product->min_stock }}"
                                onfocus="if(this.value=='0')this.value=''"
                                onblur="if(this.value=='')this.value='0'">
                        </div>
                    </div>
                    @endif

                </div>

                <div class="modal-footer gap-2">
                    <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-modal-simpan">
                        <i class="bi bi-floppy me-1"></i> Simpan Perubahan
                    </button>
                </div>

            </form>
        </div>
    </div>
</div>