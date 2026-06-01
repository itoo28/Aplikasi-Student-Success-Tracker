<?php

namespace App\Http\Controllers\Skkm;

use App\Http\Controllers\Controller;
use App\Models\PointRule;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class PointRuleController extends Controller
{
    public function index(Request $request): View
    {
        $search = trim((string) $request->query('search', ''));
        $search = $search !== '' ? $search : null;

        $unsurOptions = $this->unsurOptions();
        $selectedUnsur = $request->query('unsur');
        $selectedUnsur = is_string($selectedUnsur) && array_key_exists($selectedUnsur, $unsurOptions)
            ? $selectedUnsur
            : null;

        $statusOptions = [
            'active' => 'Aktif',
            'inactive' => 'Nonaktif',
        ];
        $selectedStatus = $request->query('status');
        $selectedStatus = is_string($selectedStatus) && array_key_exists($selectedStatus, $statusOptions)
            ? $selectedStatus
            : null;

        $pointRules = PointRule::query()
            ->when($search, function (Builder $query, string $searchTerm) {
                $safeSearchTerm = addcslashes($searchTerm, '\\%_');
                $query->where(function (Builder $innerQuery) use ($safeSearchTerm) {
                    $innerQuery
                        ->where('sub_unsur', 'like', '%'.$safeSearchTerm.'%')
                        ->orWhere('jenis_item', 'like', '%'.$safeSearchTerm.'%')
                        ->orWhere('peranan', 'like', '%'.$safeSearchTerm.'%')
                        ->orWhere('bukti_fisik_required', 'like', '%'.$safeSearchTerm.'%');
                });
            })
            ->when($selectedUnsur, fn (Builder $query, string $unsur) => $query->where('unsur', $unsur))
            ->when($selectedStatus, fn (Builder $query, string $status) => $query->where('is_active', $status === 'active'))
            ->orderBy('unsur')
            ->orderBy('sub_unsur')
            ->orderBy('jenis_item')
            ->orderBy('tingkat')
            ->orderBy('peranan')
            ->paginate(20)
            ->withQueryString();

        return view('skkm.point-rules.index', [
            'pointRules' => $pointRules,
            'search' => $search,
            'selectedUnsur' => $selectedUnsur,
            'selectedStatus' => $selectedStatus,
            'unsurOptions' => $unsurOptions,
            'statusOptions' => $statusOptions,
        ]);
    }

    public function create(): View
    {
        return $this->formView(new PointRule, false);
    }

    public function store(Request $request): RedirectResponse
    {
        PointRule::create($this->validateData($request));

        return redirect()
            ->route('skkm.point-rules.index')
            ->with('success', 'Kategori poin SKKM berhasil ditambahkan dan langsung tersedia untuk mahasiswa.');
    }

    public function edit(PointRule $pointRule): View
    {
        return $this->formView($pointRule, true);
    }

    public function update(Request $request, PointRule $pointRule): RedirectResponse
    {
        $pointRule->update($this->validateData($request, $pointRule));

        return redirect()
            ->route('skkm.point-rules.index')
            ->with('success', 'Kategori poin SKKM berhasil diperbarui.');
    }

    public function destroy(PointRule $pointRule): RedirectResponse
    {
        if ($pointRule->submissions()->exists()) {
            return redirect()
                ->back()
                ->with('error', 'Kategori poin tidak bisa dihapus karena sudah dipakai pengajuan mahasiswa. Nonaktifkan kategori jika tidak ingin menampilkannya lagi pada form mahasiswa.');
        }

        $pointRule->delete();

        return redirect()
            ->route('skkm.point-rules.index')
            ->with('success', 'Kategori poin SKKM berhasil dihapus.');
    }

    private function formView(PointRule $pointRule, bool $isEdit): View
    {
        return view('skkm.point-rules.form', [
            'pointRule' => $pointRule,
            'isEdit' => $isEdit,
            'unsurOptions' => $this->unsurOptions(),
            'tingkatOptions' => $this->tingkatOptions(),
        ]);
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, ?PointRule $pointRule = null): array
    {
        $uniqueCombination = Rule::unique('point_rules', 'peranan')
            ->where(function ($query) use ($request) {
                return $query
                    ->where('unsur', $request->input('unsur'))
                    ->where('sub_unsur', $request->input('sub_unsur'))
                    ->where('jenis_item', $request->input('jenis_item'))
                    ->where('tingkat', $request->input('tingkat'));
            })
            ->ignore($pointRule?->id);

        return $request->validate([
            'unsur' => ['required', Rule::in(array_keys($this->unsurOptions()))],
            'sub_unsur' => ['required', 'string', 'max:100'],
            'jenis_item' => ['required', 'string', 'max:100'],
            'tingkat' => ['nullable', Rule::in(array_keys($this->tingkatOptions()))],
            'peranan' => ['required', 'string', 'max:50', $uniqueCombination],
            'poin' => ['required', 'integer', 'min:1'],
            'bukti_fisik_required' => ['required', 'string'],
            'keterangan' => ['nullable', 'string'],
            'is_active' => ['required', 'boolean'],
        ], [
            'peranan.unique' => 'Kombinasi unsur, sub-unsur, jenis kegiatan, tingkat, dan peranan tersebut sudah tersedia.',
        ]);
    }

    /**
     * @return array<string, string>
     */
    private function unsurOptions(): array
    {
        return [
            'penalaran' => 'Penalaran & Keilmuan',
            'bakat_minat' => 'Bakat & Minat',
            'sosial' => 'Sosial & Kemasyarakatan',
            'kegiatan_khusus' => 'Kegiatan Khusus',
        ];
    }

    /**
     * @return array<string, string>
     */
    private function tingkatOptions(): array
    {
        return [
            'internasional' => 'Internasional',
            'nasional' => 'Nasional',
            'regional' => 'Regional',
            'provinsi' => 'Provinsi',
            'universitas' => 'Universitas',
            'fakultas' => 'Fakultas / Unit',
            'lokal' => 'Daerah / Lokal',
        ];
    }
}
