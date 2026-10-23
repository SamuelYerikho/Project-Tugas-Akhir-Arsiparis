<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | Sistem Arsip ARIS - RRI Batam</title>

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
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Bricolage Grotesque"', 'sans-serif'],
                        sans: ['"Public Sans"', 'sans-serif']
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
        }

        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        a:focus-visible, button:focus-visible { outline: 3px solid #E2B84B; outline-offset: 3px; }

        /* ============ LATAR ASTROFIL 3D (MODE GELAP) ============ */
        /* Panel kiri (bg-invert, opak) menutupinya, jadi astrofil hanya terlihat
           di area form (kanan). Hanya tampil di mode gelap. */
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

        /* Planet biru besar (kanan atas area form) — gas giant */
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

        /* Planet hangat bercincin (kiri bawah — terutama terlihat di mobile) */
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

        /* Bulan pucat kecil (kanan tengah area form) */
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
        .dust-1 { top: 20%; left: 54%; animation: float-a 26s ease-in-out infinite; }
        .dust-2 { top: 62%; left: 88%; width: 2px; height: 2px; animation: float-b 34s ease-in-out infinite; }
        .dust-3 { top: 76%; left: 62%; animation: float-c 30s ease-in-out infinite; }
        .dust-4 { top: 34%; left: 78%; width: 2px; height: 2px; animation: float-a 40s ease-in-out infinite; }

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
        .shoot-1 { top: -6%; left: 54%; animation: shoot 14s linear infinite; }
        .shoot-2 { top: -6%; left: 70%; animation: shoot 18s linear infinite; animation-delay: 6s; }
        .shoot-3 { top: -6%; left: 84%; animation: shoot 22s linear infinite; animation-delay: 13s; }

        @keyframes shoot {
            0%   { transform: translate3d(0, 0, 0) rotate(22deg);       opacity: 0; }
            4%   {                                                       opacity: 1; }
            15%  {                                                       opacity: 0.9; }
            22%  { transform: translate3d(40vw, 45vh, 0) rotate(22deg);  opacity: 0; }
            100% { transform: translate3d(40vw, 45vh, 0) rotate(22deg);  opacity: 0; }
        }

        /* Planet & bintang jatuh di layar kecil */
        @media (max-width: 640px) {
            .planet-1 { width: 140px; height: 140px; opacity: 0.55; }
            .planet-2 { width:  84px; height:  84px; opacity: 0.62; }
            .planet-3 { width:  34px; height:  34px; }
            .shooting-star { width: 120px; }
            .nebula    { filter: blur(60px); }
            /* di mobile seluruh layar terbuka, geser planet agar tidak menabrak form */
            .planet-1 { top: 4%; right: 4%; }
            .planet-2 { bottom: 6%; left: 4%; }
        }

        @media (prefers-reduced-motion: reduce) {
            .shooting-star { animation: none; opacity: 0; }
            .planet, .nebula, .dust,
            .planet-1::before, .planet-2::after { animation: none; }
        }
    </style>

    {{-- SweetAlert2: untuk notifikasi flash message (logout, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans antialiased text-ink bg-paper transition-colors duration-300">

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

    <div class="flex min-h-screen">

        {{-- ================= KIRI: Branding (selaras dengan home) ================= --}}
        <div class="hidden lg:flex lg:w-5/12 relative bg-invert text-on-invert flex-col justify-between overflow-hidden px-14 xl:px-20 py-14">

            <div class="flex items-end gap-3">
                <span class="folder-tab bg-manila text-on-manila font-display font-extrabold text-base pl-4 pr-8 py-2">RRI</span>
            </div>

            <div>
                <h1 class="font-display font-extrabold text-4xl xl:text-5xl tracking-tight leading-[1.05] mb-5">
                    Sistem Arsip ARIS<br>RRI Stasiun Batam
                </h1>
                <p class="text-on-invert/65 text-base max-w-sm leading-relaxed">
                    Kelola, telusuri, dan amankan seluruh dokumen kedinasan RRI Batam dalam satu platform arsip digital terintegrasi.
                </p>

                {{-- Ilustrasi tumpukan map arsip (tanpa teks) --}}
                <div class="relative mt-12 h-40 max-w-sm" aria-hidden="true">
                    <div class="absolute bottom-0 left-0 w-full h-28 bg-on-invert/10 rounded-sm"></div>
                    <div class="absolute bottom-0 left-0 h-6 w-24 bg-on-invert/10 folder-tab -translate-y-[6.5rem]"></div>
                    <div class="absolute bottom-0 left-6 w-[calc(100%-1.5rem)] h-24 bg-steel/60 rounded-sm"></div>
                    <div class="absolute bottom-0 left-6 h-6 w-24 bg-steel/60 folder-tab -translate-y-[5.5rem] translate-x-28"></div>
                    <div class="absolute bottom-0 left-12 w-[calc(100%-3rem)] h-20 bg-manila rounded-sm"></div>
                    <div class="absolute bottom-0 left-12 h-6 w-24 bg-manila folder-tab -translate-y-[4.5rem] translate-x-56"></div>
                </div>

                <div class="mt-10 inline-flex items-center gap-3 text-sm font-semibold text-on-invert border-l-4 border-manila pl-3">
                    Radio Republik Indonesia - Stasiun Batam
                </div>
            </div>

        </div>

        {{-- ================= KANAN: Form Login (transparan, astrofil tembus) ================= --}}
        <div class="w-full lg:w-7/12 flex flex-col justify-center px-8 sm:px-16 xl:px-28 relative transition-colors duration-300">

            <div class="absolute top-8 left-8 sm:left-16 xl:left-28 right-8 sm:right-16 xl:right-28 flex items-center justify-between">
                <a href="{{ route('home') }}" class="text-sm font-semibold text-steel hover:text-ink flex items-center gap-2 transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                    Kembali
                </a>

                {{-- Tombol ganti tema --}}
                <button type="button" onclick="toggleTheme()" aria-label="Ganti tema terang atau gelap"
                        class="p-2.5 rounded border border-line text-steel hover:text-ink hover:border-ink transition-colors bg-surface/80 backdrop-blur-sm">
                    <svg class="w-5 h-5 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
                    </svg>
                    <svg class="w-5 h-5 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
                    </svg>
                </button>
            </div>

            <div class="max-w-md w-full mx-auto mt-16 lg:mt-0">

                <div class="flex lg:hidden items-end gap-3 mb-10">
                    <span class="folder-tab bg-manila text-on-manila font-display font-extrabold text-sm pl-3 pr-6 py-1.5">RRI</span>
                    <span class="font-display font-extrabold text-xl tracking-tight leading-none">ARSIP<span class="text-steel">ARIS</span></span>
                </div>

                <div class="mb-9 border-t-2 border-ink pt-6">
                    <h2 class="font-display font-extrabold text-3xl mb-2 tracking-tight">Selamat Datang</h2>
                    <p class="text-steel text-sm leading-relaxed">Silakan masukkan Email / Username dan kata sandi Anda untuk mengakses sistem arsip.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded bg-surface border-l-4 border-red-600 dark:border-red-500 flex items-start gap-3 text-red-700 dark:text-red-300">
                        <svg class="w-5 h-5 mt-0.5 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/></svg>
                        <ul class="text-sm font-medium">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('login.process') }}" class="space-y-6">
                    @csrf

                    <div>
                        <label for="login" class="block mb-2 text-sm font-semibold">Email / Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-steel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.25a7.5 7.5 0 0115 0"/></svg>
                            </div>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus placeholder="nama@rribatam.go.id atau username"
                                class="bg-surface border border-line text-ink placeholder:text-steel/70 text-sm rounded focus:ring-2 focus:ring-manila focus:border-ink block w-full pl-11 p-3.5 outline-none transition-colors" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold underline underline-offset-4 decoration-line hover:decoration-ink transition-colors">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-steel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 10-9 0v3M5.25 10.5h13.5v9.75H5.25z"/></svg>
                            </div>
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                class="bg-surface border border-line text-ink placeholder:text-steel/70 text-sm rounded focus:ring-2 focus:ring-manila focus:border-ink block w-full pl-11 pr-11 p-3.5 outline-none transition-colors" />

                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-steel hover:text-ink transition-colors" id="togglePassword">
                                <svg id="eyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="eyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0112 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 01-3.2 3.7M6.6 7.6A16.6 16.6 0 002.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 accent-ink border-line rounded cursor-pointer">
                        <label for="remember" class="ml-2 text-sm font-medium text-steel cursor-pointer">Ingat sesi saya</label>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded text-sm font-semibold text-on-manila bg-manila hover:brightness-95 transition">
                        Masuk ke Sistem
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6a2.25 2.25 0 00-2.25 2.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l-3 3m0 0l3 3m-3-3h12.75"/></svg>
                    </button>

                </form>
            </div>

            <div class="absolute bottom-8 left-0 w-full text-center lg:text-left px-8 sm:px-16 xl:px-28">
                <p class="text-xs font-medium text-steel">
                    &copy; {{ date('Y') }} RRI Stasiun Batam. Sistem Arsip ARIS.
                </p>
            </div>

        </div>
    </div>

    <script>
        // Ganti tema
        function toggleTheme() {
            var root = document.documentElement;
            var isDark = root.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Toggle tampil / sembunyi password
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeOpen = document.querySelector('#eyeOpen');
        const eyeClosed = document.querySelector('#eyeClosed');

        togglePassword.addEventListener('click', function () {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            eyeOpen.classList.toggle('hidden');
            eyeClosed.classList.toggle('hidden');
        });

        // Token warna SweetAlert2 sesuai tema aktif
        function swalTheme() {
            const dark = document.documentElement.classList.contains('dark');
            return {
                background: dark ? '#12273A' : '#FFFFFF',
                color:      dark ? '#E9F1F6' : '#10283A'
            };
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

    {{-- Notifikasi flash message via SweetAlert2 (mis. pesan setelah logout) --}}
    @if (session('success'))
        <script>
            Swal.fire(Object.assign({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            }, swalTheme()));
        </script>
    @endif

    @if (session('info'))
        <script>
            Swal.fire(Object.assign({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: @json(session('info')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            }, swalTheme()));
        </script>
    @endif

</body>
</html>