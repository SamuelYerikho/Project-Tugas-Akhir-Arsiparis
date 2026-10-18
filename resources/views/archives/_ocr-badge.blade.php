@php
    [$label, $classes, $icon] = match ($archive->ocr_status) {
        'pending'    => ['Menunggu', 'bg-slate-100 text-slate-600', 'fa-clock'],
        'processing' => ['Diproses', 'bg-sky-50 text-sky-600', 'fa-spinner fa-spin'],
        'done'       => ['Selesai', 'bg-emerald-50 text-emerald-600', 'fa-circle-check'],
        'failed'     => ['Gagal', 'bg-red-50 text-red-600', 'fa-circle-exclamation'],
        default      => ['Tidak diproses', 'bg-slate-50 text-slate-400', 'fa-minus'],
    };
@endphp
<span class="inline-flex items-center gap-1.5 text-xs font-medium px-2.5 py-1 rounded-full {{ $classes }}">
    <i class="fa-solid {{ $icon }} text-[10px]"></i>
    {{ $label }}
</span>