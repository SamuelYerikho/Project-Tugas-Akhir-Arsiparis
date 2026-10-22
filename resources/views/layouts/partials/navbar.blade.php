{{--
    Komponen: Navbar (topbar)
    Judul halaman diambil dari @section('pageTitle', '...') pada view yang memakai layout ini.
--}}
<header
    x-data="{ scrolled: false }"
    x-init="window.addEventListener('scroll', () => scrolled = window.scrollY > 4)"
    class="sticky top-0 z-30 h-20 bg-paper/95 backdrop-blur border-b-2 flex items-center px-4 sm:px-6 lg:px-10 gap-5 transition-colors duration-200"
    :class="scrolled ? 'border-ink' : 'border-line'">

    {{-- Tombol buka/tutup sidebar --}}
    <button @click="sidebarOpen = !sidebarOpen"
        class="w-10 h-10 flex items-center justify-center rounded border border-line bg-surface text-ink hover:border-ink transition-colors shrink-0"
        aria-label="Buka/tutup menu">
        <i class="fas fa-bars"></i>
    </button>

    {{-- Judul halaman --}}
    <div class="min-w-0">
        <h1 class="font-display font-extrabold text-xl sm:text-2xl tracking-tight leading-none truncate">
            @yield('pageTitle', 'Beranda')
        </h1>
        <p class="hidden sm:block mt-1.5 text-xs text-steel font-medium">
            Sistem Arsip ARIS di RRI Stasiun Batam
        </p>
    </div>

    <div class="flex-1"></div>

    {{-- Aksi kanan --}}
    <div class="flex items-center gap-3">

        {{-- Ganti tema terang/gelap --}}
        <button type="button" onclick="toggleTheme()" aria-label="Ganti tema terang atau gelap"
            class="w-10 h-10 flex items-center justify-center rounded border border-line bg-surface text-ink hover:border-ink transition-colors shrink-0">
            <svg class="w-4 h-4 dark:hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z"/>
            </svg>
            <svg class="w-4 h-4 hidden dark:block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z"/>
            </svg>
        </button>

        {{-- Notifikasi (realtime lewat Reverb) --}}
        <div x-data="notifBell()" x-init="mulai()" class="relative">
            <button @click="toggle()" @click.outside="open = false"
                class="relative w-10 h-10 flex items-center justify-center rounded border border-line bg-surface text-ink hover:border-ink transition-colors shrink-0"
                aria-label="Notifikasi">
                <i class="fas fa-bell text-sm"></i>
                <span x-show="unread > 0" x-cloak
                    class="absolute top-1.5 right-1.5 w-2.5 h-2.5 rounded-full bg-manila ring-2 ring-surface"></span>
            </button>

            <div x-show="open" x-transition.origin.top.right x-cloak
                class="absolute right-0 mt-2 w-80 bg-surface rounded border border-ink sticker-shadow-sm z-40">

                <div class="flex items-center justify-between px-4 py-3 border-b border-line">
                    <p class="text-sm font-semibold">Notifikasi</p>
                    <button x-show="unread > 0" @click="bacaSemua()"
                        class="text-xs font-semibold text-steel hover:text-ink transition-colors">
                        Tandai semua dibaca
                    </button>
                </div>

                <div class="max-h-96 overflow-y-auto">
                    <template x-if="loading">
                        <p class="px-4 py-6 text-sm text-steel text-center">Memuat...</p>
                    </template>

                    <template x-if="!loading && items.length === 0">
                        <p class="px-4 py-6 text-sm text-steel text-center">Belum ada notifikasi.</p>
                    </template>

                    <template x-for="n in items" :key="n.id">
                        <button @click="buka(n)"
                            class="w-full text-left px-4 py-3 border-b border-line last:border-b-0 hover:bg-paper transition-colors flex gap-3"
                            :class="n.dibaca ? '' : 'bg-manila/10'">
                            <span class="mt-1.5 w-2 h-2 rounded-full shrink-0"
                                :class="n.dibaca ? 'bg-transparent' : 'bg-manila'"></span>
                            <span class="min-w-0">
                                <span class="block text-sm font-semibold truncate" x-text="n.judul"></span>
                                <span class="block text-xs text-steel mt-0.5" x-text="n.pesan"></span>
                                <span class="block text-[11px] text-steel/70 mt-1" x-text="n.waktu"></span>
                            </span>
                        </button>
                    </template>
                </div>
            </div>
        </div>

        {{-- Menu pengguna --}}
        <div x-data="{ userMenu: false }" class="relative">
            <button @click="userMenu = !userMenu" @click.outside="userMenu = false"
                class="flex items-center gap-3 h-10 pl-1 pr-3 rounded border border-line bg-surface hover:border-ink transition-colors">
                <div
                    class="w-8 h-8 rounded-sm bg-manila flex items-center justify-center text-on-manila font-display font-extrabold text-sm shrink-0 overflow-hidden">
                    @if (auth()->user()->profile)
                        <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Avatar"
                            class="w-full h-full object-cover">
                    @else
                        {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                    @endif
                </div>
                <span class="hidden sm:block text-sm font-semibold max-w-[120px] truncate">
                    {{ auth()->user()->name ?? 'Pengguna' }}
                </span>
                <i class="fas fa-chevron-down text-[10px] text-steel hidden sm:block transition-transform duration-200"
                    :class="userMenu ? 'rotate-180' : ''"></i>
            </button>

            <div x-show="userMenu" x-transition.origin.top.right x-cloak
                class="absolute right-0 mt-2 w-64 bg-surface rounded border border-ink sticker-shadow-sm z-40">

                <div class="px-4 py-4 border-b border-line">
                    <p class="text-sm font-semibold truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                    <p class="text-xs text-steel truncate">{{ auth()->user()->email ?? '-' }}</p>
                </div>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit"
                        class="w-full flex items-center gap-2.5 px-4 py-3 text-sm font-semibold text-red-700 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                        <i class="fas fa-arrow-right-from-bracket w-4 text-center"></i> Keluar
                    </button>
                </form>
            </div>
        </div>
    </div>
</header>

<script>
    function notifBell() {
        const csrf = document.querySelector('meta[name="csrf-token"]').content;
        const headers = { 'Accept': 'application/json', 'X-CSRF-TOKEN': csrf };

        return {
            open: false,
            loading: false,
            unread: {{ auth()->user()->unreadNotifications()->count() }},
            items: [],

            mulai() {
                if (!window.Echo) { console.warn('Echo belum termuat'); return; }
                window.Echo.private('App.Models.User.{{ auth()->id() }}')
                    .notification((n) => {
                        this.unread++;
                        this.items.unshift({
                            id: n.id,
                            judul: n.judul ?? 'Notifikasi',
                            pesan: n.pesan ?? '',
                            url: n.url ?? null,
                            dibaca: false,
                            waktu: 'baru saja',
                        });
                        this.items = this.items.slice(0, 10);
                    });
            },

            async toggle() {
                this.open = !this.open;
                if (this.open) await this.muat();
            },

            async muat() {
                this.loading = this.items.length === 0;
                try {
                    const res = await fetch('{{ route('notifikasi.index') }}', { headers });
                    const data = await res.json();
                    this.items = data.items;
                    this.unread = data.unread;
                } finally {
                    this.loading = false;
                }
            },

            async buka(n) {
                if (!n.dibaca) {
                    const res = await fetch(`/notifikasi/${n.id}/baca`, { method: 'POST', headers });
                    const data = await res.json();
                    n.dibaca = true;
                    this.unread = data.unread;
                }
                if (n.url) window.location.href = n.url;
            },

            async bacaSemua() {
                await fetch('{{ route('notifikasi.baca-semua') }}', { method: 'POST', headers });
                this.items.forEach(n => n.dibaca = true);
                this.unread = 0;
            },
        };
    }
</script>