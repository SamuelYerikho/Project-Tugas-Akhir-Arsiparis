@extends('layouts.app')

@section('pageTitle', 'Edit Role')

@section('content')
    <div class="max-w-3xl mx-auto">
        <div class="mb-6">
            <h1 class="text-xl font-bold text-slate-900">Edit Role</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui nama role. Untuk mengatur permission, gunakan menu
                "Permission" di daftar role.</p>
        </div>

        <form action="{{ route('roles.update', $role) }}" method="POST"
            class="bg-white border border-slate-200 rounded-xl p-6 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama Role</label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                    class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-aris-500 @error('name') border-red-300 @enderror">
                @error('name')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-3 pt-2">
                <a href="{{ route('roles.index') }}"
                    class="px-4 py-2.5 text-sm font-semibold text-slate-600 hover:text-slate-900">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-aris-600 text-white text-sm font-semibold rounded-xl hover:bg-aris-700 transition-colors">
                    Simpan Perubahan
                </button>
            </div>
        </form>
    </div>
@endsection