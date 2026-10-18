@extends('layouts.app')

@section('pageTitle', 'Atur Ulang Sandi')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
@endphp

    <div class="max-w-lg">

        <div class="mb-8">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar pengguna
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Atur ulang sandi</h2>
            <p class="text-sm text-steel mt-2">
                Buat kata sandi baru untuk <span class="font-semibold text-ink">{{ $user->name }}</span>.
            </p>
        </div>

        <form action="{{ route('users.password.update', $user) }}" method="POST"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="password" class="{{ $label }}">Kata sandi baru</label>
                <input type="password" name="password" id="password"
                    class="{{ $input }} @error('password') border-red-600 @else border-line @enderror">
                @error('password')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="{{ $label }}">Ulangi kata sandi baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="{{ $input }} border-line">
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('users.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    Simpan sandi baru
                </button>
            </div>
        </form>
    </div>
@endsection