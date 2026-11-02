@extends('layouts.app')

@section('title', 'Laporan Hasil AHP - Synthesis Huis')
@section('header', 'Laporan Hasil Keputusan')

@section('content')

<div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
    <div>
        <h2 class="text-2xl font-bold text-white">Laporan Hasil Keputusan AHP</h2>
        <p class="text-sm text-gray-400 mt-1">Perankingan akhir platform digital marketing berdasarkan kalkulasi AHP komprehensif</p>
    </div>
    <a href="{{ route('laporan.hasil.pdf') }}"
       class="flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5 flex-shrink-0">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Download PDF
    </a>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
    <div class="lg:col-span-2 glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gold-500/15 flex items-center justify-center">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Tabel Perankingan Akhir</h3>
                <p class="text-xs text-gray-400">Semua {{ $alternatives->count() }} platform terurut dari skor tertinggi</p>
            </div>
        </div>

        @php $maxScore = $alternatives->max('final_score') ?: 1; $totalScore = $alternatives->sum('final_score'); @endphp
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse text-sm">
                <thead>
                    <tr class="text-xs font-semibold text-gray-400 uppercase border-b border-slate-800 bg-slate-800/20">
                        <th class="py-3 px-4 text-center w-14">Rank</th>
                        <th class="py-3 px-4">Platform</th>
                        <th class="py-3 px-4 text-right">Skor Akhir</th>
                        <th class="py-3 px-4">Distribusi</th>
                        <th class="py-3 px-4 text-right">% Skor</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-800/40">
                    @foreach($alternatives as $i => $alt)
                        @php $pct = ($alt->final_score / $maxScore) * 100; @endphp
                        <tr class="hover:bg-slate-800/20 transition-colors">
                            <td class="py-3.5 px-4 text-center">
                                @if($i === 0)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-gold-500 text-slate-900 font-bold text-xs shadow shadow-gold-500/30">1</span>
                                @elseif($i === 1)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-slate-400 text-slate-900 font-bold text-xs">2</span>
                                @elseif($i === 2)
                                    <span class="inline-flex items-center justify-center w-6 h-6 rounded-full bg-amber-700 text-white font-bold text-xs">3</span>
                                @else
                                    <span class="text-gray-500 text-sm">{{ $i + 1 }}</span>
                                @endif
                            </td>
                            <td class="py-3.5 px-4">
                                <p class="font-semibold text-white text-sm">{{ $alt->name }}</p>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono font-bold text-gold-400">
                                {{ number_format($alt->final_score ?? 0, 4) }}
                            </td>
                            <td class="py-3.5 px-4">
                                <div class="w-full h-2 bg-slate-700 rounded-full overflow-hidden max-w-[100px]">
                                    <div class="h-full bg-gold-500 rounded-full" style="width:{{ number_format($pct,1) }}%"></div>
                                </div>
                            </td>
                            <td class="py-3.5 px-4 text-right font-mono text-sm text-gray-300">
                                {{ $totalScore > 0 ? number_format(($alt->final_score / $totalScore) * 100, 1) : '0.0' }}%
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-purple-500/15 flex items-center justify-center">
                <svg class="w-4 h-4 text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"/>
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Bobot Kriteria</h3>
                <p class="text-xs text-gray-400">Digunakan dalam kalkulasi</p>
            </div>
        </div>
        <div class="p-4 space-y-3">
            @php $maxCritW = $criteria->max('weight') ?: 1; @endphp
            @foreach($criteria as $c)
                @php $critPct = ($c->weight / $maxCritW) * 100; @endphp
                <div>
                    <div class="flex items-center justify-between mb-1">
                        <p class="text-xs font-medium text-gray-300">{{ $c->name }}</p>
                        <span class="text-xs font-mono text-gold-400">{{ number_format($c->weight ?? 0, 4) }}</span>
                    </div>
                    <div class="h-1.5 bg-slate-700 rounded-full overflow-hidden">
                        <div class="h-full rounded-full bg-purple-500" style="width:{{ number_format($critPct,1) }}%"></div>
                    </div>
                    @if($c->subCriteria->count() > 0)
                        <p class="text-xs text-gray-600 mt-0.5">{{ $c->subCriteria->count() }} sub-kriteria</p>
                    @endif
                </div>
            @endforeach
        </div>
    </div>
</div>

<div class="mb-4">
    <h3 class="text-lg font-bold text-white">Detail Kontribusi Bobot per Platform</h3>
    <p class="text-sm text-gray-400 mt-1">Pilih platform untuk melihat breakdown kontribusi node-nya</p>
</div>
<div class="flex flex-wrap gap-2 mb-4" id="alt-tabs">
    @foreach($alternatives as $i => $alt)
        <button onclick="showAltTab({{ $alt->id }})"
                id="tab-{{ $alt->id }}"
                class="px-4 py-2 text-sm font-medium rounded-lg transition-all border
                       {{ $i === 0
                           ? 'bg-gold-500/15 text-gold-400 border-gold-500/40'
                           : 'text-gray-400 border-slate-700 hover:bg-slate-800 hover:text-white' }}">
            {{ $alt->name }}
        </button>
    @endforeach
</div>

@foreach($alternatives as $i => $alt)
    @php $breakdown = $allBreakdowns[$alt->id] ?? []; @endphp
    <div id="panel-{{ $alt->id }}" class="{{ $i !== 0 ? 'hidden' : '' }}">
        <div class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden mb-8">
            <div class="flex items-center gap-4 px-6 py-4 bg-slate-800/40 border-b border-slate-700/50">
                @if($i === 0)
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-gold-500 text-slate-900 font-bold text-sm shadow shadow-gold-500/30">1</span>
                @elseif($i === 1)
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-400 text-slate-900 font-bold text-sm">2</span>
                @elseif($i === 2)
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-amber-700 text-white font-bold text-sm">3</span>
                @else
                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-slate-700 text-gray-300 font-bold text-sm">{{ $i+1 }}</span>
                @endif
                <div class="flex-1">
                    <h4 class="font-bold text-white">{{ $alt->name }}</h4>
                    <p class="text-xs text-gray-400">{{ count($breakdown) }} node berkontribusi</p>
                </div>
                <div class="text-right">
                    <p class="text-xs text-gray-500">Skor Akhir</p>
                    <p class="font-mono font-bold text-gold-400 text-lg">{{ number_format($alt->final_score ?? 0, 4) }}</p>
                </div>
            </div>

            @if(count($breakdown) > 0)
                @php $maxC = collect($breakdown)->max('contribution') ?: 1; @endphp
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse text-sm">
                        <thead>
                            <tr class="text-xs font-semibold text-gray-400 uppercase border-b border-slate-800 bg-slate-800/20">
                                <th class="py-3 px-4">Nama Kriteria</th>
                                <th class="py-3 px-4">Kelompok</th>
                                <th class="py-3 px-4 text-center">Tipe</th>
                                <th class="py-3 px-4 text-right">Skor Lokal</th>
                                <th class="py-3 px-4 text-right">Kontribusi</th>
                                <th class="py-3 px-4 text-center w-28">Bar</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-800/40">
                            @foreach($breakdown as $row)
                                @php $pct = ($row['contribution'] / $maxC) * 100; @endphp
                                <tr class="hover:bg-slate-800/20 transition-colors">
                                    <td class="py-3 px-4 font-medium text-white">{{ $row['name'] }}</td>
                                    <td class="py-3 px-4 text-xs text-gray-400">{{ $row['group'] }}</td>
                                    <td class="py-3 px-4 text-center">
                                        @if($row['type'] === 'criteria')
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-blue-500/15 text-blue-300 border border-blue-500/30">Kriteria</span>
                                        @else
                                            <span class="px-2 py-0.5 text-xs rounded-full bg-purple-500/15 text-purple-300 border border-purple-500/30">Sub</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-4 text-right font-mono text-gray-300 text-xs">{{ number_format($row['score'], 4) }}</td>
                                    <td class="py-3 px-4 text-right font-mono font-bold text-gold-400 text-xs">{{ number_format($row['contribution'], 4) }}</td>
                                    <td class="py-3 px-4">
                                        <div class="w-full h-1.5 bg-slate-700 rounded-full overflow-hidden">
                                            <div class="h-full bg-gold-500 rounded-full" style="width:{{ number_format($pct,1) }}%"></div>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot>
                            <tr class="border-t border-slate-700 bg-slate-800/30">
                                <td colspan="4" class="py-3 px-4 text-right text-xs font-semibold text-gray-400">Total Skor Akhir</td>
                                <td class="py-3 px-4 text-right font-mono font-bold text-gold-400">{{ number_format($alt->final_score ?? 0, 4) }}</td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            @else
                <div class="px-6 py-8 text-center text-sm text-gray-500 italic">
                    Belum ada data skor untuk platform ini.
                </div>
            @endif
        </div>
    </div>
@endforeach

<div class="flex justify-center mt-4 mb-8">
    <a href="{{ route('laporan.hasil.pdf') }}"
       class="flex items-center gap-2 px-8 py-3.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                  d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
        </svg>
        Download Laporan PDF
    </a>
</div>

@endsection

@push('scripts')
<script>
const altIds = {!! json_encode($alternatives->pluck('id')) !!};

function showAltTab(id) {
    altIds.forEach(aid => {
        const panel = document.getElementById('panel-' + aid);
        const tab   = document.getElementById('tab-' + aid);
        if (panel) panel.classList.add('hidden');
        if (tab) {
            tab.classList.remove('bg-gold-500/15', 'text-gold-400', 'border-gold-500/40');
            tab.classList.add('text-gray-400', 'border-slate-700');
        }
    });
    const activePanel = document.getElementById('panel-' + id);
    const activeTab   = document.getElementById('tab-' + id);
    if (activePanel) activePanel.classList.remove('hidden');
    if (activeTab) {
        activeTab.classList.add('bg-gold-500/15', 'text-gold-400', 'border-gold-500/40');
        activeTab.classList.remove('text-gray-400', 'border-slate-700');
    }
}
</script>
@endpush
