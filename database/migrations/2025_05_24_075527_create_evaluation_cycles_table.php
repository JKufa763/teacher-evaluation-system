<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEvaluationCyclesTable extends Migration
{
    public function up()
    {
        Schema::create('evaluation_cycles', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique(); // e.g., '2025-Q1'
            $table->boolean('is_active')->default(false);
            $table->timestamps();
        });

        // Set initial cycle
        \DB::table('evaluation_cycles')->insert([
            'name' => '2025-Q1',
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('evaluation_cycles');
    }
}