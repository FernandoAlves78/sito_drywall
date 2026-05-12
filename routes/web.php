<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\PreventivoController;
use App\Http\Controllers\RecensioneController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::post('/preventivo', [PreventivoController::class, 'store'])->name('preventivo.store');

Route::get('/recensioni', [RecensioneController::class, 'index'])->name('recensioni.index');
Route::post('/recensioni', [RecensioneController::class, 'store'])->name('recensioni.store');
