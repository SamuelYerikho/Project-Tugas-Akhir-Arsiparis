@extends('layouts.app')

@section('pageTitle', 'Laporan')

@section('content')
    <div class="max-w-6xl">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display font-extrabold text-3xl tracking-tight">Laporan</h2>
                <p class="text-sm text-steel mt-2 max-w-md">Riwayat laporan arsip yang sudah dibuat.</p>
            </div>

            @can('generate reports')
                <a href="{{ route('reports.create') }}"
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                    <i class="fa-solid fa-plus text-xs"></i>
                    Buat laporan
                </a>
            @endcan
        </div>

        <div class="bg-white border border-line rounded border-t-2 border-t-ink">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[52rem]">
                    <thead class="text-steel">
                        <tr class="border-b border-line">
                            <th class="px-5 py-4 font-semibold">Jenis laporan</th>
                            <th class="px-5 py-4 font-semibold">Periode</th>
                            <th class="px-5 py-4 font-semibold">Format</th>
                            <th class="px-5 py-4 font-semibold">Dibuat oleh</th>
                            <th class="px-5 py-4 font-semibold">Tanggal</th>
                            <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($reports as $report)
                            @php
                                $extension = $report->file_path ? pathinfo($report->file_path, PATHINFO_EXTENSION) : null;
                            @endphp
                            <tr class="hover:bg-paper/70">
                                <td class="px-5 py-4 font-semibold">{{ $report->report_type }}</td>
                                <td class="px-5 py-4 text-steel">
                                    @if ($report->period_start || $report->period_end)
                                        {{ optional($report->period_start)->format('d M Y') ?? '-' }}
                                        &mdash;
                                        {{ optional($report->period_end)->format('d M Y') ?? '-' }}
                                    @else
                                        Semua periode
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($extension === 'xlsx')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm bg-emerald-100 text-emerald-800">
                                            <i class="fa-solid fa-file-excel"></i> Excel
                                        </span>
                                    @elseif ($extension === 'pdf')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm bg-red-100 text-red-800">
                                            <i class="fa-solid fa-file-pdf"></i> PDF
                                        </span>
                                    @else
                                        <span class="text-xs text-steel">-</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-steel">{{ $report->user->name ?? '-' }}</td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">{{ $report->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-4 whitespace-nowrap">
                                        @can('download reports')
                                            @if ($report->file_path)
                                                <a href="{{ route('reports.download', $report) }}"
                                                    class="text-ink font-semibold underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">
                                                    Unduh
                                                </a>
                                            @endif
                                        @endcan
                                        @can('generate reports')
                                            <form action="{{ route('reports.destroy', $report) }}" method="POST"
                                                onsubmit="return confirm('Hapus laporan ini? Tindakan ini tidak bisa dibatalkan.')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-red-700 font-semibold underline underline-offset-4 decoration-red-200 hover:decoration-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-12 text-center text-steel">
                                    Belum ada laporan yang dibuat.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if ($reports->hasPages())
                <div class="px-5 py-4 border-t border-line">
                    {{ $reports->links() }}
                </div>
            @endif
        </div>
    </div>
@endsection