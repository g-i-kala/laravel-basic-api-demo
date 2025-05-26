<?php

use App\Http\Controllers\ApiTokenController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware('auth')->group(function () {
    Route::post('/api-token', [ApiTokenController::class, 'store'])->name('apitoken.generate');
    Route::put('/api-token', [ApiTokenController::class, 'update'])->name('apitoken.regenerate');
    Route::delete('/api-token', [ApiTokenController::class, 'destroy'])->name('apitoken.delete');
});

require __DIR__.'/auth.php';
