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
    <dl class="grid grid-cols-2 lg:grid-cols-4 bg-surface border border-line border-t-4 border-t-manila rounded divide-x divide-y lg:divide-y-0 divide-line">

        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['archives']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-3 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5M10 11.25h4M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>
                </svg>
                Total arsip
            </dt>
        </div>

        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['categories']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-3 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 6h.008v.008H6V6Z"/>
                </svg>
                Kategori
            </dt>
        </div>

        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['reports']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-3 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                </svg>
                Laporan dibuat
            </dt>
        </div>

        <div class="p-6 sm:p-8">
            <dd class="font-display font-extrabold text-5xl tracking-tight">{{ number_format($stats['users']) }}</dd>
            <dt class="text-sm text-steel font-medium mt-3 flex items-center gap-2">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 19.128a9.38 9.38 0 0 0 2.625.372 9.337 9.337 0 0 0 4.121-.952 4.125 4.125 0 0 0-7.533-2.493M15 19.128v-.003c0-1.113-.285-2.16-.786-3.07M15 19.128v.106A12.318 12.318 0 0 1 8.624 21c-2.331 0-4.512-.645-6.374-1.766l-.001-.109a6.375 6.375 0 0 1 11.964-3.07M12 6.375a3.375 3.375 0 1 1-6.75 0 3.375 3.375 0 0 1 6.75 0Zm8.25 2.25a2.625 2.625 0 1 1-5.25 0 2.625 2.625 0 0 1 5.25 0Z"/>
                </svg>
                Total pengguna
            </dt>
        </div>

    </dl>

    <div class="mt-10 grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

        {{-- Arsip terbaru --}}
        <div class="lg:col-span-2 bg-surface border border-line rounded">
            <div class="flex items-center justify-between px-5 pt-5 pb-3">
                <p class="font-display font-bold text-lg">Arsip terbaru</p>
                @can('view archives')
                <a href="{{ route('archives.index') }}" class="text-xs font-semibold underline underline-offset-4 decoration-line hover:decoration-ink transition-colors">
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
                <svg class="w-10 h-10 text-steel/70 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"/>
                </svg>
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
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                        </svg>
                    </span>
                    @else
                    <a href="{{ $recentArchives->previousPageUrl() }}"
                        class="px-3 py-1.5 text-xs font-semibold border border-line rounded hover:bg-paper transition-colors">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 19.5 8.25 12l7.5-7.5"/>
                        </svg>
                    </a>
                    @endif

                    <span class="text-xs font-semibold px-2">
                        {{ $recentArchives->currentPage() }} / {{ $recentArchives->lastPage() }}
                    </span>

                    @if ($recentArchives->hasMorePages())
                    <a href="{{ $recentArchives->nextPageUrl() }}"
                        class="px-3 py-1.5 text-xs font-semibold border border-line rounded hover:bg-paper transition-colors">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </a>
                    @else
                    <span class="px-3 py-1.5 text-xs font-semibold text-steel border border-line rounded opacity-50 cursor-not-allowed">
                        <svg class="w-3 h-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5"/>
                        </svg>
                    </span>
                    @endif
                </div>
            </div>
            @endif
        </div>

        {{-- Pintasan, hanya menu yang boleh diakses pengguna --}}
        <div class="bg-surface border border-line border-t-2 border-t-ink rounded">
            <p class="px-5 pt-5 pb-3 font-display font-bold text-lg">Mulai dari sini</p>
            <ul class="divide-y divide-line">
                @can('upload archives')
                <li>
                    <a href="{{ route('archives.create') }}" class="flex items-center gap-3 px-5 py-4 text-sm font-semibold hover:bg-paper transition-colors">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        <span class="flex-1">Arsipkan dokumen</span>
                        <svg class="w-3.5 h-3.5 text-steel" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </li>
                @endcan
                @can('view archives')
                <li>
                    <a href="{{ route('archives.index') }}" class="flex items-center gap-3 px-5 py-4 text-sm font-semibold hover:bg-paper transition-colors">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                        </svg>
                        <span class="flex-1">Cari di daftar arsip</span>
                        <svg class="w-3.5 h-3.5 text-steel" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </li>
                @endcan
                @can('view reports')
                <li>
                    <a href="{{ route('reports.index') }}" class="flex items-center gap-3 px-5 py-4 text-sm font-semibold hover:bg-paper transition-colors">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                        </svg>
                        <span class="flex-1">Lihat laporan</span>
                        <svg class="w-3.5 h-3.5 text-steel" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3"/>
                        </svg>
                    </a>
                </li>
                @endcan
            </ul>
        </div>

    </div>

</div>
@endsection