<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Criteria;
use App\Models\SubCriteria;

class CriteriaController extends Controller
{
    public function index()
    {
        $criteria = Criteria::with('subCriteria')->orderBy('name')->get();
        return view('ahp.criteria.kriteria', compact('criteria'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:criteria,name',
        ]);

        Criteria::create(['name' => $request->name]);

        return redirect()->route('data.criteria')->with('success', 'Kriteria berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Criteria::findOrFail($id)->delete();
        return redirect()->route('data.criteria')->with('success', 'Kriteria beserta sub-kriterianya berhasil dihapus.');
    }

    public function storeSub(Request $request)
    {
        $request->validate([
            'criteria_id' => 'required|exists:criteria,id',
            'name' => 'required|string|max:255',
        ]);

        SubCriteria::firstOrCreate([
            'criteria_id' => $request->criteria_id,
            'name' => $request->name,
        ]);

        $criteria = Criteria::find($request->criteria_id);
        if ($criteria) {
            $criteria->subCriteria()->update(['local_weight' => null, 'global_weight' => null]);
            $criteria->syncSubCriteriaWeights();
        }

        return redirect()->route('data.criteria')->with('success', 'Sub-kriteria berhasil ditambahkan.');
    }

    public function destroySub($id)
    {
        $sub = SubCriteria::findOrFail($id);
        $criteriaId = $sub->criteria_id;
        $sub->delete();

        $criteria = Criteria::find($criteriaId);
        if ($criteria) {
            $criteria->subCriteria()->update(['local_weight' => null, 'global_weight' => null]);
            $criteria->syncSubCriteriaWeights();
        }

        return redirect()->route('data.criteria')->with('success', 'Sub-kriteria berhasil dihapus.');
    }
}
