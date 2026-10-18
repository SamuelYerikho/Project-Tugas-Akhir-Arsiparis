@extends('layouts.app')

@section('pageTitle', 'Peran & Hak Akses')

@section('content')
    <div class="max-w-5xl">

        <div class="flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4 mb-8">
            <div>
                <h2 class="font-display font-extrabold text-3xl tracking-tight">Peran & hak akses</h2>
                <p class="text-sm text-steel mt-2 max-w-md">Kelola peran pengguna dan fitur apa saja yang boleh dipakai tiap peran.</p>
            </div>
            <a href="{{ route('roles.create') }}"
                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-ink text-sm font-semibold rounded hover:brightness-95 transition">
                <i class="fas fa-plus text-xs"></i> Tambah peran
            </a>
        </div>

        <div class="bg-white border border-line rounded border-t-2 border-t-ink overflow-x-auto">
            <table class="w-full text-sm min-w-[32rem]">
                <thead class="text-steel text-left">
                    <tr class="border-b border-line">
                        <th class="px-5 py-4 font-semibold">Nama peran</th>
                        <th class="px-5 py-4 font-semibold">Jumlah izin</th>
                        <th class="px-5 py-4 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-line">
                    @forelse ($roles as $role)
                        <tr class="hover:bg-paper/70">
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-sm bg-manila/30 text-ink font-semibold">
                                    {{ $role->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4 text-steel">{{ $role->permissions_count }} izin</td>
                            <td class="px-5 py-4 text-right whitespace-nowrap space-x-4">
                                <a href="{{ route('roles.permissions.edit', $role) }}"
                                    class="text-steel font-semibold underline underline-offset-4 decoration-line hover:text-ink hover:decoration-ink">
                                    Atur izin
                                </a>
                                <a href="{{ route('roles.edit', $role) }}"
                                    class="text-ink font-semibold underline underline-offset-4 decoration-manila decoration-2 hover:decoration-ink">
                                    Ubah
                                </a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Hapus peran ini? Tindakan ini tidak bisa dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="text-red-700 font-semibold underline underline-offset-4 decoration-red-200 hover:decoration-red-700">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-5 py-12 text-center text-steel">
                                Belum ada peran. Buat peran pertama dengan tombol di atas.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="mt-6">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection