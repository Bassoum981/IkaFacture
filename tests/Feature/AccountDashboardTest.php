<?php

namespace Tests\Feature;

use App\Models\Facture;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountDashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_is_redirected_to_login_from_dashboard(): void
    {
        $this->get(route('account.dashboard'))
            ->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_only_their_invoices(): void
    {
        $user = User::factory()->create();
        $otherUser = User::factory()->create();

        Facture::create([
            'user_id' => $user->id,
            'numero_facture' => 'FAC-2026-0001',
            'date_emission' => now(),
            'client_nom' => 'Mon client',
            'total_ttc' => 125000,
            'statut' => 'generee',
        ]);
        Facture::create([
            'user_id' => $otherUser->id,
            'numero_facture' => 'FAC-2026-0002',
            'date_emission' => now(),
            'client_nom' => 'Client privé',
            'total_ttc' => 90000,
            'statut' => 'generee',
        ]);

        $this->actingAs($user)
            ->get(route('account.dashboard'))
            ->assertOk()
            ->assertSee('FAC-2026-0001')
            ->assertSee('Mon client')
            ->assertDontSee('FAC-2026-0002')
            ->assertDontSee('Client privé');
    }
}
