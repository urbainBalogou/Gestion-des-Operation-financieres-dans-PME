<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Categorie;


class CategoryController extends Controller
{
    public function index(){
        $categories = Categorie::all();

        return view("index",compact('categories'));
    }
     public function store(Request $request)
    {
        $request->validate([
            'libelle' => 'required|string|max:255|unique:categories,libelle',
        ]);

        Categorie::create([
            'libelle' => $request->libelle,
        ]);
    

        return back()->with('success', 'Catégorie ajoutée avec succès.');
    }

    public function destroy(Categorie $category)
    {
        $category->delete();
        return back()->with('success', 'Catégorie supprimée.');
    }
}
