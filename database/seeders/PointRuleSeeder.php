<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\PointRule;

class PointRuleSeeder extends Seeder
{
    public function run(): void
    {
        $rules = [
            // UNSUR 1: PENALARAN & KEILMUAN
            // 1.1 Penulisan Karya Ilmiah â€” Jurnal / Majalah Ilmiah (per artikel)
            ['unsur' => 'penalaran', 'sub_unsur' => 'jurnal_ilmiah', 'jenis_item' => 'Penulis', 'tingkat' => 'internasional', 'peranan' => 'penulis', 'poin' => 50, 'bukti_fisik_required' => 'Artikel yang dipublikasikan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'jurnal_ilmiah', 'jenis_item' => 'Penulis', 'tingkat' => 'nasional', 'peranan' => 'penulis', 'poin' => 35, 'bukti_fisik_required' => 'Artikel yang dipublikasikan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'jurnal_ilmiah', 'jenis_item' => 'Penulis', 'tingkat' => 'regional', 'peranan' => 'penulis', 'poin' => 20, 'bukti_fisik_required' => 'Artikel yang dipublikasikan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'jurnal_ilmiah', 'jenis_item' => 'Penulis', 'tingkat' => 'universitas', 'peranan' => 'penulis', 'poin' => 15, 'bukti_fisik_required' => 'Artikel yang dipublikasikan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'jurnal_ilmiah', 'jenis_item' => 'Penulis', 'tingkat' => 'fakultas', 'peranan' => 'penulis', 'poin' => 10, 'bukti_fisik_required' => 'Artikel yang dipublikasikan'],

            // 1.4 Lomba Karya Ilmiah / KTI / Pemikiran Kritis / Debat â€” Mendapat Prestasi
            ['unsur' => 'penalaran', 'sub_unsur' => 'lomba_kti', 'jenis_item' => 'Mendapat Prestasi', 'tingkat' => 'internasional', 'peranan' => 'juara_1', 'poin' => 80, 'bukti_fisik_required' => 'Sertifikat, Karya Tulis, Foto kegiatan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'lomba_kti', 'jenis_item' => 'Mendapat Prestasi', 'tingkat' => 'internasional', 'peranan' => 'juara_2', 'poin' => 45, 'bukti_fisik_required' => 'Sertifikat, Karya Tulis, Foto kegiatan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'lomba_kti', 'jenis_item' => 'Mendapat Prestasi', 'tingkat' => 'nasional', 'peranan' => 'juara_1', 'poin' => 40, 'bukti_fisik_required' => 'Sertifikat, Karya Tulis, Foto kegiatan'],
            ['unsur' => 'penalaran', 'sub_unsur' => 'lomba_kti', 'jenis_item' => 'Sebagai Peserta', 'tingkat' => 'internasional', 'peranan' => 'peserta', 'poin' => 30, 'bukti_fisik_required' => 'Sertifikat / Tulisan dipublikasikan, Foto'],

            // UNSUR 2: BAKAT & MINAT
            // 2.1 Perlombaan Olahraga / Kepemudaan / Seni â€” Mendapat Prestasi
            ['unsur' => 'bakat_minat', 'sub_unsur' => 'lomba_olahraga_seni', 'jenis_item' => 'Mendapat Prestasi', 'tingkat' => 'internasional', 'peranan' => 'juara_1', 'poin' => 80, 'bukti_fisik_required' => 'Sertifikat / Piagam, Foto kegiatan'],
            ['unsur' => 'bakat_minat', 'sub_unsur' => 'lomba_olahraga_seni', 'jenis_item' => 'Mendapat Prestasi', 'tingkat' => 'internasional', 'peranan' => 'juara_2', 'poin' => 60, 'bukti_fisik_required' => 'Sertifikat / Piagam, Foto kegiatan'],
            ['unsur' => 'bakat_minat', 'sub_unsur' => 'lomba_olahraga_seni', 'jenis_item' => 'Sebagai Peserta', 'tingkat' => 'nasional', 'peranan' => 'peserta', 'poin' => 20, 'bukti_fisik_required' => 'Sertifikat / Piagam, Foto kegiatan'],

            // UNSUR 3: SOSIAL & KEMASYARAKATAN
            // 3.2 Jabatan pada Lembaga Kemahasiswaan â€” Tingkat Universitas (per periode kepengurusan)
            ['unsur' => 'sosial', 'sub_unsur' => 'jabatan_kemahasiswaan', 'jenis_item' => 'Pengurus Universitas', 'tingkat' => 'universitas', 'peranan' => 'pengurus_inti', 'poin' => 30, 'bukti_fisik_required' => 'Surat Keputusan'],
            ['unsur' => 'sosial', 'sub_unsur' => 'jabatan_kemahasiswaan', 'jenis_item' => 'Pengurus Universitas', 'tingkat' => 'universitas', 'peranan' => 'anggota', 'poin' => 15, 'bukti_fisik_required' => 'Surat Keputusan'],

            // UNSUR 4: KEGIATAN KHUSUS
            // 4.1 Asisten Dosen (per semester)
            ['unsur' => 'kegiatan_khusus', 'sub_unsur' => 'asisten_dosen', 'jenis_item' => 'Teori', 'tingkat' => null, 'peranan' => 'asisten', 'poin' => 20, 'bukti_fisik_required' => 'Surat Tugas / Surat Keterangan, Foto perkuliahan'],
            ['unsur' => 'kegiatan_khusus', 'sub_unsur' => 'asisten_dosen', 'jenis_item' => 'Praktikum', 'tingkat' => null, 'peranan' => 'asisten', 'poin' => 20, 'bukti_fisik_required' => 'Surat Tugas / Surat Keterangan, Foto praktikum'],

            // 4.2 Orientasi Mahasiswa Baru â€” PKKMB Universitas
            ['unsur' => 'kegiatan_khusus', 'sub_unsur' => 'pkkmb', 'jenis_item' => 'PKKMB Universitas', 'tingkat' => 'universitas', 'peranan' => 'peserta', 'poin' => 5, 'bukti_fisik_required' => 'Sertifikat, Foto kegiatan'],
        ];

        foreach ($rules as $rule) {
            PointRule::updateOrCreate(
                [
                    'unsur' => $rule['unsur'],
                    'sub_unsur' => $rule['sub_unsur'],
                    'jenis_item' => $rule['jenis_item'],
                    'tingkat' => $rule['tingkat'],
                    'peranan' => $rule['peranan'],
                ],
                $rule
            );
        }
    }
}
