<x-guest-layout>
    <div class="mx-auto w-full max-w-[27rem]">
        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <div class="mb-6 text-center">
            <p class="mb-2 text-xs font-bold uppercase tracking-[0.28em]" style="color: #4D774E;">Portal Masuk</p>
            <h1 class="text-3xl font-black tracking-tight text-stone-950" style="font-family: 'Montserrat', sans-serif;">Selamat Datang</h1>
            <p class="mt-2 text-sm leading-6 text-stone-600">Silakan masukkan username dan password untuk masuk ke dashboard.</p>
        </div>

        <div class="mx-auto mb-7 flex max-w-sm rounded-full bg-stone-100 p-1">
            <a href="{{ route('login') }}" class="flex-1 rounded-full px-4 py-2.5 text-center text-sm font-black text-white shadow-sm transition" style="background-color: #164A41;">Login</a>
            @if (Route::has('register'))
                <a href="{{ route('register') }}" class="flex-1 rounded-full px-4 py-2.5 text-center text-sm font-black text-stone-700 transition hover:text-stone-950">Daftar</a>
            @else
                <span class="flex-1 rounded-full px-4 py-2.5 text-center text-sm font-black text-stone-400">Daftar</span>
            @endif
        </div>

        <div x-data="{ loading: false }" class="relative">

            <form method="POST" action="{{ route('login') }}" class="space-y-4" x-on:submit="loading = true">
            @csrf

            <div>
                <x-input-label for="email" :value="__('Username')" />
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 flex items-center text-stone-400" style="left: 1rem;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l9 6 9-6M4 6h16a2 2 0 012 2v10a2 2 0 01-2 2H4a2 2 0 01-2-2V8a2 2 0 012-2z" />
                        </svg>
                    </span>
                    <x-text-input id="email" class="block w-full rounded-xl border-0 bg-stone-100/70 !pl-14 pr-4 py-3 text-sm shadow-none placeholder:text-stone-400 transition focus:bg-white focus:ring-2 focus:ring-[#164A41]" style="padding-left: 3.5rem;" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email" />
                </div>
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <div>
                <x-input-label for="password" :value="__('Password')" />
                <div class="relative mt-1">
                    <span class="pointer-events-none absolute inset-y-0 flex items-center text-stone-400" style="left: 1rem;">
                        <svg class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c1.657 0 3-1.343 3-3S13.657 5 12 5 9 6.343 9 8s1.343 3 3 3z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 11h14a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2z" />
                        </svg>
                    </span>
                    <x-text-input id="password" class="block w-full rounded-xl border-0 bg-stone-100/70 !pl-14 pr-4 py-3 text-sm shadow-none placeholder:text-stone-400 transition focus:bg-white focus:ring-2 focus:ring-[#164A41]" style="padding-left: 3.5rem;" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan password" />
                </div>
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <div class="flex items-center justify-between gap-3">
                <label for="remember_me" class="inline-flex items-center">
                    <input id="remember_me" type="checkbox" class="rounded border-stone-300 text-[#164A41] shadow-sm focus:ring-[#164A41]" name="remember">
                    <span class="ms-2 text-sm text-stone-600">{{ __('Remember me') }}</span>
                </label>

                @if (Route::has('password.request'))
                    <a class="text-sm font-semibold hover:opacity-80" style="color: #164A41;" href="{{ route('password.request') }}">
                        Lupa password?
                    </a>
                @endif
            </div>

            <button type="submit" class="mt-3 flex w-full items-center justify-center gap-3 rounded-xl px-5 py-3 text-sm font-black text-white shadow-sm transition hover:opacity-90 disabled:cursor-not-allowed disabled:opacity-70" style="background: linear-gradient(135deg, #164A41, #4D774E);" x-bind:disabled="loading">
                <svg x-show="loading" x-cloak class="h-5 w-5 animate-spin text-white" viewBox="0 0 24 24" fill="none">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                </svg>
                <span x-text="loading ? 'Memproses...' : 'Login'"></span>
            </button>

            @if (Route::has('register'))
                <p class="pt-2 text-center text-sm text-stone-600">
                    Belum punya akun?
                    <a href="{{ route('register') }}" class="font-semibold hover:opacity-80" style="color: #164A41;">Daftar sekarang</a>
                </p>
            @endif
            </form>
        </div>
    </div>
</x-guest-layout>
