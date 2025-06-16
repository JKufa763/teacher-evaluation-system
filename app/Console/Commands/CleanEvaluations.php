<?php

namespace App\Console\Commands;

use App\Models\Evaluation;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

class CleanEvaluations extends Command
{
    protected $signature = 'evaluations:clean';
    protected $description = 'Reset incomplete evaluations with zero ratings';

    public function handle()
    {
        $count = Evaluation::where('completed', 0)
            ->where(function ($q) {
                $q->where('knowledge_rating', 0)
                  ->orWhere('teaching_skill', 0)
                  ->orWhere('communication', 0)
                  ->orWhere('punctuality', 0);
            })
            ->update([
                'knowledge_rating' => null,
                'teaching_skill' => null,
                'communication' => null,
                'punctuality' => null,
            ]);

        Log::info('Cleaned incomplete evaluations', ['count' => $count]);
        $this->info("Cleaned {$count} evaluations.");
    }
}