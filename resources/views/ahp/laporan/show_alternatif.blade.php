@extends('layouts.app')

@section('title', 'Laporan Digital Marketing - Synthesis Huis')
@section('header', 'Laporan Digital Marketing')

@section('content')

    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 mb-8">
        <div>
            <h2 class="text-2xl font-bold text-white">Laporan Alternatif &amp; Bobot Prioritas</h2>
            <p class="text-sm text-gray-400 mt-1">Ringkasan perankingan dan breakdown kontribusi bobot AHP per platform
                digital marketing</p>
        </div>
        <a href="{{ route('laporan.alternatif.pdf') }}" id="btn-download"
            class="flex items-center gap-2 px-5 py-3 bg-red-600 hover:bg-red-500 text-white font-semibold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5 flex-shrink-0">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Download PDF
        </a>
    </div>

    <div class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden mb-8">
        <div class="flex items-center gap-3 px-6 py-4 border-b border-slate-800">
            <div class="w-8 h-8 rounded-lg bg-gold-500/15 flex items-center justify-center">
                <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                </svg>
            </div>
            <div>
                <h3 class="text-base font-bold text-white">Ringkasan Peringkat Platform</h3>
                <p class="text-xs text-gray-400">Diurutkan berdasarkan skor akhir AHP tertinggi</p>
            </div>
            <span
                class="ml-auto px-3 py-1 text-xs font-semibold text-gold-400 bg-gold-500/10 border border-gold-500/30 rounded-full">
                {{ $alternatives->count() }} Platform
            </span>
        </div>

        @php $maxScore = $alternatives->max('final_score') ?: 1; @endphp
        <div class="divide-y divide-slate-800/50">
            @forelse($alternatives as $i => $alt)
                <div class="flex items-center gap-4 px-6 py-4 hover:bg-slate-800/20 transition-colors cursor-pointer"
                    onclick="scrollToAlt('alt-{{ $alt->id }}')">
                    <div class="w-8 flex-shrink-0 text-center">
                        @if($i === 0)
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-gold-500 text-slate-900 font-bold text-xs shadow shadow-gold-500/30">1</span>
                        @elseif($i === 1)
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-slate-400 text-slate-900 font-bold text-xs">2</span>
                        @elseif($i === 2)
                            <span
                                class="inline-flex items-center justify-center w-7 h-7 rounded-full bg-amber-700 text-white font-bold text-xs">3</span>
                        @else
                            <span class="text-gray-500 text-sm font-semibold">{{ $i + 1 }}</span>
                        @endif
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-white text-sm {{ $i === 0 ? 'text-gold-400' : '' }}">
                            {{ $alt->name }}
                        </p>
                        <p class="text-xs text-gray-500 mt-0.5">
                            {{ count($allBreakdowns[$alt->id] ?? []) }} node terevaluasi
                        </p>
                    </div>
                    <div class="flex items-center gap-3 flex-shrink-0">
                        <div class="w-28 h-1.5 bg-slate-700 rounded-full overflow-hidden">
                            <div class="h-full bg-gold-500 rounded-full transition-all"
                                style="width: {{ ($alt->final_score / $maxScore) * 100 }}%"></div>
                        </div>
                        <span class="font-mono text-sm font-bold text-gold-400 w-16 text-right">
                            {{ number_format($alt->final_score ?? 0, 4) }}
                        </span>
                    </div>
                    <svg class="w-4 h-4 text-gray-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </div>
            @empty
                <div class="px-6 py-10 text-center text-sm text-gray-500">Belum ada data alternatif.</div>
            @endforelse
        </div>
    </div>

    <div class="flex justify-center mt-4 mb-8">
        <a href="{{ route('laporan.alternatif.pdf') }}"
            class="flex items-center gap-2 px-8 py-3.5 bg-red-600 hover:bg-red-500 text-white font-bold rounded-xl shadow-lg shadow-red-600/20 transition-all duration-200 hover:-translate-y-0.5">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z" />
            </svg>
            Download Laporan PDF
        </a>
    </div>

@endsection

@push('scripts')
    <script>
        function scrollToAlt(id) {
            const el = document.getElementById(id);
            if (el) {
                el.scrollIntoView({ behavior: 'smooth', block: 'start' });
                el.classList.add('ring-2', 'ring-gold-500/50');
                setTimeout(() => el.classList.remove('ring-2', 'ring-gold-500/50'), 2000);
            }
        }
    </script>
@endpush