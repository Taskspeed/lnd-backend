<?php

namespace App\OpenApi\Paths\Event;

use OpenApi\Attributes as OA;

/**
 * Documents the EventController endpoints (routes/api.php `event` prefix group).
 * Routes verified against controller methods:
 *   GET    /event/index                                    -> index()
 *   POST   /event/store                                     -> store()
 *   PUT    /event/edit/{event}                               -> edit()
 *   PUT    /event/status/{eventId}                           -> update()
 *   GET    /event/view/{eventId}                             -> view()
 *   GET    /event/nominated-employee/{eventId}/{eventSchedule}-> show()
 *   DELETE /event/delete/{eventId}                           -> destory()
 *
 * NOTE: add() (EventService::addSchedule) has NO registered route in the
 * provided routes/api.php — it is currently unreachable via HTTP. Not
 * documented below until a route is added; add it and I can generate its
 * Swagger block too.
 *
 * NOTE: show() calls $this->eventService->nominatedEmployee($eventId,
 * $eventScheduleId) with two arguments, but EventService::nominatedEmployee()
 * only declares one parameter (int $eventId). PHP silently drops the extra
 * argument — $eventScheduleId is NOT used to filter anything server-side.
 * The {eventSchedule} route parameter currently has no effect. Documented
 * below as accepted-but-currently-unused; please confirm this is intended
 * or fix the service method to actually filter by schedule.
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
                                    new OA\Property(property: "source_name", type: "string", example: "Internal"),
                                    new OA\Property(property: "qualifications", type: "string", example: "supervisory"),
                                    new OA\Property(property: "hours", type: "integer", example: 4),
                                    new OA\Property(property: "fee", type: "string", example: "Php 1000"),
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
                description: "Server error (caught by controller's \\Throwable handler)",
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
        path: "/api/event/view/{eventId}",
        summary: "View a single event with its form and schedule",
        description: "Returns a single event with its form and schedule (including scheduleDateTime) relations.",
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
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                new OA\Property(property: "source_name", type: "string", example: "Internal"),
                                new OA\Property(property: "qualifications", type: "string", example: "supervisory"),
                                new OA\Property(property: "hours", type: "integer", example: 4),
                                new OA\Property(property: "fee", type: "string", example: "Php 1000"),
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
                                    property: "schedule",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                            new OA\Property(property: "type_name", type: "string", example: "Seminar"),
                                            new OA\Property(property: "status", type: "string", example: "Created"),
                                            new OA\Property(
                                                property: "scheduleDateTime",
                                                type: "array",
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: "id", type: "integer", example: 1),
                                                        new OA\Property(property: "event_schedule_id", type: "integer", example: 1),
                                                        new OA\Property(property: "schedule_date", type: "string", format: "date", example: "2026-08-20"),
                                                        new OA\Property(property: "morning_in", type: "string", example: "08:00 AM"),
                                                        new OA\Property(property: "morning_out", type: "string", example: "12:00 PM"),
                                                        new OA\Property(property: "afternoon_in", type: "string", example: "01:00 PM"),
                                                        new OA\Property(property: "afternoon_out", type: "string", example: "05:00 PM"),
                                                    ]
                                                )
                                            ),
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
                description: "Server error, including 'Event not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event not found"),
                    ]
                )
            ),
        ]
    )]
    public function view() {}

    #[OA\Get(
        path: "/api/event/nominated-employee/{eventId}/{eventSchedule}",
        summary: "View nominated employees for an event's schedule",
        description: "Returns the event with form and schedule (including office, speaker, and nominatedEmployee) relations. Note: the {eventSchedule} path parameter is currently accepted by the route/controller but NOT passed through to the underlying query — EventService::nominatedEmployee() only filters by event_id, so results are not scoped to a specific schedule despite the parameter's name. Please confirm this is intended.",
        operationId: "nominatedEmployeeEvent",
        tags: ["Event"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                description: "ID of the event",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "eventSchedule",
                description: "ID of the event schedule. Currently NOT used to filter results server-side — see description above.",
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
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                new OA\Property(
                                    property: "form",
                                    type: "array",
                                    items: new OA\Items(type: "object")
                                ),
                                new OA\Property(
                                    property: "schedule",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_id", type: "integer", example: 1),
                                            new OA\Property(
                                                property: "office",
                                                type: "array",
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: "id", type: "integer", example: 1),
                                                        new OA\Property(property: "event_schedule_id", type: "integer", example: 1),
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
                                                        new OA\Property(property: "event_schedule_id", type: "integer", example: 1),
                                                        new OA\Property(property: "speaker_name", type: "string", example: "Dr. Jane Santos"),
                                                    ]
                                                )
                                            ),
                                            new OA\Property(
                                                property: "nominatedEmployee",
                                                type: "array",
                                                items: new OA\Items(
                                                    properties: [
                                                        new OA\Property(property: "id", type: "integer", example: 1),
                                                        new OA\Property(property: "event_schedule_id", type: "integer", example: 1),
                                                        new OA\Property(property: "control_no", type: "string", example: "022485"),
                                                        new OA\Property(property: "full_name", type: "string", example: "Juan Dela Cruz"),
                                                        new OA\Property(property: "designation", type: "string", example: "Administrative Officer"),
                                                        new OA\Property(property: "office", type: "string", example: "Records Office"),
                                                    ]
                                                )
                                            ),
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
                description: "Server error, including 'Event not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event not found"),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: "/api/event/store",
        summary: "Create a new event with schedule, form, office, and speakers",
        description: "Creates a new event with one EventSchedule and its related form(s), office(s), speaker(s), and DateTime entries, inside a DB transaction. Fails if an event with the same title_name already exists.",
        operationId: "storeEvent",
        tags: ["Event"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["title_name"],
                properties: [
                    new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                    new OA\Property(property: "source_name", type: "string", nullable: true, example: "Internal"),
                    new OA\Property(property: "qualifications", type: "string", nullable: true, example: "supervisory"),
                    new OA\Property(property: "hours", type: "integer", nullable: true, example: 4),
                    new OA\Property(property: "fee", type: "string", nullable: true, example: "Php 1000"),
                    new OA\Property(property: "venue_name", type: "string", nullable: true, example: "City Hall Conference Room"),
                    new OA\Property(property: "type_name", type: "string", nullable: true, example: "Seminar"),
                    new OA\Property(
                        property: "form",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "form_name", type: "string", nullable: true, example: "Attendance Sheet"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "office",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "office_name", type: "string", nullable: true, example: "HRMO"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "speaker",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "speaker_name", type: "string", nullable: true, example: "Dr. Jane Santos"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "DateTime",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "schedule_date", type: "string", format: "date", nullable: true, example: "2026-08-20"),
                                new OA\Property(property: "morning_in", type: "string", nullable: true, example: "08:00 AM"),
                                new OA\Property(property: "morning_out", type: "string", nullable: true, example: "12:00 PM"),
                                new OA\Property(property: "afternoon_in", type: "string", nullable: true, example: "01:00 PM"),
                                new OA\Property(property: "afternoon_out", type: "string", nullable: true, example: "05:00 PM"),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Created successfully. 'data' is the newly created event with form and schedule relations loaded.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success created"),
                        new OA\Property(property: "data", type: "object"),
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
                description: "Validation error (per EventCreateRequest rules)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The title name field is required."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including duplicate title_name or failed create.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event name are already exist not allow to create new. please add new schedule"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}
        #[OA\Post(
        path: "/api/event/add-schedule",
        summary: "Add a new schedule to an existing event",
        description: "Creates a new EventSchedule under an existing event, along with its related office(s), speaker(s), and DateTime entries, inside a DB transaction. Used to add an additional batch/session to an event without creating a duplicate event.",
        operationId: "addEventSchedule",
        tags: ["Event"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", description: "Must exist in the events table.", example: 1),
                    new OA\Property(property: "venue_name", type: "string", nullable: true, example: "City Hall Conference Room"),
                    new OA\Property(property: "type_name", type: "string", nullable: true, example: "Seminar"),
                    new OA\Property(
                        property: "office",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "office_name", type: "string", nullable: true, example: "HRMO"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "speaker",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "speaker_name", type: "string", nullable: true, example: "Dr. Jane Santos"),
                            ]
                        )
                    ),
                    new OA\Property(
                        property: "DateTime",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "schedule_date", type: "string", format: "date", nullable: true, example: "2026-08-20"),
                                new OA\Property(property: "morning_in", type: "string", nullable: true, example: "08:00 AM"),
                                new OA\Property(property: "morning_out", type: "string", nullable: true, example: "12:00 PM"),
                                new OA\Property(property: "afternoon_in", type: "string", nullable: true, example: "01:00 PM"),
                                new OA\Property(property: "afternoon_out", type: "string", nullable: true, example: "05:00 PM"),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Created successfully. 'data' is the new schedule with office, speaker, and scheduleDateTime relations loaded.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success created"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 2),
                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                new OA\Property(property: "type_name", type: "string", example: "Seminar"),
                                new OA\Property(property: "status", type: "string", example: "Created"),
                                new OA\Property(
                                    property: "office",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_schedule_id", type: "integer", example: 2),
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
                                            new OA\Property(property: "event_schedule_id", type: "integer", example: 2),
                                            new OA\Property(property: "speaker_name", type: "string", example: "Dr. Jane Santos"),
                                        ]
                                    )
                                ),
                                new OA\Property(
                                    property: "scheduleDateTime",
                                    type: "array",
                                    items: new OA\Items(
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "event_schedule_id", type: "integer", example: 2),
                                            new OA\Property(property: "schedule_date", type: "string", format: "date", example: "2026-08-20"),
                                            new OA\Property(property: "morning_in", type: "string", example: "08:00 AM"),
                                            new OA\Property(property: "morning_out", type: "string", example: "12:00 PM"),
                                            new OA\Property(property: "afternoon_in", type: "string", example: "01:00 PM"),
                                            new OA\Property(property: "afternoon_out", type: "string", example: "05:00 PM"),
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
                response: 422,
                description: "Validation error (e.g. missing event_id, event_id doesn't exist, or a DateTime field doesn't match the h:i A time format)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The selected event id is invalid."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Unhandled server error (caught by controller's \\Throwable handler)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Server Error"),
                    ]
                )
            ),
        ]
    )]
    public function add() {}

    #[OA\Put(
        path: "/api/event/edit/{event}",
        summary: "Edit an event and replace its schedule, form, office, speakers, and DateTimes",
        description: "Updates the event's core fields, then deletes and recreates its form, schedule, office, speaker, and DateTime records inside a DB transaction (full replace, not a partial merge). Uses the same request shape as store().",
        operationId: "editEvent",
        tags: ["Event"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "event",
                description: "ID of the event to edit",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Same shape as POST /api/event/store.",
            content: new OA\JsonContent(
                required: ["title_name"],
                properties: [
                    new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                    new OA\Property(property: "source_name", type: "string", nullable: true, example: "Internal"),
                    new OA\Property(property: "qualifications", type: "string", nullable: true, example: "supervisory"),
                    new OA\Property(property: "hours", type: "integer", nullable: true, example: 4),
                    new OA\Property(property: "fee", type: "string", nullable: true, example: "Php 1000"),
                    new OA\Property(property: "venue_name", type: "string", nullable: true, example: "City Hall Conference Room"),
                    new OA\Property(property: "type_name", type: "string", nullable: true, example: "Seminar"),
                    new OA\Property(
                        property: "form",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [new OA\Property(property: "form_name", type: "string", nullable: true, example: "Attendance Sheet")]
                        )
                    ),
                    new OA\Property(
                        property: "office",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [new OA\Property(property: "office_name", type: "string", nullable: true, example: "HRMO")]
                        )
                    ),
                    new OA\Property(
                        property: "speaker",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [new OA\Property(property: "speaker_name", type: "string", nullable: true, example: "Dr. Jane Santos")]
                        )
                    ),
                    new OA\Property(
                        property: "DateTime",
                        type: "array",
                        nullable: true,
                        items: new OA\Items(
                            properties: [
                                new OA\Property(property: "schedule_date", type: "string", format: "date", nullable: true, example: "2026-08-20"),
                                new OA\Property(property: "morning_in", type: "string", nullable: true, example: "08:00 AM"),
                                new OA\Property(property: "morning_out", type: "string", nullable: true, example: "12:00 PM"),
                                new OA\Property(property: "afternoon_in", type: "string", nullable: true, example: "01:00 PM"),
                                new OA\Property(property: "afternoon_out", type: "string", nullable: true, example: "05:00 PM"),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Updated successfully. 'data' is the event with form and schedule (with office and speaker nested inside) relations loaded.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success update"),
                        new OA\Property(property: "data", type: "object"),
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
                description: "Validation error (per EventCreateRequest rules)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The title name field is required."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'Event not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event not found"),
                    ]
                )
            ),
        ]
    )]
    public function edit() {}

    #[OA\Put(
        path: "/api/event/status/{eventId}",
        summary: "Update an event's status",
        description: "Updates only the status field of an event. Allowed values: Pending, Complete, Cancel.",
        operationId: "updateEventStatus",
        tags: ["Event"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                description: "ID of the event to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["status"],
                properties: [
                    new OA\Property(property: "status", type: "string", enum: ["Pending", "Complete", "Cancel"], example: "Complete"),
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
                        new OA\Property(property: "message", type: "string", example: "success update"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
                                new OA\Property(property: "status", type: "string", example: "Complete"),
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
                response: 422,
                description: "Validation error (missing or invalid status value)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The selected status is invalid."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including 'Event id not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event id not found"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/event/delete/{eventId}",
        summary: "Delete an event",
        description: "Deletes an event record by ID. 'data' contains the record's attributes as they were immediately before deletion. Note: this does not appear to explicitly delete related schedule/form/office/speaker/DateTime rows — confirm whether DB-level cascading FKs handle cleanup, or whether orphaned child rows are left behind.",
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
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "title_name", type: "string", example: "Annual HR Summit"),
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
                description: "Server error, including 'Event not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event not found"),
                    ]
                )
            ),
        ]
    )]
    public function destory() {}


}