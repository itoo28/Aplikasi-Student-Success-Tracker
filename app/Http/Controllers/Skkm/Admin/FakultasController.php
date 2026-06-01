<?php

namespace App\Http\Controllers\Skkm\Admin;

use App\Http\Controllers\Controller;
use App\Models\Fakultas;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class FakultasController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $search = $search !== '' ? $search : null;

        $fakultas = Fakultas::query()
            ->withCount('programStudis')
            ->when($search, function (Builder $query, string $searchTerm) {
                $safeSearchTerm = addcslashes($searchTerm, '\\%_');
                $query->where(function (Builder $innerQuery) use ($safeSearchTerm) {
                    $innerQuery
                        ->where('nama', 'like', '%' . $safeSearchTerm . '%')
                        ->orWhere('kode', 'like', '%' . $safeSearchTerm . '%');
                });
            })
            ->orderBy('nama')
            ->paginate(15)
            ->withQueryString();

        return view('skkm.super-admin.fakultas.index', compact('fakultas', 'search'));
    }

    public function create(): View
    {
        return view('skkm.super-admin.fakultas.form', [
            'fakultas' => new Fakultas(),
            'isEdit' => false,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateData($request);
        Fakultas::create($validated);

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil ditambahkan.');
    }

    public function edit(Fakultas $fakulta): View
    {
        return view('skkm.super-admin.fakultas.form', [
            'fakultas' => $fakulta,
            'isEdit' => true,
        ]);
    }

    public function update(Request $request, Fakultas $fakulta): RedirectResponse
    {
        $validated = $this->validateData($request, $fakulta);
        $fakulta->update($validated);

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil diperbarui.');
    }

    public function destroy(Fakultas $fakulta): RedirectResponse
    {
        if ($fakulta->programStudis()->exists()) {
            return redirect()->back()->withErrors(['delete_fakultas' => 'Fakultas tidak bisa dihapus karena masih memiliki program studi.']);
        }

        $fakulta->delete();

        return redirect()->route('admin.fakultas.index')->with('success', 'Fakultas berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?Fakultas $fakultas = null): array
    {
        return $request->validate([
            'kode' => ['required', 'string', 'max:20', Rule::unique('fakultas', 'kode')->ignore($fakultas?->id)],
            'nama' => ['required', 'string', 'max:100', Rule::unique('fakultas', 'nama')->ignore($fakultas?->id)],
            'is_active' => ['nullable', 'boolean'],
        ]);
    }
}
