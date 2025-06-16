<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MakeRatingsNullable extends Migration
{
    public function up()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->integer('knowledge_rating')->nullable()->change();
            $table->integer('teaching_skill')->nullable()->change();
            $table->integer('communication')->nullable()->change();
            $table->integer('punctuality')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('evaluations', function (Blueprint $table) {
            $table->integer('knowledge_rating')->nullable(false)->default(0)->change();
            $table->integer('teaching_skill')->nullable(false)->default(0)->change();
            $table->integer('communication')->nullable(false)->default(0)->change();
            $table->integer('punctuality')->nullable(false)->default(0)->change();
        });
    }
}