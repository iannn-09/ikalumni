<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing page
Route::get('/', function () {
    return Inertia::render('Landing/LandingView', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Public route untuk detail berita (bisa diakses tanpa login)
Route::get('/berita/{slug}', function ($slug) {
    return Inertia::render('Landing/DetailBeritaView', [
        'slug' => $slug
    ]);
})->name('berita.detail');

// Dashboard (hanya untuk user login + verified)
Route::get('/dashboard', function () {
    return Inertia::render('Admin/DashboardView');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================
// Admin routes (dengan prefix /admin)
// ============================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Berita - URL: /admin/berita
    Route::get('/berita', fn () => Inertia::render('Admin/BeritaView'))->name('berita.index');

    // Admin Kategori - URL: /admin/kategori
    Route::get('/kategori', fn () => Inertia::render('Admin/KategoriView'))->name('kategori.index');
    Route::get('/kategori/{id}', fn ($id) => Inertia::render('Admin/Kategori/Show', ['id' => $id]))->name('kategori.show');
    Route::get('/kategori/{id}/edit', fn ($id) => Inertia::render('Admin/Kategori/Edit', ['id' => $id]))->name('kategori.edit');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// Auth routes
require __DIR__.'/auth.php';
