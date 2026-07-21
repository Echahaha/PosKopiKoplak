@extends('dashboard')

@section('title', 'Pengaturan')

@section('content')

<style>
    .pengaturan-wrapper * {
        box-sizing: border-box;
    }

    .pengaturan-wrapper {
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

    .alert-kk-danger {
        background: #fde8e8;
        border: 1px solid #f5b8b8;
        color: #9b1c1c;
    }

    .btn-close-kk {
        margin-left: auto;
        background: none;
        border: none;
        font-size: 14px;
        color: inherit;
        cursor: pointer;
        opacity: 0.7;
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
        background: #e6f4ee;
        color: #3d7a5e;
    }

    .card-kk-header h5 {
        font-size: 14px;
        font-weight: 800;
        color: #1a1f1a;
        margin: 0;
    }

    .card-kk-header small {
        display: block;
        font-size: 12px;
        color: #9aaa9a;
        font-weight: 500;
        margin-top: 1px;
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

    .user-avatar {
        width: 34px;
        height: 34px;
        border-radius: 10px;
        background: #e6f4ee;
        color: #3d7a5e;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        font-weight: 800;
        font-size: 13px;
        flex-shrink: 0;
    }

    .user-name-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .badge-role-owner {
        background: #fdf3e2;
        color: #b07a17;
        border: 1px solid #f0d8a8;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 20px;
        text-transform: capitalize;
    }

    .badge-role-barista {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 11px;
        font-weight: 700;
        padding: 4px 11px;
        border-radius: 20px;
        text-transform: capitalize;
    }

    .badge-status-aktif {
        background: #e6f4ee;
        color: #3d7a5e;
        border: 1px solid #b2dcc8;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .badge-status-nonaktif {
        background: #f0ede6;
        color: #8a8a7a;
        border: 1px solid #ddd8cc;
        font-size: 11.5px;
        font-weight: 700;
        padding: 4px 12px;
        border-radius: 20px;
    }

    .tag-anda {
        font-size: 10.5px;
        font-weight: 700;
        color: #9aaa9a;
        background: #f5f4f0;
        padding: 2px 8px;
        border-radius: 20px;
        margin-left: 6px;
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

    .btn-aksi[disabled],
    .btn-aksi.disabled {
        opacity: 0.35;
        cursor: not-allowed;
        pointer-events: none;
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
        width: 100%;
    }

    .form-control-kk:focus,
    .form-select-kk:focus {
        border-color: #3d7a5e;
        box-shadow: 0 0 0 3px rgba(61, 122, 94, 0.12);
        background: white;
        outline: none;
    }

    .form-hint-kk {
        font-size: 11px;
        color: #9aaa9a;
        margin-top: 4px;
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

    .empty-row td {
        text-align: center;
        padding: 32px 16px;
        color: #9aaa9a;
        font-size: 13px;
        font-style: italic;
    }
</style>

<div class="pengaturan-wrapper">

    {{-- ── PAGE HEADER ── --}}
    <div class="prod-page-header">
        <div>
            <h4>Pengaturan</h4>
            <p>Kelola akun Owner dan Barista yang bisa login ke kasir ini.</p>
        </div>
        <button class="btn-tambah-kk" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
            <i class="bi bi-person-plus"></i> Tambah Barista
        </button>
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

    {{-- ── ALERT ERROR ── --}}
    @if($errors->any())
    <div class="alert-kk alert-kk-danger" id="alertError">
        <i class="bi bi-exclamation-circle-fill"></i>
        {{ $errors->first() }}
        <button class="btn-close-kk" onclick="document.getElementById('alertError').remove()">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>
    @endif

    {{-- ── TABEL USER ── --}}
    <div class="card-kk mb-4">
        <div class="card-kk-header">
            <div class="card-kk-header-icon">
                <i class="bi bi-people"></i>
            </div>
            <div>
                <h5>Daftar Akun</h5>
                <small>{{ $users->count() }} akun terdaftar di kasir ini</small>
            </div>
        </div>
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Nama</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td style="padding-left:22px">
                            <div class="user-name-cell">
                                <div class="user-avatar">{{ strtoupper(substr($user->name, 0, 1)) }}</div>
                                <div>
                                    <div class="td-name">
                                        {{ $user->name }}
                                        @if($user->id === auth()->id())
                                        <span class="tag-anda">Anda</span>
                                        @endif
                                    </div>
                                    <div class="td-muted">{{ $user->email }}</div>
                                </div>
                            </div>
                        </td>
                        <td>{{ '@'.$user->username }}</td>
                        <td>
                            <span class="{{ $user->role === 'owner' ? 'badge-role-owner' : 'badge-role-barista' }}">
                                {{ $user->role }}
                            </span>
                        </td>
                        <td>
                            @if($user->is_active)
                            <span class="badge-status-aktif">Aktif</span>
                            @else
                            <span class="badge-status-nonaktif">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            {{-- Tombol toggle nonaktifkan DIHAPUS -- barista login pakai
                                 device kedai (shared), bukan device pribadi, sehingga
                                 skenario "nonaktifkan sementara" jadi kurang relevan.
                                 Kalau ada barista resign/keluar, langsung hapus akunnya
                                 lewat tombol Hapus di bawah. --}}
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-aksi btn-aksi-edit"
                                    data-bs-toggle="modal"
                                    data-bs-target="#modalEditUser{{ $user->id }}"
                                    title="Edit">
                                    <i class="bi bi-pencil"></i>
                                </button>

                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit"
                                        class="btn-aksi btn-aksi-hapus {{ $user->id === auth()->id() ? 'disabled' : '' }}"
                                        title="Hapus"
                                        @if($user->id === auth()->id()) disabled @endif
                                        onclick="return confirm('Hapus akun {{ $user->name }} secara permanen?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row">
                        <td colspan="5">Belum ada akun yang terdaftar.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>{{-- end .pengaturan-wrapper --}}


{{-- ────────────────────────────────────────
     MODAL TAMBAH USER
──────────────────────────────────────── --}}
<div class="modal fade modal-kk" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('users.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah Akun Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label-kk">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control-kk" placeholder="Misal: Budi Santoso" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Username</label>
                        <input type="text" name="username" class="form-control-kk" placeholder="budi" required>
                        <div class="form-hint-kk">Tanpa spasi, dipakai untuk login.</div>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Email</label>
                        <input type="email" name="email" class="form-control-kk" placeholder="budi@email.com" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-kk">Password</label>
                    <input type="password" name="password" class="form-control-kk" placeholder="Minimal 6 karakter" required minlength="6">
                </div>

                <div class="mb-1">
                    <label class="form-label-kk">Role</label>
                    <div class="radio-kk">
                        <label class="radio-kk-item active" id="add-lbl-barista">
                            <input type="radio" name="role" value="barista" checked>
                            <i class="bi bi-cup-hot me-1"></i> Barista
                        </label>
                        <label class="radio-kk-item" id="add-lbl-owner">
                            <input type="radio" name="role" value="owner">
                            <i class="bi bi-shield-check me-1"></i> Owner
                        </label>
                    </div>
                </div>

            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan Akun</button>
            </div>
        </form>
    </div>
</div>

{{-- ────────────────────────────────────────
     MODAL EDIT USER (per baris)
──────────────────────────────────────── --}}
@foreach($users as $user)
<div class="modal fade modal-kk" id="modalEditUser{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit Akun: {{ $user->name }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">

                <div class="mb-3">
                    <label class="form-label-kk">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control-kk" value="{{ $user->name }}" required>
                </div>

                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Username</label>
                        <input type="text" name="username" class="form-control-kk" value="{{ $user->username }}" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Email</label>
                        <input type="email" name="email" class="form-control-kk" value="{{ $user->email }}" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label-kk">Password Baru</label>
                    <input type="password" name="password" class="form-control-kk" placeholder="Kosongkan jika tidak diubah" minlength="6">
                    <div class="form-hint-kk">Biarkan kosong jika tidak ingin mengganti password.</div>
                </div>

                <div class="mb-1">
                    <label class="form-label-kk">Role</label>
                    <div class="radio-kk">
                        <label class="radio-kk-item {{ $user->role === 'barista' ? 'active' : '' }}" id="edit-lbl-barista-{{ $user->id }}">
                            <input type="radio" name="role" value="barista" {{ $user->role === 'barista' ? 'checked' : '' }}
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <i class="bi bi-cup-hot me-1"></i> Barista
                        </label>
                        <label class="radio-kk-item {{ $user->role === 'owner' ? 'active' : '' }}" id="edit-lbl-owner-{{ $user->id }}">
                            <input type="radio" name="role" value="owner" {{ $user->role === 'owner' ? 'checked' : '' }}
                                {{ $user->id === auth()->id() ? 'disabled' : '' }}>
                            <i class="bi bi-shield-check me-1"></i> Owner
                        </label>
                    </div>
                    @if($user->id === auth()->id())
                    <div class="form-hint-kk">Anda tidak bisa mengubah role akun Anda sendiri.</div>
                    @endif
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

<script>
    // ── TOGGLE TAMPILAN ROLE (Modal Tambah) ──
    document.querySelectorAll('#modalTambahUser input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('add-lbl-barista').classList.toggle('active', this.value === 'barista');
            document.getElementById('add-lbl-owner').classList.toggle('active', this.value === 'owner');
        });
    });

    // ── TOGGLE TAMPILAN ROLE (Modal Edit, per user) ──
    document.querySelectorAll('[id^="modalEditUser"] input[name="role"]').forEach(radio => {
        radio.addEventListener('change', function() {
            const modal = this.closest('.modal');
            modal.querySelectorAll('.radio-kk-item').forEach(item => item.classList.remove('active'));
            this.closest('.radio-kk-item').classList.add('active');
        });
    });
</script>

@endsection