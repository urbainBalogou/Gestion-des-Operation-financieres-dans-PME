<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Categorie;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;


class UserController extends Controller
{
    public function index()
    {
        $users = User::latest()->paginate(10);
        $categories = Categorie::all();
        return view('users.index', compact('users','categories'));
    }

    public function create()
    {
        $categories = Categorie::all();
        return view('users.create',compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users',
        'password' => 'required|confirmed|min:6',
        'role' => 'required|in:user,admin', // on utilise le champ temporairement
    ]);

    $user = new User();
    $user->name = $request->name;
    $user->email = $request->email;
    $user->password = Hash::make($request->password);

    // Rôle basé sur remember_token
    $user->remember_token = $request->role === 'admin' ? Str::random(60) : null;

    $user->save();

    return redirect()->route('users.index')->with('success', 'Utilisateur créé.');
}

    public function edit(User $user)
    {
        $categories = Categorie::all();
        return view('users.edit', compact('user','categories'));
    }

   public function update(Request $request, User $user)
{
    $request->validate([
        'name' => 'required',
        'email' => 'required|email|unique:users,email,' . $user->id,
        'role' => 'required|in:user,admin',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;

    // Mettre à jour le rôle via remember_token
    $user->remember_token = $request->role === 'admin' ? Str::random(60) : null;

    $user->save();

    return redirect()->route('users.index')->with('success', 'Utilisateur mis à jour.');
}

    public function destroy(User $user)
    {
        $user->delete();

        return redirect()->route('users.index')->with('success', 'Utilisateur supprimé.');
    }
}

