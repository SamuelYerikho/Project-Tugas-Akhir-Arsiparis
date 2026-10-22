@extends('layouts.app')

@section('pageTitle', 'Daftar Arsip')

@section('content')
    <div class="max-w-7xl">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display font-extrabold text-3xl tracking-tight">Daftar arsip</h2>
                <p class="text-sm text-steel mt-2 max-w-md">Kelola seluruh arsip yang sudah diunggah.</p>
            </div>

            @can('upload archives')
                <a href="{{ route('archives.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m3.75 9v6m3-3H9m1.5-12H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                    </svg>
                    Unggah arsip
                </a>
            @endcan
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

        {{-- Pencarian --}}
        <form method="GET" action="{{ route('archives.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1">
                <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-steel pointer-events-none" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197m0 0A7.5 7.5 0 1 0 5.196 5.196a7.5 7.5 0 0 0 10.607 10.607Z"/>
                </svg>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul, kategori, deskripsi, atau isi dokumen..."
                    class="w-full pl-10 pr-4 py-3 bg-surface text-sm text-ink placeholder:text-steel/70 border border-line rounded outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors">
            </div>
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-6 py-3 text-sm font-semibold bg-invert text-on-invert rounded hover:bg-invert/90 transition-colors">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('archives.index') }}"
                        class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                        Hapus pencarian
                    </a>
                @endif
            </div>
        </form>

        <div class="bg-surface border border-line rounded border-t-2 border-t-ink overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[68rem]">
                    <thead class="bg-paper/60 text-steel text-xs uppercase tracking-wider">
                        <tr class="border-b border-line">
                            <th class="px-5 py-3.5 font-semibold">Judul arsip</th>
                            <th class="px-5 py-3.5 font-semibold">Kategori</th>
                            <th class="px-5 py-3.5 font-semibold">Deskripsi</th>
                            <th class="px-5 py-3.5 font-semibold">Diunggah oleh</th>
                            <th class="px-5 py-3.5 font-semibold">Status</th>
                            <th class="px-5 py-3.5 font-semibold">OCR</th>
                            <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($archives as $archive)
                            @php
                                $statusMeta = match ($archive->status) {
                                    'active' => [
                                        'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                        'text'  => 'Aktif',
                                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                                    ],
                                    'archived' => [
                                        'class' => 'bg-manila/30 text-on-manila dark:bg-manila/25 dark:text-manila',
                                        'text'  => 'Diarsipkan',
                                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m20.25 7.5-.625 10.632a2.25 2.25 0 0 1-2.247 2.118H6.622a2.25 2.25 0 0 1-2.247-2.118L3.75 7.5m8.25 3v6.75m0 0-3-3m3 3 3-3M3.375 7.5h17.25c.621 0 1.125-.504 1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125H3.375c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125Z"/>',
                                    ],
                                    default => [
                                        'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'text'  => 'Dihapus',
                                        'icon'  => '<path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
                                    ],
                                };
                            @endphp
                            <tr class="hover:bg-paper align-top transition-colors">
                                <td class="px-5 py-4 font-semibold">
                                    <div class="max-w-[18rem] truncate" title="{{ $archive->title }}">{{ $archive->title }}</div>
                                </td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">{{ $archive->category ?? '—' }}</td>
                                <td class="px-5 py-4 text-steel max-w-xs">
                                    <p class="line-clamp-2" title="{{ $archive->description }}">{{ $archive->description ?? '—' }}</p>
                                </td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">{{ $archive->user->name ?? '—' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm {{ $statusMeta['class'] }}">
                                        <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">{!! $statusMeta['icon'] !!}</svg>
                                        {{ $statusMeta['text'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @include('archives._ocr-badge', ['archive' => $archive])
                                </td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">{{ $archive->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        @can('show archives')
                                            <a href="{{ route('archives.show', $archive) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-ink hover:border-ink transition-colors">
                                                <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                                </svg>
                                                Detail
                                            </a>
                                        @endcan
                                        @can('edit archives')
                                            <a href="{{ route('archives.edit', $archive) }}"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-steel hover:text-ink hover:border-ink transition-colors">
                                                <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                                </svg>
                                                Ubah
                                            </a>
                                        @endcan
                                        @can('delete archives')
                                            <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                                                onsubmit="return confirm('Hapus arsip ini? Tindakan ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-red-700 dark:text-red-400 hover:border-red-600 dark:hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-steel/70 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 9.776c.112-.017.227-.026.344-.026h15.812c.117 0 .232.009.344.026m-16.5 0a2.25 2.25 0 0 0-1.883 2.542l.857 6a2.25 2.25 0 0 0 2.227 1.932H19.05a2.25 2.25 0 0 0 2.227-1.932l.857-6a2.25 2.25 0 0 0-1.883-2.542m-16.5 0V6A2.25 2.25 0 0 1 6 3.75h3.879a1.5 1.5 0 0 1 1.06.44l2.122 2.12a1.5 1.5 0 0 0 1.06.44H18A2.25 2.25 0 0 1 20.25 9v.776"/>
                                    </svg>
                                    <p class="text-sm font-medium text-steel">Belum ada arsip yang tersedia.</p>
                                    @can('upload archives')
                                        <a href="{{ route('archives.create') }}"
                                            class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-ink underline underline-offset-4 decoration-line hover:decoration-ink transition-colors">
                                            Unggah arsip pertama
                                        </a>
                                    @endcan
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($archives->hasPages())
                <div class="px-5 py-4 border-t border-line">
                    {{ $archives->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Muat ulang otomatis selama masih ada arsip yang menunggu/diproses OCR --}}
    @if ($archives->contains(fn ($a) => in_array($a->ocr_status, ['pending', 'processing'], true)))
        <script>
            setTimeout(() => window.location.reload(), 10000);
        </script>
    @endif
@endsection