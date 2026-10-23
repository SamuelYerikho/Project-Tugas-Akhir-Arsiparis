{{--
    Komponen: Sidebar
    Gaya "kaku" (rigid) selaras tema arsip: garis pemisah tegas, label seksi
    seperti tab map, item aktif dengan bilah manila solid, plus tombol Keluar.
    Menu aktif dicek dengan request()->routeIs('nama.route').
    Menu disembunyikan otomatis via @can() sesuai permission Spatie.
--}}
@php
    // Item menu: border kiri 4px selalu ada (transparan kalau tidak aktif) supaya
    // lebar teks tidak "geser" saat status berubah — ini yang membuat terasa kaku.
    $item = fn (bool $active) => 'group flex items-center gap-3 pl-4 pr-3 py-2.5 text-sm border-l-4 '
        . 'transition-colors duration-150 '
        . ($active
            ? 'border-manila bg-on-invert/10 text-on-invert font-semibold'
            : 'border-transparent text-on-invert/70 font-medium hover:text-on-invert hover:bg-on-invert/5');
    $icon = fn (bool $active) => 'w-5 text-center text-[15px] shrink-0 '
        . ($active ? 'text-manila' : 'text-on-invert/45 group-hover:text-on-invert/75');
    // Label seksi: kecil, uppercase, tracking lebar — meniru tab map arsip.
    $label = 'pl-5 pr-3 mb-2 text-[11px] font-bold uppercase tracking-widest text-on-invert/40';
@endphp

<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-invert text-on-invert flex flex-col
           border-r border-on-invert/10 transform transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo — dipisah garis bawah agar area navigasi terasa seperti "laci" sendiri --}}
    <div class="h-20 flex items-end gap-3 px-5 pb-4 shrink-0 border-b border-on-invert/10">
        <span class="folder-tab bg-manila text-on-manila font-display font-extrabold text-sm pl-3 pr-6 py-1.5">RRI</span>
        <span class="font-display font-extrabold text-lg tracking-tight leading-none">ARSIP<span class="text-on-invert/55">ARIS</span></span>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto py-5 pr-3">

        <p class="{{ $label }}">Kerja harian</p>

        <a href="{{ route('dashboard.index') }}"
           class="{{ $item(request()->routeIs('dashboard.index')) }}"
           @if(request()->routeIs('dashboard.index')) aria-current="page" @endif>
            <i class="fas fa-gauge-high {{ $icon(request()->routeIs('dashboard.index')) }}"></i>
            Beranda
        </a>

        {{-- use case: Unggah Arsip --}}
        @can('upload archives')
            <a href="{{ route('archives.create') }}"
               class="{{ $item(request()->routeIs('archives.create')) }}"
               @if(request()->routeIs('archives.create')) aria-current="page" @endif>
                <i class="fas fa-upload {{ $icon(request()->routeIs('archives.create')) }}"></i>
                Arsipkan Dokumen
            </a>
        @endcan

        {{-- use case: Manajemen Arsip / Lihat & Unduh Arsip --}}
        @can('view archives')
            <a href="{{ route('archives.index') }}"
               class="{{ $item(request()->routeIs('archives.index', 'archives.edit')) }}"
               @if(request()->routeIs('archives.index', 'archives.edit')) aria-current="page" @endif>
                <i class="fas fa-box-archive {{ $icon(request()->routeIs('archives.index', 'archives.edit')) }}"></i>
                Daftar Arsip
            </a>
        @endcan

        {{-- use case: Permintaan Izin Akses Arsip --}}
        @canany(['view own access requests', 'view all access requests'])
            <a href="{{ route('access-requests.index') }}"
               class="{{ $item(request()->routeIs('access-requests.*')) }}"
               @if(request()->routeIs('access-requests.*')) aria-current="page" @endif>
                <i class="fas fa-key {{ $icon(request()->routeIs('access-requests.*')) }}"></i>
                Izin Akses
            </a>
        @endcanany

        {{-- use case: Pembuatan Laporan --}}
        @can('view reports')
            <a href="{{ route('reports.index') }}"
               class="{{ $item(request()->routeIs('reports.*')) }}"
               @if(request()->routeIs('reports.*')) aria-current="page" @endif>
                <i class="fas fa-file-export {{ $icon(request()->routeIs('reports.*')) }}"></i>
                Laporan
            </a>
        @endcan

        {{-- Seksi Administrasi — dipisah garis atas supaya sekatnya tegas --}}
        <div class="mt-6 pt-5 border-t border-on-invert/10">
            <p class="{{ $label }}">Administrasi</p>

            @can('view roles')
                <a href="{{ route('roles.index') }}"
                   class="{{ $item(request()->routeIs('roles.*')) }}"
                   @if(request()->routeIs('roles.*')) aria-current="page" @endif>
                    <i class="fas fa-user-shield {{ $icon(request()->routeIs('roles.*')) }}"></i>
                    Peran &amp; Hak Akses
                </a>
            @endcan

            @can('view users')
                <a href="{{ route('users.index') }}"
                   class="{{ $item(request()->routeIs('users.*')) }}"
                   @if(request()->routeIs('users.*')) aria-current="page" @endif>
                    <i class="fas fa-users {{ $icon(request()->routeIs('users.*')) }}"></i>
                    Pengguna
                </a>
            @endcan

            <a href="{{ route('profile.edit') }}"
               class="{{ $item(request()->routeIs('profile.edit')) }}"
               @if(request()->routeIs('profile.edit')) aria-current="page" @endif>
                <i class="fas fa-gear {{ $icon(request()->routeIs('profile.edit')) }}"></i>
                Akun Saya
            </a>
        </div>

    </nav>

    {{-- Kartu pengguna + tombol Keluar (di dalam form POST + @csrf) --}}
    <div class="border-t border-on-invert/10 shrink-0">

        <div class="flex items-center gap-3 px-4 py-4">
            <div class="w-10 h-10 rounded bg-manila flex items-center justify-center text-on-manila font-display font-extrabold shrink-0 overflow-hidden">
                @if (auth()->user()->profile)
                    <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Avatar"
                         class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-on-invert truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                <p class="text-xs text-on-invert/50 truncate">{{ auth()->user()->email ?? '-' }}</p>
            </div>
        </div>

    </div>
</aside>