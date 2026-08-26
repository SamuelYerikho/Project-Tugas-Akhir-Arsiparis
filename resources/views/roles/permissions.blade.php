@extends('layouts.app')

@section('pageTitle', 'Kelola Permission')

@section('content')
    {{-- Kamus Terjemahan Permission agar lebih mudah dibaca Klien --}}
    @php
        $translatedPermissions = [
            // Manajemen Role & Pengguna
            'view roles' => 'Lihat Daftar Role',
            'create roles' => 'Buat Role Baru',
            'edit roles' => 'Ubah Data Role',
            'delete roles' => 'Hapus Role',
            'assign role permissions' => 'Kelola Izin (Permission) Role',
            'view users' => 'Lihat Daftar Pengguna',
            'create users' => 'Tambah Pengguna Baru',
            'edit users' => 'Ubah Data Pengguna',
            'delete users' => 'Hapus Pengguna',
            'reset user password' => 'Reset Password Pengguna',

            // Manajemen Arsip
            'view archives' => 'Lihat Daftar Arsip',
            'upload archives' => 'Unggah Arsip Baru',
            'show archives' => 'Lihat Detail Arsip',
            'edit archives' => 'Ubah Data Arsip',
            'delete archives' => 'Hapus Arsip',
            'download archives' => 'Unduh File Arsip',

            // Manajemen Permintaan Akses
            'request archive access' => 'Ajukan Permintaan Akses Arsip',
            'approve archive access' => 'Setujui Permintaan Akses',
            'reject archive access' => 'Tolak Permintaan Akses',

            // Laporan
            'view reports' => 'Lihat Halaman Laporan',
            'generate reports' => 'Buat/Buat (Generate) Laporan',
            'download reports' => 'Unduh File Laporan',
        ];
    @endphp

    <div class="max-w-4xl mx-auto space-y-6">
        {{-- Header --}}
        <div>
            <a href="{{ route('roles.index') }}"
                class="inline-flex items-center gap-2 text-sm font-semibold text-slate-500 hover:text-slate-700 mb-2 transition-colors">
                <i class="fas fa-arrow-left text-xs"></i>
                Kembali ke Manajemen Role
            </a>
            <h1 class="text-2xl font-bold text-slate-900">Kelola Akses: <span class="text-aris-600">{{ $role->name }}</span></h1>
            <p class="text-sm text-slate-500 mt-1">
                Tentukan fitur apa saja yang dapat digunakan oleh pengguna dengan role <strong>{{ $role->name }}</strong>.
            </p>
        </div>

        @error('permissions')
            <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 text-sm font-semibold rounded-xl">
                <i class="fas fa-exclamation-circle mr-2"></i> {{ $message }}
            </div>
        @enderror

        {{-- Form Card --}}
        <div class="bg-white border border-slate-200 shadow-sm rounded-xl overflow-hidden">
            <form action="{{ route('roles.permissions.update', $role) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="p-6 space-y-8">
                    @forelse ($permissions as $module => $modulePermissions)
                        <div class="space-y-4">
                            {{-- Judul Modul --}}
                            <div class="flex items-center gap-3 border-b border-slate-100 pb-2">
                                <div class="w-8 h-8 rounded-lg bg-aris-50 text-aris-600 flex items-center justify-center">
                                    <i class="fas fa-folder-open text-sm"></i>
                                </div>
                                <h3 class="text-sm font-bold uppercase tracking-widest text-slate-700">
                                    Modul {{ $module }}
                                </h3>
                            </div>

                            {{-- Grid Checkbox --}}
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pl-11">
                                @foreach ($modulePermissions as $permission)
                                    @php
                                        // Ambil nama terjemahan, jika tidak ada pakai nama asli dari DB
                                        $friendlyName = $translatedPermissions[$permission->name] ?? $permission->name;
                                        // Buat ID unik untuk label 'for' atribut
                                        $inputId = 'perm_' . $permission->id;
                                    @endphp

                                    <div class="relative flex items-start p-3 border border-slate-200 rounded-xl hover:bg-slate-50 hover:border-slate-300 transition-all cursor-pointer group">
                                        <div class="flex items-center h-5 mt-0.5">
                                            <input id="{{ $inputId }}" type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                                {{ in_array($permission->name, old('permissions', $rolePermissions)) ? 'checked' : '' }}
                                                class="w-4 h-4 rounded border-slate-300 text-aris-600 focus:ring-aris-500 cursor-pointer">
                                        </div>
                                        <div class="ml-3 flex flex-col">
                                            <label for="{{ $inputId }}" class="text-sm font-semibold text-slate-800 cursor-pointer group-hover:text-aris-700">
                                                {{ $friendlyName }}
                                            </label>
                                            <label for="{{ $inputId }}" class="text-xs text-slate-500 cursor-pointer mt-0.5">
                                                Memberikan hak untuk {{ strtolower($friendlyName) }}.
                                            </label>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-12">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-slate-100 text-slate-400 mb-3">
                                <i class="fas fa-shield-alt text-xl"></i>
                            </div>
                            <p class="text-sm font-medium text-slate-900">Belum ada permission</p>
                            <p class="text-sm text-slate-500 mt-1">Permission belum ditambahkan ke dalam sistem.</p>
                        </div>
                    @endforelse
                </div>

                {{-- Footer / Tombol Action --}}
                <div class="bg-slate-50 px-6 py-4 border-t border-slate-200 flex items-center justify-end gap-3">
                    <a href="{{ route('roles.index') }}"
                        class="text-sm font-semibold text-slate-600 hover:text-slate-800 px-5 py-2.5 transition-colors">
                        Batal
                    </a>
                    <button type="submit"
                        class="inline-flex items-center gap-2 bg-aris-600 hover:bg-aris-700 text-white text-sm font-semibold px-6 py-2.5 rounded-xl transition-colors shadow-sm">
                        <i class="fas fa-save"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection