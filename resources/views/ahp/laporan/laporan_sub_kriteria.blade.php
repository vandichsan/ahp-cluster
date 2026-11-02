@extends('ahp.laporan.report_layout')

@section('title', 'Laporan Sub-Kriteria AHP - Synthesis Huis')
@section('doc_title', 'Laporan Sub-Kriteria & Bobot AHP')

@section('content')
    @php
        $totalSub = $criteria->sum(fn($c) => $c->subCriteria->count());
    @endphp
    <table class="summary-grid">
        <tr>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ $criteria->count() }}</div>
                    <div class="stat-lbl">Kriteria Induk</div>
                </div>
            </td>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ $totalSub }}</div>
                    <div class="stat-lbl">Total Sub-Kriteria</div>
                </div>
            </td>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ $criteria->max(fn($c) => $c->subCriteria->count()) }}</div>
                    <div class="stat-lbl">Sub Terbanyak</div>
                </div>
            </td>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ number_format($criteria->sum('weight'), 4) }}</div>
                    <div class="stat-lbl">Total Bobot Kriteria</div>
                </div>
            </td>
        </tr>
    </table>
    @foreach($criteria as $c)
        <table style="margin-bottom: 6px;">
            <thead>
                <tr style="background:#334155;">
                    <th class="text-center" style="color:#94a3b8; font-size:10px;" width="7%">Rank</th>
                    <th style="color:#e2e8f0; font-size:11px;" width="28%">Nama Sub-Kriteria</th>
                    <th class="text-right" style="color:#e2e8f0; font-size:11px;" width="16%">Bobot Lokal</th>
                    <th class="text-right" style="color:#e2e8f0; font-size:11px;" width="11%">% Bobot</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $maxGlobal = $c->subCriteria->max('global_weight') ?: 1;
                    $totalGlobal = $c->subCriteria->sum('global_weight');
                @endphp
                @foreach($c->subCriteria as $si => $sub)
                    <tr class="{{ $si % 2 === 0 ? 'sub-row-odd' : 'sub-row-even' }}">
                        <td class="text-center">
                            @if($si === 0) <span>1</span>
                            @else <span class="badge-rankn">{{ $si + 1 }}</span>
                            @endif
                        </td>
                        <td><strong>{{ $sub->name }}</strong></td>
                        <td class="text-right mono">{{ number_format($sub->local_weight ?? 0, 4) }}</td>
                        <td class="text-right mono">
                            {{ $totalGlobal > 0 ? number_format(($sub->global_weight / $c->weight) * 100, 1) : '0.0' }}%
                        </td>
                    </tr>
                @endforeach
                <tr>
                    <td colspan="3"
                        style="text-align:left; font-weight:bold; font-size:10px; color:#64748b; background:#f1f5f9; border:1px solid #e2e8f0; padding:6px 10px;">
                        Total Bobot
                    </td>
                    <td class="text-right mono"
                        style="font-weight:bold; background:#f1f5f9; border:1px solid #e2e8f0; padding:6px 10px;">
                        100 %
                    </td>
                </tr>
            </tbody>
        </table>

        <div style="height:10px;"></div>
    @endforeach
@endsection