<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('sub_criteria', function (Blueprint $table) {
            $table->float('local_weight')->nullable()->after('global_weight');
        });
    }

    public function down(): void
    {
        Schema::table('sub_criteria', function (Blueprint $table) {
            $table->dropColumn('local_weight');
        });
    }
};
