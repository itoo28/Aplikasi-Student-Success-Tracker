<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Bimbingan Akademik (Dosen PA)') }}
            </h2>
        </div>
    </x-slot>

    <div class="space-y-8">

        @if(session('validation_whatsapp_link'))
            <div class="relative overflow-hidden rounded-3xl border border-emerald-200 bg-gradient-to-br from-emerald-50 via-white to-teal-50 p-6 shadow-[0_10px_32px_rgba(16,185,129,0.12)]">
                <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-emerald-200/30 blur-2xl"></div>
                <div class="relative flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-3xl">
                        <span class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-emerald-700">
                            Keputusan Dikirim
                        </span>
                        <h3 class="mt-3 text-lg font-bold text-slate-800">Status Pengajuan Berhasil Diperbarui</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Keputusan bimbingan mahasiswa <strong class="text-slate-800">{{ session('validation_student_name') }}</strong> telah diubah menjadi <strong class="text-slate-800">{{ session('validation_status') }}</strong>. 
                            Silakan klik tombol di samping untuk mengirim notifikasi keputusan tersebut langsung ke WhatsApp mahasiswa yang bersangkutan.
                        </p>
                    </div>
                    <div class="flex-shrink-0">
                        <a href="{{ session('validation_whatsapp_link') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-emerald-300">
                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                            </svg>
                            Hubungi Mahasiswa
                        </a>
                    </div>
                </div>
            </div>
        @endif

        @if(session('created_bimbingan_students'))
            <div class="relative overflow-hidden rounded-3xl border border-indigo-200 bg-gradient-to-br from-indigo-50 via-white to-purple-50 p-6 shadow-[0_10px_32px_rgba(79,70,229,0.12)]">
                <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-indigo-200/30 blur-2xl"></div>
                <div class="relative flex flex-col gap-4">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-indigo-700">
                            Jadwal Baru Dibuat
                        </span>
                        <h3 class="mt-3 text-lg font-bold text-slate-800">Jadwal Bimbingan @if(session('created_bimbingan_is_group')) Kelompok @endif Berhasil Dibuat</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Jadwal bimbingan dengan topik <strong class="text-slate-800">"{{ session('created_bimbingan_topic') }}"</strong> pada tanggal <strong class="text-slate-800">{{ session('created_bimbingan_date') }}</strong> telah ditambahkan. 
                            Silakan hubungi mahasiswa di bawah ini untuk mengirimkan notifikasi jadwal bimbingan via WhatsApp.
                        </p>
                    </div>

                    <div class="mt-2 border-t border-slate-100 pt-4">
                        <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Daftar Mahasiswa</h4>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach(session('created_bimbingan_students') as $student)
                                <div class="flex items-center justify-between text-xs bg-white p-3 rounded-xl border border-slate-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:border-indigo-100 transition-colors">
                                    <span class="font-semibold text-slate-800 truncate pr-2">{{ $student['name'] }}</span>
                                    @if($student['whatsapp_link'])
                                        <a href="{{ $student['whatsapp_link'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm flex-shrink-0">
                                            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                            </svg>
                                            <span>Hubungi</span>
                                        </a>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        @endif

        @if(session('cancel_whatsapp_link') || session('cancelled_students'))
            <div class="relative overflow-hidden rounded-3xl border border-rose-200 bg-gradient-to-br from-rose-50 via-white to-orange-50 p-6 shadow-[0_10px_32px_rgba(244,63,94,0.12)]">
                <div class="absolute -right-10 -top-10 h-28 w-28 rounded-full bg-rose-200/30 blur-2xl"></div>
                <div class="relative flex flex-col gap-4">
                    <div>
                        <span class="inline-flex items-center gap-2 rounded-full bg-rose-100 px-3 py-1 text-xs font-bold uppercase tracking-[0.18em] text-rose-700">
                            Jadwal Dibatalkan
                        </span>
                        <h3 class="mt-3 text-lg font-bold text-slate-800">Jadwal Bimbingan Berhasil Dibatalkan</h3>
                        <p class="mt-2 text-sm leading-relaxed text-slate-600">
                            Jadwal bimbingan mahasiswa/kelompok <strong class="text-slate-800">{{ session('cancel_student_name') }}</strong> telah dibatalkan. 
                            Silakan hubungi mahasiswa di bawah ini untuk mengirimkan notifikasi pembatalan tersebut langsung ke WhatsApp mahasiswa yang bersangkutan.
                        </p>
                    </div>

                    @if(session('cancelled_students'))
                        <div class="mt-2 border-t border-slate-100 pt-4">
                            <h4 class="text-xs font-bold text-slate-700 uppercase tracking-wider mb-3">Daftar Mahasiswa Dibatalkan</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                @foreach(session('cancelled_students') as $student)
                                    <div class="flex items-center justify-between text-xs bg-white p-3 rounded-xl border border-slate-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:border-rose-100 transition-colors">
                                        <span class="font-semibold text-slate-800 truncate pr-2">{{ $student['name'] }}</span>
                                        @if($student['whatsapp_cancel_link'])
                                            <a href="{{ $student['whatsapp_cancel_link'] }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm flex-shrink-0">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                                <span>Hubungi</span>
                                            </a>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @else
                        <div class="flex-shrink-0 md:self-end">
                            <a href="{{ session('cancel_whatsapp_link') }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 rounded-2xl bg-emerald-600 px-5 py-3 text-sm font-bold text-white shadow-lg shadow-emerald-200 transition-all hover:bg-emerald-700 hover:shadow-emerald-300">
                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                </svg>
                                Hubungi Mahasiswa
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        @endif


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
                                        <select name="filter_type" x-model="type" x-on:change="value = ''" class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-10 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="all">Semua Mahasiswa</option>
                                            <option value="angkatan">Per Angkatan</option>
                                            <option value="individu">Per Mahasiswa</option>
                                        </select>
                                    </div>

                                    <div x-show="type === 'angkatan'" class="transition-all duration-200" x-cloak>
                                        <label class="block text-sm font-semibold text-slate-700 mb-2">Angkatan</label>
                                        <select name="filter_value" x-model="value" class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-10 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                            <option value="">-- Pilih Angkatan --</option>
                                            @foreach($angkatanOptions as $angkatan)
                                                <option value="{{ $angkatan }}">{{ $angkatan }}</option>
                                            @endforeach
                                        </select>
                                    </div>

                                    <div x-show="type === 'individu'" class="transition-all duration-200" x-cloak>
                                        <div x-data="{
                                            open: false,
                                            search: '',
                                            selectedId: '{{ old('user_id', '') }}',
                                            selectedName: '',
                                            students: @js($mahasiswaBimbingan->map(fn($mhs) => ['id' => $mhs->id, 'name' => $mhs->name, 'identifier' => $mhs->identifier])),
                                            highlightedIndex: 0,
                                            init() {
                                                let selected = this.students.find(s => s.id == this.selectedId);
                                                if (selected) {
                                                    this.selectedName = selected.name + ' (' + selected.identifier + ')';
                                                    this.search = selected.name + ' (' + selected.identifier + ')';
                                                }
                                            },
                                            get filteredStudents() {
                                                if (this.search === '' || this.search === this.selectedName) {
                                                    return this.students;
                                                }
                                                return this.students.filter(student => {
                                                    let term = this.search.toLowerCase();
                                                    return student.name.toLowerCase().includes(term) || student.identifier.toLowerCase().includes(term);
                                                });
                                            },
                                            selectStudent(student) {
                                                this.selectedId = student.id;
                                                this.selectedName = student.name + ' (' + student.identifier + ')';
                                                this.search = student.name + ' (' + student.identifier + ')';
                                                this.open = false;
                                                this.highlightedIndex = 0;
                                            },
                                            clearSelection() {
                                                this.selectedId = '';
                                                this.selectedName = '';
                                                this.search = '';
                                                this.highlightedIndex = 0;
                                            },
                                            highlightNext() {
                                                if (this.filteredStudents.length === 0) return;
                                                this.highlightedIndex = (this.highlightedIndex + 1) % this.filteredStudents.length;
                                                this.scrollToHighlighted();
                                            },
                                            highlightPrev() {
                                                if (this.filteredStudents.length === 0) return;
                                                this.highlightedIndex = (this.highlightedIndex - 1 + this.filteredStudents.length) % this.filteredStudents.length;
                                                this.scrollToHighlighted();
                                            },
                                            selectHighlighted() {
                                                if (this.filteredStudents.length > 0 && this.highlightedIndex >= 0 && this.highlightedIndex < this.filteredStudents.length) {
                                                    this.selectStudent(this.filteredStudents[this.highlightedIndex]);
                                                }
                                            },
                                            scrollToHighlighted() {
                                                this.$nextTick(() => {
                                                    let container = this.$refs.dropdownList;
                                                    let item = container.querySelector('[data-index=\'' + this.highlightedIndex + '\']');
                                                    if (item) {
                                                        let containerTop = container.scrollTop;
                                                        let containerBottom = containerTop + container.clientHeight;
                                                        let elemTop = item.offsetTop;
                                                        let elemBottom = elemTop + item.clientHeight;

                                                        if (elemTop < containerTop) {
                                                            container.scrollTop = elemTop;
                                                        } else if (elemBottom > containerBottom) {
                                                            container.scrollTop = elemBottom - container.clientHeight;
                                                        }
                                                    }
                                                });
                                            }
                                        }" class="relative w-full">
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Pilih Mahasiswa</label>
                                            
                                            <div class="relative">
                                                <input 
                                                    type="text" 
                                                    placeholder="Cari & Pilih Mahasiswa..." 
                                                    x-model="search"
                                                    @focus="open = true"
                                                    @keydown.down.prevent="open = true; highlightNext()"
                                                    @keydown.up.prevent="open = true; highlightPrev()"
                                                    @keydown.enter.prevent="selectHighlighted()"
                                                    @keydown.escape="open = false"
                                                    @click.away="
                                                        open = false; 
                                                        let matched = students.find(s => (s.name + ' (' + s.identifier + ')') === search);
                                                        if (!matched) {
                                                            if (selectedId) {
                                                                let prev = students.find(s => s.id == selectedId);
                                                                search = prev.name + ' (' + prev.identifier + ')';
                                                            } else {
                                                                search = '';
                                                            }
                                                        }
                                                    "
                                                    class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-10 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all"
                                                />
                                                
                                                <!-- Dropdown arrow icon -->
                                                <div class="absolute inset-y-0 right-0 flex items-center pr-3 pointer-events-none">
                                                    <svg class="h-5 w-5 text-slate-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                                    </svg>
                                                </div>
                                                
                                                <!-- Clear (x) button if selected -->
                                                <template x-if="selectedId">
                                                    <button 
                                                        type="button" 
                                                        @click="clearSelection()" 
                                                        class="absolute inset-y-0 right-8 flex items-center pr-1 text-slate-400 hover:text-slate-600 focus:outline-none"
                                                    >
                                                        <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                                        </svg>
                                                    </button>
                                                </template>
                                            </div>

                                            <!-- Hidden input to submit the actual value -->
                                            <input type="hidden" name="user_id" :value="selectedId" />

                                            <!-- Dropdown List -->
                                            <div 
                                                x-show="open" 
                                                x-transition:enter="transition ease-out duration-100"
                                                x-transition:enter-start="transform opacity-0 scale-95"
                                                x-transition:enter-end="transform opacity-100 scale-100"
                                                x-transition:leave="transition ease-in duration-75"
                                                x-transition:leave-start="transform opacity-100 scale-100"
                                                x-transition:leave-end="transform opacity-0 scale-95"
                                                x-ref="dropdownList"
                                                class="absolute z-50 mt-2 w-full rounded-2xl border border-slate-200 bg-white shadow-xl max-h-60 overflow-y-auto py-1"
                                                style="display: none;"
                                            >
                                                <template x-for="(student, index) in filteredStudents" :key="student.id">
                                                    <button
                                                        type="button"
                                                        @click="selectStudent(student)"
                                                        @mouseenter="highlightedIndex = index"
                                                        :data-index="index"
                                                        class="w-full text-left px-4 py-2.5 text-sm transition-colors flex flex-col"
                                                        :class="highlightedIndex === index ? 'bg-indigo-50 text-indigo-900' : 'text-slate-700 hover:bg-slate-50'"
                                                    >
                                                        <span class="font-semibold" x-text="student.name"></span>
                                                        <span class="text-xs mt-0.5" :class="highlightedIndex === index ? 'text-indigo-600' : 'text-slate-400'" x-text="student.identifier"></span>
                                                    </button>
                                                </template>
                                                <div x-show="filteredStudents.length === 0" class="px-4 py-3 text-sm text-slate-500 text-center font-medium">
                                                    Mahasiswa tidak ditemukan
                                                </div>
                                            </div>
                                        </div>
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
                <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Bimbingan Terjadwal</h3>
                        <p class="text-sm text-slate-500 mt-1">Semua sesi bimbingan yang Anda jadwalkan untuk mahasiswa bimbingan.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <form method="GET" action="{{ route('bimbingan.dosen.index') }}" class="flex items-center gap-2">
                            @if(request('filter_type')) <input type="hidden" name="filter_type" value="{{ request('filter_type') }}"> @endif
                            @if(request('filter_value')) <input type="hidden" name="filter_value" value="{{ request('filter_value') }}"> @endif
                            @if(request('status_pengajuan')) <input type="hidden" name="status_pengajuan" value="{{ request('status_pengajuan') }}"> @endif
                            <label for="status_jadwal" class="text-xs font-semibold text-slate-550 whitespace-nowrap">Filter Status:</label>
                            <select name="status_jadwal" id="status-jadwal-filter" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white pl-3 pr-8 py-1.5 text-xs text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all cursor-pointer">
                                <option value="all">Semua Status</option>
                                <option value="validated" {{ $statusJadwal === 'validated' ? 'selected' : '' }}>Terjadwal</option>
                                <option value="completed" {{ $statusJadwal === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="canceled" {{ $statusJadwal === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($scheduledBimbinganGrouped as $key => $group)
                        @php
                            $firstBimbingan = $group->first();
                            $isGroup = $firstBimbingan->group_key !== null;
                            $bimbingan = $firstBimbingan;

                            $cancelLinks = $group->map(function($item) {
                                return $item->whatsapp_cancel_link;
                            })->filter()->values()->toArray();
                        @endphp
                        <div class="px-8 py-5 hover:bg-slate-50/60 transition-colors" x-data="{ showDetails: false, showMembers: false }">
                            <div class="flex flex-col gap-4">
                                <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                    @if($isGroup)
                                        <div class="flex items-center gap-3 w-48 shrink-0">
                                            <div class="w-10 h-10 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center font-bold border border-indigo-100/50">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                                </svg>
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-bold text-slate-800 text-sm">Bimbingan Kelompok</h4>
                                                <p class="text-xs text-indigo-600 font-semibold mt-0.5">{{ $group->count() }} Mahasiswa</p>
                                            </div>
                                        </div>
                                    @else
                                        <div class="flex items-center gap-3 w-48 shrink-0">
                                            <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                                {{ substr($bimbingan->mahasiswa->name, 0, 1) }}
                                            </div>
                                            <div class="min-w-0">
                                                <h4 class="font-semibold text-slate-800 text-sm truncate">{{ $bimbingan->mahasiswa->name }}</h4>
                                                <p class="text-xs text-slate-500">Semester {{ $bimbingan->semester }}</p>
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
                                                Status:
                                                @switch($bimbingan->status)
                                                    @case('pending')
                                                        <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">Pending</span>
                                                        @break
                                                    @case('validated')
                                                        <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">Terjadwal</span>
                                                        @break
                                                    @case('revised')
                                                        <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">Tolak / Revisi</span>
                                                        @break
                                                    @case('completed')
                                                        <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200">Selesai</span>
                                                        @break
                                                    @case('canceled')
                                                        <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 text-[10px] font-bold border border-slate-200">Dibatalkan</span>
                                                        @break
                                                    @default
                                                        <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 text-[10px] font-bold border border-slate-200">{{ ucfirst($bimbingan->status) }}</span>
                                                @endswitch
                                            </span>
                                        </div>
                                    </div>
                                    <div class="flex items-center justify-end sm:flex-shrink-0">
                                        <button @click="showDetails = !showDetails" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all focus:outline-none">
                                            <span x-text="showDetails ? 'Sembunyikan' : 'Detail Sesi'"></span>
                                            <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showDetails ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                                <div x-show="showDetails" x-collapse class="mt-2 border-t border-slate-100 pt-4 space-y-4" x-cloak>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-600 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50">
                                        @if(!$isGroup)
                                            <div>
                                                <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Informasi Mahasiswa</p>
                                                <p class="font-medium text-slate-800">{{ $bimbingan->mahasiswa->name }}</p>
                                                <p class="mt-0.5">NIM: {{ $bimbingan->mahasiswa->identifier }}</p>
                                                <p class="mt-0.5">Program Studi: {{ optional($bimbingan->mahasiswa->programStudi)->nama ?? 'Program Studi belum terdaftar' }}</p>
                                            </div>
                                        @else
                                            <div>
                                                <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Tipe Bimbingan</p>
                                                <p class="font-medium text-slate-800">Bimbingan Kelompok</p>
                                                <p class="mt-1">Jumlah: {{ $group->count() }} Mahasiswa</p>
                                                <button @click="showMembers = !showMembers" class="text-[10px] text-indigo-600 hover:text-indigo-800 font-semibold flex items-center gap-0.5 mt-2 focus:outline-none transition-colors">
                                                    <span x-text="showMembers ? 'Sembunyikan Anggota' : 'Lihat Anggota Kelompok'"></span>
                                                    <svg class="w-2.5 h-2.5 transition-transform duration-200" :class="showMembers ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                    </svg>
                                                </button>
                                            </div>
                                        @endif
                                        <div>
                                            <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Catatan / Instruksi</p>
                                            <p class="leading-relaxed bg-white p-2.5 rounded-xl border border-slate-100 text-slate-800">
                                                {{ $bimbingan->catatan && $bimbingan->catatan !== '-' ? $bimbingan->catatan : 'Tidak ada catatan atau instruksi khusus.' }}
                                            </p>
                                        </div>
                                    </div>

                                    @if($isGroup)
                                        <div x-show="showMembers" x-collapse class="mt-2 pl-4 border-l-2 border-indigo-200 space-y-2 bg-slate-50/40 p-4 rounded-2xl border border-slate-100" x-cloak>
                                            <h5 class="text-xs font-bold text-slate-700 mb-2 uppercase tracking-wider">Daftar Anggota Bimbingan</h5>
                                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3">
                                                @foreach($group as $item)
                                                    <div class="flex items-center justify-between text-xs bg-white p-3 rounded-xl border border-slate-100 shadow-[0_2px_8px_rgba(0,0,0,0.02)] hover:border-indigo-100 transition-colors">
                                                        <div class="min-w-0 pr-2">
                                                            <p class="font-semibold text-slate-800 truncate">{{ $item->mahasiswa->name }}</p>
                                                            <p class="text-[10px] text-slate-500 mt-0.5">{{ $item->mahasiswa->identifier }} @if($item->mahasiswa->programStudi) • {{ $item->mahasiswa->programStudi->nama }} @endif</p>
                                                        </div>
                                                        @if(($item->status === 'validated' && $item->whatsapp_link) || ($item->status === 'canceled' && $item->whatsapp_cancel_link))
                                                            <a href="{{ $item->status === 'canceled' ? $item->whatsapp_cancel_link : $item->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm flex-shrink-0">
                                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                                </svg>
                                                                <span>Hubungi Mahasiswa</span>
                                                            </a>
                                                        @endif
                                                    </div>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif

                                    <div class="flex flex-wrap items-center gap-3 justify-end pt-2 border-t border-slate-100">
                                        @if(!$isGroup && (($bimbingan->status === 'validated' && $bimbingan->whatsapp_link) || ($bimbingan->status === 'canceled' && $bimbingan->whatsapp_cancel_link)))
                                            <a href="{{ $bimbingan->status === 'canceled' ? $bimbingan->whatsapp_cancel_link : $bimbingan->whatsapp_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-2 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-4 h-4">
                                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                </svg>
                                                Hubungi Mahasiswa
                                            </a>
                                        @endif

                                        @if($bimbingan->status === 'validated')
                                            <form action="{{ route('bimbingan.dosen.destroy', $bimbingan->id) }}" method="POST" data-confirm data-confirm-title="Batalkan Jadwal Bimbingan" data-confirm-message="Apakah Anda yakin ingin membatalkan jadwal bimbingan ini? Tindakan ini tidak dapat dibatalkan." data-confirm-approve="Ya, Batalkan" data-confirm-cancel="Tidak" data-confirm-variant="danger" data-confirm-wa-links='{{ json_encode($cancelLinks) }}' class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-red-600 text-white text-xs font-bold hover:bg-red-700 transition-colors shadow-sm focus:outline-none">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    Batalkan Jadwal
                                                </button>
                                            </form>
                                        @endif
                                    </div>

                                    @if($bimbingan->status === 'validated')
                                        <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                                            <h4 class="text-sm font-semibold text-slate-800 mb-3">Catat Laporan Selesai @if($isGroup) Kelompok @endif</h4>
                                            <form action="{{ route('bimbingan.dosen.report', $bimbingan->id) }}" method="POST" enctype="multipart/form-data" class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                                @csrf
                                                <div class="sm:col-span-2">
                                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Ringkasan Penyelesaian</label>
                                                    <textarea name="resolution" rows="3" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500" placeholder="Catat hasil diskusi kelompok bimbingan..."></textarea>
                                                </div>
                                                <div>
                                                    <label class="block text-sm font-semibold text-slate-700 mb-2">Foto Kegiatan @if($isGroup) (Satu foto untuk seluruh kelompok) @endif</label>
                                                    <input type="file" name="activity_photo" accept="image/*" class="w-full text-sm text-slate-700" />
                                                </div>
                                                <div class="sm:col-span-2 flex justify-end">
                                                    <button type="submit" class="inline-flex items-center px-5 py-2.5 text-sm font-bold text-white bg-emerald-600 rounded-xl hover:bg-emerald-700 hover:scale-105 transition-all shadow-md shadow-emerald-100">Simpan Laporan @if($isGroup) Kelompok @endif</button>
                                                </div>
                                            </form>
                                        </div>
                                    @endif

                                    @if($bimbingan->status === 'completed')
                                        <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
                                            <h4 class="text-sm font-bold text-emerald-800 mb-2">Laporan Bimbingan Selesai @if($isGroup) Kelompok @endif</h4>
                                            <p class="text-sm text-slate-700 mb-3 leading-relaxed">{{ $bimbingan->resolution }}</p>
                                            
                                            <div class="flex flex-wrap gap-2 mb-3">
                                                @if($bimbingan->activity_photo_path)
                                                    <a href="{{ Storage::url($bimbingan->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100 shadow-sm transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                        Lihat Foto Kegiatan
                                                    </a>
                                                @endif
                                                
                                                @if(!$isGroup)
                                                    <a href="{{ route('bimbingan.dosen.print', $bimbingan->id) }}" target="_blank" class="inline-flex items-center gap-2 px-4 py-2.5 text-xs font-bold text-indigo-700 bg-white border border-indigo-200 rounded-xl hover:bg-indigo-50 shadow-sm transition-all">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                        Ekspor PDF
                                                    </a>
                                                @endif
                                            </div>

                                            @if($isGroup)
                                                <div class="mt-3 border-t border-emerald-100 pt-3">
                                                    <h5 class="text-xs font-bold text-emerald-800 mb-2 uppercase tracking-wider">Ekspor PDF per Anggota Kelompok:</h5>
                                                    <div class="flex flex-wrap gap-2">
                                                        @foreach($group as $member)
                                                            <a href="{{ route('bimbingan.dosen.print', $member->id) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 text-xs bg-white text-slate-700 hover:text-indigo-700 border border-slate-200 hover:border-indigo-200 rounded-xl shadow-sm transition-all font-semibold">
                                                                <svg class="w-3.5 h-3.5 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                                                                {{ $member->mahasiswa->name }}
                                                            </a>
                                                        @endforeach
                                                    </div>
                                                </div>
                                            @endif
                                        </div>
                                    @endif
                                </div>
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
                <div class="px-8 py-5 border-b border-slate-100 bg-slate-50/50 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-slate-800">Daftar Pengajuan Mahasiswa</h3>
                        <p class="text-sm text-slate-500 mt-1">Semua permintaan bimbingan yang diajukan mahasiswa Anda.</p>
                    </div>
                    <div class="flex-shrink-0">
                        <form method="GET" action="{{ route('bimbingan.dosen.index') }}" class="flex items-center gap-2">
                            @if(request('filter_type')) <input type="hidden" name="filter_type" value="{{ request('filter_type') }}"> @endif
                            @if(request('filter_value')) <input type="hidden" name="filter_value" value="{{ request('filter_value') }}"> @endif
                            @if(request('status_jadwal')) <input type="hidden" name="status_jadwal" value="{{ request('status_jadwal') }}"> @endif
                            <label for="status_pengajuan" class="text-xs font-semibold text-slate-550 whitespace-nowrap">Filter Status:</label>
                            <select name="status_pengajuan" id="status-pengajuan-filter" onchange="this.form.submit()" class="rounded-xl border border-slate-200 bg-white pl-3 pr-8 py-1.5 text-xs text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-all cursor-pointer">
                                <option value="all">Semua Status</option>
                                <option value="pending" {{ $statusPengajuan === 'pending' ? 'selected' : '' }}>Pending</option>
                                <option value="validated" {{ $statusPengajuan === 'validated' ? 'selected' : '' }}>Disetujui</option>
                                <option value="revised" {{ $statusPengajuan === 'revised' ? 'selected' : '' }}>Ditolak</option>
                                <option value="completed" {{ $statusPengajuan === 'completed' ? 'selected' : '' }}>Selesai</option>
                                <option value="canceled" {{ $statusPengajuan === 'canceled' ? 'selected' : '' }}>Dibatalkan</option>
                            </select>
                        </form>
                    </div>
                </div>
                <div class="divide-y divide-slate-100">
                    @forelse ($pendingRequests as $bimbingan)
                    <div class="px-8 py-5 hover:bg-slate-50/60 transition-colors" x-data="{ showDetails: false }">
                        <div class="flex flex-col gap-4">
                            <div class="flex flex-col sm:flex-row sm:items-center gap-4">
                                <div class="flex items-center gap-3 w-48 shrink-0">
                                    <div class="w-10 h-10 rounded-xl bg-slate-100 flex items-center justify-center text-slate-500 font-bold">
                                        {{ substr($bimbingan->mahasiswa->name, 0, 1) }}
                                    </div>
                                    <div class="min-w-0">
                                        <h4 class="font-semibold text-slate-800 text-sm truncate">{{ $bimbingan->mahasiswa->name }}</h4>
                                        <p class="text-xs text-slate-500">Semester {{ $bimbingan->semester }}</p>
                                    </div>
                                </div>
                                <div class="min-w-0 flex-1 pl-0 sm:pl-4 sm:border-l sm:border-slate-100">
                                    <h4 class="font-semibold text-slate-800 text-sm">{{ $bimbingan->topik }}</h4>
                                    <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                            {{ $bimbingan->tanggal->format('d M Y') }}
                                        </span>
                                        <span class="inline-flex items-center text-xs text-slate-500">
                                            <svg class="w-3.5 h-3.5 mr-1 text-slate-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            Status:
                                            @switch($bimbingan->status)
                                                @case('pending')
                                                    <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-amber-50 text-amber-700 text-[10px] font-bold border border-amber-200">Pending</span>
                                                    @break
                                                @case('validated')
                                                    <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-emerald-50 text-emerald-700 text-[10px] font-bold border border-emerald-200">Disetujui</span>
                                                    @break
                                                @case('revised')
                                                    <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-rose-50 text-rose-700 text-[10px] font-bold border border-rose-200">Tolak / Revisi</span>
                                                    @break
                                                @case('completed')
                                                    <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-indigo-50 text-indigo-700 text-[10px] font-bold border border-indigo-200">Selesai</span>
                                                    @break
                                                @case('canceled')
                                                    <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 text-[10px] font-bold border border-slate-200">Dibatalkan</span>
                                                    @break
                                                @default
                                                    <span class="ml-1.5 inline-flex items-center gap-1 px-2.5 py-0.5 rounded-full bg-slate-50 text-slate-700 text-[10px] font-bold border border-slate-200">{{ ucfirst($bimbingan->status) }}</span>
                                            @endswitch
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center justify-end sm:flex-shrink-0">
                                    <button @click="showDetails = !showDetails" class="inline-flex items-center gap-1.5 px-4 py-2 text-xs font-bold rounded-xl bg-slate-100 text-slate-700 hover:bg-indigo-50 hover:text-indigo-600 transition-all focus:outline-none">
                                        <span x-text="showDetails ? 'Sembunyikan' : 'Detail Sesi'"></span>
                                        <svg class="w-3.5 h-3.5 transition-transform duration-200" :class="showDetails ? 'rotate-180' : ''" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div x-show="showDetails" x-collapse class="mt-2 border-t border-slate-100 pt-4 space-y-4" x-cloak>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-xs text-slate-600 bg-slate-50/50 p-4 rounded-2xl border border-slate-100/50">
                                    <div>
                                        <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Informasi Mahasiswa</p>
                                        <p class="font-medium text-slate-800">{{ $bimbingan->mahasiswa->name }}</p>
                                        <p class="mt-0.5">NIM: {{ $bimbingan->mahasiswa->identifier }}</p>
                                        <p class="mt-0.5">Program Studi: {{ optional($bimbingan->mahasiswa->programStudi)->nama ?? 'Program Studi belum terdaftar' }}</p>
                                        <p class="mt-1 font-semibold text-slate-700">Tipe Pengajuan: <span class="text-indigo-600">{{ $bimbingan->tipe_pengajuan_label }}</span></p>
                                    </div>
                                    <div>
                                        <p class="font-bold text-slate-700 mb-1 uppercase tracking-wider text-[10px]">Catatan Pengajuan / Dokumen</p>
                                        <p class="leading-relaxed bg-white p-2.5 rounded-xl border border-slate-100 text-slate-800">
                                            {{ $bimbingan->catatan && $bimbingan->catatan !== '-' ? $bimbingan->catatan : 'Tidak ada catatan khusus.' }}
                                        </p>
                                        @if($bimbingan->document_path)
                                            <div class="mt-2 text-right">
                                                <a href="{{ Storage::url($bimbingan->document_path) }}" target="_blank" class="inline-flex items-center gap-1 font-semibold text-indigo-600 hover:text-indigo-800">
                                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                                    Lihat Dokumen Pendukung
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                @if($bimbingan->status === 'pending')
                                <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                                    <h4 class="text-sm font-semibold text-slate-800 mb-3">Tindakan Pengajuan</h4>
                                    <form action="{{ route('bimbingan.dosen.update', $bimbingan->id) }}" method="POST" class="space-y-4">
                                        @csrf
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Keputusan</label>
                                            <select name="status" required class="w-full rounded-2xl border border-slate-200 bg-white pl-4 pr-10 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                                                <option value="validated">Setujui</option>
                                                <option value="revised">Tolak / Revisi</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-slate-700 mb-2">Catatan untuk Mahasiswa</label>
                                            <textarea name="catatan" rows="3" required class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm text-slate-700 shadow-sm focus:border-indigo-500 focus:ring-indigo-500"></textarea>
                                        </div>
                                        <div class="text-right">
                                            <button type="submit" class="inline-flex items-center px-4 py-2 text-sm font-bold text-white bg-indigo-600 rounded-xl hover:bg-indigo-700 transition-all shadow-md shadow-indigo-100">Simpan Keputusan</button>
                                        </div>
                                    </form>
                                </div>
                                @endif

                                @if($bimbingan->status === 'validated')
                                    <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50/50 p-5 shadow-sm">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h4 class="text-sm font-bold text-emerald-800">Pengajuan Disetujui</h4>
                                                <p class="text-xs text-slate-600 mt-1">Status pengajuan telah diperbarui menjadi disetujui. Sesi bimbingan aktif dapat Anda kelola di daftar bimbingan terjadwal di atas.</p>
                                                @if($bimbingan->catatan && $bimbingan->catatan !== '-')
                                                    <p class="text-xs text-slate-700 mt-2 font-medium">Catatan Dosen: <span class="italic text-slate-600">"{{ $bimbingan->catatan }}"</span></p>
                                                @endif
                                            </div>
                                            @if($bimbingan->whatsapp_validation_link)
                                                <div class="flex-shrink-0">
                                                    <a href="{{ $bimbingan->whatsapp_validation_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors border border-emerald-500 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                        </svg>
                                                        <span>Hubungi Mahasiswa</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($bimbingan->status === 'revised')
                                    <div class="mt-4 rounded-3xl border border-rose-200 bg-rose-50/50 p-5 shadow-sm">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h4 class="text-sm font-bold text-rose-800">Pengajuan Ditolak / Revisi</h4>
                                                <p class="text-xs text-slate-600 mt-1">Status pengajuan telah diubah menjadi ditolak atau memerlukan revisi.</p>
                                                @if($bimbingan->catatan && $bimbingan->catatan !== '-')
                                                    <p class="text-xs text-slate-700 mt-2 font-medium">Catatan Dosen / Alasan: <span class="italic text-slate-600">"{{ $bimbingan->catatan }}"</span></p>
                                                @endif
                                            </div>
                                            @if($bimbingan->whatsapp_validation_link)
                                                <div class="flex-shrink-0">
                                                    <a href="{{ $bimbingan->whatsapp_validation_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-semibold hover:bg-emerald-700 transition-colors border border-emerald-500 shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                        </svg>
                                                        <span>Hubungi Mahasiswa</span>
                                                    </a>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endif

                                @if($bimbingan->status === 'canceled')
                                    <div class="mt-4 rounded-3xl border border-slate-200 bg-slate-50 p-5 shadow-sm">
                                        <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                            <div>
                                                <h4 class="text-sm font-bold text-slate-800">Jadwal Bimbingan Dibatalkan</h4>
                                                <p class="text-xs text-slate-500 mt-1">Pertemuan bimbingan akademik ini telah dibatalkan.</p>
                                            </div>
                                            @if($bimbingan->whatsapp_cancel_link)
                                                <div class="flex-shrink-0">
                                                    <a href="{{ $bimbingan->whatsapp_cancel_link }}" target="_blank" rel="noopener noreferrer" class="inline-flex items-center gap-1.5 px-3 py-1.5 rounded-xl bg-emerald-600 text-white text-xs font-bold hover:bg-emerald-700 transition-colors shadow-sm">
                                                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="w-3.5 h-3.5">
                                                            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413z"/>
                                                    </svg>
                                                    <span>Hubungi Mahasiswa</span>
                                                </a>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            @endif

                            @if($bimbingan->status === 'completed')
                                <div class="mt-4 rounded-3xl border border-emerald-200 bg-emerald-50 p-5 shadow-sm">
                                    <h4 class="text-sm font-semibold text-emerald-800 mb-2">Laporan Bimbingan Selesai</h4>
                                    <p class="text-sm text-slate-700 mb-3">{{ $bimbingan->resolution }}</p>
                                    @if($bimbingan->activity_photo_path)
                                        <a href="{{ Storage::url($bimbingan->activity_photo_path) }}" target="_blank" class="inline-flex items-center gap-2 px-3 py-2 text-sm font-semibold text-emerald-700 bg-white border border-emerald-200 rounded-xl hover:bg-emerald-100">Lihat Foto Kegiatan</a>
                                    @endif
                                </div>
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


</x-app-layout>
