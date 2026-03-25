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

            $content = $aiResponse['choices'][0]['message']['content'] ?? null;

            preg_match('/\{.*\}/s', $content, $matches);
            $json = $matches[0] ?? null;

            $parsed = json_decode($json, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                logger('Invalid AI JSON: ' . $json);

                $aiScore = 0;
                $aiMessage = 'AI parsing error';
            } else {
                $aiScore = $parsed['score'] ?? 0;

                if (!is_numeric($aiScore)) {
                    logger('AI score is not numeric: ' . $aiScore);
                    $aiScore = 0;
                }
                $aiScore = max(0, min(100, (int)$aiScore)); // ensure score is between 0 and 100

                $aiMessage = isset($parsed['warning_message']) && is_string($parsed['warning_message'])
                    ? $parsed['warning_message']
                    : null;
            }
        } catch (\Throwable $e) {
            logger($e->getMessage());

            $aiScore = 0;
            $aiMessage = null;
        }

        // update recommendation
        $this->recommendation->update([
            'score' => $aiScore,
            'warning_message' => $aiMessage,
            'status' => 'ready',
        ]);
    }
}
