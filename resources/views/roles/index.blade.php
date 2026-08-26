@extends('layouts.app')

@section('pageTitle', 'Manajemen Role')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Manajemen Role</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola role dan hak akses pengguna.</p>
            </div>
            <a href="{{ route('roles.create') }}"
                class="px-4 py-2.5 bg-aris-600 text-white text-sm font-semibold rounded-xl hover:bg-aris-700 transition-colors">
                <i class="fas fa-plus mr-1.5"></i> Tambah Role
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-left">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama Role</th>
                        <th class="px-4 py-3 font-semibold">Jumlah Permission</th>
                        <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($roles as $role)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $role->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $role->permissions_count }} permission</td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('roles.permissions.edit', $role) }}"
                                    class="text-slate-600 font-semibold hover:text-slate-900">
                                    <i class="fas fa-key mr-1"></i> Permission
                                </a>
                                <a href="{{ route('roles.edit', $role) }}"
                                    class="text-aris-600 font-semibold hover:text-aris-700">
                                    <i class="fas fa-pen mr-1"></i> Edit
                                </a>
                                <form action="{{ route('roles.destroy', $role) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus role ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 font-semibold hover:text-red-700">
                                        <i class="fas fa-trash mr-1"></i> Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="px-4 py-8 text-center text-slate-400">
                                Belum ada role yang dibuat.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($roles->hasPages())
            <div class="mt-4">
                {{ $roles->links() }}
            </div>
        @endif
    </div>
@endsection
