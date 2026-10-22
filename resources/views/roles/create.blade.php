@extends('layouts.app')

@section('pageTitle', 'Tambah Peran')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-10">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar peran
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Tambah peran</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">
                Buat peran baru. Izin akses diatur setelah peran dibuat, lewat tombol "Atur izin" di daftar peran.
            </p>
        </div>

        <form action="{{ route('roles.store') }}" method="POST">
            @csrf

            {{-- ===== Bagian: Detail peran ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                        Detail peran
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Beri nama peran yang jelas dan mudah dikenali. Nama ini akan muncul di daftar peran dan saat
                        mengatur pengguna.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="name" class="{{ $label }}">Nama peran</label>
                        <input type="text" name="name" id="name" value="{{ old('name') }}"
                            class="{{ $input }} @error('name') !border-red-600 dark:!border-red-500 @else border-line @enderror"
                            placeholder="Contoh: Admin, Staff">
                        @error('name')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="pt-4 border-t border-line flex items-center justify-end gap-5">
                        <a href="{{ route('roles.index') }}"
                            class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            Simpan peran
                        </button>
                    </div>
                </div>
            </section>

        </form>
    </div>
@endsection