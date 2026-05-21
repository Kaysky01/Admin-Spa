<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function create()
    {
        return view('users.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'     => 'required|string|max:255',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'phone'    => 'nullable|string|max:20',
            'npm'      => 'nullable|string|max:50',
            'jurusan'  => 'nullable|string|max:255',
        ]);

        User::create([
            'name'     => $request->name,
            'email'    => $request->email,
            'password' => Hash::make($request->password),
            'phone'    => $request->phone,
            'npm'      => $request->npm,
            'jurusan'  => $request->jurusan,
        ]);

        return redirect()->route('users.index')->with('success', 'Pengguna berhasil ditambahkan!');
    }

    public function index(Request $request)
    {
        $query = User::query();

        // Search
        if ($request->has('search') && $request->search != '') {
            $query->where(function($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('email', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query
    ->withCount('reports')
    ->orderByRaw("
        REGEXP_REPLACE(name, '[^A-Za-z ]', '') ASC,
        CAST(REGEXP_REPLACE(name, '[^0-9]', '') AS UNSIGNED) ASC
    ")
    ->paginate(15);

        return view('users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['reports.category'])->findOrFail($id);
      
  return view('users.show', compact('user'));

    }

    public function destroy($id)
    {
        $user = User::findOrFail($id);
        
        // Delete user's reports first
        $user->reports()->delete();
        
        // Delete user
        $user->delete();

        return redirect()->route('users.index')->with('success', 'User berhasil dihapus!');
    }

    public function block($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_blocked' => true]);

        return redirect()->back()->with('success', 'User berhasil diblokir!');
    }

    public function unblock($id)
    {
        $user = User::findOrFail($id);
        $user->update(['is_blocked' => false]);

        return redirect()->back()->with('success', 'User berhasil dibuka blokirnya!');
    }
}
