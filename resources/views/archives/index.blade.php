@extends('layouts.app')

@section('pageTitle', 'Manajemen Arsip')

@section('content')
    <div class="flex flex-col gap-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Manajemen Arsip</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola seluruh arsip yang telah diunggah.</p>
            </div>

            @can('upload archives')
                <a href="{{ route('archives.create') }}"
                    class="inline-flex items-center gap-2 bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-upload"></i>
                    Unggah Arsip
                </a>
            @endcan
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-4">
            <form method="GET" action="{{ route('archives.index') }}" class="flex gap-3">
                <div class="relative flex-1">
                    <i class="fa-solid fa-magnifying-glass absolute left-3 top-1/2 -translate-y-1/2 text-slate-400 text-sm"></i>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari judul, kategori, atau deskripsi arsip..."
                        class="w-full pl-9 pr-4 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500">
                </div>
                <button type="submit"
                    class="px-4 py-2.5 text-sm font-medium bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-lg transition">
                    Cari
                </button>
                @if (request('search'))
                    <a href="{{ route('archives.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-slate-500 hover:text-slate-700 transition">
                        Reset
                    </a>
                @endif
            </form>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Judul Arsip</th>
                            <th class="px-5 py-3">Kategori</th>
                            <th class="px-5 py-3">Deskripsi</th>
                            <th class="px-5 py-3">Diunggah Oleh</th>
                            <th class="px-5 py-3">Status</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($archives as $archive)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5">
                                    <p class="font-medium text-slate-800">{{ $archive->title }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $archive->category ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-500 max-w-xs">
                                    <p class="line-clamp-2">{{ $archive->description ?? '-' }}</p>
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $archive->user->name ?? '-' }}</td>
                                <td class="px-5 py-3.5">
                                    @php
                                        $statusColor = match ($archive->status) {
                                            'active' => 'bg-emerald-50 text-emerald-600',
                                            'archived' => 'bg-amber-50 text-amber-600',
                                            default => 'bg-red-50 text-red-600',
                                        };
                                    @endphp
                                    <span class="text-xs font-medium px-2.5 py-1 rounded-full {{ $statusColor }}">
                                        {{ ucfirst($archive->status) }}
                                    </span>
                                </td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $archive->created_at->format('d M Y') }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-3 text-slate-400">
                                        @can('show archives')
                                            <a href="{{ route('archives.show', $archive) }}"
                                                class="hover:text-aris-600 transition" title="Lihat Detail">
                                                <i class="fa-solid fa-eye"></i>
                                            </a>
                                        @endcan
                                        @can('edit archives')
                                            <a href="{{ route('archives.edit', $archive) }}"
                                                class="hover:text-aris-600 transition" title="Edit">
                                                <i class="fa-solid fa-pen"></i>
                                            </a>
                                        @endcan
                                        @can('delete archives')
                                            <form action="{{ route('archives.destroy', $archive) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus arsip ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover:text-red-600 transition" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-5 py-10 text-center text-slate-400">
                                    Belum ada arsip yang tersedia.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($archives->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $archives->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection