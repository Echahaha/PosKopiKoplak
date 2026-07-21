{{-- ════════════════════════════════════
     PANEL: USER
════════════════════════════════════ --}}
<div class="md-tab-panel" data-panel="user">
    <div class="card-kk mb-4">
        <div class="card-kk-header">
            <div class="card-kk-header-left">
                <div class="card-kk-header-icon icon-blue-soft"><i class="bi bi-people"></i></div>
                <h5>Daftar Pengguna</h5>
            </div>
            <button class="btn-tambah-kk" data-bs-toggle="modal" data-bs-target="#modalTambahUser">
                <i class="bi bi-plus-lg"></i> Tambah User
            </button>
        </div>
        <div class="table-responsive">
            <table class="table table-kk mb-0">
                <thead>
                    <tr>
                        <th style="padding-left:22px">Nama</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($users as $user)
                    <tr>
                        <td class="td-name" style="padding-left:22px">{{ $user->name }}</td>
                        <td class="td-muted">{{ $user->username }}</td>
                        <td class="td-muted">{{ $user->email }}</td>
                        <td>
                            @if($user->role == 'owner')
                                <span class="badge-role-owner">Owner</span>
                            @else
                                <span class="badge-role-barista">Barista</span>
                            @endif
                        </td>
                        <td>
                            @if($user->is_active)
                                <span class="badge-active">Aktif</span>
                            @else
                                <span class="badge-inactive">Nonaktif</span>
                            @endif
                        </td>
                        <td class="text-center">
                            <div class="d-flex justify-content-center gap-2">
                                <button class="btn-aksi btn-aksi-edit" data-bs-toggle="modal" data-bs-target="#modalEditUser{{ $user->id }}">
                                    <i class="bi bi-pencil"></i>
                                </button>
                                @if($user->id !== auth()->id())
                                <form action="{{ route('users.toggleActive', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('PATCH')
                                    <button type="submit" class="btn-aksi" style="color:#d97706" title="{{ $user->is_active ? 'Nonaktifkan' : 'Aktifkan' }}">
                                        <i class="bi bi-power"></i>
                                    </button>
                                </form>
                                <form action="{{ route('users.destroy', $user->id) }}" method="POST" class="d-inline">
                                    @csrf @method('DELETE')
                                    <button type="submit" class="btn-aksi btn-aksi-hapus" onclick="return confirm('Hapus user &quot;{{ $user->name }}&quot;?')">
                                        <i class="bi bi-trash"></i>
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr class="empty-row"><td colspan="6">Belum ada user.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- ── MODAL TAMBAH USER ── --}}
<div class="modal fade modal-kk" id="modalTambahUser" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('users.store') }}" method="POST" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Tambah User Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label class="form-label-kk">Nama Lengkap</label>
                    <input type="text" name="name" class="form-control-kk" required>
                </div>
                <div class="row g-3 mb-3">
                    <div class="col-md-6">
                        <label class="form-label-kk">Username</label>
                        <input type="text" name="username" class="form-control-kk" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label-kk">Role</label>
                        <select name="role" class="form-select-kk" required>
                            <option value="barista">Barista</option>
                            <option value="owner">Owner</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-kk">Email</label>
                    <input type="email" name="email" class="form-control-kk" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-kk">Password</label>
                    <input type="password" name="password" class="form-control-kk" minlength="6" required>
                </div>
            </div>
            <div class="modal-footer gap-2">
                <button type="button" class="btn-modal-batal" data-bs-dismiss="modal">Batal</button>
                <button type="submit" class="btn-modal-simpan">Simpan</button>
            </div>
        </form>
    </div>
</div>

{{-- ── MODAL EDIT USER (per item) ── --}}
@foreach($users as $user)
<div class="modal fade modal-kk" id="modalEditUser{{ $user->id }}" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <form action="{{ route('users.update', $user->id) }}" method="POST" class="modal-content">
            @csrf @method('PUT')
            <div class="modal-header">
                <h5 class="modal-title">Edit User</h5>
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
                        <label class="form-label-kk">Role</label>
                        @if($user->id === auth()->id())
                            <select class="form-select-kk" disabled>
                                <option selected>{{ ucfirst($user->role) }} (Anda)</option>
                            </select>
                            <input type="hidden" name="role" value="{{ $user->role }}">
                        @else
                            <select name="role" class="form-select-kk" required>
                                <option value="barista" {{ $user->role == 'barista' ? 'selected' : '' }}>Barista</option>
                                <option value="owner" {{ $user->role == 'owner' ? 'selected' : '' }}>Owner</option>
                            </select>
                        @endif
                    </div>
                </div>
                <div class="mb-3">
                    <label class="form-label-kk">Email</label>
                    <input type="email" name="email" class="form-control-kk" value="{{ $user->email }}" required>
                </div>
                <div class="mb-3">
                    <label class="form-label-kk">Password Baru <span style="text-transform:none; font-weight:500">(kosongkan jika tidak diubah)</span></label>
                    <input type="password" name="password" class="form-control-kk" minlength="6">
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
