<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    private function admin()
    {
        return Auth::guard('admin')->user();
    }

    public function index()
    {
        $admin = $this->admin();

        if (!$admin) {
            return redirect()->route('login');
        }

        return view('profile.index', compact('admin'));
    }

    public function edit()
    {
        $admin = $this->admin();
        return view('profile.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        $admin = $this->admin();

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:admins,email,' . $admin->id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        $data = $request->only(['name', 'email', 'phone', 'address']);

        // Upload avatar
        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $data['avatar'] = $avatarPath;

            if ($admin->avatar && Storage::disk('public')->exists($admin->avatar)) {
                Storage::disk('public')->delete($admin->avatar);
            }
        }

        $admin->update($data);

        return redirect()->route('profile.index')
            ->with('success', 'Profil berhasil diperbarui!');
    }

    public function changePassword()
    {
        $admin = $this->admin();
        return view('profile.change-password', compact('admin'));
    }

    public function updatePassword(Request $request)
    {
        $admin = $this->admin();

        $request->validate([
            'current_password' => 'required|string|min:6',
            'new_password' => 'required|string|min:6|confirmed',
        ]);

        if (!Hash::check($request->current_password, $admin->password)) {
            return back()->with('error', 'Password saat ini salah!');
        }

        $admin->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->route('profile.index')
            ->with('success', 'Password berhasil diubah!');
    }
}
