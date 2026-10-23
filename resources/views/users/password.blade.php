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
                        <input id="password" name="password" type="password"
                            placeholder="••••••••"
                            class="{{ $input }} @error('password') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        @error('password')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="{{ $label }}">Ulangi kata sandi baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            placeholder="••••••••"
                            class="{{ $input }} border-line">
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