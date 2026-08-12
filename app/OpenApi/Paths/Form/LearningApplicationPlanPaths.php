<?php

namespace App\OpenApi\Paths\Form;

use OpenApi\Attributes as OA;

/**
 * Documents the EmployeeLearningApplicationPlanController endpoints
 * (routes/api.php `erms/learning-application-plan` group).
 *
 *   GET    /erms/learning-application-plan/{eventId}/{formName}/{controlNo}         -> show()
 *   POST   /erms/learning-application-plan/store                                    -> store()
 *   PUT    /erms/learning-application-plan/update/{learningApplicationPlanId}        -> update()
 *   DELETE /erms/learning-application-plan/delete/{learningApplicationPlanId}        -> destroy()
 *
 * NOTE: Route paths above follow the same store/update/{id}/delete/{id} convention
 * used by LearnerProgressReportPaths. Adjust to match the actual routes/api.php
 * entries if the group is named differently.
 *
 * NOTE: No `security` block is added below — confirm whether this group is
 * registered ->withoutMiddleware(['auth:sanctum']) like learner-progress, or
 * requires Sanctum auth, and add `security: [["sanctum" => []]]` if so.
 *
 * NOTE: index() in the controller is empty (not implemented), so it is
 * intentionally not documented here.
 *
 * NOTE: delete() reads `$learner_application_plan->forms_name` to look up the
 * linked EmployeeFormSubmission, but create()/edit() persist the column as
 * `form_name` (no "s"). If the model has no `forms_name` attribute, that
 * where() clause receives null and the submission lookup/delete may silently
 * fail to match. Verify the actual column name before relying on this docs'
 * "Cannot delete an approved plan" guard behaving as described.
 *
 * NOTE: store() has no try/catch in the controller. The "already submitted"
 * exception thrown by the service will surface as an unhandled 500, not the
 * 400 documented below — wrap the controller call in try/catch to match.
 *
 * NOTE: BeneficiariesStrategieApplied and ResourcesUtilized are both persisted
 * from `strategic_functions` / `core_functions` / `support_functions` in the
 * service (create() and edit()), not from their own request fields
 * (employees_staff, digital_technologies, etc). Those request fields are
 * accepted by validation but currently unused. Documented as-is below.
 *
 * NOTE: {formName} and {controlNo} are string route params — if form_name or
 * control_no values contain spaces, the client must URL-encode them
 * (e.g. encodeURIComponent) before calling this endpoint.
 *
 * NOTE: LearningApplicationPlan has boolean columns 'foundation', 'supervisory',
 * and 'managerial' AND eager-loaded relations of the exact same names (see the
 * ->with([...]) call in show()). Eloquent's toArray() merges relationsToArray()
 * over attributesToArray(), so the relation object silently overwrites the
 * boolean column under each of those keys in the JSON response — the plan-level
 * true/false flags are effectively unreadable via this endpoint as currently
 * named. Consider renaming the relation methods (e.g. foundationCompetency())
 * to resolve. This also caused a real l5-swagger:generate failure here
 * ("Multiple @OA\Property() with the same property=...") since both the column
 * and the relation were documented under the same key — the docs below now
 * only show the relation (matching actual runtime output).
 */
class LearningApplicationPlanPaths
{
    #[OA\Post(
        path: "/api/erms/learning-application-plan/store",
        summary: "Submit a Learning Application Plan form",
        description: "Creates a Learning Application Plan and its related competency/strategy/resource sub-records for an employee event. Fails if the employee (control_no) already submitted this form for the given event.",
        operationId: "storeLearningApplicationPlan",
        tags: ["Learning Application Plan Form"],
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ["event_id", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1, description: "Must exist in events table"),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "office", type: "string", example: "HRMO"),
                    new OA\Property(property: "learner", type: "string", example: "Juan Dela Cruz"),
                    new OA\Property(property: "title_of_intervention", type: "string", example: "Basic Supervisory Training"),
                    new OA\Property(property: "date_conducted", type: "string", example: "2026-01-15"),
                    new OA\Property(property: "venue", type: "string", example: "City Hall Function Room"),
                    new OA\Property(property: "foundation", type: "boolean", example: true),
                    new OA\Property(property: "techinal", type: "boolean", example: true, description: "Sic: matches DB column spelling"),
                    new OA\Property(property: "supervisory", type: "boolean", example: false),
                    new OA\Property(property: "managerial", type: "boolean", example: false),
                    new OA\Property(property: "significant_learning_insight", type: "string", example: "Improved report turnaround time."),

                    new OA\Property(property: "delivering_service_excellence", type: "boolean", example: true),
                    new OA\Property(property: "exemplifying_integrity", type: "boolean", example: true),
                    new OA\Property(property: "interpersonal_skills", type: "boolean", example: false),

                    new OA\Property(property: "planning_organizing", type: "boolean", example: true),
                    new OA\Property(property: "monitoring_evaluation", type: "boolean", example: false),
                    new OA\Property(property: "records_management", type: "boolean", example: true),
                    new OA\Property(property: "partnering_networking", type: "boolean", example: false),
                    new OA\Property(property: "process_management", type: "boolean", example: true),
                    new OA\Property(property: "attention_detail", type: "boolean", example: true),

                    new OA\Property(property: "managing_performance_coaching_results", type: "boolean", example: true),
                    new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", example: false),
                    new OA\Property(property: "thinking_strategically_creatively", type: "boolean", example: true),
                    new OA\Property(property: "problem_solving_decision_making", type: "boolean", example: false),

                    new OA\Property(property: "supervisory_managing_performance_coaching_results", type: "boolean", example: true),
                    new OA\Property(property: "supervisory_building_collaborative_inclusive_working_relationships", type: "boolean", example: false),

                    new OA\Property(property: "immediate_application_skills", type: "boolean", example: true),
                    new OA\Property(property: "knowledge_sharing", type: "boolean", example: true),
                    new OA\Property(property: "peer_coaching_collaboration", type: "boolean", example: false),
                    new OA\Property(property: "develop_office_policies_guidelines", type: "boolean", example: false),
                    new OA\Property(property: "create_pilot_project", type: "boolean", example: false),
                    new OA\Property(property: "include_ipcr", type: "boolean", example: true),

                    new OA\Property(property: "strategic_functions", type: "boolean", example: true, description: "Also feeds beneficiaries/resources sub-records, see class NOTE"),
                    new OA\Property(property: "core_functions", type: "boolean", example: false, description: "Also feeds beneficiaries/resources sub-records, see class NOTE"),
                    new OA\Property(property: "support_functions", type: "boolean", example: true, description: "Also feeds beneficiaries/resources sub-records, see class NOTE"),

                    new OA\Property(property: "employees_staff", type: "boolean", example: true, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "office_department", type: "boolean", example: false, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "city_government_organization", type: "boolean", example: false, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "clients_stakeholders_general_public", type: "boolean", example: true, description: "Accepted by validation, currently unused by the service"),

                    new OA\Property(property: "digital_technologies", type: "boolean", example: true, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "physical_printed_resources", type: "boolean", example: false, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "human_resources_organizational_support", type: "boolean", example: true, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "financial_logistical_support", type: "boolean", example: false, description: "Accepted by validation, currently unused by the service"),
                    new OA\Property(property: "policy_process_resources", type: "boolean", example: false, description: "Accepted by validation, currently unused by the service"),

                    new OA\Property(property: "within_2_weeks_after_training", type: "boolean", example: true),
                    new OA\Property(property: "within_1_month_after_training", type: "boolean", example: false),
                    new OA\Property(property: "within_2_months_after_training", type: "boolean", example: false),
                    new OA\Property(property: "within_3_months_after_training", type: "boolean", example: false),
                ]
            )
        ),
        responses: [
            new OA\Response(
                response: 201,
                description: "Success",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: true),
                        new OA\Property(property: "message", type: "string", example: "success create"),
                        new OA\Property(
                            property: "data",
                            type: "array",
                            description: "Ordered list: [plan, foundation, technical, managerial, supervisory, learningStrategies, performanceIndicator, beneficiaries, resources, targetCompletion, formSubmission]",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
            new OA\Response(
                response: 400,
                description: "Employee already submitted this form for the event (see class NOTE re: unhandled 500)",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "You already submitted this form. You can edit or delete it instead."),
                    ]
                )
            ),
        ]
    )]
    public function store()
    {
    }

    #[OA\Get(
        path: "/api/erms/learning-application-plan/{eventId}/{formName}/{controlNo}",
        summary: "Get a Learning Application Plan Form by event, form name, and control no",
        description: "Returns a single Learning Application Plan Form with its related foundation, technical, managerial, supervisory, learningStrategies, performanceIndicator, beneficiaries, resources, and targetCompletion records.",
        operationId: "showLearningApplicationPlan",
        tags: ["Learning Application Plan Form"],
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
                schema: new OA\Schema(type: "string", example: "Learning Application Plan")
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
                                new OA\Property(property: "form_name", type: "string", example: "Learning Application Plan"),
                                new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                                new OA\Property(property: "office", type: "string", example: "HRMO"),
                                new OA\Property(property: "learner", type: "string", example: "Juan Dela Cruz"),
                                new OA\Property(property: "title_of_intervention", type: "string", example: "Basic Supervisory Training"),
                                new OA\Property(property: "date_conducted", type: "string", format: "date", example: "2026-01-15"),
                                new OA\Property(property: "venue", type: "string", example: "City Hall Function Room"),
                                new OA\Property(property: "techinal", type: "boolean", example: true),
                                new OA\Property(property: "significant_learning_insight", type: "string", example: "Improved report turnaround time."),
                                new OA\Property(
                                    property: "foundation",
                                    type: "object",
                                    description: "FoundationCompetencie relation. Column/relation name collision: the plan's own boolean 'foundation' column is overwritten by this relation in toArray() and is not visible in the response. See class NOTE.",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "delivering_service_excellence", type: "boolean", example: true),
                                        new OA\Property(property: "exemplifying_integrity", type: "boolean", example: true),
                                        new OA\Property(property: "interpersonal_skills", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "technical",
                                    type: "object",
                                    description: "TechnicalCompetencie relation",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "planning_organizing", type: "boolean", example: true),
                                        new OA\Property(property: "monitoring_evaluation", type: "boolean", example: false),
                                        new OA\Property(property: "records_management", type: "boolean", example: true),
                                        new OA\Property(property: "partnering_networking", type: "boolean", example: false),
                                        new OA\Property(property: "process_management", type: "boolean", example: true),
                                        new OA\Property(property: "attention_detail", type: "boolean", example: true),
                                    ]
                                ),
                                new OA\Property(
                                    property: "managerial",
                                    type: "object",
                                    description: "ManagerialCompetencie relation. Column/relation name collision: the plan's own boolean 'managerial' column is overwritten by this relation in toArray() and is not visible in the response. See class NOTE.",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "managing_performance_coaching_results", type: "boolean", example: true),
                                        new OA\Property(property: "building_collaborative_inclusive_working_relationships", type: "boolean", example: false),
                                        new OA\Property(property: "thinking_strategically_creatively", type: "boolean", example: true),
                                        new OA\Property(property: "problem_solving_decision_making", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "supervisory",
                                    type: "object",
                                    description: "SupervisoryCompetencie relation. Column/relation name collision: the plan's own boolean 'supervisory' column is overwritten by this relation in toArray() and is not visible in the response. See class NOTE.",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "supervisory_managing_performance_coaching_results", type: "boolean", example: true),
                                        new OA\Property(property: "supervisory_building_collaborative_inclusive_working_relationships", type: "boolean", example: false),
                                    ]
                                ),
                                new OA\Property(
                                    property: "learningStrategies",
                                    type: "object",
                                    description: "LearningStrategiesImplemented relation",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "immediate_application_skills", type: "boolean", example: true),
                                        new OA\Property(property: "knowledge_sharing", type: "boolean", example: true),
                                        new OA\Property(property: "peer_coaching_collaboration", type: "boolean", example: false),
                                        new OA\Property(property: "develop_office_policies_guidelines", type: "boolean", example: false),
                                        new OA\Property(property: "create_pilot_project", type: "boolean", example: false),
                                        new OA\Property(property: "include_ipcr", type: "boolean", example: true),
                                    ]
                                ),
                                new OA\Property(
                                    property: "performanceIndicator",
                                    type: "object",
                                    description: "PerformanceIndicator relation",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "strategic_functions", type: "boolean", example: true),
                                        new OA\Property(property: "core_functions", type: "boolean", example: false),
                                        new OA\Property(property: "support_functions", type: "boolean", example: true),
                                    ]
                                ),
                                new OA\Property(
                                    property: "beneficiaries",
                                    type: "object",
                                    description: "BeneficiariesStrategieApplied relation - values sourced from strategic/core/support_functions, see class NOTE",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "employees_staff", type: "boolean", example: true),
                                        new OA\Property(property: "office_department", type: "boolean", example: false),
                                        new OA\Property(property: "city_government_organization", type: "boolean", example: true),
                                        new OA\Property(property: "clients_stakeholders_general_public", type: "boolean", example: true),
                                    ]
                                ),
                                new OA\Property(
                                    property: "resources",
                                    type: "object",
                                    description: "ResourcesUtilized relation - values sourced from strategic/core/support_functions, see class NOTE",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "digital_technologies", type: "boolean", example: true),
                                        new OA\Property(property: "physical_printed_resources", type: "boolean", example: false),
                                        new OA\Property(property: "human_resources_organizational_support", type: "boolean", example: true),
                                        new OA\Property(property: "financial_logistical_support", type: "boolean", example: true),
                                        new OA\Property(property: "policy_process_resources", type: "boolean", example: true),
                                    ]
                                ),
                                new OA\Property(
                                    property: "targetCompletion",
                                    type: "object",
                                    description: "TargetDateCompletion relation",
                                    properties: [
                                        new OA\Property(property: "id", type: "integer", example: 1),
                                        new OA\Property(property: "learning_application_plan_form_id", type: "integer", example: 1),
                                        new OA\Property(property: "within_2_weeks_after_training", type: "boolean", example: true),
                                        new OA\Property(property: "within_1_month_after_training", type: "boolean", example: false),
                                        new OA\Property(property: "within_2_months_after_training", type: "boolean", example: false),
                                        new OA\Property(property: "financial_logistical_support", type: "boolean", example: true),
                                        new OA\Property(property: "within_3_months_after_training", type: "boolean", example: false),
                                    ]
                                ),
                            ]
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Learning Application Plan not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learning application plan id not found"),
                    ]
                )
            ),
        ]
    )]
    public function show()
    {
    }

    #[OA\Put(
        path: "/api/erms/learning-application-plan/update/{learningApplicationPlanId}",
        summary: "Update a Learning Application Plan Form",
        description: "Updates the Learning Application Plan and upserts (updateOrCreate) all its related sub-records. Resets the linked EmployeeFormSubmission status back to Pending.",
        operationId: "updateLearningApplicationPlan",
        tags: ["Learning Application Plan Form"],
        parameters: [
            new OA\Parameter(
                name: "learningApplicationPlanId",
                description: "Learning Application Plan record ID",
                in: "path",
                required: true,
                schema: new OA\Schema(type: "integer", example: 1)
            ),
        ],
        requestBody: new OA\RequestBody(
            required: true,
            description: "Same shape as POST /erms/learning-application-plan/store",
            content: new OA\JsonContent(
                required: ["event_id", "control_no"],
                properties: [
                    new OA\Property(property: "event_id", type: "integer", example: 1),
                    new OA\Property(property: "control_no", type: "string", example: "2021-00123"),
                    new OA\Property(property: "office", type: "string", example: "HRMO"),
                    new OA\Property(property: "learner", type: "string", example: "Juan Dela Cruz"),
                    new OA\Property(property: "title_of_intervention", type: "string", example: "Basic Supervisory Training"),
                    new OA\Property(property: "date_conducted", type: "string", example: "2026-01-15"),
                    new OA\Property(property: "venue", type: "string", example: "City Hall Function Room"),
                    new OA\Property(property: "foundation", type: "boolean", example: true),
                    new OA\Property(property: "techinal", type: "boolean", example: true),
                    new OA\Property(property: "supervisory", type: "boolean", example: false),
                    new OA\Property(property: "managerial", type: "boolean", example: false),
                    new OA\Property(property: "significant_learning_insight", type: "string", example: "Improved report turnaround time."),
                ],
                example: [
                    "note" => "Full field set matches POST /store - see that operation for all boolean competency/strategy/resource fields.",
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
                            description: "Ordered list: [plan, foundation, technical, managerial, supervisory, learningStrategies, performanceIndicator, beneficiaries, resources, targetCompletion, formSubmission]",
                            items: new OA\Items(type: "object")
                        ),
                    ]
                )
            ),
            new OA\Response(
                response: 422,
                description: "Validation error"
            ),
            new OA\Response(
                response: 400,
                description: "Learning Application Plan ID not found",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Learner progrees report id not found"),
                    ]
                )
            ),
        ]
    )]
    public function update()
    {
    }

    #[OA\Delete(
        path: "/api/erms/learning-application-plan/delete/{learningApplicationPlanId}",
        summary: "Delete a Learning Application Plan Form",
        description: "Deletes the Learning Application Plan and its linked EmployeeFormSubmission. Blocked if the linked submission status is Approved. See class NOTE re: form_name/forms_name mismatch affecting the submission lookup.",
        operationId: "destroyLearningApplicationPlan",
        tags: ["Learning Application Plan Form"],
        parameters: [
            new OA\Parameter(
                name: "learningApplicationPlanId",
                description: "Learning Application Plan record ID",
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
                        new OA\Property(property: "data", type: "object", description: "The deleted Learning Application Plan record"),
                    ]
                )
            ),
            new OA\Response(
                response: 400,
                description: "Not found, or already approved and cannot be deleted",
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: "success", type: "boolean", example: false),
                        new OA\Property(property: "message", type: "string", example: "Cannot delete an already approved Learning application plan."),
                    ]
                )
            ),
        ]
    )]
    public function destroy()
    {
    }
}