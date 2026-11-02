@extends('layouts.app')

@section('title', 'Dashboard - Synthesis Huis SPK')
@section('header', 'Overview Dashboard')

@section('content')
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="glass-panel p-6 rounded-2xl border-t-4 border-gold-500 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-gold-500/10 rounded-full blur-2xl"></div>
            <h3 class="text-gray-400 text-sm font-medium mb-1">Alternatif Platform</h3>
            <p class="text-4xl font-bold text-white">{{ $alternativesCount }}</p>
            <div class="mt-3 flex items-center text-sm">
                <a href="{{ route('data.alternatives') }}"
                    class="text-gold-400 hover:text-gold-300 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                        </path>
                    </svg>
                    Kelola
                </a>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-2xl border-t-4 border-blue-500 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-blue-500/10 rounded-full blur-2xl"></div>
            <h3 class="text-gray-400 text-sm font-medium mb-1">Total Kriteria</h3>
            <p class="text-4xl font-bold text-white">{{ $criteriaCount }}</p>
            <div class="mt-3 flex items-center text-sm">
                <a href="{{ route('data.criteria') }}"
                    class="text-blue-400 hover:text-blue-300 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                        </path>
                    </svg>
                    Kelola
                </a>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-2xl border-t-4 border-emerald-500 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-emerald-500/10 rounded-full blur-2xl"></div>
            <h3 class="text-gray-400 text-sm font-medium mb-1">Total Sub-Kriteria</h3>
            <p class="text-4xl font-bold text-white">{{ $subCriteriaCount }}</p>
            <div class="mt-3 flex items-center text-sm">
                <a href="{{ route('data.criteria') }}"
                    class="text-emerald-400 hover:text-emerald-300 flex items-center transition-colors">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6">
                        </path>
                    </svg>
                    Kelola
                </a>
            </div>
        </div>

        <div class="glass-panel p-6 rounded-2xl border-t-4 border-amber-500 shadow-lg relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-amber-500/10 rounded-full blur-2xl"></div>
            <h3 class="text-gray-400 text-sm font-medium mb-1">Data Campaign</h3>
            <p class="text-4xl font-bold text-white">{{ $campaignCount }}</p>
            <div class="mt-3 flex items-center text-sm">
                <span class="text-amber-400 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                    Dari CSV
                </span>
            </div>
        </div>
    </div>

    @if($alternativesCount > 0 && $criteriaCount > 0)
        <div
            class="glass-panel p-6 rounded-2xl border border-gold-500/30 shadow-xl flex flex-col md:flex-row items-center justify-between gap-6 mb-8 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-gold-500/5 rounded-full blur-3xl"></div>
            <div class="z-10">
                <h3 class="text-lg font-bold text-white mb-1">Data Siap Dinilai!</h3>
                <p class="text-sm text-gray-400">Anda memiliki {{ $alternativesCount }} alternatif dan {{ $criteriaCount }}
                    kriteria. Mulai isi matriks perbandingan berpasangan untuk menjalankan kalkulasi AHP.</p>
            </div>
            <a href="{{ route('ahp.matrix') }}"
                class="w-full md:w-auto px-6 py-3 bg-gold-500 hover:bg-gold-600 text-slate-900 font-bold rounded-lg shadow-lg shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5 text-center whitespace-nowrap z-10">
                Mulai Perhitungan AHP
            </a>
        </div>
    @else
        <div class="glass-panel p-6 rounded-2xl border border-slate-700 shadow-xl mb-8">
            <div class="flex items-start space-x-4">
                <div class="w-10 h-10 rounded-xl bg-gold-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-md font-bold text-white mb-1">Langkah Awal</h3>
                    <p class="text-sm text-gray-400">Untuk memulai analisis AHP, tambahkan data <strong
                            class="text-white">Alternatif</strong> dan <strong class="text-white">Kriteria</strong> terlebih
                        dahulu melalui menu navigasi di samping.</p>
                </div>
            </div>
        </div>
    @endif
@endsection