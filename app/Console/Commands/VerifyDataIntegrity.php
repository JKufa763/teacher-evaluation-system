<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class VerifyDataIntegrity extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:verify-data-integrity';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $invalidRelationships = DB::table('teacher_class')
            ->leftJoin('class_', 'teacher_class.class_id', '=', 'class_.id')
            ->whereNull('class_.id')
            ->count();

        if ($invalidRelationships > 0) {
            $this->error("Found $invalidRelationships invalid relationships!");
            DB::table('teacher_class')
                ->leftJoin('class_', 'teacher_class.class_id', '=', 'class_.id')
                ->whereNull('class_.id')
                ->delete();
            $this->info("Cleaned up invalid relationships");
        } else {
            $this->info("All relationships are valid");
        }
    }
}