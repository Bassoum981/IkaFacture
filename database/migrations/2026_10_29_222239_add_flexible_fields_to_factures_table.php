<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            // date_echeance a été supprimée d'ici car déjà créée au dessus
            $table->string('conditions_reglement')->default('Paiement comptant')->after('date_echeance');
            $table->decimal('remise', 10, 2)->default(0)->after('total_ht');
            $table->text('notes')->nullable()->after('montant_tva');
        });
    }

    public function down(): void
    {
        Schema::table('factures', function (Blueprint $table) {
            $table->dropColumn(['conditions_reglement', 'remise', 'notes']);
        });
    }
};
