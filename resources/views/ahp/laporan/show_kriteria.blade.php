@extends('layouts.app')

@section('title', 'Laporan Kriteria - Synthesis Huis')
@section('header', 'Laporan Kriteria')

@section('content')
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">Laporan Kriteria &amp; Bobot AHP</h2>
            <p class="text-sm text-gray-400 mt-1">Daftar seluruh kriteria beserta bobot prioritas hasil kalkulasi AHP</p>
        </div>
        <a href="{{ route('laporan.kriteria.pdf') }}"
            class="flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Download PDF
        </a>
    </div>
    @php
        $withSub = $criteria->filter(fn($c) => $c->subCriteria->count() > 0)->count();
        $withoutSub = $criteria->filter(fn($c) => $c->subCriteria->count() === 0)->count();
        $topCrit = $criteria->first();
    @endphp
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Kriteria</p>
            <p class="text-3xl font-bold text-gold-400">{{ $criteria->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">kriteria evaluasi</p>
        </div>
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Dengan Sub</p>
            <p class="text-3xl font-bold text-blue-400">{{ $withSub }}</p>
            <p class="text-xs text-gray-400 mt-1">memiliki sub-kriteria</p>
        </div>
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Mandiri</p>
            <p class="text-3xl font-bold text-purple-400">{{ $withoutSub }}</p>
            <p class="text-xs text-gray-400 mt-1">tanpa sub-kriteria</p>
        </div>
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Bobot</p>
            <p class="text-3xl font-bold text-green-400 font-mono">{{ number_format($totalWeight, 4) }}</p>
            <p class="text-xs text-gray-400 mt-1">≈ 1.0000 (valid)</p>
        </div>
    </div>

    <div class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden mb-8">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-blue-500/15 flex items-center justify-center">
                <svg class="w-4 h-4 text-blue-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Daftar Kriteria &amp; Bobot Prioritas</h3>
                <p class="text-xs text-gray-400">Eigenvector normalization — diurutkan dari bobot tertinggi</p>
            </div>
        </div>

        @php $maxW = $criteria->max('weight') ?: 1; @endphp
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="text-xs font-semibold text-gray-400 uppercase border-b border-slate-800 bg-slate-800/20">
                        <th class="py-3 px-4 text-center w-14">Rank</th>
                        <th class="py-3 px-4">Nama Kriteria</th>
                        <th class="py-3 px-4 text-right">Bobot</th>
                        <th class="py-3 px-4">Visualisasi Bobot</th>
                        <th class="py-3 px-4 text-center">Sub-Kriteria</th>
                        <th class="py-3 px-4 text-right">% Kontribusi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @foreach($criteria as $i => $c)
                        @php $pct = ($c->weight / $maxW) * 100;
                        $pctOf = $totalWeight > 0 ? ($c->weight / $totalWeight) * 100 : 0; @endphp
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="py-3.5 px-4 text-center">
                                @if($i === 0)
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gold-500 text-slate-900 font-bold text-xs shadow shadow-gold-500/30">1</span>
                                @elseif($i === 1)
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-400 text-slate-900 font-bold text-xs">2</span>
                                @elseif($i === 2)
                                    <span
                                        class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-700 text-white font-bold text-xs">3</span>
                                @else
                                    <span class="text-gray-500 text-sm">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-white">{{ $c->name }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-gold-400">
                                {{ number_format($c->weight ?? 0, 4) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="flex items-center gap-2">
                                    <div class="flex-1 h-2 bg-slate-700 rounded-full overflow-hidden max-w-[120px]">
                                        <div class="h-full rounded-full transition-all"
                                            style="width:{{ number_format($pct, 1) }}%; background: linear-gradient(90deg, #D4AF37, #C5A880);">
                                        </div>
                                    </div>
                                    <span class="text-xs font-mono text-gray-400">{{ number_format($pct, 1) }}%</span>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-center">
                                @if($c->subCriteria->count() > 0)
                                    <span
                                        class="px-2 py-0.5 text-xs rounded-full bg-blue-500/15 text-blue-300 border border-blue-500/30">
                                        {{ $c->subCriteria->count() }} Sub
                                    </span>
                                @else
                                    <span class="px-2 py-0.5 text-xs rounded-full bg-slate-700 text-gray-400">Mandiri</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-sm text-gray-300">
                                {{ number_format($pctOf, 1) }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr class="border-t border-slate-700 bg-slate-800/30">
                        <td colspan="2" class="py-3 px-4 text-right text-xs font-semibold text-gray-400">Total</td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-green-400">
                            {{ number_format($totalWeight, 4) }}</td>
                        <td colspan="2"></td>
                        <td class="py-3 px-4 text-right font-mono font-bold text-green-400">100.0%</td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>
    <div class="flex justify-center mt-4 mb-8">
        <a href="{{ route('laporan.kriteria.pdf') }}"
            class="flex items-center gap-2 px-8 py-3.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Download Laporan PDF
        </a>
    </div>

@endsection