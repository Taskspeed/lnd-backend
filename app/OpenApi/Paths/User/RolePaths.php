<?php

namespace App\OpenApi\Paths\User;

use OpenApi\Attributes as OA;

class RolePaths
{
    #[OA\Get(
        path: "/api/role/index",
        summary: "List all roles",
        description: "Returns all roles defined in the system.",
        operationId: "listRoles",
        tags: ["Roles"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Roles fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Role fetch successfully"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "name", type: "string", example: "admin"),
                                    new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-06T01:00:59.563000Z"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2026-08-06T01:00:59.563000Z"),
                                ]
                            )
                        ),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/role/store",
        summary: "Create a new role",
        description: "Creates a new role. Name must be unique across roles.",
        operationId: "createRole",
        tags: ["Roles"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "supervisor"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Role created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Role created successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 2),
                                new OA\Property(property: "name", type: "string", example: "supervisor"),
                                new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error (e.g. duplicate name)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The name has already been taken."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "name" => ["The name has already been taken."],
                        ]),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Get(
        path: "/api/role/show/{roleId}",
        summary: "Get a single role",
        description: "Returns a single role by ID.",
        operationId: "showRole",
        tags: ["Roles"],
        parameters: [
            new OA\Parameter(name: "roleId", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 1),
        ],
        responses: [
            new OA\Response(
                response: 201,
                description: "Role fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Role show successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "name", type: "string", example: "admin"),
                                new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Role not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "No query results for model [Spatie\\Permission\\Models\\Role] 99"),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    #[OA\Put(
        path: "/api/role/update/{roleId}",
        summary: "Update a role",
        description: "Updates the name of an existing role. Name must remain unique across roles.",
        operationId: "updateRole",
        tags: ["Roles"],
        parameters: [
            new OA\Parameter(name: "roleId", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 1),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "senior_supervisor"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Role updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Role updated successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 2),
                                new OA\Property(property: "name", type: "string", example: "senior_supervisor"),
                                new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                                new OA\Property(property: "created_at", type: "string", format: "date-time"),
                                new OA\Property(property: "updated_at", type: "string", format: "date-time"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Role not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Role not found"),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error (e.g. duplicate name)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The name has already been taken."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "name" => ["The name has already been taken."],
                        ]),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/role/destroy/{roleId}",
        summary: "Delete a role",
        description: "Permanently deletes a role.",
        operationId: "deleteRole",
        tags: ["Roles"],
        parameters: [
            new OA\Parameter(name: "roleId", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 2),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Role deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Role deleted successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 2),
                                new OA\Property(property: "name", type: "string", example: "senior_supervisor"),
                                new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Role not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "No query results for model [Spatie\\Permission\\Models\\Role] 99"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}