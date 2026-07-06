<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catatan Bimbingan Akademik - {{ $bimbingan->mahasiswa->name }}</title>
    <!-- Google Fonts Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- Tailwind CSS (via CDN for print rendering compatibility) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    }
                }
            }
        }
    </script>
    <style>
        @media print {
            body {
                background-color: white;
                color: black;
                margin: 0;
                padding: 0;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
            }
            .no-print {
                display: none !important;
            }
            .page-container {
                width: 210mm;
                height: 297mm;
                box-shadow: none;
                margin: 0;
                padding: 0; /* Full bleed layout */
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                box-sizing: border-box;
            }
        }
        @media screen {
            body {
                background-color: #f1f5f9;
            }
            .page-container {
                width: 210mm;
                height: 297mm;
                margin: 30px auto;
                background-color: white;
                box-shadow: 0 10px 25px rgba(0,0,0,0.15);
                padding: 0; /* Full bleed layout */
                position: relative;
                display: flex;
                flex-direction: column;
                justify-content: space-between;
                border-radius: 8px;
                box-sizing: border-box;
                overflow: hidden;
            }
        }
    </style>
</head>
<body class="font-sans text-slate-800 antialiased text-sm">

    <!-- Print Action Bar (Floating for screen only) -->
    <div class="no-print fixed top-6 right-6 z-50 flex items-center gap-3">
        <button onclick="window.print()" class="inline-flex items-center gap-2 rounded-xl bg-indigo-600 px-5 py-2.5 text-sm font-bold text-white shadow-lg hover:bg-indigo-700 transition-all hover:scale-105">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 00-2-2H5m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Dokumen
        </button>
        <button onclick="window.close()" class="inline-flex items-center gap-2 rounded-xl bg-white border border-slate-200 px-5 py-2.5 text-sm font-bold text-slate-700 shadow-sm hover:bg-slate-50 transition-all">
            Tutup Halaman
        </button>
    </div>

    <!-- Main Page Container -->
    <div class="page-container">
        
        <!-- Background template layer (z-index 0) -->
        <img src="{{ asset('brand/template-bg.png') }}" class="absolute inset-0 w-full h-full object-cover z-0" alt="Template UHB Asli">

        <!-- CONTENT AREA (z-index 10, padded to lay inside template space) -->
        <div class="relative z-10 flex-1 pt-[35mm] pb-[25mm] px-[15mm] flex flex-col justify-between h-full">
            <div>
                <!-- TITLE -->
                <div class="text-center mb-6">
                    <h2 class="text-base font-bold uppercase tracking-wider text-slate-900 border-b border-slate-900 inline-block pb-0.5">Berita Acara & Catatan Bimbingan Akademik</h2>
                    <p class="text-[10px] text-slate-500 mt-1">Nomor: BA/BA-{{ str_pad($bimbingan->id, 4, '0', STR_PAD_LEFT) }}/{{ $bimbingan->tanggal->format('m') }}/{{ $bimbingan->tanggal->format('Y') }}</p>
                </div>
                
                <!-- METADATA TABLE -->
                <table class="w-full text-[11px] mb-6 border-collapse">
                    <tbody>
                        <tr class="align-top">
                            <td class="w-[18%] py-1 text-slate-500 font-medium">Nama Mahasiswa</td>
                            <td class="w-[2%] py-1">:</td>
                            <td class="w-[30%] py-1 font-semibold text-slate-900">{{ $bimbingan->mahasiswa->name }}</td>
                            
                            <td class="w-[18%] py-1 text-slate-500 font-medium">Dosen Pembimbing PA</td>
                            <td class="w-[2%] py-1">:</td>
                            <td class="w-[30%] py-1 font-semibold text-slate-900">{{ $bimbingan->dosen->name }}</td>
                        </tr>
                        <tr class="align-top">
                            <td class="py-1 text-slate-500 font-medium">NIM</td>
                            <td class="py-1">:</td>
                            <td class="py-1 font-medium text-slate-800">{{ $bimbingan->mahasiswa->identifier }}</td>
                            
                            <td class="py-1 text-slate-500 font-medium">NIDN / Kode Dosen</td>
                            <td class="py-1">:</td>
                            <td class="py-1 text-slate-800">{{ $bimbingan->dosen->identifier ?? '-' }}</td>
                        </tr>
                        <tr class="align-top">
                            <td class="py-1 text-slate-500 font-medium">Program Studi</td>
                            <td class="py-1">:</td>
                            <td class="py-1 text-slate-800">{{ optional($bimbingan->mahasiswa->programStudi)->nama ?? '-' }}</td>
                            
                            <td class="py-1 text-slate-500 font-medium">Tanggal Pelaksanaan</td>
                            <td class="py-1">:</td>
                            <td class="py-1 text-slate-800">{{ $bimbingan->tanggal->format('d M Y') }}</td>
                        </tr>
                        <tr class="align-top">
                            <td class="py-1 text-slate-500 font-medium">Semester / Jenjang</td>
                            <td class="py-1">:</td>
                            <td class="py-1 text-slate-800">Semester {{ $bimbingan->semester }} / {{ $bimbingan->mahasiswa->programStudi?->jenjang ?? '-' }}</td>
                            
                            <td class="py-1 text-slate-500 font-medium">Tipe Bimbingan</td>
                            <td class="py-1">:</td>
                            <td class="py-1 text-slate-800">{{ $bimbingan->tipe_pengajuan_label }}</td>
                        </tr>
                    </tbody>
                </table>
                
                <!-- SECTION CONTENT -->
                <div class="space-y-4 text-xs leading-relaxed">
                    <!-- Topik -->
                    <div class="space-y-0.5">
                        <h3 class="font-bold text-slate-900 uppercase tracking-wider text-[9px]">I. Topik / Masalah Bimbingan</h3>
                        <p class="text-slate-800 pl-3 border-l border-slate-300 py-0.5">{{ $bimbingan->topik }}</p>
                    </div>
                    
                    <!-- Catatan -->
                    <div class="space-y-0.5">
                        <h3 class="font-bold text-slate-900 uppercase tracking-wider text-[9px]">II. Catatan / Rencana Tindak Lanjut Dosen PA</h3>
                        <p class="text-slate-800 pl-3 border-l border-slate-300 py-0.5">
                            {{ $bimbingan->catatan && $bimbingan->catatan !== '-' ? $bimbingan->catatan : 'Tidak ada catatan atau rencana tindak lanjut khusus.' }}
                        </p>
                    </div>
                    
                    <!-- Hasil Diskusi -->
                    <div class="space-y-0.5">
                        <h3 class="font-bold text-slate-900 uppercase tracking-wider text-[9px]">III. Ringkasan Hasil Penyelesaian (Resolusi)</h3>
                        <p class="text-slate-800 pl-3 border-l border-slate-300 py-0.5">
                            {{ $bimbingan->resolution ?? 'Bimbingan telah terlaksana secara bimbingan akademik.' }}
                        </p>
                    </div>
                </div>
            </div>

            <!-- SIGNATURES -->
            <div class="grid grid-cols-2 gap-8 text-[11px] px-2 mb-2">
                <div class="space-y-12">
                    <div class="space-y-0.5">
                        <p class="text-slate-500">Mengetahui,</p>
                        <p class="font-bold text-slate-800">Dosen Pembimbing Akademik,</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="font-bold text-slate-900 underline">{{ $bimbingan->dosen->name }}</p>
                        <p class="text-slate-500">NIDN. {{ $bimbingan->dosen->identifier ?? '-' }}</p>
                    </div>
                </div>
                <div class="space-y-12 text-right">
                    <div class="space-y-0.5">
                        <p class="text-slate-500">Purwokerto, {{ $bimbingan->tanggal->format('d M Y') }}</p>
                        <p class="font-bold text-slate-800">Mahasiswa yang Bersangkutan,</p>
                    </div>
                    <div class="space-y-0.5">
                        <p class="font-bold text-slate-900 underline">{{ $bimbingan->mahasiswa->name }}</p>
                        <p class="text-slate-500">NIM. {{ $bimbingan->mahasiswa->identifier }}</p>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Auto trigger print for seamless experience -->
    <script>
        window.onload = function() {
            setTimeout(function() {
                window.print();
            }, 500);
        };
    </script>
</body>
</html>
