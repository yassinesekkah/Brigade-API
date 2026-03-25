<?php

namespace App\Jobs;

use App\Services\AIService;
use App\Services\RecommendationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\SerializesModels;

class AnalyzeRecommendationJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $recommendation;
    /**
     * Create a new job instance.
     */
    public function __construct($recommendation)
    {
        $this->recommendation = $recommendation;
    }

    /**
     * Execute the job.
     */
    public function handle(RecommendationService $service, AIService $aiService): void
    {
        $user = $this->recommendation->user;
        $plat = $this->recommendation->plat;

        //calculate score
        $result = $service->calculateScore($user, $plat);

        $this->recommendation->update([
            'score' => $result['score'],
            'warning_message' => implode(', ', $result['conflicts']),
            'status' => 'ready',
        ]);
    }
}
