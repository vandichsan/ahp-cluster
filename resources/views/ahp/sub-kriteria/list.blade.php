@extends('layouts.app')

@section('title', 'Penilaian Sub-Kriteria - Synthesis Huis')
@section('header', 'Penilaian Matriks Sub-Kriteria')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-gold-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-6 -ml-6 w-24 h-24 bg-gold-500/5 rounded-full blur-2xl"></div>
            <div class="relative z-10">
                <div class="flex items-center space-x-3 mb-3">
                    <div
                        class="w-10 h-10 rounded-xl bg-gold-500/10 border border-gold-500/20 flex items-center justify-center">
                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10">
                            </path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-bold text-white">Daftar Grup Sub-Kriteria</h2>
                        <p class="text-xs text-gray-500">Penilaian matriks perbandingan berpasangan antar sub-kriteria</p>
                    </div>
                </div>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Pilih salah satu kriteria utama di bawah ini untuk mengisi <strong class="text-white">matriks
                        perbandingan berpasangan</strong> antar sub-kriterianya menggunakan <strong
                        class="text-gold-400">Skala Saaty (1–9)</strong>. Sistem akan menghitung bobot lokal (Eigenvector)
                    secara otomatis.
                </p>
            </div>
        </div>
        @if($criteriaWithSubs->isEmpty())
            <div class="glass-panel p-8 rounded-2xl border border-slate-800 text-center">
                <div
                    class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-800/50 border border-slate-700 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4">
                        </path>
                    </svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Belum Ada Sub-Kriteria</h3>
                <p class="text-sm text-gray-400 mb-4">Tambahkan sub-kriteria pada halaman <strong class="text-gold-400">Kriteria
                        & Sub</strong> terlebih dahulu.</p>
                <a href="{{ route('data.criteria') }}"
                    class="inline-flex items-center px-5 py-2.5 bg-gold-500/10 border border-gold-500/30 text-gold-400 font-semibold text-sm rounded-lg hover:bg-gold-500/20 hover:border-gold-500/50 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6">
                        </path>
                    </svg>
                    Kelola Kriteria
                </a>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($criteriaWithSubs as $crit)
                    <div
                        class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden group hover:border-gold-500/30 transition-all duration-300">
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div
                                    class="w-12 h-12 rounded-xl bg-gradient-to-br from-gold-500/20 to-gold-500/5 border border-gold-500/20 flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    <span class="text-gold-400 font-bold text-lg">{{ $loop->iteration }}</span>
                                </div>
                                <div>
                                    <h3 class="text-base font-bold text-white group-hover:text-gold-400 transition-colors">
                                        {{ $crit->name }}</h3>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="inline-flex items-center text-xs text-gray-400">
                                            <svg class="w-3.5 h-3.5 mr-1 text-gray-500" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z">
                                                </path>
                                            </svg>
                                            {{ $crit->subCriteria->count() }} Sub-Kriteria
                                        </span>
                                        <span class="inline-flex items-center text-xs text-gray-500">
                                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                            {{ $crit->subCriteria->count() * ($crit->subCriteria->count() - 1) / 2 }} Perbandingan
                                        </span>
                                    </div>
                                    <div class="flex flex-wrap gap-1.5 mt-2">
                                        @foreach($crit->subCriteria as $sub)
                                            <span
                                                class="px-2 py-0.5 text-[10px] font-medium text-gray-300 bg-slate-800/60 border border-slate-700/50 rounded-md">
                                                {{ $sub->name }}
                                                @if($sub->local_weight)
                                                    <span class="text-gold-400 ml-1">({{ number_format($sub->local_weight, 4) }})</span>
                                                @endif
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="flex-shrink-0">
                                <a href="{{ route('sub_criteria.matrix', $crit->id) }}"
                                    class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-500 text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-gold-500/30 group/btn">
                                    <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:rotate-12" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z">
                                        </path>
                                    </svg>
                                    Input Penilaian Sub-Kriteria
                                    <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none"
                                        stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                    </svg>
                                </a>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <div
            class="glass-panel p-4 rounded-xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
            <a href="{{ route('ahp.matrix') }}"
                class="flex items-center text-sm text-gray-400 hover:text-gold-400 transition-colors">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali ke Kuesioner AHP Kriteria Utama
            </a>
            <div class="flex items-center space-x-2">
                <div class="w-2 h-2 rounded-full bg-gold-500/50 animate-pulse"></div>
                <span class="text-xs text-gray-500">{{ $criteriaWithSubs->count() }} grup tersedia</span>
            </div>
        </div>
    </div>
@endsection