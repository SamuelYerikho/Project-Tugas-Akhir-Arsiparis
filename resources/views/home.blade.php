<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Rancang Bangun Website Arsip Aris di RRI Batam</title>

    {{-- Terapkan tema sebelum render untuk mencegah kedip (flash) --}}
    <script>
        (function () {
            var saved = localStorage.getItem('theme');
            var prefersDark = window.matchMedia('(prefers-color-scheme: dark)').matches;
            if (saved === 'dark' || (!saved && prefersDark)) {
                document.documentElement.classList.add('dark');
            }
        })();
    </script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&family=Courier+Prime:wght@400;700&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Bricolage Grotesque"', 'sans-serif'],
                        sans: ['"Public Sans"', 'sans-serif'],
                        type: ['"Courier Prime"', 'monospace']
                    },
                    colors: {
                        ink:         'rgb(var(--c-ink) / <alpha-value>)',
                        paper:       'rgb(var(--c-paper) / <alpha-value>)',
                        surface:     'rgb(var(--c-surface) / <alpha-value>)',
                        manila:      'rgb(var(--c-manila) / <alpha-value>)',
                        'on-manila': 'rgb(var(--c-on-manila) / <alpha-value>)',
                        steel:       'rgb(var(--c-steel) / <alpha-value>)',
                        line:        'rgb(var(--c-line) / <alpha-value>)',
                        invert:      'rgb(var(--c-invert) / <alpha-value>)',
                        'on-invert': 'rgb(var(--c-on-invert) / <alpha-value>)'
                    }
                }
            }
        }
    </script>

    <style>
        /* ============ PALET TEMA ============ */
        :root {
            --c-paper:       241 245 246; /* #F1F5F6 */
            --c-surface:     255 255 255; /* #FFFFFF */
            --c-ink:          16  40  58; /* #10283A */
            --c-steel:        91 112 131; /* #5B7083 */
            --c-line:        201 212 217; /* #C9D4D9 */
            --c-manila:      226 184  75; /* #E2B84B */
            --c-on-manila:    16  40  58; /* #10283A */
            --c-invert:       16  40  58; /* #10283A */
            --c-on-invert:   255 255 255; /* #FFFFFF */
            --c-shadow:       16  40  58; /* #10283A */
        }
        .dark {
            --c-paper:        10  24  38; /* #0A1826 */
            --c-surface:      18  39  58; /* #12273A */
            --c-ink:         233 241 246; /* #E9F1F6 */
            --c-steel:       147 168 184; /* #93A8B8 */
            --c-line:         36  64  90; /* #24405A */
            --c-manila:      226 184  75; /* #E2B84B */
            --c-on-manila:    16  40  58; /* #10283A */
            --c-invert:       23  54  75; /* #17364B */
            --c-on-invert:   233 241 246; /* #E9F1F6 */
            --c-shadow:      226 184  75; /* #E2B84B (aksen manila) */
        }

        /* Satu-satunya gerakan otomatis: garis pindai di atas surat */
        @@keyframes scan { 0% { top: 6%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 94%; opacity: 0; } }
        .scan-line { position: absolute; left: 0; right: 0; height: 2px; background: #E2B84B; box-shadow: 0 0 14px 3px rgba(226,184,75,.55); animation: scan 4.5s ease-in-out infinite; }

        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        .sticker-shadow { box-shadow: 6px 6px 0 rgb(var(--c-shadow)); }
        a:focus-visible, button:focus-visible { outline: 3px solid #E2B84B; outline-offset: 3px; }

        /* ============ BINTANG BERKELAP-KELIP (MODE GELAP) ============ */
        /* Lapisan bintang fixed di belakang konten. Hanya tampil di mode gelap.
           Tiga lapisan dengan offset & durasi animasi berbeda menghasilkan efek twinkle. */
        .star-field {
            display: none;
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
        }
        .dark .star-field { display: block; }

        .star-layer {
            position: absolute;
            inset: -240px;
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='240' height='240' viewBox='0 0 240 240'%3E%3Cpath d='M40 50l2.5 7.5 7.5 2.5-7.5 2.5-2.5 7.5-2.5-7.5-7.5-2.5 7.5-2.5z' fill='%23E2B84B' fill-opacity='0.9'/%3E%3Cpath d='M180 100l2 6 6 2-6 2-2 6-2-6-6-2 6-2z' fill='%234DD0E1' fill-opacity='0.85'/%3E%3Cpath d='M90 180l2 6 6 2-6 2-2 6-2-6-6-2 6-2z' fill='%23F48FB1' fill-opacity='0.85'/%3E%3Cpath d='M200 200l1.5 4.5 4.5 1.5-4.5 1.5-1.5 4.5-1.5-4.5-4.5-1.5 4.5-1.5z' fill='%23B39DDB' fill-opacity='0.85'/%3E%3Cpath d='M120 40l1.5 4.5 4.5 1.5-4.5 1.5-1.5 4.5-1.5-4.5-4.5-1.5 4.5-1.5z' fill='%2380CBC4' fill-opacity='0.9'/%3E%3Cpath d='M30 140l1.8 5.4 5.4 1.8-5.4 1.8-1.8 5.4-1.8-5.4-5.4-1.8 5.4-1.8z' fill='%23FFB74D' fill-opacity='0.8'/%3E%3Cpath d='M150 90l1.2 3.6 3.6 1.2-3.6 1.2-1.2 3.6-1.2-3.6-3.6-1.2 3.6-1.2z' fill='%2364B5F6' fill-opacity='0.8'/%3E%3Ccircle cx='150' cy='30' r='1.2' fill='%23CE93D8' fill-opacity='0.95'/%3E%3Ccircle cx='60' cy='120' r='1' fill='%23E9F1F6' fill-opacity='0.85'/%3E%3Ccircle cx='200' cy='150' r='1' fill='%23CE93D8' fill-opacity='0.9'/%3E%3Ccircle cx='20' cy='200' r='0.9' fill='%2380CBC4' fill-opacity='0.9'/%3E%3Ccircle cx='130' cy='150' r='1.1' fill='%23F48FB1' fill-opacity='0.9'/%3E%3Ccircle cx='220' cy='60' r='0.8' fill='%23E9F1F6' fill-opacity='0.8'/%3E%3Ccircle cx='80' cy='20' r='0.9' fill='%234DD0E1' fill-opacity='0.9'/%3E%3Ccircle cx='170' cy='170' r='0.9' fill='%23FFB74D' fill-opacity='0.85'/%3E%3C/svg%3E");
            background-size: 240px 240px;
            background-repeat: repeat;
            opacity: 0;
            animation: twinkle ease-in-out infinite;
            will-change: opacity;
        }
        .star-layer-1 { background-position:    0px   0px; animation-duration: 3.2s; animation-delay: 0s;   }
        .star-layer-2 { background-position:  120px  60px; animation-duration: 4.6s; animation-delay: 1.1s; }
        .star-layer-3 { background-position:   40px 180px; animation-duration: 5.8s; animation-delay: 2.3s; }

        @@keyframes twinkle {
            0%, 100% { opacity: 0.12; }
            45%      { opacity: 1; }
            60%      { opacity: 0.85; }
        }

        @@media (prefers-reduced-motion: reduce) {
            .scan-line { animation: none; top: 50%; opacity: 1; }
            html { scroll-behavior: auto; }
            .star-layer { animation: none; opacity: 0.55; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-paper text-ink min-h-screen flex flex-col transition-colors duration-300">

    {{-- ================= BINTANG BERKELAP-KELIP (MODE GELAP) ================= --}}
    {{-- Hanya tampil saat tema gelap; 3 lapisan dengan offset & animasi berbeda. --}}
    <div class="star-field" aria-hidden="true">
        <div class="star-layer star-layer-1"></div>
        <div class="star-layer star-layer-2"></div>
        <div class="star-layer star-layer-3"></div>
    </div>

    {{-- ================= NAVBAR ================= --}}
    <header class="border-b border-line">
        <nav class="max-w-6xl mx-auto px-6 lg:px-8 h-20 flex items-center justify-between">

            <a href="/" class="flex items-end gap-3">
                <span class="folder-tab bg-manila text-on-manila font-display font-extrabold text-sm pl-3 pr-6 py-1.5">RRI</span>
                <span class="font-display font-extrabold text-xl tracking-tight leading-none">Arsip Aris</span>
            </a>

            <div class="hidden md:flex items-center gap-9 text-sm font-medium text-steel">
                <a href="#tentang" class="hover:text-ink transition-colors">Tentang</a>
                <a href="#fitur" class="hover:text-ink transition-colors">Fitur</a>
                <a href="#alur" class="hover:text-ink transition-colors">Alur kerja</a>
            </div>

            <div class="flex items-center gap-2 sm:gap-3">

                {{-- Tombol ganti tema --}}
                <button type="button" onclick="toggleTheme()" aria-label="Ganti tema terang atau gelap"
                        class="p-2.5 rounded border border-line text-steel hover:text-ink hover:border-ink transition-colors">
                    <svg class="w-5 h-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                    <svg class="w-5 h-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                </button>

                @if (Route::has('login'))
                    @auth
                        <a href="{{ route('dashboard.index') }}" class="text-sm font-semibold bg-invert text-on-invert px-5 py-2.5 rounded hover:bg-invert/90 transition-colors">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold bg-invert text-on-invert px-5 py-2.5 rounded hover:bg-invert/90 transition-colors">Masuk sistem</a>
                    @endauth
                @endif
            </div>
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
                            <a href="{{ route('dashboard.index') }}" class="font-semibold bg-manila text-on-manila px-7 py-3.5 rounded hover:brightness-95 transition">Buka sistem arsip</a>
                        @else
                            <a href="{{ route('login') }}" class="font-semibold bg-manila text-on-manila px-7 py-3.5 rounded hover:brightness-95 transition">Masuk ke sistem</a>
                        @endauth
                        <a href="#tentang" class="font-semibold underline underline-offset-8 decoration-2 decoration-line hover:decoration-ink transition-colors">Pelajari sistem</a>
                    </div>
                </div>

                {{-- Ilustrasi: surat yang dipindai --}}
                <div class="lg:col-span-5" aria-hidden="true">
                    <div class="relative max-w-sm mx-auto">
                        <div class="relative bg-surface border border-line rounded sticker-shadow p-6 overflow-hidden rotate-[-1.5deg]">
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

                        <div class="relative -mt-6 ml-10 bg-invert text-on-invert rounded p-5 rotate-[1deg]">
                            <div class="text-xs text-on-invert/60 mb-3">Contoh data arsip tersimpan</div>
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
    <section id="tentang" class="bg-surface border-y border-line py-20 lg:py-24">
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
    <section id="alur" class="bg-invert text-on-invert py-20 lg:py-24">
        <div class="max-w-6xl mx-auto px-6 lg:px-8">
            <div class="max-w-2xl mb-14">
                <h2 class="font-display font-extrabold text-3xl sm:text-4xl tracking-tight">Dari dokumen fisik ke arsip digital</h2>
                <p class="mt-4 text-on-invert/65 leading-relaxed">Empat tahap sederhana yang dilalui setiap dokumen sebelum menjadi arsip digital yang tertata di sistem ARIS.</p>
            </div>

            <ol class="grid md:grid-cols-4 gap-10 md:gap-6 relative">
                <div class="hidden md:block absolute top-[15px] left-0 right-0 h-px bg-on-invert/25"></div>
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
                        <span class="relative z-10 flex items-center justify-center w-8 h-8 rounded-full bg-manila text-on-manila font-display font-extrabold text-sm">{{ $i + 1 }}</span>
                        <h3 class="mt-5 font-display font-bold text-lg">{{ $a[0] }}</h3>
                        <p class="mt-2 text-on-invert/65 text-sm leading-relaxed">{{ $a[1] }}</p>
                    </li>
                @endforeach
            </ol>
        </div>
    </section>

    {{-- ================= FOOTER ================= --}}
    <footer class="bg-paper border-t border-line py-8">
        <div class="max-w-6xl mx-auto px-6 lg:px-8 flex flex-col sm:flex-row items-center justify-between gap-3 text-sm text-steel">
            <div class="flex items-end gap-2.5">
                <span class="folder-tab bg-manila text-on-manila font-display font-extrabold text-xs pl-2.5 pr-5 py-1">RRI</span>
                <span class="font-display font-bold text-ink">Website Arsip ARIS - RRI Batam</span>
            </div>
            <p>&copy; {{ date('Y') }} RRI Stasiun Batam. Rancang Bangun Website Arsip Aris.</p>
        </div>
    </footer>

    <script>
        function toggleTheme() {
            var root = document.documentElement;
            var isDark = root.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }
    </script>
</body>
</html>