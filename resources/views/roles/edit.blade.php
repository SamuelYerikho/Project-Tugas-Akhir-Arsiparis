@extends('layouts.app')

@section('pageTitle', 'Ubah Peran')

@section('content')
    <div class="max-w-2xl">

        <div class="mb-8">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke daftar peran
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ubah peran</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">
                Perbarui nama peran. Untuk mengatur izin akses, pilih "Atur izin" di daftar peran.
            </p>
        </div>

        <form action="{{ route('roles.update', $role) }}" method="POST"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="name" class="block text-sm font-semibold mb-1.5">Nama peran</label>
                <input type="text" name="name" id="name" value="{{ old('name', $role->name) }}"
                    class="w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors @error('name') border-red-600 @else border-line @enderror">
                @error('name')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('roles.index') }}"
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