<?php

namespace App\Http\Controllers;

use App\Models\GuidanceLog;
use App\Models\SkkmPoint;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function __invoke(Request $request): View
    {
        $user = $request->user();

        if ($user->role === 'lecturer') {
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

        $skkmTarget = 20;
        $approvedPoints = (int) $student->skkmPoints()->where('status', 'approved')->sum('points');
        $progressPercent = min(100, (int) round(($approvedPoints / $skkmTarget) * 100));
        $progressDegree = (int) round(($progressPercent / 100) * 360);

        $latestGuidance = $student->guidanceLogs()
            ->with('lecturer')
            ->latest('guidance_date')
            ->first();

        $activities = collect();

        $skkmActivities = $student->skkmPoints()
            ->latest()
            ->take(5)
            ->get()
            ->map(function (SkkmPoint $item) {
                $activityDate = $item->created_at;

                return [
                    'date' => optional($activityDate)->format('d M Y'),
                    'sort_date' => $activityDate?->timestamp ?? 0,
                    'category' => 'SKKM',
                    'activity' => $item->name,
                    'points' => $item->points,
                    'status' => $item->status,
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
            'approvedPoints' => $approvedPoints,
            'progressPercent' => $progressPercent,
            'progressDegree' => $progressDegree,
            'latestGuidance' => $latestGuidance,
            'activities' => $activities,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function buildLecturerDashboardData(User $lecturer): array
    {
        $students = $lecturer->adviseeStudents()
            ->where('role', 'student')
            ->withSum(['skkmPoints as approved_points_sum' => fn ($query) => $query->where('status', 'approved')], 'points')
            ->get();

        $pendingSkkmCount = SkkmPoint::query()
            ->where('status', 'pending')
            ->whereHas('student', fn ($query) => $query->where('lecturer_id', $lecturer->id))
            ->count();

        $guidanceTodayCount = GuidanceLog::query()
            ->where('lecturer_id', $lecturer->id)
            ->whereDate('guidance_date', now()->toDateString())
            ->count();

        $atRiskCount = $students->filter(function (User $student) {
            return ($student->semester ?? 0) >= 5 && (($student->approved_points_sum ?? 0) < 10);
        })->count();

        $approvalQueue = SkkmPoint::query()
            ->with('student')
            ->where('status', 'pending')
            ->whereHas('student', fn ($query) => $query->where('lecturer_id', $lecturer->id))
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
}
