<?php

namespace App\OpenApi\Paths\Event;

use OpenApi\Attributes as OA;

/**
 * Documents the EventController endpoints (routes/api.php `event` group).
 * Routes assumed to follow the same convention as EventModeController — verify against
 * routes/api.php and adjust paths/operationIds if different:
 *   GET    /event/index            -> index()
 *   GET    /event/view/{eventId}   -> view()
 *   POST   /event/store            -> store()
 *   DELETE /event/delete/{eventId} -> destory()
 *
 * NOTE: EventCreateRequest rules were not provided — requestBody below is inferred
 * from the fields actually consumed in EventService::create(). Update if the real
 * validation rules differ (e.g. additional required fields, nested array rules).
 */
class EventPaths
{
    #[OA\Get(
        path: "/api/event/index",
        summary: "List all events",
        description: "Returns all event records (no relations eager loaded).",
        operationId: "indexEvent",
        tags: ["Event"],
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
                                    new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                    new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                    new OA\Property(property: "type_name", type: "string", example: "Seminar"),
                                    new OA\Property(property: "source_name", type: "string", example: "Internal"),
                                    new OA\Property(property: "created_by", type: "string", example: "Juan Dela Cruz"),
                                    new OA\Property(property: "status", type: "string", example: "Pending"),
                                ]
                            )
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
                        new OA\Property(property: "message", type: "string", example: "Server error message"),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Get(
        path: "/api/event/view/{eventId}",
        summary: "View a single event",
        description: "Returns a single event with its form, office, speaker, and schedule relations. Returns success with null data if not found (Eloquent find() returns null; not currently converted to a 404/error).",
        operationId: "viewEvent",
        tags: ["Event"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                description: "ID of the event to view",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
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
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                new OA\Property(property: "type_name", type: "string", example: "Seminar"),
                                new OA\Property(property: "source_name", type: "string", example: "Internal"),
                                new OA\Property(property: "created_by", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "status", type: "string", example: "Pending"),
                                new OA\Property(
                                    property: "form",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(property: "form_name", type: "string", example: "Attendance Sheet"),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: "office",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(property: "office_name", type: "string", example: "HRMO"),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: "speaker",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(property: "speaker_name", type: "string", example: "Dr. Jane Santos"),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: "schedule",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(property: "schedule_date", type: "string", format: "date", example: "2026-08-10"),
                                            new OA\Property(property: "morning_in", type: "string", example: "08:00"),
                                            new OA\Property(property: "morning_out", type: "string", example: "12:00"),
                                            new OA\Property(property: "afternoon_in", type: "string", example: "13:00"),
                                            new OA\Property(property: "afternoon_out", type: "string", example: "17:00"),
                                        ]
                                    )
                                ),
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
                        new OA\Property(property: "message", type: "string", example: "Server error message"),
                    ]
                )
            ),
        ]
    )]
    public function view() {}

    #[OA\Post(
        path: "/api/event/store",
        summary: "Create a new event",
        description: "Creates a new event with its related form(s), office(s), speaker(s), and schedule(s) inside a DB transaction. 'created_by' is set from the authenticated user's name. 'status' defaults to 'Pending'.",
        operationId: "storeEvent",
        tags: ["Event"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Inferred from EventService::create() — verify against EventCreateRequest rules.",
            content: new OA\JsonContent(
                required: ["title_name", "venue_name", "type_name", "source_name"],
                properties: [
                    new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                    new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                    new OA\Property(property: "type_name", type: "string", example: "Seminar"),
                    new OA\Property(property: "source_name", type: "string", example: "Internal"),
                    new OA\Property(property: "qualifications", type: "string", example: "supervisory"),
                    new OA\Property(property: "fee", type: "string", example: "Php 1000"),
                    new OA\Property(property: "hours", type: "integer", example: "4"),
                    new OA\Property(
                        property: "form",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "form_name", type: "string", example: "Attendance Sheet"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "office",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "office_name", type: "string", example: "HRMO"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "speaker",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "speaker_name", type: "string", example: "Dr. Jane Santos"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "schedule",
                        type: "array",
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "schedule_date", type: "string", format: "date", example: "2026-08-10"),
                                new OA\Property(property: "morning_in", type: "string", example: "08:00 AM"),
                                new OA\Property(property: "morning_out", type: "string", example: "12:00 AM"),
                                new OA\Property(property: "afternoon_in", type: "string", example: "01:00 PM"),
                                new OA\Property(property: "afternoon_out", type: "string", example: "05:00 PM"),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Created successfully. 'data' is the newly created event with form/office/schedule/speaker relations loaded.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success created"),
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
                description: "Validation error (per EventCreateRequest rules — not verified here)",
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
                description: "Server error, including a failed create (see EventService::create).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Failed to create event"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Delete(
        path: "/api/event/delete/{eventId}",
        summary: "Delete an event",
        description: "Deletes an event record by ID. 'data' contains the record's attributes as they were immediately before deletion.",
        operationId: "destroyEvent",
        tags: ["Event"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                description: "ID of the event to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success deleted"),
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
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EventService::delete).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "not found"),
                    ]
                )
            ),
        ]
    )]
    public function destory() {}
}