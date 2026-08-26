@extends('layouts.app')

@section('pageTitle', 'Data Pengguna')

@section('content')
    <div class="max-w-5xl mx-auto">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h1 class="text-xl font-bold text-slate-900">Data Pengguna</h1>
                <p class="text-sm text-slate-500 mt-1">Kelola akun pengguna dan role yang dimiliki.</p>
            </div>
            <a href="{{ route('users.create') }}"
                class="px-4 py-2.5 bg-aris-600 text-white text-sm font-semibold rounded-xl hover:bg-aris-700 transition-colors">
                <i class="fas fa-plus mr-1.5"></i> Tambah Pengguna
            </a>
        </div>

        <div class="bg-white border border-slate-200 rounded-xl overflow-hidden">
            <table class="w-full text-sm">
                <thead class="bg-slate-50 text-slate-500 text-left">
                    <tr>
                        <th class="px-4 py-3 font-semibold">Nama</th>
                        <th class="px-4 py-3 font-semibold">Username</th>
                        <th class="px-4 py-3 font-semibold">Email</th>
                        <th class="px-4 py-3 font-semibold">Role</th>
                        <th class="px-4 py-3 font-semibold text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse ($users as $user)
                        <tr>
                            <td class="px-4 py-3 font-semibold text-slate-900">{{ $user->name }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $user->username }}</td>
                            <td class="px-4 py-3 text-slate-500">{{ $user->email }}</td>
                            <td class="px-4 py-3">
                                @forelse ($user->roles as $role)
                                    <span
                                        class="inline-flex items-center px-2.5 py-1 rounded-lg bg-aris-50 text-aris-700 text-xs font-semibold">
                                        {{ $role->name }}
                                    </span>
                                @empty
                                    <span class="text-slate-400 text-xs">Belum ada role</span>
                                @endforelse
                            </td>
                            <td class="px-4 py-3 text-right space-x-3">
                                <a href="{{ route('users.password.edit', $user) }}"
                                    class="text-slate-600 font-semibold hover:text-slate-900">
                                    <i class="fas fa-key mr-1"></i> Reset Password
                                </a>
                                <a href="{{ route('users.edit', $user) }}"
                                    class="text-aris-600 font-semibold hover:text-aris-700">
                                    <i class="fas fa-pen mr-1"></i> Edit
                                </a>
                                @if ($user->id !== auth()->id())
                                    <form action="{{ route('users.destroy', $user) }}" method="POST" class="inline"
                                        onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 font-semibold hover:text-red-700">
                                            <i class="fas fa-trash mr-1"></i> Hapus
                                        </button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-4 py-8 text-center text-slate-400">
                                Belum ada pengguna terdaftar.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if ($users->hasPages())
            <div class="mt-4">
                {{ $users->links() }}
            </div>
        @endif
    </div>
@endsection