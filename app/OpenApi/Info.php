<?php

// app/OpenApi/Info.php
namespace App\OpenApi;

use OpenApi\Attributes as OA;

#[OA\Info(
    version: "1.0.0",
    title: "SPMS API Documentation",
    description: "API documentation for Strategic Performance Management System"
)]
#[OA\Server(
    url: "http://192.168.8.182:7000",
    description: "Local development server"
)]
#[OA\SecurityScheme(
    securityScheme: "sanctum",
    type: "apiKey",
    in: "header",
    name: "Authorization",
    description: "Enter token in format: Bearer {token}"
)]
class Info
{
    // Walang laman — annotation class lang ito
}