@extends('layouts.app')

@section('title', 'Matriks Sub-Kriteria: ' . $parentCriteria->name . ' - Synthesis Huis')
@section('header', 'Matriks Sub-Kriteria: ' . $parentCriteria->name)

@section('content')
    <div class="max-w-6xl mx-auto space-y-6">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-2xl relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-6 -mr-6 w-32 h-32 bg-gold-500/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 -mb-4 -ml-4 w-20 h-20 bg-gold-500/5 rounded-full blur-2xl"></div>
            <nav class="flex items-center text-xs text-gray-500 mb-4 relative z-10">
                <a href="{{ route('sub_criteria.list') }}" class="hover:text-gold-400 transition-colors">Daftar Grup
                    Sub-Kriteria</a>
                <svg class="w-3 h-3 mx-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
                <span class="text-gold-400 font-semibold">{{ $parentCriteria->name }}</span>
            </nav>

            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 relative z-10">
                <div>
                    <h2 class="text-xl font-bold text-white mb-1">
                        Matriks Perbandingan:
                        <span class="text-gold-400">{{ $parentCriteria->name }}</span>
                    </h2>
                    <p class="text-gray-400 text-sm leading-relaxed">
                        Isikan nilai perbandingan pada <strong class="text-white">segitiga atas</strong> matriks. Nilai
                        kebalikan (1/x) pada segitiga bawah akan terisi <strong class="text-gold-400">otomatis secara
                            real-time</strong>.
                    </p>
                </div>
                <div class="flex-shrink-0">
                    <span
                        class="px-4 py-2 text-xs font-bold text-gold-400 bg-gold-500/10 border border-gold-500/30 rounded-full whitespace-nowrap">
                        {{ count($subCriteria) }} Sub-Kriteria &bull;
                        {{ count($subCriteria) * (count($subCriteria) - 1) / 2 }} Perbandingan
                    </span>
                </div>
            </div>
        </div>

        <div class="glass-panel p-5 rounded-xl border border-slate-800">
            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-3">Skala Saaty</p>
            <div class="grid grid-cols-2 md:grid-cols-5 gap-2 text-xs">
                <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                    <span
                        class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">1</span>
                    <span class="text-gray-300">Sama Penting</span>
                </div>
                <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                    <span
                        class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">3</span>
                    <span class="text-gray-300">Sedikit Lebih Penting</span>
                </div>
                <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                    <span
                        class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">5</span>
                    <span class="text-gray-300">Jelas Lebih Penting</span>
                </div>
                <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                    <span
                        class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">7</span>
                    <span class="text-gray-300">Sangat Jelas Penting</span>
                </div>
                <div class="flex items-center space-x-2 px-3 py-2 rounded-lg bg-slate-800/40">
                    <span
                        class="w-6 h-6 rounded-md bg-gold-500/20 text-gold-400 font-bold flex items-center justify-center text-[11px]">9</span>
                    <span class="text-gray-300">Mutlak Lebih Penting</span>
                </div>
            </div>
            <p class="text-[11px] text-gray-500 mt-2">Nilai 2, 4, 6, 8 merupakan nilai tengah di antara dua skala penilaian
                berturut-turut.</p>
        </div>

        <form action="{{ route('sub_criteria.calculate', $parentCriteria->id) }}" method="POST" id="subMatrixForm">
            @csrf

            <div class="glass-panel rounded-2xl border border-slate-800 shadow-2xl overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full border-collapse min-w-[700px]">
                        <thead>
                            <tr>
                                <th
                                    class="px-3 py-4 text-left bg-slate-900/80 border-b border-r border-slate-700/50 sticky left-0 z-10">
                                    <span
                                        class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Sub-Kriteria</span>
                                </th>
                                @foreach($subCriteria as $subCol)
                                    <th
                                        class="px-2 py-4 text-center bg-slate-900/80 border-b border-r border-slate-700/50 min-w-[120px]">
                                        <span
                                            class="text-[11px] font-semibold text-gold-400 leading-tight block">{{ $subCol->name }}</span>
                                    </th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($subCriteria as $subI)
                                <tr class="group">
                                    <td
                                        class="px-3 py-3 bg-slate-900/60 border-b border-r border-slate-700/50 sticky left-0 z-10">
                                        <span
                                            class="text-xs font-semibold text-white whitespace-nowrap">{{ $subI->name }}</span>
                                    </td>

                                    @foreach($subCriteria as $subJ)
                                        <td
                                            class="px-1.5 py-1.5 border-b border-r border-slate-700/30 text-center
                                                                @if($subI->id === $subJ->id) bg-gold-500/5 @elseif($subI->id < $subJ->id) bg-slate-800/20 @else bg-slate-900/40 @endif">

                                            @if($subI->id === $subJ->id)
                                                <input type="text" value="1" readonly
                                                    name="comparison[{{ $subI->id }}][{{ $subJ->id }}]"
                                                    class="w-full text-center px-2 py-2.5 rounded-lg bg-gold-500/10 border border-gold-500/20 text-gold-400 font-bold text-sm cursor-not-allowed">

                                            @elseif($subI->id < $subJ->id)
                                                <input type="text" name="comparison[{{ $subI->id }}][{{ $subJ->id }}]"
                                                    list="pecahan-list"
                                                    class="sub-matrix-input w-full text-center px-1 py-2.5 rounded-lg bg-slate-800 border border-slate-600 text-white font-semibold text-sm cursor-text hover:border-gold-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all placeholder:text-gray-500 placeholder:text-[10px] placeholder:font-normal"
                                                    data-row="{{ $subI->id }}" data-col="{{ $subJ->id }}"
                                                    placeholder="Ketik 1-9 atau pecahan" autocomplete="off">

                                            @else
                                                <input type="text" id="cell-{{ $subI->id }}-{{ $subJ->id }}"
                                                    name="comparison[{{ $subI->id }}][{{ $subJ->id }}]" value="1.0000" readonly
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

            <div
                class="glass-panel p-5 rounded-xl border border-slate-800 shadow-xl flex flex-col sm:flex-row items-center justify-between gap-4 mt-6">
                <div class="flex items-start space-x-3">
                    <div class="w-8 h-8 rounded-lg bg-gold-500/10 flex items-center justify-center flex-shrink-0 mt-0.5">
                        <svg class="w-4 h-4 text-gold-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <p class="text-xs text-gray-400 leading-relaxed">Pastikan seluruh perbandingan diisi dengan saksama.
                        Sistem akan menolak hasil jika rasio konsistensi (CR) melebihi batas <strong
                            class="text-white">0.1</strong>.</p>
                </div>
                <div class="flex items-center space-x-3 flex-shrink-0">
                    <a href="{{ route('sub_criteria.list') }}"
                        class="px-5 py-3 text-sm font-medium text-gray-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-all">
                        Kembali
                    </a>
                    <button type="submit"
                        class="px-8 py-3.5 bg-gold-500 hover:bg-gold-600 text-slate-900 font-bold rounded-lg shadow-lg shadow-gold-500/20 transition-all duration-300 transform hover:-translate-y-0.5 flex items-center whitespace-nowrap">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z">
                            </path>
                        </svg>
                        Hitung Bobot Lokal
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
        document.addEventListener('DOMContentLoaded', function () {
            const subMatrixInputs = document.querySelectorAll('.sub-matrix-input');

            subMatrixInputs.forEach(function (input) {
                input.addEventListener('input', function () {
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

                    const reciprocalCell = document.getElementById('cell-' + colId + '-' + rowId);
                    if (reciprocalCell && reciprocal !== null) {
                        if (Number.isInteger(reciprocal)) {
                            reciprocalCell.value = reciprocal.toString();
                        } else {
                            reciprocalCell.value = reciprocal.toFixed(4);
                        }

                        reciprocalCell.classList.add('ring-2', 'ring-gold-500', 'text-gold-400');
                        setTimeout(function () {
                            reciprocalCell.classList.remove('ring-2', 'ring-gold-500', 'text-gold-400');
                        }, 700);
                    }
                });
            });
        });
    </script>
@endpush