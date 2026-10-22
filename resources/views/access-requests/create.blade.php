@extends('layouts.app')

@section('pageTitle', 'Ajukan Izin Akses')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors disabled:bg-paper disabled:text-steel disabled:cursor-not-allowed';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';
    $noArchives = $archives->isEmpty();
    $disabled = $hasFullAccess || $noArchives;
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-10">
            <a href="{{ route('access-requests.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar izin akses
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ajukan izin akses arsip</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Pilih arsip yang ingin Anda akses, lalu jelaskan alasannya.</p>
        </div>

        {{-- Banner: kondisi khusus (di luar form agar tetap terlihat jelas) --}}
        @if ($hasFullAccess)
            <div class="flex gap-3 bg-surface border-l-4 border-ink px-4 py-4 mb-8 rounded-r">
                <svg class="w-4 h-4 text-ink mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                <div class="text-xs text-ink leading-relaxed">
                    <p class="font-bold text-sm">Anda memiliki akses ke semua arsip</p>
                    <p class="mt-1.5 text-steel">
                        Karena Anda memiliki izin untuk melihat seluruh arsip, semua dokumen bisa Anda buka
                        langsung tanpa perlu meminta izin. Silakan buka dari
                        <a href="{{ route('archives.index') }}"
                            class="font-semibold text-ink underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">Daftar Arsip</a>.
                    </p>
                </div>
            </div>
        @elseif ($noArchives)
            <div class="flex gap-3 bg-surface border-l-4 border-ink px-4 py-4 mb-8 rounded-r">
                <svg class="w-4 h-4 text-ink mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                </svg>
                <div class="text-xs text-ink leading-relaxed">
                    <p class="font-bold text-sm">Belum ada arsip yang bisa diajukan</p>
                    <p class="mt-1.5 text-steel">
                        Saat ini belum ada arsip milik pengguna lain yang perlu Anda ajukan aksesnya.
                    </p>
                </div>
            </div>
        @endif

        <form action="{{ route('access-requests.store') }}" method="POST">
            @csrf

            {{-- ===== Bagian: Arsip yang diminta ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8 pb-12">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Arsip yang diminta
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Pilih satu arsip yang ingin Anda akses. Hanya arsip milik pengguna lain yang belum Anda
                        miliki aksesnya yang ditampilkan di sini.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="archive_id" class="{{ $label }}">Arsip</label>
                        <select name="archive_id" id="archive_id" @disabled($disabled)
                            class="{{ $input }} @error('archive_id') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            <option value="">{{ $disabled ? 'Tidak ada arsip tersedia' : 'Pilih arsip' }}</option>
                            @foreach ($archives as $archive)
                                <option value="{{ $archive->id }}" {{ old('archive_id') == $archive->id ? 'selected' : '' }}>
                                    {{ $archive->title }}
                                </option>
                            @endforeach
                        </select>
                        @error('archive_id')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    @unless ($hasFullAccess)
                        <div class="flex gap-3 bg-paper border-l-4 border-ink px-4 py-4 rounded-r">
                            <svg class="w-4 h-4 text-ink mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                            </svg>
                            <div class="text-xs text-ink leading-relaxed">
                                <p class="font-bold text-sm">Arsip Anda tidak ada di daftar ini</p>
                                <p class="mt-1.5 text-steel">
                                    Arsip yang Anda unggah sendiri tidak ditampilkan di sini karena Anda sudah otomatis
                                    memiliki akses penuh. Arsip yang permintaannya sudah disetujui juga tidak muncul lagi.
                                </p>
                            </div>
                        </div>
                    @endunless
                </div>
            </section>

            {{-- ===== Bagian: Alasan permintaan ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018Z"/>
                        </svg>
                        Alasan permintaan
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Jelaskan alasan Anda membutuhkan akses. Penjelasan yang jelas mempercepat proses persetujuan
                        oleh admin.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="reason" class="{{ $label }}">Alasan <span class="font-normal text-steel">(boleh dikosongkan)</span></label>
                        <textarea name="reason" id="reason" rows="5" @disabled($disabled)
                            class="{{ $input }} leading-relaxed @error('reason') !border-red-600 dark:!border-red-500 @else border-line @enderror"
                            placeholder="Jelaskan mengapa Anda membutuhkan akses ke arsip ini...">{{ old('reason') }}</textarea>
                        @error('reason')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="pt-4 border-t border-line flex items-center justify-end gap-5">
                        <a href="{{ route('access-requests.index') }}"
                            class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                            Batal
                        </a>
                        <button type="submit" @disabled($disabled)
                            class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:brightness-100">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.769 59.769 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L5.999 12Zm0 0h7.5"/>
                            </svg>
                            Kirim permintaan
                        </button>
                    </div>
                </div>
            </section>

        </form>
    </div>
@endsection