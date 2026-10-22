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
                class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-manila text-on-manila text-sm font-semibold rounded hover:brightness-95 transition">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15"/>
                </svg>
                Tambah peran
            </a>
        </div>

        <div class="bg-surface border border-line rounded border-t-2 border-t-ink overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full text-sm text-left min-w-[40rem]">
                    <thead class="bg-paper/60 text-steel text-xs uppercase tracking-wider">
                        <tr class="border-b border-line">
                            <th class="px-5 py-3.5 font-semibold">Nama peran</th>
                            <th class="px-5 py-3.5 font-semibold">Jumlah izin</th>
                            <th class="px-5 py-3.5 font-semibold text-right">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-line">
                        @forelse ($roles as $role)
                            <tr class="hover:bg-paper align-top transition-colors">
                                <td class="px-5 py-4">
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-sm bg-manila/30 text-on-manila dark:bg-manila/25 dark:text-manila font-semibold">
                                        <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 6a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0ZM4.501 20.118a7.5 7.5 0 0 1 14.998 0A17.933 17.933 0 0 1 12 21.75c-2.676 0-5.216-.584-7.499-1.632Z"/>
                                        </svg>
                                        {{ $role->name }}
                                    </span>
                                </td>
                                <td class="px-5 py-4 text-steel">
                                    <span class="inline-flex items-center gap-1.5 text-xs font-semibold text-steel">
                                        <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z"/>
                                        </svg>
                                        {{ $role->permissions_count }} izin
                                    </span>
                                </td>
                                <td class="px-5 py-4">
                                    <div class="flex items-center justify-end gap-2 whitespace-nowrap">
                                        <a href="{{ route('roles.permissions.edit', $role) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-ink hover:border-ink transition-colors">
                                            <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                                            </svg>
                                            Atur izin
                                        </a>
                                        <a href="{{ route('roles.edit', $role) }}"
                                            class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-steel hover:text-ink hover:border-ink transition-colors">
                                            <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125"/>
                                            </svg>
                                            Ubah
                                        </a>
                                        <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                            onsubmit="return confirm('Hapus peran ini? Tindakan ini tidak bisa dibatalkan.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center gap-1.5 px-2.5 py-1.5 rounded border border-line text-xs font-semibold text-red-700 dark:text-red-400 hover:border-red-600 dark:hover:border-red-500 hover:bg-red-50 dark:hover:bg-red-900/20 transition-colors">
                                                <svg class="w-3.5 h-3.5 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor" aria-hidden="true">
                                                    <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0"/>
                                                </svg>
                                                Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-5 py-16 text-center">
                                    <svg class="w-10 h-10 text-steel/70 mx-auto mb-3" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z"/>
                                    </svg>
                                    <p class="text-sm font-medium text-steel">Belum ada peran.</p>
                                    <a href="{{ route('roles.create') }}"
                                        class="inline-flex items-center gap-2 mt-4 text-sm font-semibold text-ink underline underline-offset-4 decoration-line hover:decoration-ink transition-colors">
                                        Buat peran pertama
                                    </a>
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        @if ($roles->hasPages())
            <div class="mt-6">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection