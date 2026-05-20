<?php

use App\Http\Controllers\Skkm\Admin\FakultasController;
use App\Http\Controllers\Skkm\Admin\ProgramStudiController;
use App\Http\Controllers\Skkm\Admin\SuperAdminDashboardController;
use App\Http\Controllers\Skkm\Admin\UserManagementController;
use App\Http\Controllers\Skkm\SkkmSubmissionController;
use App\Http\Controllers\Skkm\SkkmValidationController;
use App\Http\Controllers\DashboardController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('dashboard');
    }

    return view('auth.login');
});

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
])->group(function () {
    Route::get('/dashboard', DashboardController::class)->name('dashboard');

    // SKKM Module (Mahasiswa)
    Route::middleware('skkm.role:student')->group(function () {
        Route::get('/skkm', [SkkmSubmissionController::class, 'index'])->name('skkm.index');
        Route::get('/skkm/create', [SkkmSubmissionController::class, 'create'])->name('skkm.create');
        Route::post('/skkm', [SkkmSubmissionController::class, 'store'])->name('skkm.store');
        
        // Bimbingan Akademik
        Route::get('/bimbingan', [\App\Http\Controllers\BimbinganAkademikController::class, 'mahasiswaIndex'])->name('bimbingan.mahasiswa.index');
        Route::post('/bimbingan', [\App\Http\Controllers\BimbinganAkademikController::class, 'mahasiswaStore'])->name('bimbingan.mahasiswa.store');
    });

    // SKKM Module (Dosen PA)
    Route::middleware('skkm.role:dosen_pa')->group(function () {
        Route::get('/skkm/verifikasi', [SkkmSubmissionController::class, 'verifikasiIndex'])->name('skkm.verifikasi.index');
        Route::get('/skkm/monitoring', [SkkmSubmissionController::class, 'monitoringIndex'])->name('skkm.monitoring.index');
        Route::post('/skkm/{submission}/verify', [SkkmSubmissionController::class, 'verify'])->name('skkm.verify');

        // Bimbingan Akademik
        Route::get('/bimbingan/dosen', [\App\Http\Controllers\BimbinganAkademikController::class, 'dosenIndex'])->name('bimbingan.dosen.index');
        Route::post('/bimbingan/dosen/{log}/update', [\App\Http\Controllers\BimbinganAkademikController::class, 'dosenUpdate'])->name('bimbingan.dosen.update');
        Route::post('/bimbingan/dosen/store', [\App\Http\Controllers\BimbinganAkademikController::class, 'dosenStore'])->name('bimbingan.dosen.store');
    });

    // SKKM Module (Kaprodi)
    Route::middleware('skkm.role:kaprodi')
        ->prefix('/skkm/kaprodi')
        ->name('skkm.kaprodi.')
        ->group(function () {
            Route::get('/', [SkkmValidationController::class, 'kaprodiIndex'])->name('index');
            Route::get('/mahasiswa', [SkkmValidationController::class, 'kaprodiMahasiswaIndex'])->name('mahasiswa.index');
        });

    Route::middleware('skkm.role:kaprodi')->group(function () {
        Route::get('/bimbingan/rekapitulasi/kaprodi', [\App\Http\Controllers\BimbinganAkademikController::class, 'rekapitulasiIndex'])->name('bimbingan.rekapitulasi.kaprodi');
    });

    // SKKM Module (Kemahasiswaan)
    Route::middleware('skkm.role:kemahasiswaan')
        ->prefix('/skkm/kemahasiswaan')
        ->name('skkm.kemahasiswaan.')
        ->group(function () {
            Route::get('/', [SkkmValidationController::class, 'kemahasiswaanIndex'])->name('index');
        });
        
    Route::middleware('skkm.role:kemahasiswaan')->group(function () {
        Route::get('/bimbingan/rekapitulasi/kemahasiswaan', [\App\Http\Controllers\BimbinganAkademikController::class, 'rekapitulasiIndex'])->name('bimbingan.rekapitulasi.kemahasiswaan');
    });

    // SKKM Module (Super Admin)
    Route::middleware('skkm.role:super_admin')
        ->prefix('/admin')
        ->name('admin.')
        ->group(function () {
            Route::get('/dashboard', SuperAdminDashboardController::class)->name('dashboard');
            Route::resource('/users', UserManagementController::class)->except(['show']);
            Route::resource('/fakultas', FakultasController::class)->except(['show']);
            Route::resource('/program-studi', ProgramStudiController::class)->except(['show']);
        });
});
