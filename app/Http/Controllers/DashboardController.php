<?php

namespace App\Http\Controllers;

use App\Models\GuidanceLog;
use App\Models\SkkmSubmission;
use App\Models\SkkmProgress;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View|RedirectResponse
    {
        $user = $request->user();
        $skkmRole = $user->resolvedSkkmRole();

        if ($skkmRole === 'super_admin') {
            return redirect()->route('admin.dashboard');
        }

        if ($skkmRole === 'kaprodi') {
            return redirect()->route('skkm.kaprodi.index');
        }

        if ($skkmRole === 'kemahasiswaan') {
            return redirect()->route('skkm.kemahasiswaan.index');
        }

        if ($skkmRole === 'dosen_pa') {
            return view('dashboard', $this->buildLecturerDashboardData($user));
        }

        return view('dashboard', $this->buildStudentDashboardData($user));
    }

    /**
     * @return array<string, mixed>
     */
    private function buildStudentDashboardData(User $student): array
    {
        $student->load('lecturer');

        $skkmTarget = 20; // PRD v2.0 block target
        $targetKelulusan = ($student->jenjang_studi ?? 'S1') === 'D3' ? 60 : 80;
        
        $progress = $student->skkmProgress;
        $approvedPoints = (int) $student->skkmSubmissions()->finalApproved()->sum('poin_otomatis');
        
        $progressPercent = $targetKelulusan > 0
            ? min(100, (int) round(($approvedPoints / $targetKelulusan) * 100))
            : 0;
        $progressDegree = (int) round(($progressPercent / 100) * 360);

        $latestGuidance = $student->guidanceLogs()
            ->with('lecturer')
            ->latest('guidance_date')
            ->first();

        $maxSemester = ($student->jenjang_studi ?? 'S1') === 'D3' ? 6 : 8;
        $pointsPerSemester = [];
        
        for ($i = 1; $i <= $maxSemester; $i++) {
            $pointsPerSemester[$i] = 0;
        }

        $queryPoints = $student->skkmSubmissions()
            ->finalApproved()
            ->selectRaw('semester_input, sum(poin_otomatis) as total_points')
            ->groupBy('semester_input')
            ->orderBy('semester_input')
            ->pluck('total_points', 'semester_input')
            ->toArray();

        foreach ($queryPoints as $smt => $points) {
            if ($smt <= $maxSemester) {
                $pointsPerSemester[$smt] = $points;
            }
        }

        $activities = collect();

        $skkmActivities = $student->skkmSubmissions()
            ->latest()
            ->take(5)
            ->get()
            ->map(function (SkkmSubmission $item) {
                $activityDate = $item->created_at;

                return [
                    'date' => optional($activityDate)->format('d M Y'),
                    'sort_date' => $activityDate?->timestamp ?? 0,
                    'category' => 'SKKM',
                    'activity' => $item->nama_kegiatan,
                    'points' => $item->poin_otomatis,
                    'status' => $this->resolveSkkmDisplayStatus($item),
                ];
            });

        $guidanceActivities = $student->guidanceLogs()
            ->latest('guidance_date')
            ->take(5)
            ->get()
            ->map(function (GuidanceLog $item) {
                $activityDate = $item->guidance_date;

                return [
                    'date' => optional($activityDate)->format('d M Y'),
                    'sort_date' => $activityDate?->timestamp ?? 0,
                    'category' => 'Bimbingan',
                    'activity' => $item->topic,
                    'points' => null,
                    'status' => $item->status,
                ];
            });

        $activities = $activities
            ->concat($skkmActivities)
            ->concat($guidanceActivities)
            ->sortByDesc('sort_date')
            ->take(8)
            ->values();

        return [
            'dashboardType' => 'student',
            'student' => $student,
            'skkmTarget' => $skkmTarget,
            'targetKelulusan' => $targetKelulusan,
            'approvedPoints' => $approvedPoints,
            'progressPercent' => $progressPercent,
            'progressDegree' => $progressDegree,
            'latestGuidance' => $latestGuidance,
            'pointsPerSemester' => $pointsPerSemester,
            'activities' => $activities,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildLecturerDashboardData(User $lecturer): array
    {
        // For sum, we use relationship to skkmSubmissions
        $students = $lecturer->adviseeStudents()
            ->where('role', 'student')
            ->withSum(['skkmSubmissions as approved_points_sum' => fn ($query) => $query->where('status_verifikasi', 'disetujui')], 'poin_otomatis')
            ->get();

        $pendingSkkmCount = SkkmSubmission::query()
            ->where('status_verifikasi', 'pending')
            ->whereHas('mahasiswa', fn ($query) => $query->where('lecturer_id', $lecturer->id))
            ->count();

        $guidanceTodayCount = GuidanceLog::query()
            ->where('lecturer_id', $lecturer->id)
            ->whereDate('guidance_date', now()->toDateString())
            ->count();

        $atRiskCount = $students->filter(function (User $student) {
            // Updated atRisk based on PRD v2.0
            return ($student->semester ?? 0) >= 5 && (($student->approved_points_sum ?? 0) < 40);
        })->count();

        $approvalQueue = SkkmSubmission::query()
            ->with('mahasiswa')
            ->where('status_verifikasi', 'pending')
            ->whereHas('mahasiswa', fn ($query) => $query->where('lecturer_id', $lecturer->id))
            ->latest()
            ->take(8)
            ->get();

        return [
            'dashboardType' => 'lecturer',
            'lecturer' => $lecturer,
            'totalStudents' => $students->count(),
            'pendingSkkmCount' => $pendingSkkmCount,
            'guidanceTodayCount' => $guidanceTodayCount,
            'atRiskCount' => $atRiskCount,
            'approvalQueue' => $approvalQueue,
        ];
    }

    private function resolveSkkmDisplayStatus(SkkmSubmission $submission): string
    {
        if ($submission->status_verifikasi === 'ditolak') {
            return 'ditolak';
        }

        if ($submission->status_verifikasi === 'pending') {
            return 'menunggu_dosen';
        }

        if ($submission->status_verifikasi === 'disetujui') {
            return 'disetujui';
        }

        return 'pending';
    }
}
