@extends('layouts.app')

@section('pageTitle', 'Buat Laporan')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
    $choice = 'flex items-center gap-3 bg-white border border-line rounded px-4 py-4 cursor-pointer transition-colors hover:border-ink has-[:checked]:border-ink has-[:checked]:bg-manila/20';
@endphp

    <div class="max-w-2xl">

        <div class="mb-8">
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke daftar laporan
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Buat laporan baru</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Tentukan jenis, rentang tanggal, dan format file laporan arsip yang ingin dibuat.</p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf

            <div>
                <label for="report_type" class="{{ $label }}">Jenis laporan</label>
                <input type="text" name="report_type" id="report_type" value="{{ old('report_type') }}"
                    placeholder="Contoh: Laporan Arsip Bulanan"
                    class="{{ $input }} @error('report_type') border-red-600 @else border-line @enderror">
                @error('report_type')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="period_start" class="{{ $label }}">Dari tanggal</label>
                        <input type="date" name="period_start" id="period_start" value="{{ old('period_start') }}"
                            class="{{ $input }} @error('period_start') border-red-600 @else border-line @enderror">
                        @error('period_start')
                            <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="period_end" class="{{ $label }}">Sampai tanggal</label>
                        <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}"
                            class="{{ $input }} @error('period_end') border-red-600 @else border-line @enderror">
                        @error('period_end')
                            <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-xs text-steel mt-2">Kosongkan kedua tanggal untuk mencakup seluruh arsip.</p>
            </div>

            <fieldset>
                <legend class="{{ $label }}">Format file</legend>
                <div class="grid grid-cols-2 gap-3">
                    <label class="{{ $choice }}">
                        <input type="radio" name="format" value="excel" class="w-4 h-4 accent-ink"
                            {{ old('format', 'excel') === 'excel' ? 'checked' : '' }}>
                        <span class="flex items-center gap-2 text-sm font-semibold">
                            <i class="fa-solid fa-file-excel text-emerald-700"></i> Excel
                        </span>
                    </label>

                    <label class="{{ $choice }}">
                        <input type="radio" name="format" value="pdf" class="w-4 h-4 accent-ink"
                            {{ old('format') === 'pdf' ? 'checked' : '' }}>
                        <span class="flex items-center gap-2 text-sm font-semibold">
                            <i class="fa-solid fa-file-pdf text-red-700"></i> PDF
                        </span>
                    </label>
                </div>
                @error('format')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </fieldset>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('reports.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    Buat laporan
                </button>
            </div>
        </form>
    </div>
@endsection