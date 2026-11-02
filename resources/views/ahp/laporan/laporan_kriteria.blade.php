@extends('ahp.laporan.report_layout')

@section('title', 'Laporan Kriteria AHP - Synthesis Huis')
@section('doc_title', 'Laporan Kriteria & Bobot AHP')

@section('content')
    @php
        $withSub = $criteria->filter(fn($c) => $c->subCriteria->count() > 0)->count();
        $withoutSub = $criteria->filter(fn($c) => $c->subCriteria->count() === 0)->count();
    @endphp
    <table class="summary-grid">
        <tr>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ $criteria->count() }}</div>
                    <div class="stat-lbl">Total Kriteria</div>
                </div>
            </td>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ $withSub }}</div>
                    <div class="stat-lbl">Kriteria dengan Sub</div>
                </div>
            </td>
            <td>
                <div class="stat-box">
                    <div class="stat-num">{{ number_format($totalWeight, 4) }}</div>
                    <div class="stat-lbl">Total Bobot</div>
                </div>
            </td>
        </tr>
    </table>

    <table>
        <thead>
            <tr>
                <th class="text-center" width="7%">No.</th>
                <th width="25%">Nama Kriteria</th>
                <th class="text-center" width="13%">Bobot (w)</th>
                <th class="text-center" width="27%">Visualisasi Bobot</th>
                <th class="text-center" width="15%">Sub-Kriteria</th>
                <th class="text-center" width="12%">% Bobot</th>
            </tr>
        </thead>
        <tbody>
            @php $maxW = $criteria->max('weight') ?: 1; @endphp
            @foreach($criteria as $i => $c)
                <tr>
                    <td class="text-center">
                        @if($i === 0) 1
                        @else {{ $i + 1 }}
                        @endif
                    </td>
                    <td><strong>{{ $c->name }}</strong></td>
                    <td class="text-right mono">{{ number_format($c->weight ?? 0, 4) }}</td>
                    <td>
                        @php $pct = ($c->weight / $maxW) * 100; @endphp
                        <span class="bar-bg"><span class="bar-fill" style="width:{{ number_format($pct, 1) }}%;"></span></span>
                        <span
                            style="font-size:10px; color:#64748b; font-family:'Courier New',monospace;">&nbsp;{{ number_format($pct, 1) }}%</span>
                    </td>
                    <td class="text-center">
                        @if($c->subCriteria->count() > 0)
                            <span class="badge-has-sub">{{ $c->subCriteria->count() }} Sub</span>
                        @else
                            <span class="badge-no-sub">Mandiri</span>
                        @endif
                    </td>
                    <td class="text-center mono">
                        {{ $totalWeight > 0 ? number_format(($c->weight / $totalWeight) * 100, 1) : '0.0' }}%
                    </td>
                </tr>
            @endforeach
        </tbody>
        <tfoot>
            <tr>
                <td colspan="2"
                    style="text-align:left; font-weight:bold; background:#f8fafc; font-size:11px; color:#64748b; padding:8px 10px; border:1px solid #e2e8f0;">
                    Total
                </td>
                <td class="text-right mono"
                    style="font-weight:bold; background:#f8fafc; border:1px solid #e2e8f0; padding:8px 10px;">
                    {{ number_format($totalWeight, 4) }}
                </td>
                <td colspan="2" style="background:#f8fafc; border:1px solid #e2e8f0; padding:8px 10px;"></td>
                <td class="text-right mono"
                    style="font-weight:bold; background:#f8fafc; border:1px solid #e2e8f0; padding:8px 10px;">100.0%
                </td>
            </tr>
        </tfoot>
    </table>
@endsection