<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categorie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;


class TransactionController extends Controller {


   public function allTransaction() {
    \Carbon\Carbon::setLocale('fr');
    $transactions = Transaction::with('categorie')->orderBy('date', 'desc')->get();
     $categories = Categorie::all();
    return view("transaction.transaction", compact("transactions","categories"));
}

public function exportPdf($annee)
{
    Carbon::setLocale('fr');

    $transactions_par_mois = Transaction::selectRaw('
            YEAR(date) as annee,
            MONTH(date) as mois,
            type,
            SUM(montant) as total
        ')
        ->whereYear('date', $annee)
        ->groupBy('annee', 'mois', 'type')
        ->orderByDesc('mois')
        ->get()
        ->groupBy(function ($item) {
            return $item->annee . '-' . str_pad($item->mois, 2, '0', STR_PAD_LEFT);
        });

    $pdf = Pdf::loadView('transaction.rapport_pdf', compact('transactions_par_mois', 'annee'));
    return $pdf->download("rapport_{$annee}.pdf");
}


public function filter(Request $request)
{
    $query = Transaction::with('categorie')->latest();

    if ($request->filled('libelle')) {
        $query->where('description', 'like', '%' . $request->libelle . '%');
    }

    if ($request->filled('montant')) {
        $query->where('montant', $request->montant);
    }

    if ($request->filled('type')) {
        $query->where('type', $request->type);
    }

    if ($request->filled('categorie_id')) {
        $query->where('categorie_id', $request->categorie_id);
    }

    if ($request->filled('mois')) {
        $mois = \Carbon\Carbon::parse($request->mois);
        $query->whereMonth('date', $mois->month)->whereYear('date', $mois->year);
    }

    $transactions = $query->get();
    $categories = \App\Models\Categorie::all(); // pour les filtres

    return view('transaction.transaction', compact('transactions', 'categories'));
}

public function rapportMensuel()
{
    Carbon::setLocale('fr');

    $transactions_par_mois = Transaction::selectRaw('
            YEAR(date) as annee,
            MONTH(date) as mois,
            type,
            SUM(montant) as total
        ')
        ->groupBy('annee', 'mois', 'type')
        ->orderByDesc('annee')
        ->orderByDesc('mois')
        ->get()
        ->groupBy(function ($item) {
            return $item->annee . '-' . str_pad($item->mois, 2, '0', STR_PAD_LEFT);
        });
        $categories = Categorie::all();

    return view('transaction.rapport', compact('transactions_par_mois',"categories"));
}



    public function store(Request $request)
{
    $validated = $request->validate([
        'type' => 'required|in:depot,debit',
        'amount' => 'required|numeric|min:0',
        'category_id' => 'required|exists:categories,id',
        'date' => 'required|date',
        'description' => 'nullable|string',
        'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
    ]);

    if ($request->hasFile('attachment')) {
        $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
    }

    $validated['user_id'] = Auth::id();
    Transaction::create([
        'type' => $validated['type'],
        'montant' => $validated['amount'],
        'categorie_id' => $validated['category_id'],
        'date' => $validated['date'],
        'description' => $validated['description'] ?? null,
        'piece_jointe' => $validated['attachment'] ?? null,
        'user_id' => $validated['user_id'],
    ]);

    return back()->with('success', 'Transaction enregistrée avec succès.');
}

public function update(Request $request, $id)
{
    $request->validate([
        'type' => 'required',
        'montant' => 'required|numeric',
        'categorie_id' => 'required',
        'date' => 'required|date',
        'description' => 'nullable|string',
        'attachment' => 'nullable|string',
    ]);

    $transaction = Transaction::findOrFail($id);
    $transaction->update($request->all());

    return redirect()->route('liste_transactions')->with('success', 'Transaction mise à jour avec succès.');
}

public function destroy($id)
{
    $transaction = Transaction::findOrFail($id);
    $transaction->delete();

    return redirect()->route('home')->with('success', 'Transaction supprimée.');
}

public function edit($id)
{
    $transaction = Transaction::findOrFail($id);
    $categories = Categorie::all(); // Si tu veux les catégories dans le formulaire
    return view('transaction.edit', compact('transaction', 'categories'));
}

}








