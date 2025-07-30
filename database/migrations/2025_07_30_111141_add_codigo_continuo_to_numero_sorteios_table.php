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
        Schema::table('numero_sorteios', function (Blueprint $table) {
            $table->unsignedBigInteger('codigo_continuo')->nullable()->unique()->after('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('numero_sorteios', function (Blueprint $table) {
            $table->dropColumn('codigo_continuo');
        });
    }
};
