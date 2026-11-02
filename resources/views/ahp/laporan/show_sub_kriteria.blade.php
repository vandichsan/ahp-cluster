@extends('layouts.app')

@section('title', 'Laporan Sub-Kriteria - Synthesis Huis')
@section('header', 'Laporan Sub-Kriteria')

@section('content')

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">Laporan Sub-Kriteria &amp; Bobot AHP</h2>
            <p class="text-sm text-gray-400 mt-1">Sub-kriteria dikelompokkan per kriteria induk, dengan bobot lokal dan
                global masing-masing</p>
        </div>
        <a href="{{ route('laporan.sub_kriteria.pdf') }}"
            class="flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Download PDF
        </a>
    </div>

    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-8">
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Kriteria Induk</p>
            <p class="text-3xl font-bold text-gold-400">{{ $criteria->count() }}</p>
            <p class="text-xs text-gray-400 mt-1">kelompok kriteria</p>
        </div>
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Total Sub Kriteria</p>
            <p class="text-3xl font-bold text-purple-400">{{ $totalSub }}</p>
            <p class="text-xs text-gray-400 mt-1">sub-kriteria</p>
        </div>
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">Sub Terbanyak</p>
            <p class="text-3xl font-bold text-blue-400">{{ $criteria->max(fn($c) => $c->subCriteria->count()) }}</p>
            <p class="text-xs text-gray-400 mt-1">dalam satu kriteria</p>
        </div>
        <div class="glass-panel rounded-2xl border border-slate-800 p-5">
            <p class="text-xs text-gray-500 uppercase tracking-widest mb-1">∑ Bobot Kriteria</p>
            <p class="text-3xl font-bold text-green-400 font-mono">{{ number_format($criteria->sum('weight'), 4) }}</p>
            <p class="text-xs text-gray-400 mt-1">≈ total bobot AHP</p>
        </div>
    </div>

    @foreach($criteria as $ci => $c)
        @php $maxGlobal = $c->subCriteria->max('global_weight') ?: 1; @endphp

        <div class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden mb-6">
            <div class="flex items-center gap-4 px-6 py-4 bg-slate-800/40 border-b border-slate-700/50">
                <div
                    class="w-10 h-10 rounded-xl bg-gold-500/15 border border-gold-500/30 flex items-center justify-center flex-shrink-0">
                    <span class="text-sm font-bold text-gold-400">{{ $ci + 1 }}</span>
                </div>
                <div class="flex-1">
                    <h4 class="font-bold text-white text-base">{{ $c->name }}</h4>
                    <p class="text-xs text-gray-400">{{ $c->subCriteria->count() }} sub-kriteria</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Bobot Kriteria</p>
                    <p class="font-mono font-bold text-gold-400 text-lg">{{ number_format($c->weight ?? 0, 4) }}</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse text-sm">
                    <thead>
                        <tr class="text-xs font-semibold text-gray-400 uppercase border-b border-slate-800 bg-slate-800/20">
                            <th class="py-3 px-4 text-center w-14">Rank</th>
                            <th class="py-3 px-4">Nama Sub-Kriteria</th>
                            <th class="py-3 px-4 text-right">Bobot Lokal</th>
                            <th class="py-3 px-4 text-right">% dari Kriteria</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/40">
                        @foreach($c->subCriteria as $si => $sub)
                            @php
                                $pct = ($sub->global_weight / $maxGlobal) * 100;
                                $pctOf = ($c->weight > 0) ? ($sub->global_weight / $c->weight) * 100 : 0;
                            @endphp
                            <tr class="hover:bg-slate-800/20 transition-colors">
                                <td class="py-3.5 px-4 text-center">
                                    @if($si === 0)
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gold-500 text-slate-900 font-bold text-xs shadow shadow-gold-500/30">1</span>
                                    @elseif($si === 1)
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-400 text-slate-900 font-bold text-xs">2</span>
                                    @elseif($si === 2)
                                        <span
                                            class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-700 text-white font-bold text-xs">3</span>
                                    @else
                                        <span class="text-gray-500 text-sm">{{ $si + 1 }}</span>
                                    @endif
                                </td>
                                <td class="py-3.5 px-4">
                                    <p class="font-medium text-white">{{ $sub->name }}</p>
                                    @if($si === 0)
                                    <p class="text-xs text-gold-500 mt-0.5"></p> @endif
                                </td>
                                <td class="py-3.5 px-4 text-right font-mono text-gray-300 text-xs">
                                    {{ number_format($sub->local_weight ?? 0, 4) }}
                                </td>


                                <td class="py-3.5 px-4 text-right font-mono text-sm text-gray-300">
                                    {{ number_format($pctOf, 1) }}%
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr class="border-t border-slate-700 bg-slate-800/30">
                            <td colspan="3" class="py-3 px-4 text-right text-xs font-semibold text-gray-400">
                                Total
                            </td>
                            <td class="py-3 px-4 text-right font-mono font-bold text-green-400">
                                {{ number_format($c->subCriteria->sum('global_weight'), 4) }}
                            </td>
                            <td colspan="2" class="py-3 px-4 text-xs text-gray-500">
                                ≈ Bobot kriteria: {{ number_format($c->weight ?? 0, 4) }}
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    @endforeach

    <div class="flex justify-center mt-4 mb-8">
        <a href="{{ route('laporan.sub_kriteria.pdf') }}"
            class="flex items-center gap-2 px-8 py-3.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Download Laporan PDF
        </a>
    </div>

@endsection