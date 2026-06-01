<?php

namespace App\Http\Controllers\Skkm;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use App\Models\ProgramStudi;
use App\Models\SkkmSubmission;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Contracts\View\View;
use Illuminate\Http\Request;

class SkkmValidationController extends Controller
{
    public function kaprodiDashboard(Request $request): View
    {
        $kaprodi = $request->user();
        $scopeLabel = $kaprodi->programStudi?->nama ?? 'Seluruh Program Studi';

        $studentQuery = $this->applyStudentScope(User::query())
            ->with('skkmProgress');

        if ($kaprodi->program_studi_id) {
            $studentQuery->where('program_studi_id', $kaprodi->program_studi_id);
        }

        $students = $studentQuery->get();

        $submissionQuery = SkkmSubmission::query()
            ->with(['mahasiswa.programStudi', 'pointRule', 'verifiedBy'])
            ->latest();

        if ($kaprodi->program_studi_id) {
            $submissionQuery->whereHas('mahasiswa', fn (Builder $query) => $query->where('program_studi_id', $kaprodi->program_studi_id));
        }

        $statusSummary = (clone $submissionQuery)
            ->selectRaw('count(*) as total_submissions')
            ->selectRaw("sum(case when status_verifikasi = 'pending' then 1 else 0 end) as pending_submissions")
            ->selectRaw("sum(case when status_verifikasi = 'disetujui' then 1 else 0 end) as approved_submissions")
            ->selectRaw("sum(case when status_verifikasi = 'ditolak' then 1 else 0 end) as rejected_submissions")
            ->first();

        return view('skkm.kaprodi.dashboard', [
            'scopeLabel' => $scopeLabel,
            'totalStudents' => $students->count(),
            'studentsFulfilled' => $students->filter(fn (User $student) => ($student->skkmProgress?->status_yudisium ?? null) === 'memenuhi')->count(),
            'studentsInProgress' => $students->filter(fn (User $student) => in_array(($student->skkmProgress?->status_yudisium ?? null), ['dalam_proses', 'belum_memenuhi'], true))->count(),
            'statusSummary' => $statusSummary,
            'recentSubmissions' => (clone $submissionQuery)->take(8)->get(),
        ]);
    }

    public function kaprodiIndex(Request $request): View
    {
        $kaprodi = $request->user();

        $query = SkkmSubmission::query()
            ->with(['mahasiswa.programStudi.fakultas', 'pointRule', 'verifiedBy'])
            ->latest();

        if ($kaprodi->program_studi_id) {
            $query->whereHas('mahasiswa', fn ($q) => $q->where('program_studi_id', $kaprodi->program_studi_id));
        }

        $submissions = $query->paginate(20);

        return view('skkm.kaprodi.index', [
            'submissions' => $submissions,
            'scopeLabel' => $kaprodi->programStudi?->nama ?? 'Seluruh Program Studi',
        ]);
    }

    public function kaprodiMahasiswaIndex(Request $request): View
    {
        $kaprodi = $request->user();
        $scopeLabel = $kaprodi->programStudi?->nama ?? 'Program Studi belum diatur';
        $search = trim((string) $request->query('q', ''));

        if (! $kaprodi->program_studi_id) {
            return view('skkm.kaprodi.mahasiswa', [
                'students' => collect(),
                'scopeLabel' => $scopeLabel,
            ]);
        }

        $students = $this->applyStudentScope(User::query())
            ->where('program_studi_id', $kaprodi->program_studi_id)
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('identifier', 'like', "%{$search}%")
                        ->orWhereHas('lecturer', function ($lecturerQuery) use ($search) {
                            $lecturerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('identifier', 'like', "%{$search}%");
                        });
                });
            })
            ->with(['programStudi.fakultas', 'skkmProgress', 'lecturer'])
            ->orderBy('name')
            ->get();

        return view('skkm.kaprodi.mahasiswa', [
            'students' => $students,
            'scopeLabel' => $scopeLabel,
            'search' => $search,
        ]);
    }

    public function kemahasiswaanDashboard(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $selectedStatus = trim((string) $request->query('status', ''));
        $perPage = (int) $request->query('per_page', 20);
        $allowedStatuses = ['pending', 'disetujui', 'ditolak'];
        $allowedPerPage = [10, 20, 50];

        if (! in_array($selectedStatus, $allowedStatuses, true)) {
            $selectedStatus = '';
        }

        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 20;
        }

        $baseQuery = SkkmSubmission::query()
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('nama_kegiatan', 'like', "%{$search}%")
                        ->orWhereHas('mahasiswa', function (Builder $studentQuery) use ($search) {
                            $studentQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('identifier', 'like', "%{$search}%");
                        })
                        ->orWhereHas('pointRule', function (Builder $ruleQuery) use ($search) {
                            $ruleQuery->where('jenis_item', 'like', "%{$search}%")
                                ->orWhere('tingkat', 'like', "%{$search}%");
                        });
                });
            });

        $stats = (clone $baseQuery)
            ->selectRaw('count(*) as total_submissions')
            ->selectRaw("sum(case when status_verifikasi = 'pending' then 1 else 0 end) as pending_submissions")
            ->selectRaw("sum(case when status_verifikasi = 'disetujui' then 1 else 0 end) as approved_submissions")
            ->selectRaw("sum(case when status_verifikasi = 'ditolak' then 1 else 0 end) as rejected_submissions")
            ->first();

        $submissions = (clone $baseQuery)
            ->with(['mahasiswa.programStudi.fakultas', 'pointRule', 'verifiedBy'])
            ->when($selectedStatus !== '', fn (Builder $query) => $query->where('status_verifikasi', $selectedStatus))
            ->latest()
            ->paginate($perPage)
            ->withQueryString();

        $activeStudents = $this->applyStudentScope(User::query())
            ->where('is_active', true)
            ->withSum(['skkmSubmissions as approved_points_sum' => fn (Builder $query) => $query->finalApproved()], 'poin_otomatis')
            ->get(['id', 'jenjang_studi']);

        $totalActiveStudents = $activeStudents->count();

        $studentsWithoutPoints = $activeStudents->filter(function (User $student): bool {
            return (int) ($student->approved_points_sum ?? 0) <= 0;
        })->count();

        $studentsWithCompletePoints = $activeStudents->filter(function (User $student): bool {
            $approvedPoints = (int) ($student->approved_points_sum ?? 0);
            $targetPoints = ($student->jenjang_studi ?? 'S1') === 'D3' ? 60 : 80;

            return $approvedPoints >= $targetPoints;
        })->count();

        $studentsInProgress = $activeStudents->filter(function (User $student): bool {
            $approvedPoints = (int) ($student->approved_points_sum ?? 0);
            $targetPoints = ($student->jenjang_studi ?? 'S1') === 'D3' ? 60 : 80;

            return $approvedPoints > 0 && $approvedPoints < $targetPoints;
        })->count();

        return view('skkm.kemahasiswaan.dashboard', [
            'submissions' => $submissions,
            'stats' => $stats,
            'studentStats' => [
                'total_active_students' => $totalActiveStudents,
                'students_without_points' => $studentsWithoutPoints,
                'students_in_progress' => $studentsInProgress,
                'students_completed' => $studentsWithCompletePoints,
            ],
            'search' => $search,
            'selectedStatus' => $selectedStatus,
            'statusOptions' => [
                'pending' => 'Pending',
                'disetujui' => 'Disetujui',
                'ditolak' => 'Ditolak',
            ],
            'perPage' => $perPage,
            'perPageOptions' => $allowedPerPage,
        ]);
    }

    public function kemahasiswaanMahasiswaIndex(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $selectedFakultas = $request->query('fakultas_id');
        $selectedProgramStudi = $request->query('program_studi_id');
        $selectedSemester = $request->query('semester');
        $selectedStatusYudisium = trim((string) $request->query('status_yudisium', ''));
        $perPage = (int) $request->query('per_page', 25);
        $allowedPerPage = [15, 25, 50];
        $semesterOptions = range(1, 14);
        $allowedStatusYudisium = ['memenuhi', 'dalam_proses', 'belum_memenuhi', 'belum_ada'];

        if (! in_array($perPage, $allowedPerPage, true)) {
            $perPage = 25;
        }

        if (! in_array($selectedStatusYudisium, $allowedStatusYudisium, true)) {
            $selectedStatusYudisium = '';
        }

        $selectedFakultas = is_numeric($selectedFakultas) ? (int) $selectedFakultas : null;
        $selectedProgramStudi = is_numeric($selectedProgramStudi) ? (int) $selectedProgramStudi : null;
        $selectedSemester = is_numeric($selectedSemester) ? (int) $selectedSemester : null;
        if (! in_array($selectedSemester, $semesterOptions, true)) {
            $selectedSemester = null;
        }

        $students = $this->applyStudentScope(User::query())
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('identifier', 'like', "%{$search}%")
                        ->orWhereHas('lecturer', function (Builder $lecturerQuery) use ($search) {
                            $lecturerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('identifier', 'like', "%{$search}%");
                        })
                        ->orWhereHas('programStudi', function (Builder $programStudiQuery) use ($search) {
                            $programStudiQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('jenjang', 'like', "%{$search}%")
                                ->orWhereHas('fakultas', function (Builder $fakultasQuery) use ($search) {
                                    $fakultasQuery->where('nama', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->when($selectedFakultas, function (Builder $query, int $fakultasId) {
                $query->whereHas('programStudi', fn (Builder $programStudiQuery) => $programStudiQuery->where('fakultas_id', $fakultasId));
            })
            ->when($selectedProgramStudi, fn (Builder $query, int $programStudiId) => $query->where('program_studi_id', $programStudiId))
            ->when($selectedSemester, function (Builder $query, int $semester) {
                $query->where(function (Builder $semesterQuery) use ($semester) {
                    $semesterQuery->where('semester', $semester)
                        ->orWhereHas('skkmProgress', fn (Builder $progressQuery) => $progressQuery->where('semester_aktif', $semester));
                });
            })
            ->when($selectedStatusYudisium !== '', function (Builder $query) use ($selectedStatusYudisium) {
                if ($selectedStatusYudisium === 'belum_ada') {
                    $query->whereDoesntHave('skkmProgress');

                    return;
                }

                $query->whereHas('skkmProgress', fn (Builder $progressQuery) => $progressQuery->where('status_yudisium', $selectedStatusYudisium));
            })
            ->with(['programStudi.fakultas', 'skkmProgress', 'lecturer'])
            ->orderBy('name')
            ->paginate($perPage)
            ->withQueryString();

        $fakultasOptions = Fakultas::query()
            ->orderBy('nama')
            ->get(['id', 'nama']);

        $programStudiOptions = ProgramStudi::query()
            ->select(['id', 'fakultas_id', 'nama', 'jenjang'])
            ->when($selectedFakultas, fn (Builder $query, int $fakultasId) => $query->where('fakultas_id', $fakultasId))
            ->orderBy('jenjang')
            ->orderBy('nama')
            ->get();

        return view('skkm.kemahasiswaan.mahasiswa', [
            'students' => $students,
            'search' => $search,
            'selectedFakultas' => $selectedFakultas,
            'selectedProgramStudi' => $selectedProgramStudi,
            'selectedSemester' => $selectedSemester,
            'selectedStatusYudisium' => $selectedStatusYudisium,
            'semesterOptions' => $semesterOptions,
            'statusYudisiumOptions' => [
                'memenuhi' => 'Memenuhi',
                'dalam_proses' => 'Dalam proses',
                'belum_memenuhi' => 'Belum memenuhi',
                'belum_ada' => 'Belum ada progres',
            ],
            'fakultasOptions' => $fakultasOptions,
            'programStudiOptions' => $programStudiOptions,
            'perPage' => $perPage,
            'perPageOptions' => $allowedPerPage,
        ]);
    }

    public function kemahasiswaanMahasiswaExport(Request $request)
    {
        $search = trim((string) $request->query('search', ''));
        $selectedFakultas = $request->query('fakultas_id');
        $selectedProgramStudi = $request->query('program_studi_id');
        $selectedSemester = $request->query('semester');
        $selectedStatusYudisium = trim((string) $request->query('status_yudisium', ''));
        $semesterOptions = range(1, 14);
        $allowedStatusYudisium = ['memenuhi', 'dalam_proses', 'belum_memenuhi', 'belum_ada'];

        if (! in_array($selectedStatusYudisium, $allowedStatusYudisium, true)) {
            $selectedStatusYudisium = '';
        }

        $selectedFakultas = is_numeric($selectedFakultas) ? (int) $selectedFakultas : null;
        $selectedProgramStudi = is_numeric($selectedProgramStudi) ? (int) $selectedProgramStudi : null;
        $selectedSemester = is_numeric($selectedSemester) ? (int) $selectedSemester : null;
        if (! in_array($selectedSemester, $semesterOptions, true)) {
            $selectedSemester = null;
        }

        $students = $this->applyStudentScope(User::query())
            ->when($search !== '', function (Builder $query) use ($search) {
                $query->where(function (Builder $subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('identifier', 'like', "%{$search}%")
                        ->orWhereHas('lecturer', function (Builder $lecturerQuery) use ($search) {
                            $lecturerQuery->where('name', 'like', "%{$search}%")
                                ->orWhere('identifier', 'like', "%{$search}%");
                        })
                        ->orWhereHas('programStudi', function (Builder $programStudiQuery) use ($search) {
                            $programStudiQuery->where('nama', 'like', "%{$search}%")
                                ->orWhere('jenjang', 'like', "%{$search}%")
                                ->orWhereHas('fakultas', function (Builder $fakultasQuery) use ($search) {
                                    $fakultasQuery->where('nama', 'like', "%{$search}%");
                                });
                        });
                });
            })
            ->when($selectedFakultas, function (Builder $query, int $fakultasId) {
                $query->whereHas('programStudi', fn (Builder $programStudiQuery) => $programStudiQuery->where('fakultas_id', $fakultasId));
            })
            ->when($selectedProgramStudi, fn (Builder $query, int $programStudiId) => $query->where('program_studi_id', $programStudiId))
            ->when($selectedSemester, function (Builder $query, int $semester) {
                $query->where(function (Builder $semesterQuery) use ($semester) {
                    $semesterQuery->where('semester', $semester)
                        ->orWhereHas('skkmProgress', fn (Builder $progressQuery) => $progressQuery->where('semester_aktif', $semester));
                });
            })
            ->when($selectedStatusYudisium !== '', function (Builder $query) use ($selectedStatusYudisium) {
                if ($selectedStatusYudisium === 'belum_ada') {
                    $query->whereDoesntHave('skkmProgress');

                    return;
                }

                $query->whereHas('skkmProgress', fn (Builder $progressQuery) => $progressQuery->where('status_yudisium', $selectedStatusYudisium));
            })
            ->with(['programStudi.fakultas', 'skkmProgress', 'lecturer'])
            ->orderBy('name')
            ->get();

        $fileName = 'data-mahasiswa-kemahasiswaan-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($students) {
            $output = fopen('php://output', 'w');
            if ($output === false) {
                return;
            }

            // BOM untuk kompatibilitas UTF-8 di Microsoft Excel
            fwrite($output, "\xEF\xBB\xBF");

            fputcsv($output, [
                'No',
                'Nama Mahasiswa',
                'NIM',
                'Dosen PA',
                'Identifier Dosen PA',
                'Fakultas',
                'Program Studi',
                'Semester Aktif',
                'Poin Smt 1-2',
                'Poin Smt 3-4',
                'Poin Smt 5-6',
                'Poin Smt 7-8',
                'Total Poin',
                'Status Yudisium',
            ], ';');

            foreach ($students as $index => $student) {
                $progress = $student->skkmProgress;
                $p12 = (int) ($progress->poin_smt_1_2 ?? 0);
                $p34 = (int) ($progress->poin_smt_3_4 ?? 0);
                $p56 = (int) ($progress->poin_smt_5_6 ?? 0);
                $p78 = (int) ($progress->poin_smt_7_8 ?? 0);
                $total = (int) ($progress->total_poin ?? ($p12 + $p34 + $p56 + $p78));
                $semesterAktif = $progress->semester_aktif ?? $student->semester;
                $status = $progress->status_yudisium ?? null;
                $statusLabel = match ($status) {
                    'memenuhi' => 'Memenuhi',
                    'belum_memenuhi' => 'Belum memenuhi',
                    'dalam_proses' => 'Dalam proses',
                    default => 'Belum ada progres',
                };

                fputcsv($output, [
                    $index + 1,
                    $student->name,
                    $student->identifier ?? '-',
                    $student->lecturer?->name ?? '-',
                    $student->lecturer?->identifier ?? '-',
                    $student->programStudi?->fakultas?->nama ?? '-',
                    trim((string) (($student->programStudi?->jenjang ?? '') . ' ' . ($student->programStudi?->nama ?? '-'))),
                    $semesterAktif ?? '-',
                    $p12,
                    $p34,
                    $p56,
                    $p78,
                    $total,
                    $statusLabel,
                ], ';');
            }

            fclose($output);
        }, $fileName, [
            'Content-Type' => 'text/csv; charset=UTF-8',
        ]);
    }

    /**
     * @param  Builder<User>  $query
     */
    private function applyStudentScope(Builder $query): Builder
    {
        return $query->where(function (Builder $studentRoleQuery) {
            $studentRoleQuery->whereIn('skkm_role', ['mahasiswa', 'student'])
                ->orWhere(function (Builder $fallbackRoleQuery) {
                    $fallbackRoleQuery->whereNull('skkm_role')
                        ->where('role', 'student');
                });
        });
    }
}
