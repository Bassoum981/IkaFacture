<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\FactureController;
use Illuminate\Support\Facades\Route;

// Afficher de l'editeur interactif
Route::get('/', [FactureController::class, 'create'])->name('facture.create');

// Route pour enregistrer/generer la facture
Route::post('/factures', [FactureController::class, 'store'])->name('factures.store');
Route::post('/factures/register', [FactureController::class, 'registerAfterFacture'])->name('factures.register');

Route::middleware('guest')->group(function (): void {
    Route::get('/connexion', [AccountController::class, 'loginForm'])->name('login');
    Route::post('/connexion', [AccountController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/tableau-de-bord', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::post('/deconnexion', [AccountController::class, 'logout'])->name('logout');
});
