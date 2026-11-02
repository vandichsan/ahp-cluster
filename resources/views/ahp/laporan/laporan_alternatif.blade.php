@extends('ahp.laporan.report_layout')

@section('title', 'Laporan Digital Marketing - Synthesis Huis')
@section('doc_title', 'Laporan Digital Marketing — Alternatif')

@section('content')
    <table>
        <thead>
            <tr>
                <th class="text-center" width="8%">Rank</th>
                <th width="50%">Nama Platform</th>
                <th class="text-right" width="22%">Skor Akhir AHP</th>
                <th class="text-right" width="20%">% dari Tertinggi</th>
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
                    <td><strong>{{ $alt->name }}</strong></td>
                    <td class="text-right mono">{{ number_format($alt->final_score ?? 0, 4) }}</td>
                    <td class="text-right mono">{{ number_format(($alt->final_score / $maxScore) * 100, 1) }}%</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection