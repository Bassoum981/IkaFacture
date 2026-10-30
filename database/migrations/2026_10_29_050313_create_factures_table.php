<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('factures', function (Blueprint $table) {
            $table->id();
            // Pour permettre la génération de pdf sans compte utilisateur
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained('clients')->nullOnDelete();

            // Identifiants et Dates
            $table->string('numero_facture')->unique();
            $table->date('date_emission');
            $table->date('date_echeance')->nullable(); // Présente ici

            // Coordonnées du client
            $table->string('client_nom');
            $table->string('client_eamil')->nullable();
            $table->string('client_adresse')->nullable();

            // Montants financiers
            $table->decimal('taux_tva', 5, 2)->default(0); // Ex: 18.00 pour 18%
            $table->decimal('total_ht', 12, 2)->default(0);
            $table->decimal('montant_tva', 12, 2)->default(0);
            $table->decimal('total_ttc', 12, 2)->default(0);

            $table->string('statut')->default('brouillon'); // brouillon, generee, payee
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('factures');
    }
};
