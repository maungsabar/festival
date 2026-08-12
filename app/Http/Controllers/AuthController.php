<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (session('admin_user')) {
            return redirect()->route('admin.dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        // Laravel 11: password cast 'hashed' menyimpan hash bcrypt.
        // Gunakan Hash::check() untuk verifikasi.
        if ($user && Hash::check($request->password, $user->password)) {
            // SECURITY: Regenerate session ID to prevent Session Fixation Attack
            $request->session()->regenerate();

            session([
                'admin_user' => [
                    'id'       => $user->id,
                    'username' => $user->username,
                    'role'     => $user->role,
                ],
            ]);
            return redirect()->route('admin.dashboard')
                ->with('success', 'Selamat datang, ' . $user->username . '!');
        }

        return back()
            ->with('error', 'Username atau password salah.')
            ->withInput(['username' => $request->username]);
    }

    public function logout(Request $request)
    {
        // SECURITY: Invalidate entire session (not just forget the key)
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login')->with('info', 'Berhasil logout.');
    }
}
