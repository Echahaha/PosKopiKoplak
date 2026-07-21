@extends('dashboard')

@section('title', 'Stok Opname')

@section('content')

<style>
    .opname-wrapper * { box-sizing: border-box; }
    .opname-wrapper { background: #f5f4f0; min-height: 100vh; padding: 4px 0 32px; }

    .opname-page-header { display: flex; justify-content: space-between; align-items: flex-start; margin-bottom: 22px; flex-wrap: wrap; gap: 12px; }
    .opname-page-header h4 { font-size: 22px; font-weight: 800; color: #1a1f1a; margin-bottom: 2px; }
    .opname-page-header p { font-size: 13px; color: #7a8a7a; margin: 0; }

    .btn-tambah-kk {
        background: #3d7a5e; color: white; border: none; border-radius: 24px; padding: 9px 22px;
        font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 7px;
        cursor: pointer; transition: background 0.2s, transform 0.15s;
    }
    .btn-tambah-kk:hover { background: #2d6a4e; color: white; transform: translateY(-1px); }

    .alert-kk { border-radius: 14px; font-size: 13px; font-weight: 600; padding: 12px 18px; display: flex; align-items: center; gap: 10px; margin-bottom: 20px; }
    .alert-kk.success { background: #e6f4ee; border: 1px solid #b2dcc8; color: #2d6a4e; }
    .alert-kk.error { background: #fde8e8; border: 1px solid #f5b8b8; color: #9b1c1c; }
    .btn-close-kk { margin-left: auto; background: none; border: none; font-size: 14px; cursor: pointer; opacity: 0.7; color: inherit; }

    .card-kk { background: white; border-radius: 18px; border: 1px solid #eceae4; overflow: hidden; }
    .table-kk thead th {
        font-size: 10.5px; text-transform: uppercase; letter-spacing: 0.5px; color: #9aaa9a;
        font-weight: 700; padding: 12px 16px; background: #faf9f6; border-bottom: 1px solid #f0ede6; white-space: nowrap;
    }
    .table-kk tbody td { padding: 12px 16px; font-size: 13px; color: #3a4a3a; border-bottom: 1px solid #f5f4f0; vertical-align: middle; }
    .table-kk tbody tr:last-child td { border-bottom: none; }
    .table-kk tbody tr:hover td { background: #faf9f7; }
    .td-name { font-weight: 700; color: #1a1f1a; }
    .td-muted { color: #8a9a8a; font-size: 12px; }

    .badge-draft { background: #fff3de; color: #d97706; border: 1px solid #f6d860; font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 20px; }
    .badge-selesai { background: #e6f4ee; color: #3d7a5e; border: 1px solid #b2dcc8; font-size: 11px; font-weight: 700; padding: 4px 11px; border-radius: 20px; }

    .btn-aksi {
        width: 32px; height: 32px; border-radius: 9px; border: 1px solid #eceae4; background: white;
        display: inline-flex; align-items: center; justify-content: center; font-size: 13px;
        cursor: pointer; transition: all 0.15s; text-decoration: none; color: #3d7a5e;
    }
    .btn-aksi:hover { background: #e6f4ee; border-color: #b2dcc8; }
    .btn-aksi-hapus { color: #c53030; }
    .btn-aksi-hapus:hover { background: #fde8e8; border-color: #f5b8b8; }

    .empty-row td { text-align: center; padding: 40px 16px; color: #9aaa9a; font-size: 13px; font-style: italic; }

    .modal-kk .modal-content { border: none; border-radius: 20px; box-shadow: 0 20px 60px rgba(0,0,0,0.12); }
    .modal-kk .modal-header { border-bottom: 1px solid #f0ede6; padding: 20px 24px 14px; }
    .modal-kk .modal-title { font-size: 16px; font-weight: 800; color: #1a1f1a; }
    .modal-kk .modal-body { padding: 20px 24px; }
    .modal-kk .modal-footer { border-top: 1px solid #f0ede6; padding: 14px 24px 20px; }
    .form-label-kk { font-size: 11.5px; font-weight: 700; color: #6a7a6a; text-transform: uppercase; letter-spacing: 0.3px; margin-bottom: 5px; }
    .form-control-kk {
        border: 1px solid #e4e0d8; border-radius: 10px; padding: 9px 13px; font-size: 13px; color: #1a1f1a;
        background: #faf9f6; width: 100%;
    }
    .form-control-kk:focus { border-color: #3d7a5e; box-shadow: 0 0 0 3px rgba(61,122,94,0.12); background: white; outline: none; }
    .btn-modal-batal { background: #f5f4f0; border: 1px solid #e4e0d8; border-radius: 20px; padding: 8px 22px; font-size: 13px; font-weight: 700; color: #5a6a5a; cursor: pointer; }
    .btn-modal-batal:hover { background: #eceae4; }
    .btn-modal-simpan { background: #3d7a5e; border: none; border-radius: 20px; padding: 8px 22px; font-size: 13px; font-weight: 700; color: white; cursor: pointer; }
    .btn-modal-simpan:hover { background: #2d6a4e; }

    .opname-pagination-wrap { padding: 16px 22px; border-top: 1px solid #f0ede6; }
    .opname-pagination-wrap nav { display: flex; justify-content: center; }
</style>

<div class="opname-wrapper">

    <div class="opname-page-header">
        <div>
            <h4>Stok Opname</h4>
            <p>Pencocokan stok fisik gudang dengan stok pada sistem secara berkala.</p>
        </div>
        <button class="btn-tambah-kk" data-bs-toggle="modal" data-bs-target="#modalMulaiOpname">
            <i class="bi bi-plus-lg"></i> Mulai Opname Baru
        </button>
    </div>

    <div class="card-kk">
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Kode Opname</th>
                        <th>Tanggal</th>
                        <th>Dibuat Oleh</th>
                        <th>Jumlah Item</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($opnames as $opname)
                    <tr>
                        <td class="td-name" style="padding-left:22px">{{ $opname->kode_opname }}</td>
                        <td class="td-muted">{{ \Carbon\Carbon::parse($opname->tanggal_opname)->translatedFormat('d M Y') }}</td>
                        <td class="td-muted">{{ $opname->user->name ?? '-' }}</td>
                        <td class="td-muted">{{ $opname->details_count }} item</td>
                        <td>
                            @if($opname->status == 'selesai')
                                <span class="badge-selesai">Selesai</span>
                            @else
                                <span class="badge-draft">Draft</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <a href="{{ route('stock-opname.show', $opname->id) }}" class="btn-aksi" title="Lihat / Isi">
                                    <i class="bi bi-{{ $opname->status == 'selesai' ? 'eye' : 'pencil' }}"></i>
                                </a>
                                @if($opname->status != 'selesai')
                                <form action="{{ route('stock-opname.destroy', $opname->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-aksi-hapus" onclick="return confirm('Hapus sesi opname draft ini?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="6">Belum ada sesi stok opname.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($opnames->hasPages())
        <div class="opname-pagination-wrap">{{ $opnames->links() }}</div>
        @endif
    </div>

</div>

{{-- ── MODAL MULAI OPNAME BARU ── --}}
<div class="modal fade modal-kk" id="modalMulaiOpname" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('stock-opname.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Mulai Sesi Stok Opname Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p style="font-size:12.5px; color:#7a8a7a; margin-bottom:14px">
                    <i class="bi bi-info-circle me-1"></i>
                    Sistem akan mencatat snapshot stok semua bahan baku saat ini. Anda bisa mengisi stok fisik hasil hitungan gudang setelah sesi dibuat.
                </p>
                <label class="form-label-kk">Catatan (opsional)</label>
                <textarea name="catatan" class="form-control-kk" rows="2" placeholder="Misal: Opname rutin akhir bulan Juni"></textarea>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Mulai Opname</button>
            </div>
        </form>
    </div>
</div>

@endsection
