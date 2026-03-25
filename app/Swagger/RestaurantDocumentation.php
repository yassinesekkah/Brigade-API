<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class RestaurantDocumentation
{
    #[OA\Post(
        path: "/api/restaurants",
        operationId: "storeRestaurant",
        summary: "Create a restaurant (admin only)",
        tags: ["Restaurants"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "My Restaurant"),
                    new OA\Property(property: "description", type: "string", example: "Best food in town"),
                    new OA\Property(property: "address", type: "string", example: "Casablanca")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Restaurant created successfully"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized or already has a restaurant"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store() {}


    #[OA\Get(
        path: "/api/restaurants/me",
        operationId: "getMyRestaurant",
        summary: "Get authenticated user's restaurant",
        tags: ["Restaurants"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "User restaurant data"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized"
            )
        ]
    )]
    public function me() {}
}
