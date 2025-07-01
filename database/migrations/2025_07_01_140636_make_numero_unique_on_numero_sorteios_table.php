<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('numero_sorteios', function (Blueprint $table) {
            $table->unique('numero');
        });
    }

    public function down(): void
    {
        Schema::table('numero_sorteios', function (Blueprint $table) {
            $table->dropUnique(['numero']);
        });
    }
};
