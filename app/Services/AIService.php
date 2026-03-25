<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;

class AIService
{
    public function getExplanation($platName, $ingridientsTags, $userTags)
    {
        $ingridents = implode(", ", $ingridientsTags);
        $restrictions = implode(", ", $userTags);

        $prompt = <<<PROMPT
            Analyze the nutritional compatibility between this dish and the user's dietary restrictions.

            DISH: {$platName}
            INGREDIENT TAGS: {$ingridents}
            USER RESTRICTIONS: {$restrictions}

            Tag mapping rules:
            "vegan" restriction conflicts with: contains_meat, contains_lactose
            "no_sugar" restriction conflicts with: contains_sugar
            "no_cholesterol" restriction conflicts with: contains_cholesterol
            "gluten_free" restriction conflicts with: contains_gluten
            "no_lactose" restriction conflicts with: contains_lactose

            Calculate score: start at 100, subtract 25 for each conflict found.

            Respond ONLY with this JSON (no markdown, no explanation):
            {"score": <0-100>, "warning_message": "<in French if score < 50, else empty string>"}
            PROMPT;

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . env('GROQ_API_KEY'),
            'Content-Type' => 'application/json'
        ])->post('https://api.groq.com/openai/v1/chat/completions', [
            "model" => "llama3-70b-8192",
            "messages" => [
                [
                    "role" => "user",
                    "content" => $prompt
                ]
            ]
        ]);

        return $response->json();
    }
}
