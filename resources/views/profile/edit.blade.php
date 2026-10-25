@extends('layouts.app')

{{-- Layout memakai 'pageTitle' (bukan 'title') untuk judul di navbar dan tab browser --}}
@section('pageTitle', 'Akun Saya')

@section('content')
@php
    $input = 'w-full px-3.5 py-3 bg-surface border rounded text-sm text-ink placeholder:text-steel/70 outline-none focus:ring-2 focus:ring-manila focus:border-ink transition-colors';
    $locked = 'w-full px-3.5 py-3 bg-paper border border-line rounded text-sm text-steel cursor-not-allowed';
    $label = 'block text-sm font-semibold mb-1.5';
    $errorText = 'text-xs text-red-700 dark:text-red-400 mt-1.5';

    // Dikirim dari ProfileController@edit. Fallback ke pengecekan langsung
    // supaya view tetap aman kalau variabel tidak dikirim.
    $canEditIdentity = $canEditIdentity ?? auth()->user()->can('edit account identity');
@endphp

    <div class="max-w-5xl">

        <div class="mb-10">
            <h2 class="font-display font-extrabold text-3xl tracking-tight">Akun saya</h2>
            <p class="text-sm text-steel mt-2">Kelola profil dan keamanan akun Anda.</p>
        </div>

        @if (session('success'))
            <div class="mb-8 bg-surface border-l-4 border-manila text-ink text-sm font-semibold px-4 py-3 flex items-center gap-3 rounded-r">
                <svg class="w-4 h-4 text-manila shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                </svg>
                {{ session('success') }}
            </div>
        @endif

        {{-- ===== Bagian: Profil ===== --}}
        <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8 pb-12">
            <div>
                <h3 class="font-display font-bold text-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                    </svg>
                    Profil
                </h3>
                <p class="text-sm text-steel mt-2 leading-relaxed">
                    @if ($canEditIdentity)
                        Ubah nama, foto, username, dan email Anda.
                    @else
                        Ubah nama dan foto Anda. Username dan email dikelola oleh admin, jadi tidak bisa diubah di sini.
                    @endif
                </p>
            </div>

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data"
                class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                @csrf
                @method('PATCH')

                {{-- Foto profil --}}
                <div class="flex items-center gap-5">
                    <div class="w-20 h-20 rounded bg-manila flex items-center justify-center text-on-manila font-display font-extrabold text-2xl overflow-hidden shrink-0">
                        @if ($user->profile)
                            <img src="{{ asset('storage/' . $user->profile) }}" alt="Avatar"
                                class="w-full h-full object-cover">
                        @else
                            {{ strtoupper(substr($user->name ?? 'U', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <label for="profile"
                            class="inline-flex items-center gap-2 cursor-pointer text-sm font-semibold text-ink border border-ink px-4 py-2 rounded hover:bg-invert hover:text-on-invert transition-colors">
                            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.827 6.175A2.31 2.31 0 0 1 5.186 7.23c-.38.054-.757.112-1.134.175C2.999 7.58 2.25 8.507 2.25 9.574V18a2.25 2.25 0 0 0 2.25 2.25h15A2.25 2.25 0 0 0 21.75 18V9.574c0-1.067-.75-1.994-1.802-2.169a47.865 47.865 0 0 0-1.134-.175 2.31 2.31 0 0 1-1.64-1.055l-.822-1.316a2.192 2.192 0 0 0-1.736-1.039 48.774 48.774 0 0 0-5.232 0 2.192 2.192 0 0 0-1.736 1.039l-.821 1.316Z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 12.75a4.5 4.5 0 1 1-9 0 4.5 4.5 0 0 1 9 0ZM18.75 10.5h.008v.008h-.008V10.5Z"/>
                            </svg>
                            Ganti foto
                        </label>
                        <input id="profile" name="profile" type="file" accept="image/png,image/jpeg" class="hidden">
                        <p id="profile-filename" class="text-xs text-steel mt-2">JPG atau PNG, maksimal 2 MB.</p>
                        @error('profile')
                            <p class="{{ $errorText }} mt-1">{{ $message }}</p>
                        @enderror
                    </div>
                </div>

                {{-- Nama --}}
                <div>
                    <label for="name" class="{{ $label }}">Nama</label>
                    <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                        placeholder="Nama lengkap Anda"
                        class="{{ $input }} @error('name') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                    @error('name')
                        <p class="{{ $errorText }}">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Username & email --}}
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="username" class="{{ $label }}">Username</label>
                        @if ($canEditIdentity)
                            <input id="username" name="username" type="text"
                                value="{{ old('username', $user->username) }}"
                                placeholder="Username Anda"
                                class="{{ $input }} @error('username') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            @error('username')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @else
                            <input id="username" type="text" value="{{ $user->username }}" disabled class="{{ $locked }}">
                        @endif
                    </div>
                    <div>
                        <label for="email" class="{{ $label }}">Email</label>
                        @if ($canEditIdentity)
                            <input id="email" name="email" type="email"
                                value="{{ old('email', $user->email) }}"
                                placeholder="nama@contoh.com"
                                class="{{ $input }} @error('email') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            @error('email')
                                <p class="{{ $errorText }}">{{ $message }}</p>
                            @enderror
                        @else
                            <input id="email" type="email" value="{{ $user->email }}" disabled class="{{ $locked }}">
                        @endif
                    </div>
                </div>

                <div class="pt-4 border-t border-line flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/>
                        </svg>
                        Simpan profil
                    </button>
                </div>
            </form>
        </section>

        {{-- ===== Bagian: Keamanan ===== --}}
        <section class="grid lg:grid-cols-3 gap-6 lg:gap-10 border-t-2 border-ink pt-8">
            <div>
                <h3 class="font-display font-bold text-xl flex items-center gap-2">
                    <svg class="w-4 h-4 text-steel shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                    </svg>
                    Keamanan
                </h3>
                <p class="text-sm text-steel mt-2 leading-relaxed">
                    Pakai kata sandi yang kuat dan belum pernah dipakai di tempat lain.
                </p>
            </div>

            <form action="{{ route('profile.password.update') }}" method="POST"
                class="lg:col-span-2 bg-surface border border-line rounded p-6 sm:p-8 space-y-6">
                @csrf
                @method('PUT')

                <div>
                    <label for="current_password" class="{{ $label }}">Kata sandi saat ini</label>
                    <div class="relative">
                        <input id="current_password" name="current_password" type="password"
                            placeholder="••••••••"
                            class="{{ $input }} pr-11 @error('current_password') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                        <button type="button" data-toggle-password="current_password"
                            aria-label="Tampilkan kata sandi saat ini"
                            class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-steel hover:text-ink transition-colors">
                            <svg data-eye-open class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/>
                                <circle cx="12" cy="12" r="3"/>
                            </svg>
                            <svg data-eye-closed class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0 1 12 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 0 1-3.2 3.7M6.6 7.6A16.6 16.6 0 0 0 2.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/>
                            </svg>
                        </button>
                    </div>
                    @error('current_password')
                        <p class="{{ $errorText }}">{{ $message }}</p>
                    @enderror
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                    <div>
                        <label for="password" class="{{ $label }}">Kata sandi baru</label>
                        <div class="relative">
                            <input id="password" name="password" type="password"
                                placeholder="••••••••"
                                class="{{ $input }} pr-11 @error('password') !border-red-600 dark:!border-red-500 @else border-line @enderror">
                            <button type="button" data-toggle-password="password"
                                aria-label="Tampilkan kata sandi baru"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-steel hover:text-ink transition-colors">
                                <svg data-eye-open class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg data-eye-closed class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0 1 12 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 0 1-3.2 3.7M6.6 7.6A16.6 16.6 0 0 0 2.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/>
                                </svg>
                            </button>
                        </div>
                        @error('password')
                            <p class="{{ $errorText }}">{{ $message }}</p>
                        @enderror
                    </div>

                    <div>
                        <label for="password_confirmation" class="{{ $label }}">Ulangi kata sandi baru</label>
                        <div class="relative">
                            <input id="password_confirmation" name="password_confirmation" type="password"
                                placeholder="••••••••"
                                class="{{ $input }} pr-11 border-line">
                            <button type="button" data-toggle-password="password_confirmation"
                                aria-label="Tampilkan ulangi kata sandi baru"
                                class="absolute inset-y-0 right-0 pr-3.5 flex items-center text-steel hover:text-ink transition-colors">
                                <svg data-eye-open class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 12s3.75-6.75 9.75-6.75S21.75 12 21.75 12 18 18.75 12 18.75 2.25 12 2.25 12z"/>
                                    <circle cx="12" cy="12" r="3"/>
                                </svg>
                                <svg data-eye-closed class="w-4 h-4 hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18M10.6 6.3A9.6 9.6 0 0 1 12 6.25c6 0 9.75 5.75 9.75 5.75a17 17 0 0 1-3.2 3.7M6.6 7.6A16.6 16.6 0 0 0 2.25 12S6 18.75 12 18.75c1.4 0 2.7-.3 3.8-.8"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="pt-4 border-t border-line flex justify-end">
                    <button type="submit"
                        class="inline-flex items-center gap-2 px-6 py-3 bg-invert text-on-invert text-sm font-semibold rounded hover:bg-invert/90 transition-colors">
                        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 11.25h10.5a2.25 2.25 0 0 0 2.25-2.25v-6.75a2.25 2.25 0 0 0-2.25-2.25H6.75a2.25 2.25 0 0 0-2.25 2.25v6.75a2.25 2.25 0 0 0 2.25 2.25Z"/>
                        </svg>
                        Ganti kata sandi
                    </button>
                </div>
            </form>
        </section>

    </div>
@endsection

@push('scripts')
    <script>
        // Pratinjau nama file foto
        document.getElementById('profile')?.addEventListener('change', function (e) {
            const filenameEl = document.getElementById('profile-filename');
            if (e.target.files.length > 0 && filenameEl) {
                filenameEl.textContent = e.target.files[0].name;
            }
        });

        // Toggle tampil/sembunyi kata sandi (berlaku untuk semua kolom dengan data-toggle-password)
        document.querySelectorAll('[data-toggle-password]').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const target = document.getElementById(btn.dataset.togglePassword);
                if (!target) return;

                // Balik tipe input
                const nowVisible = target.type === 'password';
                target.type = nowVisible ? 'text' : 'password';

                // Sinkronkan ikon
                const eyeOpen   = btn.querySelector('[data-eye-open]');
                const eyeClosed = btn.querySelector('[data-eye-closed]');
                if (eyeOpen)   eyeOpen.classList.toggle('hidden', nowVisible);
                if (eyeClosed) eyeClosed.classList.toggle('hidden', !nowVisible);

                // Perbarui label aksesibilitas
                btn.setAttribute('aria-label', nowVisible
                    ? 'Sembunyikan kata sandi'
                    : 'Tampilkan kata sandi');
            });
        });
    </script>
@endpush