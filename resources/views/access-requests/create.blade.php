@extends('layouts.app')

@section('pageTitle', 'Ajukan Izin Akses')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
@endphp

    <div class="max-w-2xl">

        <div class="mb-8">
            <a href="{{ route('access-requests.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke daftar izin akses
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ajukan izin akses arsip</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Pilih arsip yang ingin Anda akses, lalu jelaskan alasannya.</p>
        </div>

        <form action="{{ route('access-requests.store') }}" method="POST"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf

            <div>
                <label for="archive_id" class="{{ $label }}">Arsip</label>
                <select name="archive_id" id="archive_id"
                    class="{{ $input }} @error('archive_id') border-red-600 @else border-line @enderror">
                    <option value="">Pilih arsip</option>
                    @foreach ($archives as $archive)
                        <option value="{{ $archive->id }}" {{ old('archive_id') == $archive->id ? 'selected' : '' }}>
                            {{ $archive->title }}
                        </option>
                    @endforeach
                </select>
                @error('archive_id')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="reason" class="{{ $label }}">Alasan <span class="font-normal text-steel">(boleh dikosongkan)</span></label>
                <textarea name="reason" id="reason" rows="5"
                    class="{{ $input }} leading-relaxed @error('reason') border-red-600 @else border-line @enderror"
                    placeholder="Jelaskan mengapa Anda membutuhkan akses ke arsip ini...">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('access-requests.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    Kirim permintaan
                </button>
            </div>
        </form>
    </div>
@endsection