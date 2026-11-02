@extends('layouts.app')

@section('title', 'Data Alternatif - Synthesis Huis SPK')
@section('header', 'Kelola Data Alternatif')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-xl">
            <h3 class="text-lg font-bold text-white mb-4">Tambah Alternatif Baru</h3>
            <form action="{{ route('data.alternatives.store') }}" method="POST" class="flex flex-col sm:flex-row gap-3">
                @csrf
                <div class="flex-1">
                    <input type="text" name="name" placeholder="Contoh: Instagram Ads, Google Ads, TikTok Ads..." required
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
                    Tambah
                </button>
            </form>
        </div>
        <div class="glass-panel p-6 rounded-2xl border border-slate-800 shadow-xl">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-white">Daftar Alternatif</h3>
                    <p class="text-xs text-gray-400">{{ $alternatives->count() }} alternatif platform digital marketing</p>
                </div>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-800 text-xs font-semibold text-gray-400 uppercase">
                            <th class="py-3 px-4 text-center w-16">No</th>
                            <th class="py-3 px-4">Nama Alternatif</th>
                            <th class="py-3 px-4 text-right">Skor Akhir</th>
                            <th class="py-3 px-4 text-center w-24">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-800/50">
                        @forelse($alternatives as $index => $alt)
                            <tr class="hover:bg-slate-800/20 transition-colors group">
                                <td class="py-3 px-4 text-center text-sm text-gray-400">{{ $index + 1 }}</td>
                                <td class="py-3 px-4 text-sm font-medium text-white">{{ $alt->name }}</td>
                                <td
                                    class="py-3 px-4 text-right font-mono text-sm {{ $alt->final_score ? 'text-gold-400' : 'text-gray-500' }}">
                                    {{ $alt->final_score ? number_format($alt->final_score, 4) : '—' }}
                                </td>
                                <td class="py-3 px-4 text-center">
                                    <button type="button"
                                        class="btn-delete-alt text-gray-500 hover:text-red-400 transition-colors p-1"
                                        data-id="{{ $alt->id }}" data-name="{{ $alt->name }}">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="py-8 text-center text-sm text-gray-500">
                                    Belum ada data alternatif. Tambahkan secara manual di atas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
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
            document.querySelectorAll('.btn-delete-alt').forEach(function (btn) {
                btn.addEventListener('click', function () {
                    var id = this.getAttribute('data-id');
                    var name = this.getAttribute('data-name');
                    modalText.textContent = 'Hapus alternatif "' + name + '"?';
                    deleteForm.action = '/data/alternatif/' + id;
                    modal.classList.remove('hidden');
                });
            });
        });
    </script>
@endpush