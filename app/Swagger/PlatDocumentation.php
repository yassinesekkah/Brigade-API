<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class PlatDocumentation
{
    #[OA\Get(
        path: "/api/plats",
        operationId: "getPlats",
        summary: "Get all plats",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of plats"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function index() {}


    #[OA\Post(
        path: "/api/plats",
        operationId: "createPlat",
        summary: "Create new plat",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\MediaType(
                mediaType: "multipart/form-data",
                schema: new OA\Schema(
                    required: ["name", "price", "category_id"],
                    properties: [
                        new OA\Property(property: "name", type: "string", example: "Pizza Margherita"),
                        new OA\Property(property: "description", type: "string", example: "Italian pizza"),
                        new OA\Property(property: "price", type: "number", example: 12.5),
                        new OA\Property(property: "category_id", type: "integer", example: 1),
                        new OA\Property(property: "image", type: "string", format: "binary")
                    ]
                )
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Plat created successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store() {}


    #[OA\Get(
        path: "/api/plats/{id}",
        operationId: "getPlat",
        summary: "Get single plat",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Plat details"
            ),
            new OA\Response(
                response: 404,
                description: "Plat not found"
            )
        ]
    )]
    public function show() {}


    #[OA\Put(
        path: "/api/plats/{id}",
        operationId: "updatePlat",
        summary: "Update plat",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Plat updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Plat not found"
            )
        ]
    )]
    public function update() {}


    #[OA\Delete(
        path: "/api/plats/{id}",
        operationId: "deletePlat",
        summary: "Delete plat",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Plat deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Plat not found"
            )
        ]
    )]
    public function destroy() {}


    #[OA\Get(
        path: "/api/categories/{category}/plats",
        operationId: "getPlatsByCategory",
        summary: "Get all plats belonging to a specific category",
        tags: ["Plats"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "category",
                in: "path",
                required: true,
                description: "Category ID",
                schema: new OA\Schema(type: "integer")
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of plats for the category"
            ),
            new OA\Response(
                response: 403,
                description: "Unauthorized"
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            )
        ]
    )]
    public function platsByCategory() {}
}
