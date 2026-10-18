{{--
    Komponen: Sidebar
    Menu aktif dicek dengan request()->routeIs('nama.route')
    Menu disembunyikan otomatis via @can() sesuai permission Spatie.

    Urutan menu mengikuti use case diagram:
    - Manajemen Arsip, Manajemen Pengguna, Pembuatan Laporan -> khusus Admin
    - Permintaan Izin Akses Arsip -> Staff (ajukan) & Admin (setujui/tolak)
    - Unggah Arsip, Pencarian Arsip, Lihat & Unduh Arsip -> dipakai bersama

    Route categories.* masih dimatikan (belum dibuat).
--}}
@php
    // Kelas satu baris menu: aktif = penanda manila di kiri + latar lebih terang
    $item = fn (bool $active) => 'group flex items-center gap-3 pl-4 pr-3 py-2.5 text-sm font-medium border-l-4 transition-colors '
        . ($active
            ? 'border-manila bg-white/10 text-white'
            : 'border-transparent text-white/65 hover:text-white hover:bg-white/5');
    $icon = fn (bool $active) => 'w-5 text-center ' . ($active ? 'text-manila' : 'text-white/40 group-hover:text-white/70');
@endphp

<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-ink text-white flex flex-col transform transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="h-20 flex items-end gap-3 px-5 pb-5 shrink-0">
        <span class="folder-tab bg-manila text-ink font-display font-extrabold text-sm pl-3 pr-6 py-1.5">RRI</span>
        <span class="font-display font-extrabold text-lg tracking-tight leading-none">ARSIP<span class="text-white/55">ARIS</span></span>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto py-4 pr-3">

        <p class="pl-5 mb-2 text-xs font-semibold text-white/40">Kerja harian</p>

        <a href="{{ route('dashboard.index') }}" class="{{ $item(request()->routeIs('dashboard.index')) }}">
            <i class="fas fa-gauge-high {{ $icon(request()->routeIs('dashboard.index')) }}"></i>
            Beranda
        </a>

        {{-- use case: Unggah Arsip --}}
        @can('upload archives')
            <a href="{{ route('archives.create') }}" class="{{ $item(request()->routeIs('archives.create')) }}">
                <i class="fas fa-upload {{ $icon(request()->routeIs('archives.create')) }}"></i>
                Arsipkan Dokumen
            </a>
        @endcan

        {{-- use case: Manajemen Arsip / Lihat & Unduh Arsip --}}
        @can('view archives')
            <a href="{{ route('archives.index') }}" class="{{ $item(request()->routeIs('archives.index', 'archives.edit')) }}">
                <i class="fas fa-box-archive {{ $icon(request()->routeIs('archives.index', 'archives.edit')) }}"></i>
                Daftar Arsip
            </a>
        @endcan

        {{-- use case: Permintaan Izin Akses Arsip --}}
        @canany(['view own access requests', 'view all access requests'])
            <a href="{{ route('access-requests.index') }}" class="{{ $item(request()->routeIs('access-requests.*')) }}">
                <i class="fas fa-key {{ $icon(request()->routeIs('access-requests.*')) }}"></i>
                Izin Akses
            </a>
        @endcanany

        {{-- use case: Pembuatan Laporan --}}
        @can('view reports')
            <a href="{{ route('reports.index') }}" class="{{ $item(request()->routeIs('reports.*')) }}">
                <i class="fas fa-file-export {{ $icon(request()->routeIs('reports.*')) }}"></i>
                Laporan
            </a>
        @endcan

        <p class="pl-5 mt-8 mb-2 text-xs font-semibold text-white/40">Administrasi</p>

        @can('view roles')
            <a href="{{ route('roles.index') }}" class="{{ $item(request()->routeIs('roles.*')) }}">
                <i class="fas fa-user-shield {{ $icon(request()->routeIs('roles.*')) }}"></i>
                Peran & Hak Akses
            </a>
        @endcan

        @can('view users')
            <a href="{{ route('users.index') }}" class="{{ $item(request()->routeIs('users.*')) }}">
                <i class="fas fa-users {{ $icon(request()->routeIs('users.*')) }}"></i>
                Pengguna
            </a>
        @endcan

        <a href="{{ route('profile.edit') }}" class="{{ $item(request()->routeIs('profile.edit')) }}">
            <i class="fas fa-gear {{ $icon(request()->routeIs('profile.edit')) }}"></i>
            Akun Saya
        </a>

    </nav>

    {{-- Kartu pengguna --}}
    <div class="p-4 border-t border-white/10 shrink-0">
        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 rounded bg-manila flex items-center justify-center text-ink font-display font-extrabold shrink-0 overflow-hidden">
                @if (auth()->user()->profile)
                    <img src="{{ asset('storage/' . auth()->user()->profile) }}" alt="Avatar"
                        class="w-full h-full object-cover">
                @else
                    {{ strtoupper(substr(auth()->user()->name ?? 'U', 0, 1)) }}
                @endif
            </div>
            <div class="min-w-0">
                <p class="text-sm font-semibold text-white truncate">{{ auth()->user()->name ?? 'Pengguna' }}</p>
                <p class="text-xs text-white/50 truncate">{{ auth()->user()->email ?? '-' }}</p>
            </div>
        </div>
    </div>
</aside>