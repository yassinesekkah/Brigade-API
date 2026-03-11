<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Brigade API",
    version: "1.0.0",
    description: "API documentation for Brigade restaurant management system"
)]

#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Local server"
)]

#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "JWT"
)]

class OpenApi
{
}