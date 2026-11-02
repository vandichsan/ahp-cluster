<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Alternative;

class AlternativeController extends Controller
{
    public function index()
    {
        $alternatives = Alternative::orderBy('name')->get();
        return view('ahp.alternatives.alternatif', compact('alternatives'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:alternatives,name',
        ]);

        Alternative::create(['name' => $request->name]);

        return redirect()->route('data.alternatives')->with('success', 'Alternatif berhasil ditambahkan.');
    }

    public function destroy($id)
    {
        Alternative::findOrFail($id)->delete();
        return redirect()->route('data.alternatives')->with('success', 'Alternatif berhasil dihapus.');
    }
}
