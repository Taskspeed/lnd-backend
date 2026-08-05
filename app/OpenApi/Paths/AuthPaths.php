<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

class AuthPaths
{
    
    #[OA\Post(
        path: "/api/user/login",
        summary: "Authenticate a user",
        description: "Validates credentials, revokes any previously issued tokens for that user, and issues a new Sanctum token.",
        operationId: "loginUser",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["username", "password"],
                properties: [
                    new OA\Property(property: "username", type: "string", example: "admin"),
                    new OA\Property(property: "password", type: "string", format: "password", example: "admin"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Login successful",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "User login successfully."),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Tuple: [0] = user object, [1] = new Sanctum plain-text token",
                            items: new OA\Items(
                                oneOf: [
                                    new OA\Schema(
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "username", type: "string", example: "juan.delacruz"),
                                            new OA\Property(property: "control_no", type: "string", example: "2024-001"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-05T08:00:00.000000Z"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2026-08-05T08:00:00.000000Z"),
                                        ]
                                    ),
                                    new OA\Schema(type: "string", example: "2|zyxwVUTSrqponMLKjihgFEDCba0987654321"),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Invalid credentials. NOTE: current implementation throws this as 500 — see note below.",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The provided credentials are incorrect."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "email" => ["The provided credentials are incorrect."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error (also currently returned for invalid credentials — see note below)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "The provided credentials are incorrect."),
                    ]
                )
            ),
        ]
    )]
    
    public function login() {}

    #[OA\Post(
        path: "/api/user/register",
        summary: "Register a new user",
        description: "Creates a new user account linked to an employee (via control_no) and returns an authentication token.",
        operationId: "registerUser",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["username", "password", "control_no","office"],
                properties: [
                    new OA\Property(property: "username", type: "string", example: "juan.delacruz", description: "Must be unique in the users table"),
                    new OA\Property(property: "password", type: "string", format: "password", minLength: 5, example: "secret123"),
                    new OA\Property(property: "control_no", type: "string", example: "2024-001", description: "Employee control number (links user to xPersonal/xService record)"),
                    new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER", description: "Office already provided on the employee"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "User registered successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "User registered successfully."),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Tuple: [0] = created user object, [1] = Sanctum plain-text token",
                            items: new OA\Items(
                                oneOf: [
                                    new OA\Schema(
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 1),
                                            new OA\Property(property: "username", type: "string", example: "juan.delacruz"),
                                            new OA\Property(property: "control_no", type: "string", example: "2024-001"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-05T08:00:00.000000Z"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2026-08-05T08:00:00.000000Z"),
                                        ]
                                    ),
                                    new OA\Schema(type: "string", example: "1|abcdEFGHijklMNOPqrstUVWXyz1234567890"),
                                ]
                            )
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The username has already been taken."),
                        new OA\Property(property: "errors", type: "object", example: [
                            "username" => ["The username has already been taken."],
                        ]),
                    ]
                )
            ),
            new OA\Response(
                response: 500,
                description: "Server error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Something went wrong."),
                    ]
                )
            ),
        ]
    )]
    public function register() {}


    #[OA\Post(
        path: "/api/user/logout",
        summary: "Log out the current user",
        description: "Revokes only the Sanctum token used to authenticate the current request (other active sessions/tokens are untouched).",
        operationId: "logoutUser",
        tags: ["Authentication"],
        security: [["sanctum" => []]],
        responses: [
            new OA\Response(
                response: 200,
                description: "Logged out successfully",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "Logged out successfully."),
                        new OA\Property(property: "data", type: "object", nullable: true, example: null),
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
        ]
    )]
    public function logout() {}
}