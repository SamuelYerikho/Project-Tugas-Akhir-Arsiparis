@extends('layouts.app')

@section('pageTitle', 'Ubah Pengguna')

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
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ubah pengguna</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">
                Perbarui data dan peran pengguna. Untuk mengganti kata sandi, pilih "Atur ulang sandi" di daftar pengguna.
            </p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            {{-- ===== Bagian: Detail pengguna ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8 pb-12">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                        </svg>
                        Detail pengguna
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Perbarui nama lengkap, username, dan email pengguna. Username dan email harus unik.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="name" class="{{ $label }}">Nama lengkap</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                            placeholder="Contoh: Budi Santoso"
                            class="{{ $input }} @error('name') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        @error('name')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <div>
                            <label for="username" class="{{ $label }}">Username</label>
                            <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                                placeholder="contoh: budi.santoso"
                                class="{{ $input }} @error('username') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            @error('username')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="{{ $label }}">Email</label>
                            <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                                placeholder="contoh: nama@rribatam.go.id"
                                class="{{ $input }} @error('email') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            @error('email')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>
                </div>
            </section>

            {{-- ===== Bagian: Peran ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                        </svg>
                        Peran
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Peran menentukan hak akses pengguna di sistem. Perubahan peran berlaku setelah disimpan.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="role" class="{{ $label }}">Peran</label>
                        <select name="role" id="role"
                            class="{{ $input }} @error('role') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            <option value="">Pilih peran</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->name }}"
                                    {{ old('role', $userRole) === $role->name ? 'selected' : '' }}>
                                    {{ $role->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('role')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="pt-4 border-t border-line flex items-center justify-end gap-5">
                        <a href="{{ route('users.index') }}"
                            class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            Simpan perubahan
                        </button>
                    </div>
                </div>
            </section>

        </form>
    </div>
@endsection