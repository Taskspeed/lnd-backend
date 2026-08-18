<?php

namespace App\OpenApi\Paths\Office;

use OpenApi\Attributes as OA;

/**
 * Documents the OfficeController endpoints (routes/api.php `office` group).
 * Routes verified against controller methods — no mismatches found:
 *   GET /office/index    -> index()
 *   GET /office/employee -> show()
 *
 * Note: unlike other controllers in this codebase, neither method here wraps
 * its logic in a try/catch. Any unexpected failure (e.g. a DB error) will
 * bubble up to Laravel's default exception handler instead of the
 * ApiResponseTrait error envelope used elsewhere — so no custom 500 response
 * shape is documented below, only the framework-level one for a fully
 * unhandled exception.
 */
class OfficePaths
{
    #[OA\Get(
        path: "/api/office/index",
        summary: "List all offices",
        description: "Returns all office records (officeId and office_name only). Always returns HTTP 200, including when the list is empty — there is no 'no records found' branch.",
        operationId: "indexOffice",
        tags: ["Office"],
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
                                    new OA\Property(property: "officeId", type: "integer", example: 1),
                                    new OA\Property(property: "office_name", type: "string", example: "Records Office"),
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
                description: "Unhandled server error (uncaught exception — no custom error envelope for this endpoint).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function index() {}

    #[OA\Get(
        path: "/api/office/employee",
        summary: "List employees in the authenticated user's office",
        description: "Returns employees (from the vwEmployee view) whose office matches the authenticated user's office. Always returns HTTP 200, including when the list is empty.",
        operationId: "showOfficeEmployee",
        tags: ["Office"],
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
                                    new OA\Property(property: "ControlNo", type: "string", example: "022485"),
                                    new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                    new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
                                    new OA\Property(property: "position", type: "string", example: "Administrative Officer"),
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
                description: "Unhandled server error (uncaught exception — no custom error envelope for this endpoint).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    /**
     * Documents the EventController endpoints related to office-scoped events.
     * Routes assumed based on controller signatures — please confirm actual
     * routes/api.php paths, especially the {eventId} route name:
     *   GET /event/index        -> listEvent()  (maps to EventController::index)
     *   GET /event/{eventId}    -> viewEvent()    (maps to EventController::show)
     *
     * Note: listEvent() has an early-return "no records" branch via
     * infoMessage() (still HTTP 200, but a different message than the
     * success case). viewEvent() returns errorMessage() when the event
     * isn't found — actual HTTP status code for errorMessage() depends on
     * ApiResponseTrait's implementation; documented as 404 below, please
     * confirm against the trait.
     */
    #[OA\Get(
        path: "/api/office/event/list-of-event",
        summary: "List events for the authenticated user's office",
        description: "Returns events whose office matches the authenticated user's office (via the office relation's office_name). Returns HTTP 200 with the event list, or HTTP 200 with an info message and no data if there are no events for the office.",
        operationId: "listEvent",
        tags: ["Office"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success — either the event list, or an info message when no events are available for the user's office.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(
                            property: "message",
                            type: "string",
                            description: "\"Success fetch\" when data is returned, or \"There is no available event for your office\" when empty.",
                            example: "Success fetch"
                        ),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            nullable: true,
                            description: "Omitted or empty when there are no events for the office.",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                    new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                    new OA\Property(property: "type_name", type: "string", example: "Seminar"),
                                    new OA\Property(property: "source_name", type: "string", example: "Internal"),
                                    new OA\Property(property: "hours", type: "string", example: "4"),
                                    new OA\Property(property: "qualifications", type: "string", example: "supervisory"),
                                    new OA\Property(property: "fee", type: "string", example: "Php 1000"),
                                    new OA\Property(property: "status", type: "string", example: "Verify"),
                                    new OA\Property(property: "created_by", type: "string", nullable: true, example: null),
                                    new OA\Property(property: "created_at", type: "string", example: " August 12, 2026"),
                                    new OA\Property(property: "updated_at", type: "string", example: " August 12, 2026"),
                                    new OA\Property(property: "event_id", type: "integer", example: 1),
                                    new OA\Property(
                                        property: "office",
                                        type: "array",
                                        items: new OA\Items(
                                            properties: [
                                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                                new OA\Property(property: "office_name", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
                                                new OA\Property(property: "departmentId", type: "integer", example: 3),
                                            ]
                                        )
                                    ),
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
                description: "Unhandled server error (uncaught exception — no custom error envelope for this endpoint).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function listEvent() {}

    #[OA\Get(
        path: "/api/office/event/view-event/{eventId}",
        summary: "Display a single event with its related data",
        description: "Returns a single event with form, office, speaker, schedule, and nominatedEmployee relations. The nominatedEmployee relation is scoped to only the authenticated user's office.",
        operationId: "showEvent",
        tags: ["Office"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                description: "ID of the event to retrieve",
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
                        new OA\Property(property: "message", type: "string", example: "Success fetch"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title", type: "string", example: "Basic Leadership Training"),
                                new OA\Property(property: "form", type: "object", nullable: true),
                                new OA\Property(property: "office", type: "object", nullable: true),
                                new OA\Property(property: "speaker", type: "object", nullable: true),
                                new OA\Property(property: "schedule", type: "object", nullable: true),
                                new OA\Property(
                                    property: "nominatedEmployee",
                                    type: "array",
                                    description: "Filtered to only the authenticated user's office.",
                                    items: new OA\Items(type: "object")
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Event not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event not found"),
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
                description: "Unhandled server error (uncaught exception — no custom error envelope for this endpoint).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function showEvent(int $eventId) {}

}