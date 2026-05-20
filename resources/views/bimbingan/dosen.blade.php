<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">
            
            <!-- Statistik Angkatan -->
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-6">
                @foreach($statistikAngkatan as $stat)
                <div class="bg-white/10 backdrop-blur-md rounded-3xl p-6 border border-slate-100 bg-gradient-to-br from-indigo-50 to-white shadow-[0_8px_30px_rgb(0,0,0,0.04)] flex flex-col items-center justify-center relative overflow-hidden">
                    <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-indigo-100/50 blur-xl"></div>
                    <span class="text-slate-500 text-sm font-semibold uppercase tracking-wider z-10">Angkatan {{ $stat['angkatan'] }}</span>
                    <span class="text-4xl font-extrabold text-indigo-600 mt-3 z-10">{{ $stat['total'] }}</span>
                    <span class="text-xs text-indigo-400 mt-1 z-10">Mahasiswa</span>
                </div>
                @endforeach
            </div>
            
            <!-- Form Input Bimbingan Langsung -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden p-8">
                <h3 class="text-lg font-bold text-slate-800 mb-6">Input Bimbingan Langsung (Tanpa Pengajuan)</h3>
                <form action="{{ route('bimbingan.dosen.store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <flux:select name="user_id" label="Pilih Mahasiswa" required>
                            <option value="">-- Pilih Anak Bimbingan --</option>
                            @foreach($mahasiswaBimbingan as $mhs)
                                <option value="{{ $mhs->id }}">{{ $mhs->name }} ({{ $mhs->identifier }})</option>
                            @endforeach
                        </flux:select>
                        <flux:input type="date" name="guidance_date" label="Tanggal Bimbingan" required />
                    </div>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 mt-6">
                        <flux:input type="text" name="topic" label="Topik Bimbingan" placeholder="Contoh: Konsultasi Skripsi" required />
                        <flux:input type="text" name="notes" label="Catatan Hasil Bimbingan" required />
                    </div>
                    <div class="mt-8 flex justify-end">
                        <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-lg shadow-indigo-200 hover:shadow-indigo-300 hover:scale-105">
                            Simpan Bimbingan
                        </button>
                    </div>
                </form>
            </div>

            <!-- Daftar Validasi Pengajuan -->
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden">
                <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50">
                    <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan Mahasiswa</h3>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($pengajuan as $log)
                    <div class="px-8 py-5 hover:bg-slate-50/60 transition-colors">
                        <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                            
                            {{-- Info Mahasiswa --}}
                            <div class="flex items-center gap-3 w-48 shrink-0">
                                <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                    {{ substr($log->student->name, 0, 1) }}
                                </div>
                                <div class="min-w-0">
                                    <h4 class="font-semibold text-slate-800 text-sm truncate">{{ $log->student->name }}</h4>
                                    <p class="text-xs text-slate-500">{{ $log->student->identifier }} (Smt {{ $log->semester }})</p>
                                </div>
                            </div>

                            {{-- Detail Topik --}}
                            <div class="min-w-0 flex-1 pl-0 sm:pl-4 sm:border-l sm:border-slate-100">
                                <h4 class="font-semibold text-slate-800 text-sm">{{ $log->topic }}</h4>
                                <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                    <span class="inline-flex items-center text-xs text-slate-500">
                                        <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        {{ $log->guidance_date->format('d M Y') }}
                                    </span>
                                </div>

                                @if($log->status !== 'pending' && $log->notes && $log->notes !== '-')
                                    <div class="mt-2 inline-flex items-start gap-1.5 px-3 py-1.5 bg-slate-50 rounded-lg border border-slate-100">
                                        <svg class="w-3.5 h-3.5 text-slate-400 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 8h10M7 12h4m1 8l-4-4H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-3l-4 4z"></path></svg>
                                        <span class="text-[11px] text-slate-600 leading-relaxed">{{ $log->notes }}</span>
                                    </div>
                                @endif
                            </div>

                            {{-- Actions --}}
                            <div class="flex items-center gap-3 sm:flex-shrink-0">
                                @if($log->document_path)
                                    <a href="{{ Storage::url($log->document_path) }}" target="_blank"
                                        class="inline-flex items-center justify-center w-9 h-9 rounded-xl bg-slate-100 text-slate-500 hover:bg-indigo-100 hover:text-indigo-600 transition-colors" title="Lihat Bukti">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif

                                @if($log->status == 'pending')
                                    <div x-data="{ showForm: false }" class="relative">
                                        <button @click="showForm = !showForm" class="inline-flex items-center px-3 py-1.5 text-xs font-bold text-white bg-indigo-600 rounded-lg hover:bg-indigo-700 transition-all shadow-sm">
                                            Validasi
                                        </button>
                                        
                                        <div x-show="showForm" @click.away="showForm = false" class="absolute right-0 top-10 w-64 bg-white rounded-xl shadow-xl border border-slate-200 p-4 z-10" style="display: none;">
                                            <form action="{{ route('bimbingan.dosen.update', $log->id) }}" method="POST" class="flex flex-col gap-3">
                                                @csrf
                                                <div>
                                                    <label class="block text-xs font-semibold text-slate-700 mb-1">Catatan</label>
                                                    <input type="text" name="notes" placeholder="Tambahkan catatan" required class="w-full text-sm border-slate-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                                </div>
                                                <div class="flex gap-2">
                                                    <button type="submit" name="status" value="validated" class="flex-1 text-xs font-bold bg-emerald-100 text-emerald-700 py-1.5 rounded-md hover:bg-emerald-200">Setujui</button>
                                                    <button type="submit" name="status" value="revised" class="flex-1 text-xs font-bold bg-rose-100 text-rose-700 py-1.5 rounded-md hover:bg-rose-200">Tolak</button>
                                                </div>
                                            </form>
                                        </div>
                                    </div>
                                @else
                                    @if($log->status === 'revised')
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-semibold ring-1 ring-inset ring-rose-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-rose-500"></span> Ditolak
                                        </span>
                                    @else
                                        <span class="inline-flex items-center gap-1 px-2.5 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-semibold ring-1 ring-inset ring-emerald-200">
                                            <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Disetujui
                                        </span>
                                    @endif
                                @endif
                            </div>

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
</x-app-layout>
