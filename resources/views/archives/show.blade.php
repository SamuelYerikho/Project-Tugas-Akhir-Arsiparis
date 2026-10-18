@extends('layouts.app')

@section('pageTitle', 'Detail Arsip')

@section('content')
<div class="max-w-6xl mx-auto flex flex-col gap-6">
    <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-4">
        <h1 class="text-xl font-bold text-slate-800">
            Detail Arsip: <span class="text-aris-600">{{ $archive->title }}</span>
        </h1>
    </div>

    @if (session('success'))
    <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 text-sm px-4 py-3 rounded-lg">
        {{ session('success') }}
    </div>
    @endif
    @if (session('error'))
    <div class="bg-red-50 border border-red-200 text-red-700 text-sm px-4 py-3 rounded-lg">
        {{ session('error') }}
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

        {{-- KOLOM KIRI: Detail Arsip --}}
        <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col gap-6">

            <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                <div class="w-14 h-14 rounded-xl bg-aris-50 flex items-center justify-center text-aris-600 text-xl shrink-0">
                    <i class="fa-solid fa-file-lines"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-slate-800 truncate">
                        {{ $archive->original_filename ?? basename($archive->file_path) }}
                    </p>
                    <p class="text-xs text-slate-400 mt-0.5">
                        {{ strtoupper($archive->file_type ?? '-') }}
                        @if ($archive->file_size)
                        &middot; {{ number_format($archive->file_size / 1024, 1) }} KB
                        @endif
                    </p>
                </div>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Kategori</p>
                    <p class="text-sm text-slate-800">{{ $archive->category ?? '-' }}</p>
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Diunggah Oleh</p>
                    <p class="text-sm text-slate-800">{{ $archive->user->name ?? '-' }}</p>
                </div>
            </div>

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Deskripsi</p>
                <p class="text-sm text-slate-600 leading-relaxed">
                    {{ $archive->description ?? 'Tidak ada deskripsi untuk arsip ini.' }}
                </p>
            </div>

            {{-- HASIL OCR --}}
            <div class="pt-6 border-t border-slate-100">
                <div class="flex items-center justify-between gap-3 mb-3">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400">Teks Hasil OCR</p>
                    @include('archives._ocr-badge', ['archive' => $archive])
                </div>

                @switch($archive->ocr_status)
                @case('done')
                <p class="text-xs text-slate-400 mb-3">
                    {{ $archive->ocr_pages }} halaman
                    &middot; {{ number_format($archive->ocr_seconds ?? 0, 1) }} detik
                    @if ($archive->ocr_at)
                    &middot; selesai {{ $archive->ocr_at->timezone('Asia/Jakarta')->format('d M Y, H:i') }} WIB
                    @endif
                </p>

                {{-- Catatan akurasi OCR --}}
                <div class="flex gap-2.5 bg-amber-50 border border-amber-100 rounded-lg px-4 py-3 mb-3">
                    <i class="fa-solid fa-circle-info text-amber-500 mt-0.5 text-sm"></i>
                    <div class="text-xs text-amber-800 leading-relaxed">
                        <p class="font-semibold">Teks dihasilkan otomatis dan tidak 100% akurat.</p>
                        <p class="mt-0.5">
                            Hasil bergantung pada kualitas berkas (kejelasan scan/foto, kemiringan, pencahayaan, resolusi).
                            Mungkin ada huruf atau kata yang salah baca. Gunakan sebagai alat bantu pencarian,
                            dan rujuk berkas asli untuk kutipan resmi.
                        </p>
                    </div>
                </div>

                @if (filled($archive->ocr_text))

                <details class="group border border-slate-200 rounded-lg">
                    <summary class="cursor-pointer select-none px-4 py-2.5 text-sm font-medium text-slate-700 hover:bg-slate-50 rounded-lg">
                        Tampilkan / sembunyikan teks
                    </summary>
                    <div class="px-4 py-3 border-t border-slate-100 max-h-96 overflow-y-auto">
                        <pre class="whitespace-pre-wrap break-words text-xs text-slate-600 font-mono">{{ $archive->ocr_text }}</pre>
                    </div>
                </details>
                @else
                <p class="text-sm text-slate-400">Tidak ada teks yang terbaca dari berkas ini.</p>
                @endif
                @break

                @case('failed')
                <div class="bg-red-50 border border-red-100 rounded-lg px-4 py-3 text-sm text-red-700">
                    <p class="font-medium">OCR gagal diproses.</p>
                    @if ($archive->ocr_error)
                    <p class="text-xs mt-1 break-words">{{ $archive->ocr_error }}</p>
                    @endif
                </div>
                {{-- RETRY --}}
                @can('edit archives')
                <form action="{{ route('archives.ocr-retry', $archive) }}" method="POST" class="mt-3">
                    @csrf
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2 rounded-lg transition">
                        <i class="fa-solid fa-rotate-right"></i>
                        Proses ulang OCR
                    </button>
                </form>
                @endcan
                @break

                @case('pending')
                @case('processing')
                <p class="text-sm text-slate-500">
                    OCR sedang dalam antrean atau diproses. Halaman ini diperbarui otomatis.
                </p>
                @break

                @default
                <p class="text-sm text-slate-400">
                    Tipe berkas ini tidak didukung OCR (hanya PDF dan gambar).
                </p>
                @endswitch
            </div>

            @can('delete archives')
            <div class="bg-white rounded-xl border border-red-100 p-5 flex items-center justify-between mt-2">
                <div>
                    <p class="text-sm font-semibold text-slate-800">Hapus Arsip</p>
                    <p class="text-xs text-slate-500 mt-0.5">Tindakan ini tidak dapat dibatalkan.</p>
                </div>
                <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                    onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium px-4 py-2.5 rounded-lg transition">
                        <i class="fa-solid fa-trash"></i>
                        Hapus
                    </button>
                </form>
            </div>
            @endcan
        </div>

        {{-- KOLOM KANAN: Status & Aksi --}}
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col gap-6">

            <div>
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Status Saat Ini</p>
                @php
                $statusColor = match ($archive->status) {
                'active' => 'bg-emerald-50 text-emerald-600',
                'archived' => 'bg-amber-50 text-amber-600',
                default => 'bg-red-50 text-red-600',
                };
                @endphp
                <span class="inline-flex items-center gap-2 text-sm font-medium px-3 py-1.5 rounded-full {{ $statusColor }}">
                    <i class="fa-solid fa-circle text-[8px]"></i>
                    {{ ucfirst($archive->status) }}
                </span>
            </div>

            <div class="pt-4 border-t border-slate-100">
                <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Tanggal Unggah</p>
                <p class="text-sm text-slate-800 flex items-center gap-2">
                    <i class="fa-regular fa-calendar text-slate-400"></i>
                    {{ $archive->created_at->format('d M Y, H:i') }} WIB
                </p>
            </div>

            <div class="flex flex-col gap-2 pt-4 border-t border-slate-100">
                @can('download archives')
                <a href="{{ route('archives.download', $archive) }}"
                    class="inline-flex items-center justify-center gap-2 bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-download"></i>
                    Unduh File
                </a>
                @endcan
                @can('edit archives')
                <a href="{{ route('archives.edit', $archive) }}"
                    class="inline-flex items-center justify-center gap-2 bg-white border border-slate-200 hover:bg-slate-50 text-slate-700 text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-pen"></i>
                    Edit
                </a>
                @endcan
                <a href="{{ route('archives.index') }}"
                    class="inline-flex items-center justify-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar
                </a>
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