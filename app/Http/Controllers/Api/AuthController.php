<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|string',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Email atau password salah',
            ], 401);
        }

        // HAPUS TOKEN LAMA
        $user->tokens()->delete();

        // BUAT TOKEN SEKALI SAJA
        $token = $user->createToken('mobile')->plainTextToken;

        return response()->json([
            'success' => true,
            'data' => [
                'user'  => $user,
                'token' => $token, // ⬅️ TOKEN INI YANG DIPAKAI FLUTTER
            ]
        ]);
    }

public function google(Request $request)
{
    $request->validate([
        'id_token' => 'required'
    ]);

    $response = Http::get(
        'https://oauth2.googleapis.com/tokeninfo',
        [
            'id_token' => $request->id_token
        ]
    );

    if (!$response->successful()) {
        return response()->json([
            'success' => false,
            'message' => 'Invalid Google token'
        ], 401);
    }

    $payload = $response->json();

    if (!isset($payload['email'])) {
        return response()->json([
            'success' => false,
            'message' => 'Token tidak valid'
        ], 401);
    }

    $user = User::firstOrCreate(
        ['email' => $payload['email']],
        [
            'name' => $payload['name'] ?? '',
            'google_id' => $payload['sub'] ?? null,
            'avatar' => $payload['picture'] ?? null,
            'password' => bcrypt(Str::random(16)),
        ]
    );

    // Hapus token lama
    $user->tokens()->delete();

    // Generate token baru
    $token = $user->createToken('mobile-token')->plainTextToken;

    return response()->json([
        'success' => true,
        'data' => [
            'user' => $user,
            'token' => $token
        ]
    ]);
}
public function updateProfile(Request $request)
{
    $user = $request->user();

    $request->validate([
        'name' => 'required|string',
        'phone' => 'nullable|string',
        'npm' => 'nullable|string',
        'jurusan' => 'nullable|string',
    ]);

    $user->update([
        'name' => $request->name,
        'phone' => $request->phone,
        'npm' => $request->npm,
        'jurusan' => $request->jurusan,
    ]);

    return response()->json([
        'success' => true,
        'data' => $user
    ]);
}

public function saveToken(Request $request)
{
    $user = auth()->user();
    $user->fcm_token = $request->fcm_token;
    $user->save();

    return response()->json([
        'message' => 'Token saved'
    ]);
}
}
