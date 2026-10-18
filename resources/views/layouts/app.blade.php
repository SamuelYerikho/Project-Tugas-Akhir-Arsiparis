<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: window.matchMedia('(min-width: 1024px)').matches }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('pageTitle', 'Beranda') | Sistem Arsip ARIS - RRI Batam</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

    {{-- Font Awesome tetap dimuat karena halaman-halaman isi (arsip, pengguna, dll) masih memakai ikonnya --}}
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        display: ['"Bricolage Grotesque"', 'sans-serif'],
                        sans: ['"Public Sans"', 'sans-serif']
                    },
                    colors: {
                        ink: '#10283A',
                        paper: '#F1F5F6',
                        manila: '#E2B84B',
                        steel: '#5B7083',
                        line: '#C9D4D9',
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
        [x-cloak] { display: none !important; }
        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        a:focus-visible, button:focus-visible { outline: 3px solid #E2B84B; outline-offset: 2px; }
    </style>

    {{-- Echo/Reverb untuk notifikasi realtime (hanya JS, CSS tetap dari Tailwind CDN).
         Harus sebelum Alpine supaya window.Echo sudah siap saat komponen lonceng berjalan. --}}
    @auth
        @vite('resources/js/app.js')
    @endauth

    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    {{-- SweetAlert2: untuk notifikasi flash message (login, create, update, delete, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-paper text-ink">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Lapisan gelap di mobile saat sidebar terbuka --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition.opacity
        class="fixed inset-0 z-30 bg-ink/60 lg:hidden"></div>

    {{-- Area konten utama --}}
    <div class="flex flex-col min-h-screen transition-[padding] duration-300"
        :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-0'">

        @include('layouts.partials.navbar')

        <main class="flex-1 px-4 sm:px-6 lg:px-10 py-8">
            @yield('content')
        </main>

        @include('layouts.partials.footer')

    </div>

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
            arisToast.fire({
                icon: 'success',
                title: @json(session('success')),
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            arisToast.fire({
                icon: 'info',
                title: @json(session('info')),
            });
        </script>
    @endif

    {{-- Modal: untuk error/warning penting yang wajib disadari pengguna --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Tidak berhasil',
                text: @json(session('error')),
                confirmButtonColor: '#10283A',
                confirmButtonText: 'Tutup',
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Mohon diperhatikan',
                text: @json(session('warning')),
                confirmButtonColor: '#10283A',
                confirmButtonText: 'Tutup',
            });
        </script>
    @endif

    {{-- Validation error dari form (misal login gagal) --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Ada isian yang perlu diperbaiki',
                html: @json(implode('<br>', $errors->all())),
                confirmButtonColor: '#10283A',
                confirmButtonText: 'Tutup',
            });
        </script>
    @endif

</body>

</html>