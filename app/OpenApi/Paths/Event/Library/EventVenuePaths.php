<?php

namespace App\OpenApi\Paths\Event\Library;

use OpenApi\Attributes as OA;

/**
 * Documents the EventVenueController endpoints (routes/api.php `venue` group).
 * Routes verified against controller methods — no mismatches found:
 *   GET    /venue/index          -> index()
 *   POST   /venue/store          -> store()
 *   PUT    /venue/update/{id}    -> update()
 *   DELETE /venue/delete/{id}    -> destroy()
 */
class EventVenuePaths
{
    #[OA\Get(
        path: "/api/venue/index",
        summary: "List all event venues",
        description: "Returns all event venue records. Returns an info message (still HTTP 200) if none exist.",
        operationId: "indexEventvenue",
        tags: ["Event Venue (optional)"],
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
        path: "/api/venue/store",
        summary: "Create a new event venue",
        description: "Creates a new event venue record inside a DB transaction.",
        operationId: "storeEventvenue",
        tags: ["Event Venue (optional)"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["venue_name"],
                properties: [
                    new OA\Property(property: "venue_name", type: "string", example: "Hybrid"),
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
                        new OA\Property(property: "message", type: "string", example: "The venue name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "venue_name" => ["The venue name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including a failed create (see EventvenueService::create).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to create event venue"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: "/api/venue/update/{venueId}",
        summary: "Update an event venue",
        description: "Updates an existing event venue record by ID inside a DB transaction.",
        operationId: "updateEventvenue",
        tags: ["Event Venue (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "venueId",
                description: "ID of the event venue to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["venue_name"],
                properties: [
                    new OA\Property(property: "venue_name", type: "string", example: "Online (Zoom)"),
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
                        new OA\Property(property: "message", type: "string", example: "The venue name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "venue_name" => ["The venue name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventvenueService::update).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event venue not found"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/venue/delete/{venueId}",
        summary: "Delete an event venue",
        description: "Deletes an event venue record by ID.",
        operationId: "destroyEventvenue",
        tags: ["Event Venue (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "venueId",
                description: "ID of the event venue to delete",
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
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventvenueService::destroy).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event venue not found"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}