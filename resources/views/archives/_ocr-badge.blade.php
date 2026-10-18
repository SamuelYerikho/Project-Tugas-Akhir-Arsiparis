@php
    // Nama variabel diawali "ocr" agar tidak menimpa variabel milik halaman yang meng-include partial ini
    [$ocrLabel, $ocrClasses, $ocrIcon] = match ($archive->ocr_status) {
        'pending'    => ['Menunggu', 'bg-line/50 text-steel', 'fa-clock'],
        'processing' => ['Diproses', 'bg-manila/40 text-ink', 'fa-spinner fa-spin'],
        'done'       => ['Selesai', 'bg-emerald-100 text-emerald-800', 'fa-circle-check'],
        'failed'     => ['Gagal', 'bg-red-100 text-red-800', 'fa-circle-exclamation'],
        default      => ['Tidak diproses', 'bg-paper text-steel', 'fa-minus'],
    };
@endphp
<span class="inline-flex items-center gap-1.5 text-xs font-semibold px-2.5 py-1 rounded-sm whitespace-nowrap {{ $ocrClasses }}">
    <i class="fa-solid {{ $ocrIcon }} text-[10px]"></i>
    {{ $ocrLabel }}
</span>