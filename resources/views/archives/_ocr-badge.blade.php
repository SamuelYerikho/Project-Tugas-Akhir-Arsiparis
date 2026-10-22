@php
    // Nama variabel diawali "ocr" agar tidak menimpa variabel milik halaman yang meng-include partial ini
    [$ocrLabel, $ocrClasses, $ocrIcon] = match ($archive->ocr_status) {
        'pending'    => [
            'Menunggu',
            'bg-line/50 text-steel',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        ],
        'processing' => [
            'Diproses',
            'bg-manila/40 text-on-manila dark:bg-manila/25 dark:text-manila',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M16.023 9.348h4.992v-.001M2.985 19.644v-4.992m0 0h4.992m-4.993 0 3.181 3.183a8.25 8.25 0 0 0 13.803-3.7M4.031 9.865a8.25 8.25 0 0 1 13.803-3.7l3.181 3.182m0-4.991v4.99"/>',
        ],
        'done'       => [
            'Selesai',
            'bg-emerald-100 text-emerald-800 dark:bg-emerald-900/30 dark:text-emerald-300',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>',
        ],
        'failed'     => [
            'Gagal',
            'bg-red-100 text-red-800 dark:bg-red-900/30 dark:text-red-300',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m9-.75a9 9 0 1 1-18 0 9 9 0 0 1 18 0Zm-9 3.75h.008v.008H12v-.008Z"/>',
        ],
        default      => [
            'Tidak diproses',
            'bg-paper text-steel',
            '<path stroke-linecap="round" stroke-linejoin="round" d="M5 12h14"/>',
        ],
    };

    // Ikon yang berputar (spinner) hanya untuk status "processing"
    $ocrSpin = $archive->ocr_status === 'processing' ? 'animate-spin' : '';
@endphp

<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm whitespace-nowrap {{ $ocrClasses }}">
    <svg class="w-3 h-3 shrink-0 {{ $ocrSpin }}" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
        {!! $ocrIcon !!}
    </svg>
    {{ $ocrLabel }}
</span>