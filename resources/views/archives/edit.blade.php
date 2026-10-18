@extends('layouts.app')

@section('pageTitle', 'Edit Arsip')

@section('content')
    <div class="max-w-2xl mx-auto flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Edit Arsip</h1>
            <p class="text-sm text-slate-500 mt-1">Perbarui detail arsip "{{ $archive->title }}".</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('archives.update', $archive) }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Judul Arsip</label>
                    <input type="text" name="title" id="title" value="{{ old('title', $archive->title) }}"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                    <input type="text" name="category" id="category" value="{{ old('category', $archive->category) }}"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('category') border-red-400 @enderror">
                    @error('category')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('description') border-red-400 @enderror">{{ old('description', $archive->description) }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-slate-700 mb-1.5">Status</label>
                    <select name="status" id="status"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('status') border-red-400 @enderror">
                        @foreach (['active' => 'Aktif', 'archived' => 'Diarsipkan', 'deleted' => 'Dihapus'] as $value => $label)
                            <option value="{{ $value }}" @selected(old('status', $archive->status) === $value)>
                                {{ $label }}
                            </option>
                        @endforeach
                    </select>
                    @error('status')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-slate-700 mb-1.5">
                        Ganti Berkas <span class="text-slate-400 font-normal">(opsional)</span>
                    </label>
                    <input type="file" name="file" id="file"
                        class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-aris-50 file:text-aris-700 file:text-sm file:font-medium hover:file:bg-aris-100 border border-slate-200 rounded-lg @error('file') border-red-400 @enderror">
                    <p class="text-xs text-slate-400 mt-1.5">Berkas saat ini: {{ $archive->original_filename ?? basename($archive->file_path) }}</p>
                    <p class="text-xs text-amber-600 mt-1">
    <i class="fa-solid fa-circle-info"></i>
    Jika berkas diganti, teks OCR lama dihapus dan OCR dijalankan ulang untuk berkas baru.
</p>
                    @error('file')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('archives.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection