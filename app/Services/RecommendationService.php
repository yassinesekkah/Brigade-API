<?php

namespace App\Services;

use App\Models\Plat;
use App\Models\User;

class RecommendationService
{
    public function calculateScore(User $user, Plat $plat)
    {
        $userTags = $user->dietary_tags ?? [];

        $ingredientTags = $plat->ingredients
            ->flatMap(function ($ingredient) {
                return $ingredient->tags ?? [];
            })
            ->values();

        $map = [
            'no_sugar' => 'contains_sugar',
            'no_lactose' => 'contains_lactose',
            'gluten_free' => 'contains_gluten',
            'vegan' => 'contains_meat',
        ];

        $conflicts = [];

        foreach ($userTags as $tag) {
            if (isset($map[$tag])) {
                $forbidden = $map[$tag];

                if ($ingredientTags->contains($forbidden)) {
                    $conflicts[] = $forbidden;
                }
            }
        }

        $score = 100 - (count($conflicts) * 20);

        // label
        if ($score >= 80) {
            $label = 'Highly Recommended';
        } elseif ($score >= 50) {
            $label = 'Recommended with notes';
        } else {
            $label = 'Not Recommended';
        }

        return [
            'score' => max($score, 0),
            'label' => $label,
            'conflicts' => $conflicts
        ];
    }
}
