<?php

namespace App\OpenApi\Paths\Form;

use OpenApi\Attributes as OA;

/**
 * Documents the EmployeeLearnerProgressFormController endpoints
 * (routes/api.php `erms/learner-progress` group).
 *
 *   GET    /erms/learner-progress/{LearnerProgressFormId} -> show()
 *   POST   /erms/learner-progress/store                            -> store()
 *   PUT    /erms/learner-progress/update/{LearnerProgressFormId} -> update()
 *   DELETE /erms/learner-progress/delete/{LearnerProgressFormId} -> destroy()
 *
 * NOTE: All routes are registered ->withoutMiddleware(['auth:sanctum']), so no
 * `security` block is added below (public/unauthenticated endpoints).
 *
 * NOTE: index() in the controller is currently empty (not implemented), so it is
 * intentionally not documented here.
 *
 * NOTE: show() filters by `form_name`, but LearnerProgressFormService::create()
 * persists the value under `form_name` (note the extra "s"). Verify which column
 * actually exists on the table — if it's `form_name`, the show() query and/or this
 * doc's path param will need to match. Documented here as `formName` per the route
 * signature.
 *
 * NOTE: {controlNo} and {formName} are string route params — if control_no or
 * form_name values contain spaces, the client must URL-encode them
 * (e.g. encodeURIComponent) before calling this endpoint.
 */
class LearnerProgressFormPaths
{
    #[OA\Get(
        path: "/api/erms/learner-progress/{LearnerProgressFormId}",
        summary: "Get a Learner Progress Form by event, form name, and control no",
        description: "Returns a single Learner Progress Form with its related core, leadership, and technical progress records.",
        operationId: "showLearnerProgressForm",
        tags: ["Learner Progress Form"],
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
                schema: new OA\Schema(type: "string", example: "Leaner Progress Report")
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
                                new OA\Property(property: "form_name", type: "string", example: "Leaner Progress Report"),
                                new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                                new OA\Property(property: "learner", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "lnd_attended", type: "string", example: "Basic Supervisory Course"),
                                new OA\Property(property: "date_of_attendance", type: "string", format: "date", example: "2026-01-15"),
                                new OA\Property(property: "delivering_service_excellence_competency", type: "integer", example: 4),
                                new OA\Property(property: "exemplifying_integrity_competency", type: "integer", example: 5),
                                new OA\Property(property: "interpersonal_skills_competency", type: "integer", example: 4),
                                new OA\Property(property: "planning_organizing_competency", type: "integer", example: 3),
                                new OA\Property(property: "monitoring_evaluation_competency", type: "integer", example: 3),
                                new OA\Property(property: "records_management_competency", type: "integer", example: 4),
                                new OA\Property(property: "partnering_networking_competency", type: "integer", example: 4),
                                new OA\Property(property: "process_management_competency", type: "integer", example: 3),
                                new OA\Property(property: "managing_performance_coaching_results_competency", type: "integer", example: 4),
                                new OA\Property(property: "building_collaborative_inclusive_working_relationships_competency", type: "integer", example: 4),
                                new OA\Property(property: "thinking_strategically_creatively_competency", type: "integer", example: 3),
                                new OA\Property(property: "problem_solving_decision_making_competency", type: "integer", example: 4),
                                new OA\Property(
                                    property: "core_progress",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learner_progress_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "delivering_service_excellence", type: "boolean", example: true),
                                        new OA\Property(property: "exemplifying_integrity", type: "boolean", example: true),
                                        new OA\Property(property: "interpersonal_skills", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "leader_ship_progress",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learner_progress_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "managing_performance_coaching_results", type: "boolean", example: true),
                                        new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", example: false),
                                        new OA\Property(property: "thinking_strategically_creatively", type: "boolean", example: true),
                                        new OA\Property(property: "problem_solving_decision_making", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "technical_progress",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learner_progress_report_id", type: "integer", example: 1),
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
                description: "Learner Progress Form not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learner progrees report id not found"),
                    ]
                )
            ),
        ]
    )]
    public function show() {}

    #[OA\Post(
        path: "/api/erms/learner-progress/store",
        summary: "Submit a new Learner Progress Form",
        description: "Creates a Learner Progress Form along with its Core, Leadership, and Technical progress child records, and an associated EmployeeFormSubmission (status defaults to 'Pending'). Fails if the employee (control_no) already submitted this form for the given event.",
        operationId: "storeLearnerProgressForm",
        tags: ["Learner Progress Form"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in events table"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "office", type: "string", nullable: true, example: "HR Office"),
                    new OA\Property(property: "learner", type: "string", nullable: true, example: "Juan Dela Cruz"),
                    new OA\Property(property: "lnd_attended", type: "string", nullable: true, example: "Basic Supervisory Course"),
                    new OA\Property(property: "date_of_attendance", type: "string", format: "date", nullable: true, example: "2026-01-15"),

                    new OA\Property(property: "delivering_service_excellence_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "exemplifying_integrity_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 5),
                    new OA\Property(property: "interpersonal_skills_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "planning_organizing_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "monitoring_evaluation_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "records_management_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "partnering_networking_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "process_management_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "managing_performance_coaching_results_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "thinking_strategically_creatively_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "problem_solving_decision_making_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),

                    new OA\Property(property: "remarks", type: "string", nullable: true, example: "Good progress overall"),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", nullable: true, example: true, description: "Core progress"),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", nullable: true, example: true, description: "Core progress"),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", nullable: true, example: false, description: "Core progress"),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", nullable: true, example: true, description: "Leadership progress"),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", nullable: true, example: false, description: "Leadership progress"),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", nullable: true, example: true, description: "Leadership progress"),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", nullable: true, example: false, description: "Leadership progress"),

                    new OA\Property(property: "planning_organizing", type: "boolean", nullable: true, example: true, description: "Technical progress"),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", nullable: true, example: false, description: "Technical progress"),
                    new OA\Property(property: "records_management", type: "boolean", nullable: true, example: true, description: "Technical progress"),
                    new OA\Property(property: "partnering_networking", type: "boolean", nullable: true, example: false, description: "Technical progress"),
                    new OA\Property(property: "process_management", type: "boolean", nullable: true, example: true, description: "Technical progress"),
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
                        new OA\Property(property: "message", type: "string", example: "success created"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Indexed array: [0] LearnerProgressForm, [1] CoreProgress, [2] LeadershipProgress, [3] TechnicalProgress, [4] EmployeeFormSubmission",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 409,
                description: "Employee already submitted this form for the event, or another error occurred",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "You already submitted this form. You can edit or delete it instead."),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The event id field is required."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
        ]
    )]
    public function store() {}

    #[OA\Put(
        path: "/api/erms/learner-progress/update/{LearnerProgressFormId}",
        summary: "Update an existing Learner Progress Form",
        description: "Updates the LearnerProgressForm record and upserts (updateOrCreate) its Core, Leadership, and Technical progress child records. If a matching EmployeeFormSubmission exists for the event/control_no, its status is reset back to 'Pending'.",
        operationId: "updateLearnerProgressForm",
        tags: ["Learner Progress Form"],
        parameters: [
            new OA\Parameter(
                name: "LearnerProgressFormId",
                description: "LearnerProgressForm primary key",
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
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in events table"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "office", type: "string", nullable: true, example: "HR Office"),
                    new OA\Property(property: "learner", type: "string", nullable: true, example: "Juan Dela Cruz"),
                    new OA\Property(property: "lnd_attended", type: "string", nullable: true, example: "Basic Supervisory Course"),
                    new OA\Property(property: "date_of_attendance", type: "string", format: "date", nullable: true, example: "2026-01-15"),

                    new OA\Property(property: "delivering_service_excellence_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "exemplifying_integrity_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 5),
                    new OA\Property(property: "interpersonal_skills_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "planning_organizing_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "monitoring_evaluation_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "records_management_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "partnering_networking_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "process_management_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "managing_performance_coaching_results_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),
                    new OA\Property(property: "thinking_strategically_creatively_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 3),
                    new OA\Property(property: "problem_solving_decision_making_competency", type: "integer", minimum: 1, maximum: 5, nullable: true, example: 4),

                    new OA\Property(property: "remarks", type: "string", nullable: true, example: "Good progress overall"),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", nullable: true, example: true, description: "Core progress"),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", nullable: true, example: true, description: "Core progress"),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", nullable: true, example: false, description: "Core progress"),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", nullable: true, example: true, description: "Leadership progress"),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", nullable: true, example: false, description: "Leadership progress"),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", nullable: true, example: true, description: "Leadership progress"),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", nullable: true, example: false, description: "Leadership progress"),

                    new OA\Property(property: "planning_organizing", type: "boolean", nullable: true, example: true, description: "Technical progress"),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", nullable: true, example: false, description: "Technical progress"),
                    new OA\Property(property: "records_management", type: "boolean", nullable: true, example: true, description: "Technical progress"),
                    new OA\Property(property: "partnering_networking", type: "boolean", nullable: true, example: false, description: "Technical progress"),
                    new OA\Property(property: "process_management", type: "boolean", nullable: true, example: true, description: "Technical progress"),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: "Updated",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success update"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Indexed array: [0] LearnerProgressForm (fresh), [1] CoreProgress, [2] LeadershipProgress, [3] TechnicalProgress, [4] EmployeeFormSubmission|null",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Learner Progress Form not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learner progrees report id not found"),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Error updating the report",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Error message"),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "message", type: "string", example: "The event id field is required."),
                        new OA\Property(property: "errors", type: "object"),
                    ]
                )
            ),
        ]
    )]
    public function update() {}

    #[OA\Delete(
        path: "/api/erms/learner-progress/delete/{LearnerProgressFormId}",
        summary: "Delete a Learner Progress Form",
        description: "Deletes the LearnerProgressForm and its associated EmployeeFormSubmission. Blocked if the associated EmployeeFormSubmission status is already 'Approved'.",
        operationId: "destroyLearnerProgressForm",
        tags: ["Learner Progress Form"],
        parameters: [
            new OA\Parameter(
                name: "LearnerProgressFormId",
                description: "LearnerProgressForm primary key",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: "Deleted",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success delete"),
                        new OA\Property(
                            property: "data",
                            type: "object",
                            description: "The deleted LearnerProgressForm",
                            properties: [
                                new OA\Property(property: "id", type: "integer", example: 1),
                                new OA\Property(property: "event_id", type: "integer", example: 1),
                                new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 404,
                description: "Learner Progress Form not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learner progrees report id not found"),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Cannot delete an already approved report",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Cannot delete an already approved Learner Progress Form."),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}