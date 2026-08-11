<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\Teams\TeamInvitationController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Di sini kita mendaftarkan route untuk aplikasi web.
|
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

// CRUD Kategori
Route::resource('kategori', KategoriController::class)
    ->except(['show']);

require __DIR__.'/settings.php';