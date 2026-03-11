<?php

namespace App\Swagger;

use OpenApi\Attributes as OA;

#[OA\Info(
    title: "Brigade API",
    version: "1.0.0",
    description: "API documentation for Brigade project"
)]
#[OA\Server(
    url: "http://127.0.0.1:8000",
    description: "Local server"
)]
class OpenApi
{
}