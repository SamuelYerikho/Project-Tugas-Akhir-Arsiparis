@extends('layouts.app')

@section('pageTitle', 'Unggah Arsip')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
@endphp

    <div class="max-w-2xl">

        <div class="mb-8">
            <a href="{{ route('archives.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4">
                <i class="fa-solid fa-arrow-left text-xs"></i>
                Kembali ke daftar arsip
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Unggah arsip baru</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Isi detail arsip, lalu pilih berkas yang ingin diunggah.</p>
        </div>

        <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf

            <div>
                <label for="title" class="{{ $label }}">Judul arsip</label>
                <input type="text" name="title" id="title" value="{{ old('title') }}"
                    class="{{ $input }} @error('title') border-red-600 @else border-line @enderror">
                @error('title')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="{{ $label }}">Kategori</label>
                <input type="text" name="category" id="category" value="{{ old('category') }}"
                    placeholder="Contoh: Surat Masuk, SK, Dokumentasi"
                    class="{{ $input }} @error('category') border-red-600 @else border-line @enderror">
                @error('category')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="{{ $label }}">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="{{ $input }} leading-relaxed @error('description') border-red-600 @else border-line @enderror">{{ old('description') }}</textarea>
                @error('description')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="file" class="{{ $label }}">Berkas arsip</label>
                <input type="file" name="file" id="file"
                    accept=".pdf,.png,.jpg,.jpeg,.bmp,.tif,.tiff"
                    class="w-full text-sm text-steel bg-white border rounded cursor-pointer file:mr-4 file:py-3 file:px-4 file:border-0 file:bg-ink file:text-white file:text-sm file:font-semibold hover:file:bg-ink/90 @error('file') border-red-600 @else border-line @enderror">
                <p class="text-xs text-steel mt-2">Ukuran maksimal 10 MB.</p>
                @error('file')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror

                {{-- Catatan OCR --}}
                <div class="flex gap-3 bg-paper border-l-4 border-ink px-4 py-4 mt-4">
                    <i class="fa-solid fa-circle-info text-ink mt-0.5 text-sm"></i>
                    <div class="text-xs text-ink leading-relaxed">
                        <p class="font-bold text-sm">Isi dokumen dibaca otomatis (OCR)</p>
                        <ul class="list-disc pl-4 mt-2 space-y-1 text-steel">
                            <li>Berlaku untuk PDF dan gambar (PNG, JPG, BMP, TIFF). Format lain tetap tersimpan, tetapi isinya tidak dibaca.</li>
                            <li>Hasil terbaik didapat dari PDF hasil scan, atau foto yang tegak, terang, dan tidak buram.</li>
                            <li>Akurasi tidak 100%. Kualitas berkas sangat memengaruhi hasil, jadi ada kemungkinan huruf salah terbaca.</li>
                            <li>Prosesnya berjalan di latar belakang. Statusnya bisa dilihat di daftar arsip.</li>
                        </ul>
                    </div>
                </div>
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('archives.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    Unggah arsip
                </button>
            </div>
        </form>
    </div>
@endsection