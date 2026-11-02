<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('criteria', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('weight')->nullable();
            $table->timestamps();
        });

        Schema::create('sub_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('criteria_id')->constrained('criteria')->onDelete('cascade');
            $table->string('name');
            $table->float('global_weight')->nullable();
            $table->timestamps();
        });

        Schema::create('alternatives', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->float('final_score')->nullable();
            $table->timestamps();
        });

        Schema::create('alternative_sub_criteria', function (Blueprint $table) {
            $table->id();
            $table->foreignId('alternative_id')->constrained('alternatives')->onDelete('cascade');
            $table->foreignId('sub_criteria_id')->constrained('sub_criteria')->onDelete('cascade');
            $table->timestamps();
        });

        Schema::create('pairwise_comparisons', function (Blueprint $table) {
            $table->id();
            $table->string('comparison_type');
            $table->unsignedBigInteger('element_1');
            $table->unsignedBigInteger('element_2');
            $table->integer('saaty_value');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pairwise_comparisons');
        Schema::dropIfExists('alternative_sub_criteria');
        Schema::dropIfExists('alternatives');
        Schema::dropIfExists('sub_criteria');
        Schema::dropIfExists('criteria');
    }
};