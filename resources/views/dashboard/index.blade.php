@extends('layouts.app')

@section('pageTitle', 'Beranda')

@section('content')
<div class="max-w-6xl">

    <div class="mb-10">
        <h2 class="font-display font-extrabold text-4xl tracking-tight leading-tight">
            Selamat datang, {{ auth()->user()->name ?? 'Pengguna' }}
        </h2>
        <p class="text-steel text-sm mt-3 max-w-lg">
            Ringkasan aktivitas arsip di Sistem ARIS - RRI Stasiun Batam.
        </p>
    </div>

    {{-- Angka ringkasan: satu deret bersekat, bukan kartu terpisah --}}
    <dl class="grid grid-cols-2 lg:grid-cols-4 bg-white border border-line border-t-4 border-t-manila rounded divide-x divide-y lg:divide-y-0 divide-line">
        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['archives']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-2">Total arsip</dt>
        </div>
        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['categories']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-2">Kategori</dt>
        </div>
        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['reports']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-2">Laporan dibuat</dt>
        </div>
        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['users']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-2">Total pengguna</dt>
        </div>
    </dl>

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- Arsip terbaru --}}
        <div class="lg:col-span-2 bg-white border border-line rounded">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <p class="font-display font-bold text-lg">Arsip terbaru</p>
                @can('view archives')
                <a href="{{ route('archives.index') }}" class="text-xs font-semibold underline underline-offset-4 decoration-line hover:decoration-ink">
                    Lihat semua
                </a>
                @endcan
            </div>

            @forelse ($recentArchives as $archive)
            <a href="{{ route('archives.show', $archive) }}"
                class="flex items-center justify-between gap-4 px-5 py-4 border-t border-line hover:bg-paper transition-colors">
                <div class="min-w-0">
                    <p class="text-sm font-semibold truncate">{{ $archive->title }}</p>
                    <p class="text-xs text-steel mt-1">
                        {{ $archive->category ?: 'Tanpa kategori' }} · {{ $archive->user->name ?? '-' }}
                    </p>
                </div>
                <span class="text-xs text-steel shrink-0">{{ $archive->created_at->format('d M Y') }}</span>
            </a>
            @empty
            <div class="border-t border-line px-6 py-12 text-center">
                <i class="fa-solid fa-folder-open text-2xl text-steel mb-3"></i>
                <p class="text-sm font-medium text-steel">Belum ada arsip.</p>
            </div>
            @endforelse

            @if ($recentArchives->hasPages())
            <div class="flex items-center justify-between gap-4 px-5 py-4 border-t border-line">
                <p class="text-xs text-steel">
                    {{ $recentArchives->firstItem() }}–{{ $recentArchives->lastItem() }}
                    dari {{ number_format($recentArchives->total()) }} arsip
                </p>

                <div class="flex items-center gap-2">
                    @if ($recentArchives->onFirstPage())
                    <span class="px-3 py-1.5 text-xs font-semibold text-steel border border-line rounded opacity-50 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </span>
                    @else
                    <a href="{{ $recentArchives->previousPageUrl() }}"
                        class="px-3 py-1.5 text-xs font-semibold border border-line rounded hover:bg-paper transition-colors">
                        <i class="fa-solid fa-chevron-left text-[10px]"></i>
                    </a>
                    @endif

                    <span class="text-xs font-semibold px-2">
                        {{ $recentArchives->currentPage() }} / {{ $recentArchives->lastPage() }}
                    </span>

                    @if ($recentArchives->hasMorePages())
                    <a href="{{ $recentArchives->nextPageUrl() }}"
                        class="px-3 py-1.5 text-xs font-semibold border border-line rounded hover:bg-paper transition-colors">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </a>
                    @else
                    <span class="px-3 py-1.5 text-xs font-semibold text-steel border border-line rounded opacity-50 cursor-not-allowed">
                        <i class="fa-solid fa-chevron-right text-[10px]"></i>
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Pintasan, hanya menu yang boleh diakses pengguna --}}
        <div class="bg-white border border-line border-t-2 border-t-ink rounded">
            <p class="px-5 pt-5 pb-3 font-display font-bold text-lg">Mulai dari sini</p>
            <ul class="divide-y divide-line">
                @can('upload archives')
                <li>
                    <a href="{{ route('archives.create') }}" class="flex items-center justify-between px-5 py-4 text-sm font-semibold hover:bg-paper transition-colors">
                        Arsipkan dokumen
                        <i class="fa-solid fa-arrow-right text-xs text-steel"></i>
                    </a>
                </li>
                @endcan
                @can('view archives')
                <li>
                    <a href="{{ route('archives.index') }}" class="flex items-center justify-between px-5 py-4 text-sm font-semibold hover:bg-paper transition-colors">
                        Cari di daftar arsip
                        <i class="fa-solid fa-arrow-right text-xs text-steel"></i>
                    </a>
                </li>
                @endcan
                @can('view reports')
                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center justify-between px-5 py-4 text-sm font-semibold hover:bg-paper transition-colors">
                        Lihat laporan
                        <i class="fa-solid fa-arrow-right text-xs text-steel"></i>
                    </a>
                </li>
                @endcan
            </ul>
        </div>

    </div>

</div>
@endsection