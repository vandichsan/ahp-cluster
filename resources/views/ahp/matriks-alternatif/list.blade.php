@extends('layouts.app')

@section('title', 'Penilaian Alternatif - Synthesis Huis')
@section('header', 'Matriks Perbandingan Alternatif')

@section('content')
<div class="max-w-5xl mx-auto space-y-6">
    <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-8 -mr-8 w-40 h-40 bg-gold-500/10 rounded-full blur-3xl"></div>
        <div class="absolute bottom-0 left-0 -mb-6 -ml-6 w-24 h-24 bg-gold-500/5 rounded-full blur-2xl"></div>
        <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-3">
                <div class="w-10 h-10 rounded-xl bg-gold-500/10 border border-gold-500/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white">Penilaian Alternatif (AHP Konvensional)</h2>
                    <p class="text-xs text-gray-500">Perbandingan berpasangan antar alternatif terhadap setiap kriteria/sub-kriteria</p>
                </div>
            </div>
            <p class="text-gray-400 text-sm leading-relaxed">
                Untuk setiap <strong class="text-white">kriteria tunggal</strong> (tanpa sub-kriteria) dan setiap <strong class="text-gold-400">sub-kriteria</strong>, Anda perlu mengisi matriks perbandingan berpasangan antar alternatif. Bobot prioritas lokal akan dihitung secara otomatis menggunakan metode <strong class="text-white">Eigenvector</strong>.
            </p>
        </div>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-3">
        <div class="glass-panel p-4 rounded-xl border border-slate-800 text-center">
            <p class="text-2xl font-bold text-gold-400">{{ $alternatives->count() }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Alternatif</p>
        </div>
        <div class="glass-panel p-4 rounded-xl border border-slate-800 text-center">
            <p class="text-2xl font-bold text-white">{{ $standaloneCriteria->count() }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Kriteria Tunggal</p>
        </div>
        <div class="glass-panel p-4 rounded-xl border border-slate-800 text-center">
            <p class="text-2xl font-bold text-white">{{ $allSubCriteria->count() }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Sub-Kriteria</p>
        </div>
        <div class="glass-panel p-4 rounded-xl border border-slate-800 text-center">
            <p class="text-2xl font-bold text-emerald-400">{{ $completedCount }}</p>
            <p class="text-[10px] text-gray-500 uppercase tracking-widest mt-1">Sudah Dinilai</p>
        </div>
    </div>

    @if($alternatives->count() < 2)
        <div class="glass-panel p-8 rounded-2xl border border-slate-800 text-center">
            <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-800/50 border border-slate-700 flex items-center justify-center">
                <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path></svg>
            </div>
            <h3 class="text-lg font-semibold text-white mb-2">Minimal 2 Alternatif Diperlukan</h3>
            <p class="text-sm text-gray-400 mb-4">Tambahkan alternatif terlebih dahulu sebelum melakukan penilaian perbandingan.</p>
            <a href="{{ route('data.alternatives') }}" class="inline-flex items-center px-5 py-2.5 bg-gold-500/10 border border-gold-500/30 text-gold-400 font-semibold text-sm rounded-lg hover:bg-gold-500/20 transition-all">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                Kelola Alternatif
            </a>
        </div>
    @else
        @if($standaloneCriteria->isNotEmpty())
            <div class="space-y-3">
                <div class="flex items-center space-x-3 px-1">
                    <div class="w-1.5 h-6 rounded-full bg-gold-500"></div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest">Kriteria Tunggal</h3>
                    <span class="text-[10px] text-gray-500">(tanpa sub-kriteria)</span>
                </div>

                @foreach($standaloneCriteria as $crit)
                    @php
                        $isDone = $completedNodes->contains(fn($n) => $n->node_type === 'criteria' && $n->node_id == $crit->id);
                    @endphp
                    <div class="glass-panel rounded-2xl border {{ $isDone ? 'border-emerald-500/30' : 'border-slate-800' }} shadow-xl overflow-hidden group hover:border-gold-500/30 transition-all duration-300">
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-11 h-11 rounded-xl {{ $isDone ? 'bg-emerald-500/20 border-emerald-500/30' : 'bg-gradient-to-br from-gold-500/20 to-gold-500/5 border-gold-500/20' }} border flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    @if($isDone)
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white group-hover:text-gold-400 transition-colors">{{ $crit->name }}</h4>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="inline-flex items-center text-[11px] text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                            Kriteria Utama
                                        </span>
                                        @if($crit->weight)
                                            <span class="text-[11px] text-gold-400 font-mono">w={{ number_format($crit->weight, 4) }}</span>
                                        @endif
                                        @if($isDone)
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-full">✓ Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('alt_matrix.form', ['type' => 'criteria', 'id' => $crit->id]) }}"
                               class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-500 text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-gold-500/30 group/btn whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Input Penilaian Alternatif
                                <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($allSubCriteria->isNotEmpty())
            <div class="space-y-3 mt-2">
                <div class="flex items-center space-x-3 px-1">
                    <div class="w-1.5 h-6 rounded-full bg-gold-400"></div>
                    <h3 class="text-sm font-bold text-white uppercase tracking-widest">Sub-Kriteria</h3>
                    <span class="text-[10px] text-gray-500">(dari kriteria yang memiliki anak)</span>
                </div>

                @foreach($allSubCriteria as $sub)
                    @php
                        $isDone = $completedNodes->contains(fn($n) => $n->node_type === 'sub_criteria' && $n->node_id == $sub->id);
                    @endphp
                    <div class="glass-panel rounded-2xl border {{ $isDone ? 'border-emerald-500/30' : 'border-slate-800' }} shadow-xl overflow-hidden group hover:border-gold-500/30 transition-all duration-300">
                        <div class="p-5 flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
                            <div class="flex items-center space-x-4">
                                <div class="w-11 h-11 rounded-xl {{ $isDone ? 'bg-emerald-500/20 border-emerald-500/30' : 'bg-slate-800/50 border-slate-700' }} border flex items-center justify-center group-hover:scale-105 transition-transform duration-300">
                                    @if($isDone)
                                        <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    @else
                                        <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path></svg>
                                    @endif
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-white group-hover:text-gold-400 transition-colors">{{ $sub->name }}</h4>
                                    <div class="flex items-center space-x-3 mt-1">
                                        <span class="inline-flex items-center text-[11px] text-gray-500">
                                            <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 9l3 3m0 0l-3 3m3-3H8m13 0a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $sub->criteria->name }}
                                        </span>
                                        @if($sub->local_weight)
                                            <span class="text-[11px] text-gold-400 font-mono">lw={{ number_format($sub->local_weight, 4) }}</span>
                                        @endif
                                        @if($isDone)
                                            <span class="px-2 py-0.5 text-[10px] font-bold text-emerald-400 bg-emerald-500/10 border border-emerald-500/30 rounded-full">✓ Selesai</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <a href="{{ route('alt_matrix.form', ['type' => 'sub_criteria', 'id' => $sub->id]) }}"
                               class="inline-flex items-center px-5 py-3 bg-gradient-to-r from-gold-500 to-gold-600 hover:from-gold-600 hover:to-gold-500 text-slate-900 font-bold text-sm rounded-xl shadow-lg shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5 hover:shadow-xl hover:shadow-gold-500/30 group/btn whitespace-nowrap">
                                <svg class="w-4 h-4 mr-2 transition-transform group-hover/btn:rotate-12" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                Input Penilaian Alternatif
                                <svg class="w-4 h-4 ml-2 transition-transform group-hover/btn:translate-x-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        @if($standaloneCriteria->isEmpty() && $allSubCriteria->isEmpty())
            <div class="glass-panel p-8 rounded-2xl border border-slate-800 text-center">
                <div class="w-16 h-16 mx-auto mb-4 rounded-2xl bg-slate-800/50 border border-slate-700 flex items-center justify-center">
                    <svg class="w-8 h-8 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <h3 class="text-lg font-semibold text-white mb-2">Tidak Ada Kriteria / Sub-Kriteria</h3>
                <p class="text-sm text-gray-400 mb-4">Tambahkan kriteria dan sub-kriteria terlebih dahulu.</p>
                <a href="{{ route('data.criteria') }}" class="inline-flex items-center px-5 py-2.5 bg-gold-500/10 border border-gold-500/30 text-gold-400 font-semibold text-sm rounded-lg hover:bg-gold-500/20 transition-all">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                    Kelola Kriteria
                </a>
            </div>
        @endif

        @if($totalNodes > 0 && $completedCount === $totalNodes)
            <div class="glass-panel p-5 rounded-xl border border-emerald-500/30 shadow-xl bg-emerald-500/5">
                <div class="flex flex-col sm:flex-row items-center justify-between gap-4">
                    <div class="flex items-start space-x-3">
                        <div class="w-10 h-10 rounded-xl bg-emerald-500/20 border border-emerald-500/30 flex items-center justify-center flex-shrink-0">
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-emerald-400">Semua Penilaian Selesai!</p>
                            <p class="text-xs text-gray-400 mt-0.5">Klik tombol di samping untuk menghitung skor akhir dan membuat peringkat alternatif secara konvensional.</p>
                        </div>
                    </div>
                    <form action="{{ route('ahp.finalRanking') }}" method="POST" class="flex-shrink-0">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-6 py-3.5 bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-sm rounded-xl shadow-lg shadow-emerald-500/20 transition-all duration-300 transform hover:-translate-y-0.5 whitespace-nowrap">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                            Hitung Skor Akhir & Peringkat
                        </button>
                    </form>
                </div>
            </div>
        @endif
    @endif

    <div class="glass-panel p-4 rounded-xl border border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-3">
        <a href="{{ route('ahp.matrix') }}" class="flex items-center text-sm text-gray-400 hover:text-gold-400 transition-colors">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali ke Kuesioner AHP Kriteria
        </a>
        <div class="flex items-center space-x-2">
            <div class="w-2 h-2 rounded-full {{ $completedCount === $totalNodes && $totalNodes > 0 ? 'bg-emerald-500' : 'bg-gold-500/50 animate-pulse' }}"></div>
            <span class="text-xs text-gray-500">{{ $completedCount }}/{{ $totalNodes }} penilaian selesai</span>
        </div>
    </div>
</div>
@endsection
