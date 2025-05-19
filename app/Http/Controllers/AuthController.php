<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Categorie;
use App\Models\Transaction;

class AuthController extends Controller
{
    public function login(){
        return view("auth.login");
    }

    public function loginSubmit(Request $request)
{
    $credentials = $request->validate([
        'username' => ['required', 'string'],
        'password' => ['required', 'string'],
    ]);

    if (Auth::attempt(['name' => $credentials['username'], 'password' => $credentials['password']])) {
        $request->session()->regenerate();
        return redirect()->intended('/accueil'); // rediriger vers un espace protégé
    }

    return back()->withErrors([
        'username' => 'Nom d\'utilisateur ou mot de passe incorrect.',
    ]);
}


    public function register(){
        return view("auth.register");
    }

    public function home() {
    \Carbon\Carbon::setLocale('fr');

    $transactions = Transaction::with('categorie')->orderBy('date', 'desc')->paginate(3);
    $categories = Categorie::all();

    $totalDepot = Transaction::where('type', 'depot')->sum('montant');
    $totalRetrait = Transaction::where('type', 'debit')->sum('montant');

    return view('dashboard', compact('categories', 'transactions', 'totalDepot', 'totalRetrait'));
}


}
