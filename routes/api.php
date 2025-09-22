<?php

use App\Http\Controllers\BeritaController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\KategoriController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/dashboard/stats', [DashboardController::class, 'getStats']);
Route::get('/dashboard/news-by-category', [DashboardController::class, 'getNewsByCategory']);

// API Routes for Berita
Route::apiResource('berita', BeritaController::class);

// API Routes for Kategori
Route::apiResource('kategori', KategoriController::class);
