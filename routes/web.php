<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\UserController;


Route::get('/', [AuthController::class, 'login'])->name('login');
Route::get('/register', [AuthController::class, 'register'])->name('register');
Route::post('/login', [AuthController::class, 'loginSubmit'])->name('login.submit');
Route::get('/accueil', [AuthController::class, 'home'])->name('home');
Route::resource('users', UserController::class);

Route::middleware(['auth'])->group(function () {
    Route::get('/liste_transactions', [TransactionController::class, 'allTransaction'])->name('liste_transactions');
    Route::post('/transactions', [TransactionController::class, 'store'])->name('transactions.store');
   Route::get('/transactions/{id}/edit', [TransactionController::class, 'edit'])->name('transactions.edit');
      Route::get('/transactions', [TransactionController::class, 'filter'])->name('transactions.filter');
    Route::delete('/transactions/{id}/destroy', [TransactionController::class, 'destroy'])->name('transactions.destroy');
    Route::put('/transactions/{id}/update', [TransactionController::class, 'update'])->name('transactions.update');
Route::get('/rapport/exporter/{annee_mois}', [TransactionController::class, 'exportPdf'])->name('transactions.rapport.pdf');


});
Route::get('/rapport-mensuel', [TransactionController::class, 'rapportMensuel'])->name('transactions.rapport');


Route::middleware(['auth'])->group(function () {
    Route::get('/categories', [CategoryController::class, 'index'])->name('categories.index');
    Route::post('/categories', [CategoryController::class, 'store'])->name('categories.store');
    Route::delete('/categories/{category}', [CategoryController::class, 'destroy'])->name('categories.destroy');
});