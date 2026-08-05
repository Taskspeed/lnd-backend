<?php

namespace App\OpenApi\Paths\Event\Library;

use OpenApi\Attributes as OA;

/**
 * Documents the EventSourceController endpoints (routes/api.php `source` group).
 * Routes verified against controller methods — no mismatches found:
 *   GET    /source/index          -> index()
 *   POST   /source/store          -> store()
 *   PUT    /source/update/{id}    -> update()
 *   DELETE /source/delete/{id}    -> destroy()
 */
class EventSourcePaths
{
    #[OA\Get(
        path: "/api/source/index",
        summary: "List all event sources",
        description: "Returns all event source records. Returns an info message (still HTTP 200) if none exist.",
        operationId: "indexEventsource",
        tags: ["Event Source (optional)"],
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
        path: "/api/source/store",
        summary: "Create a new event source",
        description: "Creates a new event source record inside a DB transaction.",
        operationId: "storeEventsource",
        tags: ["Event Source (optional)"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["source_name"],
                properties: [
                    new OA\Property(property: "source_name", type: "string", example: "Hybrid"),
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
                        new OA\Property(property: "message", type: "string", example: "The source name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "source_name" => ["The source name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including a failed create (see EventsourceService::create).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to create event source"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: "/api/source/update/{sourceId}",
        summary: "Update an event source",
        description: "Updates an existing event source record by ID inside a DB transaction.",
        operationId: "updateEventsource",
        tags: ["Event Source (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "sourceId",
                description: "ID of the event source to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["source_name"],
                properties: [
                    new OA\Property(property: "source_name", type: "string", example: "Online (Zoom)"),
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
                        new OA\Property(property: "message", type: "string", example: "The source name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "source_name" => ["The source name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventsourceService::update).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event source not found"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/source/delete/{sourceId}",
        summary: "Delete an event source",
        description: "Deletes an event source record by ID.",
        operationId: "destroyEventsource",
        tags: ["Event Source (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "sourceId",
                description: "ID of the event source to delete",
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
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventsourceService::destroy).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event source not found"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}