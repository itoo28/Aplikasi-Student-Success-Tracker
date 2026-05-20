<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <span class="inline-flex items-center rounded-xl bg-indigo-100 px-3 py-1 text-xs font-bold uppercase tracking-wide text-indigo-700">
                Super Admin
            </span>
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
                {{ __('Dashboard Kontrol') }}
            </h2>
        </div>
    </x-slot>

    <div class="relative isolate overflow-hidden rounded-[2rem] border border-indigo-100/80 bg-gradient-to-br from-indigo-50 via-sky-50 to-cyan-50 p-6 sm:p-8">
        <div class="pointer-events-none absolute -left-20 top-8 h-48 w-48 rounded-full bg-indigo-300/30 blur-3xl"></div>
        <div class="pointer-events-none absolute -right-24 top-16 h-56 w-56 rounded-full bg-cyan-300/35 blur-3xl"></div>
        <div class="pointer-events-none absolute -bottom-20 left-1/3 h-56 w-56 rounded-full bg-emerald-200/35 blur-3xl"></div>

        <div class="relative space-y-8">
            <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
                <div class="rounded-3xl p-6 bg-gradient-to-br from-indigo-600 to-violet-600 text-white shadow-[0_14px_35px_rgb(79,70,229,0.35)]">
                    <p class="text-sm font-semibold text-indigo-100">Total User</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalUsers }}</h3>
                    <p class="text-xs text-indigo-100 mt-2">{{ $totalStudents }} mahasiswa &middot; {{ $totalStaff }} staf</p>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-emerald-500 to-teal-500 text-white shadow-[0_14px_35px_rgb(16,185,129,0.30)]">
                    <p class="text-sm font-semibold text-emerald-100">Master Fakultas</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalFakultas }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-sky-500 to-cyan-500 text-white shadow-[0_14px_35px_rgb(14,165,233,0.30)]">
                    <p class="text-sm font-semibold text-sky-100">Master Program Studi</p>
                    <h3 class="mt-2 text-3xl font-extrabold">{{ $totalProgramStudi }}</h3>
                </div>
                <div class="rounded-3xl p-6 bg-gradient-to-br from-amber-500 to-orange-500 text-white shadow-[0_14px_35px_rgb(245,158,11,0.30)]">
                    <p class="text-sm font-semibold text-amber-100">Antrean Verifikasi</p>
                    <h3 class="mt-2 text-2xl font-extrabold">{{ $pendingDosen }}</h3>
                    <p class="text-xs text-amber-100 mt-2">Menunggu verifikasi Dosen PA</p>
                </div>
            </div>

            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <a href="{{ route('admin.users.index') }}" class="group rounded-3xl p-6 border border-white/80 bg-white/85 backdrop-blur-sm shadow-[0_8px_30px_rgb(99,102,241,0.14)] hover:-translate-y-0.5 hover:shadow-[0_14px_35px_rgb(79,70,229,0.18)] transition-all">
                    <div class="inline-flex p-3 rounded-2xl bg-indigo-100 text-indigo-700 mb-4">
                        <i data-lucide="users-cog" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Kelola User</h3>
                    <p class="text-sm text-slate-600 mt-1">Kelola semua akun: mahasiswa, dosen PA, kaprodi, kemahasiswaan.</p>
                </a>
                <a href="{{ route('admin.fakultas.index') }}" class="group rounded-3xl p-6 border border-white/80 bg-white/85 backdrop-blur-sm shadow-[0_8px_30px_rgb(45,212,191,0.14)] hover:-translate-y-0.5 hover:shadow-[0_14px_35px_rgb(20,184,166,0.18)] transition-all">
                    <div class="inline-flex p-3 rounded-2xl bg-emerald-100 text-emerald-700 mb-4">
                        <i data-lucide="building-2" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Kelola Fakultas</h3>
                    <p class="text-sm text-slate-600 mt-1">Master fakultas untuk kebutuhan mapping prodi dan laporan SKKM.</p>
                </a>
                <a href="{{ route('admin.program-studi.index') }}" class="group rounded-3xl p-6 border border-white/80 bg-white/85 backdrop-blur-sm shadow-[0_8px_30px_rgb(14,165,233,0.14)] hover:-translate-y-0.5 hover:shadow-[0_14px_35px_rgb(14,165,233,0.20)] transition-all">
                    <div class="inline-flex p-3 rounded-2xl bg-sky-100 text-sky-700 mb-4">
                        <i data-lucide="book-copy" class="w-5 h-5"></i>
                    </div>
                    <h3 class="font-bold text-slate-800">Kelola Program Studi</h3>
                    <p class="text-sm text-slate-600 mt-1">Master prodi dinamis yang terhubung ke akun pengguna sistem SKKM.</p>
                </a>
            </div>

            <div class="rounded-3xl border border-indigo-100/80 bg-white/90 backdrop-blur-sm shadow-[0_8px_30px_rgb(37,99,235,0.12)] overflow-hidden">
                <div class="px-8 py-6 border-b border-indigo-100 bg-gradient-to-r from-indigo-100/80 via-sky-100/70 to-cyan-100/70">
                    <h3 class="text-lg font-bold text-slate-800">User Terbaru</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-sm text-left text-slate-600">
                        <thead class="text-xs text-indigo-700 uppercase bg-indigo-50/70">
                            <tr>
                                <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Email</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Role</th>
                                <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-indigo-100/60">
                            @forelse ($recentUsers as $user)
                                <tr class="hover:bg-indigo-50/40 transition-colors">
                                    <td class="px-6 py-4 font-semibold text-slate-800">{{ $user->name }}</td>
                                    <td class="px-6 py-4">{{ $user->email }}</td>
                                    <td class="px-6 py-4">{{ str_replace('_', ' ', strtoupper($user->resolvedSkkmRole())) }}</td>
                                    <td class="px-6 py-4">
                                        @if($user->is_active)
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-100 text-emerald-700">Aktif</span>
                                        @else
                                            <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-8 text-center text-slate-500">Belum ada data user.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
