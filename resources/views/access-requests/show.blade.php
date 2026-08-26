@extends('layouts.app')

@section('pageTitle', 'Detail Permintaan Izin Akses')

@section('content')
    <div class="max-w-6xl mx-auto flex flex-col gap-6">

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm px-6 py-4 flex items-center justify-between">
            <h1 class="text-xl font-bold text-slate-800">
                Permintaan Akses: <span class="text-aris-600">{{ $accessRequest->archive->title ?? '-' }}</span>
            </h1>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            {{-- KOLOM KIRI: Detail Permintaan --}}
            <div class="lg:col-span-2 bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col gap-6">

                <div class="flex items-center gap-4 pb-6 border-b border-slate-100">
                    <div class="w-14 h-14 rounded-xl bg-aris-50 flex items-center justify-center text-aris-600 text-xl shrink-0">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div class="min-w-0">
                        <a href="{{ route('archives.show', $accessRequest->archive) }}"
                            class="font-semibold text-slate-800 hover:text-aris-600 hover:underline truncate block">
                            {{ $accessRequest->archive->title ?? '-' }}
                        </a>
                        <p class="text-xs text-slate-400 mt-0.5">
                            Diajukan {{ $accessRequest->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Diajukan Oleh</p>
                        <p class="text-sm text-slate-800">{{ $accessRequest->user->name ?? '-' }}</p>
                        <p class="text-xs text-slate-400 mt-0.5">{{ $accessRequest->user->email ?? '-' }}</p>
                    </div>

                    @if ($accessRequest->status !== 'pending')
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Diproses Oleh</p>
                            <p class="text-sm text-slate-800">{{ $accessRequest->reviewer->name ?? '-' }}</p>
                            @if ($accessRequest->reviewed_at)
                                <p class="text-xs text-slate-400 mt-0.5">{{ $accessRequest->reviewed_at->format('d M Y, H:i') }} WIB</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Alasan Pengajuan</p>
                    <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">
                        {{ $accessRequest->reason ?? 'Tidak ada alasan yang diberikan.' }}
                    </p>
                </div>

                @if ($accessRequest->status !== 'pending' && $accessRequest->review_note)
                    <div class="bg-slate-50 rounded-xl border border-slate-100 p-5">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Catatan Reviewer</p>
                        <p class="text-sm text-slate-600 leading-relaxed whitespace-pre-line">{{ $accessRequest->review_note }}</p>
                    </div>
                @endif
            </div>

            {{-- KOLOM KANAN: Status & Aksi --}}
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6 flex flex-col gap-6">

                <div>
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-2">Status Permintaan</p>
                    @php
                        $statusColor = match ($accessRequest->status) {
                            'approved' => 'bg-emerald-50 text-emerald-600',
                            'rejected' => 'bg-red-50 text-red-600',
                            default => 'bg-amber-50 text-amber-600',
                        };
                        $statusLabel = match ($accessRequest->status) {
                            'approved' => 'Disetujui',
                            'rejected' => 'Ditolak',
                            default => 'Menunggu Persetujuan',
                        };
                    @endphp
                    <span class="inline-flex items-center gap-2 text-sm font-medium px-3 py-1.5 rounded-full {{ $statusColor }}">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="pt-4 border-t border-slate-100">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Tanggal Diajukan</p>
                    <p class="text-sm text-slate-800 flex items-center gap-2">
                        <i class="fa-regular fa-calendar text-slate-400"></i>
                        {{ $accessRequest->created_at->format('d M Y, H:i') }} WIB
                    </p>
                </div>

                @if ($accessRequest->status === 'pending')
                    <div class="flex flex-col gap-2 pt-4 border-t border-slate-100">
                        @can('approve archive access')
                            <form action="{{ route('access-requests.approve', $accessRequest) }}" method="POST"
                                onsubmit="return confirm('Setujui permintaan akses ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-emerald-50 hover:bg-emerald-100 text-emerald-600 text-sm font-medium px-4 py-2.5 rounded-lg transition">
                                    <i class="fa-solid fa-check"></i>
                                    Setujui Permintaan
                                </button>
                            </form>
                        @endcan
                        @can('reject archive access')
                            <form action="{{ route('access-requests.reject', $accessRequest) }}" method="POST"
                                onsubmit="return confirm('Tolak permintaan akses ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-red-50 hover:bg-red-100 text-red-600 text-sm font-medium px-4 py-2.5 rounded-lg transition">
                                    <i class="fa-solid fa-xmark"></i>
                                    Tolak Permintaan
                                </button>
                            </form>
                        @endcan
                    </div>
                @else
                    <div class="pt-4 border-t border-slate-100">
                        <p class="text-xs font-semibold uppercase tracking-wide text-slate-400 mb-1">Diproses Oleh</p>
                        <p class="text-sm text-slate-800">{{ $accessRequest->reviewer->name ?? '-' }}</p>
                        @if ($accessRequest->reviewed_at)
                            <p class="text-xs text-slate-400 mt-0.5">{{ $accessRequest->reviewed_at->format('d M Y, H:i') }} WIB</p>
                        @endif
                    </div>
                @endif

                <a href="{{ route('access-requests.index') }}"
                    class="inline-flex items-center justify-center gap-2 text-slate-500 hover:text-slate-700 text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-arrow-left"></i>
                    Kembali ke Daftar
                </a>
            </div>

        </div>
    </div>
@endsection