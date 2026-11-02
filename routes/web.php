<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\AhpCalculationController;
use App\Http\Controllers\AlternativeController;
use App\Http\Controllers\CriteriaController;

Route::get('/', function () {
    return redirect()->route('login');
});

Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout')->middleware('auth');

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', [AhpCalculationController::class, 'index'])->name('dashboard');

    Route::get('/data/alternatif', [AlternativeController::class, 'index'])->name('data.alternatives');
    Route::post('/data/alternatif', [AlternativeController::class, 'store'])->name('data.alternatives.store');
    Route::delete('/data/alternatif/{id}', [AlternativeController::class, 'destroy'])->name('data.alternatives.destroy');


    Route::get('/data/kriteria', [CriteriaController::class, 'index'])->name('data.criteria');
    Route::post('/data/kriteria', [CriteriaController::class, 'store'])->name('data.criteria.store');
    Route::delete('/data/kriteria/{id}', [CriteriaController::class, 'destroy'])->name('data.criteria.destroy');

    Route::post('/data/sub-kriteria', [CriteriaController::class, 'storeSub'])->name('data.subcriteria.store');
    Route::delete('/data/sub-kriteria/{id}', [CriteriaController::class, 'destroySub'])->name('data.subcriteria.destroy');

    Route::get('/ahp-dashboard/matrix', [AhpCalculationController::class, 'showMatrixForm'])->name('ahp.matrix');
    Route::post('/ahp-dashboard/calculate', [AhpCalculationController::class, 'calculateAhp'])->name('ahp.calculate');
    Route::get('/ahp-dashboard/result', [AhpCalculationController::class, 'result'])->name('ahp.result');

    Route::get('/ahp-dashboard/sub-criteria', [AhpCalculationController::class, 'showSubMatrixList'])->name('sub_criteria.list');
    Route::get('/ahp-dashboard/sub-criteria/matrix/{criteria_id}', [AhpCalculationController::class, 'showSubMatrixForm'])->name('sub_criteria.matrix');
    Route::post('/ahp-dashboard/sub-criteria/matrix/{criteria_id}/calculate', [AhpCalculationController::class, 'calculateSubAhp'])->name('sub_criteria.calculate');
    Route::get('/ahp-dashboard/alternatives/matrix', [AhpCalculationController::class, 'showAlternativeMatrixList'])->name('alt_matrix.list');
    Route::get('/ahp-dashboard/alternatives/matrix/{type}/{id}', [AhpCalculationController::class, 'showAlternativeMatrixForm'])->name('alt_matrix.form');
    Route::post('/ahp-dashboard/alternatives/matrix/{type}/{id}/calculate', [AhpCalculationController::class, 'calculateAlternativeAhp'])->name('alt_matrix.calculate');

    Route::post('/ahp-dashboard/final-ranking', [AhpCalculationController::class, 'finalRanking'])->name('ahp.finalRanking');

    Route::get('/ahp-dashboard/result/pdf', [AhpCalculationController::class, 'exportPdf'])->name('ahp.export_pdf');

    // ── Laporan: Web Preview ──────────────────────────────────────────────────────
    Route::get('/laporan/alternatif',           [AhpCalculationController::class, 'showLaporanAlternatif'])->name('laporan.alternatif');
    Route::get('/laporan/kriteria',             [AhpCalculationController::class, 'showLaporanKriteria'])->name('laporan.kriteria');
    Route::get('/laporan/sub-kriteria',         [AhpCalculationController::class, 'showLaporanSubKriteria'])->name('laporan.sub_kriteria');
    Route::get('/laporan/hasil',                [AhpCalculationController::class, 'showLaporanHasil'])->name('laporan.hasil');

    // ── Laporan: PDF Download ─────────────────────────────────────────────────────
    Route::get('/laporan/alternatif/pdf',       [AhpCalculationController::class, 'laporanAlternatif'])->name('laporan.alternatif.pdf');
    Route::get('/laporan/kriteria/pdf',         [AhpCalculationController::class, 'laporanKriteria'])->name('laporan.kriteria.pdf');
    Route::get('/laporan/sub-kriteria/pdf',     [AhpCalculationController::class, 'laporanSubKriteria'])->name('laporan.sub_kriteria.pdf');
    Route::get('/laporan/hasil/pdf',            [AhpCalculationController::class, 'laporanHasil'])->name('laporan.hasil.pdf');
});
