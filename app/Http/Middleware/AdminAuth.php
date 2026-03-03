<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AdminAuth
{
    public function handle($request, Closure $next)
    {
        if (!session()->has('admin_id')) {
    return redirect('/login');
}
        $admin = \App\Models\Admin::find(session('admin_id'));

        return $next($request);
    }
}
