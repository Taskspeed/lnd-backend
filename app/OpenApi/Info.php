<?php

// app/OpenApi/Info.php
namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "LND API Documentation",
    description: "API documentation for Learning and Development"
)]
#[OA\Server(
    url: "http://192.168.8.182:7000",
    description: "Local development server"
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "http",
    scheme: "bearer",
    bearerFormat: "Sanctum",
    description: "Enter your Sanctum token"
)]
class Info
{
    // Walang laman — annotation class lang ito
}