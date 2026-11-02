@extends('ahp.laporan.report_layout')

@section('title', 'Laporan Hasil AHP - Synthesis Huis')
@section('doc_title', 'Laporan Hasil Keputusan AHP')

@section('content')
    <div class="section-title">Tabel Pemeringkatan Akhir Platform Digital Marketing</div>
    <table>
        <thead>
            <tr>
                <th class="text-center" width="8%">Rank</th>
                <th width="35%">Nama Platform</th>
                <th class="text-center" width="18%">Skor Akhir</th>
                <th class="text-center" width="29%">Visualisasi Skor</th>
                <th class="text-center" width="10%">% Skor</th>
            </tr>
        </thead>
        <tbody>
            @php $maxScore = $alternatives->max('final_score') ?: 1; @endphp
            @foreach($alternatives as $i => $alt)
                <tr>
                    <td class="text-center">
                        @if($i === 0) <span class="rank-1">1</span>
                        @elseif($i === 1) <span class="rank-2">2</span>
                        @elseif($i === 2) <span class="rank-3">3</span>
                        @else <span class="rank-n">{{ $i + 1 }}</span>
                        @endif
                    </td>
                    <td>
                        <strong>{{ $alt->name }}</strong>
                    </td>
                    <td class="text-right mono">{{ number_format($alt->final_score ?? 0, 4) }}</td>
                    <td>
                        @php $pct = ($alt->final_score / $maxScore) * 100; @endphp
                        <span class="bar-bg"><span class="bar-fill"
                                style="width:{{ number_format($pct, 1) }}%;"></span></span>
                        <span
                            style="font-size:10px; color:#64748b; font-family:'Courier New',monospace;">&nbsp;{{ number_format($pct, 1) }}%</span>
                    </td>
                    <td class="text-right mono">
                        {{ number_format(($alt->final_score / $alternatives->sum('final_score')) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title"> Rincian Bobot Kriteria &amp; Sub-Kriteria yang Digunakan</div>
    <table>
        <thead>
            <tr>
                <th width="35%">Kriteria Utama</th>
                <th class="text-right" width="18%">Bobot Kriteria</th>
                <th width="30%">Sub-Kriteria (jika ada)</th>
                <th class="text-right" width="17%">Bobot Global</th>
            </tr>
        </thead>
        <tbody>
            @foreach($criteria as $c)
                @if($c->subCriteria->count() > 0)
                    @foreach($c->subCriteria as $si => $sub)
                        <tr>
                            <td>
                                @if($si === 0) <strong>{{ $c->name }}</strong> @endif
                            </td>
                            <td class="text-right mono">
                                @if($si === 0) {{ number_format($c->weight ?? 0, 4) }} @endif
                            </td>
                            <td>{{ $sub->name }}</td>
                            <td class="text-right mono">{{ number_format($sub->global_weight ?? 0, 4) }}</td>
                        </tr>
                    @endforeach
                @else
                    <tr>
                        <td><strong>{{ $c->name }}</strong></td>
                        <td class="text-right mono">{{ number_format($c->weight ?? 0, 4) }}</td>
                        <td style="color:#94a3b8; font-style:italic; font-size:11px;">— Mandiri (tanpa sub-kriteria) —</td>
                        <td class="text-right mono">{{ number_format($c->weight ?? 0, 4) }}</td>
                    </tr>
                @endif
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Detail Kontribusi Bobot per Platform</div>

    @foreach($alternatives as $i => $alt)
        @php $breakdown = $allBreakdowns[$alt->id] ?? []; @endphp
        <table style="margin-bottom:6px;">
            <thead>
                <tr class="alt-header-row">
                    <td colspan="5">
                        @if($i === 0)Rank 1 &nbsp;
                        @elseif($i === 1)Rank 2 &nbsp;
                        @elseif($i === 2)Rank 3 &nbsp;
                        @else Rank {{ $i + 1 }}&nbsp;
                        @endif
                        {{ $alt->name }}
                        <span class="alt-score-span">&nbsp;&mdash; Skor:
                            {{ number_format($alt->final_score ?? 0, 4) }}</span>
                    </td>
                </tr>
                <tr style="background:#334155;">
                    <th style="color:#e2e8f0; font-size:11px;" width="28%">Nama</th>
                    <th style="color:#e2e8f0; font-size:11px;" width="20%">Kriteria</th>
                    <th class="text-center" style="color:#e2e8f0; font-size:11px;" width="10%">Tipe</th>
                    <th class="text-right" style="color:#e2e8f0; font-size:11px;" width="14%">Skor Lokal</th>
                    <th class="text-right" style="color:#e2e8f0; font-size:11px;" width="14%">Kontribusi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($breakdown as $row)
                    <tr>
                        <td><strong>{{ $row['name'] }}</strong></td>
                        <td style="font-size:11px; color:#64748b;">{{ $row['group'] }}</td>
                        <td class="text-center">
                            @if($row['type'] === 'criteria')
                                <span class="badge-kriteria">Kriteria</span>
                            @else
                                <span class="badge-sub">Sub</span>
                            @endif
                        </td>
                        <td class="text-right mono">{{ number_format($row['score'], 4) }}</td>
                        <td class="text-right mono"><strong>{{ number_format($row['contribution'], 4) }}</strong></td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="text-align:center; color:#94a3b8; font-style:italic; padding:12px;">
                            Belum ada data skor untuk platform ini.
                        </td>
                    </tr>
                @endforelse
                @if(count($breakdown) > 0)
                    <tr>
                        <td colspan="4"
                            style="text-align:left; font-weight:bold; font-size:10px; color:#64748b; background:#f1f5f9; border:1px solid #e2e8f0; padding:7px 10px;">
                            Total Skor Akhir
                        </td>
                        <td class="text-right mono"
                            style="font-weight:bold; background:#f1f5f9; border:1px solid #e2e8f0; padding:7px 10px;">
                            {{ number_format($alt->final_score ?? 0, 4) }}
                        </td>
                    </tr>
                @endif
            </tbody>
        </table>
        <div style="height:10px;"></div>
    @endforeach
@endsection