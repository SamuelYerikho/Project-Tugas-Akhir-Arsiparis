<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" x-data="{ sidebarOpen: window.matchMedia('(min-width: 1024px)').matches }">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('pageTitle', 'Dashboard') | Sistem Arsip ARIS - RRI Batam</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Poppins', 'sans-serif']
                    },
                    colors: {
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
        [x-cloak] {
            display: none !important;
        }
    </style>

    <script defer src="https://cdnjs.cloudflare.com/ajax/libs/alpinejs/3.13.5/cdn.min.js"></script>

    {{-- SweetAlert2: untuk notifikasi flash message (login, create, update, delete, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    @stack('styles')
</head>

<body class="font-sans antialiased bg-slate-50 text-slate-900">

    {{-- Sidebar --}}
    @include('layouts.partials.sidebar')

    {{-- Overlay mobile saat sidebar terbuka --}}
    <div x-show="sidebarOpen" x-cloak @click="sidebarOpen = false" x-transition.opacity
        class="fixed inset-0 z-30 bg-slate-900/50 lg:hidden"></div>

    {{-- Area konten utama --}}
    <div class="flex flex-col min-h-screen transition-[padding] duration-300"
        :class="sidebarOpen ? 'lg:pl-64' : 'lg:pl-0'">

        @include('layouts.partials.navbar')

        <main class="flex-1 px-4 sm:px-6 lg:px-8 py-8">
            @yield('content')
        </main>

        @include('layouts.partials.footer')

    </div>

    @stack('scripts')

    {{-- Notifikasi flash message via SweetAlert2 --}}
    <script>
        // Toast: dipakai untuk notifikasi ringan (success, info) yang tidak
        // perlu menghentikan aktivitas user — muncul di pojok, hilang sendiri,
        // tidak perlu klik apa pun.
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

    {{-- Modal: dipakai untuk error/warning yang penting dan wajib disadari user --}}
    @if (session('error'))
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Gagal',
                text: @json(session('error')),
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Mengerti',
            });
        </script>
    @endif

    @if (session('warning'))
        <script>
            Swal.fire({
                icon: 'warning',
                title: 'Perhatian',
                text: @json(session('warning')),
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Mengerti',
            });
        </script>
    @endif

    {{-- Validation error dari form (misal login gagal) --}}
    @if ($errors->any())
        <script>
            Swal.fire({
                icon: 'error',
                title: 'Periksa Kembali',
                html: @json(implode('<br>', $errors->all())),
                confirmButtonColor: '#2563eb',
                confirmButtonText: 'Mengerti',
            });
        </script>
    @endif

</body>

</html>