<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Skkm\Admin\FakultasController;
use App\Http\Controllers\Skkm\Admin\ProgramStudiController;
use App\Http\Controllers\Skkm\Admin\SuperAdminDashboardController;
use App\Http\Controllers\Skkm\Admin\UserManagementController;
use App\Http\Controllers\Skkm\PointRuleController;
use App\Http\Controllers\Skkm\SkkmSubmissionController;
use App\Http\Controllers\Skkm\SkkmValidationController;
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

    // SKKM Module (Master Kategori Poin)
    Route::middleware('skkm.role:super_admin,kemahasiswaan')
        ->prefix('/skkm/poin')
        ->name('skkm.point-rules.')
        ->group(function () {
            Route::get('/', [PointRuleController::class, 'index'])->name('index');
            Route::get('/create', [PointRuleController::class, 'create'])->name('create');
            Route::post('/', [PointRuleController::class, 'store'])->name('store');
            Route::get('/{pointRule}/edit', [PointRuleController::class, 'edit'])->name('edit');
            Route::put('/{pointRule}', [PointRuleController::class, 'update'])->name('update');
            Route::delete('/{pointRule}', [PointRuleController::class, 'destroy'])->name('destroy');
        });

    // SKKM Module (Mahasiswa)
    Route::middleware('skkm.role:student')->group(function () {
        Route::get('/skkm', [SkkmSubmissionController::class, 'index'])->name('skkm.index');
        Route::get('/skkm/create', [SkkmSubmissionController::class, 'create'])->name('skkm.create');
        Route::post('/skkm', [SkkmSubmissionController::class, 'store'])->name('skkm.store');
    });

    // SKKM Module (Dosen PA)
    Route::middleware('skkm.role:dosen_pa')->group(function () {
        Route::get('/skkm/verifikasi', [SkkmSubmissionController::class, 'verifikasiIndex'])->name('skkm.verifikasi.index');
        Route::get('/skkm/monitoring', [SkkmSubmissionController::class, 'monitoringIndex'])->name('skkm.monitoring.index');
        Route::post('/skkm/{submission}/verify', [SkkmSubmissionController::class, 'verify'])->name('skkm.verify');
    });

    // SKKM Module (Kaprodi)
    Route::middleware('skkm.role:kaprodi')
        ->prefix('/skkm/kaprodi')
        ->name('skkm.kaprodi.')
        ->group(function () {
            Route::get('/', fn () => redirect()->route('skkm.kaprodi.dashboard'))->name('home');
            Route::get('/dashboard', [SkkmValidationController::class, 'kaprodiDashboard'])->name('dashboard');
            Route::get('/monitoring', [SkkmValidationController::class, 'kaprodiIndex'])->name('index');
            Route::get('/mahasiswa', [SkkmValidationController::class, 'kaprodiMahasiswaIndex'])->name('mahasiswa.index');
        });

    // SKKM Module (Kemahasiswaan)
    Route::middleware('skkm.role:kemahasiswaan')
        ->prefix('/skkm/kemahasiswaan')
        ->name('skkm.kemahasiswaan.')
        ->group(function () {
            Route::get('/', fn () => redirect()->route('dashboard'))->name('index');
            Route::get('/mahasiswa', [SkkmValidationController::class, 'kemahasiswaanMahasiswaIndex'])->name('mahasiswa.index');
            Route::get('/mahasiswa/export', [SkkmValidationController::class, 'kemahasiswaanMahasiswaExport'])->name('mahasiswa.export');
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
