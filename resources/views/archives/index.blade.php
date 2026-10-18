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
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    <i class="fa-solid fa-upload text-xs"></i>
                    Unggah arsip
                </a>
            @endcan
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

        {{-- Pencarian --}}
        <form method="GET" action="{{ route('archives.index') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
            <div class="relative flex-1">
                <i class="fa-solid fa-magnifying-glass absolute left-3.5 top-1/2 -translate-y-1/2 text-steel text-sm"></i>
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari judul, kategori, deskripsi, atau isi dokumen..."
                    class="w-full pl-10 pr-4 py-3 bg-white text-sm border border-line rounded outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors">
            </div>
            <div class="flex items-center gap-4">
                <button type="submit"
                    class="px-6 py-3 text-sm font-semibold bg-ink text-white rounded hover:bg-ink/90 transition-colors">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('archives.index') }}"
                        class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                        Hapus pencarian
                    </a>
                @endif
            </div>
        </form>

        <div class="bg-white border border-line rounded border-t-2 border-t-ink">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[64rem]">
                    <thead class="text-steel">
                        <tr class="border-b border-line">
                            <th class="px-5 py-4 font-semibold">Judul arsip</th>
                            <th class="px-5 py-4 font-semibold">Kategori</th>
                            <th class="px-5 py-4 font-semibold">Deskripsi</th>
                            <th class="px-5 py-4 font-semibold">Diunggah oleh</th>
                            <th class="px-5 py-4 font-semibold">Status</th>
                            <th class="px-5 py-4 font-semibold">OCR</th>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($archives as $archive)
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
                            @endphp
                            <tr class="hover:bg-paper/70 align-top">
                                <td class="px-5 py-4 font-semibold">{{ $archive->title }}</td>
                                <td class="px-5 py-4 text-steel">{{ $archive->category ?? '-' }}</td>
                                <td class="px-5 py-4 text-steel max-w-xs">
                                    <p class="line-clamp-2">{{ $archive->description ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-4 text-steel">{{ $archive->user->name ?? '-' }}</td>
                                <td class="px-5 py-4">
                                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-sm {{ $statusClass }}">
                                        {{ $statusText }}
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    @include('archives._ocr-badge', ['archive' => $archive])
                                </td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">{{ $archive->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-4 whitespace-nowrap">
                                        @can('show archives')
                                            <a href="{{ route('archives.show', $archive) }}"
                                                class="text-ink font-semibold underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">
                                                Detail
                                            </a>
                                        @endcan
                                        @can('edit archives')
                                            <a href="{{ route('archives.edit', $archive) }}"
                                                class="text-steel font-semibold underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                                                Ubah
                                            </a>
                                        @endcan
                                        @can('delete archives')
                                            <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                                                onsubmit="return confirm('Hapus arsip ini? Tindakan ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-700 font-semibold underline underline-offset-4 decoration-red-200 hover:decoration-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-5 py-12 text-center text-steel">
                                    Belum ada arsip yang tersedia.
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