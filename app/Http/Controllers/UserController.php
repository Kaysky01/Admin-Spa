<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
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
