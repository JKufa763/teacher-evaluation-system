<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('teacher_class', function (Blueprint $table) {
        // Check if the column exists with wrong name
        if (Schema::hasColumn('teacher_class', 'class__id')) {
            $table->renameColumn('class__id', 'class_id');
        }
        
        // If column doesn't exist at all, add it
        if (!Schema::hasColumn('teacher_class', 'class_id')) {
            $table->unsignedBigInteger('class_id');
            $table->foreign('class_id')->references('id')->on('classes');
        }
    });
}

public function down()
{
    Schema::table('teacher_class', function (Blueprint $table) {
        $table->dropForeign(['class_id']);
        $table->dropColumn('class_id');
    });
}
};
