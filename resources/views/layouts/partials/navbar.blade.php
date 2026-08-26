{{--
    Komponen: Navbar (topbar) - versi sederhana
    Pakai variabel opsional $pageTitle dari view yang me-render layout ini,
    contoh: @section('pageTitle', 'Dashboard')
--}}
<header
    x-data="{ scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 4)"
    class="sticky top-0 z-30 h-20 bg-white/90 backdrop-blur-md border-b border-slate-100 flex items-center px-4 sm:px-6 lg:px-8 gap-4 transition-shadow duration-200"
    :class="scrolled ? 'shadow-sm shadow-slate-200/60' : ''">

    {{-- Tombol buka/tutup sidebar (semua ukuran layar) --}}
    <button @click="sidebarOpen = !sidebarOpen"
        class="w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-colors shrink-0"
        aria-label="Buka/tutup menu">
        <i class="fas fa-bars text-lg"></i>
    </button>

    {{-- Judul halaman --}}
    <div class="min-w-0">
        <h1 class="text-lg sm:text-xl font-extrabold text-slate-900 truncate">
            @yield('pageTitle', 'Dashboard')
        </h1>
        <p class="hidden sm:block text-xs text-slate-400 font-medium">
            Sistem Arsip ARIS &mdash; RRI Stasiun Batam
        </p>
    </div>

    <div class="flex-1"></div>

    {{-- Aksi kanan --}}
    <div class="flex items-center gap-2 sm:gap-3">

        {{-- Tombol notifikasi --}}
        <button
            class="relative w-10 h-10 flex items-center justify-center rounded-xl text-slate-500 hover:bg-blue-50 hover:text-blue-600 transition-colors shrink-0"
            aria-label="Notifikasi">
            <i class="fas fa-bell text-base"></i>
            {{-- Badge, tampilkan hanya kalau ada notifikasi belum dibaca --}}
            {{-- <span class="absolute top-2 right-2 w-2 h-2 rounded-full bg-blue-600 ring-2 ring-white"></span> --}}
        </button>

        <div class="w-px h-8 bg-slate-100 hidden sm:block"></div>

        {{-- Dropdown user --}}
        <div x-data="{ userMenu: false }" class="relative">
            <button @click="userMenu = !userMenu" @click.outside="userMenu = false"
                class="flex items-center gap-2.5 pl-1 pr-2 sm:pr-3 py-1.5 rounded-xl hover:bg-blue-50 transition-colors">
                <div
                    class="w-9 h-9 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm shrink-0 overflow-hidden ring-2 ring-white shadow-sm shadow-blue-200">
                    @if (auth()->user()->profile)
                        <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Avatar"
                            class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <span class="hidden sm:block text-sm font-bold text-slate-700 max-w-[120px] truncate">
                    {{ auth()->user()->name ?? 'Pengguna' }}
                </span>
                <i class="fas fa-chevron-down text-xs text-slate-400 hidden sm:block transition-transform duration-200"
                    :class="userMenu ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="userMenu" x-transition.origin.top.right x-cloak
                class="absolute right-0 mt-2 w-60 bg-white rounded-2xl shadow-xl shadow-slate-200/60 border border-slate-100 py-2 z-40">

                <div class="flex items-center gap-3 px-4 py-3 border-b border-slate-100">
                    <div
                        class="w-10 h-10 rounded-full bg-gradient-to-br from-blue-500 to-blue-700 flex items-center justify-center text-white font-bold text-sm shrink-0 overflow-hidden">
                        @if (auth()->user()->profile)
                            <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Avatar"
                                class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div class="min-w-0">
                        <p class="text-sm font-bold text-slate-900 truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ auth()->user()->email ?? '-' }}</p>
                    </div>
                </div>

                {{-- <a href="{{ route('profile.edit') }}"
                    class="flex items-center gap-2.5 px-4 py-2.5 mt-1 text-sm font-medium text-slate-600 hover:bg-blue-50 hover:text-blue-700 transition-colors">
                    <i class="fas fa-user w-4 text-center"></i> Profil Saya
                </a> --}}

                <div class="my-1 border-t border-slate-100"></div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2.5 px-4 py-2.5 text-sm font-bold text-red-600 hover:bg-red-50 transition-colors">
                        <i class="fas fa-arrow-right-from-bracket w-4 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>