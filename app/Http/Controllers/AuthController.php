<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email'    => 'required|email',
            'password' => 'required'
        ]);

        $admin = Admin::where('email', $credentials['email'])->first();

        if (!$admin || !Hash::check($credentials['password'], $admin->password)) {
            return back()->withErrors([
                'email' => 'Email atau password salah'
            ]);
        }

        if ($admin->is_active == 0) {
            return back()->withErrors([
                'email' => 'Akun admin nonaktif'
            ]);
        }

        // LOGIN
        Auth::guard('admin')->login($admin);

        // 🔥 INI YANG PENTING (BEDAIN ADMIN & SUPER ADMIN)
        session([
            'admin_id'   => $admin->id,
            'admin_name' => $admin->name,
            'admin_role' => $admin->role, 
        ]);

        return redirect()->route('dashboard');
    }

    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
