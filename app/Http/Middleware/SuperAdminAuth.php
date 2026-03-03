<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\Admin;
use Symfony\Component\HttpFoundation\Response;

class SuperAdminAuth
{
    public function handle(Request $request, Closure $next): Response
    {
        // Belum login
        if (!session()->has('admin_id')) {
            abort(401, 'Unauthorized');
        }

        $admin = Admin::find(session('admin_id'));

        // Admin tidak valid / nonaktif
        if (!$admin || $admin->is_active == 0) {
            session()->flush();
            abort(401, 'Unauthorized');
        }

        // Bukan super admin
        if ($admin->role !== 'super_admin') {
            abort(403, 'Forbidden');
        }

        return $next($request);
    }
}
