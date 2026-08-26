<?php

namespace App\Http\Controllers;

use App\Exports\ArchivesExport;
use App\Models\Archive;
use App\Models\Report;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Display a listing of the reports.
     */
    public function index(): View
    {
        $reports = Report::query()->with('user')->latest()->paginate(10);

        return view('reports.index', compact('reports'));
    }

    /**
     * Show the form for generating a new report.
     */
    public function create(): View
    {
        return view('reports.create');
    }

    /**
     * Generate and store a new report as Excel or PDF.
     */
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'report_type'  => ['required', 'string', 'max:255'],
            'period_start' => ['nullable', 'date'],
            'period_end'   => ['nullable', 'date', 'after_or_equal:period_start'],
            'format'       => ['required', 'in:excel,pdf'],
        ]);

        $periodStart = $validated['period_start'] ?? null;
        $periodEnd = $validated['period_end'] ?? null;
        $fileName = 'laporan-arsip-' . now()->format('Ymd-His') . '-' . Str::random(6);

        $filePath = $validated['format'] === 'excel'
            ? $this->generateExcel($fileName, $periodStart, $periodEnd)
            : $this->generatePdf($fileName, $periodStart, $periodEnd);

        Report::create([
            'user_id'      => $request->user()->id,
            'report_type'  => $validated['report_type'],
            'period_start' => $periodStart,
            'period_end'   => $periodEnd,
            'file_path'    => $filePath,
        ]);

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dibuat.');
    }

    /**
     * Remove the specified report.
     */
    public function destroy(Report $report): RedirectResponse
    {
        if ($report->file_path) {
            Storage::disk('public')->delete($report->file_path);
        }

        $report->delete();

        return redirect()->route('reports.index')
            ->with('success', 'Laporan berhasil dihapus.');
    }

    /**
     * Download the specified report file.
     */
    public function download(Report $report)
    {
        return Storage::disk('public')->download($report->file_path);
    }

    /**
     * Generate an Excel report and return its stored file path.
     */
    protected function generateExcel(string $fileName, ?string $periodStart, ?string $periodEnd): string
    {
        $filePath = 'reports/' . $fileName . '.xlsx';

        Excel::store(new ArchivesExport($periodStart, $periodEnd), $filePath, 'public');

        return $filePath;
    }

    /**
     * Generate a PDF report and return its stored file path.
     */
    protected function generatePdf(string $fileName, ?string $periodStart, ?string $periodEnd): string
    {
        $archives = Archive::query()
            ->when($periodStart, fn ($q) => $q->whereDate('created_at', '>=', $periodStart))
            ->when($periodEnd, fn ($q) => $q->whereDate('created_at', '<=', $periodEnd))
            ->with('user')
            ->get();

        $pdf = Pdf::loadView('reports.exports.archives-pdf', [
            'archives'    => $archives,
            'periodStart' => $periodStart,
            'periodEnd'   => $periodEnd,
        ]);

        $filePath = 'reports/' . $fileName . '.pdf';

        Storage::disk('public')->put($filePath, $pdf->output());

        return $filePath;
    }
}