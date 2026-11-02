<?php

namespace Tests\Feature;

use App\Models\Facture;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FactureNumberingTest extends TestCase
{
    use RefreshDatabase;

    public function test_invoice_numbers_are_sequential_and_keep_the_expected_format(): void
    {
        $payload = [
            'client' => json_encode(['nom' => 'Client numéro']),
            'items' => json_encode([[
                'description' => 'Prestation',
                'quantite' => 1,
                'prix_unitaire' => 1000,
            ]]),
            'tauxTva' => 18,
            'remise' => 0,
        ];

        $this->post(route('factures.store'), $payload)->assertOk();
        $this->post(route('factures.store'), $payload)->assertOk();

        $this->assertSame(
            ['FAC-'.now()->format('Y').'-0001', 'FAC-'.now()->format('Y').'-0002'],
            Facture::query()->orderBy('id')->pluck('numero_facture')->all(),
        );
    }
}
