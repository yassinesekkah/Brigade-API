<?php

namespace App\Http\Controllers;

use App\Models\Plat;
use App\Services\RecommendationService;
use Illuminate\Http\Request;

class RecommendationController extends Controller
{
    public function analyze(Plat $plat, RecommendationService $service)
    {
        $user = auth()->user();

        $result = $service->calculateScore($user, $plat);

        return response()->json($result);
    }
}
