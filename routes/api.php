<?php

use App\Http\Controllers\Api\AnggotaController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\BukuController;
use App\Http\Controllers\Api\KategoriController;
use App\Http\Controllers\Api\PeminjamanController;
use App\Http\Controllers\Api\PengembalianController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| REST API Routes - Sistem Informasi Perpustakaan
|--------------------------------------------------------------------------
*/

// Authentication API Routes
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout']);
Route::get('/me', [AuthController::class, 'me']);

// Core Library Resource API Routes
Route::apiResource('kategori', KategoriController::class);
Route::apiResource('anggota', AnggotaController::class)->parameters(['anggota' => 'anggota']);
Route::apiResource('buku', BukuController::class);
Route::apiResource('peminjaman', PeminjamanController::class)->except(['update']);
Route::apiResource('pengembalian', PengembalianController::class)->only(['index', 'store', 'show']);
