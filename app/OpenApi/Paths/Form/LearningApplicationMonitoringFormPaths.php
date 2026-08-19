<?php

namespace App\OpenApi\Paths\Form;

use OpenApi\Attributes as OA;

/**
 * Documents the EmployeeLearningApplicationMonitoringReportController endpoints.
 *
 * NOTE: Route file for this controller was not provided — paths below assume the
 * same convention as the `erms/learner-progress` group (see LearnerProgressReportPaths):
 *   GET    /erms/learning-application-monitoring/{learningApplicationMonitoringFormId}     -> show()
 *   POST   /erms/learning-application-monitoring/store                            -> store()
 *   PUT    /erms/learning-application-monitoring/update/{learningApplicationMonitoringFormId}                   -> update()
 *   DELETE /erms/learning-application-monitoring/delete/{learningApplicationMonitoringFormId}                 -> destroy()
 * Verify against routes/api.php and adjust the `path:` values / group prefix if different.
 * Assumed ->withoutMiddleware(['auth:sanctum']) as well (same as the sibling LPR routes),
 * so no `security` block is added — update if this group is actually protected.
 *
 * NOTE: index() in the controller is currently empty (not implemented), so it is
 * intentionally not documented here.
 *
 * NOTE: delete() reads `$learner_application_monitoring->form_name` (extra "s") to
 * look up the EmployeeFormSubmission, but create()/edit() persist and query the column
 * as `form_name` (no "s") everywhere else. As written, `form_name` is likely a
 * non-existent attribute (will just resolve to null), so the EmployeeFormSubmission
 * lookup in delete() may silently fail to match. Worth double-checking against the
 * actual `learning_application_monitoring_reports` table schema.
 *
 * NOTE: {controlNo} and {formName} are string route params — if control_no or
 * form_name values contain spaces, the client must URL-encode them before calling
 * this endpoint (e.g. "Learning Application Monitoring Report Form" has spaces).
 */
class LearningApplicationMonitoringFormPaths
{
    #[OA\Get(
        path: "/api/erms/learning-application-monitoring/{learningApplicationMonitoringFormId}",
        summary: "Get a Learning Application Monitoring Report Form by event, form name, and control no",
        description: "Returns a single Learning Application Monitoring Report Form with its related core, leadership, and technical monitoring records.",
        operationId: "showLearningApplicationMonitoringReport",
        tags: ["Learning Application Monitoring Report Form"],
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
                schema: new OA\Schema(type: "string", example: "Learning Application Monitoring Report Form")
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
                                new OA\Property(property: "form_name", type: "string", example: "Learning Application Monitoring Report Form"),
                                new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                                new OA\Property(property: "learner", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "lnd_attended", type: "string", example: "Basic Supervisory Course"),
                                new OA\Property(property: "date_of_attendance", type: "string", format: "date", example: "2026-01-15"),
                                new OA\Property(property: "competency_developed_acquired", type: "string", example: "Records Management"),
                                new OA\Property(property: "goals", type: "string", example: "Improve records filing turnaround time"),
                                new OA\Property(property: "performance_indicator", type: "string", example: "100% of records filed within 24 hours"),
                                new OA\Property(property: "learning_strategies_applied", type: "string", example: "On-the-job coaching"),
                                new OA\Property(property: "required_resources", type: "string", example: "Filing cabinet, scanner"),
                                new OA\Property(property: "target_date_completion", type: "string", example: "2026-03-01"),
                                new OA\Property(property: "status_as_of_v1", type: "string", example: "Ongoing"),
                                new OA\Property(property: "status_as_of_v2", type: "string", example: "Completed"),
                                new OA\Property(
                                    property: "core_monitoring",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_monitoring_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "delivering_service_excellence", type: "boolean", example: true),
                                        new OA\Property(property: "exemplifying_integrity", type: "boolean", example: true),
                                        new OA\Property(property: "interpersonal_skills", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "leader_ship_monitoring",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_monitoring_report_id", type: "integer", example: 1),
                                        new OA\Property(property: "managing_performance_coaching_results", type: "boolean", example: true),
                                        new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", example: false),
                                        new OA\Property(property: "thinking_strategically_creatively", type: "boolean", example: true),
                                        new OA\Property(property: "problem_solving_decision_making", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "technical_monitoring",
                                    type: "object",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_monitoring_report_id", type: "integer", example: 1),
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
                description: "Learning Application Monitoring Report Form not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learning Application Monitoring Report Form id not found"),
                    ]
                )
            ),
        ]
    )]  
    public function show() {}

    #[OA\Post(
        path: "/api/erms/learning-application-monitoring/store",
        summary: "Submit a new Learning Application Monitoring Report Form",
        description: "Creates a Learning Application Monitoring Report Form along with its Core, Leadership, and Technical monitoring child records, and an associated EmployeeFormSubmission (status defaults to 'Pending'). Fails if the employee (control_no) already submitted this form for the given event.",
        operationId: "storeLearningApplicationMonitoringReport",
        tags: ["Learning Application Monitoring Report Form"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id", "form_name", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in events table"),
                    new OA\Property(property: "form_name", type: "string", example: "Learning Application Monitoring Report Form"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "learner", type: "string", nullable: true, example: "Juan Dela Cruz"),
                    new OA\Property(property: "lnd_attended", type: "string", nullable: true, example: "Basic Supervisory Course"),
                    new OA\Property(property: "date_of_attendance", type: "string", format: "date", nullable: true, example: "2026-01-15"),
                    new OA\Property(property: "competency_developed_acquired", type: "string", nullable: true, example: "Records Management"),

                    new OA\Property(property: "goals", type: "string", nullable: true, example: "Improve records filing turnaround time"),
                    new OA\Property(property: "performance_indicator", type: "string", nullable: true, example: "100% of records filed within 24 hours"),
                    new OA\Property(property: "learning_strategies_applied", type: "string", nullable: true, example: "On-the-job coaching"),
                    new OA\Property(property: "required_resources", type: "string", nullable: true, example: "Filing cabinet, scanner"),
                    new OA\Property(property: "target_date_completion", type: "string", nullable: true, example: "2026-03-01"),
                    new OA\Property(property: "status_as_of_v1", type: "string", nullable: true, example: "Ongoing"),
                    new OA\Property(property: "status_as_of_v2", type: "string", nullable: true, example: "Completed"),

                    new OA\Property(property: "planning_organizing", type: "boolean", nullable: true, example: true, description: "Technical monitoring"),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", nullable: true, example: false, description: "Technical monitoring"),
                    new OA\Property(property: "records_management", type: "boolean", nullable: true, example: true, description: "Technical monitoring"),
                    new OA\Property(property: "partnering_networking", type: "boolean", nullable: true, example: false, description: "Technical monitoring"),
                    new OA\Property(property: "process_management", type: "boolean", nullable: true, example: true, description: "Technical monitoring"),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", nullable: true, example: true, description: "Leadership monitoring"),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", nullable: true, example: false, description: "Leadership monitoring"),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", nullable: true, example: true, description: "Leadership monitoring"),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", nullable: true, example: false, description: "Leadership monitoring"),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", nullable: true, example: true, description: "Core monitoring"),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", nullable: true, example: true, description: "Core monitoring"),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", nullable: true, example: false, description: "Core monitoring"),
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
                            description: "Indexed array: [0] LearningApplicationMonitoringReport, [1] CoreMonitoring, [2] LeadershipMonitoring, [3] TechnicalMonitoring, [4] EmployeeFormSubmission",
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
        path: "/api/erms/learning-application-monitoring/update/{learningApplicationMonitoringFormId}",
        summary: "Update an existing Learning Application Monitoring Report Form",
        description: "Updates the LearningApplicationMonitoringReport record and upserts (updateOrCreate) its Core, Leadership, and Technical monitoring child records. If a matching EmployeeFormSubmission exists for the event/control_no, its status is reset back to 'Pending'.",
        operationId: "updateLearningApplicationMonitoringReport",
        tags: ["Learning Application Monitoring Report Form"],
        parameters: [
            new OA\Parameter(
                name: "learningApplicationMonitoringFormId",
                description: "LearningApplicationMonitoringReport primary key",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id", "form_name", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in events table"),
                    new OA\Property(property: "form_name", type: "string", example: "Learning Application Monitoring Report Form"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "learner", type: "string", nullable: true, example: "Juan Dela Cruz"),
                    new OA\Property(property: "lnd_attended", type: "string", nullable: true, example: "Basic Supervisory Course"),
                    new OA\Property(property: "date_of_attendance", type: "string", format: "date", nullable: true, example: "2026-01-15"),
                    new OA\Property(property: "competency_developed_acquired", type: "string", nullable: true, example: "Records Management"),

                    new OA\Property(property: "goals", type: "string", nullable: true, example: "Improve records filing turnaround time"),
                    new OA\Property(property: "performance_indicator", type: "string", nullable: true, example: "100% of records filed within 24 hours"),
                    new OA\Property(property: "learning_strategies_applied", type: "string", nullable: true, example: "On-the-job coaching"),
                    new OA\Property(property: "required_resources", type: "string", nullable: true, example: "Filing cabinet, scanner"),
                    new OA\Property(property: "target_date_completion", type: "string", nullable: true, example: "2026-03-01"),
                    new OA\Property(property: "status_as_of_v1", type: "string", nullable: true, example: "Ongoing"),
                    new OA\Property(property: "status_as_of_v2", type: "string", nullable: true, example: "Completed"),

                    new OA\Property(property: "planning_organizing", type: "boolean", nullable: true, example: true, description: "Technical monitoring"),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", nullable: true, example: false, description: "Technical monitoring"),
                    new OA\Property(property: "records_management", type: "boolean", nullable: true, example: true, description: "Technical monitoring"),
                    new OA\Property(property: "partnering_networking", type: "boolean", nullable: true, example: false, description: "Technical monitoring"),
                    new OA\Property(property: "process_management", type: "boolean", nullable: true, example: true, description: "Technical monitoring"),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", nullable: true, example: true, description: "Leadership monitoring"),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", nullable: true, example: false, description: "Leadership monitoring"),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", nullable: true, example: true, description: "Leadership monitoring"),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", nullable: true, example: false, description: "Leadership monitoring"),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", nullable: true, example: true, description: "Core monitoring"),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", nullable: true, example: true, description: "Core monitoring"),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", nullable: true, example: false, description: "Core monitoring"),
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
                            description: "Indexed array: [0] LearningApplicationMonitoringReport (fresh), [1] CoreMonitoring, [2] LeadershipMonitoring, [3] TechnicalMonitoring, [4] EmployeeFormSubmission|null",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Learning Application Monitoring Report Form not found, or another error occurred",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learner progrees report id not found"),
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
        path: "/api/erms/learning-application-monitoring/delete/{learningApplicationMonitoringFormId}",
        summary: "Delete a Learning Application Monitoring Report Form",
        description: "Deletes the LearningApplicationMonitoringReport and its associated EmployeeFormSubmission. Blocked if the associated EmployeeFormSubmission status is already 'Approved'.",
        operationId: "destroyLearningApplicationMonitoringReport",
        tags: ["Learning Application Monitoring Report Form"],
        parameters: [
            new OA\Parameter(
                name: "learningApplicationMonitoringFormId",
                description: "LearningApplicationMonitoringReport primary key",
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
                            description: "The deleted LearningApplicationMonitoringReport",
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
                response: 400,
                description: "Learning Application Monitoring Report Form not found, or cannot delete an already approved report",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Cannot delete an already approved Learning application monitoring."),
                    ]
                )
            ),
        ]
    )]
    public function destroy() {}
}