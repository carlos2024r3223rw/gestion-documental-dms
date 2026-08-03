<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::get('/dashboard', [\App\Http\Controllers\DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('documents', \App\Http\Controllers\DocumentController::class);
    Route::get('documents/download/{version}', [\App\Http\Controllers\DocumentController::class, 'download'])->name('documents.download');
    Route::get('documents/preview/{version}', [\App\Http\Controllers\DocumentController::class, 'preview'])->name('documents.preview');
    Route::post('documents/{document}/versions', [\App\Http\Controllers\DocumentController::class, 'storeVersion'])->name('documents.versions.store');
    Route::patch('documents/{document}/status', [\App\Http\Controllers\DocumentController::class, 'updateStatus'])->name('documents.status.update');
});

require __DIR__.'/auth.php';
