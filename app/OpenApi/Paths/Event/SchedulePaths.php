<?php

namespace App\OpenApi\Paths\Event;

use OpenApi\Attributes as OA;

/**
 * Documents the EventController endpoints (routes/api.php `event` prefix group).
 * Routes verified against controller methods:
 *   GET    /event/index                                    -> index()
 *   POST   /event/store                                     -> store()
 * 
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
class SchedulePaths
{
  

   
    #[OA\Post(
        path: "/api/event/schedule/store",
        summary: "Add a new schedule to an existing event",
        description: "Creates a new EventSchedule under an existing event, along with its related office(s), speaker(s), and DateTime entries, inside a DB transaction. Used to add an additional batch/session to an event without creating a duplicate event.",
        operationId: "addEventSchedule",
        tags: ["Event Schedule"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", description: "Must exist in the events table.", example: 1),
                    new OA\Property(property: "venue_name", type: "string", nullable: true, example: "City Hall Conference Room"),
                    new OA\Property(property: "mode_name", type: "string", nullable: true, example: "face to face"),
                     new OA\Property(property: "qualifications", type: "string", nullable: true, example: "supervisory"),
                    new OA\Property(property: "hours", type: "integer", nullable: true, example: 4),
                    new OA\Property(property: "fee", type: "string", nullable: true, example: "Php 1000"),
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
                                new OA\Property(property: "mode_name", type: "string", example: "face to face"),
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
    public function store() {}

    #[OA\Put(
    path: "/api/event/schedule/edit/{eventScheduleId}",
    summary: "Update an event schedule",
    description: "Updates an event schedule's venue, mode, qualifications, hours, and fee. Office, speaker, and DateTime entries are fully replaced (deleted then recreated) — sending an omitted or empty array for any of these will clear existing related records for that field.",
    operationId: "updateEventSchedule",
    tags: ["Event Schedule"],
    security: [["sanctum" => []]],
    parameters: [
        new OA\Parameter(
            name: "eventScheduleId",
            description: "ID of the event schedule to update",
            in: "path",
            required: true,
            schema: new OA\Schema(type: "integer", example: 1)
        ),
    ],
    requestBody: new OA\RequestBody(
        required: true,
        content: new OA\JsonContent(
            properties: [
                new OA\Property(property: "venue_name", type: "string", nullable: true, example: "City Hall Function Room"),
                new OA\Property(property: "mode_name", type: "string", nullable: true, example: "Face to Face"),
                new OA\Property(property: "qualifications", type: "string", nullable: true, example: "Supervisory"),
                new OA\Property(property: "fee", type: "string", nullable: true, example: "Php 1000"),
                new OA\Property(property: "hours", type: "integer", nullable: true, example: 4),
                new OA\Property(
                    property: "DateTime",
                    type: "array",
                    nullable: true,
                    items: new OA\Items(
                        properties: [
                            new OA\Property(property: "schedule_date", type: "string", format: "date", nullable: true, example: "2026-08-25"),
                            new OA\Property(property: "morning_in", type: "string", nullable: true, example: "08:00 AM"),
                            new OA\Property(property: "morning_out", type: "string", nullable: true, example: "12:00 PM"),
                            new OA\Property(property: "afternoon_in", type: "string", nullable: true, example: "01:00 PM"),
                            new OA\Property(property: "afternoon_out", type: "string", nullable: true, example: "05:00 PM"),
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
                            new OA\Property(property: "speaker_name", type: "string", nullable: true, example: "Juan Dela Cruz"),
                        ]
                    )
                ),
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
                            new OA\Property(property: "event_id", type: "integer", example: 1),
                            new OA\Property(property: "venue_name", type: "string", example: "City Hall Function Room"),
                            new OA\Property(property: "mode_name", type: "string", example: "Face to Face"),
                            new OA\Property(property: "qualifications", type: "string", example: "Supervisory"),
                            new OA\Property(property: "fee", type: "string", example: "Php 1000"),
                            new OA\Property(property: "hours", type: "integer", example: 4),
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
                                        new OA\Property(property: "speaker_name", type: "string", example: "Juan Dela Cruz"),
                                    ]
                                )
                            ),
                            new OA\Property(
                                property: "scheduleDateTime",
                                type: "array",
                                items: new OA\Items(
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 5),
                                        new OA\Property(property: "event_schedule_id", type: "integer", example: 1),
                                        new OA\Property(property: "schedule_date", type: "string", format: "date", example: "2026-08-25"),
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
            response: 500,
            description: "Server error, including 'Event schedule are not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
            content: new OA\JsonContent(
                properties: [
                    new OA\Property(property: "success", type: "boolean", example: false),
                    new OA\Property(property: "message", type: "string", example: "Event schedule are not found"),
                ]
            )
        ),
    ]
)]
public function edit() {}
    #[OA\Put(
        path: "/api/event/schedule/update-status/{eventScheduleId}",
        summary: "Update an event schedule's status",
        description: "Updates only the status field of an EventSchedule record (not the parent Event). Allowed values: Pending, Complete, Cancel.",
        operationId: "updateEventScheduleStatus",
        tags: ["Event Schedule"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventScheduleId",
                description: "ID of the event schedule to update",
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
                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                new OA\Property(property: "venue_name", type: "string", example: "City Hall Conference Room"),
                                new OA\Property(property: "mode_name", type: "string", example: "face to face"),
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
                description: "Server error, including 'Event id not found' (thrown when no EventSchedule matches the given ID; caught as a generic 500, not a 404).",
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
        path: "/api/event/schedule/delete/{eventScheduleId}",
        summary: "Delete an event schedule",
        description: "Permanently deletes an EventSchedule record. Does not explicitly cascade-delete related office, speaker, DateTime, or nominated employee records here in the service — deletion behavior for those depends on DB-level constraints (see nominated_employees.event_schedule_id 'no action' note elsewhere in this codebase).",
        operationId: "deleteEventSchedule",
        tags: ["Event Schedule"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "eventScheduleId",
                description: "ID of the event schedule to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted successfully. 'data' is the deleted schedule's attributes as they were before deletion.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success deleted"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                new OA\Property(property: "venue_name", type: "string", example: "City Hall Function Room"),
                                new OA\Property(property: "mode_name", type: "string", example: "Face to Face"),
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
                response: 500,
                description: "Server error, including 'Event Schedule not found' (thrown as a generic \\Exception and caught as a 500, not a 404).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Event Schedule not found"),
                    ]
                )
            ),
        ]
    )]
    public function destory() {}
}


