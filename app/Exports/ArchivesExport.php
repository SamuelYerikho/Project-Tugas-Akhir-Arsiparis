<?php

namespace App\Exports;

use App\Models\Archive;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class ArchivesExport implements FromView
{
    public function __construct(
        protected ?string $periodStart = null,
        protected ?string $periodEnd = null,
    ) {}

    public function view(): View
    {
        $archives = Archive::query()
            ->when($this->periodStart, fn ($q) => $q->whereDate('created_at', '>=', $this->periodStart))
            ->when($this->periodEnd, fn ($q) => $q->whereDate('created_at', '<=', $this->periodEnd))
            ->with('user')
            ->get();

        return view('reports.exports.archives-excel', compact('archives'));
    }
}