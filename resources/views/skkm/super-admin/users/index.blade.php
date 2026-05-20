<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between gap-3">
            <flux:heading size="xl" level="1">CRUD User</flux:heading>
            <flux:button :href="route('admin.users.create')" variant="primary" icon="plus">
                Tambah User
            </flux:button>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if (session('success'))
            <div class="rounded-xl border border-emerald-200 bg-emerald-50 px-4 py-3 text-sm font-medium text-emerald-700">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="rounded-xl border border-rose-200 bg-rose-50 px-4 py-3 text-sm font-medium text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <div class="rounded-2xl border border-zinc-200/70 bg-white p-5 shadow-sm">
            <form method="GET" action="{{ route('admin.users.index') }}" class="flex flex-col gap-3 sm:flex-row sm:items-end">
                <div class="w-full sm:max-w-xs">
                    <flux:field>
                        <flux:label for="role">Filter Role</flux:label>
                        <flux:select id="role" name="role">
                            <flux:select.option value="">Semua Role</flux:select.option>
                            @foreach ($roleOptions as $value => $label)
                                <flux:select.option value="{{ $value }}" :selected="$selectedRole === $value">
                                    {{ $label }}
                                </flux:select.option>
                            @endforeach
                        </flux:select>
                    </flux:field>
                </div>

                <flux:button type="submit" variant="filled">Terapkan</flux:button>

                @if ($selectedRole)
                    <flux:button :href="route('admin.users.index')" variant="ghost">Reset</flux:button>
                @endif
            </form>
        </div>

        <div class="rounded-2xl border border-zinc-200/70 bg-white p-2 shadow-sm">
            <flux:table :paginate="$users">
                <flux:table.columns>
                    <flux:table.column>Nama</flux:table.column>
                    <flux:table.column>Role</flux:table.column>
                    <flux:table.column>Program Studi</flux:table.column>
                    <flux:table.column>Identifier</flux:table.column>
                    <flux:table.column>Status</flux:table.column>
                    <flux:table.column class="text-right">Aksi</flux:table.column>
                </flux:table.columns>

                <flux:table.rows>
                    @forelse ($users as $user)
                        <flux:table.row>
                            <flux:table.cell>
                                <div class="font-medium text-zinc-800">{{ $user->name }}</div>
                                <div class="text-xs text-zinc-500">{{ $user->email }}</div>
                            </flux:table.cell>
                            <flux:table.cell>{{ $roleOptions[$user->resolvedSkkmRole()] ?? strtoupper($user->resolvedSkkmRole()) }}</flux:table.cell>
                            <flux:table.cell>{{ $user->programStudi?->nama ?? '-' }}</flux:table.cell>
                            <flux:table.cell>{{ $user->identifier ?? '-' }}</flux:table.cell>
                            <flux:table.cell>
                                @if ($user->is_active)
                                    <flux:badge color="emerald">Aktif</flux:badge>
                                @else
                                    <flux:badge color="zinc">Nonaktif</flux:badge>
                                @endif
                            </flux:table.cell>
                            <flux:table.cell>
                                <div class="flex justify-end gap-2">
                                    <flux:button :href="route('admin.users.edit', $user)" size="sm">Edit</flux:button>
                                    <form action="{{ route('admin.users.destroy', $user) }}" method="POST" onsubmit="return confirm('Hapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <flux:button type="submit" size="sm" variant="danger">Hapus</flux:button>
                                    </form>
                                </div>
                            </flux:table.cell>
                        </flux:table.row>
                    @empty
                        <flux:table.row>
                            <flux:table.cell colspan="6" class="py-8 text-center text-zinc-500">
                                Belum ada data user.
                            </flux:table.cell>
                        </flux:table.row>
                    @endforelse
                </flux:table.rows>
            </flux:table>
        </div>
    </div>
</x-app-layout>
