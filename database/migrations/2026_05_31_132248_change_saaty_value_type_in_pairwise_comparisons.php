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
        Schema::table('pairwise_comparisons', function (Blueprint $table) {
            $table->float('saaty_value')->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pairwise_comparisons', function (Blueprint $table) {
            $table->integer('saaty_value')->change();
        });
    }
};
