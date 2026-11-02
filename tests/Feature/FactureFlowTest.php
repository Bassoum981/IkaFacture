<?php

namespace Tests\Feature;

use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\View;
use Tests\TestCase;

class FactureFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_registration_attaches_the_invoice_saved_in_session(): void
    {
        $facture = Facture::create([
            'numero_facture' => 'FAC-2026-0001',
            'date_emission' => now(),
            'client_nom' => 'Client test',
            'statut' => 'generee',
        ]);

        $response = $this->withSession(['last_facture_id' => $facture->id])
            ->postJson(route('factures.register'), [
                'name' => 'Compte test',
                'email' => 'compte@example.test',
                'password' => 'password123',
            ]);

        $response->assertOk()
            ->assertJsonPath('success', true);
        $this->assertAuthenticated();
        $this->assertSame(auth()->id(), $facture->fresh()->user_id);
        $this->assertNull(session('last_facture_id'));
    }

    public function test_invalid_invoice_json_is_rejected(): void
    {
        $response = $this->post(route('factures.store'), [
            'client' => '{invalid',
            'items' => '[]',
        ]);

        $response->assertSessionHasErrors(['client', 'items']);
    }

    public function test_invoice_pdf_view_contains_the_preview_fields(): void
    {
        $facture = Facture::create([
            'numero_facture' => 'FAC-2026-0002',
            'date_emission' => now(),
            'date_echeance' => '2026-12-31',
            'conditions_reglement' => '30 jours',
            'client_nom' => 'Client PDF',
            'total_ht' => 100000,
            'remise' => 10000,
            'montant_tva' => 16200,
            'total_ttc' => 106200,
            'notes' => 'Merci pour votre confiance.',
            'taux_tva' => 18,
            'statut' => 'generee',
        ]);
        $facture->lignes()->create([
            'description' => 'Prestation PDF',
            'quantite' => 1,
            'prix_unitaire' => 100000,
            'prix_total' => 100000,
        ]);

        $html = View::make('factures.pdf', [
            'facture' => $facture->load('lignes'),
            'logoData' => null,
        ])->render();

        $this->assertStringContainsString('Base HT après remise', $html);
        $this->assertStringContainsString('31/12/2026', $html);
        $this->assertStringContainsString('30 jours', $html);
        $this->assertStringContainsString('Merci pour votre confiance.', $html);
    }

    public function test_invoice_creation_persists_calculated_amounts_and_lines(): void
    {
        $response = $this->post(route('factures.store'), [
            'client' => json_encode(['nom' => 'Client calcul']),
            'items' => json_encode([
                ['description' => 'Ligne 1', 'quantite' => 2, 'prix_unitaire' => 1500],
                ['description' => 'Ligne 2', 'quantite' => 1, 'prix_unitaire' => 1000],
            ]),
            'tauxTva' => 18,
            'remise' => 500,
        ]);

        $response->assertOk();
        $facture = Facture::query()->with('lignes')->firstOrFail();

        $this->assertEquals(4000, $facture->total_ht);
        $this->assertEquals(500, $facture->remise);
        $this->assertEquals(630, $facture->montant_tva);
        $this->assertEquals(4130, $facture->total_ttc);
        $this->assertCount(2, $facture->lignes);
        $this->assertEquals(3000, $facture->lignes->first()->prix_total);
        $this->assertSame($facture->id, session('last_facture_id'));
    }
}
