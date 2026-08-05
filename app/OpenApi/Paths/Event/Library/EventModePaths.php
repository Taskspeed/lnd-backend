<?php

namespace App\OpenApi\Paths\Event\Library;

use OpenApi\Attributes as OA;

/**
 * Documents the EventModeController endpoints (routes/api.php `mode` group).
 * Routes verified against controller methods — no mismatches found:
 *   GET    /mode/index          -> index()
 *   POST   /mode/store          -> store()
 *   PUT    /mode/update/{id}    -> update()
 *   DELETE /mode/delete/{id}    -> destroy()
 */
class EventModePaths
{
    #[OA\Get(
        path: "/api/mode/index",
        summary: "List all event modes",
        description: "Returns all event mode records. Returns an info message (still HTTP 200) if none exist.",
        operationId: "indexEventMode",
        tags: ["Event Mode (optional)"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success (records found or empty list)",
                content: new OA\JsonContent(
                    oneOf: [
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: "success", type: "boolean", example: true),
                                new OA\Property(property: "message", type: "string", example: "Success"),
                            ]
                        ),
                        new OA\Schema(
                            properties: [
                                new OA\Property(property: "success", type: "boolean", example: true),
                                new OA\Property(property: "message", type: "string", example: "No records found"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated."),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to retrieve event types"),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Post(
        path: "/api/mode/store",
        summary: "Create a new event mode",
        description: "Creates a new event mode record inside a DB transaction.",
        operationId: "storeEventMode",
        tags: ["Event Mode (optional)"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mode_name"],
                properties: [
                    new OA\Property(property: "mode_name", type: "string", example: "Hybrid"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Created successfully"),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated."),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The mode name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "mode_name" => ["The mode name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including a failed create (see EventModeService::create).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to create event mode"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: "/api/mode/update/{modeId}",
        summary: "Update an event mode",
        description: "Updates an existing event mode record by ID inside a DB transaction.",
        operationId: "updateEventMode",
        tags: ["Event Mode (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "modeId",
                description: "ID of the event mode to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["mode_name"],
                properties: [
                    new OA\Property(property: "mode_name", type: "string", example: "Online (Zoom)"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Updated successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Updated successfully"),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated."),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The mode name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "mode_name" => ["The mode name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventModeService::update).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event mode not found"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/mode/delete/{modeId}",
        summary: "Delete an event mode",
        description: "Deletes an event mode record by ID.",
        operationId: "destroyEventMode",
        tags: ["Event Mode (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "modeId",
                description: "ID of the event mode to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted successfully. 'data' contains the record's attributes as they were immediately before deletion.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Deleted successfully"),
                    ]
                )
            ),
            new OA\Response(
                response: 401,
                description: "Unauthenticated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Unauthenticated."),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventModeService::destroy).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event mode not found"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}