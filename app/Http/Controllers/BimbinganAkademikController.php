<?php

namespace App\Http\Controllers;

use App\Models\Bimbingan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BimbinganAkademikController extends Controller
{
    // --- ROLE: MAHASISWA ---
    public function mahasiswaIndex()
    {
        $mahasiswa = Auth::user();
        $semesterAktif = $mahasiswa->semester ?? 1;

        $riwayat = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->with(['mahasiswa', 'dosen'])
            ->latest()
            ->get();

        $bimbinganSemesterIni = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester', $semesterAktif)
            ->where('status', 'completed')
            ->count();

        return view('bimbingan.mahasiswa', compact('riwayat', 'bimbinganSemesterIni', 'semesterAktif'));
    }

    public function mahasiswaStore(Request $request)
    {
        $mahasiswa = Auth::user();
        $semesterAktif = $mahasiswa->semester ?? 1;

        if (! $mahasiswa->lecturer_id) {
            return redirect()->back()->with('error', 'Akun Anda belum memiliki Dosen PA. Silakan hubungi admin program studi.');
        }

        $bimbinganSemesterIni = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
            ->where('semester', $semesterAktif)
            ->where('status', 'completed')
            ->count();

        if ($bimbinganSemesterIni >= 3) {
            return redirect()->back()->with('error', 'Anda sudah mencapai batas maksimal 3 kali bimbingan semester ini.');
        }

        $request->validate([
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $path = null;
        if ($request->hasFile('document')) {
            $path = $request->file('document')->store('bimbingan_dokumen', 'public');
        }

        $bimbingan = Bimbingan::create([
            'mahasiswa_id' => $mahasiswa->id,
            'dosen_id' => $mahasiswa->lecturer_id,
            'semester' => $semesterAktif,
            'tanggal' => $request->tanggal,
            'topik' => $request->topik,
            'catatan' => '-',
            'tipe_pengajuan' => 'mandiri_mahasiswa',
            'status' => 'pending',
            'document_path' => $path,
        ]);

        $bimbingan->loadMissing(['mahasiswa', 'dosen']);

        return redirect()->route('bimbingan.mahasiswa.index')->with([
            'success' => 'Pengajuan bimbingan berhasil dikirim. Saat ini pengajuan Anda menunggu validasi dosen PA.',
            'submitted_bimbingan_whatsapp_link' => $bimbingan->dosen_whatsapp_link,
            'submitted_bimbingan_dosen_name' => $bimbingan->dosen?->name,
            'submitted_bimbingan_topik' => $bimbingan->topik,
            'submitted_bimbingan_tanggal' => $bimbingan->tanggal?->format('d M Y'),
        ]);
    }

    // --- ROLE: DOSEN PA ---
    public function dosenIndex(Request $request)
    {
        $dosen = Auth::user();
        $filterType = $request->query('filter_type', 'all');
        $filterValue = $request->query('filter_value');

        $statistikAngkatan = $dosen->adviseeStudents()
            ->whereNotNull('identifier')
            ->selectRaw("DISTINCT SUBSTRING(identifier, 1, 2) as angkatan_kode")
            ->get()
            ->map(function ($item) {
                return '20' . $item->angkatan_kode;
            })
            ->filter()
            ->values();

        $mahasiswaBimbingan = $dosen->adviseeStudents()->with('programStudi')->get();
        $angkatanOptions = $statistikAngkatan;

        $pendingRequestsQuery = Bimbingan::where('dosen_id', $dosen->id)
            ->where('tipe_pengajuan', 'mandiri_mahasiswa')
            ->with('mahasiswa.programStudi');

        if ($filterType === 'angkatan' && $filterValue) {
            $angkatanCode = substr($filterValue, 2);
            $pendingRequestsQuery->whereHas('mahasiswa', function ($query) use ($angkatanCode) {
                $query->whereRaw('SUBSTRING(identifier, 1, 2) = ?', [$angkatanCode]);
            });
        }

        if ($filterType === 'individu' && $filterValue) {
            $pendingRequestsQuery->where('mahasiswa_id', $filterValue);
        }

        $pendingRequests = $pendingRequestsQuery->latest()->get();

        $scheduledBimbingan = Bimbingan::where('dosen_id', $dosen->id)
            ->where('tipe_pengajuan', 'undangan_dosen')
            ->with('mahasiswa.programStudi')
            ->orderBy('tanggal', 'asc')
            ->get();

        // Kelompokkan bimbingan terjadwal berdasarkan group_key
        $scheduledBimbinganGrouped = $scheduledBimbingan->groupBy(function ($item) {
            return $item->group_key ?? 'individual_' . $item->id;
        });

        return view('bimbingan.dosen', compact(
            'statistikAngkatan',
            'pendingRequests',
            'scheduledBimbinganGrouped',
            'mahasiswaBimbingan',
            'angkatanOptions',
            'filterType',
            'filterValue'
        ));
    }

    public function dosenStore(Request $request)
    {
        $dosen = Auth::user();

        $request->validate([
            'filter_type' => 'required|in:all,angkatan,individu',
            'filter_value' => 'nullable',
            'tanggal' => 'required|date',
            'topik' => 'required|string|max:255',
            'catatan' => 'nullable|string',
            'user_id' => 'nullable|exists:users,id',
        ]);

        if ($request->filter_type === 'angkatan' && ! $request->filter_value) {
            return redirect()->back()->with('error', 'Silakan pilih filter yang sesuai sebelum menyimpan bimbingan.');
        }

        if ($request->filter_type === 'individu' && ! $request->user_id) {
            return redirect()->back()->with('error', 'Silakan pilih mahasiswa terlebih dahulu.');
        }

        $studentsQuery = $dosen->adviseeStudents();

        if ($request->filter_type === 'angkatan') {
            $angkatanCode = substr($request->filter_value, 2);
            $studentsQuery->whereRaw('SUBSTRING(identifier, 1, 2) = ?', [$angkatanCode]);
        }

        if ($request->filter_type === 'individu') {
            $studentsQuery->where('id', $request->user_id);
        }

        $students = $studentsQuery->get();

        if ($students->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ditemukan mahasiswa untuk filter yang dipilih.');
        }

        $created = [];
        $skipped = [];

        // Generate group_key jika tipe filter adalah kelompok (all / angkatan)
        $groupKey = null;
        if (in_array($request->filter_type, ['all', 'angkatan'])) {
            $groupKey = 'grp_' . uniqid() . '_' . time();
        }

        foreach ($students as $mahasiswa) {
            $semesterAktif = $mahasiswa->semester ?? 1;
            $bimbinganSemesterIni = Bimbingan::where('mahasiswa_id', $mahasiswa->id)
                ->where('semester', $semesterAktif)
                ->where('status', 'completed')
                ->count();

            if ($bimbinganSemesterIni >= 3) {
                $skipped[] = $mahasiswa->name;
                continue;
            }

            Bimbingan::create([
                'mahasiswa_id' => $mahasiswa->id,
                'dosen_id' => $dosen->id,
                'semester' => $semesterAktif,
                'tanggal' => $request->tanggal,
                'topik' => $request->topik,
                'catatan' => $request->catatan ?? '-',
                'tipe_pengajuan' => 'undangan_dosen',
                'status' => 'validated',
                'group_key' => $groupKey,
            ]);

            $created[] = $mahasiswa->name;
        }

        if (empty($created)) {
            return redirect()->back()->with('error', 'Semua mahasiswa terpilih telah mencapai batas maksimal bimbingan semester ini.');
        }

        $message = 'Bimbingan berhasil ditambahkan untuk ' . count($created) . ' mahasiswa.';
        if (! empty($skipped)) {
            $message .= ' Beberapa mahasiswa tidak ditambahkan karena sudah mencapai batas: ' . implode(', ', $skipped) . '.';
        }

        return redirect()->route('bimbingan.dosen.index')->with('success', $message);
    }

    public function dosenUpdate(Request $request, Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:validated,revised',
            'catatan' => 'required|string',
        ]);

        $bimbingan->update([
            'status' => $request->status,
            'catatan' => $request->catatan,
        ]);

        return redirect()->route('bimbingan.dosen.index')->with('success', 'Status pengajuan berhasil diperbarui.');
    }

    public function dosenReport(Request $request, Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }

        $request->validate([
            'resolution' => 'required|string|max:1000',
            'activity_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $photoPath = $bimbingan->activity_photo_path;
        if ($request->hasFile('activity_photo')) {
            $photoPath = $request->file('activity_photo')->store('bimbingan_foto', 'public');
        }

        if ($bimbingan->group_key) {
            Bimbingan::where('group_key', $bimbingan->group_key)
                ->where('dosen_id', Auth::id())
                ->update([
                    'resolution' => $request->resolution,
                    'activity_photo_path' => $photoPath,
                    'status' => 'completed',
                ]);
        } else {
            $bimbingan->update([
                'resolution' => $request->resolution,
                'activity_photo_path' => $photoPath,
                'status' => 'completed',
            ]);
        }

        return redirect()->route('bimbingan.dosen.index')->with('success', 'Laporan bimbingan berhasil disimpan.');
    }

    public function dosenDestroy(Bimbingan $bimbingan)
    {
        if ($bimbingan->dosen_id !== Auth::id()) {
            abort(403);
        }

        if ($bimbingan->group_key) {
            Bimbingan::where('group_key', $bimbingan->group_key)
                ->where('dosen_id', Auth::id())
                ->delete();
        } else {
            $bimbingan->delete();
        }

        return redirect()->route('bimbingan.dosen.index')->with('success', 'Jadwal bimbingan berhasil dibatalkan/dihapus.');
    }

    // --- ROLE: KAPRODI & KEMAHASISWAAN ---
    public function rekapitulasiIndex()
    {
        $mahasiswaList = User::whereNotNull('lecturer_id')
            ->where(function ($query) {
                $query->where('role', 'student')
                      ->orWhere('skkm_role', 'student')
                      ->orWhere('skkm_role', 'mahasiswa');
            })
            ->withCount(['bimbingans as bimbingan_semester_count' => function ($query) {
                $query->whereColumn('bimbingans.semester', 'users.semester')
                    ->where('status', 'completed');
            }])
            ->with('programStudi.fakultas')
            ->get();

        $totalMahasiswa = $mahasiswaList->count();
        $memenuhiSyarat = $mahasiswaList->where('bimbingan_semester_count', '>=', 1)->count();
        $belumBimbingan = $totalMahasiswa - $memenuhiSyarat;

        return view('bimbingan.rekapitulasi', compact('mahasiswaList', 'totalMahasiswa', 'memenuhiSyarat', 'belumBimbingan'));
    }
}
