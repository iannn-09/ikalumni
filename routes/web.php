<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

// Landing page
Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

// Dashboard (hanya untuk user login + verified)
Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Profile routes (user login)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// ============================
// Admin routes (prefix: /admin)
// ============================
Route::middleware(['auth', 'verified'])->prefix('admin')->name('admin.')->group(function () {
    // Admin Berita
    Route::get('/berita', fn () => Inertia::render('Admin/BeritaView'))->name('berita.index');

    // Admin Kategori
    Route::get('/kategori', fn () => Inertia::render('Admin/KategoriView'))->name('kategori.index');
});

// ==================================
// Alternative routes (tanpa /admin)
// ==================================
Route::middleware(['auth', 'verified'])->group(function () {
    // Berita
    Route::get('/berita', fn () => Inertia::render('Admin/BeritaView'))->name('berita.index');

    // Kategori
    Route::get('/kategori', fn () => Inertia::render('Admin/KategoriView'))->name('kategori.index');
    Route::get('/kategori/{id}', fn ($id) => Inertia::render('Kategori/Show', ['id' => $id]))->name('kategori.show');
    Route::get('/kategori/{id}/edit', fn ($id) => Inertia::render('Kategori/Edit', ['id' => $id]))->name('kategori.edit');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

// Auth routes
require __DIR__.'/auth.php';
