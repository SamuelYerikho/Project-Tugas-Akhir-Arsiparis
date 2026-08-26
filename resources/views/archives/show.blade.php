@extends('layouts.app')

@section('pageTitle', 'Detail Arsip')

@section('content')
    <div class="max-w-6xl mx-auto flex flex-col gap-6">
        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-4">
            <h1 class="text-xl font-bold text-slate-800">
                Detail Arsip: <span class="text-aris-600">{{ $archive->title }}</span>
            </h1>
        </div>

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
@endsection