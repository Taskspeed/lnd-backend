<?php

namespace App\OpenApi\Paths\User;

use OpenApi\Attributes as OA;

/**
 * Documents UserController endpoints.
 * Routes verified against controller methods (routes/api.php `user` prefix group):
 *   PUT    /user/edit/{userId}   -> edit()
 *   DELETE /user/delete/{userId} -> destroy()
 *
 * Note: index() has no route registered in the provided routes/api.php
 * snippet. Path below is a placeholder — please confirm the actual route.
 */
class UserPaths
{
    #[OA\Get(
        path: "/api/user/index",
        summary: "List all users",
        description: "Returns all users. Route not present in the provided routes/api.php snippet — please confirm the actual path.",
        operationId: "indexUser",
        tags: ["User"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success fetch"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                    new OA\Property(property: "username", type: "string", example: "jdelacruz"),
                                    new OA\Property(property: "office", type: "string", example: "Records Office"),
                                    new OA\Property(property: "control_no", type: "string", example: "022485"),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Put(
        path: "/api/user/edit/{userId}",
        summary: "Edit a user's basic profile fields",
        description: "Updates name, username, control_no, and optionally password. Unlike AuthController::update(), this does not touch office, role, or permissions.",
        operationId: "editUser",
        tags: ["User"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "userId",
                description: "ID of the user to edit",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "username", "control_no"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                    new OA\Property(property: "username", type: "string", example: "jdelacruz"),
                    new OA\Property(property: "password", type: "string", format: "password", nullable: true, minLength: 5, description: "Optional. Only updated if provided.", example: "newSecret123"),
                    new OA\Property(property: "control_no", type: "string", example: "022485"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success edit"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "username", type: "string", example: "jdelacruz"),
                                new OA\Property(property: "control_no", type: "string", example: "022485"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error (e.g. duplicate username)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The username has already been taken."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'User not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "User not found"),
                    ]
                )
            ),
        ]
    )]
    public function edit() {}

    #[OA\Delete(
        path: "/api/user/delete/{userId}",
        summary: "Delete a user",
        description: "Permanently deletes a user record.",
        operationId: "destroyUser",
        tags: ["User"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "userId",
                description: "ID of the user to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success. Note: data returns the deleted user object (already removed from the database by the time it's serialized).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success deleted"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "username", type: "string", example: "jdelacruz"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'User not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "User not found"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}