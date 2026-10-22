@extends('layouts.app')

@section('pageTitle', 'Ubah Arsip')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';
@endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-10">
            <a href="{{ route('archives.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar arsip
            </a>
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ubah arsip</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Perbarui detail arsip "{{ $archive->title }}".</p>
        </div>

        <form action="{{ route('archives.update', $archive) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- ===== Bagian: Detail arsip ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8 pb-12">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                        </svg>
                        Detail arsip
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Judul, kategori, deskripsi, dan status menentukan bagaimana arsip ini tampil di daftar.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="title" class="{{ $label }}">Judul arsip</label>
                        <input type="text" name="title" id="title" value="{{ old('title', $archive->title) }}"
                            placeholder="Contoh: Undangan Rapat Koordinasi Triwulan IV 2024"
                            class="{{ $input }} @error('title') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        @error('title')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="category" class="{{ $label }}">Kategori</label>
                        <input type="text" name="category" id="category" value="{{ old('category', $archive->category) }}"
                            placeholder="Contoh: Surat Masuk, SK, Dokumentasi"
                            class="{{ $input }} @error('category') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        @error('category')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="description" class="{{ $label }}">Deskripsi</label>
                        <textarea name="description" id="description" rows="3"
                            placeholder="Ringkasan isi atau keterangan tambahan tentang arsip ini (opsional)."
                            class="{{ $input }} leading-relaxed @error('description') !border-red-600 dark:!border-red-500 @else border-line @enderror">{{ old('description', $archive->description) }}</textarea>
                        @error('description')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="status" class="{{ $label }}">Status</label>
                        <select name="status" id="status"
                            class="{{ $input }} @error('status') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            @foreach (['active' => 'Aktif', 'archived' => 'Diarsipkan', 'deleted' => 'Dihapus'] as $value => $statusText)
                                <option value="{{ $value }}" @selected(old('status', $archive->status) === $value)>
                                    {{ $statusText }}
                                </option>
                            @endforeach
                        </select>
                        @error('status')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </section>

            {{-- ===== Bagian: Berkas ===== --}}
            <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
                <div>
                    <h3 class="font-display font-bold text-xl flex items-center gap-2">
                        <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m18.375 12.739-7.693 7.693a4.5 4.5 0 0 1-6.364-6.364l10.94-10.94A3 3 0 1 1 19.5 7.372L8.552 18.32m.009-.01-.01.01m5.699-9.941-7.81 7.81a1.5 1.5 0 0 0 2.112 2.13"/>
                        </svg>
                        Berkas
                    </h3>
                    <p class="text-sm text-steel mt-2 leading-relaxed">
                        Ganti berkas arsip bila perlu. Kosongkan jika tidak ingin mengubahnya.
                    </p>
                </div>

                <div class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                    <div>
                        <label for="file" class="{{ $label }}">
                            Ganti berkas <span class="font-normal text-steel">(boleh dikosongkan)</span>
                        </label>
                        <input type="file" name="file" id="file"
                            accept=".pdf,.png,.jpg,.jpeg,.bmp,.tif,.tiff"
                            class="w-full text-sm text-steel bg-surface border rounded cursor-pointer file:mr-4 file:py-3 file:px-4 file:border-0 file:bg-invert file:text-on-invert file:text-sm file:font-semibold hover:file:bg-invert/90 @error('file') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        <p class="text-xs text-steel mt-2">
                            Berkas saat ini:
                            <span class="font-semibold text-ink">{{ $archive->original_filename ?? basename($archive->file_path) }}</span>
                        </p>
                        @error('file')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Catatan OCR --}}
                    <div class="flex gap-3 bg-manila/20 border-l-4 border-manila px-4 py-3 text-xs text-ink leading-relaxed rounded-r">
                        <svg class="w-4 h-4 mt-0.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m11.25 11.25.041-.02a.75.75 0 0 1 1.063.852l-.708 2.836a.75.75 0 0 0 1.063.853l.041-.021M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9-3.75h.008v.008H12V8.25Z"/>
                        </svg>
                        <p>Jika berkas diganti, teks OCR lama dihapus dan OCR dijalankan ulang untuk berkas yang baru.</p>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="pt-4 border-t border-line flex items-center justify-end gap-5">
                        <a href="{{ route('archives.index') }}"
                            class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                            Batal
                        </a>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                            </svg>
                            Simpan perubahan
                        </button>
                    </div>
                </div>
            </section>

        </form>
    </div>
@endsection