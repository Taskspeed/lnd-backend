<?php

namespace App\OpenApi\Paths\HR\Dashboard;

use OpenApi\Attributes as OA;

/**
 * Documents the DashboardController endpoints (routes/api.php `hr/dashboard` prefix group — VERIFY ACTUAL PATH).
 *
 * NOTE: Assumed paths since no route registration was provided for this
 * controller. Confirm the actual prefix/route names and update the `path`
 * values below if they differ.
 */
class DashboardPaths
{
    #[OA\Get(
        path: "/api/hr/dashboard/index",
        summary: "List upcoming event schedules (paginated)",
        description: "Returns a paginated list of EventSchedule records that have at least one scheduleDateTime on or after today. Each schedule includes a computed 'schedule_date_range' (e.g. 'August 20 - 21, 2026') in place of the raw scheduleDateTime collection.",
        operationId: "getUpcomingEvents",
        tags: ["HR Dashboard"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "per_page",
                description: "Number of results per page. Defaults to 10.",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            description: "Laravel LengthAwarePaginator structure",
                            properties: [
                                new OA\Property(property: "current_page", type: "integer", example: 1),
                                new OA\Property(
                                    property: "data",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                            new OA\Property(property: "hours", type: "integer", example: 4),
                                            new OA\Property(property: "status", type: "string", example: "Approved"),
                                            new OA\Property(property: "schedule_date_range", type: "string", example: "August 20 - 21, 2026"),
                                            new OA\Property(
                                                property: "event",
                                                type: "object",
                                                properties: [
                                                    new OA\Property(property: "id", type: "integer", example: 1),
                                                    new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                                ]
                                            ),
                                        ]
                                    )
                                ),
                                new OA\Property(property: "last_page", type: "integer", example: 1),
                                new OA\Property(property: "per_page", type: "integer", example: 10),
                                new OA\Property(property: "total", type: "integer", example: 2),
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
                description: "Unhandled server error",
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

    #[OA\Get(
        path: "/api/hr/dashboard/calendar",
        summary: "List event schedule dates for a given month/year",
        description: "Returns EventScheduleDateTime records filtered by year and month, each with its parent eventSchedule's status. Defaults to the current year and month when 'year'/'month' query params are omitted.",
        operationId: "getCalendarEvents",
        tags: ["HR Dashboard"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "year",
                description: "Year to filter by. Defaults to the current year.",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", minimum: 2000, maximum: 2100, example: 2026)
            ),
            new OA\Parameter(
                name: "month",
                description: "Month to filter by (1-12). Defaults to the current month.",
                in: "query",
                required: false,
                schema: new OA\Schema(type: "integer", minimum: 1, maximum: 12, example: 8)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 1),
                                    new OA\Property(property: "schedule_date", type: "string", format: "date", example: "2026-08-20"),
                                    new OA\Property(property: "event_schedule_id", type: "integer", example: 1),
                                    new OA\Property(
                                        property: "event_schedule",
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "status", type: "string", example: "Approved"),
                                        ]
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
                response: 422,
                description: "Validation error (year out of range, or month not between 1-12)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The month must be between 1 and 12."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Unhandled server error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function calendar() {}
}