<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('factures', 'client_eamil') && ! Schema::hasColumn('factures', 'client_email')) {
            Schema::table('factures', function (Blueprint $table): void {
                $table->renameColumn('client_eamil', 'client_email');
            });
        }

        if (Schema::hasColumn('clients', 'adresse')) {
            Schema::table('clients', function (Blueprint $table): void {
                $table->text('adresse')->nullable()->change();
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasColumn('factures', 'client_email') && ! Schema::hasColumn('factures', 'client_eamil')) {
            Schema::table('factures', function (Blueprint $table): void {
                $table->renameColumn('client_email', 'client_eamil');
            });
        }

        if (Schema::hasColumn('clients', 'adresse')) {
            Schema::table('clients', function (Blueprint $table): void {
                $table->timestamp('adresse')->nullable()->change();
            });
        }
    }
};
