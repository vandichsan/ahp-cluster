@extends('layouts.app')

@section('title', 'Data Kriteria - Synthesis Huis SPK')
@section('header', 'Kelola Kriteria & Sub-Kriteria')

@section('content')
    <div class="max-w-5xl mx-auto space-y-6">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4">Tambah Kriteria Baru</h3>
            <form action="{{ route('data.criteria.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" placeholder="Contoh: Biaya Promosi, Jangkauan Audiens..." required
                        class="w-full px-4 py-3 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all text-sm"
                        value="{{ old('name') }}">
                    @error('name')
                        <p class="mt-1 text-xs text-red-400">{{ $message }}</p>
                    @enderror
                </div>
                <button type="submit"
                    class="px-6 py-3 bg-gold-500 hover:bg-gold-600 text-slate-900 font-semibold rounded-lg shadow-lg shadow-gold-500/20 transition-all duration-300 flex items-center justify-center whitespace-nowrap">
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                    Tambah Kriteria
                </button>
            </form>
        </div>

        @forelse($criteria as $crit)
            <div class="glass-panel rounded-2xl border border-slate-800 shadow-xl overflow-hidden">
                <div class="px-6 py-4 flex items-center justify-between border-b border-slate-800/50 bg-slate-900/30">
                    <div class="flex items-center space-x-3">
                        <div
                            class="w-8 h-8 rounded-lg bg-gold-500/10 flex items-center justify-center text-gold-400 font-bold text-sm">
                            {{ $loop->iteration }}
                        </div>
                        <div>
                            <h4 class="text-md font-bold text-white">{{ $crit->name }}</h4>
                            <p class="text-xs text-gray-400">
                                {{ $crit->subCriteria->count() }} sub-kriteria
                                @if($crit->weight)
                                    &bull; <span class="text-gold-400">Bobot: {{ number_format($crit->weight, 4) }}</span>
                                @endif
                            </p>
                        </div>
                    </div>
                    <button type="button"
                        class="btn-delete-criteria flex items-center space-x-1.5 px-3 py-1.5 text-xs text-gray-400 hover:text-red-400 hover:bg-red-500/10 border border-transparent hover:border-red-500/30 rounded-lg transition-all"
                        data-id="{{ $crit->id }}" data-name="{{ $crit->name }}">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                            </path>
                        </svg>
                        <span>Hapus</span>
                    </button>
                </div>
                <div class="px-6 py-3">
                    @if($crit->subCriteria->count() > 0)
                        <div class="divide-y divide-slate-800/30">
                            @foreach($crit->subCriteria as $sub)
                                <div class="flex items-center justify-between py-2.5 group">
                                    <div class="flex items-center space-x-3">
                                        <span
                                            class="w-1.5 h-1.5 rounded-full bg-gray-600 group-hover:bg-gold-500 transition-colors"></span>
                                        <span class="text-sm text-gray-300">{{ $sub->name }}</span>
                                        @if($sub->global_weight)
                                            <span
                                                class="text-[10px] px-2 py-0.5 rounded-full bg-gold-500/10 text-gold-400 border border-gold-500/20">
                                                {{ number_format($sub->global_weight, 4) }}
                                            </span>
                                        @endif
                                    </div>
                                    <button type="button"
                                        class="btn-delete-sub text-gray-600 hover:text-red-400 transition-colors p-1 opacity-0 group-hover:opacity-100"
                                        data-id="{{ $sub->id }}" data-name="{{ $sub->name }}">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-xs text-gray-500 py-2">Belum ada sub-kriteria.</p>
                    @endif
                </div>
                <div class="px-6 py-3 border-t border-slate-800/30 bg-slate-900/20">
                    <form action="{{ route('data.subcriteria.store') }}" method="POST" class="flex gap-2">
                        @csrf
                        <input type="hidden" name="criteria_id" value="{{ $crit->id }}">
                        <input type="text" name="name" placeholder="Tambah sub-kriteria..." required
                            class="flex-1 px-3 py-2 bg-slate-800/50 border border-slate-700 rounded-lg text-white placeholder-gray-500 focus:outline-none focus:ring-2 focus:ring-gold-500 focus:border-transparent transition-all text-xs">
                        <button type="submit"
                            class="px-4 py-2 bg-slate-700 hover:bg-gold-500 hover:text-slate-900 text-gray-300 text-xs font-semibold rounded-lg transition-all flex items-center">
                            <svg class="w-3.5 h-3.5 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Tambah
                        </button>
                    </form>
                </div>
            </div>
        @empty
            <div class="glass-panel p-8 rounded-2xl border border-slate-800 shadow-xl text-center">
                <svg class="w-12 h-12 mx-auto mb-3 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2">
                    </path>
                </svg>
                <p class="text-sm text-gray-500">Belum ada data kriteria. Tambahkan melalui form di atas.</p>
            </div>
        @endforelse

    </div>

    <div id="deleteModal" class="hidden fixed inset-0 z-50 flex items-center justify-center bg-black/60 backdrop-blur-sm">
        <div class="glass-panel rounded-2xl p-6 w-full max-w-sm mx-4 border border-red-500/30 shadow-2xl">
            <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-red-500/10 flex items-center justify-center flex-shrink-0">
                    <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z">
                        </path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-md font-bold text-white">Konfirmasi Hapus</h3>
                    <p class="text-xs text-gray-400" id="deleteModalText">Apakah Anda yakin?</p>
                </div>
            </div>
            <div class="flex justify-end space-x-3">
                <button type="button" id="deleteCancelBtn"
                    class="px-4 py-2 text-sm text-gray-400 hover:text-white border border-slate-700 hover:border-slate-600 rounded-lg transition-colors">
                    Batal
                </button>
                <form id="deleteForm" method="POST">
                    @csrf
                    @method('DELETE')
                    <button type="submit"
                        class="px-5 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-semibold rounded-lg shadow-lg transition-all">
                        Ya, Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const modal = document.getElementById('deleteModal');
            const modalText = document.getElementById('deleteModalText');
            const deleteForm = document.getElementById('deleteForm');
            const cancelBtn = document.getElementById('deleteCancelBtn');

            cancelBtn.addEventListener('click', function () {
                modal.classList.add('hidden');
            });
            modal.addEventListener('click', function (e) {
                if (e.target === modal) modal.classList.add('hidden');
            });
            document.querySelectorAll('.btn-delete-criteria').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    modalText.textContent = 'Hapus kriteria "' + name + '" beserta seluruh sub-kriterianya?';
                    deleteForm.action = '/data/kriteria/' + id;
                    modal.classList.remove('hidden');
                });
            });
            document.querySelectorAll('.btn-delete-sub').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    modalText.textContent = 'Hapus sub-kriteria "' + name + '"?';
                    deleteForm.action = '/data/sub-kriteria/' + id;
                    modal.classList.remove('hidden');
                });
            });
        });
    </script>
@endpush