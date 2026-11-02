<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternative;
use App\Models\Criteria;
use App\Models\SubCriteria;
use App\Models\AlternativeScore;
use App\Models\PairwiseComparison;
use App\Services\AhpMathService;
use Illuminate\Support\Facades\DB;


class AhpCalculationController extends Controller
{
    public function __construct(private readonly AhpMathService $ahp)
    {
    }

    public function index()
    {
        return view('ahp.dashboard.dashboard', [
            'alternativesCount' => Alternative::count(),
            'criteriaCount' => Criteria::count(),
            'subCriteriaCount' => SubCriteria::count(),
            'campaignCount' => (int) (DB::table('alternative_sub_criteria')->count() / 2),
        ]);
    }

    public function showMatrixForm()
    {
        $criteria = Criteria::all();

        $savedValues = [];
        foreach (PairwiseComparison::where('comparison_type', 'criteria')->get() as $comp) {
            $savedValues[$comp->element_1][$comp->element_2] =
                $this->ahp->formatSaatyDisplay($comp->saaty_value);
        }

        return view('ahp.kuesioner.kuesioner', compact('criteria', 'savedValues'));
    }

    public function calculateAhp(Request $request)
    {
        $criteria = Criteria::all();
        $ids = $criteria->pluck('id')->all();
        $n = count($ids);

        if ($n === 0) {
            return back()->with('error', 'Kriteria tidak ditemukan di database.');
        }

        $rawPairs = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $idA = $ids[$i];
                $idB = $ids[$j];
                $raw = $request->input("comparison.{$idA}.{$idB}", 1);
                $val = $this->ahp->parseValue($raw);

                PairwiseComparison::updateOrCreate(
                    ['comparison_type' => 'criteria', 'element_1' => $idA, 'element_2' => $idB],
                    ['saaty_value' => $val]
                );

                $rawPairs[$idA][$idB] = $val;
            }
        }

        ['weights' => $weights, 'consistency' => $con] = $this->ahp->calculate($ids, $rawPairs);
        $cr = $con['cr'];

        if ($cr > 0.1) {
            return back()
                ->with('cr_error', sprintf(
                    'Nilai Consistency Ratio (CR) Anda adalah %.4f. Angka ini melebihi batas ideal (0.1), mohon sesuaikan kembali perbandingan kriteria Anda.',
                    $cr
                ))
                ->with('preview_cr', $cr)
                ->with('preview_weights', $weights);
        }

        foreach ($criteria as $crit) {
            $crit->update(['weight' => $weights[$crit->id]]);
            $crit->syncSubCriteriaWeights();
        }

        return back()
            ->with('success', sprintf(
                'Kalkulasi nilai bobot kriteria berhasil disimpan! Nilai CR = %.4f (Valid).',
                $cr
            ))
            ->with('preview_cr', $cr)
            ->with('preview_weights', $weights);
    }

    public function showSubMatrixList()
    {
        $criteriaWithSubs = Criteria::has('subCriteria')->with('subCriteria')->orderBy('name')->get();
        return view('ahp.sub-kriteria.list', compact('criteriaWithSubs'));
    }

    public function showSubMatrixForm($criteria_id)
    {
        $parentCriteria = Criteria::findOrFail($criteria_id);
        $subCriteria = SubCriteria::where('criteria_id', $criteria_id)->orderBy('id')->get();

        if ($subCriteria->count() < 2) {
            return redirect()->route('sub_criteria.list')
                ->with('error', sprintf(
                    'Kriteria "%s" memerlukan minimal 2 sub-kriteria untuk membuat matriks perbandingan.',
                    $parentCriteria->name
                ));
        }

        return view('ahp.sub-kriteria.form', compact('parentCriteria', 'subCriteria'));
    }

    public function calculateSubAhp(Request $request, $criteria_id)
    {
        $parentCriteria = Criteria::findOrFail($criteria_id);
        $subCriteria = SubCriteria::where('criteria_id', $criteria_id)->orderBy('id')->get();
        $ids = $subCriteria->pluck('id')->all();
        $n = count($ids);

        if ($n < 2) {
            return back()->with('error', 'Minimal 2 sub-kriteria diperlukan untuk kalkulasi AHP.');
        }

        $rawPairs = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $idA = $ids[$i];
                $idB = $ids[$j];
                $raw = $request->input("comparison.{$idA}.{$idB}", 1);
                $val = $this->ahp->parseValue($raw);

                PairwiseComparison::updateOrCreate(
                    [
                        'comparison_type' => 'sub_criteria_' . $criteria_id,
                        'element_1' => $idA,
                        'element_2' => $idB,
                    ],
                    ['saaty_value' => $val]
                );

                $rawPairs[$idA][$idB] = $val;
            }
        }

        ['weights' => $weights, 'consistency' => $con] = $this->ahp->calculate($ids, $rawPairs);
        $cr = $con['cr'];

        if ($cr > 0.1) {
            return back()->with('error', sprintf(
                'Penilaian sub-kriteria "%s" tidak konsisten (CR = %.4f > 0.1). Harap ulangi pengisian matriks!',
                $parentCriteria->name,
                $cr
            ));
        }

        foreach ($subCriteria as $sub) {
            $sub->update(['local_weight' => $weights[$sub->id]]);
        }
        $parentCriteria->syncSubCriteriaWeights();

        return redirect()->route('sub_criteria.list')->with('success', sprintf(
            'Bobot lokal sub-kriteria "%s" berhasil dihitung (CR = %.4f). %d sub-kriteria telah diperbarui.',
            $parentCriteria->name,
            $cr,
            $n
        ));
    }

    public function showAlternativeMatrixList()
    {
        $alternatives = Alternative::orderBy('name')->get();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->orderBy('name')->get();
        $allSubCriteria = SubCriteria::with('criteria')->orderBy('criteria_id')->orderBy('id')->get();
        $completedNodes = AlternativeScore::select('node_type', 'node_id')->groupBy('node_type', 'node_id')->get();

        return view('ahp.matriks-alternatif.list', [
            'alternatives' => $alternatives,
            'standaloneCriteria' => $standaloneCriteria,
            'allSubCriteria' => $allSubCriteria,
            'completedNodes' => $completedNodes,
            'totalNodes' => $standaloneCriteria->count() + $allSubCriteria->count(),
            'completedCount' => $completedNodes->count(),
        ]);
    }

    public function showAlternativeMatrixForm($type, $id)
    {
        $alternatives = Alternative::orderBy('id')->get();

        if ($alternatives->count() < 2) {
            return redirect()->route('alt_matrix.list')
                ->with('error', 'Minimal 2 alternatif diperlukan untuk membuat matriks perbandingan.');
        }

        if ($type === 'criteria') {
            $node = Criteria::findOrFail($id);
            $parentName = '';
        } else {
            $node = SubCriteria::with('criteria')->findOrFail($id);
            $parentName = $node->criteria->name;
        }

        return view('ahp.matriks-alternatif.form', [
            'alternatives' => $alternatives,
            'nodeName' => $node->name,
            'nodeType' => $type,
            'nodeId' => $id,
            'parentName' => $parentName,
        ]);
    }

    public function calculateAlternativeAhp(Request $request, $type, $id)
    {
        $node = ($type === 'criteria') ? Criteria::findOrFail($id) : SubCriteria::findOrFail($id);
        $alternatives = Alternative::orderBy('id')->get();
        $ids = $alternatives->pluck('id')->all();
        $n = count($ids);

        if ($n < 2) {
            return back()->with('error', 'Minimal 2 alternatif diperlukan untuk kalkulasi AHP.');
        }

        $rawPairs = [];
        for ($i = 0; $i < $n; $i++) {
            for ($j = $i + 1; $j < $n; $j++) {
                $idA = $ids[$i];
                $idB = $ids[$j];
                $raw = $request->input("comparison.{$idA}.{$idB}", 1);
                $val = $this->ahp->parseValue($raw);

                PairwiseComparison::updateOrCreate(
                    [
                        'comparison_type' => "alternative_{$type}_{$id}",
                        'element_1' => $idA,
                        'element_2' => $idB,
                    ],
                    ['saaty_value' => $val]
                );

                $rawPairs[$idA][$idB] = $val;
            }
        }

        ['weights' => $weights, 'consistency' => $con] = $this->ahp->calculate($ids, $rawPairs);
        $cr = $con['cr'];

        if ($cr > 0.1) {
            return back()->with('error', sprintf(
                'Penilaian alternatif terhadap "%s" tidak konsisten (CR = %.4f > 0.1). Harap ulangi pengisian matriks!',
                $node->name,
                $cr
            ));
        }

        foreach ($alternatives as $alt) {
            AlternativeScore::updateOrCreate(
                ['node_type' => $type, 'node_id' => $id, 'alternative_id' => $alt->id],
                ['score' => $weights[$alt->id]]
            );
        }

        return redirect()->route('alt_matrix.list')->with('success', sprintf(
            'Bobot prioritas alternatif terhadap "%s" berhasil dihitung (CR = %.4f). %d alternatif telah diperbarui.',
            $node->name,
            $cr,
            $n
        ));
    }

    public function finalRanking()
    {
        $alternatives = Alternative::orderBy('id')->get();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->get();
        $allSubCriteria = SubCriteria::with('criteria')->get();

        $nodeWeights = [];

        foreach ($standaloneCriteria as $crit) {
            $nodeWeights["criteria_{$crit->id}"] = [
                'type' => 'criteria',
                'id' => $crit->id,
                'global_weight' => $crit->weight ?? 0,
            ];
        }

        foreach ($allSubCriteria as $sub) {
            $gw = $sub->global_weight > 0
                ? $sub->global_weight
                : (($sub->criteria->weight ?? 0) * ($sub->local_weight ?? 0));

            $nodeWeights["sub_criteria_{$sub->id}"] = [
                'type' => 'sub_criteria',
                'id' => $sub->id,
                'global_weight' => $gw,
            ];
        }

        foreach ($alternatives as $alt) {
            $finalScore = 0.0;

            foreach ($nodeWeights as $node) {
                $altScore = AlternativeScore::where('node_type', $node['type'])
                    ->where('node_id', $node['id'])
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore) {
                    $finalScore += $node['global_weight'] * $altScore->score;
                }
            }

            $alt->update(['final_score' => $finalScore]);
        }

        return redirect()->route('ahp.result')
            ->with('success', 'Skor akhir AHP Konvensional berhasil dihitung! Peringkat alternatif telah diperbarui.');
    }

    public function result()
    {
        $alternatives = Alternative::orderBy('final_score', 'desc')->get();
        $topAlternative = $alternatives->first();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->get();
        $allSubCriteria = SubCriteria::with('criteria')->get();
        $allBreakdowns = [];

        foreach ($alternatives as $alt) {
            $breakdown = [];

            foreach ($standaloneCriteria as $crit) {
                $altScore = AlternativeScore::where('node_type', 'criteria')
                    ->where('node_id', $crit->id)
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore && $altScore->score > 0) {
                    $breakdown[] = [
                        'group' => $crit->name,
                        'name' => $crit->name,
                        'score' => round($altScore->score, 4),
                        'contribution' => round(($crit->weight ?? 0) * $altScore->score, 4),
                        'type' => 'criteria',
                    ];
                }
            }

            foreach ($allSubCriteria as $sub) {
                $altScore = AlternativeScore::where('node_type', 'sub_criteria')
                    ->where('node_id', $sub->id)
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore && $altScore->score > 0) {
                    $gw = $sub->global_weight > 0
                        ? $sub->global_weight
                        : (($sub->criteria->weight ?? 0) * ($sub->local_weight ?? 0));

                    $breakdown[] = [
                        'group' => $sub->criteria->name ?? 'Sub-Kriteria',
                        'name' => $sub->name,
                        'score' => round($altScore->score, 4),
                        'contribution' => round($gw * $altScore->score, 4),
                        'type' => 'sub_criteria',
                    ];
                }
            }

            usort($breakdown, fn($a, $b) => $b['contribution'] <=> $a['contribution']);
            $allBreakdowns[$alt->id] = $breakdown;
        }

        $topBreakdown = collect($allBreakdowns[$topAlternative?->id ?? 0] ?? []);

        return view('ahp.hasil.hasil-keputusan', compact(
            'alternatives',
            'topAlternative',
            'topBreakdown',
            'allBreakdowns'
        ));
    }

    public function exportPdf()
    {
        $alternatives = Alternative::orderBy('final_score', 'desc')->get();
        $criteria = Criteria::with('subCriteria')->get();

        return app('dompdf.wrapper')
            ->loadView('ahp.hasil.report_pdf', compact('alternatives', 'criteria'))
            ->download('Laporan_AHP_Synthesis_Huis.pdf');
    }

    public function showLaporanAlternatif()
    {
        $alternatives = Alternative::orderBy('final_score', 'desc')->get();
        $allSubCriteria = SubCriteria::with('criteria')->orderBy('criteria_id')->orderBy('id')->get();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->get();

        $allBreakdowns = [];
        foreach ($alternatives as $alt) {
            $breakdown = [];
            foreach ($standaloneCriteria as $crit) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'criteria')
                    ->where('node_id', $crit->id)->where('alternative_id', $alt->id)->first();
                if ($altScore && $altScore->score > 0) {
                    $breakdown[] = [
                        'node'         => $crit->name,
                        'group'        => $crit->name,
                        'type'         => 'Kriteria',
                        'global_weight'=> round($crit->weight ?? 0, 4),
                        'local_score'  => round($altScore->score, 4),
                        'contribution' => round(($crit->weight ?? 0) * $altScore->score, 4),
                    ];
                }
            }
            foreach ($allSubCriteria as $sub) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'sub_criteria')
                    ->where('node_id', $sub->id)->where('alternative_id', $alt->id)->first();
                if ($altScore && $altScore->score > 0) {
                    $gw = $sub->global_weight > 0
                        ? $sub->global_weight
                        : (($sub->criteria->weight ?? 0) * ($sub->local_weight ?? 0));
                    $breakdown[] = [
                        'node'         => $sub->name,
                        'group'        => $sub->criteria->name ?? 'Sub-Kriteria',
                        'type'         => 'Sub-Kriteria',
                        'global_weight'=> round($gw, 4),
                        'local_score'  => round($altScore->score, 4),
                        'contribution' => round($gw * $altScore->score, 4),
                    ];
                }
            }
            usort($breakdown, fn($a, $b) => $b['contribution'] <=> $a['contribution']);
            $allBreakdowns[$alt->id] = $breakdown;
        }

        return view('ahp.laporan.show_alternatif', compact('alternatives', 'allBreakdowns'));
    }

    public function showLaporanKriteria()
    {
        $criteria = Criteria::with('subCriteria')->orderBy('weight', 'desc')->get();
        $totalWeight = $criteria->sum('weight');

        return view('ahp.laporan.show_kriteria', compact('criteria', 'totalWeight'));
    }

    public function showLaporanSubKriteria()
    {
        $criteria = Criteria::with(['subCriteria' => function ($q) {
            $q->orderBy('global_weight', 'desc');
        }])->has('subCriteria')->orderBy('weight', 'desc')->get();
        $totalSub = $criteria->sum(fn($c) => $c->subCriteria->count());

        return view('ahp.laporan.show_sub_kriteria', compact('criteria', 'totalSub'));
    }

    public function showLaporanHasil()
    {
        $alternatives = Alternative::orderBy('final_score', 'desc')->get();
        $criteria = Criteria::with('subCriteria')->get();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->get();
        $allSubCriteria = SubCriteria::with('criteria')->get();
        $topAlternative = $alternatives->first();

        $allBreakdowns = [];
        foreach ($alternatives as $alt) {
            $breakdown = [];
            foreach ($standaloneCriteria as $crit) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'criteria')
                    ->where('node_id', $crit->id)->where('alternative_id', $alt->id)->first();
                if ($altScore && $altScore->score > 0) {
                    $breakdown[] = [
                        'group'        => $crit->name,
                        'name'         => $crit->name,
                        'score'        => round($altScore->score, 4),
                        'contribution' => round(($crit->weight ?? 0) * $altScore->score, 4),
                        'type'         => 'criteria',
                    ];
                }
            }
            foreach ($allSubCriteria as $sub) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'sub_criteria')
                    ->where('node_id', $sub->id)->where('alternative_id', $alt->id)->first();
                if ($altScore && $altScore->score > 0) {
                    $gw = $sub->global_weight > 0
                        ? $sub->global_weight
                        : (($sub->criteria->weight ?? 0) * ($sub->local_weight ?? 0));
                    $breakdown[] = [
                        'group'        => $sub->criteria->name ?? 'Sub-Kriteria',
                        'name'         => $sub->name,
                        'score'        => round($altScore->score, 4),
                        'contribution' => round($gw * $altScore->score, 4),
                        'type'         => 'sub_criteria',
                    ];
                }
            }
            usort($breakdown, fn($a, $b) => $b['contribution'] <=> $a['contribution']);
            $allBreakdowns[$alt->id] = $breakdown;
        }

        return view('ahp.laporan.show_hasil', compact(
            'alternatives', 'criteria', 'topAlternative', 'allBreakdowns'
        ));
    }

    public function laporanAlternatif()
    {
        $alternatives = Alternative::orderBy('final_score', 'desc')->get();
        $allSubCriteria = SubCriteria::with('criteria')->orderBy('criteria_id')->orderBy('id')->get();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->get();

        $allBreakdowns = [];
        foreach ($alternatives as $alt) {
            $breakdown = [];

            foreach ($standaloneCriteria as $crit) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'criteria')
                    ->where('node_id', $crit->id)
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore && $altScore->score > 0) {
                    $breakdown[] = [
                        'node'         => $crit->name,
                        'group'        => $crit->name,
                        'type'         => 'Kriteria',
                        'global_weight'=> round($crit->weight ?? 0, 4),
                        'local_score'  => round($altScore->score, 4),
                        'contribution' => round(($crit->weight ?? 0) * $altScore->score, 4),
                    ];
                }
            }

            foreach ($allSubCriteria as $sub) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'sub_criteria')
                    ->where('node_id', $sub->id)
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore && $altScore->score > 0) {
                    $gw = $sub->global_weight > 0
                        ? $sub->global_weight
                        : (($sub->criteria->weight ?? 0) * ($sub->local_weight ?? 0));

                    $breakdown[] = [
                        'node'         => $sub->name,
                        'group'        => $sub->criteria->name ?? 'Sub-Kriteria',
                        'type'         => 'Sub-Kriteria',
                        'global_weight'=> round($gw, 4),
                        'local_score'  => round($altScore->score, 4),
                        'contribution' => round($gw * $altScore->score, 4),
                    ];
                }
            }

            usort($breakdown, fn($a, $b) => $b['contribution'] <=> $a['contribution']);
            $allBreakdowns[$alt->id] = $breakdown;
        }

        return app('dompdf.wrapper')
            ->loadView('ahp.laporan.laporan_alternatif', compact('alternatives', 'allBreakdowns'))
            ->download('Laporan_Alternatif_Digital_Marketing.pdf');
    }

    public function laporanKriteria()
    {
        $criteria = Criteria::with('subCriteria')->orderBy('weight', 'desc')->get();
        $totalWeight = $criteria->sum('weight');

        return app('dompdf.wrapper')
            ->loadView('ahp.laporan.laporan_kriteria', compact('criteria', 'totalWeight'))
            ->download('Laporan_Kriteria_AHP.pdf');
    }

    public function laporanSubKriteria()
    {
        $criteria = Criteria::with(['subCriteria' => function ($q) {
            $q->orderBy('global_weight', 'desc');
        }])->has('subCriteria')->orderBy('weight', 'desc')->get();

        return app('dompdf.wrapper')
            ->loadView('ahp.laporan.laporan_sub_kriteria', compact('criteria'))
            ->download('Laporan_Sub_Kriteria_AHP.pdf');
    }

    public function laporanHasil()
    {
        $alternatives = Alternative::orderBy('final_score', 'desc')->get();
        $criteria = Criteria::with('subCriteria')->get();
        $standaloneCriteria = Criteria::doesntHave('subCriteria')->get();
        $allSubCriteria = SubCriteria::with('criteria')->get();

        $allBreakdowns = [];
        foreach ($alternatives as $alt) {
            $breakdown = [];

            foreach ($standaloneCriteria as $crit) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'criteria')
                    ->where('node_id', $crit->id)
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore && $altScore->score > 0) {
                    $breakdown[] = [
                        'group'        => $crit->name,
                        'name'         => $crit->name,
                        'score'        => round($altScore->score, 4),
                        'contribution' => round(($crit->weight ?? 0) * $altScore->score, 4),
                        'type'         => 'criteria',
                    ];
                }
            }

            foreach ($allSubCriteria as $sub) {
                $altScore = \App\Models\AlternativeScore::where('node_type', 'sub_criteria')
                    ->where('node_id', $sub->id)
                    ->where('alternative_id', $alt->id)
                    ->first();

                if ($altScore && $altScore->score > 0) {
                    $gw = $sub->global_weight > 0
                        ? $sub->global_weight
                        : (($sub->criteria->weight ?? 0) * ($sub->local_weight ?? 0));

                    $breakdown[] = [
                        'group'        => $sub->criteria->name ?? 'Sub-Kriteria',
                        'name'         => $sub->name,
                        'score'        => round($altScore->score, 4),
                        'contribution' => round($gw * $altScore->score, 4),
                        'type'         => 'sub_criteria',
                    ];
                }
            }

            usort($breakdown, fn($a, $b) => $b['contribution'] <=> $a['contribution']);
            $allBreakdowns[$alt->id] = $breakdown;
        }

        return app('dompdf.wrapper')
            ->loadView('ahp.laporan.laporan_hasil', compact('alternatives', 'criteria', 'allBreakdowns'))
            ->download('Laporan_Hasil_AHP_Synthesis_Huis.pdf');
    }
}

