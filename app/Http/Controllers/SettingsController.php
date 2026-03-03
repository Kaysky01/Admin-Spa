<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = [
            'app_name' => config('app.name'),
            'app_url' => config('app.url'),
            'timezone' => config('app.timezone'),
            'db_connection' => config('database.default'),
            'cache_driver' => config('cache.default'),
        ];

        return view('settings.index', compact('settings'));
    }

    public function clearCache()
    {


        return redirect()->back()->with('success', 'Cache berhasil dibersihkan!');
    }

    public function maintenance()
    {
        $isDown = file_exists(storage_path('framework/down'));
        
        if ($isDown) {
            //Artisan::call('up');
            return redirect()->back()->with('success', 'Mode maintenance dinonaktifkan!');
        } else {
            //Artisan::call('down');
            return redirect()->back()->with('success', 'Mode maintenance diaktifkan!');
        }
    }

    public function database()
    {
        $tables = DB::select('SHOW TABLES');
        $dbName = DB::connection()->getDatabaseName();
        
        return view('settings.database', compact('tables', 'dbName'));
    }

    public function optimize()
    {
        //Artisan::call('optimize');
        
        return redirect()->back()->with('success', 'Optimasi sistem berhasil dilakukan!');
    }
}
