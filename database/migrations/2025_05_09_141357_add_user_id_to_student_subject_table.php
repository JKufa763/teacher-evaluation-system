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
    Schema::table('student_subject', function (Blueprint $table) {
        $table->foreignId('user_id')->nullable()->after('subject_id'); // Adjust as needed
    });
}

public function down()
{
    Schema::table('student_subject', function (Blueprint $table) {
        $table->dropColumn('user_id');
    });
}
};
