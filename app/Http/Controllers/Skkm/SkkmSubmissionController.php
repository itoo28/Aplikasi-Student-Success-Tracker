<?php

namespace App\Http\Controllers\Skkm;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\PointRule;
use App\Models\SkkmProgress;
use App\Models\SkkmSubmission;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class SkkmSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        abort_unless(Auth::user()->hasSkkmRole('student'), 403);

        $user = Auth::user();

        // Mahasiswa melihat daftar pengajuannya
        $submissions = SkkmSubmission::where('mahasiswa_id', Auth::id())
            ->with('pointRule')
            ->orderBy('created_at', 'desc')
            ->get();

        $approvedPoints = (int) $user->skkmSubmissions()->finalApproved()->sum('poin_otomatis');
        $targetKelulusan = ($user->jenjang_studi ?? 'S1') === 'D3' ? 60 : 80;
        $statusYudisium = $approvedPoints >= $targetKelulusan ? 'memenuhi' : 'dalam_proses';
        $remainingPoints = max(0, $targetKelulusan - $approvedPoints);

        $pendingCount = $submissions->where('status_verifikasi', 'pending')->count();
        $approvedCount = $submissions->where('status_verifikasi', 'disetujui')->count();
        $rejectedCount = $submissions->where('status_verifikasi', 'ditolak')->count();
        $pendingPoints = (int) $submissions->where('status_verifikasi', 'pending')->sum('poin_otomatis');

        return view('skkm.mahasiswa.index', compact(
            'submissions', 'approvedPoints', 'targetKelulusan', 'statusYudisium',
            'remainingPoints', 'pendingCount', 'approvedCount', 'rejectedCount', 'pendingPoints', 'user'
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        abort_unless(Auth::user()->hasSkkmRole('student'), 403);

        // Get available rules for the form
        $rules = PointRule::where('is_active', true)->get();

        return view('skkm.mahasiswa.create', compact('rules'));
    }

    /**
     * Upload file bukti temporary/directly via AJAX.
     */
    public function uploadTemp(Request $request)
    {
        abort_unless(Auth::user()->hasSkkmRole('student'), 403);

        $request->validate([
            'file_bukti' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // maks 5MB
        ]);

        if ($request->hasFile('file_bukti')) {
            $path = $request->file('file_bukti')->store('bukti_skkm', 'public');
            
            return response()->json([
                'success' => true,
                'path' => $path,
                'url' => asset('storage/' . $path)
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => 'File tidak ditemukan.'
        ], 400);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->hasSkkmRole('student'), 403);

        $request->validate([
            'point_rule_id' => ['required', Rule::exists('point_rules', 'id')->where('is_active', true)],
            'nama_kegiatan' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'file_bukti' => $request->filled('uploaded_file_path') ? 'nullable' : 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // maks 5MB
            'uploaded_file_path' => 'nullable|string',
            'semester_input' => 'required|integer|min:1|max:8',
        ]);

        $pointRule = PointRule::query()
            ->where('is_active', true)
            ->findOrFail($request->point_rule_id);

        if ($request->filled('uploaded_file_path')) {
            $path = $request->uploaded_file_path;
        } else {
            $path = $request->file('file_bukti')->store('bukti_skkm', 'public');
        }

        SkkmSubmission::create([
            'mahasiswa_id' => Auth::id(),
            'point_rule_id' => $pointRule->id,
            'nama_kegiatan' => $request->nama_kegiatan,
            'penyelenggara' => $request->penyelenggara,
            'tanggal_kegiatan' => $request->tanggal_kegiatan,
            'file_bukti' => $path,
            'semester_input' => $request->semester_input,
            'poin_otomatis' => $pointRule->poin,
            'status_verifikasi' => 'pending',
            'is_progress_counted' => false,
        ]);

        return redirect()->route('skkm.index')->with('success', 'Pengajuan SKKM berhasil dikirim dan menunggu verifikasi.');
    }

    /**
     * Dashboard Verifikasi untuk Dosen PA
     */
    public function verifikasiIndex()
    {
        abort_unless(Auth::user()->hasSkkmRole('dosen_pa'), 403);

        // Menampilkan daftar pengajuan dari mahasiswa bimbingannya (dengan status pending)
        // Note: Asumsi User memiliki relasi adviseeStudents yang sudah ada
        $adviseeIds = Auth::user()->adviseeStudents()->pluck('id');

        $pendingSubmissions = SkkmSubmission::whereIn('mahasiswa_id', $adviseeIds)
            ->where('status_verifikasi', 'pending')
            ->with(['mahasiswa', 'pointRule'])
            ->orderBy('created_at', 'asc')
            ->get();

        $verifiedSubmissions = SkkmSubmission::whereIn('mahasiswa_id', $adviseeIds)
            ->whereIn('status_verifikasi', ['disetujui', 'ditolak'])
            ->with(['mahasiswa', 'pointRule'])
            ->orderBy('verified_at', 'desc')
            ->take(20)
            ->get();

        return view('skkm.dosen.verifikasi', compact('pendingSubmissions', 'verifiedSubmissions'));
    }

    /**
     * Monitoring data mahasiswa bimbingan untuk Dosen PA
     */
    public function monitoringIndex(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->hasSkkmRole('dosen_pa'), 403);

        $search = trim((string) $request->query('q', $request->query('search', '')));
        $selectedSemester = $request->query('semester');
        $selectedStatusYudisium = trim((string) $request->query('status_yudisium', ''));
        $perPage = (int) $request->query('per_page', 25);
        $perPageOptions = [15, 25, 50];

        if (! in_array($perPage, $perPageOptions, true)) {
            $perPage = 25;
        }

        $semesterOptions = range(1, 14);
        $statusYudisiumOptions = [
            'memenuhi' => 'Memenuhi',
            'dalam_proses' => 'Dalam proses',
            'belum_memenuhi' => 'Belum memenuhi',
            'belum_ada' => 'Belum ada progres',
        ];
        $allowedStatusYudisium = array_keys($statusYudisiumOptions);

        $selectedSemester = is_numeric($selectedSemester) ? (int) $selectedSemester : null;
        if (! in_array($selectedSemester, $semesterOptions, true)) {
            $selectedSemester = null;
        }

        if (! in_array($selectedStatusYudisium, $allowedStatusYudisium, true)) {
            $selectedStatusYudisium = '';
        }

        $studentsQuery = $user->adviseeStudents()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('identifier', 'like', "%{$search}%");
                });
            })
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
            });

        // Hitung statistik berdasarkan filter aktif (sebelum dipaginasi)
        $totalStudents = (clone $studentsQuery)->count();

        $studentsFulfilled = (clone $studentsQuery)
            ->whereHas('skkmProgress', fn ($q) => $q->where('status_yudisium', 'memenuhi'))
            ->count();

        $studentsInProgress = (clone $studentsQuery)
            ->whereHas('skkmProgress', fn ($q) => $q->whereIn('status_yudisium', ['dalam_proses', 'belum_memenuhi']))
            ->count();

        $students = $studentsQuery
            ->with(['programStudi.fakultas', 'skkmProgress'])
            ->orderBy('name')
            ->paginate($perPage, ['*'], 'students_page')
            ->withQueryString();

        return view('skkm.dosen.mahasiswa', [
            'students' => $students,
            'totalStudents' => $totalStudents,
            'studentsFulfilled' => $studentsFulfilled,
            'studentsInProgress' => $studentsInProgress,
            'search' => $search,
            'selectedSemester' => $selectedSemester,
            'selectedStatusYudisium' => $selectedStatusYudisium,
            'semesterOptions' => $semesterOptions,
            'statusYudisiumOptions' => $statusYudisiumOptions,
            'perPage' => $perPage,
            'perPageOptions' => $perPageOptions,
        ]);
    }

    public function monitoringExport(Request $request)
    {
        $user = Auth::user();
        abort_unless($user->hasSkkmRole('dosen_pa'), 403);

        $search = trim((string) $request->query('q', $request->query('search', '')));
        $selectedSemester = $request->query('semester');
        $selectedStatusYudisium = trim((string) $request->query('status_yudisium', ''));
        $semesterOptions = range(1, 14);
        $statusYudisiumOptions = [
            'memenuhi' => 'Memenuhi',
            'dalam_proses' => 'Dalam proses',
            'belum_memenuhi' => 'Belum memenuhi',
            'belum_ada' => 'Belum ada progres',
        ];
        $allowedStatusYudisium = array_keys($statusYudisiumOptions);

        $selectedSemester = is_numeric($selectedSemester) ? (int) $selectedSemester : null;
        if (! in_array($selectedSemester, $semesterOptions, true)) {
            $selectedSemester = null;
        }

        if (! in_array($selectedStatusYudisium, $allowedStatusYudisium, true)) {
            $selectedStatusYudisium = '';
        }

        $students = $user->adviseeStudents()
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($subQuery) use ($search) {
                    $subQuery->where('name', 'like', "%{$search}%")
                        ->orWhere('identifier', 'like', "%{$search}%");
                });
            })
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
            ->with(['programStudi.fakultas', 'skkmProgress'])
            ->orderBy('name')
            ->get();

        $fileName = 'poin-skkm-mahasiswa-bimbingan-' . now()->format('Ymd-His') . '.csv';

        return response()->streamDownload(function () use ($students) {
            $output = fopen('php://output', 'w');
            if ($output === false) {
                return;
            }

            // BOM untuk kompatibilitas UTF-8 di Microsoft Excel
            fwrite($output, "\xEF\xBB\xBF");
            fwrite($output, "sep=;\r\n");

            fputcsv($output, [
                'No',
                'Nama Mahasiswa',
                'NIM',
                'Fakultas',
                'Program Studi',
                'Semester',
                'Poin Semester 1-2',
                'Poin Semester 3-4',
                'Poin Semester 5-6',
                'Poin Semester 7-8',
                'Total Poin SKKM',
                'Status SKKM (Yudisium)',
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
                    'belum_memenuhi' => 'Belum Memenuhi',
                    'dalam_proses' => 'Dalam Proses',
                    default => 'Belum Ada Progres',
                };

                // Format identifier (NIM) agar tidak terpotong leading zero di Excel
                $nim = $student->identifier;
                $formattedNim = $nim ? '="' . $nim . '"' : '-';

                fputcsv($output, [
                    $index + 1,
                    trim($student->name),
                    $formattedNim,
                    trim($student->programStudi?->fakultas?->nama ?? '-'),
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
     * Verifikasi oleh Dosen PA
     */
    public function verify(Request $request, SkkmSubmission $submission)
    {
        abort_unless(Auth::user()->hasSkkmRole('dosen_pa'), 403);

        if ((int) $submission->mahasiswa_id !== (int) Auth::user()->adviseeStudents()->whereKey($submission->mahasiswa_id)->value('id')) {
            abort(403);
        }

        if ($submission->status_verifikasi !== 'pending') {
            return redirect()->back()->withErrors(['verify' => 'Pengajuan ini sudah diverifikasi pada tahap Dosen PA.']);
        }

        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'required_if:status_verifikasi,ditolak|string|nullable',
        ]);

        $payload = [
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_dosen' => $request->catatan_dosen,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ];

        if ($request->status_verifikasi === 'disetujui') {
            // Single-stage: Dosen PA approval is final
        } else {
            // Ditolak — no extra fields needed
        }

        $submission->update($payload);

        if ($request->status_verifikasi === 'disetujui') {
            $this->applyFinalPointToProgress($submission);
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi.');
    }

    private function applyFinalPointToProgress(SkkmSubmission $submission): void
    {
        if ($submission->is_progress_counted) {
            return;
        }

        $submission->loadMissing('mahasiswa');

        $student = $submission->mahasiswa;
        $jenjang = $student?->jenjang_studi ?? 'S1';
        $semesterAktif = $student?->semester ?? max(1, (int) $submission->semester_input);

        $progress = SkkmProgress::firstOrCreate(
            ['mahasiswa_id' => $submission->mahasiswa_id],
            [
                'jenjang' => $jenjang,
                'semester_aktif' => $semesterAktif,
                'status_yudisium' => 'dalam_proses',
            ]
        );

        $poin = (int) $submission->poin_otomatis;
        $semester = (int) $submission->semester_input;
        $blockColumn = $this->resolveBlockColumn($semester);

        $progress->{$blockColumn} += $poin;
        $progress->total_poin += $poin;
        $progress->semester_aktif = max((int) $progress->semester_aktif, $semesterAktif);
        $progress->jenjang = $jenjang;
        $progress->status_yudisium = $this->resolveYudisiumStatus(
            $jenjang,
            (int) $progress->semester_aktif,
            (int) $progress->total_poin,
        );

        $progress->save();

        $submission->update(['is_progress_counted' => true]);
    }

    private function resolveBlockColumn(int $semester): string
    {
        if ($semester <= 2) {
            return 'poin_smt_1_2';
        }

        if ($semester <= 4) {
            return 'poin_smt_3_4';
        }

        if ($semester <= 6) {
            return 'poin_smt_5_6';
        }

        return 'poin_smt_7_8';
    }

    private function resolveYudisiumStatus(string $jenjang, int $semesterAktif, int $totalPoin): string
    {
        $target = $jenjang === 'D3' ? 60 : 80;
        $semesterAkhir = $jenjang === 'D3' ? 6 : 8;

        if ($totalPoin >= $target) {
            return 'memenuhi';
        }

        if ($semesterAktif >= $semesterAkhir) {
            return 'belum_memenuhi';
        }

        return 'dalam_proses';
    }
}
