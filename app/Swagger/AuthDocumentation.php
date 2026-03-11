<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

class AuthDocumentation
{
    #[OA\Post(
        path: "/api/register",
        summary: "Register a new user",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "email", "password"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Yassine"),
                    new OA\Property(property: "email", type: "string", example: "yassine@mail.com"),
                    new OA\Property(property: "password", type: "string", example: "123456")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "User registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "user", type: "object"),
                        new OA\Property(property: "token", type: "string", example: "1|xYzTokenExample")
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            )
        ]
    )]
    public function register() {}

    #[OA\Post(
        path: "/api/login",
        summary: "User login",
        tags: ["Auth"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["email", "password"],
                properties: [
                    new OA\Property(property: "email", type: "string", example: "yassine@mail.com"),
                    new OA\Property(property: "password", type: "string", example: "123456")
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "user", type: "object"),
                        new OA\Property(property: "token", type: "string", example: "1|exampleToken123")
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Invalid credentials"
            )
        ]
    )]
    public function login() {}


    #[OA\Post(
        path: "/api/logout",
        summary: "Logout authenticated user",
        tags: ["Auth"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "User logged out successfully"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function logout() {}


    #[OA\Get(
        path: "/api/user",
        summary: "Get authenticated user",
        tags: ["Auth"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Authenticated user data"
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated"
            )
        ]
    )]
    public function user() {}
}
