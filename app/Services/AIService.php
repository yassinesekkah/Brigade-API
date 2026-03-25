<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function getExplanation($platName, $ingredientTags, $userTags)
    {
        $ingredients = implode(', ', $ingredientTags);
        $restrictions = implode(', ', $userTags);

        $prompt = <<<PROMPT
Analyze the nutritional compatibility between this dish and the user's dietary restrictions.

DISH: {$platName}
INGREDIENT TAGS: {$ingredients}
USER RESTRICTIONS: {$restrictions}

Tag mapping rules:
"vegan" restriction conflicts with: contains_meat, contains_lactose
"no_sugar" restriction conflicts with: contains_sugar
"no_cholesterol" restriction conflicts with: contains_cholesterol
"gluten_free" restriction conflicts with: contains_gluten
"no_lactose" restriction conflicts with: contains_lactose

Calculate score: start at 100, subtract 25 for each conflict found.

Respond ONLY with this JSON (no markdown):
{"score": <0-100>, "warning_message": "<in French if score < 50, else empty string>"}
PROMPT;

        try {

            logger('GROQ KEY: ' . env('GROQ_API_KEY')); 

            $response = Http::withOptions([
                'verify' => false,
            ])->withHeaders([
                'Authorization' => 'Bearer ' . config('services.groq.key'),
                'Content-Type' => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                "model" => "llama-3.3-70b-versatile", 
                "messages" => [
                    [
                        "role" => "user",
                        "content" => $prompt
                    ]
                ],
                "temperature" => 0
            ]);

            $data = $response->json();

            logger($data); 

            return $data;
        } catch (\Throwable $e) {
            logger('AI ERROR: ' . $e->getMessage());
            return null;
        }
    }
}
