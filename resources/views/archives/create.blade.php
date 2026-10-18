@extends('layouts.app')

@section('pageTitle', 'Unggah Arsip')

@section('content')
    <div class="max-w-2xl mx-auto flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Unggah Arsip Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Lengkapi detail arsip dan pilih berkas yang ingin diunggah.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('archives.store') }}" method="POST" enctype="multipart/form-data" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="title" class="block text-sm font-medium text-slate-700 mb-1.5">Judul Arsip</label>
                    <input type="text" name="title" id="title" value="{{ old('title') }}"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('title') border-red-400 @enderror">
                    @error('title')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="category" class="block text-sm font-medium text-slate-700 mb-1.5">Kategori</label>
                    <input type="text" name="category" id="category" value="{{ old('category') }}"
                        placeholder="Contoh: Surat Masuk, SK, Dokumentasi"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('category') border-red-400 @enderror">
                    @error('category')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="description" class="block text-sm font-medium text-slate-700 mb-1.5">Deskripsi</label>
                    <textarea name="description" id="description" rows="3"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('description') border-red-400 @enderror">{{ old('description') }}</textarea>
                    @error('description')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="file" class="block text-sm font-medium text-slate-700 mb-1.5">Berkas Arsip</label>
                    <input type="file" name="file" id="file"
                        accept=".pdf,.png,.jpg,.jpeg,.bmp,.tif,.tiff"
                        class="w-full text-sm text-slate-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-aris-50 file:text-aris-700 file:text-sm file:font-medium hover:file:bg-aris-100 border border-slate-200 rounded-lg @error('file') border-red-400 @enderror">
                    <p class="text-xs text-slate-400 mt-1.5">Ukuran maksimal 10MB.</p>
                    @error('file')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror

                    {{-- Catatan OCR --}}
                    <div class="flex gap-2.5 bg-sky-50 border border-sky-100 rounded-lg px-4 py-3 mt-3">
                        <i class="fa-solid fa-circle-info text-sky-500 mt-0.5 text-sm"></i>
                        <div class="text-xs text-sky-800 leading-relaxed">
                            <p class="font-semibold">Isi dokumen dibaca otomatis (OCR).</p>
                            <ul class="list-disc pl-4 mt-1 space-y-0.5">
                                <li>Berlaku untuk PDF dan gambar (PNG, JPG, BMP, TIFF). Format lain tetap tersimpan, tapi isinya tidak dibaca.</li>
                                <li>Hasil terbaik dari PDF hasil scan, atau foto yang tegak, terang, dan tidak buram.</li>
                                <li>Akurasi tidak 100%. Kualitas berkas sangat memengaruhi hasil, jadi mungkin ada huruf yang salah baca.</li>
                                <li>Proses berjalan di latar belakang, status bisa dilihat di daftar arsip.</li>
                            </ul>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('archives.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        Unggah Arsip
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection