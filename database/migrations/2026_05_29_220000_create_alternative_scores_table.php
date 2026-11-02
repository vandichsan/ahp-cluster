<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Create the alternative_scores table.
     * Stores AHP pairwise comparison eigenvector results for
     * each alternative against each criteria or sub-criteria node.
     */
    public function up(): void
    {
        Schema::create('alternative_scores', function (Blueprint $table) {
            $table->id();
            $table->string('node_type'); // 'criteria' or 'sub_criteria'
            $table->unsignedBigInteger('node_id'); // ID of the criteria or sub_criteria
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->float('score')->default(0); // eigenvector priority weight
            $table->timestamps();

            $table->unique(['node_type', 'node_id', 'alternative_id'], 'alt_score_unique');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('alternative_scores');
    }
};
