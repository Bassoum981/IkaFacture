<?php

use App\Http\Controllers\FactureController;
use Illuminate\Support\Facades\Route;


//Afficher de l'editeur interactif
Route::get('/',  [FactureController::class, 'create'])->name('facture.create');

//Route pour enregistrer/generer la facture
Route::post('/factures',  [FactureController::class, 'store'])->name('factures.store');
Route::post('/factures/register', [FactureController::class, 'registerAfterFacture'])->name('factures.register');
