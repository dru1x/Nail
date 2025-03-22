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
        Schema::create('set_results', function (Blueprint $table) {
            $table->id();

            $table->foreignId('match_result_id')->constrained('match_results');
            $table->foreignId('winner_id')->nullable()->constrained('entries');

            $table->unsignedTinyInteger('sequence');

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('set_results');
    }
};
