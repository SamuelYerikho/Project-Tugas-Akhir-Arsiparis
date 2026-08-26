<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Masuk | Sistem Arsip ARIS - RRI Batam</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: { sans: ['Poppins', 'sans-serif'] },
                    colors: {
                        aris: { 50: '#eff6ff', 100: '#dbeafe', 500: '#3b82f6', 600: '#2563eb', 700: '#1d4ed8', 900: '#1e3a8a' }
                    }
                }
            }
        }
    </script>

    {{-- SweetAlert2: untuk notifikasi flash message (logout, dll) --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>
<body class="font-sans antialiased text-slate-900 bg-white">

    <div class="flex min-h-screen">

        {{-- ================= KIRI: Branding (selaras dengan home) ================= --}}
        <div class="hidden lg:flex lg:w-1/2 relative bg-slate-50 border-r border-slate-100 items-center justify-center overflow-hidden">

            <!-- lingkaran dekoratif, sama seperti hero home -->
            <svg class="absolute" width="600" height="600" viewBox="0 0 600 600">
                <circle cx="300" cy="300" r="220" fill="none" stroke="#dbeafe" stroke-width="2"/>
                <circle cx="300" cy="300" r="170" fill="none" stroke="#dbeafe" stroke-width="2"/>
            </svg>

            <div class="relative z-10 px-16 xl:px-24 text-center">

                <div class="w-16 h-16 bg-slate-900 rounded-2xl flex items-center justify-center text-white font-black text-2xl shadow-lg mx-auto mb-10">
                    RRI
                </div>

                <div class="relative w-full max-w-[220px] mx-auto mb-10 flex items-center justify-center">
                    <div class="w-40 h-40 bg-slate-900 rounded-3xl shadow-xl flex flex-col items-center justify-center gap-3 text-white">
                        <i class="fas fa-folder-open text-5xl text-aris-500"></i>
                        <span class="font-bold text-xs tracking-widest uppercase">Arsip Digital</span>
                    </div>
                </div>

                <h1 class="text-3xl xl:text-4xl font-extrabold tracking-tight mb-4 leading-tight text-slate-900">
                    Sistem Arsip ARIS<br>RRI Stasiun Batam
                </h1>
                <p class="text-slate-500 text-base max-w-sm mx-auto leading-relaxed">
                    Kelola, telusuri, dan amankan seluruh dokumen kedinasan RRI Batam dalam satu platform arsip digital terintegrasi.
                </p>

                <div class="mt-10 inline-flex items-center gap-2 text-sm font-bold text-slate-700 bg-white w-fit px-5 py-3 rounded-xl border border-slate-200">
                    <i class="fas fa-broadcast-tower text-aris-600"></i>
                    Radio Republik Indonesia - Stasiun Batam
                </div>
            </div>
        </div>

        {{-- ================= KANAN: Form Login ================= --}}
        <div class="w-full lg:w-1/2 flex flex-col justify-center px-8 sm:px-16 xl:px-24 bg-white relative">

            <a href="{{ route('home') }}" class="absolute top-8 left-8 sm:left-16 xl:left-24 text-sm font-bold text-slate-400 hover:text-slate-900 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
                Kembali
            </a>

            <div class="max-w-md w-full mx-auto mt-12 lg:mt-0">

                <div class="flex lg:hidden items-center gap-2.5 mb-10">
                    <div class="w-9 h-9 bg-slate-900 rounded-lg flex items-center justify-center text-white font-black text-sm">RRI</div>
                    <span class="font-extrabold text-xl tracking-tight text-slate-900">ARSIP<span class="text-aris-600">ARIS</span></span>
                </div>

                <div class="mb-10">
                    <h2 class="text-3xl font-extrabold text-slate-900 mb-2 tracking-tight">Selamat Datang</h2>
                    <p class="text-slate-500 text-sm">Silakan masukkan Email / Username dan kata sandi Anda untuk mengakses sistem arsip.</p>
                </div>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-xl bg-red-50 border border-red-100 flex items-start gap-3 text-red-600">
                        <i class="fas fa-exclamation-circle mt-0.5"></i>
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
                        <label for="login" class="block mb-2 text-sm font-bold text-slate-700">Email / Username</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-user"></i>
                            </div>
                            <input id="login" type="text" name="login" value="{{ old('login') }}" required autofocus placeholder="nama@rribatam.go.id atau username"
                                class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 block w-full pl-11 p-3.5 outline-none transition-all" />
                        </div>
                    </div>

                    <div>
                        <div class="flex items-center justify-between mb-2">
                            <label for="password" class="block text-sm font-bold text-slate-700">Kata Sandi</label>
                            @if (Route::has('password.request'))
                                <a href="{{ route('password.request') }}" class="text-xs font-bold text-aris-600 hover:text-aris-700 transition-colors">
                                    Lupa sandi?
                                </a>
                            @endif
                        </div>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none text-slate-400">
                                <i class="fas fa-lock"></i>
                            </div>
                            <input id="password" type="password" name="password" required placeholder="••••••••"
                                class="bg-slate-50 border border-slate-200 text-slate-900 text-sm rounded-xl focus:ring-2 focus:ring-slate-900/20 focus:border-slate-900 block w-full pl-11 pr-11 p-3.5 outline-none transition-all" />

                            <div class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer text-slate-400 hover:text-slate-900 transition-colors" id="togglePassword">
                                <i class="fas fa-eye" id="eyeIcon"></i>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center">
                        <input id="remember" type="checkbox" name="remember" class="w-4 h-4 text-slate-900 bg-slate-50 border-slate-300 rounded focus:ring-slate-900 focus:ring-2 cursor-pointer">
                        <label for="remember" class="ml-2 text-sm font-medium text-slate-600 cursor-pointer">Ingat sesi saya</label>
                    </div>

                    <button type="submit" class="w-full flex justify-center items-center gap-2 py-3.5 px-4 rounded-xl text-sm font-bold text-white bg-slate-900 hover:bg-slate-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-slate-900 transition-colors">
                        Masuk ke Sistem <i class="fas fa-sign-in-alt"></i>
                    </button>

                </form>
            </div>

            <div class="absolute bottom-8 left-0 w-full text-center lg:text-left lg:px-16 xl:px-24">
                <p class="text-xs font-semibold text-slate-400">
                    &copy; {{ date('Y') }} RRI Stasiun Batam. Sistem Arsip ARIS.
                </p>
            </div>

        </div>
    </div>

    <script>
        const togglePassword = document.querySelector('#togglePassword');
        const password = document.querySelector('#password');
        const eyeIcon = document.querySelector('#eyeIcon');

        togglePassword.addEventListener('click', function (e) {
            const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
            password.setAttribute('type', type);

            eyeIcon.classList.toggle('fa-eye');
            eyeIcon.classList.toggle('fa-eye-slash');
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