@extends('layouts.app')

@section('pageTitle', 'Buat Laporan')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';
    $choice = 'flex items-center gap-3 bg-surface border border-line rounded px-4 py-4 cursor-pointer transition-colors hover:border-ink has-[:checked]:border-ink has-[:checked]:bg-manila/20 dark:has-[:checked]:bg-manila/15';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-10">
            <a href="{{ route('reports.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar laporan
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Buat laporan baru</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Tentukan jenis, rentang tanggal, dan format file laporan arsip yang ingin dibuat.</p>
        </div>

        <form action="{{ route('reports.store') }}" method="POST">
            @csrf

            {{-- ===== Bagian: Detail laporan ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8 pb-12">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Detail laporan
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Beri nama laporan dan tentukan rentang tanggal arsip yang akan disertakan. Kosongkan tanggal
                        untuk mencakup seluruh arsip.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="report_type" class="{{ $label }}">Jenis laporan</label>
                        <input type="text" name="report_type" id="report_type" value="{{ old('report_type') }}"
                            placeholder="Contoh: Laporan Arsip Bulanan"
                            class="{{ $input }} @error('report_type') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        @error('report_type')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                            <div>
                                <label for="period_start" class="{{ $label }}">Dari tanggal</label>
                                <input type="date" name="period_start" id="period_start" value="{{ old('period_start') }}"
                                    class="{{ $input }} @error('period_start') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                                @error('period_start')
                                    <p class="{{ $errorText }}">{{ $message }}</p>
                                @enderror
                            </div>

                            <div>
                                <label for="period_end" class="{{ $label }}">Sampai tanggal</label>
                                <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}"
                                    class="{{ $input }} @error('period_end') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                                @error('period_end')
                                    <p class="{{ $errorText }}">{{ $message }}</p>
                                @enderror
                            </div>
                        </div>
                        <p class="text-xs text-steel mt-2">Kosongkan kedua tanggal untuk mencakup seluruh arsip.</p>
                    </div>
                </div>
            </section>

            {{-- ===== Bagian: Format file ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 7.5h10.5m-10.5 4.5h10.5m-10.5 4.5h6M3.75 3.75h16.5v16.5H3.75V3.75Z"/>
                        </svg>
                        Format file
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Pilih format berkas laporan. Excel cocok untuk pengolahan data, PDF cocok untuk dicetak
                        atau dibagikan.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <fieldset>
                        <legend class="{{ $label }}">Format file</legend>
                        <div class="grid grid-cols-2 gap-3">
                            <label class="{{ $choice }}">
                                <input type="radio" name="format" value="excel" class="w-4 h-4 accent-ink"
                                    {{ old('format', 'excel') === 'excel' ? 'checked' : '' }}>
                                <span class="flex items-center gap-2 text-sm font-semibold">
                                    <svg class="w-4 h-4 text-emerald-700 dark:text-emerald-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0 1 18 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 0 1 6 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5"/>
                                    </svg>
                                    Excel
                                </span>
                            </label>

                            <label class="{{ $choice }}">
                                <input type="radio" name="format" value="pdf" class="w-4 h-4 accent-ink"
                                    {{ old('format') === 'pdf' ? 'checked' : '' }}>
                                <span class="flex items-center gap-2 text-sm font-semibold">
                                    <svg class="w-4 h-4 text-red-700 dark:text-red-400 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                    </svg>
                                    PDF
                                </span>
                            </label>
                        </div>
                        @error('format')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </fieldset>

                    {{-- Tombol aksi --}}
                    <div class="pt-4 border-t border-line flex items-center justify-end gap-5">
                        <a href="{{ route('reports.index') }}"
                            class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                            Buat laporan
                        </button>
                    </div>
                </div>
            </section>

        </form>
    </div>
@endsection