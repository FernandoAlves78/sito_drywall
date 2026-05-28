<?php

use App\Http\Controllers\Admin\PreventivoController as AdminPreventivoController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreventivoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\RecensioneController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/preventivo', [PreventivoController::class, 'store'])->name('preventivo.store');

Route::get('/recensioni', [RecensioneController::class, 'index'])->name('recensioni.index');
Route::post('/recensioni', [RecensioneController::class, 'store'])->name('recensioni.store');

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::get('/preventivi', [AdminPreventivoController::class, 'index'])->name('preventivi.index');
    Route::get('/preventivi/{preventivo}', [AdminPreventivoController::class, 'show'])->name('preventivi.show');
    Route::patch('/preventivi/{preventivo}', [AdminPreventivoController::class, 'update'])->name('preventivi.update');
    Route::delete('/preventivi/{preventivo}', [AdminPreventivoController::class, 'destroy'])->name('preventivi.destroy');
});

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
