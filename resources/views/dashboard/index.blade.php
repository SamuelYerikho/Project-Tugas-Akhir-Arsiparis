@extends('layouts.app')

@section('pageTitle', 'Dashboard')

@section('content')
    <div class="max-w-7xl mx-auto">

        <div class="mb-8">
            <h2 class="text-2xl font-extrabold text-slate-900">
                Selamat datang, {{ auth()->user()->name ?? 'Pengguna' }} 👋
            </h2>
            <p class="text-slate-500 text-sm mt-1">
                Ini adalah ringkasan aktivitas arsip di Sistem ARIS - RRI Stasiun Batam.
            </p>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-5">

            <div class="bg-white rounded-2xl p-6 border border-slate-100">
                <div class="w-12 h-12 bg-aris-50 rounded-xl flex items-center justify-center text-aris-600 mb-4">
                    <i class="fas fa-box-archive text-xl"></i>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">0</p>
                <p class="text-sm text-slate-500 font-medium">Total Arsip</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-100">
                <div class="w-12 h-12 bg-emerald-50 rounded-xl flex items-center justify-center text-emerald-600 mb-4">
                    <i class="fas fa-tags text-xl"></i>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">0</p>
                <p class="text-sm text-slate-500 font-medium">Kategori</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-100">
                <div class="w-12 h-12 bg-amber-50 rounded-xl flex items-center justify-center text-amber-600 mb-4">
                    <i class="fas fa-file-export text-xl"></i>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">0</p>
                <p class="text-sm text-slate-500 font-medium">Laporan Dibuat</p>
            </div>

            <div class="bg-white rounded-2xl p-6 border border-slate-100">
                <div class="w-12 h-12 bg-indigo-50 rounded-xl flex items-center justify-center text-indigo-600 mb-4">
                    <i class="fas fa-users text-xl"></i>
                </div>
                <p class="text-2xl font-extrabold text-slate-900">0</p>
                <p class="text-sm text-slate-500 font-medium">Total Pengguna</p>
            </div>

        </div>

        <div class="mt-8 bg-white rounded-2xl p-8 border border-slate-100 text-center text-slate-400">
            <i class="fas fa-chart-simple text-3xl mb-3"></i>
            <p class="text-sm font-medium">Grafik dan data arsip terbaru akan tampil di sini.</p>
        </div>

    </div>
@endsection