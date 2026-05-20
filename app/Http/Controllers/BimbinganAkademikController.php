<?php

namespace App\Http\Controllers;

use App\Models\GuidanceLog;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BimbinganAkademikController extends Controller
{
    // --- ROLE: MAHASISWA ---
    public function mahasiswaIndex()
    {
        $mahasiswa = Auth::user();
        $semesterAktif = $mahasiswa->semester ?? 1; // Fallback ke semester 1 jika null
        
        $riwayat = GuidanceLog::where('user_id', $mahasiswa->id)->latest()->get();
        // Ambil data dosen PA berdasarkan relasi lecturer di model User
        $dosen_pa = $mahasiswa->lecturer; 
        
        $bimbinganSemesterIni = GuidanceLog::where('user_id', $mahasiswa->id)
            ->where('semester', $semesterAktif)
            ->count();
        
        return view('bimbingan.mahasiswa', compact('riwayat', 'dosen_pa', 'bimbinganSemesterIni', 'semesterAktif'));
    }

    public function mahasiswaStore(Request $request)
    {
        $mahasiswa = Auth::user();
        $semesterAktif = $mahasiswa->semester ?? 1;
        
        $bimbinganSemesterIni = GuidanceLog::where('user_id', $mahasiswa->id)
            ->where('semester', $semesterAktif)
            ->count();

        if ($bimbinganSemesterIni >= 3) {
            return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal 3 kali bimbingan semester ini.');
        }

        $request->validate([
            'guidance_date' => 'required|date',
            'topic' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('bimbingan_dokumen', 'public');
        }

        GuidanceLog::create([
            'user_id' => $mahasiswa->id,
            'lecturer_id' => $mahasiswa->lecturer_id, 
            'semester' => $semesterAktif,
            'guidance_date' => $request->guidance_date,
            'topic' => $request->topic,
            'document_path' => $path,
            'status' => 'pending',
            'notes' => '-',
        ]);

        return redirect()->route('bimbingan.mahasiswa.index')->with('success', 'Pengajuan bimbingan berhasil dikirim.');
    }

    // --- ROLE: DOSEN PA ---
    public function dosenIndex()
    {
        $dosen = Auth::user();
        
        // Statistik dinamis berdasarkan 2 digit awal identifier (NIM)
        $statistikAngkatan = $dosen->adviseeStudents()
            ->selectRaw("SUBSTRING(identifier, 1, 2) as angkatan_kode, COUNT(*) as total_anak")
            ->groupBy('angkatan_kode')
            ->get()
            ->map(function ($item) {
                return [
                    'angkatan' => '20' . $item->angkatan_kode,
                    'total' => $item->total_anak
                ];
            });

        // Daftar pengajuan mahasiswa bimbingannya
        $pengajuan = GuidanceLog::where('lecturer_id', $dosen->id)
            ->with('student')
            ->latest()
            ->get();
            
        $mahasiswaBimbingan = $dosen->adviseeStudents()->get();

        return view('bimbingan.dosen', compact('statistikAngkatan', 'pengajuan', 'mahasiswaBimbingan'));
    }

    public function dosenUpdate(Request $request, GuidanceLog $log)
    {
        // Pastikan dosen yang mengubah adalah dosen yang bersangkutan
        if ($log->lecturer_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:validated,revised', // validated = Disetujui, revised = Ditolak
            'notes' => 'required|string',
        ]);

        $log->update([
            'status' => $request->status,
            'notes' => $request->notes,
        ]);

        return redirect()->route('bimbingan.dosen.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }
    
    public function dosenStore(Request $request)
    {
        $dosen = Auth::user();
        
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'guidance_date' => 'required|date',
            'topic' => 'required|string|max:255',
            'notes' => 'required|string',
        ]);
        
        $mahasiswa = User::findOrFail($request->user_id);
        
        if ($mahasiswa->lecturer_id !== $dosen->id) {
            abort(403);
        }
        
        $semesterAktif = $mahasiswa->semester ?? 1;
        
        $bimbinganSemesterIni = GuidanceLog::where('user_id', $mahasiswa->id)
            ->where('semester', $semesterAktif)
            ->count();

        if ($bimbinganSemesterIni >= 3) {
            return redirect()->back()->with('error', 'Mahasiswa ini sudah mencapai batas maksimal 3 kali bimbingan di semester aktifnya.');
        }

        GuidanceLog::create([
            'user_id' => $mahasiswa->id,
            'lecturer_id' => $dosen->id,
            'semester' => $semesterAktif,
            'guidance_date' => $request->guidance_date,
            'topic' => $request->topic,
            'document_path' => null,
            'status' => 'validated', // Langsung disetujui jika dosen yang input
            'notes' => $request->notes,
        ]);

        return redirect()->route('bimbingan.dosen.index')->with('success', 'Bimbingan berhasil ditambahkan secara manual.');
    }

    // --- ROLE: KAPRODI & KEMAHASISWAAN ---
    public function rekapitulasiIndex()
    {
        // Mendapatkan seluruh mahasiswa yang memiliki dosen pembimbing
        $mahasiswaList = User::whereNotNull('lecturer_id')
            ->where(function($query) {
                $query->where('role', 'student')
                      ->orWhere('skkm_role', 'student')
                      ->orWhere('skkm_role', 'mahasiswa');
            })
            ->withCount(['guidanceLogs' => function($query) {
                // Sesuai prompt: hitung bimbingan di semester aktif
                $query->whereColumn('guidance_logs.semester', 'users.semester');
            }])
            ->get();
            
        // Rekap total
        $totalMahasiswa = $mahasiswaList->count();
        
        // Mahasiswa yang memenuhi syarat (>= 1 bimbingan di semester aktif)
        $memenuhiSyarat = $mahasiswaList->where('guidance_logs_count', '>=', 1)->count();
        $belumBimbingan = $totalMahasiswa - $memenuhiSyarat;

        return view('bimbingan.rekapitulasi', compact('mahasiswaList', 'totalMahasiswa', 'memenuhiSyarat', 'belumBimbingan'));
    }
}
