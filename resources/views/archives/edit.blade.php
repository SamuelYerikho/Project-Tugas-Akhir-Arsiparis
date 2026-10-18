@extends('layouts.app')

@section('pageTitle', 'Ubah Arsip')

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
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Ubah arsip</h2>
            <p class="text-sm text-steel mt-2 max-w-lg">Perbarui detail arsip "{{ $archive->title }}".</p>
        </div>

        <form action="{{ route('archives.update', $archive) }}" method="POST" enctype="multipart/form-data"
            class="bg-white border border-line border-t-4 border-t-manila rounded p-6 sm:p-8 space-y-6">
            @csrf
            @method('PUT')

            <div>
                <label for="title" class="{{ $label }}">Judul arsip</label>
                <input type="text" name="title" id="title" value="{{ old('title', $archive->title) }}"
                    class="{{ $input }} @error('title') border-red-600 @else border-line @enderror">
                @error('title')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="category" class="{{ $label }}">Kategori</label>
                <input type="text" name="category" id="category" value="{{ old('category', $archive->category) }}"
                    class="{{ $input }} @error('category') border-red-600 @else border-line @enderror">
                @error('category')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="description" class="{{ $label }}">Deskripsi</label>
                <textarea name="description" id="description" rows="3"
                    class="{{ $input }} leading-relaxed @error('description') border-red-600 @else border-line @enderror">{{ old('description', $archive->description) }}</textarea>
                @error('description')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="status" class="{{ $label }}">Status</label>
                <select name="status" id="status"
                    class="{{ $input }} @error('status') border-red-600 @else border-line @enderror">
                    @foreach (['active' => 'Aktif', 'archived' => 'Diarsipkan', 'deleted' => 'Dihapus'] as $value => $statusText)
                        <option value="{{ $value }}" @selected(old('status', $archive->status) === $value)>
                            {{ $statusText }}
                        </option>
                    @endforeach
                </select>
                @error('status')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="file" class="block text-sm font-semibold mb-1.5">
                    Ganti berkas <span class="font-normal text-steel">(boleh dikosongkan)</span>
                </label>
                <input type="file" name="file" id="file"
                    class="w-full text-sm text-steel bg-white border rounded cursor-pointer file:mr-4 file:py-3 file:px-4 file:border-0 file:bg-ink file:text-white file:text-sm file:font-semibold hover:file:bg-ink/90 @error('file') border-red-600 @else border-line @enderror">
                <p class="text-xs text-steel mt-2">Berkas saat ini: <span class="font-semibold text-ink">{{ $archive->original_filename ?? basename($archive->file_path) }}</span></p>
                @error('file')
                    <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                @enderror

                <div class="flex gap-3 bg-manila/20 border-l-4 border-manila px-4 py-3 mt-4 text-xs text-ink leading-relaxed">
                    <i class="fa-solid fa-circle-info mt-0.5 text-sm"></i>
                    <p>Jika berkas diganti, teks OCR lama dihapus dan OCR dijalankan ulang untuk berkas yang baru.</p>
                </div>
            </div>

            <div class="flex items-center justify-end gap-5 pt-4 border-t border-line">
                <a href="{{ route('archives.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                    Batal
                </a>
                <button type="submit"
                    class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
@endsection