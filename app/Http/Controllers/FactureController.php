<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Facture;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class FactureController extends Controller
{
    public function create()
    {
        return view('factures.create');
    }

    public function store(Request $request)
    {
        // 1. Décodage des données JSON envoyées via FormData depuis le JS
        $clientData = json_decode($request->input('client'), true);
        $itemsData = json_decode($request->input('items'), true);
        $tauxTva = $request->input('tauxTva', 0);
        $remise = $request->input('remise', 0);

        // On fusionne les données décodées pour que la validation Laravel les lise correctement
        $request->merge([
            'client_nom' => $clientData['nom'] ?? null,
            'client_adresse' => $clientData['adresse'] ?? null,
            'items' => $itemsData,
            'tauxTva' => $tauxTva,
            'remise' => $remise,
        ]);

        // 2. Validation des données (incluant les nouveaux champs flexibles)
        $request->validate([
            'client_nom' => 'required|string|max:255',
            'client_adresse' => 'nullable|string',
            'tauxTva' => 'required|numeric|min:0',
            'remise' => 'nullable|numeric|min:0',
            'date_echeance' => 'nullable|date',
            'conditions_reglement' => 'nullable|string|max:255',
            'notes' => 'nullable|string',
            'items' => 'required|array|min:1',
            'items.*.description' => 'required|string',
            'items.*.quantite' => 'required|integer|min:1',
            'items.*.prix_unitaire' => 'required|numeric|min:0',
        ]);

        // 3. Gestion optionnelle du logo
        $logoPath = null;
        if ($request->hasFile('logo')) {
            $logoPath = $request->file('logo')->store('logos', 'public');
        }

        // 4. Calculs financiers sécurisés côté serveur
        $sousTotal = 0;
        foreach ($itemsData as $item) {
            $sousTotal += ($item['quantite'] * $item['prix_unitaire']);
        }

        // Application de la remise globale (le total HT après remise ne peut pas être négatif)
        $baseCalcul = max(0, $sousTotal - $remise);

        $montantTva = ($baseCalcul * $tauxTva) / 100;
        $totalTtc = $baseCalcul + $montantTva;

        // Génération d'un numéro de facture unique (ex: FAC-2026-0001)
        $numeroFacture = 'FAC-' . date('Y') . '-' . str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT);

        // 5. Enregistrement de la facture principale avec les champs flexibles
        $facture = Facture::create([
            'user_id' => auth()->id(), // null pour les invités
            'numero_facture' => $numeroFacture,
            'date_emission' => now(),
            'date_echeance' => $request->input('date_echeance'),
            'conditions_reglement' => $request->input('conditions_reglement', 'Paiement comptant'),
            'client_nom' => $clientData['nom'],
            'client_adresse' => $clientData['adresse'] ?? null,
            'taux_tva' => $tauxTva,
            'total_ht' => $sousTotal,
            'remise' => $remise,
            'montant_tva' => $montantTva,
            'total_ttc' => $totalTtc,
            'notes' => $request->input('notes'),
            'statut' => 'generee',
        ]);

        // 6. Enregistrement des lignes de la facture
        foreach ($itemsData as $item) {
            $facture->lignes()->create([
                'description' => $item['description'],
                'quantite' => $item['quantite'],
                'prix_unitaire' => $item['prix_unitaire'],
                'prix_total' => $item['quantite'] * $item['prix_unitaire'],
            ]);
        }

        // 7. Génération et téléchargement instantané du PDF
        $pdf = Pdf::loadView('factures.pdf', compact('facture', 'logoPath'));

        return $pdf->download("facture-{$facture->numero_facture}.pdf");
    }

    public function registerAfterFacture(Request $request)
    {
        // 1. Validation des données du formulaire d'inscription rapide
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        // 2. Création de l'utilisateur
        $user = \App\Models\User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => \Illuminate\Support\Facades\Hash::make($request->password),
        ]);

        // 3. Connexion automatique de l'utilisateur
        auth()->login($user);

        // 4. Rattachement de la dernière facture créée (via la session) si elle existe
        if (session()->has('last_facture_id')) {
            Facture::where('id', session('last_facture_id'))
                ->whereNull('user_id')
                ->update(['user_id' => $user->id]);
        }

        // 5. Retour de la réponse en JSON pour Alpine.js avec redirection
        return response()->json([
            'success' => true,
            'redirect' => route('factures.create')
        ]);
    }

}
