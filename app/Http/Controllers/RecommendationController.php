<?php

namespace App\Http\Controllers;

use App\Jobs\AnalyzeRecommendationJob;
use App\Models\Plat;
use App\Models\Recommendation;
use App\Services\RecommendationService;

class RecommendationController extends Controller
{
    public function analyze(Plat $plat, RecommendationService $service)
    {
        $user = auth()->user();

        //create a recommendation
        $recommendation = Recommendation::create([
            'user_id' => $user->id,
            'plat_id' => $plat->id,
            'status' => 'processing',
        ]);

        //dispatch a job to analyze the plat
        AnalyzeRecommendationJob::dispatch($recommendation);

        return response()->json([
            'message' => 'Analysis started',
            'recommendation_id' => $recommendation->id,
            'status' => 'processing'
        ], 202);

    }

    public function show(Recommendation $recommendation)
    {
        $this->authorize('view', $recommendation);

        return response()->json($recommendation);
    }
}
