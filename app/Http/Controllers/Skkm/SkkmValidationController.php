<?php

namespace App\Http\Controllers\Skkm;

use App\Http\Controllers\Controller;
use App\Models\SkkmProgress;
use App\Models\SkkmSubmission;
use App\Models\User;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class SkkmValidationController extends Controller
{
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

        $students = User::query()
            ->where('program_studi_id', $kaprodi->program_studi_id)
            ->where(function ($query) {
                $query->whereIn('skkm_role', ['mahasiswa', 'student'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNull('skkm_role')->where('role', 'student');
                    });
            })
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



    public function kemahasiswaanIndex(): View
    {
        $submissions = SkkmSubmission::query()
            ->with(['mahasiswa.programStudi.fakultas', 'pointRule', 'verifiedBy'])
            ->latest()
            ->paginate(20);

        $studentRecap = User::query()
            ->where(function ($query) {
                $query->whereIn('skkm_role', ['mahasiswa', 'student'])
                    ->orWhere(function ($subQuery) {
                        $subQuery->whereNull('skkm_role')->where('role', 'student');
                    });
            })
            ->with(['programStudi.fakultas'])
            ->withSum(['skkmSubmissions as final_points_sum' => fn ($query) => $query->finalApproved()], 'poin_otomatis')
            ->get()
            ->map(fn (User $student) => $this->formatRecapItem($student));

        $warningStudents = $studentRecap
            ->filter(fn (array $item) => $item['warning_message'] !== null)
            ->values();

        return view('skkm.kemahasiswaan.index', [
            'submissions' => $submissions,
            'studentRecap' => $studentRecap,
            'warningStudents' => $warningStudents,
        ]);
    }



    /**
     * @return array<string, mixed>
     */
    private function formatRecapItem(User $student): array
    {
        $totalPoin = (int) ($student->final_points_sum ?? 0);
        $jenjang = $student->jenjang_studi ?? 'S1';
        $semesterAktif = (int) ($student->semester ?? 1);

        $targetKelulusan = $jenjang === 'D3' ? 60 : 80;
        $targetWarningOranye = $jenjang === 'D3' ? 40 : 60;
        $semesterWarningOranye = $jenjang === 'D3' ? 5 : 7;
        $semesterAkhir = $jenjang === 'D3' ? 6 : 8;

        $targetBlok = match (true) {
            $semesterAktif <= 2 => 20,
            $semesterAktif <= 4 => 40,
            $semesterAktif <= 6 => 60,
            default => 80,
        };

        $warningLevel = null;
        $warningMessage = null;

        if ($semesterAktif >= $semesterAkhir && $totalPoin < $targetKelulusan) {
            $warningLevel = 'merah';
            $warningMessage = 'Poin belum mencukupi untuk yudisium.';
        } elseif ($semesterAktif >= $semesterWarningOranye && $totalPoin < $targetWarningOranye) {
            $warningLevel = 'oranye';
            $warningMessage = 'Segera lengkapi SKKM, tersisa satu blok semester.';
        } elseif ($totalPoin < $targetBlok) {
            $warningLevel = 'kuning';
            $warningMessage = 'Target poin blok semester belum mencapai batas minimal.';
        }

        return [
            'student' => $student,
            'total_poin' => $totalPoin,
            'target_kelulusan' => $targetKelulusan,
            'status_yudisium' => $totalPoin >= $targetKelulusan ? 'memenuhi' : 'belum_memenuhi',
            'warning_level' => $warningLevel,
            'warning_message' => $warningMessage,
        ];
    }
}
