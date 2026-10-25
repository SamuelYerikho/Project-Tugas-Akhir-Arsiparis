@extends('layouts.app')

@section('pageTitle', 'Atur Ulang Sandi')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-10">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar pengguna
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Atur ulang sandi</h2>
            <p class="text-sm text-steel mt-2">
                Buat kata sandi baru untuk <span class="font-semibold text-ink">{{ $user->name }}</span>.
            </p>
        </div>

        {{-- Bagian: Keamanan --}}
        <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
            <div>
                <h3 class="font-display font-bold text-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                    </svg>
                    Keamanan
                </h3>
                <p class="text-sm text-steel mt-2 leading-relaxed">
                    Pakai kata sandi yang kuat dan belum pernah dipakai di tempat lain. Setelah disimpan, pengguna harus memakai sandi baru ini untuk masuk.
                </p>
            </div>

            <form action="{{ route('users.password.update', $user) }}" method="POST"
                class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="{{ $label }}">Kata sandi baru</label>
                        <div class="relative">
                            <input id="password" name="password" type="password"
                                placeholder="••••••••"
                                class="{{ $input }} pr-11 @error('password') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            <button type="button" data-toggle-password="password"
                                aria-label="Tampilkan kata sandi baru"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-steel hover:text-ink transition-colors">
                                <svg data-eye-open class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg data-eye-closed class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0 1 12 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 0 1-3.2 3.7M6.6 7.6A16.6 16.6 0 0 0 2.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="{{ $label }}">Ulangi kata sandi baru</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                placeholder="••••••••"
                                class="{{ $input }} pr-11 border-line">
                            <button type="button" data-toggle-password="password_confirmation"
                                aria-label="Tampilkan ulangi kata sandi baru"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-steel hover:text-ink transition-colors">
                                <svg data-eye-open class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg data-eye-closed class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0 1 12 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 0 1-3.2 3.7M6.6 7.6A16.6 16.6 0 0 0 2.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-line flex items-center justify-end gap-5">
                    <a href="{{ route('users.index') }}"
                        class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-invert text-on-invert text-sm font-semibold rounded hover:bg-invert/90 transition-colors">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                        </svg>
                        Simpan sandi baru
                    </button>
                </div>
            </form>
        </section>

    </div>
@endsection

@push('scripts')
    <script>
        // Toggle tampil/sembunyi kata sandi (berlaku untuk semua kolom dengan data-toggle-password)
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.getElementById(btn.dataset.togglePassword);
                if (!target) return;

                // Balik tipe input
                const nowVisible = target.type === 'password';
                target.type = nowVisible ? 'text' : 'password';

                // Sinkronkan ikon
                const eyeOpen   = btn.querySelector('[data-eye-open]');
                const eyeClosed = btn.querySelector('[data-eye-closed]');
                if (eyeOpen)   eyeOpen.classList.toggle('hidden', nowVisible);
                if (eyeClosed) eyeClosed.classList.toggle('hidden', !nowVisible);

                // Perbarui label aksesibilitas
                btn.setAttribute('aria-label', nowVisible
                    ? 'Sembunyikan kata sandi'
                    : 'Tampilkan kata sandi');
            });
        });
    </script>
@endpush