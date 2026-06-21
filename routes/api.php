<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\PaketController;
use App\Http\Controllers\PengaduanController;
use App\Http\Controllers\ProfilController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\VideoPanduanController;
use App\Http\Middleware\IsAdmin;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

// ============ ROUTE PUBLIK ============
Route::get('/berita', [BeritaController::class, 'index']);
Route::get('/berita/{id}', [BeritaController::class, 'show']);

Route::get('/paket', [PaketController::class, 'index']);
Route::get('/paket/{id}', [PaketController::class, 'show']);

Route::get('/pengaduan', [PengaduanController::class, 'index']);
Route::post('/pengaduan', [PengaduanController::class, 'store']);

Route::get('/slider', [SliderController::class, 'index']);
Route::get('/video-panduan', [VideoPanduanController::class, 'index']);
Route::get('/profil', [ProfilController::class, 'index']);

// ============ ROUTE ADMIN ============
Route::middleware(['auth:sanctum', IsAdmin::class])->group(function () {
    Route::post('/berita', [BeritaController::class, 'store']);
    Route::put('/berita/{id}', [BeritaController::class, 'update']);
    Route::delete('/berita/{id}', [BeritaController::class, 'destroy']);

    Route::post('/paket', [PaketController::class, 'store']);
    Route::put('/paket/{id}', [PaketController::class, 'update']);
    Route::delete('/paket/{id}', [PaketController::class, 'destroy']);

    Route::post('/profil', [ProfilController::class, 'store']);
    Route::delete('/profil/{id}', [ProfilController::class, 'destroy']);

    Route::post('/slider', [SliderController::class, 'store']);
    Route::delete('/slider/{id}', [SliderController::class, 'destroy']);

    Route::post('/video-panduan', [VideoPanduanController::class, 'store']);
    Route::delete('/video-panduan/{id}', [VideoPanduanController::class, 'destroy']);
});

// ============ AUTH ============
Route::post('/register', function (Request $request) {
    try {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6|confirmed',
        ]);

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'user',
        ]);

        return response()->json(['message' => 'Registrasi berhasil', 'user' => $user], 201);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
})->middleware('throttle:5,1');

// ===== LOGIN (PASTI WORK) =====
Route::post('/login', function (Request $request) {
    try {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json(['message' => 'User tidak ditemukan'], 401);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Password salah'], 401);
        }

        // Hapus token lama jika ada
        $user->tokens()->delete();

        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Login berhasil',
            'token' => $token,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role,
            ],
        ]);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
});

Route::post('/logout', function (Request $request) {
    try {
        $request->user()->currentAccessToken()->delete();
        return response()->json(['message' => 'Logout berhasil']);
    } catch (\Exception $e) {
        return response()->json(['message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
    }
})->middleware('auth:sanctum');

Route::get('/user', function (Request $request) {
    return response()->json($request->user());
})->middleware('auth:sanctum');