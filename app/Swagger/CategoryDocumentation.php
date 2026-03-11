<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class CategoryDocumentation
{
    #[OA\Get(
        path: "/api/categories",
        summary: "Get all categories for authenticated user",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "List of categories"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/categories",
        summary: "Create new category",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Pizza")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Category created successfully"
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function store() {}


    #[OA\Put(
        path: "/api/categories/{id}",
        summary: "Update category",
        tags: ["Categories"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "id",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer")
            )
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Updated Category")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Category updated successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            )
        ]
    )]
    public function update() {}


    #[OA\Delete(
        path: "/api/categories/{id}",
        summary: "Delete category",
        tags: ["Categories"],
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
                description: "Category deleted successfully"
            ),
            new OA\Response(
                response: 404,
                description: "Category not found"
            )
        ]
    )]
    public function destroy() {}
}
