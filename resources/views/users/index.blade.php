<x-app-layout>
    <x-slot name="header">
        <p class="page-label">Pengaturan Sistem</p>
        <h2 class="page-title" style="font-family:'Montserrat',sans-serif;">Manajemen Pengguna</h2>
        <p class="page-sub">Kelola akun Admin dan HRD yang bisa mengakses sistem.</p>
    </x-slot>

    <div class="grid gap-6 xl:grid-cols-[1fr_1.6fr] items-start">

        {{-- ── FORM TAMBAH ── --}}
        <section class="card">
            <div class="mb-4 flex items-center gap-3">
                <span class="flex h-9 w-9 items-center justify-center rounded-xl" style="background:#e8f5e0;width:36px;height:36px;">
                    <svg class="h-5 w-5" style="width:20px;height:20px;color:#164A41;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                    </svg>
                </span>
                <div>
                    <h3 class="text-xs font-black text-stone-900 uppercase tracking-wider" style="font-family:'Montserrat',sans-serif;">Tambah Pengguna</h3>
                    <p class="text-[10px] text-slate-400">Buat akun admin atau HRD baru</p>
                </div>
            </div>

            <form method="POST" action="{{ route('admin.users.store') }}" class="space-y-3">
                @csrf
                <div>
                    <label class="label">Nama Lengkap <span class="text-rose-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name') }}" class="field" required>
                    @error('name')<p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Email <span class="text-rose-500">*</span></label>
                    <input type="email" name="email" value="{{ old('email') }}" class="field" required>
                    @error('email')<p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Password <span class="text-rose-500">*</span></label>
                    <input type="password" name="password" class="field" placeholder="Min. 8 karakter" required>
                    @error('password')<p class="mt-1 text-[10px] text-rose-600 font-semibold">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="label">Role / Akses <span class="text-rose-500">*</span></label>
                    <select name="role" class="field" required>
                        <option value="admin" @selected(old('role') === 'admin')>Admin — Akses Penuh</option>
                        <option value="hrd" @selected(old('role') === 'hrd')>HRD — Hanya Lihat</option>
                    </select>
                </div>

                <div class="flex items-center gap-2 mt-2">
                    <input type="checkbox" name="is_active" value="1" checked class="rounded border-slate-300 text-emerald-600 focus:ring-emerald-600" id="is_active_create">
                    <label for="is_active_create" class="label !mb-0" style="margin-bottom:0;">Akun Aktif (Bisa Login)</label>
                </div>

                <div class="pt-1">
                    <button type="submit" class="btn btn-compact w-full font-semibold text-white"
                            style="background: linear-gradient(135deg, #164A41, #4D774E);">
                        Buat Akun Pengguna
                    </button>
                </div>
            </form>
        </section>

        {{-- ── DAFTAR PENGGUNA ── --}}
        <section class="card">
            <div class="mb-4 flex items-center justify-between gap-4">
                <div>
                    <p class="section-label">Daftar</p>
                    <h3 class="section-title" style="font-family:'Montserrat',sans-serif;">Semua Pengguna</h3>
                </div>
                <span class="badge-forest">{{ $users->total() }} akun</span>
            </div>

            <div class="table-wrapper">
                <table class="data-table">
                    <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr x-data="{ editing: false }">
                                {{-- View Mode --}}
                                <td x-show="!editing" class="font-semibold text-slate-900">
                                    {{ $user->name }}
                                    @if($user->id === auth()->id())
                                        <span class="badge-amber ml-1">Anda</span>
                                    @endif
                                </td>
                                <td x-show="!editing" class="text-slate-500">{{ $user->email }}</td>
                                <td x-show="!editing">
                                    @if($user->isAdmin())
                                        <span class="badge" style="background:#e8f5e0; color:#164A41;">Admin</span>
                                    @else
                                        <span class="badge-amber">HRD</span>
                                    @endif
                                </td>
                                <td x-show="!editing">
                                    @if($user->is_active)
                                        <span class="badge" style="background:#e8f5e0; color:#164A41;">Aktif</span>
                                    @else
                                        <span class="badge" style="background:#fee2e2; color:#991b1b;">Tidak Aktif</span>
                                    @endif
                                </td>
                                <td x-show="!editing">
                                    <div class="flex gap-1.5">
                                        <button @click="editing = true"
                                            class="btn btn-compact btn-soft text-[10px]">Edit</button>
                                        @if($user->id !== auth()->id())
                                            <form method="POST" action="{{ route('admin.users.destroy', $user) }}">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="btn btn-compact btn-danger text-[10px]"
                                                        onclick="return confirm('Hapus akun {{ addslashes($user->name) }}?')">Hapus</button>
                                            </form>
                                        @endif
                                    </div>
                                </td>

                                {{-- Edit Mode --}}
                                <td x-show="editing" x-cloak colspan="4">
                                    <form method="POST" action="{{ route('admin.users.update', $user) }}"
                                          class="flex flex-wrap items-center gap-2 py-1">
                                        @csrf @method('PUT')
                                        <input type="text" name="name" value="{{ $user->name }}" class="field w-28" required>
                                        <input type="email" name="email" value="{{ $user->email }}" class="field w-32" required>
                                        <input type="password" name="password" class="field w-28" placeholder="Password (opsional)">
                                        <select name="role" class="field w-20" required>
                                            <option value="admin" @selected($user->role === 'admin')>Admin</option>
                                            <option value="hrd" @selected($user->role === 'hrd')>HRD</option>
                                        </select>
                                        <div class="flex items-center gap-1">
                                            <input type="checkbox" name="is_active" value="1" @checked($user->is_active) class="rounded border-slate-300 text-emerald-600">
                                            <span class="text-[10px] font-semibold text-slate-700">Aktif</span>
                                        </div>
                                        <button type="submit" class="btn btn-compact btn-success">Simpan</button>
                                        <button type="button" @click="editing = false" class="btn btn-compact btn-soft">Batal</button>
                                    </form>
                                </td>
                                <td x-show="editing" x-cloak></td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="empty-state text-slate-400">Belum ada pengguna terdaftar.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">{{ $users->links() }}</div>

            {{-- Info --}}
            <div class="mt-4 rounded-lg border p-3 text-xs" style="background:#f0faf4; border-color:#9DC88D; color:#164A41;">
                <p class="font-bold mb-1">📋 Panduan Role:</p>
                <p><span class="font-semibold">Admin</span> — Akses penuh ke semua fitur, input transaksi, dan master data.</p>
                <p class="mt-0.5"><span class="font-semibold">HRD</span> — Hanya bisa melihat laporan gaji, operasional, dan keuangan. Tidak bisa mengedit data apapun.</p>
            </div>
        </section>
    </div>
</x-app-layout>
