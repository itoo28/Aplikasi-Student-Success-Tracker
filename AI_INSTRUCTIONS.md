# Panduan Pengembangan dengan AI Agent (Student Success Tracker)

Proyek **Aplikasi Student Success Tracker** ini dikerjakan oleh dua tim yang memiliki tanggung jawab modul yang berbeda. Untuk mencegah tumpang tindih pekerjaan dan menjaga integritas kode, AI Agent **DIWAJIBKAN** untuk mematuhi pembagian tugas berikut saat diminta melakukan modifikasi, penambahan fitur, atau perbaikan bug.

## Pembagian Tim dan Modul

### 1. Tim 1 (User / teman anda) - Modul Poin SKKM

Tim ini bertanggung jawab **HANYA** pada bagian yang berkaitan dengan Sistem Kredit Kegiatan Mahasiswa (SKKM).

- **Fokus utama:** Manajemen poin SKKM, input kegiatan, validasi poin, perhitungan poin, dan dashboard pencapaian SKKM mahasiswa.
- **Tugas AI Agent saat membantu Tim 1:**
    - Hanya edit/buat file yang berkaitan dengan fitur SKKM (contoh: model `Skkm`, controller `SkkmController`, view yang berada di folder terkait SKKM, migration/tabel `skkms`, dll).
    - **DILARANG KERAS** memodifikasi file, struktur database, atau logika bisnis yang berkaitan dengan Modul Laporan Dosen PA.

### 2. Tim 2 (Saya) - Modul Laporan Dosen PA

Tim ini bertanggung jawab **HANYA** pada bagian yang berkaitan dengan Laporan Dosen Pembimbing Akademik (PA) / Bimbingan Logbook.

- **Fokus utama:** Pencatatan logbook bimbingan, persetujuan bimbingan oleh Dosen PA, laporan riwayat bimbingan, dan dashboard Dosen PA.
- **Tugas AI Agent saat membantu Tim 2:**
    - Hanya edit/buat file yang berkaitan dengan fitur Dosen PA dan Logbook (contoh: model `Logbook`, `Bimbingan`, controller `LogbookController`, view terkait bimbingan/dosen, migration/tabel `logbooks`, dll).
    - **DILARANG KERAS** memodifikasi file, struktur database, atau logika bisnis yang berkaitan dengan Modul Poin SKKM.

---

## Instruksi Penting untuk AI Agent

Setiap kali AI Agent menerima perintah dari pengguna dalam repository ini, **LAKUKAN PENGECEKAN BERIKUT SEBELUM MENULIS ATAU MENGUBAH KODE**:

1.  **Identifikasi Konteks:** Tanyakan pada diri sendiri, "Apakah instruksi pengguna saat ini ditujukan untuk Modul Poin SKKM atau Modul Laporan Dosen PA?". Pastikan untuk hanya bekerja sesuai dengan modul milik pengguna yang sedang berinteraksi.
2.  **Batasi Scope (Ruang Lingkup):** Batasi pencarian, pembuatan, dan modifikasi file hanya pada area yang relevan dengan modul tersebut.
3.  **Jangan Sentuh Modul Lain:** Jika menemukan bug atau kode yang bisa dioptimalkan di modul milik tim lain (saat sedang mengerjakan tugas di suatu modul), **ABAIKAN**. Fokus hanya pada modul yang sedang ditugaskan untuk mencegah konflik _merge_ atau kerusakan pada pekerjaan tim lain.
4.  **Konfirmasi di Area Bersama (Shared Area):**
    Area bersama seperti `routes/web.php`, `App\Models\User.php`, atau layout utama (`resources/views/layouts/*`) sering digunakan oleh kedua tim.
    - Jika harus mengedit area bersama, **HANYA TAMBAHKAN** kode yang diperlukan untuk modul saat ini.
    - **JANGAN PERNAH MENGHAPUS ATAU MENGUBAH** baris kode yang ditulis untuk modul lain.
    - Jika ragu apakah suatu perubahan akan merusak modul lain, berikan peringatan kepada pengguna terlebih dahulu.

## Struktur Referensi File (Contoh Area Kerja)

- **Poin SKKM (Area Tim 1):**
    - Controllers: `*SkkmController.php`, `*KegiatanController.php`
    - Models: `Skkm.php`, `KategoriSkkm.php`
    - Views: `resources/views/skkm/*` atau `resources/views/mahasiswa/skkm/*`
- **Laporan Dosen PA (Area Tim 2):**
    - Controllers: `*LogbookController.php`, `*BimbinganController.php`
    - Models: `Logbook.php`, `Bimbingan.php`
    - Views: `resources/views/logbook/*` atau `resources/views/dosen/*`
