@extends('layouts.app')

@section('pageTitle', 'Reset Password')

@section('content')
    <div class="max-w-md mx-auto">
        <div class="mb-6">
            <a href="{{ route('users.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 mb-2">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Data Pengguna
            </a>
            <h1 class="text-xl font-bold text-slate-900">Reset Password</h1>
            <p class="text-sm text-slate-500 mt-1">
                Atur ulang password untuk <span class="font-semibold text-slate-700">{{ $user->name }}</span>.
            </p>
        </div>

        <form action="{{ route('users.password.update', $user) }}" method="POST"
            class="bg-white border border-slate-200 rounded-xl p-6 space-y-5">
            @csrf
            @method('PUT')

            <div>
                <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
                <input type="password" name="password" id="password"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('password') border-red-300 @enderror">
                @error('password')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi
                    Password Baru</label>
                <input type="password" name="password_confirmation" id="password_confirmation"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500">
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('users.index') }}"
                    class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-aris-600 text-white text-sm font-semibold rounded-xl hover:bg-aris-700 transition-colors">
                    Reset Password
                </button>
            </div>
        </form>
    </div>
@endsection