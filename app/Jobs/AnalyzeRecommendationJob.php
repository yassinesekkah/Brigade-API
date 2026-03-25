<?php

namespace App\Jobs;

use App\Models\Recommendation;
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
    public function __construct(Recommendation $recommendation)
    {
        $this->recommendation = $recommendation;
    }

    /**
     * Execute the job.
     */
    public function handle(
        RecommendationService $service,
        AIService $aiService
    ): void {

        // relations
        $user = $this->recommendation->user;
        $plat = $this->recommendation->plat;

        // calculate score (code)
        $result = $service->calculateScore($user, $plat);

        //collect ingredient tags
        $ingredientTags = $plat->ingredients
            ->flatMap(fn($ingredient) => $ingredient->tags ?? [])
            ->values()
            ->toArray();

        // call AI (Groq)
        try {
            $aiResponse = $aiService->getExplanation(
                $plat->name,
                $ingredientTags,
                $user->dietary_tags ?? []
            );

            
            logger($aiResponse);

            $explanation = $aiResponse['choices'][0]['message']['content'] ?? null;

        } catch (\Throwable $e) {
            
            logger($e->getMessage());
            $explanation = null;
        }

        // 🔥 4. update recommendation
        $this->recommendation->update([
            'score' => $result['score'], // ✔️ من code
            'warning_message' => $explanation,
            'status' => 'ready',
        ]);
    }
}