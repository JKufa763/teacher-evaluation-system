<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Grade;
use Illuminate\Support\Facades\DB;

class GradeSeeder extends Seeder
{
    public function run()
    
    {
        DB::table('grades')->insert([
            ['id' => 1, 'name' => 'Grade 10'],
            ['id' => 2, 'name' => 'Grade 11'],
            // Add more grades if necessary
        ]);
    }
}