<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\BeritaController;
use App\Http\Controllers\KategoriController;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

// Web Routes for Berita and Kategori with Inertia
Route::middleware(['auth', 'verified'])->group(function () {
    // Berita routes
    Route::get('/berita', function () {
        return Inertia::render('Berita/Index');
    })->name('berita.index');
    Route::get('/berita/create', function () {
        return Inertia::render('Berita/Create');
    })->name('berita.create');
    Route::get('/berita/{id}', function ($id) {
        return Inertia::render('Berita/Show', ['id' => $id]);
    })->name('berita.show');
    Route::get('/berita/{id}/edit', function ($id) {
        return Inertia::render('Berita/Edit', ['id' => $id]);
    })->name('berita.edit');

    // Kategori routes
    Route::get('/kategori', function () {
        return Inertia::render('Kategori/Index');
    })->name('kategori.index');
    Route::get('/kategori/create', function () {
        return Inertia::render('Kategori/Create');
    })->name('kategori.create');
    Route::get('/kategori/{id}', function ($id) {
        return Inertia::render('Kategori/Show', ['id' => $id]);
    })->name('kategori.show');
    Route::get('/kategori/{id}/edit', function ($id) {
        return Inertia::render('Kategori/Edit', ['id' => $id]);
    })->name('kategori.edit');
    Route::delete('/kategori/{id}', [KategoriController::class, 'destroy'])->name('kategori.destroy');
});

require __DIR__.'/auth.php';
