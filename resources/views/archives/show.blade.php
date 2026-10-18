@extends('layouts.app')

@section('pageTitle', 'Detail Arsip')

@section('content')
@php
    $statusClass = match ($archive->status) {
        'active' => 'bg-emerald-100 text-emerald-800',
        'archived' => 'bg-manila/40 text-ink',
        default => 'bg-red-100 text-red-800',
    };
    $statusText = match ($archive->status) {
        'active' => 'Aktif',
        'archived' => 'Diarsipkan',
        default => 'Dihapus',
    };
    $meta = 'text-xs font-semibold text-steel mb-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('archives.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke daftar arsip
            </a>
            <p class="text-sm font-semibold text-steel">Detail arsip</p>
            <h2 class="font-display font-extrabold text-3xl tracking-tight leading-tight mt-1 break-words">{{ $archive->title }}</h2>
        </div>

        @if (session('success'))
            <div class="mb-6 bg-white border-l-4 border-manila text-ink text-sm font-semibold px-4 py-3">
                {{ session('success') }}
            </div>
        @endif
        @if (session('error'))
            <div class="mb-6 bg-white border-l-4 border-red-600 text-red-700 text-sm font-semibold px-4 py-3">
                {{ session('error') }}
            </div>
        @endif

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KIRI: rincian arsip --}}
            <div class="lg:col-span-2 space-y-6">

                <div class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-8">

                    <div class="flex items-center gap-4 pb-8 border-b border-line">
                        <div class="w-12 h-12 rounded-sm bg-ink text-manila flex items-center justify-center text-lg shrink-0">
                            <i class="fa-solid fa-file-lines"></i>
                        </div>
                        <div class="min-w-0">
                            <p class="font-semibold truncate">
                                {{ $archive->original_filename ?? basename($archive->file_path) }}
                            </p>
                            <p class="text-xs text-steel mt-1">
                                {{ strtoupper($archive->file_type ?? '-') }}
                                @if ($archive->file_size)
                                    &middot; {{ number_format($archive->file_size / 1024, 1) }} KB
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                        <div>
                            <p class="{{ $meta }}">Kategori</p>
                            <p class="text-sm font-semibold">{{ $archive->category ?? '-' }}</p>
                        </div>
                        <div>
                            <p class="{{ $meta }}">Diunggah oleh</p>
                            <p class="text-sm font-semibold">{{ $archive->user->name ?? '-' }}</p>
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
                                <div class="flex gap-3 bg-manila/20 border-l-4 border-manila px-4 py-3 mb-4">
                                    <i class="fa-solid fa-circle-info text-ink mt-0.5 text-sm"></i>
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
                                        <summary class="cursor-pointer select-none px-4 py-3 text-sm font-semibold hover:bg-paper rounded">
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
                                <div class="bg-white border-l-4 border-red-600 px-4 py-3 text-sm text-red-700">
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
                                            class="inline-flex items-center gap-2 border border-ink text-ink text-sm font-semibold px-4 py-2.5 rounded hover:bg-ink hover:text-white transition-colors">
                                            <i class="fa-solid fa-rotate-right"></i>
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
                    <div class="bg-white border border-red-200 rounded p-5 flex items-center justify-between gap-4">
                        <div>
                            <p class="text-sm font-semibold">Hapus arsip</p>
                            <p class="text-xs text-steel mt-0.5">Tindakan ini tidak bisa dibatalkan.</p>
                        </div>
                        <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                            onsubmit="return confirm('Hapus arsip ini? Tindakan ini tidak bisa dibatalkan.')">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                class="inline-flex items-center gap-2 border border-red-700 text-red-700 text-sm font-semibold px-4 py-2.5 rounded hover:bg-red-700 hover:text-white transition-colors">
                                <i class="fa-solid fa-trash"></i>
                                Hapus
                            </button>
                        </form>
                    </div>
                @endcan
            </div>

            {{-- KANAN: status dan aksi --}}
            <div class="bg-white border border-line border-t-2 border-t-ink rounded p-6 space-y-6">

                <div>
                    <p class="{{ $meta }}">Status saat ini</p>
                    <span class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1.5 rounded-sm {{ $statusClass }}">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        {{ $statusText }}
                    </span>
                </div>

                <div class="pt-6 border-t border-line">
                    <p class="{{ $meta }}">Tanggal unggah</p>
                    <p class="text-sm font-semibold flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-steel"></i>
                        {{ $archive->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>

                <div class="flex flex-col gap-3 pt-6 border-t border-line">
                    @can('download archives')
                        <a href="{{ route('archives.download', $archive) }}"
                            class="inline-flex items-center justify-center gap-2 bg-manila text-ink text-sm font-semibold px-4 py-3 rounded hover:brightness-95 transition">
                            <i class="fa-solid fa-download"></i>
                            Unduh file
                        </a>
                    @endcan
                    @can('edit archives')
                        <a href="{{ route('archives.edit', $archive) }}"
                            class="inline-flex items-center justify-center gap-2 border border-ink text-ink text-sm font-semibold px-4 py-3 rounded hover:bg-ink hover:text-white transition-colors">
                            <i class="fa-solid fa-pen"></i>
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