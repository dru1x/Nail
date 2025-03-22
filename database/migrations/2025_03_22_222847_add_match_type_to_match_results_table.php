<?php

use App\Enums\MatchType;
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
        Schema::table('match_results', function (Blueprint $table) {
            $table->string('match_type', 10)->after('winner_id')->default(MatchType::Scores)->index();
        });

        // Remove default value from column
        Schema::table('match_results', function (Blueprint $table) {
            $table->string('match_type', 10)->after('winner_id')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('match_results', function (Blueprint $table) {
            $table->dropColumn('match_type');
        });
    }
};
