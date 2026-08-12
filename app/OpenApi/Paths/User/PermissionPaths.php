<?php

namespace App\OpenApi\Paths\User;

use OpenApi\Attributes as OA;

class PermissionPaths
{
    #[OA\Get(
        path: "/api/permission/index",
        summary: "List all permissions",
        description: "Returns all permissions defined in the system.",
        operationId: "listPermissions",
        tags: ["Permissions"],
        responses: [
            new OA\Response(
                response: 200,
                description: "Permissions fetched successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permission fetch successfully"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 2),
                                    new OA\Property(property: "name", type: "string", example: "view_reports"),
                                    new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                                    new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-06T01:02:14.460000Z"),
                                    new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2026-08-06T01:02:14.460000Z"),
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
        path: "/api/permission/store",
        summary: "Create a new permission",
        description: "Creates a new permission. Name must be unique across permissions.",
        operationId: "createPermission",
        tags: ["Permissions"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "delete_users"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Permission created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permission created successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 4),
                                new OA\Property(property: "name", type: "string", example: "delete_users"),
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

    #[OA\Put(
        path: "/api/permission/update/{permissionId}",
        summary: "Update a permission",
        description: "Updates the name of an existing permission.",
        operationId: "updatePermission",
        tags: ["Permissions"],
        parameters: [
            new OA\Parameter(name: "permissionId", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 4),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "manage_users"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Permission updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permission update successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 4),
                                new OA\Property(property: "name", type: "string", example: "manage_users"),
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
                description: "Permission not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Permission not found"),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
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
        path: "/api/permission/destroy/{permissionId}",
        summary: "Delete a permission",
        description: "Permanently deletes a permission.",
        operationId: "deletePermission",
        tags: ["Permissions"],
        parameters: [
            new OA\Parameter(name: "permissionId", in: "path", required: true, schema: new OA\Schema(type: "integer"), example: 4),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Permission deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Permission deleted successfully"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 4),
                                new OA\Property(property: "name", type: "string", example: "manage_users"),
                                new OA\Property(property: "guard_name", type: "string", example: "sanctum"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Permission not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "No query results for model [Spatie\\Permission\\Models\\Permission] 99"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}