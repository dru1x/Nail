<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scores', function (Blueprint $table) {
            $table->id();

            $table->foreignId('match_result_id')->constrained('match_results');
            $table->foreignId('entry_id')->constrained('entries');

            $table->string('side', 5)->index();
            $table->unsignedTinyInteger('handicap_before')->default(0);
            $table->unsignedTinyInteger('handicap_after')->default(0);
            $table->unsignedMediumInteger('allowance')->default(0);
            $table->unsignedTinyInteger('match_points')->default(0);
            $table->unsignedMediumInteger('match_points_adjusted')->default(0);
            $table->unsignedTinyInteger('bonus_points')->default(0);
            $table->unsignedTinyInteger('league_points')->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scores');
    }
};
