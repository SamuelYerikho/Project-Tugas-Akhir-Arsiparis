@extends('layouts.app')

@section('pageTitle', 'Detail Izin Akses')

@section('content')
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
            'label' => 'Menunggu persetujuan',
        ],
    };
    $meta = 'text-xs font-semibold text-steel mb-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('access-requests.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar izin akses
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Permintaan izin akses</h2>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 items-start">

            {{-- KIRI: rincian permintaan --}}
            <div class="lg:col-span-2 bg-surface border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-8">

                <div class="flex items-start gap-4 pb-8 border-b border-line">
                    <div class="w-12 h-12 rounded-sm bg-invert text-manila flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                        </svg>
                    </div>
                    <div class="min-w-0">
                        <p class="{{ $meta }} mb-1">Arsip yang diminta</p>
                        <a href="{{ route('archives.show', $accessRequest->archive) }}"
                            class="font-display font-bold text-xl leading-snug underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink break-words transition-colors">
                            {{ $accessRequest->archive->title ?? '—' }}
                        </a>
                        <p class="text-xs text-steel mt-2">
                            Diajukan {{ $accessRequest->created_at->format('d M Y, H:i') }} WIB
                        </p>
                    </div>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-8">
                    <div>
                        <p class="{{ $meta }}">Diajukan oleh</p>
                        <p class="text-sm font-semibold">{{ $accessRequest->user->name ?? '—' }}</p>
                        <p class="text-xs text-steel mt-0.5">{{ $accessRequest->user->email ?? '—' }}</p>
                    </div>

                    @if ($accessRequest->status !== 'pending')
                        <div>
                            <p class="{{ $meta }}">Diproses oleh</p>
                            <p class="text-sm font-semibold">{{ $accessRequest->reviewer->name ?? '—' }}</p>
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
                    <div class="bg-paper border-l-4 border-ink p-5 rounded-r">
                        <p class="{{ $meta }}">Catatan peninjau</p>
                        <p class="text-sm leading-relaxed whitespace-pre-line">{{ $accessRequest->review_note }}</p>
                    </div>
                @endif
            </div>

            {{-- KANAN: status dan aksi --}}
            <div class="bg-surface border border-line border-t-2 border-t-ink rounded p-6 space-y-6">

                <div>
                    <p class="{{ $meta }}">Status</p>
                    <span class="inline-flex items-center gap-2 text-sm font-semibold px-3 py-1.5 rounded-sm {{ $statusMeta['class'] }}">
                        <span class="w-1.5 h-1.5 rounded-full bg-current"></span>
                        {{ $statusMeta['label'] }}
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
                                    class="w-full inline-flex items-center justify-center gap-2 bg-manila text-on-manila text-sm font-semibold px-4 py-3 rounded hover:brightness-95 transition">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5"/>
                                    </svg>
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
                                    class="w-full inline-flex items-center justify-center gap-2 border border-red-700 text-red-700 dark:border-red-500 dark:text-red-400 text-sm font-semibold px-4 py-3 rounded hover:bg-red-700 hover:text-white dark:hover:bg-red-700 dark:hover:text-white transition-colors">
                                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                                    </svg>
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