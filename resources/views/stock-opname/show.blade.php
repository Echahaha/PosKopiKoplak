@extends('dashboard')

@section('content')

<style>
    @import url('https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap');

    .opn-wrapper * {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    .opn-wrapper {
        background: #f5f4f0;
        min-height: 100vh;
        padding: 4px 0 32px;
    }

    .opn-header {
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        margin-bottom: 20px;
        flex-wrap: wrap;
        gap: 12px;
    }

    .opn-header h4 {
        font-size: 22px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 4px;
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .opn-header p {
        font-size: 13px;
        color: #7a8a7a;
        margin: 0;
    }

    .opn-chip {
        font-size: 12px;
        font-weight: 700;
        padding: 3px 12px;
        border-radius: 20px;
    }

    .opn-chip.draft {
        background: #fff3de;
        color: #d97706;
        border: 1px solid #f6d860;
    }

    .opn-chip.selesai {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
    }

    .btn-balik {
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
    }

    .btn-balik:hover {
        background: #faf9f6;
        color: #3a4a3a;
    }

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

    .alert-kk.success {
        background: #e6f4ee;
        border: 1px solid #b2dcc8;
        color: #2d6a4e;
    }

    .alert-kk.error {
        background: #fde8e8;
        border: 1px solid #f5b8b8;
        color: #9b1c1c;
    }

    .btn-close-kk {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 14px;
        cursor: pointer;
        opacity: 0.7;
        color: inherit;
    }

    .card-kk {
        background: white;
        border-radius: 18px;
        border: 1px solid #eceae4;
        overflow: hidden;
        margin-bottom: 18px;
    }

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
        padding: 10px 16px;
        font-size: 13px;
        color: #3a4a3a;
        border-bottom: 1px solid #f5f4f0;
        vertical-align: middle;
    }

    .table-kk tbody tr:last-child td {
        border-bottom: none;
    }

    .td-name {
        font-weight: 700;
        color: #1a1f1a;
    }

    .td-muted {
        color: #8a9a8a;
        font-size: 12px;
    }

    .opn-input-fisik {
        border: 1px solid #e4e0d8;
        border-radius: 9px;
        padding: 7px 10px;
        font-size: 13px;
        width: 100px;
        background: #faf9f6;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .opn-input-fisik:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61, 122, 94, 0.12);
        background: white;
        outline: none;
    }

    .opn-input-fisik:disabled {
        background: #f5f4f0;
        color: #9aaa9a;
        cursor: not-allowed;
    }

    .opn-input-ket {
        border: 1px solid #e4e0d8;
        border-radius: 9px;
        padding: 7px 10px;
        font-size: 12px;
        width: 100%;
        background: #faf9f6;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .opn-input-ket:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61, 122, 94, 0.12);
        background: white;
        outline: none;
    }

    .opn-input-ket:disabled {
        background: #f5f4f0;
        color: #9aaa9a;
        cursor: not-allowed;
    }

    .selisih-badge {
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 10px;
        border-radius: 20px;
        display: inline-block;
        min-width: 50px;
        text-align: center;
    }

    .selisih-zero {
        background: #f5f4f0;
        color: #9aaa9a;
    }

    .selisih-plus {
        background: #e6f4ee;
        color: #3d7a5e;
    }

    .selisih-minus {
        background: #fde8e8;
        color: #c53030;
    }

    .selisih-belum {
        color: #c5b89a;
        font-style: italic;
        font-size: 12px;
    }

    .opn-footer-actions {
        padding: 16px 22px;
        border-top: 1px solid #f0ede6;
        display: flex;
        justify-content: space-between;
        align-items: center;
        flex-wrap: wrap;
        gap: 12px;
    }

    .btn-simpan-draft {
        background: white;
        border: 1px solid #3d7a5e;
        color: #3d7a5e;
        border-radius: 20px;
        padding: 9px 20px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-simpan-draft:hover {
        background: #e6f4ee;
    }

    .btn-selesaikan {
        background: #3d7a5e;
        border: none;
        color: white;
        border-radius: 20px;
        padding: 9px 22px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
    }

    .btn-selesaikan:hover {
        background: #2d6a4e;
    }

    .btn-selesaikan:disabled {
        background: #c5d6cc;
        cursor: not-allowed;
    }

    .opn-info-box {
        background: #faf9f6;
        border: 1px solid #eceae4;
        border-radius: 12px;
        padding: 12px 14px;
        display: flex;
        align-items: flex-start;
        gap: 10px;
        margin-bottom: 18px;
        font-size: 12.5px;
        color: #6a7a6a;
    }

    .opn-info-box i {
        color: #d97706;
        margin-top: 1px;
    }

    .opn-modal-overlay {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(26, 31, 26, 0.45);
        z-index: 9999;
        align-items: center;
        justify-content: center;
        padding: 16px;
    }

    .opn-modal-overlay.show {
        display: flex;
    }

    .opn-modal-box {
        background: white;
        border-radius: 18px;
        max-width: 380px;
        width: 100%;
        padding: 26px 24px 20px;
        text-align: center;
        box-shadow: 0 20px 50px rgba(0, 0, 0, 0.18);
    }

    .opn-modal-icon {
        width: 52px;
        height: 52px;
        border-radius: 50%;
        background: #e6f4ee;
        color: #3d7a5e;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 24px;
        margin: 0 auto 14px;
    }

    .opn-modal-box h5 {
        font-size: 16px;
        font-weight: 800;
        color: #1a1f1a;
        margin-bottom: 8px;
    }

    .opn-modal-box p {
        font-size: 13px;
        color: #7a8a7a;
        margin-bottom: 22px;
        line-height: 1.5;
    }

    .opn-modal-actions {
        display: flex;
        gap: 10px;
    }

    .opn-modal-actions button {
        flex: 1;
        border-radius: 20px;
        padding: 10px 16px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .btn-modal-batal {
        background: white;
        border: 1px solid #e4e0d8;
        color: #3a4a3a;
    }

    .btn-modal-batal:hover {
        background: #faf9f6;
    }

    .btn-modal-ya {
        background: #3d7a5e;
        border: none;
        color: white;
    }

    .btn-modal-ya:hover {
        background: #2d6a4e;
    }
</style>

<div class="opn-wrapper">

    <div class="opn-header">
        <div>
            <h4>
                <i class="bi bi-clipboard-check" style="color:#3d7a5e"></i>
                {{ $opname->kode_opname }}
                @if($opname->status == 'selesai')
                <span class="opn-chip selesai">Selesai</span>
                @else
                <span class="opn-chip draft">Draft</span>
                @endif
            </h4>
            <p>
                Tanggal: {{ \Carbon\Carbon::parse($opname->tanggal_opname)->translatedFormat('d M Y') }}
                &middot; Dibuat oleh: {{ $opname->user->name ?? '-' }}
                @if($opname->catatan) &middot; "{{ $opname->catatan }}" @endif
            </p>
        </div>
        <a href="{{ route('stock-opname.index') }}" class="btn-balik"><i class="bi bi-arrow-left"></i> Kembali</a>
    </div>

    @if($opname->status != 'selesai')
    <div class="opn-info-box">
        <i class="bi bi-info-circle-fill"></i>
        <span>Isi kolom <strong>Stok Fisik</strong> berdasarkan hasil hitungan langsung di gudang. Selisih akan terhitung otomatis. Anda bisa menyimpan sebagian dulu lalu lanjutkan nanti — stok baru akan diterapkan setelah Anda menekan <strong>"Selesaikan Opname"</strong>.</span>
    </div>
    @endif

    <form id="formStokFisik" action="{{ route('stock-opname.updateDetails', $opname->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="card-kk">
            <div class="table-responsive">
                <table class="table table-kk mb-0">
                    <thead>
                        <tr>
                            <th style="padding-left:22px">Bahan Baku</th>
                            <th>Stok Sistem</th>
                            <th>Stok Fisik</th>
                            <th>Selisih</th>
                            <th>Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($opname->details as $detail)
                        <tr>
                            <td class="td-name" style="padding-left:22px">
                                {{ $detail->product->name ?? 'Bahan dihapus' }}
                                <div class="td-muted">{{ $detail->product->unit ?? '' }}</div>
                            </td>
                            <td class="td-muted">{{ $detail->stok_sistem }} {{ $detail->product->unit ?? '' }}</td>
                            <td>
                                <input type="number"
                                    class="opn-input-fisik"
                                    name="stok_fisik[{{ $detail->id }}]"
                                    value="{{ $detail->stok_fisik }}"
                                    min="0"
                                    {{ $opname->status == 'selesai' ? 'disabled' : '' }}
                                    data-sistem="{{ $detail->stok_sistem }}"
                                    onchange="opnHitungSelisih(this, {{ $detail->id }})">
                            </td>
                            <td>
                                <span class="selisih-badge {{ $detail->selisih === null ? 'selisih-belum' : ($detail->selisih == 0 ? 'selisih-zero' : ($detail->selisih > 0 ? 'selisih-plus' : 'selisih-minus')) }}"
                                    id="selisihBadge{{ $detail->id }}">
                                    @if($detail->selisih === null)
                                    Belum diisi
                                    @else
                                    {{ $detail->selisih > 0 ? '+' : '' }}{{ $detail->selisih }}
                                    @endif
                                </span>
                            </td>
                            <td>
                                <input type="text"
                                    class="opn-input-ket"
                                    name="keterangan[{{ $detail->id }}]"
                                    value="{{ $detail->keterangan }}"
                                    placeholder="Misal: rusak, tumpah, dll"
                                    {{ $opname->status == 'selesai' ? 'disabled' : '' }}>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" style="text-align:center; padding:32px; color:#9aaa9a; font-style:italic">Tidak ada item.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if($opname->status != 'selesai')
            <div class="opn-footer-actions">
                <button type="submit" class="btn-simpan-draft">
                    <i class="bi bi-save me-1"></i> Simpan Sementara
                </button>
                <span style="font-size:12px; color:#9aaa9a">
                    Pastikan semua item terisi sebelum menyelesaikan opname.
                </span>
            </div>
            @endif
        </div>
    </form>

    @if($opname->status != 'selesai')
    <div style="display:flex; justify-content:flex-end; margin-top:12px">
        <button type="button" class="btn-selesaikan" id="btnSelesaikan" onclick="opnSelesaikan()">
            <i class="bi bi-check-circle me-1"></i> Selesaikan Opname
        </button>
    </div>

    <form id="formFinish" action="{{ route('stock-opname.finish', $opname->id) }}" method="POST" style="display:none">
        @csrf
    </form>
    @endif

    <div class="opn-modal-overlay" id="opnModalKonfirmasi">
        <div class="opn-modal-box">
            <div class="opn-modal-icon"><i class="bi bi-question-lg"></i></div>
            <h5>Selesaikan Sesi Opname?</h5>
            <p>Stok bahan baku akan disesuaikan otomatis sesuai stok fisik dan tidak bisa diubah lagi setelah ini.</p>
            <div class="opn-modal-actions">
                <button type="button" class="btn-modal-batal" onclick="opnTutupModal()">Batal</button>
                <button type="button" class="btn-modal-ya" id="btnModalYa" onclick="opnKonfirmasiSelesai()">Ya, Selesaikan</button>
            </div>
        </div>
    </div>

</div>

<script>
    function opnHitungSelisih(input, detailId) {
        const sistem = parseInt(input.getAttribute('data-sistem')) || 0;
        const fisik = input.value === '' ? null : parseInt(input.value);
        const badge = document.getElementById('selisihBadge' + detailId);

        if (fisik === null || isNaN(fisik)) {
            badge.textContent = 'Belum diisi';
            badge.className = 'selisih-badge selisih-belum';
            return;
        }

        const selisih = fisik - sistem;
        badge.textContent = (selisih > 0 ? '+' : '') + selisih;
        badge.className = 'selisih-badge ' + (selisih == 0 ? 'selisih-zero' : (selisih > 0 ? 'selisih-plus' : 'selisih-minus'));
    }

    function opnSelesaikan() {
        document.getElementById('opnModalKonfirmasi').classList.add('show');
    }

    function opnTutupModal() {
        document.getElementById('opnModalKonfirmasi').classList.remove('show');
    }

    function opnKonfirmasiSelesai() {
        opnTutupModal();

        const btn = document.getElementById('btnSelesaikan');
        btn.disabled = true;
        btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i> Menyimpan...';

        const formStokFisik = document.getElementById('formStokFisik');
        const formData = new FormData(formStokFisik);
        const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

        fetch(formStokFisik.action, {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': csrfToken
                }
            })
            .then(async response => {
                if (!response.ok) {
                    const errText = await response.text();
                    console.error('Status:', response.status, 'Body:', errText);
                    throw new Error('Gagal menyimpan stok fisik');
                }

                // Lanjut ke proses finish
                return fetch(document.getElementById('formFinish').action, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': csrfToken
                    }
                });
            })
            .then(async response => {
                if (!response.ok) {
                    const errText = await response.text();
                    console.error('Status:', response.status, 'Body:', errText);
                    throw new Error('Gagal menyelesaikan opname');
                }
                // Berhasil, kembali ke halaman daftar stok opname
                window.location.href = '{{ route('stock-opname.index')}}';
            })
            .catch((err) => {
                alert('Gagal menyelesaikan opname. Cek console untuk detail error.');
                console.error(err);
                btn.disabled = false;
                btn.innerHTML = '<i class="bi bi-check-circle me-1"></i> Selesaikan Opname';
            });
    }
</script>

@endsection