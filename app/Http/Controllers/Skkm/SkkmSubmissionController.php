<?php

namespace App\Http\Controllers\Skkm;

use App\Http\Controllers\Controller;
use App\Models\PointRule;
use App\Models\SkkmProgress;
use App\Models\SkkmSubmission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        $pendingCount  = $submissions->where('status_verifikasi', 'pending')->count();
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        abort_unless(Auth::user()->hasSkkmRole('student'), 403);

        $request->validate([
            'point_rule_id' => 'required|exists:point_rules,id',
            'nama_kegiatan' => 'required|string|max:255',
            'penyelenggara' => 'required|string|max:255',
            'tanggal_kegiatan' => 'required|date',
            'file_bukti' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120', // maks 5MB
            'semester_input' => 'required|integer|min:1|max:8',
        ]);

        $pointRule = PointRule::findOrFail($request->point_rule_id);
        
        $path = $request->file('file_bukti')->store('bukti_skkm', 'public');

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
    public function monitoringIndex()
    {
        abort_unless(Auth::user()->hasSkkmRole('dosen_pa'), 403);

        $students = Auth::user()
            ->adviseeStudents()
            ->with(['programStudi.fakultas', 'skkmProgress'])
            ->orderBy('name')
            ->get();

        return view('skkm.dosen.mahasiswa', compact('students'));
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
            $payload['status_kaprodi'] = 'disetujui'; // Bypass Kaprodi
            $payload['status_kemahasiswaan'] = 'disetujui'; // Bypass Kemahasiswaan
            $payload['kaprodi_verified_by'] = Auth::id();
            $payload['kaprodi_verified_at'] = now();
            $payload['kemahasiswaan_verified_by'] = Auth::id();
            $payload['kemahasiswaan_verified_at'] = now();
        } else {
            $payload['status_kaprodi'] = 'ditolak';
            $payload['status_kemahasiswaan'] = 'ditolak';
            $payload['kaprodi_verified_by'] = null;
            $payload['kaprodi_verified_at'] = null;
            $payload['kemahasiswaan_verified_by'] = null;
            $payload['kemahasiswaan_verified_at'] = null;
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
