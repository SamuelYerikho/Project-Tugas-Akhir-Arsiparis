@extends('layouts.app')

@section('pageTitle', 'Tambah Pengguna')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 mb-2">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Data Pengguna
            </a>
            <h1 class="text-xl font-bold text-slate-900">Tambah Pengguna</h1>
            <p class="text-sm text-slate-500 mt-1">Buat akun pengguna baru beserta role-nya.</p>
        </div>

        <form action="{{ route('users.store') }}" method="POST"
            class="bg-white border border-slate-200 rounded-xl p-6 space-y-5">
            @csrf

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Lengkap</label>
                <input type="text" name="name" id="name" value="{{ old('name') }}"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('name') border-red-300 @enderror">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                    <input type="text" name="username" id="username" value="{{ old('username') }}"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('username') border-red-300 @enderror">
                    @error('username')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input type="email" name="email" id="email" value="{{ old('email') }}"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('email') border-red-300 @enderror">
                    @error('email')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password</label>
                    <input type="password" name="password" id="password"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('password') border-red-300 @enderror">
                    @error('password')
                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi
                        Password</label>
                    <input type="password" name="password_confirmation" id="password_confirmation"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500">
                </div>
            </div>

            <div>
                <label for="role" class="block text-sm font-semibold text-slate-700 mb-1.5">Role</label>
                <select name="role" id="role"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('role') border-red-300 @enderror">
                    <option value="">Pilih role</option>
                    @foreach ($roles as $role)
                        <option value="{{ $role->name }}" {{ old('role') === $role->name ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-aris-600 text-white text-sm font-semibold rounded-xl hover:bg-aris-700 transition-colors">
                    Simpan Pengguna
                </button>
            </div>
        </form>
    </div>
@endsection