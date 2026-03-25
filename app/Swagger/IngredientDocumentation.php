<?php 

namespace App\Swagger;

use OpenApi\Attributes as OA;

class IngredientDocumentation
{
    #[OA\Get(
    path: "/api/ingredients",
    operationId: "getIngredients",
    summary: "Get all ingredients",
    tags: ["Ingredients"],
    security: [["sanctum" => []]],
    responses: [
        new OA\Response(
            response: 200,
            description: "List of ingredients"
        ),
        new OA\Response(
            response: 403,
            description: "Unauthorized"
        )
    ]
)]
public function index() {}

#[OA\Post(
    path: "/api/ingredients",
    operationId: "storeIngredient",
    summary: "Create a new ingredient",
    tags: ["Ingredients"],
    security: [["sanctum" => []]],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            required: ["name"],
            properties: [
                new OA\Property(property: "name", type: "string", example: "Milk"),
                new OA\Property(
                    property: "tags",
                    type: "array",
                    items: new OA\Items(type: "string"),
                    example: ["contains_lactose"]
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 201,
            description: "Ingredient created successfully"
        ),
        new OA\Response(
            response: 422,
            description: "Validation error"
        )
    ]
)]
public function store() {}


#[OA\Put(
    path: "/api/ingredients/{ingredient}",
    operationId: "updateIngredient",
    summary: "Update an ingredient",
    tags: ["Ingredients"],
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "ingredient",
            in: "path",
            required: true,
            description: "Ingredient ID",
            schema: new OA\Schema(type: "integer")
        )
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "name", type: "string", example: "Cheese"),
                new OA\Property(
                    property: "tags",
                    type: "array",
                    items: new OA\Items(type: "string"),
                    example: ["contains_lactose"]
                )
            ]
        )
    ),
    responses: [
        new OA\Response(
            response: 200,
            description: "Ingredient updated successfully"
        ),
        new OA\Response(
            response: 404,
            description: "Ingredient not found"
        ),
        new OA\Response(
            response: 422,
            description: "Validation error"
        )
    ]
)]
public function update() {}


#[OA\Delete(
    path: "/api/ingredients/{ingredient}",
    operationId: "deleteIngredient",
    summary: "Delete an ingredient",
    tags: ["Ingredients"],
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "ingredient",
            in: "path",
            required: true,
            description: "Ingredient ID",
            schema: new OA\Schema(type: "integer")
        )
    ],
    responses: [
        new OA\Response(
            response: 200,
            description: "Ingredient deleted successfully"
        ),
        new OA\Response(
            response: 404,
            description: "Ingredient not found"
        )
    ]
)]
public function destroy() {}

}