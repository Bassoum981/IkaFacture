<?php

namespace App\Http\Controllers;

use App\Models\Facture;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;
use JsonException;

class FactureController extends Controller
{
    public function create()
    {
        return view('factures.create');
    }

    public function store(Request $request)
    {
        try {
            $clientData = json_decode($request->input('client', ''), true, 512, JSON_THROW_ON_ERROR);
            $itemsData = json_decode($request->input('items', ''), true, 512, JSON_THROW_ON_ERROR);
        } catch (JsonException) {
            throw ValidationException::withMessages([
                'client' => 'Les données du client sont invalides.',
                'items' => 'Les lignes de facture sont invalides.',
            ]);
        }

        if (! is_array($clientData) || ! is_array($itemsData)) {
            throw ValidationException::withMessages([
                'client' => 'Les données du client sont invalides.',
                'items' => 'Les lignes de facture sont invalides.',
            ]);
        }

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

        $sousTotal = 0;
        foreach ($itemsData as $item) {
            $sousTotal += ($item['quantite'] * $item['prix_unitaire']);
        }

        $baseCalcul = max(0, $sousTotal - $remise);
        $montantTva = ($baseCalcul * $tauxTva) / 100;
        $totalTtc = $baseCalcul + $montantTva;

        $facture = DB::transaction(function () use ($request, $clientData, $itemsData, $tauxTva, $remise, $sousTotal, $montantTva, $totalTtc) {
            $facture = Facture::create([
                'user_id' => auth()->id(),
                'numero_facture' => 'FAC-'.date('Y').'-'.str_pad(Facture::count() + 1, 4, '0', STR_PAD_LEFT),
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

            foreach ($itemsData as $item) {
                $facture->lignes()->create([
                    'description' => $item['description'],
                    'quantite' => $item['quantite'],
                    'prix_unitaire' => $item['prix_unitaire'],
                    'prix_total' => $item['quantite'] * $item['prix_unitaire'],
                ]);
            }

            return $facture;
        });

        session()->put('last_facture_id', $facture->id);

        $pdf = Pdf::loadView('factures.pdf', compact('facture', 'logoPath'));

        return $pdf->download("facture-{$facture->numero_facture}.pdf");
    }

    public function registerAfterFacture(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
        ]);

        $user = DB::transaction(function () use ($request) {
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
            ]);

            if ($factureId = session('last_facture_id')) {
                Facture::whereKey($factureId)
                    ->whereNull('user_id')
                    ->update(['user_id' => $user->id]);
            }

            return $user;
        });

        auth()->login($user);
        session()->forget('last_facture_id');

        return response()->json([
            'success' => true,
            'redirect' => route('facture.create'),
        ]);
    }
}
