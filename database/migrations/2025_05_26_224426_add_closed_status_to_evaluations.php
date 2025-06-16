<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;

class AddClosedStatusToEvaluations extends Migration
{
    public function up()
    {
        DB::statement("ALTER TABLE evaluations MODIFY status ENUM('pending','reviewed','approved','rejected','closed') NOT NULL DEFAULT 'pending'");
    }

    public function down()
    {
        DB::statement("ALTER TABLE evaluations MODIFY status ENUM('pending','reviewed','approved','rejected') NOT NULL DEFAULT 'pending'");
    }
}