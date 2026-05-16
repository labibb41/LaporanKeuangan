<div class="flex flex-wrap items-end justify-between gap-4 border-b border-stone-200/70 pb-5">
    <div>
        <p class="text-xs font-semibold uppercase tracking-[0.35em] text-stone-500">Filter Periode</p>
        <p class="mt-1 text-sm text-stone-600">Atur bulan dan tahun untuk laporan ini.</p>
    </div>

    <form method="GET" action="{{ url()->current() }}" class="flex flex-wrap items-end gap-3">
        <div>
            <label for="bulan" class="mb-1 block text-xs font-semibold uppercase tracking-[0.25em] text-stone-500">Bulan</label>
            <input id="bulan" name="bulan" type="number" min="1" max="12" value="{{ $bulan }}" class="field w-24 py-2">
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
