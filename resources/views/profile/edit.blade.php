@extends('layouts.app')

@section('title', 'Pengaturan Akun')

@section('content')
    <div class="max-w-3xl mx-auto space-y-6">

        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Pengaturan Akun</h1>
            <p class="text-sm text-slate-500 mt-1">Kelola informasi profil dan keamanan akun kamu.</p>
        </div>

        @if (session('success'))
            <div class="rounded-xl bg-green-50 border border-green-200 text-green-700 text-sm font-semibold px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- Card: Info Profil & Foto --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="text-base font-bold text-slate-900">Informasi Profil</h2>
            <p class="text-sm text-slate-400 mt-1 mb-5">Perbarui nama dan foto profil kamu. Username &amp; email tidak dapat diubah.</p>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf
                @method('PATCH')

                {{-- Foto Profil --}}
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 rounded-full bg-aris-600 flex items-center justify-center text-white font-bold text-lg overflow-hidden shrink-0">
                        @if ($user->profile)
                            <img src="{{ asset('storage/' . $user->profile) }}" alt="Avatar"
                                class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <label for="profile" class="inline-block cursor-pointer text-sm font-semibold text-aris-700 bg-aris-50 hover:bg-aris-100 px-4 py-2 rounded-xl transition-colors">
                            Ganti Foto
                        </label>
                        <input id="profile" name="profile" type="file" accept="image/png,image/jpeg" class="hidden">
                        <p id="profile-filename" class="text-xs text-slate-400 mt-1">JPG atau PNG, maks 2MB.</p>
                        @error('profile')
                            <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Nama --}}
                <div>
                    <label for="name" class="block text-sm font-semibold text-slate-700 mb-1.5">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-transparent">
                    @error('name')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username (readonly) --}}
                <div>
                    <label for="username" class="block text-sm font-semibold text-slate-700 mb-1.5">Username</label>
                    <input id="username" type="text" value="{{ $user->username }}" disabled
                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                    <p class="text-xs text-slate-400 mt-1">Username tidak dapat diubah.</p>
                </div>

                {{-- Email (readonly) --}}
                <div>
                    <label for="email" class="block text-sm font-semibold text-slate-700 mb-1.5">Email</label>
                    <input id="email" type="email" value="{{ $user->email }}" disabled
                        class="w-full px-3.5 py-2.5 border border-slate-200 bg-slate-50 rounded-xl text-sm text-slate-500 cursor-not-allowed">
                    <p class="text-xs text-slate-400 mt-1">Email tidak dapat diubah.</p>
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="bg-aris-600 hover:bg-aris-700 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>

        {{-- Card: Ubah Password --}}
        <div class="bg-white border border-slate-200 rounded-2xl p-6">
            <h2 class="text-base font-bold text-slate-900">Ubah Password</h2>
            <p class="text-sm text-slate-400 mt-1 mb-5">Gunakan password yang kuat dan belum pernah dipakai di tempat lain.</p>

            <form action="{{ route('profile.password.update') }}" method="POST" class="space-y-5">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Saat Ini</label>
                    <input id="current_password" name="current_password" type="password"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-transparent">
                    @error('current_password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password" class="block text-sm font-semibold text-slate-700 mb-1.5">Password Baru</label>
                    <input id="password" name="password" type="password"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-transparent">
                    @error('password')
                        <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-slate-700 mb-1.5">Konfirmasi Password Baru</label>
                    <input id="password_confirmation" name="password_confirmation" type="password"
                        class="w-full px-3.5 py-2.5 border border-slate-200 rounded-xl text-sm shadow-sm focus:outline-none focus:ring-2 focus:ring-aris-500 focus:border-transparent">
                </div>

                <div class="pt-2">
                    <button type="submit"
                        class="bg-slate-900 hover:bg-slate-800 text-white text-sm font-bold px-5 py-2.5 rounded-xl transition-colors">
                        Ubah Password
                    </button>
                </div>
            </form>
        </div>

    </div>
@endsection

@push('scripts')
    <script>
        document.getElementById('profile')?.addEventListener('change', function (e) {
            const filenameEl = document.getElementById('profile-filename');
            if (e.target.files.length > 0 && filenameEl) {
                filenameEl.textContent = e.target.files[0].name;
            }
        });
    </script>
@endpush