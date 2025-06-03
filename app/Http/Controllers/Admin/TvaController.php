<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ParametreTva;
use App\Models\Categorie;

class TvaController extends Controller
{
    public function edit()
    {
        $tva = ParametreTva::latest('updated_at')->first();
        $categories = Categorie::all();
        return view('tva.edit', compact('tva','categories'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'taux' => 'required|numeric|min:0|max:100',
        ]);

        $tva = ParametreTva::latest('updated_at')->first();

        if ($tva) {
            $tva->update([
                'taux' => $request->taux,
                'updated_at' => now(),
            ]);
        } else {
            ParametreTva::create([
                'taux' => $request->taux,
                'updated_at' => now(),
            ]);
        }

        return redirect()->back()->with('success', 'Taux de TVA mis à jour avec succès.');
    }
}

