@extends('layouts.app')

@section('pageTitle', 'Buat Laporan')

@section('content')
    <div class="max-w-2xl mx-auto flex flex-col gap-6">

        <div>
            <h1 class="text-2xl font-bold text-slate-800">Buat Laporan Baru</h1>
            <p class="text-sm text-slate-500 mt-1">Tentukan jenis, periode, dan format laporan arsip yang ingin dibuat.</p>
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm p-6">
            <form action="{{ route('reports.store') }}" method="POST" class="flex flex-col gap-5">
                @csrf

                <div>
                    <label for="report_type" class="block text-sm font-medium text-slate-700 mb-1.5">Jenis Laporan</label>
                    <input type="text" name="report_type" id="report_type" value="{{ old('report_type') }}"
                        placeholder="Contoh: Laporan Arsip Bulanan"
                        class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('report_type') border-red-400 @enderror">
                    @error('report_type')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div>
                        <label for="period_start" class="block text-sm font-medium text-slate-700 mb-1.5">Periode Mulai</label>
                        <input type="date" name="period_start" id="period_start" value="{{ old('period_start') }}"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('period_start') border-red-400 @enderror">
                        @error('period_start')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="period_end" class="block text-sm font-medium text-slate-700 mb-1.5">Periode Akhir</label>
                        <input type="date" name="period_end" id="period_end" value="{{ old('period_end') }}"
                            class="w-full px-3.5 py-2.5 text-sm border border-slate-200 rounded-lg focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-aris-500 @error('period_end') border-red-400 @enderror">
                        @error('period_end')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
                <p class="text-xs text-slate-400 -mt-3">Kosongkan periode untuk mencakup seluruh arsip.</p>

                <div>
                    <label class="block text-sm font-medium text-slate-700 mb-2">Format Laporan</label>
                    <div class="grid grid-cols-2 gap-3">
                        <label
                            class="flex items-center gap-3 border border-slate-200 rounded-lg px-4 py-3 cursor-pointer transition has-[:checked]:border-aris-500 has-[:checked]:bg-aris-50">
                            <input type="radio" name="format" value="excel"
                                class="text-aris-600 focus:ring-aris-500"
                                {{ old('format', 'excel') === 'excel' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-excel text-emerald-600"></i>
                                <span class="text-sm font-medium text-slate-700">Excel</span>
                            </div>
                        </label>

                        <label
                            class="flex items-center gap-3 border border-slate-200 rounded-lg px-4 py-3 cursor-pointer transition has-[:checked]:border-aris-500 has-[:checked]:bg-aris-50">
                            <input type="radio" name="format" value="pdf"
                                class="text-aris-600 focus:ring-aris-500"
                                {{ old('format') === 'pdf' ? 'checked' : '' }}>
                            <div class="flex items-center gap-2">
                                <i class="fa-solid fa-file-pdf text-red-600"></i>
                                <span class="text-sm font-medium text-slate-700">PDF</span>
                            </div>
                        </label>
                    </div>
                    @error('format')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div class="flex items-center justify-end gap-3 pt-2">
                    <a href="{{ route('reports.index') }}"
                        class="px-4 py-2.5 text-sm font-medium text-slate-600 hover:text-slate-800 transition">
                        Batal
                    </a>
                    <button type="submit"
                        class="bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-5 py-2.5 rounded-lg transition">
                        Buat Laporan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection