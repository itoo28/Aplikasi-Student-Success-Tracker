<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between w-full">
            <h2 class="font-semibold text-2xl text-slate-800 leading-tight">CRUD User</h2>
            <a href="{{ route('admin.users.create') }}" class="inline-flex items-center px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-sm font-semibold shadow-sm">
                <i data-lucide="plus" class="w-4 h-4 mr-2"></i> Tambah User
            </a>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="bg-emerald-50 border border-emerald-100 text-emerald-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="bg-rose-50 border border-rose-100 text-rose-700 px-4 py-3 rounded-xl text-sm font-semibold">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] p-6">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col sm:flex-row gap-3 sm:items-end">
                <div class="w-full sm:max-w-xs">
                    <label for="role" class="block text-sm font-semibold text-slate-700 mb-2">Filter Role</label>
                    <select id="role" name="role" class="block w-full rounded-xl border-slate-200 bg-slate-50 py-2.5 px-3 text-sm focus:border-indigo-500 focus:ring-indigo-500">
                        <option value="">Semua Role</option>
                        @foreach ($roleOptions as $value => $label)
                            <option value="{{ $value }}" @selected($selectedRole === $value)>{{ $label }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="inline-flex px-4 py-2.5 rounded-xl bg-slate-900 text-white text-sm font-semibold hover:bg-slate-700">Terapkan</button>
                @if ($selectedRole)
                    <a href="{{ route('admin.users.index') }}" class="inline-flex px-4 py-2.5 rounded-xl border border-slate-200 text-slate-700 text-sm font-semibold hover:bg-slate-50">Reset</a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-3xl border border-slate-100 shadow-[0_8px_30px_rgb(0,0,0,0.04)] overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left text-slate-500">
                    <thead class="text-xs text-slate-400 uppercase bg-slate-50">
                        <tr>
                            <th class="px-6 py-4 font-semibold tracking-wider">Nama</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Role</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Program Studi</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Identifier</th>
                            <th class="px-6 py-4 font-semibold tracking-wider">Status</th>
                            <th class="px-6 py-4 font-semibold tracking-wider text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($users as $user)
                            <tr class="hover:bg-slate-50/70 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-semibold text-slate-800">{{ $user->name }}</div>
                                    <div class="text-xs text-slate-500">{{ $user->email }}</div>
                                </td>
                                <td class="px-6 py-4">{{ $roleOptions[$user->resolvedSkkmRole()] ?? strtoupper($user->resolvedSkkmRole()) }}</td>
                                <td class="px-6 py-4">{{ $user->programStudi?->nama ?? '-' }}</td>
                                <td class="px-6 py-4">{{ $user->identifier ?? '-' }}</td>
                                <td class="px-6 py-4">
                                    @if ($user->is_active)
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-50 text-emerald-700">Aktif</span>
                                    @else
                                        <span class="inline-flex px-2.5 py-1 rounded-lg text-xs font-bold bg-slate-100 text-slate-600">Nonaktif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="{{ route('admin.users.edit', $user) }}" class="inline-flex px-3 py-2 rounded-lg bg-indigo-50 text-indigo-700 text-xs font-semibold hover:bg-indigo-100">Edit</a>
                                        <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex px-3 py-2 rounded-lg bg-rose-50 text-rose-700 text-xs font-semibold hover:bg-rose-100">Hapus</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-6 py-8 text-center text-slate-500">Belum ada data user.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 border-t border-slate-100">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
