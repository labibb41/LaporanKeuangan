<div class="flex flex-wrap items-end justify-between gap-4 border-b border-stone-200/70 pb-5">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Filter Periode</p>
        <p class="mt-1 text-sm text-stone-600">Atur bulan dan tahun untuk laporan ini.</p>
    </div>

    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-3">
        <div>
            <label for="bulan" class="mb-2 block text-sm font-semibold text-stone-700">Bulan</label>
            <select id="bulan" name="bulan" class="field min-w-32 py-2">
                @foreach(['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'] as $idx => $namaBulan)
                    <option value="{{ $idx + 1 }}" @selected($bulan == $idx + 1)>{{ $namaBulan }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label for="tahun" class="mb-1 block text-xs font-semibold uppercase tracking-[0.25em] text-stone-500">Tahun</label>
            <input id="tahun" name="tahun" type="number" min="2020" value="{{ $tahun }}" class="field w-28 py-2">
        </div>
        <button type="submit" class="btn-primary btn-compact">
            Terapkan
        </button>
    </form>
</div>
