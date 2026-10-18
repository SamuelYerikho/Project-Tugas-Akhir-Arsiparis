@extends('layouts.app')

@section('pageTitle', 'Pengguna')

@section('content')
    <div class="max-w-6xl">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display font-extrabold text-3xl tracking-tight">Pengguna</h2>
                <p class="text-sm text-steel mt-2 max-w-md">Kelola akun dan peran setiap pengguna sistem arsip.</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                <i class="fas fa-plus text-xs"></i> Tambah pengguna
            </a>
        </div>

        <div class="bg-white border border-line rounded border-t-2 border-t-ink overflow-x-auto">
            <table class="w-full text-sm min-w-[40rem]">
                <thead class="text-steel text-left">
                    <tr class="border-b border-line">
                        <th class="px-5 py-4 font-semibold">Nama</th>
                        <th class="px-5 py-4 font-semibold">Username</th>
                        <th class="px-5 py-4 font-semibold">Email</th>
                        <th class="px-5 py-4 font-semibold">Peran</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($users as $user)
                        <tr class="hover:bg-paper/70">
                            <td class="px-5 py-4">
                                <div class="flex items-center gap-3">
                                    <span class="w-8 h-8 rounded-sm bg-ink text-white font-display font-extrabold text-xs flex items-center justify-center shrink-0">
                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                    </span>
                                    <span class="font-semibold">{{ $user->name }}</span>
                                </div>
                            </td>
                            <td class="px-5 py-4 text-steel">{{ $user->username }}</td>
                            <td class="px-5 py-4 text-steel">{{ $user->email }}</td>
                            <td class="px-5 py-4">
                                @forelse ($user->roles as $role)
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-sm bg-manila/30 text-ink text-xs font-semibold">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-steel text-xs">Belum ada peran</span>
                                @endforelse
                            </td>
                            <td class="px-5 py-4 text-right whitespace-nowrap space-x-4">
                                <a href="{{ route('users.password.edit', $user) }}"
                                    class="text-steel font-semibold underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                                    Atur ulang sandi
                                </a>
                                <a href="{{ route('users.edit', $user) }}"
                                    class="text-ink font-semibold underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">
                                    Ubah
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Hapus pengguna ini? Tindakan ini tidak bisa dibatalkan.')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                            class="text-red-700 font-semibold underline underline-offset-4 decoration-red-200 hover:decoration-red-700">
                                            Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-5 py-12 text-center text-steel">
                                Belum ada pengguna terdaftar. Tambahkan pengguna pertama dengan tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection