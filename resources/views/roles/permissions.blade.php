@extends('layouts.app')

@section('pageTitle', 'Atur Izin')

@section('content')
    {{-- Kamus terjemahan permission agar mudah dibaca pengguna --}}
    @php
        $translatedPermissions = [
            'view roles' => 'Lihat daftar peran',
            'create roles' => 'Buat peran baru',
            'edit roles' => 'Ubah data peran',
            'delete roles' => 'Hapus peran',
            'assign role permissions' => 'Atur izin akses peran',
            'view users' => 'Lihat daftar pengguna',
            'create users' => 'Tambah pengguna baru',
            'edit users' => 'Ubah data pengguna',
            'delete users' => 'Hapus pengguna',
            'reset user password' => 'Atur ulang sandi pengguna',
            'edit account identity' => 'Ubah username dan email akun sendiri',
            'view archives' => 'Lihat daftar arsip',
            'view all archives' => 'Lihat semua arsip',
            'upload archives' => 'Unggah arsip baru',
            'show archives' => 'Lihat detail arsip',
            'edit archives' => 'Ubah data arsip',
            'delete archives' => 'Hapus arsip',
            'download archives' => 'Unduh file arsip',
            'request archive access' => 'Ajukan permintaan akses arsip',
            'approve archive access' => 'Setujui permintaan akses',
            'reject archive access' => 'Tolak permintaan akses',
            'view all access requests' => 'Lihat semua permintaan akses',
            'view own access requests' => 'Lihat permintaan akses milik sendiri',
            'delete archive access request' => 'Hapus permintaan akses arsip',
            'view reports' => 'Lihat halaman laporan',
            'generate reports' => 'Buat laporan',
            'download reports' => 'Unduh file laporan',
        ];
    @endphp

    <div class="max-w-5xl">

        {{-- Header --}}
        <div class="mb-8">
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-steel hover:text-ink mb-4 transition-colors">
                <svg class="w-3.5 h-3.5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18"/>
                </svg>
                Kembali ke daftar peran
            </a>
            <div class="flex flex-wrap items-center gap-3">
                <h2 class="font-display font-extrabold text-3xl tracking-tight">Atur izin untuk peran</h2>
                <span class="inline-flex items-center px-3 py-1 rounded-sm bg-manila text-on-manila font-display font-extrabold text-lg">
                    {{ $role->name }}
                </span>
            </div>
            <p class="text-sm text-steel mt-3 max-w-xl">
                Centang fitur yang boleh dipakai pengguna dengan peran ini. Perubahan berlaku setelah Anda menyimpannya.
            </p>
        </div>

        @error('permissions')
            <div class="mb-6 px-4 py-3 bg-surface border-l-4 border-red-600 dark:border-red-500 text-red-700 dark:text-red-400 text-sm font-semibold flex items-center gap-3 rounded-r">
                <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m9.75 9.75 4.5 4.5m0-4.5-4.5 4.5M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ $message }}
            </div>
        @enderror

        <form action="{{ route('roles.permissions.update', $role) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="space-y-10">
                @forelse ($permissions as $module => $modulePermissions)
                    <section class="grid lg:grid-cols-3 gap-4 lg:gap-10 border-t-2 border-ink pt-6">
                        {{-- Judul modul --}}
                        <div>
                            <h3 class="font-display font-bold text-xl flex items-center gap-2">
                                <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12.75V12A2.25 2.25 0 0 1 4.5 9.75h15A2.25 2.25 0 0 1 21.75 12v.75m-8.69-6.44-2.12-2.12a1.5 1.5 0 0 0-1.061-.44H4.5A2.25 2.25 0 0 0 2.25 6v12a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9a2.25 2.25 0 0 0-2.25-2.25h-5.379a1.5 1.5 0 0 1-1.06-.44Z"/>
                                </svg>
                                Modul {{ $module }}
                            </h3>
                        </div>

                        {{-- Daftar izin: seluruh baris bisa diklik, baris tercentang diberi warna --}}
                        <div class="lg:col-span-2 grid grid-cols-1 md:grid-cols-2 gap-3">
                            @foreach ($modulePermissions as $permission)
                                @php
                                    $friendlyName = $translatedPermissions[$permission->name] ?? $permission->name;
                                    $inputId = 'perm_' . $permission->id;
                                @endphp

                                <label for="{{ $inputId }}"
                                    class="flex items-start gap-3 p-4 bg-surface border border-line rounded cursor-pointer transition-colors hover:border-ink has-[:checked]:border-ink has-[:checked]:bg-manila/20 dark:has-[:checked]:bg-manila/15">
                                    <input id="{{ $inputId }}" type="checkbox" name="permissions[]"
                                        value="{{ $permission->name }}"
                                        {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                        class="mt-0.5 w-4 h-4 accent-ink cursor-pointer shrink-0">
                                    <span class="flex flex-col">
                                        <span class="text-sm font-semibold">{{ $friendlyName }}</span>
                                        <span class="text-xs text-steel mt-0.5">
                                            Memberikan hak untuk {{ strtolower($friendlyName) }}.
                                        </span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                    </section>
                @empty
                    <div class="border-t-2 border-ink pt-10 pb-6">
                        <p class="font-display font-bold text-xl">Belum ada izin akses</p>
                        <p class="text-sm text-steel mt-1">Izin akses belum ditambahkan ke dalam sistem.</p>
                    </div>
                @endforelse
            </div>

            {{-- Tombol aksi menempel di bawah layar agar tetap terjangkau saat daftar panjang --}}
            <div class="sticky bottom-0 z-10 mt-10 -mx-4 sm:-mx-6 lg:-mx-10 px-4 sm:px-6 lg:px-10 py-4 bg-paper/95 backdrop-blur border-t-2 border-ink flex items-center justify-end gap-5">
                <a href="{{ route('roles.index') }}"
                    class="text-sm font-semibold text-steel underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                    <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                    </svg>
                    Simpan perubahan
                </button>
            </div>
        </form>
    </div>
@endsection