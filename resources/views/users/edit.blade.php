@extends('layouts.app')

@section('pageTitle', 'Ubah Pengguna')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
@endphp

    <div class="max-w-3xl">

        <div class="mb-8">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar pengguna
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ubah pengguna</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">
                Perbarui data dan peran pengguna. Untuk mengganti kata sandi, pilih "Atur ulang sandi" di daftar pengguna.
            </p>
        </div>

        <form action="{{ route('users.update', $user) }}" method="POST"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="{{ $label }}">Nama lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}"
                    class="{{ $input }} @error('name') border-red-600 @else border-line @enderror">
                @error('name')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label for="username" class="{{ $label }}">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username', $user->username) }}"
                        class="{{ $input }} @error('username') border-red-600 @else border-line @enderror">
                    @error('username')
                        <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="{{ $label }}">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}"
                        class="{{ $input }} @error('email') border-red-600 @else border-line @enderror">
                    @error('email')
                        <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div>
                <label for="role" class="{{ $label }}">Peran</label>
                <select name="role" id="role"
                    class="{{ $input }} @error('role') border-red-600 @else border-line @enderror">
                    <option value="">Pilih peran</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}"
                            {{ old('role', $userRole) === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('users.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
@endsection