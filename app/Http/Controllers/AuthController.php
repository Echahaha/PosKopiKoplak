<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\User;

class AuthController extends Controller
{
    // ─────────────────────────────────────────────────────────────
    //  LOGIN
    // ─────────────────────────────────────────────────────────────

    /**
     * Menampilkan halaman login
     */
    public function showLogin()
    {
        return view('auth.login');
    }

    /**
     * Proses pengecekan login
     */
    public function login(Request $request)
    {
        // 1. Validasi input
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);

        // 2. Coba login (Auth::attempt akan otomatis mengecek password yang di-hash)
        if (Auth::attempt($credentials)) {

            // 2a. Cek apakah akun masih aktif
            if (!Auth::user()->is_active) {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();

                return back()->withErrors([
                    'username' => 'Akun Anda sudah dinonaktifkan. Silakan hubungi Owner.',
                ])->onlyInput('username');
            }

            // Jika berhasil, buat session baru
            $request->session()->regenerate();

            return redirect()->intended('dashboard')
                ->with('success', 'Selamat datang kembali, ' . Auth::user()->name . '!');
        }

        // 3. Jika gagal
        return back()->withErrors([
            'username' => 'Username atau password salah.',
        ])->onlyInput('username');
    }

    /**
     * Proses logout
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/')->with('success', 'Anda telah keluar dari sistem.');
    }

    // ─────────────────────────────────────────────────────────────
    //  LUPA SANDI — Tampilkan form input email
    // ─────────────────────────────────────────────────────────────

    public function showForgotPassword()
    {
        return view('auth.forgot-password');
    }

    // ─────────────────────────────────────────────────────────────
    //  LUPA SANDI — Kirim link reset ke email
    // ─────────────────────────────────────────────────────────────

    public function sendResetLink(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ], [
            'email.required' => 'Email wajib diisi.',
            'email.email'    => 'Format email tidak valid.',
        ]);

        $user = User::where('email', $request->email)->first();

        // Jika email tidak terdaftar, tampilkan pesan error
        if (!$user) {
            return back()->withErrors([
                'email' => 'Email ini tidak terdaftar di sistem. Pastikan email yang kamu masukkan benar.',
            ])->onlyInput('email');
        }

        // Hapus token lama untuk email ini (kalau ada)
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        // Buat token baru
        $token = Str::random(64);

        DB::table('password_reset_tokens')->insert([
            'email'      => $request->email,
            'token'      => Hash::make($token),
            'created_at' => Carbon::now(),
        ]);

        // Kirim email
        $resetUrl = route('password.reset', ['token' => $token])
            . '?email=' . urlencode($request->email);

        try {
            Mail::send('emails.reset-password', [
                'user'     => $user,
                'resetUrl' => $resetUrl,
            ], function ($message) use ($request) {
                $message->to($request->email)
                    ->subject('Reset Sandi — Kopi Koplak POS');
            });
        } catch (\Exception $e) {
            Log::error('Gagal mengirim email reset password: ' . $e->getMessage());
            return back()->with('error', 'Gagal mengirim email. Silakan coba lagi nanti.');
        }

        return back()->with(
            'status',
            'Link reset sandi telah dikirimkan ke email kamu. Cek inbox (atau folder spam).'
        );
    }

    // ─────────────────────────────────────────────────────────────
    //  RESET SANDI — Tampilkan form password baru
    // ─────────────────────────────────────────────────────────────

    public function showResetForm(Request $request, string $token)
    {
        return view('auth.reset-password', [
            'token' => $token,
            'email' => $request->query('email', ''),
        ]);
    }

    // ─────────────────────────────────────────────────────────────
    //  RESET SANDI — Simpan password baru
    // ─────────────────────────────────────────────────────────────

    public function resetPassword(Request $request)
    {
        $request->validate([
            'token'                 => ['required'],
            'email'                 => ['required', 'email'],
            'password'              => ['required', 'min:8', 'confirmed'],
            'password_confirmation' => ['required'],
        ], [
            'password.min'       => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        // Ambil record token dari DB
        $record = DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->first();

        // Validasi: token harus ada, cocok, dan belum expired (60 menit)
        if (
            !$record ||
            !Hash::check($request->token, $record->token) ||
            Carbon::parse($record->created_at)->addMinutes(60)->isPast()
        ) {
            return back()->withErrors([
                'email' => 'Link reset tidak valid atau sudah kedaluwarsa. Silakan minta link baru.',
            ]);
        }

        // Update password user
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'Email tidak ditemukan.',
            ]);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        // Hapus token setelah berhasil dipakai
        DB::table('password_reset_tokens')
            ->where('email', $request->email)
            ->delete();

        return redirect()->route('login')
            ->with('success', 'Password berhasil diubah! Silakan login dengan password baru.');
    }
}
