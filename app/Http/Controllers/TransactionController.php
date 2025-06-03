<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\ParametreTva;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categorie;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Support\Facades\Log;


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
    $tvaApplicable = $request->merge([
    'tva_applicable' => $request->has('tva_applicable'),
]);
    try {
        $validated = $request->validate([
            'type' => 'required|in:depot,debit',
            'amount_ht' => 'required|numeric|min:0',
            'tva_applicable' => 'nullable|boolean',
            'category_id' => 'required|exists:categories,id',
            'date' => 'required|date',
            'description' => 'nullable|string',
            'attachment' => 'nullable|file|max:10240|mimes:jpg,jpeg,png,pdf',
        ]);

        $taux = 0;
        $montantTva = 0;
        $montantTtc = $validated['amount_ht'];

        if ($tvaApplicable) {
            $paramTva = ParametreTva::latest('updated_at')->first();
            if ($paramTva && $paramTva->taux > 0) {
                $taux = $paramTva->taux;
                $montantTva = round($validated['amount_ht'] * ($taux / 100), 2);
                $montantTtc = round($validated['amount_ht'] + $montantTva, 2);
            }
        }

        if ($request->hasFile('attachment')) {
            $validated['attachment'] = $request->file('attachment')->store('attachments', 'public');
        }

        Transaction::create([
            'type' => $validated['type'],
            'montant' => $montantTtc,
            'montant_ht' => $validated['amount_ht'],
            'montant_tva' => $montantTva,
            'categorie_id' => $validated['category_id'],
            'date' => $validated['date'],
            'description' => $validated['description'] ?? null,
            'piece_jointe' => $validated['attachment'] ?? null,
            'user_id' => Auth::id(),
        ]);

        return back()->with('success', 'Transaction enregistrée avec succès.');
    } catch (Exception $e) {
        // Logger l’erreur pour analyse technique
        dd($e->getMessage());
        Log::error('Erreur lors de l\'enregistrement de la transaction : ' . $e->getMessage());

        // Rediriger avec un message d’erreur
        return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de la transaction. Veuillez réessayer.']);
    }
}


public function update(Request $request, $id)
{
    try {
    $request->validate([
        'type' => 'required|in:Dépot,Retrait',
        'montant_ht' => 'nullable|numeric|min:0',
        'montant' => 'required|numeric|min:0',
        'categorie_id' => 'required|exists:categories,id',
        'date' => 'required|date',
        'description' => 'nullable|string',
        'tva_applicable' => 'nullable|boolean',
    ]);

    $transaction = Transaction::findOrFail($id);

    $tvaApplicable = $request->boolean('tva_applicable');
    $taux = 0;
    $montantTva = 0;
    $montantHt = $request->input('montant_ht', $request->montant);
    $montantTtc = $request->montant;

    if ($tvaApplicable) {
        $paramTva = ParametreTva::latest('updated_at')->first();
        if ($paramTva && $paramTva->taux > 0) {
            $taux = $paramTva->taux;
            $montantTva = round($montantHt * ($taux / 100), 2);
            $montantTtc = round($montantHt + $montantTva, 2);
        }
    } else {
        $montantHt = $request->montant; // si pas de TVA, montant_ht = montant TTC
        $montantTva = 0;
    }

    $transaction->update([
        'type' => $request->type,
        'montant_ht' => $montantHt,
        'montant_tva' => $montantTva,
        'montant' => $montantTtc,
        'categorie_id' => $request->categorie_id,
        'date' => $request->date,
        'description' => $request->description,
    ]);

    return redirect()->route('liste_transactions')->with('success', 'Transaction mise à jour avec succès.');
    } catch (Exception $e) {
        // Logger l’erreur pour analyse technique
        dd($e->getMessage());
        Log::error('Erreur lors de l\'enregistrement de la transaction : ' . $e->getMessage());

        // Rediriger avec un message d’erreur
        return back()->withErrors(['error' => 'Une erreur est survenue lors de l\'enregistrement de la transaction. Veuillez réessayer.']);
    }
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








