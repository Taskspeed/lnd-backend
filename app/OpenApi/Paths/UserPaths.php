<?php

// app/OpenApi/Paths/UserPaths.php
namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

class UserPaths
{
    #[OA\Get(
        path: "/index",
        summary: "Get list of all users",
        tags: ["Users"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Successful response",
                content: new OA\JsonContent(
                    type: "array",
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "id", type: "integer", example: 1),
                            new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                            new OA\Property(property: "email", type: "string", example: "juan@example.com"),
                            new OA\Property(property: "created_at", type: "string", format: "date-time"),
                            new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                        ],
                        type: "object"
                    )
                )
            ),
        ]
    )]
    public function index() {}
}