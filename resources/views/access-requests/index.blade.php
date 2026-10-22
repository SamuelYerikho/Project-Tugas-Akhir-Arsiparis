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
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
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

        <div class="bg-surface border border-line rounded border-t-2 border-t-ink overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[56rem]">
                    <thead class="bg-paper/60 text-steel text-xs uppercase tracking-wider">
                        <tr class="border-b border-line">
                            <th class="px-5 py-3.5 font-semibold">Arsip</th>
                            <th class="px-5 py-3.5 font-semibold">Diajukan oleh</th>
                            <th class="px-5 py-3.5 font-semibold">Status</th>
                            <th class="px-5 py-3.5 font-semibold">Tanggal diajukan</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($accessRequests as $accessRequest)
                            @php
                                $statusMeta = match ($accessRequest->status) {
                                    'approved' => [
                                        'class' => 'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
                                        'label' => 'Disetujui',
                                    ],
                                    'rejected' => [
                                        'class' => 'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
                                        'label' => 'Ditolak',
                                    ],
                                    default => [
                                        'class' => 'bg-manila/30 text-on-manila dark:bg-manila/25 dark:text-manila',
                                        'label' => 'Menunggu',
                                    ],
                                };
                            @endphp
                            <tr class="hover:bg-paper align-top transition-colors">
                                <td class="px-5 py-4">
                                    <a href="{{ route('archives.show', $accessRequest->archive) }}"
                                        class="font-semibold underline underline-offset-4 decoration-line hover:decoration-ink transition-colors">
                                        {{ $accessRequest->archive->title ?? '—' }}
                                    </a>
                                </td>
                                <td class="px-5 py-4 text-steel">
                                    {{ $accessRequest->user->name ?? '—' }}
                                </td>
                                <td class="px-5 py-4">
                                    <span class="inline-block text-xs font-semibold px-2.5 py-1 rounded-sm {{ $statusMeta['class'] }}">
                                        {{ $statusMeta['label'] }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">
                                    {{ $accessRequest->created_at->format('d M Y, H:i') }}
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        <a href="{{ route('access-requests.show', $accessRequest) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-ink hover:border-ink transition-colors">
                                            <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178Z"/>
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z"/>
                                            </svg>
                                            Detail
                                        </a>
                                        @if ($accessRequest->status === 'pending')
                                            @can('approve archive access')
                                                <form action="{{ route('access-requests.approve', $accessRequest) }}" method="POST"
                                                    onsubmit="return confirm('Setujui permintaan akses ini?')">
                                                    @csrf
                                                    @method('PUT')
                                                    <button type="submit"
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-emerald-800 dark:text-emerald-400 hover:border-emerald-600 dark:hover:border-emerald-500 hover:bg-emerald-50 dark:hover:bg-emerald-900/20 transition-colors">
                                                        <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                                        </svg>
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
                                                        class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-red-700 dark:text-red-400 hover:border-red-600 dark:hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                        <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" aria-hidden="true">
                                                            <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                                        </svg>
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
                                <td colspan="5" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-steel/70 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-steel">Belum ada permintaan izin akses.</p>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($accessRequests->hasPages())
            <div class="mt-6">
                {{ $accessRequests->links() }}
            </div>
        @endif

    </div>
@endsection