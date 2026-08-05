<?php

namespace App\OpenApi\Paths\Event\Library;

use OpenApi\Attributes as OA;

/**
 * Documents the EventTypeController endpoints (routes/api.php `mode` group).
 * Routes verified against controller methods — no mismatches found:
 *   GET    /type/index          -> index()
 *   POST   /type/store          -> store()
 *   PUT    /type/update/{id}    -> update()
 *   DELETE /type/delete/{id}    -> destroy()
 */
class EventTypePaths
{
    #[OA\Get(
        path: "/api/type/index",
        summary: "List all event types",
        description: "Returns all event type records. Returns an info message (still HTTP 200) if none exist.",
        operationId: "indexEventType",
        tags: ["Event Type (optional)"],
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
        path: "/api/type/store",
        summary: "Create a new event type",
        description: "Creates a new event type record inside a DB transaction.",
        operationId: "storeEventType",
        tags: ["Event Type (optional)"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["type_name"],
                properties: [
                    new OA\Property(property: "type_name", type: "string", example: "Webinar"),
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
                        new OA\Property(property: "message", type: "string", example: "The type name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "type_name" => ["The type name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to create event type"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: "/api/type/update/{typeId}",
        summary: "Update an event type",
        description: "Updates an existing event type record by ID inside a DB transaction.",
        operationId: "updateEventType",
        tags: ["Event Type (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "typeId",
                description: "ID of the event type to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 5)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["type_name"],
                properties: [
                    new OA\Property(property: "type_name", type: "string", example: "In-person Training"),
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
                        new OA\Property(property: "message", type: "string", example: "The type name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "type_name" => ["The type name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventTypeService::update).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event type not found"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/type/delete/{typeId}",
        summary: "Delete an event type",
        description: "Deletes an event type record by ID. NOTE: routes/api.php must point this route to the controller's destroy() method (currently references a non-existent 'delete' method).",
        operationId: "destroyEventType",
        tags: ["Event Type (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "typeId",
                description: "ID of the event type to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 5)
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
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventTypeService::destroy).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event type not found"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}


