<?php

namespace App\OpenApi\Paths\Employee;

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
class EmployeePaths
{
     #[OA\Get(
        path: "/api/employee/show/{office}",
        summary: "List employees by office name",
        description: "Returns employees (from the vwEmployee view) whose office matches the given office name path parameter.",
        operationId: "showEmployeeByOffice",
        tags: ["Employee"],
        security: [["sanctum" => []]],
        parameters: [
            new OA\Parameter(
                name: "office",
                description: "Exact office name to filter employees by",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER")
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
                            type: "array",
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(property: "ControlNo", type: "string", example: "022485"),
                                    new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
                                    new OA\Property(property: "position", type: "string", example: "Administrative Officer"),
                                    new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                    new OA\Property(property: "status", type: "string", example: "Active"),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "No employees found for the given office",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "No record employee found"),
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
    public function showEmployeeByOffice(string $office) {}
}