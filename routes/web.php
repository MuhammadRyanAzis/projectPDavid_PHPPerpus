<?php

use App\Http\Controllers\AnggotaController;
use App\Http\Controllers\BukuController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\PeminjamanController;
use App\Http\Controllers\PengembalianController;
use App\Http\Controllers\Teams\TeamInvitationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes - Sistem Informasi Perpustakaan
|--------------------------------------------------------------------------
*/

// Halaman utama
Route::get('/', function () {
    return redirect()->route('kategori.index');
})->name('home');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');
    Route::post('/invitations/{invitation:code}/accept', [TeamInvitationController::class, 'accept'])->name('invitations.accept');
    Route::post('/invitations/{invitation:code}/decline', [TeamInvitationController::class, 'decline'])->name('invitations.decline');
});

// CRUD Web Resource Perpustakaan
Route::resource('kategori', KategoriController::class)->except(['show']);
Route::resource('anggota', AnggotaController::class);
Route::resource('buku', BukuController::class);
Route::resource('peminjaman', PeminjamanController::class)->except(['edit', 'update']);
Route::resource('pengembalian', PengembalianController::class)->only(['index', 'create', 'store']);

require __DIR__.'/settings.php';
