<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class UpdateEvaluationsRatingsConstraints extends Migration
{
    public function up()
    {
        // Step 1: Set null values to 0 for all evaluations
        DB::table('evaluations')
            ->whereNull('knowledge_rating')
            ->update(['knowledge_rating' => 0]);

        DB::table('evaluations')
            ->whereNull('teaching_skill')
            ->update(['teaching_skill' => 0]);

        DB::table('evaluations')
            ->whereNull('communication')
            ->update(['communication' => 0]);

        DB::table('evaluations')
            ->whereNull('punctuality')
            ->update(['punctuality' => 0]);

        // Step 2: Apply not null constraint
        Schema::table('evaluations', function (Blueprint $table) {
            $table->integer('knowledge_rating')->nullable(false)->change();
            $table->integer('teaching_skill')->nullable(false)->change();
            $table->integer('communication')->nullable(false)->change();
            $table->integer('punctuality')->nullable(false)->change();
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->integer('knowledge_rating')->nullable()->change();
            $table->integer('teaching_skill')->nullable()->change();
            $table->integer('communication')->nullable()->change();
            $table->integer('punctuality')->nullable()->change();
        });
    }
}