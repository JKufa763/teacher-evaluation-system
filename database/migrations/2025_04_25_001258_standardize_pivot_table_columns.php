<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StandardizePivotTableColumns extends Migration
{
    public function up()
    {
        // Fix teacher_class table
        if (Schema::hasTable('teacher_class')) {
            Schema::table('teacher_class', function (Blueprint $table) {
                // Rename if exists with double underscore
                if (Schema::hasColumn('teacher_class', 'class__id')) {
                    $table->renameColumn('class__id', 'class_id');
                }
                // Add if missing
                elseif (!Schema::hasColumn('teacher_class', 'class_id')) {
                    $table->unsignedBigInteger('class_id')->after('teacher_id');
                    $table->foreign('class_id')->references('id')->on('classes');
                }
            });
        }

        // Similarly fix student_class table if needed
        if (Schema::hasTable('student_class')) {
            // Add similar logic if this table exists
        }
    }

    public function down()
    {
        // Not typically needed for production
    }
}