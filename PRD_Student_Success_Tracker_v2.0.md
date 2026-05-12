# Product Requirements Document (PRD)
# Student Success Tracker

| Atribut | Detail |
|---|---|
| **Versi** | 2.0 — Diperbarui berdasarkan Juknis SKKM UHB 2025–2026 (SK Rektor No. UHB/KEP/155/1125) |
| **Institusi** | Universitas Harapan Bangsa (UHB), Purwokerto |
| **Tanggal Pembaruan** | 30 November 2025 |
| **Tujuan** | Platform digital untuk mengelola poin SKKM (akumulatif & per semester) dan laporan bimbingan akademik sebagai syarat kelulusan |

---

## Daftar Isi

1. [Persona Pengguna & Hak Akses](#1-persona-pengguna--hak-akses)
2. [Ketentuan & Target Poin Resmi](#2-ketentuan--target-poin-resmi)
3. [Fitur Utama & Kebutuhan Fungsional](#3-fitur-utama--kebutuhan-fungsional)
4. [Matriks Poin SKKM Lengkap](#4-matriks-poin-skkm-lengkap)
5. [Logika Bisnis & Aturan Sistem](#5-logika-bisnis--aturan-sistem)
6. [Alur Kerja (Workflow) Mahasiswa](#6-alur-kerja-workflow-mahasiswa)
7. [Struktur Database](#7-struktur-database)

---

## 1. Persona Pengguna & Hak Akses

| Persona | Hak Akses |
|---|---|
| **Mahasiswa** | Input data kegiatan, upload bukti fisik, melihat progres poin (per semester & kumulatif), melihat status verifikasi |
| **Dosen PA (Pembimbing Akademik)** | Melihat preview dokumen bukti, verifikasi keaslian, menyetujui/menolak poin (disertai catatan), mengisi laporan bimbingan akademik |
| **Bagian Kemahasiswaan / Admin** | Memantau rekap akhir semua mahasiswa (Semester 1–8), validasi akhir poin, mengirim notifikasi kekurangan poin, melihat daftar mahasiswa yang memenuhi syarat Yudisium |

---

## 2. Ketentuan & Target Poin Resmi

> Sumber: Petunjuk Teknis SKKM UHB 2025–2026, Bab II Pasal B

### 2.1 Batas Minimal Kelulusan

| Jenjang | Total Poin Minimal |
|---|:---:|
| Sarjana (S1) & Sarjana Terapan (D4) | **80 poin** |
| Diploma Tiga (D3) | **60 poin** |

### 2.2 Target Per Blok Semester (Wajib Dipenuhi Bertahap)

| Blok Semester | S1 / D4 | D3 |
|---|:---:|:---:|
| Semester 1–2 | Minimal 20 poin | Minimal 20 poin |
| Semester 3–4 | Minimal 20 poin | Minimal 20 poin |
| Semester 5–6 | Minimal 20 poin | Minimal 20 poin |
| Semester 7–8 | Minimal 20 poin | *(tidak berlaku)* |
| **Total** | **80 poin** | **60 poin** |

### 2.3 Anjuran Penyelesaian SKKM Lebih Awal

| Jenjang | Target Lunas SKKM Paling Lambat |
|---|---|
| S1 / D4 | Akhir **Semester 7** (agar Semester 8 fokus skripsi) |
| D3 | Akhir **Semester 5** (agar Semester 6 fokus tugas akhir) |

---

## 3. Fitur Utama & Kebutuhan Fungsional

### A. Modul Pengajuan SKKM (Mahasiswa)

| Fitur | Detail Kebutuhan |
|---|---|
| **Upload Bukti** | Wajib mengunggah file bukti fisik (Sertifikat, Surat Tugas, SK, Laporan Kegiatan, Foto) dalam format **PDF** atau **JPG/PNG** |
| **Alur Input Bertingkat** | Pilih **Unsur** → Pilih **Jenis Kegiatan** → Pilih **Tingkat** → Pilih **Peranan** → **Poin Muncul Otomatis** |
| **4 Unsur Utama** | (1) Penalaran & Keilmuan, (2) Bakat & Minat, (3) Sosial & Kemasyarakatan, (4) Kegiatan Khusus |
| **6 Tingkat Kegiatan** | Internasional, Nasional, Regional/Provinsi, Universitas, Fakultas/Unit, Daerah/Lokal |
| **Auto-Calculation** | Sistem menghitung poin otomatis dari referensi tabel `point_rules` (bukan estimasi) |
| **Aturan Satu Peranan** | Mahasiswa hanya dapat mengajukan **satu peranan tertinggi** dalam satu kegiatan yang sama |

---

### B. Modul Verifikasi (Dosen PA)

| Fitur | Detail Kebutuhan |
|---|---|
| **Preview Dokumen** | Dosen melihat preview file bukti fisik yang diunggah mahasiswa langsung dari dashboard |
| **Checklist Bukti Fisik** | Sistem menampilkan checklist bukti yang dipersyaratkan sesuai jenis kegiatan (lihat §4) |
| **Manajemen Status** | Status awal: `Pending`. Dosen mengubah ke `Disetujui` (poin masuk saldo) atau `Ditolak` (wajib isi catatan alasan) |
| **Catatan Dosen** | Field wajib diisi saat menolak pengajuan; mahasiswa dapat melihat catatan dan melakukan perbaikan |
| **Batas Perbaikan** | Pengajuan yang tidak diperbaiki sampai batas waktu dinyatakan gugur pada periode tersebut |

---

### C. Modul Notifikasi & Early Warning

| Kondisi | Notifikasi |
|---|---|
| Poin blok semester belum terpenuhi | **Peringatan Kuning**: "Poin pada periode Semester X–Y belum mencapai 20 poin" |
| Mahasiswa S1 masuk Semester 7 dengan poin < 60 | **Peringatan Oranye**: "Segera lengkapi SKKM — tersisa 1 blok semester untuk memenuhi syarat" |
| Mahasiswa D3 masuk Semester 5 dengan poin < 40 | **Peringatan Oranye**: "Segera lengkapi SKKM — tersisa 1 blok semester untuk memenuhi syarat" |
| Mahasiswa Semester 8 (S1) atau Semester 6 (D3) dengan total poin di bawah batas kelulusan | **Peringatan Merah**: "Poin Belum Mencukupi untuk Yudisium — Segera hubungi Dosen PA" |

---

### D. Modul Rekapitulasi & Pelaporan (Gaya SIAKAD)

**Rekap Semester (Detail — Ala IPS)**

Menampilkan daftar kegiatan yang diakui dalam satu semester tertentu:

| Kolom | Keterangan |
|---|---|
| Nama Kegiatan | Nama kegiatan yang diikuti |
| Unsur | Penalaran / Bakat & Minat / Sosial / Kegiatan Khusus |
| Jenis Kegiatan | Sub-unsur spesifik (mis. Lomba KTI, Seminar) |
| Tingkat | Internasional / Nasional / dll |
| Peranan | Juara 1 / Peserta / Pengurus Inti / dll |
| Poin Diperoleh | Angka poin yang disetujui Dosen PA |
| Status | Disetujui / Ditolak / Pending |

**Transkrip SKKM Kumulatif (Ala IPK — untuk Lampiran SKPI/Ijazah)**

Menampilkan akumulasi poin per unsur dari Semester 1 sampai 8 tanpa rincian kegiatan:

| Unsur | Smt 1–2 | Smt 3–4 | Smt 5–6 | Smt 7–8 | Total |
|---|:---:|:---:|:---:|:---:|:---:|
| Penalaran & Keilmuan | — | — | — | — | — |
| Bakat & Minat | — | — | — | — | — |
| Sosial & Kemasyarakatan | — | — | — | — | — |
| Kegiatan Khusus | — | — | — | — | — |
| **Grand Total** | | | | | |

**Dashboard Kemahasiswaan (Admin)**

Menampilkan rekap seluruh mahasiswa: nama, NIM, prodi, jenjang, total poin, status Yudisium (Memenuhi / Belum Memenuhi).

---

## 4. Matriks Poin SKKM Lengkap

> Sumber data: Tabel 1 — Satuan Kredit Kegiatan Mahasiswa, Juknis SKKM UHB 2025–2026

---

### UNSUR 1: PENALARAN & KEILMUAN

#### 1.1 Penulisan Karya Ilmiah — Jurnal / Majalah Ilmiah (per artikel)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 50 | Artikel yang dipublikasikan |
| Nasional | 35 | Artikel yang dipublikasikan |
| Regional | 20 | Artikel yang dipublikasikan |
| Universitas | 15 | Artikel yang dipublikasikan |
| Fakultas | 10 | Artikel yang dipublikasikan |

#### 1.2 Penulisan Karya Ilmiah — Koran / Majalah Populer (per artikel)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 40 | Artikel yang dipublikasikan |
| Nasional | 30 | Artikel yang dipublikasikan |
| Regional | 20 | Artikel yang dipublikasikan |
| Universitas | 15 | Artikel yang dipublikasikan |
| Fakultas | 10 | Artikel yang dipublikasikan |

#### 1.3 Penulisan Artikel — Media Online (Non-Medsos)

| Jenis | Poin | Bukti Fisik |
|---|:---:|---|
| Artikel Online | 10 | Artikel yang dipublikasikan (URL aktif) |

---

#### 1.4 Lomba Karya Ilmiah / KTI / Pemikiran Kritis / Debat — Mendapat Prestasi

| Tingkat | Juara 1 | Juara 2 | Juara 3 | Harapan 1,2,3 | Bukti Fisik |
|---|:---:|:---:|:---:|:---:|---|
| Internasional | 80 | 45 | 40 | 30 | Sertifikat, Karya Tulis, Foto kegiatan |
| Nasional | 40 | 35 | 25 | 20 | Sertifikat, Karya Tulis, Foto kegiatan |
| Regional | 30 | 25 | 20 | 15 | Sertifikat, Karya Tulis, Foto kegiatan |
| Universitas | 25 | 20 | 15 | 10 | Sertifikat, Karya Tulis, Foto kegiatan |
| Fakultas | 20 | 15 | 10 | 5 | Sertifikat, Karya Tulis, Foto kegiatan |

#### 1.5 Lomba Karya Ilmiah / KTI / Pemikiran Kritis / Debat — Sebagai Peserta

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 30 | Sertifikat / Tulisan dipublikasikan, Foto |
| Nasional | 25 | Sertifikat / Tulisan dipublikasikan, Foto |
| Regional | 20 | Sertifikat / Tulisan dipublikasikan, Foto |
| Universitas | 10 | Sertifikat / Tulisan dipublikasikan, Foto |
| Fakultas | 5 | Sertifikat / Tulisan dipublikasikan, Foto |

---

#### 1.6 Seminar Ilmiah / Oral Presentasi — Mendapat Prestasi (Best Paper / Best Presenter)

| Tingkat | Juara 1 | Juara 2 | Juara 3 | Harapan 1,2,3 | Bukti Fisik |
|---|:---:|:---:|:---:|:---:|---|
| Internasional | 80 | 45 | 40 | 30 | Sertifikat, Karya Tulis, Foto kegiatan |
| Nasional | 40 | 35 | 25 | 20 | Sertifikat, Karya Tulis, Foto kegiatan |
| Regional | 30 | 25 | 20 | 15 | Sertifikat, Karya Tulis, Foto kegiatan |
| Universitas | 25 | 20 | 15 | 10 | Sertifikat, Karya Tulis, Foto kegiatan |
| Fakultas | 20 | 15 | 10 | 5 | Sertifikat, Karya Tulis, Foto kegiatan |

#### 1.7 Seminar Ilmiah / Oral Presentasi — Sebagai Peserta Pemakalah

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 40 | Sertifikat pemakalah, Makalah, Foto |
| Nasional | 30 | Sertifikat pemakalah, Makalah, Foto |
| Provinsi | 25 | Sertifikat pemakalah, Makalah, Foto |
| Universitas | 15 | Sertifikat pemakalah, Makalah, Foto |
| Fakultas | 10 | Sertifikat pemakalah, Makalah, Foto |

#### 1.8 Seminar Ilmiah — Sebagai Moderator / MC

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 50 | Sertifikat Moderator, Foto kegiatan |
| Nasional | 20 | Sertifikat Moderator, Foto kegiatan |
| Provinsi | 15 | Sertifikat Moderator, Foto kegiatan |
| Universitas | 10 | Sertifikat Moderator, Foto kegiatan |
| Fakultas | 5 | Sertifikat Moderator, Foto kegiatan |

---

#### 1.9 Lomba Esai / Lomba Poster pada Pertemuan Ilmiah — Mendapat Prestasi

| Tingkat | Juara 1 | Juara 2 | Juara 3 | Harapan 1,2,3 | Bukti Fisik |
|---|:---:|:---:|:---:|:---:|---|
| Internasional | 80 | 45 | 40 | 30 | Sertifikat, Poster, Foto kegiatan |
| Nasional | 40 | 35 | 25 | 20 | Sertifikat, Poster, Foto kegiatan |
| Regional | 30 | 25 | 20 | 15 | Sertifikat, Poster, Foto kegiatan |
| Universitas | 25 | 20 | 15 | 10 | Sertifikat, Poster, Foto kegiatan |
| Fakultas | 20 | 15 | 10 | 5 | Sertifikat, Poster, Foto kegiatan |

#### 1.10 Lomba Esai / Lomba Poster pada Pertemuan Ilmiah — Sebagai Peserta

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 40 | Sertifikat, Poster, Foto kegiatan |
| Nasional | 30 | Sertifikat, Poster, Foto kegiatan |
| Provinsi | 25 | Sertifikat, Poster, Foto kegiatan |
| Universitas | 15 | Sertifikat, Poster, Foto kegiatan |
| Fakultas | 10 | Sertifikat, Poster, Foto kegiatan |

---

#### 1.11 Membuat Rancangan & Karya Teknologi / Karya Seni / Pertunjukan Seni

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 80 | Hasil Rancangan Karya / Laporan / Dokumentasi |
| Nasional | 40 | Hasil Rancangan Karya / Laporan / Dokumentasi |
| Provinsi | 35 | Hasil Rancangan Karya / Laporan / Dokumentasi |
| Universitas | 25 | Hasil Rancangan Karya / Laporan / Dokumentasi |
| Fakultas | 15 | Hasil Rancangan Karya / Laporan / Dokumentasi |

#### 1.12 Magang **Sesuai** Bidang Keilmuan (di luar kurikulum)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 80 | Sertifikat / Surat keterangan magang, Foto |
| Nasional | 40 | Sertifikat / Surat keterangan magang, Foto |
| Provinsi | 25 | Sertifikat / Surat keterangan magang, Foto |
| Universitas | 20 | Sertifikat / Surat keterangan magang, Foto |
| Fakultas | 15 | Sertifikat / Surat keterangan magang, Foto |

#### 1.13 Pelatihan / Workshop **Sesuai** Bidang Keilmuan (di luar kurikulum)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 60 | Sertifikat / Surat keterangan pelatihan, Foto |
| Nasional | 40 | Sertifikat / Surat keterangan pelatihan, Foto |
| Provinsi | 25 | Sertifikat / Surat keterangan pelatihan, Foto |
| Universitas | 20 | Sertifikat / Surat keterangan pelatihan, Foto |
| Fakultas | 15 | Sertifikat / Surat keterangan pelatihan, Foto |

#### 1.14 Magang **Tidak Sesuai** Bidang Keilmuan (di luar kurikulum)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 50 | Sertifikat / Surat keterangan magang, Foto |
| Nasional | 20 | Sertifikat / Surat keterangan magang, Foto |
| Provinsi | 15 | Sertifikat / Surat keterangan magang, Foto |
| Universitas | 10 | Sertifikat / Surat keterangan magang, Foto |
| Fakultas | 5 | Sertifikat / Surat keterangan magang, Foto |

#### 1.15 Pelatihan / Workshop **Tidak Sesuai** Bidang Keilmuan (di luar kurikulum)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 40 | Sertifikat / Surat keterangan pelatihan, Foto |
| Nasional | 20 | Sertifikat / Surat keterangan pelatihan, Foto |
| Provinsi | 15 | Sertifikat / Surat keterangan pelatihan, Foto |
| Universitas | 10 | Sertifikat / Surat keterangan pelatihan, Foto |
| Fakultas | 5 | Sertifikat / Surat keterangan pelatihan, Foto |

#### 1.16 Kuliah Kerja Nyata (KKN)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 80 | Sertifikat / Surat keterangan KKN, Foto |
| Nasional | 40 | Sertifikat / Surat keterangan KKN, Foto |
| Provinsi | 25 | Sertifikat / Surat keterangan KKN, Foto |
| Universitas | 20 | Sertifikat / Surat keterangan KKN, Foto |
| Fakultas | 15 | Sertifikat / Surat keterangan KKN, Foto |

#### 1.17 Tenaga Lapangan / Interviewer pada Penelitian Dosen

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 50 | Surat Tugas / Surat Keterangan dari Dosen, Foto |
| Nasional | 30 | Surat Tugas / Surat Keterangan dari Dosen, Foto |
| Provinsi | 25 | Surat Tugas / Surat Keterangan dari Dosen, Foto |
| Universitas | 20 | Surat Tugas / Surat Keterangan dari Dosen, Foto |
| Fakultas | 15 | Surat Tugas / Surat Keterangan dari Dosen, Foto |

#### 1.18 Hak Kekayaan Intelektual (HKI) — Hak Cipta

| Peranan | Poin | Bukti Fisik |
|---|:---:|---|
| Pencipta Utama | 20 | Sertifikat HKI dari DJKI Kemenkumham RI |
| Anggota | 10 | Sertifikat HKI dari DJKI Kemenkumham RI |

---

### UNSUR 2: BAKAT & MINAT

#### 2.1 Perlombaan Olahraga / Kepemudaan / Seni — Mendapat Prestasi

| Tingkat | Juara 1 | Juara 2 | Juara 3 | Harapan 1,2,3 | Bukti Fisik |
|---|:---:|:---:|:---:|:---:|---|
| Internasional | 80 | 60 | 50 | 30 | Sertifikat / Piagam, Foto kegiatan |
| Nasional | 40 | 35 | 25 | 20 | Sertifikat / Piagam, Foto kegiatan |
| Regional | 30 | 25 | 20 | 15 | Sertifikat / Piagam, Foto kegiatan |
| Lokal / Universitas | 25 | 20 | 15 | 10 | Sertifikat / Piagam, Foto kegiatan |
| Fakultas / Unit | 20 | 15 | 10 | 5 | Sertifikat / Piagam, Foto kegiatan |

#### 2.2 Perlombaan Olahraga / Kepemudaan / Seni — Sebagai Peserta

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 30 | Sertifikat / Piagam, Foto kegiatan |
| Nasional | 20 | Sertifikat / Piagam, Foto kegiatan |
| Provinsi | 15 | Sertifikat / Piagam, Foto kegiatan |
| Lokal | 10 | Sertifikat / Piagam, Foto kegiatan |
| Universitas | 5 | Sertifikat / Piagam, Foto kegiatan |
| Fakultas / Unit | 5 | Sertifikat / Piagam, Foto kegiatan |

#### 2.3 Mewakili PT / Fakultas dalam Panitia Antar Lembaga

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 50 | Surat Tugas, Foto kegiatan |
| Nasional | 40 | Surat Tugas, Foto kegiatan |
| Provinsi | 20 | Surat Tugas, Foto kegiatan |
| Lokal | 15 | Surat Tugas, Foto kegiatan |
| Universitas | 10 | Surat Tugas, Foto kegiatan |
| Fakultas / Unit | 5 | Surat Tugas, Foto kegiatan |

#### 2.4 Pelatihan Bidang Minat & Bakat

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 40 | Sertifikat / Daftar Hadir, Foto kegiatan |
| Nasional | 30 | Sertifikat / Daftar Hadir, Foto kegiatan |
| Provinsi | 25 | Sertifikat / Daftar Hadir, Foto kegiatan |
| Lokal | 15 | Sertifikat / Daftar Hadir, Foto kegiatan |
| Universitas | 10 | Sertifikat / Daftar Hadir, Foto kegiatan |
| Fakultas / Unit | 5 | Sertifikat / Daftar Hadir, Foto kegiatan |

#### 2.5 Pertemuan Organisasi Mahasiswa / Ormawa (per kegiatan)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 30 | Surat Tugas / Daftar Hadir, Foto kegiatan |
| Nasional | 25 | Surat Tugas / Daftar Hadir, Foto kegiatan |
| Provinsi | 20 | Surat Tugas / Daftar Hadir, Foto kegiatan |
| Lokal | 15 | Surat Tugas / Daftar Hadir, Foto kegiatan |
| Universitas | 10 | Surat Tugas / Daftar Hadir, Foto kegiatan |
| Fakultas / Unit | 5 | Surat Tugas / Daftar Hadir, Foto kegiatan |

---

### UNSUR 3: SOSIAL & KEMASYARAKATAN

#### 3.1 Kegiatan Sosial Kemasyarakatan — Sebagai Peserta (per kegiatan)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 40 | Surat Tugas dan Sertifikat |
| Nasional | 25 | Surat Tugas dan Sertifikat |
| Provinsi | 20 | Surat Tugas dan Sertifikat |
| Daerah / Lokal | 15 | Surat Tugas dan Sertifikat |

#### 3.2 Jabatan pada Lembaga Kemahasiswaan — Tingkat Universitas (per periode kepengurusan)

| Jabatan | Poin | Bukti Fisik |
|---|:---:|---|
| Pengurus Inti (Ketua / Wakil Ketua / Sekretaris / Bendahara) | 30 | Surat Keputusan |
| Ketua Bidang / Departemen | 25 | Surat Keputusan |
| Anggota Pengurus | 15 | Surat Keputusan |

#### 3.3 Jabatan pada Lembaga Kemahasiswaan — Tingkat Fakultas (per periode kepengurusan)

| Jabatan | Poin | Bukti Fisik |
|---|:---:|---|
| Pengurus Inti (Ketua / Wakil Ketua / Sekretaris / Bendahara) | 20 | Surat Keputusan |
| Ketua Bidang / Departemen | 15 | Surat Keputusan |
| Anggota Pengurus | 10 | Surat Keputusan |

#### 3.4 Jabatan Organisasi di Luar Universitas — Tingkat Internasional

| Jabatan | Poin | Bukti Fisik |
|---|:---:|---|
| Pengurus Inti (Ketua / Wakil Ketua / Sekretaris / Bendahara) | 30 | Surat Keputusan |
| Koordinator per Divisi / Departemen | 25 | Surat Keputusan |
| Anggota | 20 | Surat Keputusan |

#### 3.5 Jabatan Organisasi di Luar Universitas — Tingkat Nasional

| Jabatan | Poin | Bukti Fisik |
|---|:---:|---|
| Pengurus Inti (Ketua / Wakil Ketua / Sekretaris / Bendahara) | 25 | Surat Keputusan |
| Koordinator per Divisi / Departemen | 20 | Surat Keputusan |
| Anggota | 15 | Surat Keputusan |

#### 3.6 Jabatan Organisasi di Luar Universitas — Tingkat Regional / Provinsi

| Jabatan | Poin | Bukti Fisik |
|---|:---:|---|
| Pengurus Inti (Ketua / Wakil Ketua / Sekretaris / Bendahara) | 20 | Surat Keputusan |
| Koordinator per Divisi / Departemen | 15 | Surat Keputusan |
| Anggota | 10 | Surat Keputusan |

#### 3.7 Jabatan Organisasi di Luar Universitas — Tingkat Kota / Lokal / Universitas Lain

| Jabatan | Poin | Bukti Fisik |
|---|:---:|---|
| Pengurus Inti (Ketua / Wakil Ketua / Sekretaris / Bendahara) | 15 | Surat Keputusan |
| Koordinator per Divisi / Departemen | 10 | Surat Keputusan |
| Anggota | 5 | Surat Keputusan |

#### 3.8 Tutor / Narasumber Pelatihan kepada Masyarakat (per kegiatan, sesuai bidang ilmu)

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 60 | Sertifikat dan/atau Makalah, Foto kegiatan |
| Nasional | 30 | Sertifikat dan/atau Makalah, Foto kegiatan |
| Provinsi | 25 | Sertifikat dan/atau Makalah, Foto kegiatan |
| Daerah / Lokal | 20 | Sertifikat dan/atau Makalah, Foto kegiatan |
| Universitas | 15 | Sertifikat dan/atau Makalah, Foto kegiatan |

#### 3.9 Tenaga Lapangan pada Pengabdian Masyarakat Dosen / Pihak Lain

| Tingkat | Poin | Bukti Fisik |
|---|:---:|---|
| Internasional | 60 | Surat Tugas / Surat Keterangan Dosen, Foto |
| Nasional | 30 | Surat Tugas / Surat Keterangan Dosen, Foto |
| Provinsi | 25 | Surat Tugas / Surat Keterangan Dosen, Foto |
| Daerah / Lokal | 20 | Surat Tugas / Surat Keterangan Dosen, Foto |
| Universitas | 15 | Surat Tugas / Surat Keterangan Dosen, Foto |

---

### UNSUR 4: KEGIATAN KHUSUS

#### 4.1 Asisten Dosen (per semester)

| Jenis | Poin | Bukti Fisik |
|---|:---:|---|
| Teori | 20 | Surat Tugas / Surat Keterangan, Foto perkuliahan |
| Praktikum | 20 | Surat Tugas / Surat Keterangan, Foto praktikum |

#### 4.2 Orientasi Mahasiswa Baru — PKKMB Universitas

| Jenis | Poin | Bukti Fisik |
|---|:---:|---|
| PKKMB Universitas | 5 | Sertifikat, Foto kegiatan |

#### 4.3 Branding UHB — Kunjungan & Sosialisasi ke SMA/SMK Sederajat

| Jenis | Poin | Bukti Fisik |
|---|:---:|---|
| Sosialisasi / Kunjungan | 5 | Laporan Kegiatan, Foto kegiatan |
| Mendapat siswa yang mendaftar hingga registrasi ke UHB | 20 | Laporan Kegiatan, Foto kegiatan |

---

## 5. Logika Bisnis & Aturan Sistem

### 5.1 Aturan Pengajuan

**Satu Peranan Tertinggi per Kegiatan**
Mahasiswa hanya dapat mengajukan satu jenis peranan tertinggi pada satu kegiatan yang sama. Jika mahasiswa adalah Juara 1 sekaligus Pemakalah dalam satu acara, hanya peranan dengan poin tertinggi yang diakui.

**Validitas Poin**
Poin hanya ditambahkan ke saldo mahasiswa apabila status verifikasi berubah dari `Pending` menjadi `Disetujui` oleh Dosen PA.

**Kegiatan di Luar Tabel**
Kegiatan yang tidak tercantum dalam tabel SKKM dapat dipertimbangkan melalui mekanisme evaluasi khusus oleh unit Kemahasiswaan, Alumni, dan Pusat Karir.

### 5.2 Aturan Verifikasi Berjenjang

| Tahap | Aktor | Tugas |
|---|---|---|
| 1 — Verifikasi Awal | Dosen PA | Periksa kesesuaian unsur, kelengkapan & keabsahan bukti, rekomendasikan Setuju/Perbaikan |
| 2 — Validasi Lanjutan | Program Studi | Validasi kesesuaian dengan ketentuan akademik & kebijakan institusi |
| 3 — Validasi Akhir | Unit Kemahasiswaan | Tetapkan besaran poin final, catat ke sistem administrasi SKKM |

### 5.3 Checklist Bukti Fisik yang Valid (Dosen PA)

Sistem wajib menampilkan checklist berikut saat Dosen PA melakukan verifikasi:

- [ ] Sertifikat atau Piagam resmi dari penyelenggara
- [ ] Surat Tugas atau Surat Keputusan (SK) dari institusi berwenang
- [ ] Artikel / Karya yang dipublikasikan (dengan URL atau bukti cetak)
- [ ] Makalah, Poster, atau Hasil Karya
- [ ] Laporan Kegiatan atau Logbook
- [ ] Dokumentasi Foto kegiatan
- [ ] Sertifikat HKI dari DJKI Kemenkumham RI (khusus kekayaan intelektual)

Bukti fisik yang tidak memenuhi ketentuan atau tidak dapat diverifikasi dinyatakan **tidak sah** dan tidak memperoleh kredit poin.

### 5.4 Logika Yudisium Otomatis

```
JIKA jenjang = 'S1' ATAU 'D4':
    batas_kelulusan = 80
    semester_peringatan_dini = 7
JIKA jenjang = 'D3':
    batas_kelulusan = 60
    semester_peringatan_dini = 5

JIKA semester_aktif >= semester_peringatan_dini DAN total_poin < batas_kelulusan:
    → Tampilkan Peringatan Oranye

JIKA semester_aktif = semester_akhir DAN total_poin < batas_kelulusan:
    → Tampilkan Peringatan Merah: "Poin Belum Mencukupi untuk Yudisium"

JIKA total_poin >= batas_kelulusan:
    → Status Yudisium = MEMENUHI SYARAT
```

---

## 6. Alur Kerja (Workflow) Mahasiswa

Alur input wajib mengikuti urutan bertingkat berikut. Setiap pilihan mempersempit opsi berikutnya secara dinamis.

```
[Langkah 1] Pilih UNSUR
    ├── Penalaran & Keilmuan
    ├── Bakat & Minat
    ├── Sosial & Kemasyarakatan
    └── Kegiatan Khusus
            ↓
[Langkah 2] Pilih JENIS KEGIATAN
    (Daftar muncul sesuai Unsur yang dipilih)
    Contoh: Lomba KTI, Seminar, Magang, HKI, Ormawa, dst.
            ↓
[Langkah 3] Pilih TINGKAT KEGIATAN
    (Hanya tingkat yang relevan dengan jenis kegiatan tampil)
    Contoh: Internasional / Nasional / Regional / Universitas / Fakultas
            ↓
[Langkah 4] Pilih PERANAN
    (Hanya peranan yang berlaku untuk jenis kegiatan tampil)
    Contoh: Juara 1 / Juara 2 / Peserta / Pengurus Inti / Anggota / dst.
            ↓
[Langkah 5] POIN MUNCUL OTOMATIS
    Sistem query ke tabel `point_rules` berdasarkan
    kombinasi (jenis_kegiatan_id, tingkat, peranan)
    → Tampilkan: "Estimasi Poin: XX poin"
            ↓
[Langkah 6] INPUT DATA KEGIATAN
    - Nama Kegiatan (teks bebas)
    - Penyelenggara
    - Tanggal Pelaksanaan
    - Semester Input
            ↓
[Langkah 7] UPLOAD BUKTI FISIK
    - Format: PDF / JPG / PNG (maks. 5 MB)
    - Wajib minimal 1 file
    - Sistem tampilkan checklist bukti yang dipersyaratkan
            ↓
[Langkah 8] SUBMIT → Status: PENDING
    Notifikasi otomatis ke Dosen PA
```

---

## 7. Struktur Database

### 7.1 Tabel: `skkm_submissions` (Pengajuan SKKM)

| Field | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK) | Primary key, auto-increment |
| `mahasiswa_id` | INT (FK) | Relasi ke tabel `users` (mahasiswa) |
| `point_rule_id` | INT (FK) | Relasi ke tabel `point_rules` |
| `nama_kegiatan` | VARCHAR(255) | Nama kegiatan yang diikuti |
| `penyelenggara` | VARCHAR(255) | Nama lembaga penyelenggara |
| `tanggal_kegiatan` | DATE | Tanggal pelaksanaan kegiatan |
| `file_bukti` | VARCHAR(500) | Path/URL file bukti yang diunggah |
| `semester_input` | TINYINT | Semester saat pengajuan (1–8) |
| `poin_otomatis` | INT | Poin dari referensi `point_rules` |
| `status_verifikasi` | ENUM | `pending` / `disetujui` / `ditolak` |
| `catatan_dosen` | TEXT | Catatan atau alasan penolakan dari Dosen PA |
| `verified_by` | INT (FK) | ID Dosen PA yang melakukan verifikasi |
| `verified_at` | TIMESTAMP | Waktu verifikasi |
| `created_at` | TIMESTAMP | Waktu pengajuan |

---

### 7.2 Tabel: `point_rules` (Referensi Poin — Master Data)

Tabel ini adalah jantung sistem auto-calculation. Diisi satu kali oleh Admin berdasarkan Juknis SKKM resmi.

| Field | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK) | Primary key |
| `unsur` | ENUM | `penalaran` / `bakat_minat` / `sosial` / `kegiatan_khusus` |
| `sub_unsur` | VARCHAR(100) | Nama sub-unsur (mis. `lomba_kti`, `seminar_prestasi`) |
| `jenis_item` | VARCHAR(100) | Deskripsi lebih spesifik (mis. `Mendapat Prestasi`, `Sebagai Peserta`) |
| `tingkat` | ENUM | `internasional` / `nasional` / `regional` / `universitas` / `fakultas` / `lokal` |
| `peranan` | VARCHAR(50) | `juara_1` / `juara_2` / `juara_3` / `harapan` / `peserta` / `pengurus_inti` / `koordinator` / `anggota` / `pencipta_utama` / dll |
| `poin` | INT | Nilai poin berdasarkan Juknis SKKM UHB 2025–2026 |
| `bukti_fisik_required` | TEXT | Daftar bukti fisik yang wajib dilampirkan |
| `keterangan` | TEXT | Deskripsi kegiatan dari Juknis |
| `is_active` | BOOLEAN | Status aktif/nonaktif rule |

**Contoh Baris Data `point_rules`:**

| id | unsur | sub_unsur | jenis_item | tingkat | peranan | poin |
|---|---|---|---|---|---|:---:|
| 1 | penalaran | lomba_kti | Mendapat Prestasi | internasional | juara_1 | 80 |
| 2 | penalaran | lomba_kti | Mendapat Prestasi | internasional | juara_2 | 45 |
| 3 | penalaran | lomba_kti | Mendapat Prestasi | nasional | juara_1 | 40 |
| 4 | penalaran | lomba_kti | Sebagai Peserta | internasional | peserta | 30 |
| 5 | penalaran | jurnal_ilmiah | Penulis | internasional | penulis | 50 |
| 6 | bakat_minat | lomba_olahraga_seni | Mendapat Prestasi | internasional | juara_1 | 80 |
| 7 | bakat_minat | lomba_olahraga_seni | Mendapat Prestasi | internasional | juara_2 | 60 |
| 8 | sosial | jabatan_kemahasiswaan | Pengurus Universitas | universitas | pengurus_inti | 30 |
| 9 | kegiatan_khusus | asisten_dosen | Teori | — | asisten | 20 |
| 10 | kegiatan_khusus | pkkmb | PKKMB Universitas | universitas | peserta | 5 |

---

### 7.3 Tabel: `bimbingan_akademik` (Laporan Bimbingan)

| Field | Tipe | Keterangan |
|---|---|---|
| `id` | INT (PK) | Primary key, auto-increment |
| `mahasiswa_id` | INT (FK) | Relasi ke tabel `users` (mahasiswa) |
| `dosen_id` | INT (FK) | Relasi ke tabel `users` (dosen PA) |
| `tanggal` | DATE | Tanggal pelaksanaan bimbingan |
| `topik` | VARCHAR(255) | Topik yang dibahas |
| `hasil_bimbingan` | TEXT | Catatan hasil dan tindak lanjut bimbingan |
| `created_at` | TIMESTAMP | Waktu pencatatan |

---

### 7.4 Tabel: `skkm_progress` (Ringkasan Progres per Mahasiswa)

Tabel materialized / cached untuk performa dashboard.

| Field | Tipe | Keterangan |
|---|---|---|
| `mahasiswa_id` | INT (PK, FK) | Relasi ke tabel `users` |
| `jenjang` | ENUM | `S1` / `D4` / `D3` |
| `semester_aktif` | TINYINT | Semester aktif saat ini |
| `poin_smt_1_2` | INT | Total poin disetujui Semester 1–2 |
| `poin_smt_3_4` | INT | Total poin disetujui Semester 3–4 |
| `poin_smt_5_6` | INT | Total poin disetujui Semester 5–6 |
| `poin_smt_7_8` | INT | Total poin disetujui Semester 7–8 (S1/D4) |
| `total_poin` | INT | Grand total poin terverifikasi |
| `status_yudisium` | ENUM | `memenuhi` / `belum_memenuhi` / `dalam_proses` |
| `updated_at` | TIMESTAMP | Waktu pembaruan terakhir |

---

*Dokumen ini merupakan versi resmi yang mengacu pada Petunjuk Teknis SKKM UHB 2025–2026 (SK Rektor No. UHB/KEP/155/1125, ditetapkan 30 November 2025). Setiap pembaruan Juknis oleh institusi wajib diikuti dengan pembaruan tabel `point_rules` dan dokumen PRD ini.*
