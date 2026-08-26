@extends('layouts.app')

@section('pageTitle', 'Laporan')

@section('content')
    <div class="flex flex-col gap-6">

        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-slate-800">Laporan</h1>
                <p class="text-sm text-slate-500 mt-1">Riwayat laporan arsip yang telah dibuat.</p>
            </div>

            @can('generate reports')
                <a href="{{ route('reports.create') }}"
                    class="inline-flex items-center gap-2 bg-aris-600 hover:bg-aris-700 text-white text-sm font-medium px-4 py-2.5 rounded-lg transition">
                    <i class="fa-solid fa-plus"></i>
                    Buat Laporan
                </a>
            @endcan
        </div>

        <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left">
                    <thead class="bg-slate-50 text-slate-600 uppercase text-xs">
                        <tr>
                            <th class="px-5 py-3">Jenis Laporan</th>
                            <th class="px-5 py-3">Periode</th>
                            <th class="px-5 py-3">Format</th>
                            <th class="px-5 py-3">Dibuat Oleh</th>
                            <th class="px-5 py-3">Tanggal</th>
                            <th class="px-5 py-3 text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-100">
                        @forelse ($reports as $report)
                            @php
                                $extension = $report->file_path ? pathinfo($report->file_path, PATHINFO_EXTENSION) : null;
                            @endphp
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-5 py-3.5 font-medium text-slate-800">{{ $report->report_type }}</td>
                                <td class="px-5 py-3.5 text-slate-600">
                                    @if ($report->period_start || $report->period_end)
                                        {{ optional($report->period_start)->format('d M Y') ?? '-' }}
                                        &mdash;
                                        {{ optional($report->period_end)->format('d M Y') ?? '-' }}
                                    @else
                                        <span class="text-slate-400">Semua periode</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5">
                                    @if ($extension === 'xlsx')
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-emerald-50 text-emerald-600">
                                            Excel
                                        </span>
                                    @elseif ($extension === 'pdf')
                                        <span class="text-xs font-medium px-2.5 py-1 rounded-full bg-red-50 text-red-600">
                                            PDF
                                        </span>
                                    @else
                                        <span class="text-xs text-slate-400">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-3.5 text-slate-600">{{ $report->user->name ?? '-' }}</td>
                                <td class="px-5 py-3.5 text-slate-500">{{ $report->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-3.5">
                                    <div class="flex items-center justify-end gap-3 text-slate-400">
                                        @can('download reports')
                                            @if ($report->file_path)
                                                <a href="{{ route('reports.download', $report) }}"
                                                    class="hover:text-aris-600 transition" title="Unduh">
                                                    <i class="fa-solid fa-download"></i>
                                                </a>
                                            @endif
                                        @endcan
                                        @can('generate reports')
                                            <form action="{{ route('reports.destroy', $report) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus laporan ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="hover:text-red-600 transition" title="Hapus">
                                                    <i class="fa-solid fa-trash"></i>
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-10 text-center text-slate-400">
                                    Belum ada laporan yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="px-5 py-4 border-t border-slate-100">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection