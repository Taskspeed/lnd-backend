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
                                    new OA\Property(property: "ControlNo", type: "string", example: "2026-00123"),
                                    new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                    new OA\Property(property: "office", type: "string", example: "Records Office"),
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
}