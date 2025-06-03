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
        Schema::table('transactions', function (Blueprint $table) {
             $table->decimal('montant_ht', 10, 2)->nullable()->after('montant');
            $table->decimal('montant_tva', 10, 2)->nullable()->after('montant_ht');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
         Schema::table('transactions', function (Blueprint $table) {
            $table->dropColumn(['montant_ht', 'montant_tva']);
        });
    }
};
