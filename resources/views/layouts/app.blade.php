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
                        /* palet lama dipertahankan agar halaman isi yang masih memakai kelas aris-* tidak rusak */
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

        /* ============ BINTANG BERKELAP-KELIP (MODE GELAP) ============ */
        /* Lapisan bintang fixed di belakang konten. Hanya tampil di mode gelap.
           Tiga lapisan dengan offset & durasi animasi berbeda menghasilkan efek twinkle.
           Kartu bg-surface, sidebar, dan navbar yang solid otomatis menutupi bintang. */
        .star-field {
            display: none;
            position: fixed;
            inset: 0;
            z-index: -1;
            pointer-events: none;
            overflow: hidden;
            background-color: rgb(var(--c-paper));
            background-image: radial-gradient(1000px 400px at 50% 0%, rgb(var(--c-invert) / 0.45), transparent 70%);
            background-repeat: no-repeat;
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
            .star-layer { animation: none; opacity: 0.55; }
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

    {{-- ================= BINTANG BERKELAP-KELIP (MODE GELAP) ================= --}}
    {{-- Fixed di belakang semua konten; sidebar, navbar, dan kartu solid menutupinya,
         jadi bintang hanya terlihat di ruang kosong sekitar konten. --}}
    <div class="star-field" aria-hidden="true">
        <div class="star-layer star-layer-1"></div>
        <div class="star-layer star-layer-2"></div>
        <div class="star-layer star-layer-3"></div>
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
    </script>

    @stack('scripts')

    {{-- Notifikasi flash message via SweetAlert2 --}}
    <script>
        // Toast: notifikasi ringan (success, info) yang muncul di pojok dan hilang sendiri.
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

    {{-- Modal: error/warning penting --}}
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

    {{-- Validation error dari form --}}
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