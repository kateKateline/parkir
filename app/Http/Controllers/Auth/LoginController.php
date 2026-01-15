<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\LogAktifitas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where('username', $request->username)->first();

        if ($user && Hash::check($request->password, $user->password)) {
            if (!$user->status_aktif) {
                return back()->withErrors(['username' => 'Akun Anda tidak aktif'])->withInput();
            }

            Auth::login($user);

            // Log aktivitas
            LogAktifitas::create([
                'id_user' => $user->id_user,
                'aktivitas' => 'Login ke sistem',
                'waktu_aktivitas' => now(),
            ]);

            // Redirect berdasarkan role
            if ($user->role === 'admin') {
                return redirect()->route('admin.dashboard');
            } elseif ($user->role === 'petugas') {
                return redirect()->route('petugas.dashboard');
            } elseif ($user->role === 'owner') {
                return redirect()->route('owner.dashboard');
            }
        }

        return back()->withErrors(['username' => 'Username atau password salah'])->withInput();
    }

    public function logout(Request $request)
    {
        if (Auth::check()) {
            try {
                LogAktifitas::create([
                    'id_user' => Auth::id(),
                    'aktivitas' => 'Logout dari sistem',
                    'waktu_aktivitas' => now(),
                ]);
            } catch (\Exception $e) {
                // Skip jika ada error saat log
            }
        }

        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('message', 'Anda  telahlogout');
    }
}
