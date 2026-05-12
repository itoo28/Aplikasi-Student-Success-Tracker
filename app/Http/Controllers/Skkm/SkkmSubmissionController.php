<?php

namespace App\Http\Controllers\Skkm;

use App\Http\Controllers\Controller;
use App\Models\SkkmSubmission;
use App\Models\PointRule;
use App\Models\SkkmProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SkkmSubmissionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Mahasiswa melihat daftar pengajuannya
        $submissions = SkkmSubmission::where('mahasiswa_id', Auth::id())
            ->with('pointRule')
            ->orderBy('created_at', 'desc')
            ->get();

        $progress = SkkmProgress::where('mahasiswa_id', Auth::id())->first();

        return view('skkm.mahasiswa.index', compact('submissions', 'progress'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        // Get available rules for the form
        $rules = PointRule::where('is_active', true)->get();
        return view('skkm.mahasiswa.create', compact('rules'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
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
        ]);

        return redirect()->route('skkm.index')->with('success', 'Pengajuan SKKM berhasil dikirim dan menunggu verifikasi.');
    }

    /**
     * Verifikasi oleh Dosen PA
     */
    public function verify(Request $request, SkkmSubmission $submission)
    {
        $request->validate([
            'status_verifikasi' => 'required|in:disetujui,ditolak',
            'catatan_dosen' => 'required_if:status_verifikasi,ditolak|string|nullable',
        ]);

        $submission->update([
            'status_verifikasi' => $request->status_verifikasi,
            'catatan_dosen' => $request->catatan_dosen,
            'verified_by' => Auth::id(),
            'verified_at' => now(),
        ]);

        if ($request->status_verifikasi === 'disetujui') {
            $this->updateProgress($submission);
        }

        return redirect()->back()->with('success', 'Pengajuan berhasil diverifikasi.');
    }

    /**
     * Update SKKM Progress after approval
     */
    private function updateProgress(SkkmSubmission $submission)
    {
        $progress = SkkmProgress::firstOrCreate(
            ['mahasiswa_id' => $submission->mahasiswa_id],
            ['jenjang' => 'S1', 'semester_aktif' => 1] // Nilai default jika belum ada
        );

        $semester = $submission->semester_input;
        $poin = $submission->poin_otomatis;

        if ($semester <= 2) {
            $progress->increment('poin_smt_1_2', $poin);
        } elseif ($semester <= 4) {
            $progress->increment('poin_smt_3_4', $poin);
        } elseif ($semester <= 6) {
            $progress->increment('poin_smt_5_6', $poin);
        } else {
            $progress->increment('poin_smt_7_8', $poin);
        }

        $progress->increment('total_poin', $poin);
        
        // Logika yudisium
        $batas_kelulusan = ($progress->jenjang == 'D3') ? 60 : 80;
        if ($progress->total_poin >= $batas_kelulusan) {
            $progress->update(['status_yudisium' => 'memenuhi']);
        }
    }
}
