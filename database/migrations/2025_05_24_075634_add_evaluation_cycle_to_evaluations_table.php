<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEvaluationCycleToEvaluationsTable extends Migration
{
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->string('evaluation_cycle')->default('2025-Q1')->after('status');
        });

        // Update existing evaluations
        \DB::table('evaluations')->whereNull('evaluation_cycle')->update(['evaluation_cycle' => '2025-Q1']);
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->dropColumn('evaluation_cycle');
        });
    }
}