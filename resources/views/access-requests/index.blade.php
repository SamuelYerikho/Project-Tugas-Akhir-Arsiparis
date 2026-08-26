@extends('layouts.app')

@section('pageTitle', 'Permintaan Izin Akses')

@section('content')
    <div class="max-w-6xl mx-auto flex flex-col gap-6">

        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Permintaan Izin Akses Arsip</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola permintaan izin akses arsip dari staff.</p>
            </div>
            @can('request archive access')
                <a href="{{ route('access-requests.create') }}"
                    class="inline-flex items-center gap-2 bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-4 py-2 rounded-lg transition">
                    <i class="fa-solid fa-plus"></i>
                    Tambah Permintaan
                </a>
            @endcan
        </div>

        {{-- Filter Status --}}
        <div class="flex items-center gap-2">
            @foreach (['' => 'Semua', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
                <a href="{{ route('access-requests.index', array_filter(['status' => $value])) }}"
                    class="text-sm font-medium px-3.5 py-1.5 rounded-lg transition
                          {{ request('status', '') === $value
                              ? 'bg-aris-600 text-white'
                              : 'bg-white border border-slate-200 text-slate-600 hover:bg-slate-50' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <table class="w-full text-sm">
                <thead>
                    <tr class="bg-slate-50 border-b border-slate-200 text-left text-xs font-semibold uppercase tracking-wide text-slate-400">
                        <th class="px-6 py-3">Arsip</th>
                        <th class="px-6 py-3">Diajukan Oleh</th>
                        <th class="px-6 py-3">Status</th>
                        <th class="px-6 py-3">Tanggal Diajukan</th>
                        <th class="px-6 py-3 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($accessRequests as $accessRequest)
                        <tr class="hover:bg-slate-50/60 transition">
                            <td class="px-6 py-4">
                                <a href="{{ route('archives.show', $accessRequest->archive) }}"
                                    class="font-medium text-aris-700 hover:underline">
                                    {{ $accessRequest->archive->title ?? '-' }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-slate-700">
                                {{ $accessRequest->user->name ?? '-' }}
                            </td>
                            <td class="px-6 py-4">
                                @php
                                    $statusColor = match ($accessRequest->status) {
                                        'approved' => 'bg-emerald-50 text-emerald-600',
                                        'rejected' => 'bg-red-50 text-red-600',
                                        default => 'bg-amber-50 text-amber-600',
                                    };
                                    $statusLabel = match ($accessRequest->status) {
                                        'approved' => 'Disetujui',
                                        'rejected' => 'Ditolak',
                                        default => 'Menunggu',
                                    };
                                @endphp
                                <span class="inline-block text-xs font-medium px-2.5 py-1 rounded-full {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $accessRequest->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-6 py-4">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('access-requests.show', $accessRequest) }}"
                                        title="Lihat Detail"
                                        class="inline-flex items-center justify-center w-8 h-8 bg-slate-50 hover:bg-slate-100 text-slate-500 rounded-lg transition">
                                        <i class="fa-solid fa-eye"></i>
                                    </a>
                                    @if ($accessRequest->status === 'pending')
                                        @can('approve archive access')
                                            <form action="{{ route('access-requests.approve', $accessRequest) }}" method="POST"
                                                onsubmit="return confirm('Setujui permintaan akses ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" title="Setujui"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 rounded-lg transition">
                                                    <i class="fa-solid fa-check"></i>
                                                </button>
                                            </form>
                                        @endcan
                                        @can('reject archive access')
                                            <form action="{{ route('access-requests.reject', $accessRequest) }}" method="POST"
                                                onsubmit="return confirm('Tolak permintaan akses ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit" title="Tolak"
                                                    class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">
                                                    <i class="fa-solid fa-xmark"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                    @can('delete archive access request')
                                        <form action="{{ route('access-requests.destroy', $accessRequest) }}" method="POST"
                                            onsubmit="return confirm('Hapus permintaan akses ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" title="Hapus"
                                                class="inline-flex items-center justify-center w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition">
                                                <i class="fa-solid fa-trash"></i>
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-slate-400 text-sm">
                                Belum ada permintaan izin akses.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($accessRequests->hasPages())
            <div>
                {{ $accessRequests->links() }}
            </div>
        @endif

    </div>
@endsection