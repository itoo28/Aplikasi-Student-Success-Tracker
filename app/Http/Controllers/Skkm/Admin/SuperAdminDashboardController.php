<?php

namespace App\Http\Controllers\Skkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\SkkmSubmission;
use App\Models\User;
use Illuminate\Contracts\View\View;

class SuperAdminDashboardController extends Controller
{
    public function __invoke(): View
    {
        $totalUsers = User::query()->count();
        $totalStudents = User::query()
            ->where(function ($query) {
                $query->whereIn('skkm_role', ['mahasiswa', 'student'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNull('skkm_role')->where('role', 'student');
                    });
            })
            ->count();

        $totalStaff = User::query()
            ->where(function ($query) {
                $query->whereNotIn('skkm_role', ['mahasiswa', 'student'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNull('skkm_role')->where('role', 'lecturer');
                    });
            })
            ->count();

        $pendingDosen = SkkmSubmission::query()->where('status_verifikasi', 'pending')->count();
        $pendingKaprodi = SkkmSubmission::query()
            ->where('status_verifikasi', 'disetujui')
            ->where(function ($query) {
                $query->whereNull('status_kaprodi')->orWhere('status_kaprodi', 'pending');
            })
            ->count();
        $pendingKemahasiswaan = SkkmSubmission::query()
            ->where('status_verifikasi', 'disetujui')
            ->where('status_kaprodi', 'disetujui')
            ->where(function ($query) {
                $query->whereNull('status_kemahasiswaan')->orWhere('status_kemahasiswaan', 'pending');
            })
            ->count();

        $recentUsers = User::query()->latest()->take(6)->get();

        return view('skkm.super-admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalStaff' => $totalStaff,
            'totalFakultas' => Fakultas::count(),
            'totalProgramStudi' => ProgramStudi::count(),
            'pendingDosen' => $pendingDosen,
            'pendingKaprodi' => $pendingKaprodi,
            'pendingKemahasiswaan' => $pendingKemahasiswaan,
            'recentUsers' => $recentUsers,
        ]);
    }
}
