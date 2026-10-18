@extends('layouts.app')

{{-- Layout memakai 'pageTitle' (bukan 'title') untuk judul di navbar dan tab browser --}}
@section('pageTitle', 'Akun Saya')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-white border rounded text-sm text-ink outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $locked = 'w-full px-3.5 py-3 bg-paper border border-line rounded text-sm text-steel cursor-not-allowed';
    $label = 'block text-sm font-semibold mb-1.5';
@endphp

    <div class="max-w-5xl">

        <div class="mb-10">
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Akun saya</h2>
            <p class="text-sm text-steel mt-2">Kelola profil dan keamanan akun Anda.</p>
        </div>

        @if (session('success'))
            <div class="mb-8 bg-white border-l-4 border-manila text-ink text-sm font-semibold px-4 py-3">
                {{ session('success') }}
            </div>
        @endif

        {{-- ===== Bagian: Profil ===== --}}
        <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8 pb-12">
            <div>
                <h3 class="font-display font-bold text-xl">Profil</h3>
                <p class="text-sm text-steel mt-2 leading-relaxed">
                    Ubah nama dan foto Anda. Username dan email dikelola oleh admin, jadi tidak bisa diubah di sini.
                </p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="lg:col-span-2 bg-white border border-line rounded p-6 sm:p-8 space-y-6">
                @csrf
                @method('PATCH')

                {{-- Foto profil --}}
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded bg-manila flex items-center justify-center text-ink font-display font-extrabold text-2xl overflow-hidden shrink-0">
                        @if ($user->profile)
                            <img src="{{ asset('storage/' . $user->profile) }}" alt="Avatar"
                                class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <label for="profile"
                            class="inline-block cursor-pointer text-sm font-semibold text-ink border border-ink px-4 py-2 rounded hover:bg-ink hover:text-white transition-colors">
                            Ganti foto
                        </label>
                        <input id="profile" name="profile" type="file" accept="image/png,image/jpeg" class="hidden">
                        <p id="profile-filename" class="text-xs text-steel mt-2">JPG atau PNG, maksimal 2 MB.</p>
                        @error('profile')
                            <p class="text-xs text-red-700 mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Nama --}}
                <div>
                    <label for="name" class="{{ $label }}">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        class="{{ $input }} @error('name') border-red-600 @else border-line @enderror">
                    @error('name')
                        <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username & email (hanya baca) --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="username" class="{{ $label }}">Username</label>
                        <input id="username" type="text" value="{{ $user->username }}" disabled class="{{ $locked }}">
                    </div>
                    <div>
                        <label for="email" class="{{ $label }}">Email</label>
                        <input id="email" type="email" value="{{ $user->email }}" disabled class="{{ $locked }}">
                    </div>
                </div>

                <div class="pt-4 border-t border-line flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                        Simpan profil
                    </button>
                </div>
            </form>
        </section>

        {{-- ===== Bagian: Keamanan ===== --}}
        <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
            <div>
                <h3 class="font-display font-bold text-xl">Keamanan</h3>
                <p class="text-sm text-steel mt-2 leading-relaxed">
                    Pakai kata sandi yang kuat dan belum pernah dipakai di tempat lain.
                </p>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST"
                class="lg:col-span-2 bg-white border border-line rounded p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="{{ $label }}">Kata sandi saat ini</label>
                    <input id="current_password" name="current_password" type="password"
                        class="{{ $input }} @error('current_password') border-red-600 @else border-line @enderror">
                    @error('current_password')
                        <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="{{ $label }}">Kata sandi baru</label>
                        <input id="password" name="password" type="password"
                            class="{{ $input }} @error('password') border-red-600 @else border-line @enderror">
                        @error('password')
                            <p class="text-xs text-red-700 mt-1.5">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="{{ $label }}">Ulangi kata sandi baru</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="{{ $input }} border-line">
                    </div>
                </div>

                <div class="pt-4 border-t border-line flex justify-end">
                    <button type="submit"
                        class="px-6 py-3 bg-ink text-white text-sm font-semibold rounded hover:bg-ink/90 transition-colors">
                        Ganti kata sandi
                    </button>
                </div>
            </form>
        </section>

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