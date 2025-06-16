<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

class CleanupEvaluationScores extends Migration
{
    public function up()
    {
        // Set score to null for non-self evaluations
        DB::table('evaluations')
            ->whereIn('evaluation_type', ['peer', 'student'])
            ->update(['score' => null]);
    }

    public function down()
    {
        // No rollback needed, as this is a cleanup
    }
}
