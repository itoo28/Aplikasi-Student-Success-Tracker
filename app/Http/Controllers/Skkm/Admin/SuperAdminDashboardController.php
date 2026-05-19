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

        $recentUsers = User::query()->latest()->take(6)->get();

        return view('skkm.super-admin.dashboard', [
            'totalUsers' => $totalUsers,
            'totalStudents' => $totalStudents,
            'totalStaff' => $totalStaff,
            'totalFakultas' => Fakultas::count(),
            'totalProgramStudi' => ProgramStudi::count(),
            'pendingDosen' => $pendingDosen,
            'recentUsers' => $recentUsers,
        ]);
    }
}
