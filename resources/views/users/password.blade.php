@extends('layouts.app')

@section('pageTitle', 'Atur Ulang Sandi')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';
@endphp

    <div class="max-w-lg">

        <div class="mb-8">
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

        <form action="{{ route('users.password.update', $user) }}" method="POST"
            class="bg-surface border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="password" class="{{ $label }}">Kata sandi baru</label>
                <input type="password" name="password" id="password"
                    placeholder="••••••••"
                    class="{{ $input }} @error('password') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                @error('password')
                    <p class="{{ $errorText }}">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="{{ $label }}">Ulangi kata sandi baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    placeholder="••••••••"
                    class="{{ $input }} border-line">
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('users.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                    </svg>
                    Simpan sandi baru
                </button>
            </div>
        </form>
    </div>
@endsection