<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Berita;
use App\Models\Paket;

// ============ HALAMAN UTAMA ============
Route::get('/', function () {
    $beritas = Berita::where('jenis', 'terkini')->latest()->get();
    $beritasLainnya = Berita::where('jenis', 'lainnya')->latest()->get();
    $paketHaji = Paket::where('jenis', 'haji')->get();
    $paketUmrah = Paket::where('jenis', 'umrah')->get();
    
    return view('index', compact('beritas', 'beritasLainnya', 'paketHaji', 'paketUmrah'));
})->name('home');

// ============ LOGIN ============
Route::get('/login', function () {
    return view('login');
})->name('login');

// ============ LOGOUT (TAMBAHKAN INI) ============
Route::post('/logout', function (Request $request) {
    Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect('/login');
})->name('logout');

// ============ REGISTER ============
Route::get('/register', function () {
    return view('register');
})->name('register');

// ============ INDEX ============
Route::get('/index', function () {
    $beritas = Berita::where('jenis', 'terkini')->latest()->get();
    $beritasLainnya = Berita::where('jenis', 'lainnya')->latest()->get();
    $paketHaji = Paket::where('jenis', 'haji')->get();
    $paketUmrah = Paket::where('jenis', 'umrah')->get();
    
    return view('index', compact('beritas', 'beritasLainnya', 'paketHaji', 'paketUmrah'));
})->name('index');

// ============ ADMIN (TANPA MIDDLEWARE) ============
Route::get('/admin', function () {
    return view('admin');
})->name('admin');

// ============ ROUTE BERITA ============
Route::get('/daftar-berita', function () {
    $beritas = Berita::latest()->get();
    return view('daftar-berita', compact('beritas'));
})->name('berita.daftar');

Route::get('/detailberita/{id}', function ($id) {
    $berita = Berita::findOrFail($id);
    return view('detailberita', compact('berita'));
})->name('berita.detail');

// ============ ROUTE PAKET ============
Route::get('/detailpakethaji', function () {
    $paketHaji = Paket::where('jenis', 'haji')->get();
    return view('detailpakethaji', compact('paketHaji'));
})->name('detailpakethaji');

Route::get('/detailpaketumrah', function () {
    $paketUmrah = Paket::where('jenis', 'umrah')->get();
    return view('detailpaketumrah', compact('paketUmrah'));
})->name('detailpaketumrah');

Route::get('/paket-detail/{id}', function ($id) {
    $paket = Paket::findOrFail($id);
    return view('paket-detail', compact('paket'));
})->name('paket.detail');

// ============ HALAMAN LAINNYA ============
Route::get('/kontak', function () {
    return view('kontak');
})->name('kontak');

Route::get('/panduanhaji', function () {
    return view('panduanhaji');
})->name('panduanhaji');

Route::get('/panduanumrah', function () {
    return view('panduanumrah');
})->name('panduanumrah');

Route::get('/informasihaji', function () {
    return view('informasihaji');
})->name('informasihaji');

Route::get('/informasiumrah', function () {
    return view('informasiumrah');
})->name('informasiumrah');

Route::get('/informasiacara', function () {
    return view('informasiacara');
})->name('informasiacara');