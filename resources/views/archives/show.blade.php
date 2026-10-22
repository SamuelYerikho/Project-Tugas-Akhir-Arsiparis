@extends('layouts.app')

@section('pageTitle', 'Detail Arsip')

@section('content')
@php
    $statusMeta = match ($archive->status) {
        'active' => [
            'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            'text'  => 'Aktif',
        ],
        'archived' => [
            'class' => 'bg-manila/30 text-on-manila dark:bg-manila/25 dark:text-manila',
            'text'  => 'Diarsipkan',
        ],
        default => [
            'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            'text'  => 'Dihapus',
        ],
    };
    $meta = 'text-xs font-semibold text-steel mb-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('archives.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar arsip
            </a>
            <p class="text-sm font-semibold text-steel">Detail arsip</p>
            <h2 class="font-display font-extrabold text-3xl tracking-tight leading-tight mt-1 break-words">{{ $archive->title }}</h2>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-surface border-l-4 border-manila text-ink text-sm font-semibold px-4 py-3 flex items-center gap-3 rounded-r">
                <svg class="w-4 h-4 text-manila shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-surface border-l-4 border-red-600 text-red-700 dark:text-red-400 text-sm font-semibold px-4 py-3 flex items-center gap-3 rounded-r">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KIRI: rincian arsip --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-surface border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-8">

                    <div class="flex items-center gap-4 pb-8 border-b border-line">
                        <div class="w-12 h-12 rounded-sm bg-invert text-manila flex items-center justify-center shrink-0">
                            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                            </svg>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold truncate">
                                {{ $archive->original_filename ?? basename($archive->file_path) }}
                            </p>
                            <p class="text-xs text-steel mt-1">
                                {{ strtoupper($archive->file_type ?? '—') }}
                                @if ($archive->file_size)
                                    &middot; {{ number_format($archive->file_size / 1024, 1) }} KB
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <p class="{{ $meta }}">Kategori</p>
                            <p class="text-sm font-semibold">{{ $archive->category ?? '—' }}</p>
                        </div>
                        <div>
                            <p class="{{ $meta }}">Diunggah oleh</p>
                            <p class="text-sm font-semibold">{{ $archive->user->name ?? '—' }}</p>
                        </div>
                    </div>

                    <div>
                        <p class="{{ $meta }}">Deskripsi</p>
                        <p class="text-sm leading-relaxed max-w-prose">
                            {{ $archive->description ?? 'Tidak ada deskripsi untuk arsip ini.' }}
                        </p>
                    </div>

                    {{-- HASIL OCR --}}
                    <div class="pt-8 border-t border-line">
                        <div class="flex items-center justify-between gap-3 mb-4">
                            <p class="font-display font-bold text-lg">Teks hasil OCR</p>
                            @include('archives._ocr-badge', ['archive' => $archive])
                        </div>

                        @switch($archive->ocr_status)
                            @case('done')
                                <p class="text-xs text-steel mb-4">
                                    {{ $archive->ocr_pages }} halaman
                                    &middot; {{ number_format($archive->ocr_seconds ?? 0, 1) }} detik
                                    @if ($archive->ocr_at)
                                        &middot; selesai {{ $archive->ocr_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                                    @endif
                                </p>

                                {{-- Catatan akurasi OCR --}}
                                <div class="flex gap-3 bg-manila/20 border-l-4 border-manila px-4 py-3 mb-4 rounded-r">
                                    <svg class="w-4 h-4 text-ink mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                                    </svg>
                                    <div class="text-xs text-ink leading-relaxed">
                                        <p class="font-bold">Teks dihasilkan otomatis dan tidak 100% akurat.</p>
                                        <p class="mt-1">
                                            Hasil bergantung pada kualitas berkas (kejelasan scan atau foto, kemiringan, pencahayaan, resolusi).
                                            Ada kemungkinan huruf atau kata salah terbaca. Pakai sebagai alat bantu pencarian,
                                            dan rujuk berkas asli untuk kutipan resmi.
                                        </p>
                                    </div>
                                </div>

                                @if (filled($archive->ocr_text))
                                    <details class="group border border-line rounded">
                                        <summary class="cursor-pointer select-none px-4 py-3 text-sm font-semibold hover:bg-paper rounded transition-colors">
                                            Tampilkan atau sembunyikan teks
                                        </summary>
                                        <div class="px-4 py-3 border-t border-line max-h-96 overflow-y-auto">
                                            <pre class="whitespace-pre-wrap break-words text-xs text-steel font-mono">{{ $archive->ocr_text }}</pre>
                                        </div>
                                    </details>
                                @else
                                    <p class="text-sm text-steel">Tidak ada teks yang terbaca dari berkas ini.</p>
                                @endif
                            @break

                            @case('failed')
                                <div class="bg-surface border-l-4 border-red-600 dark:border-red-500 px-4 py-3 text-sm text-red-700 dark:text-red-400 rounded-r">
                                    <p class="font-semibold">OCR gagal diproses.</p>
                                    @if ($archive->ocr_error)
                                        <p class="text-xs mt-1 break-words">{{ $archive->ocr_error }}</p>
                                    @endif
                                </div>
                                {{-- Coba lagi --}}
                                @can('edit archives')
                                    <form action="{{ route('archives.ocr-retry', $archive) }}" method="POST" class="mt-4">
                                        @csrf
                                        <button type="submit"
                                            class="inline-flex items-center gap-2 border border-ink text-ink text-sm font-semibold px-4 py-2.5 rounded hover:bg-invert hover:text-on-invert transition-colors">
                                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>
                                            </svg>
                                            Proses ulang OCR
                                        </button>
                                    </form>
                                @endcan
                            @break

                            @case('pending')
                            @case('processing')
                                <p class="text-sm text-steel">
                                    OCR sedang dalam antrean atau diproses. Halaman ini diperbarui otomatis.
                                </p>
                            @break

                            @default
                                <p class="text-sm text-steel">
                                    Tipe berkas ini tidak didukung OCR (hanya PDF dan gambar).
                                </p>
                        @endswitch
                    </div>
                </div>

                @can('delete archives')
                    <div class="bg-surface border border-red-200 dark:border-red-900/40 rounded p-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold">Hapus arsip</p>
                            <p class="text-xs text-steel mt-0.5">Tindakan ini tidak bisa dibatalkan.</p>
                        </div>
                        <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                            onsubmit="return confirm('Hapus arsip ini? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 border border-red-700 text-red-700 dark:border-red-500 dark:text-red-400 text-sm font-semibold px-4 py-2.5 rounded hover:bg-red-700 hover:text-white dark:hover:bg-red-700 dark:hover:text-white transition-colors">
                                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                </svg>
                                Hapus
                            </button>
                        </form>
                    </div>
                @endcan
            </div>

            {{-- KANAN: status dan aksi --}}
            <div class="bg-surface border border-line border-t-2 border-t-ink rounded p-6 space-y-6">

                <div>
                    <p class="{{ $meta }}">Status saat ini</p>
                    <span class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1.5 rounded-sm {{ $statusMeta['class'] }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $statusMeta['text'] }}
                    </span>
                </div>

                <div class="pt-6 border-t border-line">
                    <p class="{{ $meta }}">Tanggal unggah</p>
                    <p class="text-sm font-semibold flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5"/>
                        </svg>
                        {{ $archive->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>

                <div class="flex flex-col gap-3 pt-6 border-t border-line">
                    @can('download archives')
                        <a href="{{ route('archives.download', $archive) }}"
                            class="inline-flex items-center justify-center gap-2 bg-manila text-on-manila text-sm font-semibold px-4 py-3 rounded hover:brightness-95 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                            </svg>
                            Unduh file
                        </a>
                    @endcan
                    @can('edit archives')
                        <a href="{{ route('archives.edit', $archive) }}"
                            class="inline-flex items-center justify-center gap-2 border border-ink text-ink text-sm font-semibold px-4 py-3 rounded hover:bg-invert hover:text-on-invert transition-colors">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/>
                            </svg>
                            Ubah arsip
                        </a>
                    @endcan
                </div>
            </div>

        </div>
    </div>

    @if (in_array($archive->ocr_status, ['pending', 'processing'], true))
        <script>
            setTimeout(() => window.location.reload(), 8000);
        </script>
    @endif
@endsection