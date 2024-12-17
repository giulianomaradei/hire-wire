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
        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->decimal('balance', 15, 2)->default(0);
            $table->morphs('accountable');
            $table->timestamps();
        });

        // Tabela específica para conta poupança
        Schema::create('savings_accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // Tabela específica para conta corrente
        Schema::create('checking_accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });

        // Tabela específica para conta de investimento
        Schema::create('investment_accounts', function (Blueprint $table) {
            $table->id();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('savings_accounts');
        Schema::dropIfExists('checking_accounts');
        Schema::dropIfExists('investment_accounts');
    }
};
