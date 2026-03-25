<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class RecommendationDocumentation
{
    #[OA\Post(
        path: "/api/recommendations/analyze/{plat}",
        operationId: "analyzeRecommendation",
        summary: "Start analysis of a plat (async)",
        tags: ["Recommendations"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "plat",
                in: "path",
                required: true,
                description: "Plat ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 202,
                description: "Analysis started (processing)"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized"
            ),
            new OA\Response(
                response: 404,
                description: "Plat not found"
            )
        ]
    )]
    public function analyze() {}


    #[OA\Get(
        path: "/api/recommendations/{recommendation}",
        operationId: "getRecommendation",
        summary: "Get recommendation result",
        tags: ["Recommendations"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "recommendation",
                in: "path",
                required: true,
                description: "Recommendation ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Recommendation data"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized"
            ),
            new OA\Response(
                response: 404,
                description: "Recommendation not found"
            )
        ]
    )]
    public function show() {}


    #[OA\Get(
        path: "/api/recommendations",
        operationId: "getUserRecommendations",
        summary: "Get user recommendation history",
        tags: ["Recommendations"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of user recommendations"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized"
            )
        ]
    )]
    public function index() {}
}
