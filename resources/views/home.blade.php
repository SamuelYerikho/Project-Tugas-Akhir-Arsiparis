<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rancang Bangun Website Arsip Aris di RRI Batam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Bricolage Grotesque"', 'sans-serif'],
                        sans: ['"Public Sans"', 'sans-serif'],
                        type: ['"Courier Prime"', 'monospace']
                    },
                    colors: {
                        ink: '#10283A',
                        paper: '#F1F5F6',
                        manila: '#E2B84B',
                        steel: '#5B7083',
                        line: '#C9D4D9'
                    }
                }
            }
        }
    </script>

    <style>
        /* Satu-satunya gerakan otomatis: garis pindai di atas surat */
        @@keyframes scan { 0% { top: 6%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 94%; opacity: 0; } }
        .scan-line { position: absolute; left: 0; right: 0; height: 2px; background: #E2B84B; box-shadow: 0 0 14px 3px rgba(226,184,75,.55); animation: scan 4.5s ease-in-out infinite; }
        @@media (prefers-reduced-motion: reduce) { .scan-line { animation: none; top: 50%; opacity: 1; } html { scroll-behavior: auto; } }
        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        a:focus-visible { outline: 3px solid #E2B84B; outline-offset: 3px; }
    </style>
</head>
<body class="font-sans antialiased bg-paper text-ink min-h-screen flex flex-col">

    {{-- ================= NAVBAR ================= --}}
    <header class="border-b border-line">
        <nav class="max-w-6xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">

            <a href="/" class="flex items-end gap-3">
                <span class="folder-tab bg-manila text-ink font-display font-extrabold text-sm pl-3 pr-6 py-1.5">RRI</span>
                <span class="font-display font-extrabold text-xl tracking-tight leading-none">Arsip Aris</span>
            </a>

            <div class="hidden md:flex items-center gap-9 text-sm font-medium text-steel">
                <a href="#tentang" class="hover:text-ink">Tentang</a>
                <a href="#fitur" class="hover:text-ink">Fitur</a>
                <a href="#alur" class="hover:text-ink">Alur kerja</a>
            </div>

            @if (Route::has('login'))
                @auth
                    <a href="{{ route('dashboard.index') }}" class="text-sm font-semibold bg-ink text-white px-5 py-2.5 rounded hover:bg-ink/90">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-semibold bg-ink text-white px-5 py-2.5 rounded hover:bg-ink/90">Masuk sistem</a>
                @endauth
            @endif
        </nav>
    </header>

    {{-- ================= HERO ================= --}}
    <main class="flex-grow">
        <section class="max-w-6xl mx-auto px-6 lg:px-8 pt-14 pb-20 lg:pt-20 lg:pb-28">
            <div class="grid lg:grid-cols-12 gap-12 lg:gap-8 items-center">

                <div class="lg:col-span-7">
                    <p class="text-sm font-semibold text-steel mb-5 border-l-4 border-manila pl-3">RRI Stasiun Batam</p>

                    <h1 class="font-display font-extrabold text-5xl sm:text-6xl lg:text-[4.25rem] leading-[1.02] tracking-tight">
                        Rancang Bangun Website Arsip Aris di RRI Batam
                    </h1>

                    <p class="mt-7 text-lg text-steel leading-relaxed max-w-xl">
                        ARIS (Arsip Digital) adalah sistem pengelolaan arsip berbasis website yang dirancang untuk membantu RRI Stasiun Batam dalam menyimpan, mengklasifikasi, dan menelusuri dokumen arsip secara cepat, aman, dan terstruktur — menggantikan pencatatan manual yang selama ini digunakan.
                    </p>

                    <div class="mt-9 flex flex-wrap items-center gap-x-8 gap-y-4">
                        @auth
                            <a href="{{ route('dashboard.index') }}" class="font-semibold bg-manila text-ink px-7 py-3.5 rounded hover:brightness-95">Buka sistem arsip</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold bg-manila text-ink px-7 py-3.5 rounded hover:brightness-95">Masuk ke sistem</a>
                        @endauth
                        <a href="#tentang" class="font-semibold underline underline-offset-8 decoration-2 decoration-line hover:decoration-ink">Pelajari sistem</a>
                    </div>
                </div>

                {{-- Ilustrasi: surat yang dipindai --}}
                <div class="lg:col-span-5" aria-hidden="true">
                    <div class="relative max-w-sm mx-auto">
                        <div class="relative bg-white border border-line rounded shadow-[6px_6px_0_#10283A] p-6 overflow-hidden rotate-[-1.5deg]">
                            <div class="scan-line"></div>
                            <div class="font-type text-[11px] leading-relaxed text-ink">
                                <div class="font-bold text-xs">RRI STASIUN BATAM</div>
                                <div class="mt-3">Nomor &nbsp;: 123/RRI/BTM/IV/2024</div>
                                <div>Perihal : Undangan Rapat</div>
                            </div>
                            <div class="mt-4 space-y-2">
                                <div class="h-1.5 bg-line rounded w-full"></div>
                                <div class="h-1.5 bg-line rounded w-11/12"></div>
                                <div class="h-1.5 bg-line rounded w-full"></div>
                                <div class="h-1.5 bg-line rounded w-3/4"></div>
                                <div class="h-1.5 bg-line rounded w-10/12"></div>
                                <div class="h-1.5 bg-line rounded w-2/3"></div>
                            </div>
                            <div class="mt-6 h-10 w-24 border-2 border-steel/40 rounded-full"></div>
                        </div>

                        <div class="relative -mt-6 ml-10 bg-ink text-white rounded p-5 rotate-[1deg]">
                            <div class="text-xs text-white/60 mb-3">Contoh data arsip tersimpan</div>
                            <dl class="font-type text-xs space-y-1.5">
                                <div class="flex gap-3"><dt class="w-16 text-manila">Nomor</dt><dd>123/RRI/BTM/IV/2024</dd></div>
                                <div class="flex gap-3"><dt class="w-16 text-manila">Jenis</dt><dd>Surat masuk</dd></div>
                                <div class="flex gap-3"><dt class="w-16 text-manila">Tahun</dt><dd>2024</dd></div>
                            </dl>
                        </div>
                    </div>
                </div>

            </div>
        </section>
    </main>

    {{-- ================= TENTANG ================= --}}
    <section id="tentang" class="bg-white border-y border-line py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 grid lg:grid-cols-12 gap-12">
            <div class="lg:col-span-6">
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight leading-tight">Mengapa arsip digital dibutuhkan?</h2>
                <p class="mt-6 text-steel leading-relaxed max-w-prose">
                    Pengelolaan arsip di RRI Stasiun Batam selama ini masih banyak dilakukan secara manual, mulai dari pencatatan buku agenda hingga penyimpanan fisik dokumen. Cara ini rentan terhadap kehilangan data, kesulitan pencarian, dan keterbatasan ruang penyimpanan.
                </p>
                <p class="mt-4 text-steel leading-relaxed max-w-prose">
                    Melalui website ARIS, proses pengarsipan surat, dokumen, dan berkas kedinasan dapat dilakukan secara digital — mempermudah pencarian, meningkatkan keamanan data, serta mendukung efisiensi kerja pegawai RRI Batam.
                </p>
            </div>

            <ul class="lg:col-span-5 lg:col-start-8 self-center divide-y divide-line border-y border-line">
                <li class="py-5 flex items-center gap-4"><span class="w-2.5 h-2.5 bg-manila rotate-45"></span><span class="font-display font-bold text-lg">Keamanan data</span></li>
                <li class="py-5 flex items-center gap-4"><span class="w-2.5 h-2.5 bg-manila rotate-45"></span><span class="font-display font-bold text-lg">Pencarian cepat</span></li>
                <li class="py-5 flex items-center gap-4"><span class="w-2.5 h-2.5 bg-manila rotate-45"></span><span class="font-display font-bold text-lg">Akses digital</span></li>
                <li class="py-5 flex items-center gap-4"><span class="w-2.5 h-2.5 bg-manila rotate-45"></span><span class="font-display font-bold text-lg">Klasifikasi terstruktur</span></li>
            </ul>
        </div>
    </section>

    {{-- ================= FITUR ================= --}}
    <section id="fitur" class="py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="max-w-2xl mb-14">
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight">Kemampuan website ARIS</h2>
                <p class="mt-4 text-steel leading-relaxed">Dirancang khusus untuk mendukung alur kerja kearsipan di lingkungan RRI Stasiun Batam, dari input dokumen hingga pelaporan.</p>
            </div>

            <div class="grid md:grid-cols-2 lg:grid-cols-3 border-t-2 border-ink">
                @php
                    $fitur = [
                        ['Input & unggah arsip', 'Pegawai dapat mengunggah dokumen arsip beserta metadata seperti nomor surat, tanggal, kategori, dan unit kerja terkait secara langsung melalui website.'],
                        ['Klasifikasi & kategori', 'Setiap arsip dikelompokkan berdasarkan jenis dokumen, unit kerja, dan tahun, sehingga penelusuran data menjadi lebih rapi dan sistematis.'],
                        ['Pencarian arsip', 'Fitur pencarian memungkinkan pegawai menemukan dokumen berdasarkan kata kunci, nomor surat, atau rentang tanggal hanya dalam hitungan detik.'],
                        ['Hak akses bertingkat', 'Setiap pengguna memiliki hak akses berbeda sesuai peran — admin, kepala unit, atau staf — untuk menjaga keamanan dan validitas data arsip.'],
                        ['Riwayat & log aktivitas', 'Setiap perubahan pada data arsip tercatat otomatis, memudahkan audit dan pelacakan siapa yang mengubah atau menambahkan dokumen.'],
                        ['Cetak & ekspor laporan', 'Data arsip dapat diekspor atau dicetak dalam bentuk laporan rekapitulasi untuk kebutuhan administrasi dan pelaporan kedinasan.'],
                    ];
                @endphp
                @foreach ($fitur as $f)
                    <article class="py-8 md:pr-10 border-b border-line">
                        <h3 class="font-display font-bold text-xl mb-3">{{ $f[0] }}</h3>
                        <p class="text-steel text-[15px] leading-relaxed">{{ $f[1] }}</p>
                    </article>
                @endforeach
            </div>
        </div>
    </section>

    {{-- ================= ALUR KERJA ================= --}}
    <section id="alur" class="bg-ink text-white py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="max-w-2xl mb-14">
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight">Dari dokumen fisik ke arsip digital</h2>
                <p class="mt-4 text-white/65 leading-relaxed">Empat tahap sederhana yang dilalui setiap dokumen sebelum menjadi arsip digital yang tertata di sistem ARIS.</p>
            </div>

            <ol class="grid md:grid-cols-4 gap-10 md:gap-6 relative">
                <div class="hidden md:block absolute top-[15px] left-0 right-0 h-px bg-white/25"></div>
                @php
                    $alur = [
                        ['Penerimaan dokumen', 'Dokumen atau surat diterima oleh bagian tata usaha RRI Batam.'],
                        ['Input ke sistem', 'Petugas mengunggah dan mencatat metadata dokumen ke website ARIS.'],
                        ['Klasifikasi otomatis', 'Sistem mengelompokkan arsip berdasarkan kategori dan unit kerja terkait.'],
                        ['Penelusuran & laporan', 'Arsip siap dicari, diakses, dan direkap kapan pun dibutuhkan.'],
                    ];
                @endphp
                @foreach ($alur as $i => $a)
                    <li class="relative">
                        <span class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full bg-manila text-ink font-display font-extrabold text-sm">{{ $i + 1 }}</span>
                        <h3 class="mt-5 font-display font-bold text-lg">{{ $a[0] }}</h3>
                        <p class="mt-2 text-white/65 text-sm leading-relaxed">{{ $a[1] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-paper border-t border-line py-8">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-steel">
            <div class="flex items-end gap-2.5">
                <span class="folder-tab bg-manila text-ink font-display font-extrabold text-xs pl-2.5 pr-5 py-1">RRI</span>
                <span class="font-display font-bold text-ink">Website Arsip ARIS - RRI Batam</span>
            </div>
            <p>&copy; {{ date('Y') }} RRI Stasiun Batam. Rancang Bangun Website Arsip Aris.</p>
        </div>
    </footer>

</body>
</html>