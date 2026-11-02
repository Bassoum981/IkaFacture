<?php

namespace Tests\Feature;

use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
