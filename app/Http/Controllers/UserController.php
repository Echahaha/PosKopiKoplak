<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Tampilkan halaman Pengaturan (daftar user/barista).
     */
    public function index()
    {
        $users = User::orderByRaw("role = 'owner' DESC")
            ->orderBy('name')
            ->get();

        return view('pengaturan.index', compact('users'));
    }

    /**
     * Simpan akun barista baru.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'unique:users,username', 'alpha_dash'],
            'email'    => ['required', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['owner', 'barista'])],
        ], [
            'username.unique' => 'Username sudah dipakai, coba username lain.',
            'email.unique'    => 'Email sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, - dan _ (tanpa spasi).',
        ]);

        User::create([
            'name'      => $validated['name'],
            'username'  => $validated['username'],
            'email'     => $validated['email'],
            'password'  => $validated['password'],
            'role'      => $validated['role'],
            'is_active' => true,
        ]);

        return redirect()->route('pengaturan.index')
            ->with('success', 'Akun ' . $validated['name'] . ' berhasil ditambahkan.');
    }

    /**
     * Perbarui data user (nama, username, email, role, dan password jika diisi).
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => ['required', 'string', 'max:255'],
            'username' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('users', 'username')->ignore($user->id)],
            'email'    => ['required', 'email', 'max:255', Rule::unique('users', 'email')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:6'],
            'role'     => ['required', Rule::in(['owner', 'barista'])],
        ], [
            'username.unique' => 'Username sudah dipakai, coba username lain.',
            'email.unique'    => 'Email sudah terdaftar.',
            'username.alpha_dash' => 'Username hanya boleh huruf, angka, - dan _ (tanpa spasi).',
        ]);

        // Owner tidak boleh menurunkan role dirinya sendiri (mencegah lockout)
        if ($user->id === Auth::id() && $validated['role'] !== 'owner') {
            return back()->withErrors(['role' => 'Anda tidak bisa mengubah role akun Anda sendiri.']);
        }

        $user->name     = $validated['name'];
        $user->username = $validated['username'];
        $user->email    = $validated['email'];
        $user->role     = $validated['role'];

        if (!empty($validated['password'])) {
            $user->password = $validated['password'];
        }

        $user->save();

        return redirect()->route('pengaturan.index')
            ->with('success', 'Data ' . $user->name . ' berhasil diperbarui.');
    }

    /**
     * Aktifkan / nonaktifkan akun (soft-disable, tanpa hapus data).
     */
    public function toggleActive(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => 'Anda tidak bisa menonaktifkan akun Anda sendiri.']);
        }

        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('pengaturan.index')
            ->with('success', 'Akun ' . $user->name . ' berhasil ' . $status . '.');
    }

    /**
     * Hapus akun secara permanen.
     */
    public function destroy(User $user)
    {
        if ($user->id === Auth::id()) {
            return back()->withErrors(['user' => 'Anda tidak bisa menghapus akun Anda sendiri.']);
        }

        $nama = $user->name;
        $user->delete();

        return redirect()->route('pengaturan.index')
            ->with('success', 'Akun ' . $nama . ' berhasil dihapus.');
    }
}
