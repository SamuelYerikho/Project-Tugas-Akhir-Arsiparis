@extends('layouts.app')

@section('pageTitle', 'Izin Akses')

@section('content')
    <div class="max-w-6xl">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display font-extrabold text-3xl tracking-tight">Izin akses arsip</h2>
                <p class="text-sm text-steel mt-2 max-w-md">Tinjau dan kelola permintaan izin akses arsip dari staf.</p>
            </div>
            @can('request archive access')
                <a href="{{ route('access-requests.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Ajukan izin akses
                </a>
            @endcan
        </div>

        {{-- Filter status: tab bergaris bawah, tab aktif ditandai manila --}}
        <div class="flex flex-wrap gap-x-7 gap-y-1 border-b border-line mb-6">
            @foreach (['' => 'Semua', 'pending' => 'Menunggu', 'approved' => 'Disetujui', 'rejected' => 'Ditolak'] as $value => $label)
                <a href="{{ route('access-requests.index', array_filter(['status' => $value])) }}"
                    class="-mb-px pb-3 text-sm border-b-4 transition-colors
                          {{ request('status', '') === $value
                              ? 'border-manila text-ink font-bold'
                              : 'border-transparent text-steel font-medium hover:text-ink' }}">
                    {{ $label }}
                </a>
            @endforeach
        </div>

        <div class="bg-white border border-line rounded border-t-2 border-t-ink overflow-x-auto">
            <table class="w-full text-sm min-w-[48rem]">
                <thead class="text-steel text-left">
                    <tr class="border-b border-line">
                        <th class="px-5 py-4 font-semibold">Arsip</th>
                        <th class="px-5 py-4 font-semibold">Diajukan oleh</th>
                        <th class="px-5 py-4 font-semibold">Status</th>
                        <th class="px-5 py-4 font-semibold">Tanggal diajukan</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($accessRequests as $accessRequest)
                        @php
                            $statusClass = match ($accessRequest->status) {
                                'approved' => 'bg-emerald-100 text-emerald-800',
                                'rejected' => 'bg-red-100 text-red-800',
                                default => 'bg-manila/40 text-ink',
                            };
                            $statusLabel = match ($accessRequest->status) {
                                'approved' => 'Disetujui',
                                'rejected' => 'Ditolak',
                                default => 'Menunggu',
                            };
                        @endphp
                        <tr class="hover:bg-paper/70">
                            <td class="px-5 py-4">
                                <a href="{{ route('archives.show', $accessRequest->archive) }}"
                                    class="font-semibold underline underline-offset-4 decoration-line hover:decoration-ink">
                                    {{ $accessRequest->archive->title ?? '-' }}
                                </a>
                            </td>
                            <td class="px-5 py-4 text-steel">
                                {{ $accessRequest->user->name ?? '-' }}
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-sm {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-steel whitespace-nowrap">
                                {{ $accessRequest->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-5 py-4">
                                <div class="flex items-center justify-end gap-4 whitespace-nowrap">
                                    <a href="{{ route('access-requests.show', $accessRequest) }}"
                                        class="text-ink font-semibold underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">
                                        Detail
                                    </a>
                                    @if ($accessRequest->status === 'pending')
                                        @can('approve archive access')
                                            <form action="{{ route('access-requests.approve', $accessRequest) }}" method="POST"
                                                onsubmit="return confirm('Setujui permintaan akses ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="text-emerald-800 font-semibold underline underline-offset-4 decoration-emerald-200 hover:decoration-emerald-800">
                                                    Setujui
                                                </button>
                                            </form>
                                        @endcan
                                        @can('reject archive access')
                                            <form action="{{ route('access-requests.reject', $accessRequest) }}" method="POST"
                                                onsubmit="return confirm('Tolak permintaan akses ini?')">
                                                @csrf
                                                @method('PUT')
                                                <button type="submit"
                                                    class="text-red-700 font-semibold underline underline-offset-4 decoration-red-200 hover:decoration-red-700">
                                                    Tolak
                                                </button>
                                            </form>
                                        @endcan
                                    @endif
                                    @can('delete archive access request')
                                        <form action="{{ route('access-requests.destroy', $accessRequest) }}" method="POST"
                                            onsubmit="return confirm('Hapus permintaan akses ini secara permanen?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="text-steel font-semibold underline underline-offset-4 decoration-line hover:text-red-700 hover:decoration-red-700">
                                                Hapus
                                            </button>
                                        </form>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-steel">
                                Belum ada permintaan izin akses.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($accessRequests->hasPages())
            <div class="mt-6">
                {{ $accessRequests->links() }}
            </div>
        @endif

    </div>
@endsection