@extends('layouts.app')

@section('pageTitle', 'Ajukan Izin Akses')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors disabled:bg-paper disabled:text-steel disabled:cursor-not-allowed';
    $label = 'block text-sm font-semibold mb-1.5';
    $noArchives = $archives->isEmpty();
    $disabled = $hasFullAccess || $noArchives;
@endphp

    <div class="max-w-2xl">

        <div class="mb-8">
            <a href="{{ route('access-requests.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke daftar izin akses
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ajukan izin akses arsip</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Pilih arsip yang ingin Anda akses, lalu jelaskan alasannya.</p>
        </div>

        <form action="{{ route('access-requests.store') }}" method="POST"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf

            @if ($hasFullAccess)
                {{-- Punya izin 'view all archives' (termasuk Super Admin) --}}
                <div class="flex gap-3 bg-paper border-l-4 border-ink px-4 py-4">
                    <i class="fa-solid fa-circle-info text-ink mt-0.5 text-sm"></i>
                    <div class="text-xs text-ink leading-relaxed">
                        <p class="font-bold text-sm">Anda memiliki akses ke semua arsip</p>
                        <p class="mt-1.5 text-steel">
                            Karena Anda memiliki izin untuk melihat seluruh arsip, semua dokumen bisa Anda buka
                            langsung tanpa perlu meminta izin. Silakan buka dari
                            <a href="{{ route('archives.index') }}"
                                class="font-semibold text-ink underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">Daftar Arsip</a>.
                        </p>
                    </div>
                </div>
            @elseif ($noArchives)
                {{-- Pengguna biasa, tapi tidak ada arsip yang bisa diajukan --}}
                <div class="flex gap-3 bg-paper border-l-4 border-ink px-4 py-4">
                    <i class="fa-solid fa-circle-info text-ink mt-0.5 text-sm"></i>
                    <div class="text-xs text-ink leading-relaxed">
                        <p class="font-bold text-sm">Belum ada arsip yang bisa diajukan</p>
                        <p class="mt-1.5 text-steel">
                            Saat ini belum ada arsip milik pengguna lain yang perlu Anda ajukan aksesnya.
                        </p>
                    </div>
                </div>
            @endif

            <div>
                <label for="archive_id" class="{{ $label }}">Arsip</label>
                <select name="archive_id" id="archive_id" @disabled($disabled)
                    class="{{ $input }} @error('archive_id') border-red-600 @else border-line @enderror">
                    <option value="">{{ $disabled ? 'Tidak ada arsip tersedia' : 'Pilih arsip' }}</option>
                    @foreach ($archives as $archive)
                        <option value="{{ $archive->id }}" {{ old('archive_id') == $archive->id ? 'selected' : '' }}>
                            {{ $archive->title }}
                        </option>
                    @endforeach
                </select>
                @error('archive_id')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror

                {{-- Catatan: arsip sendiri tidak ditampilkan (hanya untuk pengguna biasa) --}}
                @unless ($hasFullAccess)
                    <div class="flex gap-3 bg-paper border-l-4 border-ink px-4 py-4 mt-4">
                        <i class="fa-solid fa-circle-info text-ink mt-0.5 text-sm"></i>
                        <div class="text-xs text-ink leading-relaxed">
                            <p class="font-bold text-sm">Arsip Anda tidak ada di daftar ini</p>
                            <p class="mt-1.5 text-steel">
                                Arsip yang Anda unggah sendiri tidak ditampilkan di sini karena Anda sudah otomatis
                                memiliki akses penuh. Arsip yang permintaannya sudah disetujui juga tidak muncul lagi.
                            </p>
                        </div>
                    </div>
                @endunless
            </div>

            <div>
                <label for="reason" class="{{ $label }}">Alasan <span class="font-normal text-steel">(boleh dikosongkan)</span></label>
                <textarea name="reason" id="reason" rows="5" @disabled($disabled)
                    class="{{ $input }} leading-relaxed @error('reason') border-red-600 @else border-line @enderror"
                    placeholder="Jelaskan mengapa Anda membutuhkan akses ke arsip ini...">{{ old('reason') }}</textarea>
                @error('reason')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('access-requests.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit" @disabled($disabled)
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition disabled:opacity-50 disabled:cursor-not-allowed disabled:hover:brightness-100">
                    Kirim permintaan
                </button>
            </div>
        </form>
    </div>
@endsection