<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- Statistik Angkatan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($statistikAngkatan as $stat)
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-slate-100 bg-gradient-to-br from-indigo-50 to-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col items-center justify-center relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-indigo-100/50 blur-xl"></div>
                    <span class="text-slate-500 text-sm font-semibold uppercase tracking-wider z-10">Angkatan {{ $stat }}</span>
                    <span class="text-4xl font-extrabold text-indigo-600 mt-3 z-10">{{ $mahasiswaBimbingan->where('identifier', 'like', substr($stat, 2) . '%')->count() }}</span>
                    <span class="text-xs text-indigo-400 mt-1 z-10">Mahasiswa</span>
                </div>
                @endforeach
            </div>

            <div x-data="{ type: '{{ old('filter_type', 'all') }}', value: '{{ old('filter_value', '') }}' }" class="space-y-8">
                <div class="grid grid-cols-1 gap-8">
                    <div class="space-y-8">
                        <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden p-8">
                            <h3 class="text-lg font-bold text-slate-800 mb-6">Jadwalkan Bimbingan</h3>
                            <form action="{{ route('bimbingan.dosen.store') }}" method="POST">
                                @csrf

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Jangkauan Bimbingan</label>
                                        <select name="filter_type" x-model="type" x-on:change="value = ''" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="all">Semua Mahasiswa</option>
                                            <option value="angkatan">Per Angkatan</option>
                                            <option value="individu">Per Mahasiswa</option>
                                        </select>
                                    </div>

                                    <div x-show="type === 'angkatan'" class="transition-all duration-200" x-cloak>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Angkatan</label>
                                        <select name="filter_value" x-model="value" class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Pilih Angkatan --</option>
                                            @foreach($angkatanOptions as $angkatan)
                                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div x-show="type === 'individu'" class="transition-all duration-200" x-cloak>
                                        <flux:select name="user_id" label="Pilih Mahasiswa">
                                            <option value="">-- Pilih Anak Bimbingan --</option>
                                            @foreach($mahasiswaBimbingan as $mhs)
                                                <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->identifier }})</option>
                                            @endforeach
                                        </flux:select>
                                    </div>
                                </div>

                                <div x-show="type !== 'individu'" class="rounded-3xl border border-slate-200 bg-slate-50 p-5 text-sm text-slate-600 mt-6" x-cloak>
                                    <p x-show="type === 'all'">Jadwalkan bimbingan untuk semua mahasiswa bimbingan Anda.</p>
                                    <p x-show="type === 'angkatan'">Jadwalkan bimbingan untuk semua mahasiswa angkatan yang dipilih.</p>
                                </div>

                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                                    <flux:input type="date" name="tanggal" label="Tanggal Bimbingan" required />
                                    <flux:input type="text" name="topik" label="Topik Bimbingan" placeholder="Contoh: Konsultasi Skripsi" required />
                                </div>
                                <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                                    <flux:input type="text" name="catatan" label="Catatan / Instruksi" placeholder="Contoh: Bawa draft proposal" />
                                </div>
                                <div class="mt-8 flex justify-end">
                                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:scale-105">
                                        Simpan Jadwal
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Daftar Bimbingan Terjadwal -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Bimbingan Terjadwal</h3>
                    <p class="text-sm text-slate-500 mt-1">Semua sesi bimbingan yang Anda jadwalkan untuk mahasiswa bimbingan.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($scheduledBimbingan as $bimbingan)
                    <div class="px-8 py-5 hover:bg-slate-50/60 transition-colors">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                @if($bimbingan->is_group)
                                    <div class="flex items-center gap-3 w-48 shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-indigo-50 border border-indigo-100 flex items-center justify-center text-indigo-600 text-lg font-bold">
                                            👥
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-semibold text-slate-800 text-sm truncate">
                                                {{ $bimbingan->group_type === 'all' ? 'Semua Mahasiswa' : 'Angkatan ' . $bimbingan->group_value }}
                                            </h4>
                                            <p class="text-xs text-indigo-500 font-semibold mt-0.5">Bimbingan Kelompok</p>
                                        </div>
                                    </div>
                                @else
                                    <div class="flex items-center gap-3 w-48 shrink-0">
                                        <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                            {{ substr($bimbingan->mahasiswa->name, 0, 1) }}
                                        </div>
                                        <div class="min-w-0">
                                            <h4 class="font-semibold text-slate-800 text-sm truncate">{{ $bimbingan->mahasiswa->name }}</h4>
                                            <p class="text-xs text-slate-500">{{ $bimbingan->mahasiswa->identifier }} (Smt {{ $bimbingan->semester }})</p>
                                            <p class="text-xs text-slate-500">{{ optional($bimbingan->mahasiswa->programStudi)->name ?? 'Program Studi belum terdaftar' }}</p>
                                        </div>
                                    </div>
                                @endif

                                <div class="min-w-0 flex-1 pl-0 sm:pl-4 sm:border-l sm:border-slate-100">
                                    <h4 class="font-semibold text-slate-800 text-sm">{{ $bimbingan->topik }}</h4>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $bimbingan->tanggal->format('d M Y') }}
                                        </span>
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Status: <strong class="ml-1">{{ ucfirst($bimbingan->status) }}</strong>
                                        </span>
                                    </div>
                                    @if($bimbingan->catatan && $bimbingan->catatan !== '-')
                                        <div class="mt-2 inline-flex items-start gap-1.5 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100">
                                            <span class="text-[11px] text-slate-600 leading-relaxed">{{ $bimbingan->catatan }}</span>
                                        </div>
                                    @endif

                                    @if($bimbingan->is_group)
                                        <div class="mt-3">
                                            <p class="text-xs font-semibold text-slate-500 mb-1.5">Anggota Kelompok ({{ $bimbingan->group_students->count() }}):</p>
                                            <div class="flex flex-wrap gap-1.5">
                                                @foreach($bimbingan->group_students as $student)
                                                    <span class="inline-flex items-center gap-1 px-2.5 py-1 rounded-full bg-slate-50 text-[11px] font-medium text-slate-700 border border-slate-200 shadow-sm hover:bg-indigo-50 hover:border-indigo-200 transition-colors">
                                                        <span>{{ $student->name }} ({{ $student->identifier }})</span>
                                                        @if($student->phone_number && $bimbingan->status === 'validated')
                                                            @php
                                                                $phone = preg_replace('/\D+/', '', $student->phone_number);
                                                                $text = "Halo {$student->name}, jadwal bimbingan kelompok Anda telah dibuat untuk tanggal {$bimbingan->tanggal->format('d M Y')} dengan topik: {$bimbingan->topik}. Mohon konfirmasi kehadiran Anda.";
                                                                $waLink = 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);
                                                            @endphp
                                                            <a href="{{ $waLink }}" target="_blank" class="text-emerald-600 hover:text-emerald-800 ml-1" title="Kirim WA">
                                                                <svg class="w-3.5 h-3.5 inline text-emerald-500" fill="currentColor" viewBox="0 0 24 24"><path d="M20.52 3.48A11.79 11.79 0 0012.03.007 11.875 11.875 0 002.5 11.89 11.6 11.6 0 001.77 16.4l.03.41 2.69-.87a.62.62 0 01.45.02l2.82 1.02a.61.61 0 01.34.3l.3.72a.6.6 0 01-.1.66l-1.42 1.76a.63.63 0 01-.58.24 12.32 12.32 0 01-4.18-.97 12.15 12.15 0 01-2.77-2.09C.47 16.99 1.73 9.77 6.75 4.76A11.688 11.688 0 0112.03 1.5c3.13 0 6.05 1.22 8.24 3.43a11.64 11.64 0 013.44 8.25c0 3.09-1.2 5.99-3.38 8.18l-.42.41-2.8-1.01a.62.62 0 01-.33-.28l-.21-.35a.59.59 0 01.1-.63l1.4-1.74a.6.6 0 01.29-.2l.63-.2a.6.6 0 00.36-.27l.95-1.39c1.57-1.91 2.44-4.37 2.44-6.99a11.68 11.68 0 00-3.38-8.23zm-6.44 12.65c-.24.65-1.4 1.25-1.92 1.31-.52.05-1.14.08-2.31-.54-1.17-.61-2.21-1.77-2.56-1.89-.36-.12-.79-.19-1.34.19-.55.38-2.08 1.56-2.08 1.56s-1.17-.33-2.25-1.11c-1.06-.77-1.62-1.93-1.82-2.37-.2-.44-.02-.69.39-.95.4-.25.85-.63 1.2-.95.38-.34.47-.56.7-.94.23-.39.12-.72-.06-.99-.17-.27-1.4-3.4-1.92-4.63-.5-1.23-.99-1.07-1.37-1.09-.35-.02-.76-.02-1.17-.02-.39 0-.99.14-1.5.7-.51.57-1.91 1.86-1.91 4.54 0 2.69 1.96 5.28 2.24 5.64.27.35 3.87 5.96 9.4 8.1 5.83 2.24 5.83 1.48 6.88 1.39 1.05-.1 3.94-1.6 4.5-3.15.56-1.55.56-2.88.39-3.16-.17-.28-1.69-.53-3.31-1.18a5.31 5.31 0 01-1.67-.86c-.47-.4-.79-.87-.99-1.36z"/></svg>
                                                            </a>
                                                        @endif
                                                    </span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                                <div class="flex flex-col items-end gap-2 sm:flex-shrink-0">
                                    @if($bimbingan->is_group && $bimbingan->status === 'validated')
                                        @php
                                            $targetName = $bimbingan->group_type === 'all' ? 'semua anak bimbingan' : 'Angkatan ' . $bimbingan->group_value;
                                            $catatanText = ($bimbingan->catatan && $bimbingan->catatan !== '-') ? " Catatan: {$bimbingan->catatan}." : "";
                                            $groupText = "Halo rekan-rekan {$targetName}, jadwal bimbingan kelompok kita telah dibuat untuk tanggal {$bimbingan->tanggal->format('d M Y')} dengan topik: {$bimbingan->topik}.{$catatanText} Mohon konfirmasi dan kehadiran rekan-rekan sekalian.";
                                            $groupWaLink = 'https://api.whatsapp.com/send?text=' . rawurlencode($groupText);

                                            // Prepare direct links for personal WA
                                            $personalWaLinks = [];
                                            foreach ($bimbingan->group_students as $student) {
                                                if ($student->phone_number) {
                                                    $phone = preg_replace('/\D+/', '', $student->phone_number);
                                                    $text = "Halo {$student->name}, jadwal bimbingan kelompok Anda telah dibuat untuk tanggal {$bimbingan->tanggal->format('d M Y')} dengan topik: {$bimbingan->topik}. Mohon konfirmasi kehadiran Anda.";
                                                    $personalWaLinks[] = 'https://wa.me/' . $phone . '?text=' . rawurlencode($text);
                                                }
                                            }
                                        @endphp
                                        <a href="{{ $groupWaLink }}" target="_blank" class="inline-flex items-center gap-2 px-3.5 py-2 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm" title="Kirim undangan ke Grup WhatsApp Kelas">
                                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946C.06 5.348 5.397.01 12.008.01c3.202.001 6.212 1.246 8.477 3.514 2.266 2.268 3.507 5.28 3.505 8.484-.004 6.657-5.34 11.997-11.953 11.997-2.005-.001-3.973-.502-5.717-1.458L0 24zm6.59-4.846c1.666.988 3.311 1.503 5.351 1.505 5.548 0 10.065-4.512 10.068-10.066.002-2.69-1.04-5.216-2.93-7.11C17.195 1.59 14.677.55 12.002.55 6.45.55 1.932 5.057 1.93 10.612c-.001 2.083.547 4.117 1.588 5.922L2.502 21.1l4.85-1.272zM18.66 14.93c-.36-.18-2.134-1.055-2.466-1.176-.33-.12-.57-.18-.81.18-.24.36-.93 1.176-1.14 1.416-.21.24-.42.27-.78.09-.36-.18-1.52-.56-2.896-1.78-1.07-.957-1.793-2.14-2.003-2.5-.21-.36-.02-.555.16-.735.16-.163.36-.42.54-.63.18-.21.24-.36.36-.6.12-.24.06-.45-.03-.63-.09-.18-.81-1.955-1.11-2.675-.3-.72-.6-1.035-.81-1.04-.21-.005-.45-.005-.69-.005-.24 0-.63.09-.96.45-.33.36-1.26 1.23-1.26 3.003 0 1.77 1.29 3.483 1.47 3.723.18.24 2.535 3.87 6.14 5.43.858.37 1.528.59 2.05.757.86.273 1.64.234 2.26.142.69-.103 2.133-.873 2.433-1.717.3-.843.3-1.563.21-1.716-.09-.153-.33-.243-.69-.423z"/></svg>
                                            Kirim WA ke Grup
                                        </a>

                                        @if(count($personalWaLinks) > 0)
                                            <button type="button" onclick="openAllWaChats(this)" data-links="{{ json_encode($personalWaLinks) }}" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100 transition-colors shadow-sm" title="Buka personal chat WA semua anggota sekaligus">
                                                <span>🚀 Buka WA Semua Anggota</span>
                                            </button>
                                        @endif
                                    @endif

                                    @if(!$bimbingan->is_group && $bimbingan->status === 'validated' && $bimbingan->mahasiswa->phone_number)
                                        <a href="{{ $bimbingan->whatsapp_link }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 text-xs font-semibold hover:bg-emerald-100 transition-colors">
                                            <svg class="w-4 h-4" viewBox="0 0 24 24" fill="currentColor"><path d="M20.52 3.48A11.79 11.79 0 0012.03.007 11.875 11.875 0 002.5 11.89 11.6 11.6 0 001.77 16.4l.03.41 2.69-.87a.62.62 0 01.45.02l2.82 1.02a.61.61 0 01.34.3l.3.72a.6.6 0 01-.1.66l-1.42 1.76a.63.63 0 01-.58.24 12.32 12.32 0 01-4.18-.97 12.15 12.15 0 01-2.77-2.09C.47 16.99 1.73 9.77 6.75 4.76A11.688 11.688 0 0112.03 1.5c3.13 0 6.05 1.22 8.24 3.43a11.64 11.64 0 013.44 8.25c0 3.09-1.2 5.99-3.38 8.18l-.42.41-2.8-1.01a.62.62 0 01-.33-.28l-.21-.35a.59.59 0 01.1-.63l1.4-1.74a.6.6 0 01.29-.2l.63-.2a.6.6 0 00.36-.27l.95-1.39c1.57-1.91 2.44-4.37 2.44-6.99a11.68 11.68 0 00-3.38-8.23zm-6.44 12.65c-.24.65-1.4 1.25-1.92 1.31-.52.05-1.14.08-2.31-.54-1.17-.61-2.21-1.77-2.56-1.89-.36-.12-.79-.19-1.34.19-.55.38-2.08 1.56-2.08 1.56s-1.17-.33-2.25-1.11c-1.06-.77-1.62-1.93-1.82-2.37-.2-.44-.02-.69.39-.95.4-.25.85-.63 1.2-.95.38-.34.47-.56.7-.94.23-.39.12-.72-.06-.99-.17-.27-1.4-3.4-1.92-4.63-.5-1.23-.99-1.07-1.37-1.09-.35-.02-.76-.02-1.17-.02-.39 0-.99.14-1.5.7-.51.57-1.91 1.86-1.91 4.54 0 2.69 1.96 5.28 2.24 5.64.27.35 3.87 5.96 9.4 8.1 5.83 2.24 5.83 1.48 6.88 1.39 1.05-.1 3.94-1.6 4.5-3.15.56-1.55.56-2.88.39-3.16-.17-.28-1.69-.53-3.31-1.18a5.31 5.31 0 01-1.67-.86c-.47-.4-.79-.87-.99-1.36z"/></svg>
                                            Kirim WhatsApp
                                        </a>
                                    @endif
                                </div>
                            </div>

                            @if($bimbingan->status === 'validated')
                                <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Catat Laporan Selesai</h4>
                                    <form action="{{ route('bimbingan.dosen.report', $bimbingan->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @csrf
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ringkasan Penyelesaian</label>
                                            <textarea name="resolution" rows="3" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Kegiatan</label>
                                            <input type="file" name="activity_photo" accept="image/*" class="w-full text-sm text-slate-700" />
                                        </div>
                                        <div class="sm:col-span-2 flex justify-end">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-all">Simpan Laporan</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if($bimbingan->status === 'completed')
                                <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                                    <h4 class="text-sm font-semibold text-emerald-800 mb-2">Laporan Bimbingan selesai</h4>
                                    <p class="text-sm text-slate-700 mb-3">{{ $bimbingan->resolution }}</p>
                                    @if($bimbingan->activity_photo_path)
                                        <a href="{{ Storage::url($bimbingan->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100">Lihat Foto Kegiatan</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-8 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 mb-5">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Belum Ada Bimbingan Terjadwal</h3>
                        <p class="text-slate-500 mt-2 text-sm max-w-sm mx-auto">Anda belum menjadwalkan sesi bimbingan untuk mahasiswa Anda.</p>
                    </div>
                    @endforelse
                </div>
            </div>

            <!-- Daftar Pengajuan Mahasiswa -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan Mahasiswa</h3>
                    <p class="text-sm text-slate-500 mt-1">Semua permintaan bimbingan yang diajukan mahasiswa Anda.</p>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($pendingRequests as $bimbingan)
                    <div class="px-8 py-5 hover:bg-slate-50/60 transition-colors">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-3 w-48 shrink-0">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                        {{ substr($bimbingan->mahasiswa->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-slate-800 text-sm truncate">{{ $bimbingan->mahasiswa->name }}</h4>
                                        <p class="text-xs text-slate-500">{{ $bimbingan->mahasiswa->identifier }} (Smt {{ $bimbingan->semester }})</p>
                                        <p class="text-xs text-slate-500">{{ optional($bimbingan->mahasiswa->programStudi)->name ?? 'Program Studi belum terdaftar' }}</p>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 pl-0 sm:pl-4 sm:border-l sm:border-slate-100">
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1">
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $bimbingan->tanggal->format('d M Y') }}
                                        </span>
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Status: <strong class="ml-1">{{ ucfirst($bimbingan->status) }}</strong>
                                        </span>
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                                            {{ $bimbingan->tipe_pengajuan_label }}
                                        </span>
                                    </div>
                                    <h4 class="font-semibold text-slate-800 text-sm mt-3">{{ $bimbingan->topik }}</h4>
                                    @if($bimbingan->catatan && $bimbingan->catatan !== '-')
                                        <div class="mt-2 inline-flex items-start gap-1.5 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100">
                                            <span class="text-[11px] text-slate-600 leading-relaxed">{{ $bimbingan->catatan }}</span>
                                        </div>
                                    @endif
                                    @if($bimbingan->document_path)
                                        <div class="mt-3">
                                            <a href="{{ Storage::url($bimbingan->document_path) }}" target="_blank" class="text-xs font-semibold text-indigo-700 hover:text-indigo-900">Lihat Dokumen Pendukung</a>
                                        </div>
                                    @endif
                                </div>
                            </div>

                            @if($bimbingan->status === 'pending')
                            <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                <h4 class="text-sm font-semibold text-slate-800 mb-3">Tindakan Pengajuan</h4>
                                <form action="{{ route('bimbingan.dosen.update', $bimbingan->id) }}" method="POST" class="space-y-4">
                                    @csrf
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Keputusan</label>
                                        <select name="status" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="validated">Setujui</option>
                                            <option value="revised">Tolak / Revisi</option>
                                        </select>
                                    </div>
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan untuk Mahasiswa</label>
                                        <textarea name="catatan" rows="3" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                    </div>
                                    <div class="text-right">
                                        <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all">Simpan Keputusan</button>
                                    </div>
                                </form>
                            </div>
                            @endif

                            @if($bimbingan->status === 'validated')
                                <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5">
                                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Catat Laporan Selesai</h4>
                                    <form action="{{ route('bimbingan.dosen.report', $bimbingan->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                        @csrf
                                        <div class="sm:col-span-2">
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Ringkasan Penyelesaian</label>
                                            <textarea name="resolution" rows="3" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Kegiatan</label>
                                            <input type="file" name="activity_photo" accept="image/*" class="w-full text-sm text-slate-700" />
                                        </div>
                                        <div class="sm:col-span-2 flex justify-end">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 transition-all">Simpan Laporan</button>
                                        </div>
                                    </form>
                                </div>
                            @endif

                            @if($bimbingan->status === 'completed')
                                <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 p-5">
                                    <h4 class="text-sm font-semibold text-emerald-800 mb-2">Laporan Bimbingan selesai</h4>
                                    <p class="text-sm text-slate-700 mb-3">{{ $bimbingan->resolution }}</p>
                                    @if($bimbingan->activity_photo_path)
                                        <a href="{{ Storage::url($bimbingan->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100">Lihat Foto Kegiatan</a>
                                    @endif
                                </div>
                            @endif
                        </div>
                    </div>
                    @empty
                    <div class="px-8 py-16 text-center">
                        <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-gradient-to-br from-indigo-50 to-purple-50 mb-5">
                            <svg class="w-10 h-10 text-indigo-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <h3 class="text-lg font-bold text-slate-800">Tidak Ada Pengajuan</h3>
                        <p class="text-slate-500 mt-2 text-sm max-w-sm mx-auto">Saat ini belum ada pengajuan bimbingan dari mahasiswa Anda.</p>
                    </div>
                    @endforelse
                </div>
            </div>

        </div>
    </div>

    <script>
        function openAllWaChats(button) {
            const links = JSON.parse(button.getAttribute('data-links'));
            if (!links || links.length === 0) return;
            
            if (confirm(`Apakah Anda ingin membuka ${links.length} tab chat WhatsApp baru sekaligus untuk mengirim pesan individual? (Pastikan browser Anda mengizinkan pop-up)`)) {
                links.forEach((link, index) => {
                    setTimeout(() => {
                        window.open(link, '_blank');
                    }, index * 1000); // 1-second delay between tabs to prevent browser blocks
                });
            }
        }
    </script>
</x-app-layout>
