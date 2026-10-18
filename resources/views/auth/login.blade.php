<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | Sistem Arsip ARIS - RRI Batam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Bricolage+Grotesque:opsz,wght@12..96,500;12..96,700;12..96,800&family=Public+Sans:wght@400;500;600&display=swap" rel="stylesheet">

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
                        line: '#C9D4D9'
                    }
                }
            }
        }
    </script>

    <style>
        .folder-tab { clip-path: polygon(0 0, 78% 0, 88% 100%, 0 100%); }
        a:focus-visible, button:focus-visible { outline: 3px solid #E2B84B; outline-offset: 3px; }
    </style>

    {{-- SweetAlert2: untuk notifikasi flash message (logout, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans antialiased text-ink bg-paper">

    <div class="flex min-h-screen">

        {{-- ================= KIRI: Branding (selaras dengan home) ================= --}}
        <div class="hidden lg:flex lg:w-5/12 relative bg-ink text-white flex-col justify-between overflow-hidden px-14 xl:px-20 py-14">

            <div class="flex items-end gap-3">
                <span class="folder-tab bg-manila text-ink font-display font-extrabold text-base pl-4 pr-8 py-2">RRI</span>
            </div>

            <div>
                <h1 class="font-display font-extrabold text-4xl xl:text-5xl tracking-tight leading-[1.05] mb-5">
                    Sistem Arsip ARIS<br>RRI Stasiun Batam
                </h1>
                <p class="text-white/65 text-base max-w-sm leading-relaxed">
                    Kelola, telusuri, dan amankan seluruh dokumen kedinasan RRI Batam dalam satu platform arsip digital terintegrasi.
                </p>

                {{-- Ilustrasi tumpukan map arsip (tanpa teks) --}}
                <div class="relative mt-12 h-40 max-w-sm" aria-hidden="true">
                    <div class="absolute bottom-0 left-0 w-full h-28 bg-white/10 rounded-sm"></div>
                    <div class="absolute bottom-0 left-0 h-6 w-24 bg-white/10 folder-tab -translate-y-[6.5rem]"></div>
                    <div class="absolute bottom-0 left-6 w-[calc(100%-1.5rem)] h-24 bg-steel/60 rounded-sm"></div>
                    <div class="absolute bottom-0 left-6 h-6 w-24 bg-steel/60 folder-tab -translate-y-[5.5rem] translate-x-28"></div>
                    <div class="absolute bottom-0 left-12 w-[calc(100%-3rem)] h-20 bg-manila rounded-sm"></div>
                    <div class="absolute bottom-0 left-12 h-6 w-24 bg-manila folder-tab -translate-y-[4.5rem] translate-x-56"></div>
                </div>

                <div class="mt-10 inline-flex items-center gap-3 text-sm font-semibold text-white border-l-4 border-manila pl-3">
                    Radio Republik Indonesia - Stasiun Batam
                </div>
            </div>

        </div>

        {{-- ================= KANAN: Form Login ================= --}}
        <div class="w-full lg:w-7/12 flex flex-col justify-center px-8 sm:px-16 xl:px-28 relative">

            <a href="{{ route('home') }}" class="absolute top-8 left-8 sm:left-16 xl:left-28 text-sm font-semibold text-steel hover:text-ink flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>

            <div class="max-w-md w-full mx-auto mt-16 lg:mt-0">

                <div class="flex lg:hidden items-end gap-3 mb-10">
                    <span class="folder-tab bg-manila text-ink font-display font-extrabold text-sm pl-3 pr-6 py-1.5">RRI</span>
                    <span class="font-display font-extrabold text-xl tracking-tight leading-none">ARSIP<span class="text-steel">ARIS</span></span>
                </div>

                <div class="mb-9 border-t-2 border-ink pt-6">
                    <h2 class="font-display font-extrabold text-3xl mb-2 tracking-tight">Selamat Datang</h2>
                    <p class="text-steel text-sm leading-relaxed">Silakan masukkan Email / Username dan kata sandi Anda untuk mengakses sistem arsip.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded bg-white border-l-4 border-red-600 flex items-start gap-3 text-red-700">
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
                                class="bg-white border border-line text-ink text-sm rounded focus:ring-2 focus:ring-manila focus:border-ink block w-full pl-11 p-3.5 outline-none transition-colors" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-semibold">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-semibold underline underline-offset-4 decoration-line hover:decoration-ink">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-steel">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V7.5a4.5 4.5 0 10-9 0v3M5.25 10.5h13.5v9.75H5.25z"/></svg>
                            </div>
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                class="bg-white border border-line text-ink text-sm rounded focus:ring-2 focus:ring-manila focus:border-ink block w-full pl-11 pr-11 p-3.5 outline-none transition-colors" />

                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-steel hover:text-ink" id="togglePassword">
                                <svg id="eyeOpen" class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/><circle cx="12" cy="12" r="3"/></svg>
                                <svg id="eyeClosed" class="w-4 h-4 hidden" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0112 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 01-3.2 3.7M6.6 7.6A16.6 16.6 0 002.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/></svg>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 accent-ink border-line rounded cursor-pointer">
                        <label for="remember" class="ml-2 text-sm font-medium text-steel cursor-pointer">Ingat sesi saya</label>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded text-sm font-semibold text-ink bg-manila hover:brightness-95 transition">
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
    </script>

    {{-- Notifikasi flash message via SweetAlert2 (mis. pesan setelah logout) --}}
    @if (session('success'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: @json(session('success')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

    @if (session('info'))
        <script>
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: @json(session('info')),
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });
        </script>
    @endif

</body>
</html>