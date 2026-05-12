<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center">
            <a href="{{ route('skkm.index') }}" class="mr-4 text-slate-400 hover:text-slate-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            </a>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Pengajuan Poin SKKM Baru') }}
            </h2>
        </div>
    </x-slot>

    <div class="py-12 bg-slate-50 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white rounded-3xl shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-slate-100 overflow-hidden backdrop-blur-xl">
                
                <div class="bg-indigo-600 px-8 py-6">
                    <h3 class="text-xl font-bold text-white">Form Pengajuan Kegiatan</h3>
                    <p class="text-indigo-200 mt-1 text-sm">Pilih kategori kegiatan dan unggah bukti fisik yang sesuai.</p>
                </div>

                <div class="p-8">
                    @if ($errors->any())
                        <div class="mb-6 bg-rose-50 border-l-4 border-rose-500 p-4 rounded-r-xl">
                            <div class="flex items-center">
                                <svg class="w-5 h-5 text-rose-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <h3 class="text-rose-800 font-medium">Terdapat kesalahan pengisian form:</h3>
                            </div>
                            <ul class="mt-2 list-disc list-inside text-sm text-rose-600 ml-5">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('skkm.store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf
                        
                        <!-- Pilihan Referensi Poin -->
                        <div>
                            <label for="point_rule_id" class="block text-sm font-semibold text-slate-700 mb-2">Jenis Kegiatan & Poin</label>
                            <div class="relative">
                                <select name="point_rule_id" id="point_rule_id" required class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 pl-4 pr-10 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                                    <option value="" disabled selected>-- Pilih Jenis Kegiatan --</option>
                                    @foreach($rules as $rule)
                                        <option value="{{ $rule->id }}">
                                            {{ str_replace('_', ' ', strtoupper($rule->unsur)) }} - 
                                            {{ $rule->jenis_item }} 
                                            @if($rule->tingkat) ({{ ucfirst($rule->tingkat) }}) @endif 
                                            - {{ str_replace('_', ' ', ucfirst($rule->peranan)) }} 
                                            (+{{ $rule->poin }} Poin)
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <p class="mt-2 text-xs text-slate-500">Poin akan dikalkulasi secara otomatis berdasarkan pilihan di atas.</p>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <!-- Nama Kegiatan -->
                            <div>
                                <label for="nama_kegiatan" class="block text-sm font-semibold text-slate-700 mb-2">Nama Kegiatan</label>
                                <input type="text" name="nama_kegiatan" id="nama_kegiatan" required value="{{ old('nama_kegiatan') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: Lomba Karya Tulis Ilmiah Nasional">
                            </div>

                            <!-- Penyelenggara -->
                            <div>
                                <label for="penyelenggara" class="block text-sm font-semibold text-slate-700 mb-2">Penyelenggara</label>
                                <input type="text" name="penyelenggara" id="penyelenggara" required value="{{ old('penyelenggara') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: Kementerian Pendidikan">
                            </div>

                            <!-- Tanggal Kegiatan -->
                            <div>
                                <label for="tanggal_kegiatan" class="block text-sm font-semibold text-slate-700 mb-2">Tanggal Pelaksanaan</label>
                                <input type="date" name="tanggal_kegiatan" id="tanggal_kegiatan" required value="{{ old('tanggal_kegiatan') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors">
                            </div>

                            <!-- Semester Input -->
                            <div>
                                <label for="semester_input" class="block text-sm font-semibold text-slate-700 mb-2">Semester Saat Kegiatan</label>
                                <input type="number" name="semester_input" id="semester_input" min="1" max="8" required value="{{ old('semester_input') }}" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-3 px-4 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 focus:bg-white transition-colors" placeholder="Cth: 3">
                            </div>
                        </div>

                        <!-- File Upload -->
                        <div class="pt-4 border-t border-slate-100">
                            <label class="block text-sm font-semibold text-slate-700 mb-2">Bukti Fisik (Sertifikat / Surat Tugas)</label>
                            <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-slate-300 border-dashed rounded-2xl hover:border-indigo-500 hover:bg-indigo-50 transition-colors bg-slate-50 group">
                                <div class="space-y-2 text-center">
                                    <svg class="mx-auto h-12 w-12 text-slate-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48" aria-hidden="true">
                                        <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    <div class="flex text-sm text-slate-600 justify-center">
                                        <label for="file_bukti" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                            <span class="px-2">Upload file PDF, JPG, PNG</span>
                                            <input id="file_bukti" name="file_bukti" type="file" class="sr-only" required accept=".pdf,.jpg,.jpeg,.png">
                                        </label>
                                    </div>
                                    <p class="text-xs text-slate-500">Maksimal 5MB.</p>
                                </div>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex items-center justify-end space-x-4 pt-6 border-t border-slate-100">
                            <a href="{{ route('skkm.index') }}" class="px-6 py-3 border border-slate-300 rounded-xl shadow-sm text-sm font-medium text-slate-700 bg-white hover:bg-slate-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                                Batal
                            </a>
                            <button type="submit" class="px-8 py-3 border border-transparent rounded-xl shadow-lg shadow-indigo-200 text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-all transform hover:scale-105">
                                Kirim Pengajuan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
