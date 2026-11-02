<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\FactureController;
use App\Http\Controllers\SeoController;
use Illuminate\Support\Facades\Route;

Route::view('/', 'welcome')->name('home');
Route::get('/creer-facture', [FactureController::class, 'create'])->name('facture.create');
Route::get('/robots.txt', [SeoController::class, 'robots'])->name('seo.robots');
Route::get('/sitemap.xml', [SeoController::class, 'sitemap'])->name('seo.sitemap');

// Route pour enregistrer/generer la facture
Route::post('/factures', [FactureController::class, 'store'])->name('factures.store');
Route::post('/factures/register', [FactureController::class, 'registerAfterFacture'])->name('factures.register');
Route::view('/mentions-legales', 'legal.notice')->name('legal.notice');

Route::middleware('guest')->group(function (): void {
    Route::get('/connexion', [AccountController::class, 'loginForm'])->name('login');
    Route::post('/connexion', [AccountController::class, 'login'])->name('login.store');
});

Route::middleware('auth')->group(function (): void {
    Route::get('/tableau-de-bord', [AccountController::class, 'dashboard'])->name('account.dashboard');
    Route::get('/factures/{facture}/pdf', [FactureController::class, 'download'])->name('factures.download');
    Route::post('/deconnexion', [AccountController::class, 'logout'])->name('logout');
});
