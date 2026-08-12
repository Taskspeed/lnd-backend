<?php

namespace App\OpenApi\Paths\Form;

use OpenApi\Attributes as OA;

/**
 * Documents the EmployeeLearningImplementationReportController endpoints
 * (routes/api.php `erms/learner-implementation` group).
 *
 *   GET    /erms/learner-implementation/{eventId}/{formName}/{controlNo}          -> show()
 *   POST   /erms/learner-implementation/store                                     -> store()
 *   PUT    /erms/learner-implementation/update/{learningImplementationFormId}         -> update()
 *   DELETE /erms/learner-implementation/delete/{learningImplementationFormId}         -> destroy()
 *
 * NOTE: All routes are registered ->withoutMiddleware(['auth:sanctum']), so no
 * `security` block is added below (public/unauthenticated endpoints).
 *
 * NOTE: index() in the controller is currently empty (not implemented), so it is
 * intentionally not documented here.
 *
 * NOTE: show() eager-loads `coreImplementation` (listed twice in the controller —
 * looks like a copy/paste duplicate), `learderShipImplementation` (typo'd relation
 * name, kept as-is here since it must match the model), and `technicalImplementation`.
 *
 * NOTE: update() type-hints the first param as `LearningImplementationReport`
 * (an Eloquent model) instead of `LearningImplementationReportRequest` (the Form
 * Request used by store()). As written, `$request->validated()` will fail at
 * runtime since Eloquent models don't have a validated() method — this is
 * documented against the FormRequest's rules() since that is almost certainly
 * the intent.
 *
 * NOTE: create() throws when a submission already exists for the same
 * event_id + form_name + control_no ("You already submitted this form...."),
 * so store() can also return a 400 in that case, not just on validation failure.
 *
 * NOTE: delete() blocks deletion once the related EmployeeFormSubmission status
 * is 'Approved'.
 *
 * NOTE: {controlNo} and {formName} are string route params — if control_no or
 * form_name values contain spaces, the client must URL-encode them
 * (e.g. encodeURIComponent) before calling this endpoint.
 */
class LearningImplementationFormPaths
{
    #[OA\Get(
        path: "/api/erms/learner-implementation/{eventId}/{formName}/{controlNo}",
        summary: "Get a Learning Implementation Report Form by event, form name, and control no",
        description: "Returns a single Learning Implementation Report Form with its related core, leadership, and technical implementation records.",
        operationId: "showLearningImplementationReport",
        tags: ["Learning Implementation Report Form"],
        parameters: [
            new OA\Parameter(
                name: "eventId",
                description: "Event ID the form was submitted for",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
            new OA\Parameter(
                name: "formName",
                description: "Form name (URL-encode if it contains spaces)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "Learning Implementation Report")
            ),
            new OA\Parameter(
                name: "controlNo",
                description: "Employee control number (URL-encode if it contains spaces)",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "string", example: "2021-00123")
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
                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                new OA\Property(property: "form_name", type: "string", example: "Learning Implementation Report"),
                                new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                                new OA\Property(property: "learner", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "lnd_attended", type: "string", example: "Basic Supervisory Course"),
                                new OA\Property(property: "date_of_attendance", type: "string", example: "2026-01-15"),
                                new OA\Property(property: "competency_developed_acquired", type: "string", example: "Improved coaching techniques"),
                                new OA\Property(property: "learning_strategies_applied", type: "string", example: "Applied active listening during 1:1s"),
                                new OA\Property(property: "resources_used", type: "string", example: "LnD handouts, online modules"),
                                new OA\Property(property: "beneficiaries_strategies_applied", type: "string", example: "Team members, direct reports"),
                                new OA\Property(property: "performance_indicators_behavior_toward_work", type: "string", example: "Increased team engagement scores"),
                                new OA\Property(property: "financial_aid_training_attended", type: "string", example: "None"),
                                new OA\Property(property: "return_financial_aid", type: "string", example: "N/A"),
                                new OA\Property(
                                    property: "core_implementation",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_implementation_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "delivering_service_excellence", type: "boolean", example: true),
                                        new OA\Property(property: "exemplifying_integrity", type: "boolean", example: true),
                                        new OA\Property(property: "interpersonal_skills", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "learder_ship_implementation",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_implementation_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "managing_performance_coaching_results", type: "boolean", example: true),
                                        new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", example: false),
                                        new OA\Property(property: "thinking_strategically_creatively", type: "boolean", example: true),
                                        new OA\Property(property: "problem_solving_decision_making", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "technical_implementation",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_implementation_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "planning_organizing", type: "boolean", example: true),
                                        new OA\Property(property: "monitoring_evaluation", type: "boolean", example: false),
                                        new OA\Property(property: "records_management", type: "boolean", example: true),
                                        new OA\Property(property: "partnering_networking", type: "boolean", example: false),
                                        new OA\Property(property: "process_management", type: "boolean", example: true),
                                    ]
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Learning Implementation Report Form not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learning implementation id not found"),
                    ]
                )
            ),
        ]
    )]
    #[OA\Post(
        path: "/api/erms/learner-implementation/store",
        summary: "Submit a new Learning Implementation Report Form",
        description: "Creates a Learning Implementation Report along with its related core, leadership, and technical implementation records, and creates a pending EmployeeFormSubmission entry. Fails if a submission already exists for the same event_id + form_name + control_no.",
        operationId: "storeLearningImplementationReport",
        tags: ["Learning Implementation Report Form"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in the events table"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "learner", type: "string", nullable: true, example: "Juan Dela Cruz"),
                    new OA\Property(property: "lnd_attended", type: "string", nullable: true, example: "Basic Supervisory Course"),
                    new OA\Property(property: "date_of_attendance", type: "string", nullable: true, example: "2026-01-15"),
                    new OA\Property(property: "competency_developed_acquired", type: "string", nullable: true, example: "Improved coaching techniques"),
                    new OA\Property(property: "learning_strategies_applied", type: "string", nullable: true, example: "Applied active listening during 1:1s"),
                    new OA\Property(property: "resources_used", type: "string", nullable: true, example: "LnD handouts, online modules"),
                    new OA\Property(property: "beneficiaries_strategies_applied", type: "string", nullable: true, example: "Team members, direct reports"),
                    new OA\Property(property: "performance_indicators_behavior_toward_work", type: "string", nullable: true, example: "Increased team engagement scores"),
                    new OA\Property(property: "financial_aid_training_attended", type: "string", nullable: true, example: "None"),
                    new OA\Property(property: "return_financial_aid", type: "string", nullable: true, example: "N/A"),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", nullable: true, example: false),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", nullable: true, example: false),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", nullable: true, example: false),

                    new OA\Property(property: "planning_organizing", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", nullable: true, example: false),
                    new OA\Property(property: "records_management", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "partnering_networking", type: "boolean", nullable: true, example: false),
                    new OA\Property(property: "process_management", type: "boolean", nullable: true, example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Created",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success create"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Tuple of [learning_implementation, core_implementation, leadership_implementation, technical_implementation, form_submit]",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Validation error or duplicate submission",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "You already submitted this form. You can edit or delete it instead."),
                    ]
                )
            ),
        ]
    )]
    #[OA\Put(
        path: "/api/erms/learner-implementation/update/{learningImplementationFormId}",
        summary: "Update an existing Learning Implementation Report Form",
        description: "Updates the Learning Implementation Report and its related core, leadership, and technical implementation records (created via updateOrCreate). Resets the linked EmployeeFormSubmission status back to 'Pending' if one exists.",
        operationId: "updateLearningImplementationReport",
        tags: ["Learning Implementation Report Form"],
        parameters: [
            new OA\Parameter(
                name: "learningImplementationFormId",
                description: "Learning Implementation Report ID to update",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in the events table"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "learner", type: "string", nullable: true, example: "Juan Dela Cruz"),
                    new OA\Property(property: "lnd_attended", type: "string", nullable: true, example: "Basic Supervisory Course"),
                    new OA\Property(property: "date_of_attendance", type: "string", nullable: true, example: "2026-01-15"),
                    new OA\Property(property: "competency_developed_acquired", type: "string", nullable: true, example: "Improved coaching techniques"),
                    new OA\Property(property: "learning_strategies_applied", type: "string", nullable: true, example: "Applied active listening during 1:1s"),
                    new OA\Property(property: "resources_used", type: "string", nullable: true, example: "LnD handouts, online modules"),
                    new OA\Property(property: "beneficiaries_strategies_applied", type: "string", nullable: true, example: "Team members, direct reports"),
                    new OA\Property(property: "performance_indicators_behavior_toward_work", type: "string", nullable: true, example: "Increased team engagement scores"),
                    new OA\Property(property: "financial_aid_training_attended", type: "string", nullable: true, example: "None"),
                    new OA\Property(property: "return_financial_aid", type: "string", nullable: true, example: "N/A"),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", nullable: true, example: false),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", nullable: true, example: false),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", nullable: true, example: false),

                    new OA\Property(property: "planning_organizing", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", nullable: true, example: false),
                    new OA\Property(property: "records_management", type: "boolean", nullable: true, example: true),
                    new OA\Property(property: "partnering_networking", type: "boolean", nullable: true, example: false),
                    new OA\Property(property: "process_management", type: "boolean", nullable: true, example: true),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success update"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Tuple of [learning_implementation, core_implementation, leadership_implementation, technical_implementation, form_submit]",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Not found or validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learner progrees report id not found"),
                    ]
                )
            ),
        ]
    )]
    #[OA\Delete(
        path: "/api/erms/learner-implementation/delete/{learningImplementationFormId}",
        summary: "Delete a Learning Implementation Report Form",
        description: "Deletes the Learning Implementation Report and its linked EmployeeFormSubmission. Fails if the linked submission status is already 'Approved'.",
        operationId: "destroyLearningImplementationReport",
        tags: ["Learning Implementation Report Form"],
        parameters: [
            new OA\Parameter(
                name: "learningImplementationFormId",
                description: "Learning Implementation Report ID to delete",
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
                        new OA\Property(property: "message", type: "string", example: "success delete"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            description: "The deleted Learning Implementation Report",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                new OA\Property(property: "form_name", type: "string", example: "Learning Implementation Report"),
                                new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Not found, or attempted delete of an already approved submission",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Cannot delete an already approved Learning implementation."),
                    ]
                )
            ),
        ]
    )]
    public function endpoints()
    {
        // Marker method only — attributes above carry the OpenAPI documentation.
    }
}