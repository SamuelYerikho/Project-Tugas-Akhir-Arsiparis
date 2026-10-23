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
            --c-paper:       241 245 246;
            --c-surface:     255 255 255;
            --c-ink:          16  40  58;
            --c-steel:        91 112 131;
            --c-line:        201 212 217;
            --c-manila:      226 184  75;
            --c-on-manila:    16  40  58;
            --c-invert:       16  40  58;
            --c-on-invert:   255 255 255;
            --c-shadow:       16  40  58;
        }
        .dark {
            --c-paper:        10  24  38;
            --c-surface:      18  39  58;
            --c-ink:         233 241 246;
            --c-steel:       147 168 184;
            --c-line:         36  64  90;
            --c-manila:      226 184  75;
            --c-on-manila:    16  40  58;
            --c-invert:       23  54  75;
            --c-on-invert:   233 241 246;
            --c-shadow:      226 184  75;
        }

        /* Satu-satunya gerakan otomatis di area konten: garis pindai di atas surat */
        @keyframes scan { 0% { top: 6%; opacity: 0; } 10% { opacity: 1; } 90% { opacity: 1; } 100% { top: 94%; opacity: 0; } }
        .scan-line { position: absolute; left: 0; right: 0; height: 2px; background: #E2B84B; box-shadow: 0 0 14px 3px rgba(226,184,75,.55); animation: scan 4.5s ease-in-out infinite; }

        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        .sticker-shadow { box-shadow: 6px 6px 0 rgb(var(--c-shadow)); }
        a:focus-visible, button:focus-visible { outline: 3px solid #E2B84B; outline-offset: 3px; }

        /* ============ LATAR ASTROFIL 3D (MODE GELAP) ============ */
        .star-field {
            display: none;
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
            perspective: 1400px;
            perspective-origin: 50% 40%;
            background-color: rgb(var(--c-paper));
            background-image:
                radial-gradient(1200px 700px at 72% -12%, rgba(76, 29, 149, 0.30), transparent 65%),
                radial-gradient(900px  550px at  4%  92%, rgba(14, 116, 144, 0.18), transparent 65%),
                radial-gradient(700px  450px at 96%  78%, rgba(190, 60,  80, 0.14), transparent 65%);
            background-repeat: no-repeat;
        }
        .dark .star-field { display: block; }

        /* ---- NEBULA (paling belakang, blur berat = jauh) ---- */
        .nebula {
            position: absolute;
            border-radius: 50%;
            filter: blur(90px);
            will-change: transform;
        }
        .nebula-1 {
            width: 560px; height: 560px;
            top: -12%; left: 28%;
            background: radial-gradient(circle, rgba(76, 29, 149, 0.80), transparent 70%);
            opacity: 0.38;
            animation: drift-a 90s ease-in-out infinite;
        }
        .nebula-2 {
            width: 460px; height: 460px;
            bottom: -8%; left: -6%;
            background: radial-gradient(circle, rgba(14, 116, 144, 0.60), transparent 70%);
            opacity: 0.34;
            animation: drift-b 115s ease-in-out infinite;
        }
        .nebula-3 {
            width: 400px; height: 400px;
            top: 32%; right: -10%;
            background: radial-gradient(circle, rgba(190, 60, 80, 0.50), transparent 70%);
            opacity: 0.30;
            animation: drift-c 100s ease-in-out infinite;
        }

        /* ---- PLANET: shading berlapis + rim light ---- */
        .planet {
            position: absolute;
            border-radius: 50%;
            will-change: transform;
            transform-style: preserve-3d;
        }

        /* Planet biru besar (gas giant) */
        .planet-1 {
            width: 220px; height: 220px;
            top: 8%; right: 5%;
            background:
                radial-gradient(circle at 30% 26%,
                    #7fb3e0 0%,
                    #4a7fb5 20%,
                    #1e4a72 48%,
                    #0e2a45 76%,
                    #05121f 100%);
            box-shadow:
                inset -35px -25px 65px rgba(0, 0, 0, 0.80),
                inset  14px  14px 28px rgba(160, 210, 255, 0.16),
                inset 0 0 55px rgba(0, 0, 0, 0.45),
                0 0 80px  rgba(74, 127, 181, 0.30),
                0 0 220px rgba(74, 127, 181, 0.10);
            opacity: 0.72;
            animation: drift-a 70s ease-in-out infinite;
        }
        /* Pita atmosfer berputar */
        .planet-1::before {
            content: '';
            position: absolute;
            inset: 0;
            border-radius: 50%;
            background: conic-gradient(
                from 45deg,
                transparent 0deg,
                rgba(0, 0, 0, 0.20) 70deg,
                transparent 150deg,
                rgba(160, 210, 255, 0.10) 220deg,
                transparent 300deg,
                rgba(0, 0, 0, 0.18) 360deg);
            mix-blend-mode: soft-light;
            animation: spin 160s linear infinite;
            pointer-events: none;
        }
        /* Rim light atmospheric */
        .planet-1::after {
            content: '';
            position: absolute;
            inset: -2px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%,
                transparent 62%,
                rgba(130, 190, 240, 0.28) 90%,
                transparent 100%);
            pointer-events: none;
        }

        /* Planet hangat bercincin (cincin pakai rotateX = 3D asli) */
        .planet-2 {
            width: 122px; height: 122px;
            bottom: 10%; left: 6%;
            background:
                radial-gradient(circle at 32% 28%,
                    #f5c78a 0%,
                    #d99a5e 20%,
                    #a15a2a 52%,
                    #5c2a10 82%,
                    #2a1004 100%);
            box-shadow:
                inset -20px -20px 36px rgba(0, 0, 0, 0.72),
                inset   8px   8px 16px rgba(255, 220, 180, 0.16),
                0 0 60px  rgba(217, 154, 94, 0.30),
                0 0 140px rgba(217, 154, 94, 0.10);
            opacity: 0.78;
            animation: drift-b 90s ease-in-out infinite;
        }
        .planet-2::after {
            content: '';
            position: absolute;
            inset: -30px -48px;
            border-radius: 50%;
            border: 2px solid rgba(217, 154, 94, 0.50);
            border-bottom-color: transparent;
            transform: rotateX(74deg) rotate(-14deg);
            box-shadow:
                0 0 26px rgba(217, 154, 94, 0.28),
                inset 0 0 16px rgba(217, 154, 94, 0.16);
            pointer-events: none;
        }

        /* Bulan pucat kecil */
        .planet-3 {
            width: 46px; height: 46px;
            top: 44%; right: 20%;
            background:
                radial-gradient(circle at 30% 30%,
                    #f0f5f9 0%,
                    #b5c4d0 42%,
                    #7a8fa1 72%,
                    #4e6579 100%);
            box-shadow:
                inset -7px -7px 14px rgba(0, 0, 0, 0.60),
                inset  3px  3px  6px rgba(255, 255, 255, 0.20),
                0 0 25px rgba(216, 228, 236, 0.32);
            opacity: 0.88;
            animation: drift-c 50s ease-in-out infinite;
        }

        /* ---- DUST / debu foreground (paling dekat, tajam) ---- */
        .dust {
            position: absolute;
            width: 3px; height: 3px;
            border-radius: 50%;
            background: rgba(233, 241, 246, 0.75);
            box-shadow: 0 0 6px rgba(233, 241, 246, 0.55);
            will-change: transform;
        }
        .dust-1 { top: 20%; left: 14%; animation: float-a 26s ease-in-out infinite; }
        .dust-2 { top: 62%; left: 82%; width: 2px; height: 2px; animation: float-b 34s ease-in-out infinite; }
        .dust-3 { top: 76%; left: 24%; animation: float-c 30s ease-in-out infinite; }
        .dust-4 { top: 34%; left: 68%; width: 2px; height: 2px; animation: float-a 40s ease-in-out infinite; }

        /* ---- Gerakan ---- */
        @keyframes drift-a {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)); }
            50%      { transform: translate(calc(var(--px, 0px) - 14px), calc(var(--py, 0px) + 10px)); }
        }
        @keyframes drift-b {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)); }
            50%      { transform: translate(calc(var(--px, 0px) + 10px), calc(var(--py, 0px) - 8px)); }
        }
        @keyframes drift-c {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)); }
            50%      { transform: translate(calc(var(--px, 0px) - 6px),  calc(var(--py, 0px) + 6px)); }
        }
        @keyframes spin {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }
        @keyframes float-a {
            0%, 100% { transform: translate(0, 0);        opacity: 0.35; }
            50%      { transform: translate(22px, -16px); opacity: 0.85; }
        }
        @keyframes float-b {
            0%, 100% { transform: translate(0, 0);        opacity: 0.50; }
            50%      { transform: translate(-16px, 22px); opacity: 0.90; }
        }
        @keyframes float-c {
            0%, 100% { transform: translate(0, 0);        opacity: 0.30; }
            50%      { transform: translate(14px, -12px); opacity: 0.75; }
        }

        /* ============ BINTANG JATUH ============ */
        .shooting-star {
            position: absolute;
            width: 160px;
            height: 1.5px;
            border-radius: 999px;
            background: linear-gradient(90deg,
                rgba(226, 184,  75, 0.00)  0%,
                rgba(226, 184,  75, 0.65) 55%,
                rgba(233, 241, 246, 0.95) 100%);
            filter: drop-shadow(0 0 6px rgba(226, 184, 75, 0.55));
            opacity: 0;
            will-change: transform, opacity;
        }
        .shoot-1 { top: -6%; left: 4%;  animation: shoot 14s linear infinite; }
        .shoot-2 { top: -6%; left: 42%; animation: shoot 18s linear infinite; animation-delay: 6s; }
        .shoot-3 { top: -6%; left: 72%; animation: shoot 22s linear infinite; animation-delay: 13s; }

        @keyframes shoot {
            0%   { transform: translate3d(0, 0, 0) rotate(22deg);       opacity: 0; }
            4%   {                                                       opacity: 1; }
            15%  {                                                       opacity: 0.9; }
            22%  { transform: translate3d(90vw, 45vh, 0) rotate(22deg);  opacity: 0; }
            100% { transform: translate3d(90vw, 45vh, 0) rotate(22deg);  opacity: 0; }
        }

        /* Planet & bintang jatuh di layar kecil */
        @media (max-width: 640px) {
            .planet-1 { width: 140px; height: 140px; opacity: 0.55; }
            .planet-2 { width:  84px; height:  84px; opacity: 0.62; }
            .planet-3 { width:  34px; height:  34px; }
            .shooting-star { width: 120px; }
            .nebula    { filter: blur(60px); }
        }

        @media (prefers-reduced-motion: reduce) {
            .scan-line     { animation: none; top: 50%; opacity: 1; }
            html           { scroll-behavior: auto; }
            .shooting-star { animation: none; opacity: 0; }
            .planet, .nebula, .dust,
            .planet-1::before, .planet-2::after { animation: none; }
        }
    </style>
</head>
<body class="font-sans antialiased bg-paper text-ink min-h-screen flex flex-col transition-colors duration-300">

    {{-- ================= LATAR ASTROFIL 3D (MODE GELAP) ================= --}}
    <div class="star-field" aria-hidden="true">
        <!-- Nebula belakang (paling jauh) -->
        <div class="nebula nebula-1"></div>
        <div class="nebula nebula-2"></div>
        <div class="nebula nebula-3"></div>

        <!-- Planet (mid ground) -->
        <div class="planet planet-1"></div>
        <div class="planet planet-2"></div>
        <div class="planet planet-3"></div>

        <!-- Debu foreground -->
        <div class="dust dust-1"></div>
        <div class="dust dust-2"></div>
        <div class="dust dust-3"></div>
        <div class="dust dust-4"></div>

        <!-- Bintang jatuh -->
        <div class="shooting-star shoot-1"></div>
        <div class="shooting-star shoot-2"></div>
        <div class="shooting-star shoot-3"></div>
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

        /* ---- Parallax mouse untuk latar astrophile 3D ---- */
        (function () {
            var field = document.querySelector('.star-field');
            if (!field) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            var layers = [
                { els: field.querySelectorAll('.nebula'),   depth: 0.4 },
                { els: field.querySelectorAll('.planet-1'), depth: 1.4 },
                { els: field.querySelectorAll('.planet-2'), depth: 1.0 },
                { els: field.querySelectorAll('.planet-3'), depth: 1.8 },
                { els: field.querySelectorAll('.dust'),     depth: 2.6 }
            ];

            var mx = 0, my = 0, cx = 0, cy = 0;

            function onMove(e) {
                mx = (e.clientX / window.innerWidth  - 0.5) * 2;
                my = (e.clientY / window.innerHeight - 0.5) * 2;
            }
            window.addEventListener('mousemove', onMove, { passive: true });

            (function tick() {
                cx += (mx - cx) * 0.06;
                cy += (my - cy) * 0.06;
                layers.forEach(function (g) {
                    var x = (-cx * 14 * g.depth).toFixed(2) + 'px';
                    var y = (-cy * 14 * g.depth).toFixed(2) + 'px';
                    g.els.forEach(function (el) {
                        el.style.setProperty('--px', x);
                        el.style.setProperty('--py', y);
                    });
                });
                requestAnimationFrame(tick);
            })();
        })();
    </script>
</body>
</html>