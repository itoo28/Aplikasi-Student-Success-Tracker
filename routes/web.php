<?php

use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // SKKM Module (Mahasiswa)
    Route::get('/skkm', [\App\Http\Controllers\Skkm\SkkmSubmissionController::class, 'index'])->name('skkm.index');
    Route::get('/skkm/create', [\App\Http\Controllers\Skkm\SkkmSubmissionController::class, 'create'])->name('skkm.create');
    Route::post('/skkm', [\App\Http\Controllers\Skkm\SkkmSubmissionController::class, 'store'])->name('skkm.store');
    
    // SKKM Module (Dosen PA)
    Route::get('/skkm/verifikasi', [\App\Http\Controllers\Skkm\SkkmSubmissionController::class, 'verifikasiIndex'])->name('skkm.verifikasi.index');
    Route::post('/skkm/{submission}/verify', [\App\Http\Controllers\Skkm\SkkmSubmissionController::class, 'verify'])->name('skkm.verify');
});
