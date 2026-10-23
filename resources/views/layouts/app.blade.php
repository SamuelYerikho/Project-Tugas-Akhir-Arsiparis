<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: window.matchMedia('(min-width: 1024px)').matches }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('pageTitle', 'Beranda') | Sistem Arsip ARIS - RRI Batam</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

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
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome tetap dimuat karena halaman-halaman isi (arsip, pengguna, dll) masih memakai ikonnya --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

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
                        'on-invert': 'rgb(var(--c-on-invert) / <alpha-value>)',
                        aris: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            900: '#1e3a8a'
                        }
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

        [x-cloak] { display: none !important; }
        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        .sticker-shadow-sm { box-shadow: 4px 4px 0 rgb(var(--c-shadow)); }
        a:focus-visible, button:focus-visible { outline: 3px solid #E2B84B; outline-offset: 2px; }

        /* ============ LATAR ASTROFIL 3D — TATA SURYA (MODE GELAP) ============ */
        /* Panel, sidebar, navbar, dan kartu solid menutupi sebagian besar layar,
           jadi tata surya hanya terlihat di ruang kosong sekitar konten dan di
           pojok kanan bawah (tempat ia "mengintip" dari balik layout). */
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

        /* ============ TATA SURYA ============ */
        /* Seluruh sistem ditempatkan di pojok kanan bawah dengan offset negatif,
           sehingga hanya sebagian yang "mengintip" ke dalam viewport — seperti
           tata surya yang lebih besar dari layar. */
        .solar-system {
            position: absolute;
            width: 660px;
            height: 660px;
            bottom: -200px;
            right: -200px;
            will-change: transform;
            transform: translate(var(--px, 0px), var(--py, 0px));
        }

        /* ---- MATAHARI ---- */
        .sun {
            position: absolute;
            top: 50%; left: 50%;
            width: 80px; height: 80px;
            margin: -40px 0 0 -40px;
            border-radius: 50%;
            background: radial-gradient(circle at 42% 38%,
                #fffde7 0%,
                #fff59d 12%,
                #ffd54f 32%,
                #ff9800 58%,
                #e65100 82%,
                #4e1200 100%);
            box-shadow:
                0 0 30px rgba(255, 213, 79, 0.85),
                0 0 70px rgba(255, 152, 0, 0.50),
                0 0 140px rgba(255, 87, 34, 0.25),
                0 0 240px rgba(255, 87, 34, 0.10);
            opacity: 0.88;
        }
        .sun::before {
            content: '';
            position: absolute;
            inset: -40px;
            border-radius: 50%;
            background: radial-gradient(circle, rgba(255, 183, 77, 0.35), transparent 65%);
            animation: sun-pulse 6s ease-in-out infinite;
            pointer-events: none;
        }

        @keyframes sun-pulse {
            0%, 100% { transform: scale(1);    opacity: 0.55; }
            50%      { transform: scale(1.15); opacity: 0.85; }
        }

        /* ---- ORBIT PATH (dashed lingkaran) ---- */
        .orbit-path {
            position: absolute;
            top: 50%; left: 50%;
            border-radius: 50%;
            border: 1px dashed rgba(226, 184, 75, 0.14);
            transform: translate(-50%, -50%);
            pointer-events: none;
        }
        .orbit-path-1 { width: 200px; height: 200px; }
        .orbit-path-2 { width: 360px; height: 360px; }
        .orbit-path-3 { width: 540px; height: 540px; }
        .orbit-path-4 { width: 680px; height: 680px; }

        /* ---- SPINNER (lengan orbit berputar) ---- */
        .orbit-spinner {
            position: absolute;
            top: 50%; left: 50%;
            border-radius: 50%;
            animation: orbit-rotate linear infinite;
            transform-origin: center center;
        }
        .orbit-spinner-1 {
            width: 200px; height: 200px;
            margin: -100px 0 0 -100px;
            animation-duration: 22s;
        }
        .orbit-spinner-2 {
            width: 360px; height: 360px;
            margin: -180px 0 0 -180px;
            animation-duration: 40s;
        }
        .orbit-spinner-3 {
            width: 540px; height: 540px;
            margin: -270px 0 0 -270px;
            animation-duration: 62s;
        }
        .orbit-spinner-4 {
            width: 680px; height: 680px;
            margin: -340px 0 0 -340px;
            animation-duration: 90s;
        }

        @keyframes orbit-rotate {
            from { transform: rotate(0deg); }
            to   { transform: rotate(360deg); }
        }

        /* ---- PLANET (diposisikan di 12 o'clock, counter-rotate agar tetap tegak) ---- */
        .orbit-planet {
            position: absolute;
            top: -10px;
            left: 50%;
            margin-left: -10px;
            border-radius: 50%;
            animation: orbit-counter linear infinite;
            transform-origin: center center;
            will-change: transform;
        }

        .orbit-spinner-1 .orbit-planet { animation-duration: 22s; }
        .orbit-spinner-2 .orbit-planet { animation-duration: 40s; }
        .orbit-spinner-3 .orbit-planet { animation-duration: 62s; }
        .orbit-spinner-4 .orbit-planet { animation-duration: 90s; }

        @keyframes orbit-counter {
            from { transform: rotate(0deg); }
            to   { transform: rotate(-360deg); }
        }

        /* Planet Merkurius — kecil, batu */
        .planet-mercury {
            width: 14px; height: 14px;
            margin-left: -7px;
            background: radial-gradient(circle at 35% 30%,
                #c8bfb8 0%, #8a807a 45%, #4a423d 80%, #1e1815 100%);
            box-shadow:
                inset -3px -3px 5px rgba(0,0,0,0.75),
                inset 1px 1px 2px rgba(255,255,255,0.15),
                0 0 10px rgba(200, 191, 184, 0.35);
            opacity: 0.85;
        }

        /* Planet Bumi — biru dengan bulan kecil mengorbit */
        .planet-earth {
            width: 18px; height: 18px;
            margin-left: -9px;
            background: radial-gradient(circle at 35% 30%,
                #9fcde8 0%, #4a7fb5 35%, #1e4a72 70%, #071a2c 100%);
            box-shadow:
                inset -4px -4px 7px rgba(0,0,0,0.75),
                inset 2px 2px 4px rgba(160, 210, 255, 0.20),
                0 0 16px rgba(74, 127, 181, 0.50);
            opacity: 0.90;
        }
        .planet-earth::after {
            content: '';
            position: absolute;
            top: 50%; left: 50%;
            width: 4px; height: 4px;
            margin: -2px;
            border-radius: 50%;
            background: radial-gradient(circle at 30% 30%, #e8e8e8, #7a7a7a 80%);
            box-shadow: 0 0 4px rgba(220, 220, 220, 0.55);
            animation: moon-orbit 4s linear infinite;
        }

        @keyframes moon-orbit {
            from { transform: rotate(0deg) translateX(15px) rotate(0deg); }
            to   { transform: rotate(360deg) translateX(15px) rotate(-360deg); }
        }

        /* Gas giant bercincin */
        .planet-gas {
            width: 26px; height: 26px;
            margin-left: -13px;
            background: radial-gradient(circle at 35% 30%,
                #f5d9a8 0%, #d99a5e 22%, #a15a2a 55%, #5c2a10 85%, #1f0a02 100%);
            box-shadow:
                inset -5px -5px 10px rgba(0,0,0,0.75),
                inset 2px 2px 5px rgba(255, 230, 200, 0.20),
                0 0 22px rgba(217, 154, 94, 0.50);
            opacity: 0.90;
        }
        .planet-gas::after {
            content: '';
            position: absolute;
            inset: -8px -14px;
            border-radius: 50%;
            border: 1.5px solid rgba(217, 154, 94, 0.55);
            border-bottom-color: rgba(217, 154, 94, 0.15);
            transform: rotateX(74deg) rotate(-14deg);
            pointer-events: none;
        }

        /* Planet es — biru pucat */
        .planet-ice {
            width: 20px; height: 20px;
            margin-left: -10px;
            background: radial-gradient(circle at 35% 30%,
                #c9f0ff 0%, #4fc3f7 30%, #0277bd 65%, #002c4a 100%);
            box-shadow:
                inset -4px -4px 8px rgba(0,0,0,0.75),
                inset 2px 2px 4px rgba(200, 240, 255, 0.20),
                0 0 18px rgba(79, 195, 247, 0.50);
            opacity: 0.90;
        }

        /* ============ PLANET TERSERAK (foreground — nuansa langit penuh benda) ============ */
        .scattered-planet {
            position: absolute;
            border-radius: 50%;
            will-change: transform;
        }
        .sp-1 {
            width: 34px; height: 34px;
            top: 18%; left: 12%;
            background: radial-gradient(circle at 32% 28%,
                #b39ddb 0%, #6a4fa3 55%, #2f2159 100%);
            box-shadow:
                inset -5px -5px 10px rgba(0,0,0,0.70),
                inset 2px 2px 4px rgba(200, 180, 240, 0.20),
                0 0 20px rgba(179, 157, 219, 0.35);
            opacity: 0.55;
            animation: drift-d 80s ease-in-out infinite;
        }
        .sp-2 {
            width: 26px; height: 26px;
            top: 42%; right: 18%;
            background: radial-gradient(circle at 35% 30%,
                #80cbc4 0%, #3d8a85 55%, #1c4a47 100%);
            box-shadow:
                inset -4px -4px 8px rgba(0,0,0,0.70),
                inset 1px 1px 3px rgba(160, 230, 220, 0.20),
                0 0 16px rgba(128, 203, 196, 0.35);
            opacity: 0.55;
            animation: drift-e 65s ease-in-out infinite;
        }
        .sp-3 {
            width: 22px; height: 22px;
            top: 68%; left: 8%;
            background: radial-gradient(circle at 32% 30%,
                #e57373 0%, #a63a3a 55%, #4d1414 100%);
            box-shadow:
                inset -4px -4px 8px rgba(0,0,0,0.70),
                inset 1px 1px 3px rgba(240, 160, 160, 0.20),
                0 0 16px rgba(229, 115, 115, 0.35);
            opacity: 0.55;
            animation: drift-f 75s ease-in-out infinite;
        }

        /* ============ ASTEROID ============ */
        .asteroid {
            position: absolute;
            background: radial-gradient(circle at 35% 28%,
                #525d6b 0%, #2c3440 55%, #171c24 100%);
            box-shadow:
                inset -2px -3px 5px rgba(0, 0, 0, 0.60),
                inset  1px  1px 2px rgba(160, 175, 190, 0.15),
                0 0 6px rgba(0, 0, 0, 0.40);
            opacity: 0.55;
            will-change: transform;
        }
        .asteroid-1 {
            width: 14px; height: 12px;
            top: 12%; right: 8%;
            border-radius: 42% 58% 45% 55% / 50% 48% 52% 50%;
            animation: ast-1 55s ease-in-out infinite;
        }
        .asteroid-2 {
            width: 10px; height: 9px;
            top: 55%; left: 4%;
            border-radius: 55% 45% 52% 48% / 48% 52% 50% 50%;
            animation: ast-2 70s ease-in-out infinite;
        }
        .asteroid-3 {
            width: 16px; height: 14px;
            bottom: 22%; right: 6%;
            border-radius: 48% 52% 42% 58% / 55% 45% 55% 45%;
            animation: ast-3 85s ease-in-out infinite;
        }
        .asteroid-4 {
            width: 12px; height: 10px;
            bottom: 8%; left: 22%;
            border-radius: 45% 55% 50% 50% / 50% 50% 50% 50%;
            animation: ast-4 60s ease-in-out infinite;
        }
        .asteroid-5 {
            width: 8px; height: 8px;
            top: 30%; right: 42%;
            border-radius: 55% 45% 48% 52% / 50% 50% 50% 50%;
            animation: ast-5 45s ease-in-out infinite;
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

        /* ============ GERAKAN UMUM (dengan slot parallax --px/--py) ============ */
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
        @keyframes drift-d {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)); }
            50%      { transform: translate(calc(var(--px, 0px) + 8px),  calc(var(--py, 0px) + 8px)); }
        }
        @keyframes drift-e {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)); }
            50%      { transform: translate(calc(var(--px, 0px) - 8px),  calc(var(--py, 0px) - 6px)); }
        }
        @keyframes drift-f {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)); }
            50%      { transform: translate(calc(var(--px, 0px) + 6px),  calc(var(--py, 0px) - 8px)); }
        }

        @keyframes ast-1 {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)) rotate(0deg); }
            50%      { transform: translate(calc(var(--px, 0px) + 18px), calc(var(--py, 0px) - 14px)) rotate(140deg); }
        }
        @keyframes ast-2 {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)) rotate(0deg); }
            50%      { transform: translate(calc(var(--px, 0px) - 14px), calc(var(--py, 0px) + 12px)) rotate(-120deg); }
        }
        @keyframes ast-3 {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)) rotate(0deg); }
            50%      { transform: translate(calc(var(--px, 0px) - 20px), calc(var(--py, 0px) + 16px)) rotate(180deg); }
        }
        @keyframes ast-4 {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)) rotate(0deg); }
            50%      { transform: translate(calc(var(--px, 0px) + 16px), calc(var(--py, 0px) + 14px)) rotate(-160deg); }
        }
        @keyframes ast-5 {
            0%, 100% { transform: translate(var(--px, 0px), var(--py, 0px)) rotate(0deg); }
            50%      { transform: translate(calc(var(--px, 0px) + 10px), calc(var(--py, 0px) - 10px)) rotate(200deg); }
        }

        /* ============ RESPONSIVE ============ */
        @media (max-width: 1024px) {
            .solar-system {
                width: 500px; height: 500px;
                bottom: -160px; right: -160px;
            }
            .orbit-path-1 { width: 150px; height: 150px; }
            .orbit-path-2 { width: 270px; height: 270px; }
            .orbit-path-3 { width: 410px; height: 410px; }
            .orbit-path-4 { width: 510px; height: 510px; }

            .orbit-spinner-1 { width: 150px; height: 150px; margin: -75px 0 0 -75px; }
            .orbit-spinner-2 { width: 270px; height: 270px; margin: -135px 0 0 -135px; }
            .orbit-spinner-3 { width: 410px; height: 410px; margin: -205px 0 0 -205px; }
            .orbit-spinner-4 { width: 510px; height: 510px; margin: -255px 0 0 -255px; }
        }

        @media (max-width: 640px) {
            .solar-system {
                width: 380px; height: 380px;
                bottom: -120px; right: -120px;
            }
            .sun { width: 56px; height: 56px; margin: -28px 0 0 -28px; }
            .orbit-path-1 { width: 110px; height: 110px; }
            .orbit-path-2 { width: 200px; height: 200px; }
            .orbit-path-3 { width: 300px; height: 300px; }
            .orbit-path-4 { width: 380px; height: 380px; }

            .orbit-spinner-1 { width: 110px; height: 110px; margin: -55px 0 0 -55px; }
            .orbit-spinner-2 { width: 200px; height: 200px; margin: -100px 0 0 -100px; }
            .orbit-spinner-3 { width: 300px; height: 300px; margin: -150px 0 0 -150px; }
            .orbit-spinner-4 { width: 380px; height: 380px; margin: -190px 0 0 -190px; }

            .scattered-planet { opacity: 0.38; }
            .asteroid { opacity: 0.40; }
            .shooting-star { width: 110px; }
            .sp-1 { width: 24px; height: 24px; }
            .sp-2 { width: 20px; height: 20px; }
            .sp-3 { width: 18px; height: 18px; }
        }

        @media (prefers-reduced-motion: reduce) {
            .shooting-star { animation: none; opacity: 0; }
            .planet, .asteroid, .scattered-planet,
            .nebula, .orbit-spinner, .orbit-planet, .solar-system,
            .sun::before, .planet-earth::after { animation: none; }
        }
    </style>

    {{-- Echo/Reverb untuk notifikasi realtime (hanya JS, CSS tetap dari Tailwind CDN). --}}
    @auth
        @vite('resources/js/app.js')
    @endauth

    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    {{-- SweetAlert2: untuk notifikasi flash message --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-paper text-ink transition-colors duration-200">

    {{-- ================= LATAR ASTROFIL 3D — TATA SURYA (MODE GELAP) ================= --}}
    <div class="star-field" aria-hidden="true">
        {{-- Nebula (paling belakang) --}}
        <div class="nebula nebula-1"></div>
        <div class="nebula nebula-2"></div>
        <div class="nebula nebula-3"></div>

        {{-- TATA SURYA di pojok kanan bawah --}}
        <div class="solar-system">
            {{-- Matahari di pusat --}}
            <div class="sun"></div>

            {{-- Orbit 1 — Merkurius --}}
            <div class="orbit-path orbit-path-1"></div>
            <div class="orbit-spinner orbit-spinner-1">
                <div class="orbit-planet planet-mercury"></div>
            </div>

            {{-- Orbit 2 — Bumi + bulan --}}
            <div class="orbit-path orbit-path-2"></div>
            <div class="orbit-spinner orbit-spinner-2">
                <div class="orbit-planet planet-earth"></div>
            </div>

            {{-- Orbit 3 — Gas giant bercincin --}}
            <div class="orbit-path orbit-path-3"></div>
            <div class="orbit-spinner orbit-spinner-3">
                <div class="orbit-planet planet-gas"></div>
            </div>

            {{-- Orbit 4 — Planet es --}}
            <div class="orbit-path orbit-path-4"></div>
            <div class="orbit-spinner orbit-spinner-4">
                <div class="orbit-planet planet-ice"></div>
            </div>
        </div>

        {{-- Planet tersebar (foreground) --}}
        <div class="scattered-planet sp-1"></div>
        <div class="scattered-planet sp-2"></div>
        <div class="scattered-planet sp-3"></div>

        {{-- Asteroid --}}
        <div class="asteroid asteroid-1"></div>
        <div class="asteroid asteroid-2"></div>
        <div class="asteroid asteroid-3"></div>
        <div class="asteroid asteroid-4"></div>
        <div class="asteroid asteroid-5"></div>

        {{-- Bintang jatuh --}}
        <div class="shooting-star shoot-1"></div>
        <div class="shooting-star shoot-2"></div>
        <div class="shooting-star shoot-3"></div>
    </div>

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Lapisan gelap di mobile saat sidebar terbuka --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition.opacity
        class="fixed inset-0 z-30 bg-black/60 lg:hidden"></div>

    {{-- Area konten utama --}}
    <div class="flex flex-col min-h-screen transition-[padding] duration-300"
        :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-0'">

        @include('layouts.partials.navbar')

        <main class="flex-1 px-4 sm:px-6 lg:px-10 py-8 transition-colors duration-200">
            @yield('content')
        </main>

        @include('layouts.partials.footer')

    </div>

    <script>
        // Ganti tema terang/gelap
        function toggleTheme() {
            var root = document.documentElement;
            var isDark = root.classList.toggle('dark');
            localStorage.setItem('theme', isDark ? 'dark' : 'light');
        }

        // Sinkron tema antar-tab
        window.addEventListener('storage', (e) => {
            if (e.key === 'theme') {
                document.documentElement.classList.toggle('dark', e.newValue === 'dark');
            }
        });

        // Tema untuk dialog/toast SweetAlert2
        function swalTheme(withConfirm = false) {
            const dark = document.documentElement.classList.contains('dark');
            const base = {
                background: dark ? '#12273A' : '#FFFFFF',
                color:      dark ? '#E9F1F6' : '#10283A',
            };
            if (withConfirm) {
                base.confirmButtonColor = '#E2B84B';
                base.didOpen = () => {
                    const btn = Swal.getConfirmButton();
                    if (btn) btn.style.color = '#10283A';
                };
            }
            return base;
        }

        /* ---- Parallax mouse untuk tata surya 3D ---- */
        (function () {
            var field = document.querySelector('.star-field');
            if (!field) return;
            if (window.matchMedia('(prefers-reduced-motion: reduce)').matches) return;

            var layers = [
                { els: field.querySelectorAll('.nebula'),           depth: 0.4 },
                { els: field.querySelectorAll('.solar-system'),     depth: 0.6 },
                { els: field.querySelectorAll('.scattered-planet'), depth: 1.4 },
                { els: field.querySelectorAll('.asteroid'),         depth: 2.0 }
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

    @stack('scripts')

    {{-- Notifikasi flash message via SweetAlert2 --}}
    <script>
        const arisToast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
    </script>

    @if (session('success'))
        <script>
            arisToast.fire(Object.assign({
                icon: 'success',
                title: @json(session('success')),
            }, swalTheme()));
        </script>
    @endif

    @if (session('info'))
        <script>
            arisToast.fire(Object.assign({
                icon: 'info',
                title: @json(session('info')),
            }, swalTheme()));
        </script>
    @endif

    @if (session('error'))
        <script>
            Swal.fire(Object.assign({
                icon: 'error',
                title: 'Tidak berhasil',
                text: @json(session('error')),
                confirmButtonText: 'Tutup',
            }, swalTheme(true)));
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire(Object.assign({
                icon: 'warning',
                title: 'Mohon diperhatikan',
                text: @json(session('warning')),
                confirmButtonText: 'Tutup',
            }, swalTheme(true)));
        </script>
    @endif

    @if ($errors->any())
        <script>
            Swal.fire(Object.assign({
                icon: 'error',
                title: 'Ada isian yang perlu diperbaiki',
                html: @json(implode('<br>', $errors->all())),
                confirmButtonText: 'Tutup',
            }, swalTheme(true)));
        </script>
    @endif

</body>

</html>