@extends('layouts.app')

@section('pageTitle', 'Detail Izin Akses')

@section('content')
@php
    $statusClass = match ($accessRequest->status) {
        'approved' => 'bg-emerald-100 text-emerald-800',
        'rejected' => 'bg-red-100 text-red-800',
        default => 'bg-manila/40 text-ink',
    };
    $statusLabel = match ($accessRequest->status) {
        'approved' => 'Disetujui',
        'rejected' => 'Ditolak',
        default => 'Menunggu persetujuan',
    };
    $meta = 'text-xs font-semibold text-steel mb-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('access-requests.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke daftar izin akses
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Permintaan izin akses</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KIRI: rincian permintaan --}}
            <div class="lg:col-span-2 bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-8">

                <div class="flex items-start gap-4 pb-8 border-b border-line">
                    <div class="w-12 h-12 rounded-sm bg-ink text-manila flex items-center justify-center text-lg shrink-0">
                        <i class="fa-solid fa-file-shield"></i>
                    </div>
                    <div class="min-w-0">
                        <p class="{{ $meta }} mb-1">Arsip yang diminta</p>
                        <a href="{{ route('archives.show', $accessRequest->archive) }}"
                            class="font-display font-bold text-xl leading-snug underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink break-words">
                            {{ $accessRequest->archive->title ?? '-' }}
                        </a>
                        <p class="text-xs text-steel mt-2">
                            Diajukan {{ $accessRequest->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <p class="{{ $meta }}">Diajukan oleh</p>
                        <p class="text-sm font-semibold">{{ $accessRequest->user->name ?? '-' }}</p>
                        <p class="text-xs text-steel mt-0.5">{{ $accessRequest->user->email ?? '-' }}</p>
                    </div>

                    @if ($accessRequest->status !== 'pending')
                        <div>
                            <p class="{{ $meta }}">Diproses oleh</p>
                            <p class="text-sm font-semibold">{{ $accessRequest->reviewer->name ?? '-' }}</p>
                            @if ($accessRequest->reviewed_at)
                                <p class="text-xs text-steel mt-0.5">{{ $accessRequest->reviewed_at->format('d M Y, H:i') }} WIB</p>
                            @endif
                        </div>
                    @endif
                </div>

                <div>
                    <p class="{{ $meta }}">Alasan pengajuan</p>
                    <p class="text-sm leading-relaxed whitespace-pre-line max-w-prose">
                        {{ $accessRequest->reason ?? 'Tidak ada alasan yang diberikan.' }}
                    </p>
                </div>

                @if ($accessRequest->status !== 'pending' && $accessRequest->review_note)
                    <div class="bg-paper border-l-4 border-ink p-5">
                        <p class="{{ $meta }}">Catatan peninjau</p>
                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $accessRequest->review_note }}</p>
                    </div>
                @endif
            </div>

            {{-- KANAN: status dan aksi --}}
            <div class="bg-white border border-line border-t-2 border-t-ink rounded p-6 space-y-6">

                <div>
                    <p class="{{ $meta }}">Status</p>
                    <span class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1.5 rounded-sm {{ $statusClass }}">
                        <i class="fa-solid fa-circle text-[8px]"></i>
                        {{ $statusLabel }}
                    </span>
                </div>

                @if ($accessRequest->status === 'pending')
                    <div class="flex flex-col gap-3 pt-6 border-t border-line">
                        @can('approve archive access')
                            <form action="{{ route('access-requests.approve', $accessRequest) }}" method="POST"
                                onsubmit="return confirm('Setujui permintaan akses ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 bg-manila text-ink text-sm font-semibold px-4 py-3 rounded hover:brightness-95 transition">
                                    <i class="fa-solid fa-check"></i>
                                    Setujui permintaan
                                </button>
                            </form>
                        @endcan
                        @can('reject archive access')
                            <form action="{{ route('access-requests.reject', $accessRequest) }}" method="POST"
                                onsubmit="return confirm('Tolak permintaan akses ini?')">
                                @csrf
                                @method('PUT')
                                <button type="submit"
                                    class="w-full inline-flex items-center justify-center gap-2 border border-red-700 text-red-700 text-sm font-semibold px-4 py-3 rounded hover:bg-red-700 hover:text-white transition-colors">
                                    <i class="fa-solid fa-xmark"></i>
                                    Tolak permintaan
                                </button>
                            </form>
                        @endcan
                    </div>
                @endif
            </div>

        </div>
    </div>
@endsection