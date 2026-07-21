{{-- ════════════════════════════════════
     PANEL: RESEP (ringkasan semua menu, drill-down per produk)
════════════════════════════════════ --}}
<div class="md-tab-panel" data-panel="resep">
    <div class="card-kk mb-4">
        <div class="card-kk-header">
            <div class="card-kk-header-left">
                <div class="card-kk-header-icon icon-amber-soft"><i class="bi bi-journal-text"></i></div>
                <h5>Resep per Menu</h5>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Nama Menu</th>
                        <th>Kategori</th>
                        <th>Jumlah Bahan</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($menus as $product)
                    <tr>
                        <td class="td-name" style="padding-left:22px">{{ $product->name }}</td>
                        <td><span class="badge-menu">{{ $product->category->name ?? '-' }}</span></td>
                        <td class="td-muted">{{ $product->recipes->count() }} bahan</td>
                        <td>
                            @if($product->recipes->count() > 0)
                                <span class="badge-stok-ok">Resep tersedia</span>
                            @else
                                <span class="badge-stok-danger">Belum ada resep</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <a href="{{ route('recipes.index', $product->id) }}" class="btn-aksi btn-aksi-resep" title="Kelola Resep">
                                <i class="bi bi-pencil-square"></i>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="5">Belum ada menu yang bisa diberi resep.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
