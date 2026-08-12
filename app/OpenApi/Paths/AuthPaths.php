<?php

namespace App\OpenApi\Paths;

use OpenApi\Attributes as OA;

class AuthPaths
{

    #[OA\Post(
        path: "/api/user/login",
        summary: "Authenticate a user",
        description: "Validates credentials, revokes any previously issued tokens for that user, and issues a new Sanctum token along with the user's roles and permissions.",
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
                            type: "object",
                            properties: [
                                new OA\Property(
                                    property: "user",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 4),
                                        new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                        new OA\Property(property: "username", type: "string", example: "jdelacruz"),
                                        new OA\Property(property: "office", type: "string", nullable: true, example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
                                        new OA\Property(property: "control_no", type: "string", nullable: true, example: "2024-001"),
                                        new OA\Property(
                                            property: "roles",
                                            type: "array",
                                            items: new OA\Items(type: "string"),
                                            example: ["admin"]
                                        ),
                                        new OA\Property(
                                            property: "permissions",
                                            type: "array",
                                            items: new OA\Items(type: "string"),
                                            example: ["view_reports", "edit_users"]
                                        ),
                                    ]
                                ),
                                new OA\Property(property: "token", type: "string", example: "9|goTwiaO9F1BuEZSjoqA7elV2UluqShjF2NziIwFpb3dbf5cc"),
                            ]
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
        description: "Creates a new user account linked to an employee (via control_no), assigns a role and optional direct permissions, and returns an authentication token.",
        operationId: "registerUser",
        tags: ["Authentication"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["name", "username", "password", "office", "control_no", "role"],
                properties: [
                    new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                    new OA\Property(property: "username", type: "string", example: "jdelacruz", description: "Must be unique in the users table"),
                    new OA\Property(property: "password", type: "string", format: "password", minLength: 5, example: "secret123"),
                    new OA\Property(property: "control_no", type: "string", example: "2024-001", description: "Employee control number (links user to xPersonal/xService record)"),
                    new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER", description: "Office already provided on the employee"),
                    new OA\Property(property: "role", type: "string", example: "hr_admin", description: "Must exist in the roles table (guard: sanctum)"),
                    new OA\Property(
                        property: "permissions",
                        type: "array",
                        items: new OA\Items(type: "string"),
                        example: ["create_event", "create_user"],
                        description: "Optional. Direct permissions granted on top of the role's permissions. Each value must exist in the permissions table."
                    ),
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
                            description: "Tuple: [0] = created user object (with roles/permissions loaded), [1] = Sanctum plain-text token",
                            items: new OA\Items(
                                oneOf: [
                                    new OA\Schema(
                                        type: "object",
                                        properties: [
                                            new OA\Property(property: "id", type: "integer", example: 4),
                                            new OA\Property(property: "name", type: "string", example: "Juan Dela Cruz"),
                                            new OA\Property(property: "username", type: "string", example: "jdelacruz"),
                                            new OA\Property(property: "office", type: "string", example: "OFFICE OF THE CITY INFORMATION AND COMMUNICATIONS TECHNOLOGY MANAGEMENT OFFICER"),
                                            new OA\Property(property: "control_no", type: "string", example: "2024-001"),
                                            new OA\Property(property: "created_at", type: "string", format: "date-time", example: "2026-08-06T01:06:53.130000Z"),
                                            new OA\Property(property: "updated_at", type: "string", format: "date-time", example: "2026-08-06T01:06:53.130000Z"),
                                            new OA\Property(
                                                property: "roles",
                                                type: "array",
                                                items: new OA\Items(type: "object"),
                                                description: "Full role model objects (not yet resource-transformed)"
                                            ),
                                            new OA\Property(
                                                property: "permissions",
                                                type: "array",
                                                items: new OA\Items(type: "object"),
                                                description: "Direct permission model objects only (not role-inherited)"
                                            ),
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
                description: "Validation error (e.g. duplicate username, role/permission not found)",
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
