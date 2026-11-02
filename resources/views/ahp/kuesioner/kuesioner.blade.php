@extends('layouts.app')

@section('title', 'Kuesioner Matriks AHP - Synthesis Huis')
@section('header', 'Matriks Perbandingan Berpasangan')

@section('content')
<div class="max-w-6xl mx-auto space-y-6">

    @if(session('cr_error'))
        <div class="bg-red-900/50 border border-red-500 text-red-200 p-4 rounded-lg mb-6">
            ⚠️ {{ session('cr_error') }}
        </div>
    @endif

    @if(session()->has('preview_cr'))
        <div class="glass-panel p-6 rounded-2xl border {{ session('preview_cr') <= 0.1 ? 'border-green-500' : 'border-red-500' }} shadow-2xl mb-6">
            <h3 class="text-lg font-bold {{ session('preview_cr') <= 0.1 ? 'text-green-400' : 'text-red-400' }} mb-4">
                Hasil Kalkulasi Bobot Kriteria (CR = {{ number_format(session('preview_cr'), 4) }})
            </h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                @foreach($criteria as $c)
                    <div class="p-3 bg-slate-900/50 border border-slate-700 rounded-lg text-center">
                        <p class="text-xs text-gray-400 mb-1">{{ $c->name }}</p>
                        <p class="text-xl font-bold text-white">{{ number_format(session('preview_weights')[$c->id] ?? 0, 4) }}</p>
                    </div>
                @endforeach
            </div>
        </div>
    @endif

    <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-2xl relative overflow-hidden">
        <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-gold-500/10 rounded-full blur-3xl"></div>
        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative z-10">
            <div>
                <h2 class="text-xl font-bold text-white mb-1">Matriks Perbandingan Kriteria AHP</h2>
                <p class="text-gray-400 text-sm leading-relaxed">
                    Isikan nilai perbandingan pada <strong class="text-white">segitiga atas</strong> matriks. Nilai kebalikan (1/x) pada segitiga bawah akan terisi <strong class="text-gold-400">otomatis</strong>.
                </p>
            </div>
            <div class="flex-shrink-0">
                <span class="px-4 py-2 text-xs font-bold text-gold-400 bg-gold-500/10 border border-gold-500/30 rounded-full">
                    {{ count($criteria) }} Kriteria &bull; {{ count($criteria) * (count($criteria) - 1) / 2 }} Perbandingan
                </span>
            </div>
        </div>
    </div>

    <div class="glass-panel p-5 rounded-xl border border-slate-800">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Skala Saaty</p>
        <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
            <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                <span class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">1</span>
                <span class="text-gray-300">Sama Penting</span>
            </div>
            <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                <span class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">3</span>
                <span class="text-gray-300">Sedikit Lebih Penting</span>
            </div>
            <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                <span class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">5</span>
                <span class="text-gray-300">Jelas Lebih Penting</span>
            </div>
            <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                <span class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">7</span>
                <span class="text-gray-300">Sangat Jelas Penting</span>
            </div>
            <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                <span class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">9</span>
                <span class="text-gray-300">Mutlak Lebih Penting</span>
            </div>
        </div>
        <p class="text-[11px] text-gray-500 mt-2">Nilai 2, 4, 6, 8 merupakan nilai tengah di antara dua skala penilaian berturut-turut.</p>
    </div>

    <form action="{{ route('ahp.calculate') }}" method="POST">
        @csrf

        <div class="glass-panel rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full border-collapse min-w-[700px]">
                    <thead>
                        <tr>
                            <th class="px-3 py-4 text-left bg-slate-900/80 border-b border-r border-slate-700/50 sticky left-0 z-10">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Kriteria</span>
                            </th>
                            @foreach($criteria as $cCol)
                                <th class="px-2 py-4 text-center bg-slate-900/80 border-b border-r border-slate-700/50 min-w-[110px]">
                                    <span class="text-[11px] font-semibold text-gold-400 leading-tight block">{{ $cCol->name }}</span>
                                </th>
                            @endforeach
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($criteria as $cRow)
                            <tr class="group">
                                <td class="px-3 py-3 bg-slate-900/60 border-b border-r border-slate-700/50 sticky left-0 z-10">
                                    <span class="text-xs font-semibold text-white whitespace-nowrap">{{ $cRow->name }}</span>
                                </td>

                                @foreach($criteria as $cCol)
                                    <td class="px-1.5 py-1.5 border-b border-r border-slate-700/30 text-center
                                        @if($cRow->id === $cCol->id) bg-gold-500/5 @elseif($cRow->id < $cCol->id) bg-slate-800/20 @else bg-slate-900/40 @endif">

                                        @if($cRow->id === $cCol->id)
                                            <input type="text" value="1" readonly
                                                   class="w-full text-center px-2 py-2.5 rounded-lg bg-gold-500/10 border border-gold-500/20 text-gold-400 font-bold text-sm cursor-not-allowed">

                                        @elseif($cRow->id < $cCol->id)
                                            <input type="text" name="comparison[{{ $cRow->id }}][{{ $cCol->id }}]" list="pecahan-list"
                                                   value="{{ $savedValues[$cRow->id][$cCol->id] ?? '' }}"
                                                   class="matrix-input w-full text-center px-1 py-2.5 rounded-lg bg-slate-800 border border-slate-600 text-white font-semibold text-sm cursor-text hover:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all placeholder:text-gray-500 placeholder:text-[10px] placeholder:font-normal"
                                                   data-row="{{ $cRow->id }}" data-col="{{ $cCol->id }}" placeholder="Ketik 1-9 atau pecahan" autocomplete="off">

                                        @else
                                            <input type="text"
                                                   id="cell_{{ $cRow->id }}_{{ $cCol->id }}"
                                                   name="comparison[{{ $cRow->id }}][{{ $cCol->id }}]"
                                                   value="1.0000" readonly
                                                   class="w-full text-center px-2 py-2.5 rounded-lg bg-slate-900/50 border border-slate-700/50 text-gray-400 text-sm cursor-not-allowed font-mono">
                                        @endif

                                    </td>
                                @endforeach
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="glass-panel p-5 rounded-xl border border-slate-800 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
            <div class="flex items-start space-x-3">
                <div class="w-8 h-8 rounded-lg bg-gold-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                    <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <p class="text-xs text-gray-400 leading-relaxed">Pastikan seluruh perbandingan diisi dengan saksama. Sistem akan menolak hasil jika rasio konsistensi (CR) melebihi batas 0.1.</p>
            </div>
            <div class="flex flex-col sm:flex-row space-y-3 sm:space-y-0 sm:space-x-3 w-full sm:w-auto">
                <button type="submit" name="action" value="preview_weights" class="w-full sm:w-auto px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white font-bold rounded-lg shadow-lg shadow-blue-600/20 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center justify-center whitespace-nowrap">
                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path></svg>
                    Simpan & Kalkulasi Nilai Bobot
                </button>
            </div>
        </div>
    </form>

    <datalist id="pecahan-list">
        <option value="1/2">1/2 - Mendekati Sama Penting</option>
        <option value="1/3">1/3 - Sedikit Kurang Penting</option>
        <option value="1/4">1/4</option>
        <option value="1/5">1/5 - Jelas Kurang Penting</option>
        <option value="1/6">1/6</option>
        <option value="1/7">1/7 - Sangat Jelas Kurang Penting</option>
        <option value="1/8">1/8</option>
        <option value="1/9">1/9 - Mutlak Kurang Penting</option>
    </datalist>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const matrixInputs = document.querySelectorAll('.matrix-input');

    matrixInputs.forEach(function(input) {
        input.addEventListener('input', function() {
            const rowId = this.getAttribute('data-row');
            const colId = this.getAttribute('data-col');
            const rawValue = this.value.trim();
            let reciprocal = null;

            if (rawValue === '') return;

            if (rawValue.includes('/')) {
                const parts = rawValue.split('/');
                if (parts.length === 2) {
                    const num = parseFloat(parts[0]);
                    const den = parseFloat(parts[1]);
                    if (den !== 0 && !isNaN(num) && !isNaN(den)) {
                        reciprocal = den / num;
                    }
                }
            } else {
                const parsedValue = parseFloat(rawValue);
                if (!isNaN(parsedValue) && parsedValue !== 0) {
                    reciprocal = 1 / parsedValue;
                }
            }

            const reciprocalCell = document.getElementById('cell_' + colId + '_' + rowId);
            if (reciprocalCell && reciprocal !== null) {
                if (Number.isInteger(reciprocal)) {
                    reciprocalCell.value = reciprocal.toString();
                } else {
                    reciprocalCell.value = reciprocal.toFixed(4);
                }

                reciprocalCell.classList.add('ring-2', 'ring-gold-500', 'text-gold-400');
                setTimeout(function() {
                    reciprocalCell.classList.remove('ring-2', 'ring-gold-500', 'text-gold-400');
                }, 600);
            }
        });
    });

    matrixInputs.forEach(function(input) {
        if (input.value.trim() !== '') {
            input.dispatchEvent(new Event('input'));
        }
    });
});
</script>
@endpush
