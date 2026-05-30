<section class="space-y-4">
    <p class="text-sm leading-6 text-slate-600">
        Setelah akun dihapus, semua data yang terkait dengan akun ini akan dihapus permanen. Pastikan keputusan ini sudah benar sebelum melanjutkan.
    </p>

    <x-danger-button
        class="rounded-xl px-5 py-3"
        x-data=""
        x-on:click.prevent="$dispatch('open-modal', 'confirm-user-deletion')"
    >Hapus Akun</x-danger-button>

    <x-modal name="confirm-user-deletion" :show="$errors->userDeletion->isNotEmpty()" focusable>
        <form method="post" action="{{ route('profile.destroy') }}" class="p-6">
            @csrf
            @method('delete')

            <h2 class="text-lg font-black text-slate-950">
                Yakin ingin menghapus akun?
            </h2>

            <p class="mt-2 text-sm leading-6 text-slate-600">
                Tindakan ini permanen. Masukkan password untuk mengonfirmasi penghapusan akun.
            </p>

            <div class="mt-6">
                <x-input-label for="password" value="Password" class="sr-only" />

                <x-text-input
                    id="password"
                    name="password"
                    type="password"
                    class="field-white mt-1 block w-full py-3 text-sm"
                    placeholder="Masukkan password"
                />

                <x-input-error :messages="$errors->userDeletion->get('password')" class="mt-2" />
            </div>

            <div class="mt-6 flex justify-end gap-3">
                <x-secondary-button class="rounded-xl" x-on:click="$dispatch('close')">
                    Batal
                </x-secondary-button>

                <x-danger-button class="rounded-xl">
                    Hapus Akun
                </x-danger-button>
            </div>
        </form>
    </x-modal>
</section>
