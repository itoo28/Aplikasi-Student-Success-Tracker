<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-2xl text-slate-800 leading-tight">
            {{ __('Super Admin Dashboard') }}
        </h2>
    </x-slot>

    <div class="space-y-8">
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-sm font-semibold text-slate-500">Total User</p>
                <h3 class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalUsers }}</h3>
                <p class="text-xs text-slate-500 mt-2">{{ $totalStudents }} mahasiswa · {{ $totalStaff }} staf</p>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-sm font-semibold text-slate-500">Master Fakultas</p>
                <h3 class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalFakultas }}</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-sm font-semibold text-slate-500">Master Program Studi</p>
                <h3 class="mt-2 text-3xl font-extrabold text-slate-800">{{ $totalProgramStudi }}</h3>
            </div>
            <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)]">
                <p class="text-sm font-semibold text-slate-500">Antrean Verifikasi</p>
                <h3 class="mt-2 text-2xl font-extrabold text-slate-800">{{ $pendingDosen }}</h3>
                <p class="text-xs text-slate-500 mt-2">Menunggu verifikasi Dosen PA</p>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
            <a href="{{ route('admin.users.index') }}" class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:border-indigo-200 hover:shadow-[0_8px_30px_rgb(79,70,229,0.15)] transition-all">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-50 text-indigo-600 mb-4">
                    <i data-lucide="users-cog" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-800">Kelola User</h3>
                <p class="text-sm text-slate-500 mt-1">CRUD semua akun: mahasiswa, dosen PA, kaprodi, kemahasiswaan.</p>
            </a>
            <a href="{{ route('admin.fakultas.index') }}" class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:border-indigo-200 hover:shadow-[0_8px_30px_rgb(79,70,229,0.15)] transition-all">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-50 text-indigo-600 mb-4">
                    <i data-lucide="building-2" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-800">Kelola Fakultas</h3>
                <p class="text-sm text-slate-500 mt-1">Master fakultas untuk kebutuhan mapping prodi dan laporan SKKM.</p>
            </a>
            <a href="{{ route('admin.program-studi.index') }}" class="bg-white rounded-3xl p-6 border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] hover:border-indigo-200 hover:shadow-[0_8px_30px_rgb(79,70,229,0.15)] transition-all">
                <div class="inline-flex p-3 rounded-2xl bg-indigo-50 text-indigo-600 mb-4">
                    <i data-lucide="book-copy" class="w-5 h-5"></i>
                </div>
                <h3 class="font-bold text-slate-800">Kelola Program Studi</h3>
                <p class="text-sm text-slate-500 mt-1">Master prodi dinamis yang terhubung ke akun pengguna sistem SKKM.</p>
            </a>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="px-8 py-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800">User Terbaru</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Email</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Role</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($recentUsers as $user)
                            <tr>
                                <td class="px-6 py-4 font-semibold text-slate-800">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ str_replace('_', ' ', strtoupper($user->resolvedSkkmRole())) }}</td>
                                <td class="px-6 py-4">
                                    @if($user->is_active)
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700">Aktif</span>
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
</x-app-layout>
