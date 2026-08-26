{{--
    Komponen: Sidebar
    Menu aktif dicek dengan request()->routeIs('nama.route')
    Menu disembunyikan otomatis via @can() sesuai permission Spatie.

    Menu di bawah disusun mengikuti use case diagram (Gambar 4):
    - Manajemen Arsip, Manajemen Pengguna, Pembuatan Laporan -> khusus Admin
    - Permintaan Izin Akses Arsip -> khusus Staff (ajukan) & Admin (approve/reject)
    - Unggah Arsip, Pencarian Arsip, Lihat & Unduh Arsip -> dipakai bersama

    Route categories.* masih dimatikan (belum dibuat).
--}}
<aside
    class="fixed inset-y-0 left-0 z-40 w-64 bg-white border-r border-slate-200 flex flex-col transform transition-transform duration-300"
    :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'">

    {{-- Logo --}}
    <div class="h-20 flex items-center gap-2.5 px-6 border-b border-slate-100 shrink-0">
        <div class="w-9 h-9 bg-aris-600 rounded-xl flex items-center justify-center text-white font-black text-sm">
            RRI
        </div>
        <span class="font-extrabold text-lg tracking-tight text-slate-900">
            ARSIP<span class="text-aris-600">ARIS</span>
        </span>
    </div>

    {{-- Navigasi --}}
    <nav class="flex-1 overflow-y-auto px-4 py-6 space-y-1">

        <p class="px-3 mb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Utama</p>

        <a href="{{ route('dashboard.index') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
                  {{ request()->routeIs('dashboard.index')
                      ? 'bg-aris-50 text-aris-700'
                      : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <i
                class="fas fa-gauge-high w-5 text-center {{ request()->routeIs('dashboard.index') ? 'text-aris-600' : 'text-slate-400' }}"></i>
            Dashboard
        </a>

        {{-- use case: Unggah Arsip --}}
        @can('upload archives')
            <a href="{{ route('archives.create') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
                      {{ request()->routeIs('archives.create')
                          ? 'bg-aris-50 text-aris-700'
                          : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i
                    class="fas fa-upload w-5 text-center {{ request()->routeIs('archives.create') ? 'text-aris-600' : 'text-slate-400' }}"></i>
                Unggah Arsip
            </a>
        @endcan

        {{-- use case: Manajemen Arsip / Lihat & Unduh Arsip --}}
        @can('view archives')
            <a href="{{ route('archives.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
                      {{ request()->routeIs('archives.index', 'archives.edit')
                          ? 'bg-aris-50 text-aris-700'
                          : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i
                    class="fas fa-box-archive w-5 text-center {{ request()->routeIs('archives.index', 'archives.edit') ? 'text-aris-600' : 'text-slate-400' }}"></i>
                Data Arsip
            </a>
        @endcan

        {{-- use case: Permintaan Izin Akses Arsip --}}
        @canany(['view own access requests', 'view all access requests'])
            <a href="{{ route('access-requests.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
            {{ request()->routeIs('access-requests.*')
                ? 'bg-aris-50 text-aris-700'
                : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i
                    class="fas fa-key w-5 text-center {{ request()->routeIs('access-requests.*') ? 'text-aris-600' : 'text-slate-400' }}"></i>
                Permintaan Izin Akses
            </a>
        @endcanany

        {{-- use case: Pembuatan Laporan --}}
        @can('view reports')
            <a href="{{ route('reports.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
                      {{ request()->routeIs('reports.*')
                          ? 'bg-aris-50 text-aris-700'
                          : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i
                    class="fas fa-file-export w-5 text-center {{ request()->routeIs('reports.*') ? 'text-aris-600' : 'text-slate-400' }}"></i>
                Laporan
            </a>
        @endcan

        <p class="px-3 mt-6 mb-2 text-[11px] font-bold uppercase tracking-widest text-slate-400">Pengaturan</p>

        @can('view roles')
            <a href="{{ route('roles.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
              {{ request()->routeIs('roles.*')
                  ? 'bg-aris-50 text-aris-700'
                  : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i
                    class="fas fa-user-shield w-5 text-center {{ request()->routeIs('roles.*') ? 'text-aris-600' : 'text-slate-400' }}"></i>
                Manajemen Role
            </a>
        @endcan

        @can('view users')
            <a href="{{ route('users.index') }}"
                class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
              {{ request()->routeIs('users.*')
                  ? 'bg-aris-50 text-aris-700'
                  : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
                <i
                    class="fas fa-users w-5 text-center {{ request()->routeIs('users.*') ? 'text-aris-600' : 'text-slate-400' }}"></i>
                Data Pengguna
            </a>
        @endcan

        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 px-3 py-2.5 rounded-xl text-sm font-semibold transition-colors
          {{ request()->routeIs('profile.edit')
              ? 'bg-aris-50 text-aris-700'
              : 'text-slate-500 hover:bg-slate-50 hover:text-slate-900' }}">
            <i
                class="fas fa-gear w-5 text-center {{ request()->routeIs('profile.edit') ? 'text-aris-600' : 'text-slate-400' }}"></i>
            Pengaturan Akun
        </a>

    </nav>



    {{-- Footer Sidebar --}}
    <div class="p-4 border-t border-slate-100 shrink-0">
        <div class="flex items-center gap-3 px-2 py-2">
            <div
                class="w-9 h-9 rounded-full bg-aris-600 flex items-center justify-center text-white font-bold text-sm shrink-0 overflow-hidden">
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

        <p class="text-[11px] text-slate-400 text-center mt-3">
            &copy; {{ date('Y') }} RRI Stasiun Batam.
        </p>
    </div>
</aside>
