<?php

namespace App\OpenApi\Paths\Office\Employee;

use OpenApi\Attributes as OA;

/**
 * Documents the EmployeeController endpoints (routes/api.php `employee` group).
 * Routes verified against controller methods:
 *   POST   /employee/store             -> store()
 *   DELETE /employee/delete{nominatedId} -> destory()
 *
 * Notes on quirks found while verifying against the source:
 * - The delete route is defined as 'delete{nominatedId}' (no slash before the
 *   parameter), so the actual path segment is literally "delete{nominatedId}"
 *   (e.g. /api/employee/delete5), not "/delete/{nominatedId}".
 * - store(): NominatedEmployeeRequest validation failures return the standard
 *   Laravel 422 response. Business-rule failures (duplicate control_no, either
 *   within the same request or already in the DB) are thrown as \Exception
 *   inside EmployeeService::create() but are caught by the controller's
 *   generic `catch (\Throwable $e)` and re-returned as HTTP 500 (the 422 code
 *   set on the exception is not used) — documented below as 500, not 422.
 * - destory() (note controller method name typo) returns "not found" as a
 *   thrown \Exception, also caught and returned as HTTP 500, not 404.
 */
class NominatedEmployeePaths
{
    #[OA\Post(
        path: "/api/office/employee/store",
        summary: "Nominate employees for an event",
        description: "Bulk-creates nominated employee records. Rejects duplicate (event_id, control_no) pairs both within the submitted payload and against existing records. Each created record is stamped with the authenticated user's office. Runs inside a DB transaction.",
        operationId: "storeNominatedEmployee",
        tags: ["Office Employee Nomination"],
        security: [["sanctum" => []]],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["employee"],
                properties: [
                    new OA\Property(
                        property: "employee",
                        type: "array",
                        items: new OA\Items(
                            required: ["event_id", "control_no"],
                            properties: [
                                new OA\Property(property: "event_id", type: "integer", example: 4),
                                new OA\Property(property: "control_no", type: "string", example: "022485"),
                            ]
                        )
                    ),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success — employees nominated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success nominate employee"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "id", type: "integer", example: 10),
                                    new OA\Property(property: "event_id", type: "integer", example: 4),
                                    new OA\Property(property: "control_no", type: "string", example: "022485"),
                                    new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
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
                description: "Validation error (NominatedEmployeeRequest)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The employee field is required."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "employee" => ["The employee field is required."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error, including duplicate control_no within the request or against existing records (currently returned as 500, not 422 — see EmployeeService::create).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Employee with control_no 2026-00123 is already nominated for this event."),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Delete(
        path: "/api/office/employee/delete{nominatedId}",
        summary: "Remove a nominated employee",
        description: "Deletes a nominated employee record by ID. Note: the route parameter is concatenated directly onto 'delete' with no separating slash (e.g. /api/employee/delete5).",
        operationId: "destroyNominatedEmployee",
        tags: ["Office Employee Nomination"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "nominatedId",
                description: "ID of the nominated employee record to delete",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 10)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted successfully. 'data' contains the record's attributes as they were immediately before deletion.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success deleted"),
                        new OA\Property(
                            property: "data",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 10),
                                new OA\Property(property: "event_id", type: "integer", example: 4),
                                new OA\Property(property: "control_no", type: "string", example: "022485"),
                                new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
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
                description: "Server error, including 'not found' (currently returned as 500, not 404 — see EmployeeService::delete).",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "nominate employee not found"),
                    ]
                )
            ),
        ]
    )]
    public function destory() {}
}