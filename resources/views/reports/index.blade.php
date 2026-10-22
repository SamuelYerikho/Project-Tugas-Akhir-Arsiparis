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
                    class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                    </svg>
                    Buat laporan
                </a>
            @endcan
        </div>

        <div class="bg-surface border border-line rounded border-t-2 border-t-ink overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[56rem]">
                    <thead class="bg-paper/60 text-steel text-xs uppercase tracking-wider">
                        <tr class="border-b border-line">
                            <th class="px-5 py-3.5 font-semibold">Jenis laporan</th>
                            <th class="px-5 py-3.5 font-semibold">Periode</th>
                            <th class="px-5 py-3.5 font-semibold">Format</th>
                            <th class="px-5 py-3.5 font-semibold">Dibuat oleh</th>
                            <th class="px-5 py-3.5 font-semibold">Tanggal</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($reports as $report)
                            @php
                                $extension = $report->file_path ? pathinfo($report->file_path, PATHINFO_EXTENSION) : null;
                            @endphp
                            <tr class="hover:bg-paper align-top transition-colors">
                                <td class="px-5 py-4 font-semibold">{{ $report->report_type }}</td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">
                                    @if ($report->period_start || $report->period_end)
                                        {{ optional($report->period_start)->format('d M Y') ?? '—' }}
                                        <span class="text-steel/60">&mdash;</span>
                                        {{ optional($report->period_end)->format('d M Y') ?? '—' }}
                                    @else
                                        Semua periode
                                    @endif
                                </td>
                                <td class="px-5 py-4">
                                    @if ($extension === 'xlsx')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300">
                                            <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M3.375 19.5h17.25m-17.25 0a1.125 1.125 0 0 1-1.125-1.125M3.375 19.5h1.5C5.496 19.5 6 18.996 6 18.375m-3.75 0V5.625m0 12.75v-1.5c0-.621.504-1.125 1.125-1.125m18.375 2.625V5.625m0 12.75c0 .621-.504 1.125-1.125 1.125m1.125-1.125v-1.5c0-.621-.504-1.125-1.125-1.125m0 3.75h-1.5A1.125 1.125 0 0 1 18 18.375M20.625 4.5H3.375m17.25 0c.621 0 1.125.504 1.125 1.125M20.625 4.5h-1.5C18.504 4.5 18 5.004 18 5.625m3.75 0v1.5c0 .621-.504 1.125-1.125 1.125M3.375 4.5c-.621 0-1.125.504-1.125 1.125M3.375 4.5h1.5C5.496 4.5 6 5.004 6 5.625m-3.75 0v1.5c0 .621.504 1.125 1.125 1.125m0 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125m1.5-3.75C5.496 8.25 6 7.746 6 7.125v-1.5M4.875 8.25C5.496 8.25 6 8.754 6 9.375v1.5m0-5.25v5.25m0-5.25C6 5.004 6.504 4.5 7.125 4.5h9.75c.621 0 1.125.504 1.125 1.125m1.125 2.625h1.5m-1.5 0A1.125 1.125 0 0 1 18 7.125v-1.5m1.125 2.625c-.621 0-1.125.504-1.125 1.125v1.5m2.625-2.625c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125M18 5.625v5.25M7.125 12h9.75m-9.75 0A1.125 1.125 0 0 1 6 10.875M7.125 12C6.504 12 6 12.504 6 13.125m0-2.25C6 11.496 5.496 12 4.875 12M18 10.875c0 .621-.504 1.125-1.125 1.125M18 10.875c0 .621.504 1.125 1.125 1.125m-2.25 0c.621 0 1.125.504 1.125 1.125m-12 5.25v-5.25m0 5.25c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125m-12 0v-1.5c0-.621-.504-1.125-1.125-1.125M18 18.375v-5.25m0 5.25v-1.5c0-.621.504-1.125 1.125-1.125M18 13.125v1.5c0 .621.504 1.125 1.125 1.125M18 13.125c0-.621.504-1.125 1.125-1.125M6 13.125v1.5c0 .621-.504 1.125-1.125 1.125M6 13.125C6 12.504 5.496 12 4.875 12m-1.5 0h1.5m-1.5 0c-.621 0-1.125.504-1.125 1.125v1.5c0 .621.504 1.125 1.125 1.125M19.125 12h1.5m0 0c.621 0 1.125.504 1.125 1.125v1.5c0 .621-.504 1.125-1.125 1.125m-17.25 0h1.5m14.25 0h1.5"/>
                                            </svg>
                                            Excel
                                        </span>
                                    @elseif ($extension === 'pdf')
                                        <span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300">
                                            <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H8.25m0 12.75h7.5m-7.5 3H12M10.5 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z"/>
                                            </svg>
                                            PDF
                                        </span>
                                    @else
                                        <span class="text-xs text-steel">—</span>
                                    @endif
                                </td>
                                <td class="px-5 py-4 text-steel">{{ $report->user->name ?? '—' }}</td>
                                <td class="px-5 py-4 text-steel whitespace-nowrap">{{ $report->created_at->format('d M Y H:i') }}</td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        @can('download reports')
                                            @if ($report->file_path)
                                                <a href="{{ route('reports.download', $report) }}"
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-ink hover:border-ink transition-colors">
                                                    <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                                                    </svg>
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
                                                    class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-red-700 dark:text-red-400 hover:border-red-600 dark:hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                    <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                    </svg>
                                                    Hapus
                                                </button>
                                            </form>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-steel/70 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-steel">Belum ada laporan yang dibuat.</p>
                                    @can('generate reports')
                                        <a href="{{ route('reports.create') }}"
                                            class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-ink underline underline-offset-4 decoration-line hover:decoration-ink transition-colors">
                                            Buat laporan pertama
                                        </a>
                                    @endcan
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