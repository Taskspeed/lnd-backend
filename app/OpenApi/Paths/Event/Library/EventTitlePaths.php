<?php

namespace App\OpenApi\Paths\Event\Library;

use OpenApi\Attributes as OA;

/**
 * Documents the EventTitleController endpoints (routes/api.php `title` group).
 * Routes verified against controller methods — no mismatches found:
 *   GET    /title/index          -> index()
 *   POST   /title/store          -> store()
 *   PUT    /title/update/{id}    -> update()
 *   DELETE /title/delete/{id}    -> destroy()
 */
class EventTitlePaths
{
    #[OA\Get(
        path: "/api/title/index",
        summary: "List all event titles",
        description: "Returns all event title records. Returns an info message (still HTTP 200) if none exist.",
        operationId: "indexEventtitle",
        tags: ["Event Title (optional)"],
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
        path: "/api/title/store",
        summary: "Create a new event title",
        description: "Creates a new event title record inside a DB transaction.",
        operationId: "storeEventtitle",
        tags: ["Event Title (optional)"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title_name"],
                properties: [
                    new OA\Property(property: "title_name", type: "string", example: "Hybrid"),
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
                        new OA\Property(property: "message", type: "string", example: "The title name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "title_name" => ["The title name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including a failed create (see EventtitleService::create).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to create event title"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: "/api/title/update/{titleId}",
        summary: "Update an event title",
        description: "Updates an existing event title record by ID inside a DB transaction.",
        operationId: "updateEventtitle",
        tags: ["Event Title (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "titleId",
                description: "ID of the event title to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 2)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title_name"],
                properties: [
                    new OA\Property(property: "title_name", type: "string", example: "Online (Zoom)"),
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
                        new OA\Property(property: "message", type: "string", example: "The title name field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "title_name" => ["The title name field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventtitleService::update).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event title not found"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/title/delete/{titleId}",
        summary: "Delete an event title",
        description: "Deletes an event title record by ID.",
        operationId: "destroyEventtitle",
        tags: ["Event Title (optional)"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "titleId",
                description: "ID of the event title to delete",
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
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventtitleService::destroy).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event title not found"),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}