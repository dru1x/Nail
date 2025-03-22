<?php

use App\Enums\ResultType;
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
        Schema::table('scores', function (Blueprint $table) {
            $table->dropForeign(['match_result_id']);
            $table->renameColumn('match_result_id', 'result_id');

            $table->string('result_type')->after('id')->default(ResultType::Match);
            $table->index(['result_type', 'result_id']);
        });

        Schema::table('scores', function (Blueprint $table) {
            $table->string('result_type')->after('id')->default(null)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('scores', function (Blueprint $table) {
            $table->dropIndex(['result_type', 'result_id']);
            $table->dropColumn('result_type');

            $table->renameColumn('result_id', 'match_result_id');

            $table->foreign('match_result_id')->references('id')->on('match_results');
        });
    }
};
