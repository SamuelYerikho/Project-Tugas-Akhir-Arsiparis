@extends('layouts.app')

@section('pageTitle', 'Ajukan Izin Akses')

@section('content')
    <div class="max-w-2xl mx-auto flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Ajukan Izin Akses Arsip</h1>
            <p class="text-sm text-slate-500 mt-1">Pilih arsip yang ingin kamu akses dan berikan alasan pengajuan.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('access-requests.store') }}" method="POST" class="flex flex-col gap-4">
                @csrf

                <div>
                    <label for="archive_id" class="block text-sm font-medium text-slate-700 mb-1.5">Arsip</label>
                    <select name="archive_id" id="archive_id"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3.5 py-2 focus:outline-none focus:ring-2 focus:ring-aris-500 @error('archive_id') border-red-400 @enderror">
                        <option value="">-- Pilih Arsip --</option>
                        @foreach ($archives as $archive)
                            <option value="{{ $archive->id }}" {{ old('archive_id') == $archive->id ? 'selected' : '' }}>
                                {{ $archive->title }}
                            </option>
                        @endforeach
                    </select>
                    @error('archive_id')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="reason" class="block text-sm font-medium text-slate-700 mb-1.5">Alasan (opsional)</label>
                    <textarea name="reason" id="reason" rows="4"
                        class="w-full text-sm border border-slate-200 rounded-lg px-3.5 py-2 focus:outline-none focus:ring-2 focus:ring-aris-500 @error('reason') border-red-400 @enderror"
                        placeholder="Jelaskan alasan kamu membutuhkan akses arsip ini...">{{ old('reason') }}</textarea>
                    @error('reason')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-2 pt-2">
                    <a href="{{ route('access-requests.index') }}"
                        class="text-sm font-medium px-4 py-2 rounded-lg text-slate-600 hover:bg-slate-50 border border-slate-200 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="text-sm font-medium px-4 py-2 rounded-lg bg-aris-600 hover:bg-aris-700 text-white transition">
                        Ajukan Permintaan
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection