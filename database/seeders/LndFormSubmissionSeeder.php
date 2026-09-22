<?php

namespace Database\Seeders;

use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAMR\CoreMonitoring;
use App\Models\Forms\LAMR\LeadershipMonitoring;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAMR\TechnicalMonitoring;
use App\Models\Forms\LAP\BeneficiariesStrategieApplied;
use App\Models\Forms\LAP\FoundationCompetencie;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Models\Forms\LAP\LearningStrategiesImplemented;
use App\Models\Forms\LAP\ManagerialCompetencie;
use App\Models\Forms\LAP\PerformanceIndicator;
use App\Models\Forms\LAP\ResourcesUtilized;
use App\Models\Forms\LAP\SupervisoryCompetencie;
use App\Models\Forms\LAP\TargetDateCompletion;
use App\Models\Forms\LAP\TechnicalCompetencie;
use App\Models\Forms\LIR\CoreImplementation;
use App\Models\Forms\LIR\LeadershipImplementation;
use App\Models\Forms\LIR\LearningImplementationForm;
use App\Models\Forms\LIR\TechinicalImplementation;
use App\Models\Forms\LPR\CoreProgress;
use App\Models\Forms\LPR\LeadershipProgress;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Models\Forms\LPR\TechnicalProgress;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class LndFormSubmissionSeeder extends Seeder
{
    /**
     * The 4 form names supported by this feature.
     * NOTE: "Leaner Progress Report" spelling below matches formName()
     * as written in LearnerProgressForm service — fix the typo there
     * if it's unintentional ("Learner" vs "Leaner").
     */
    private array $formNames = [
        'Learner Progress Report', // ⚠️ check: service class returns 'Leaner Progress Report' (typo) — see note below
        'Learning Application Monitoring Report',
        'Learning Application Plan',
        'Learning Implementation Report',
    ];

    public function run(): void
    {
        DB::transaction(function () {

            // -----------------------------------------------------
            // 1. Create an Event and attach all 4 forms to it
            // -----------------------------------------------------
            // $event = Event::create([
            //     'title_name' => 'Seeded LND Event for Form Testing',
            //     // 👇 add other required columns on your events table here
            // ]);

            // // ⚠️ ASSUMPTION: Event has a many-to-many "form" relationship
            // // via a pivot table (e.g. event_form) with a "form_name" column.
            // // Adjust this block to match your actual relationship method.
            // foreach ($this->formNames as $formName) {
            //     $event->form()->create([
            //         'form_name' => $formName,
            //     ]);
            // }

            // // -----------------------------------------------------
            // // 2. Create an EventSchedule with status Approved
            // // -----------------------------------------------------
            // $eventSchedule = EventSchedule::create([
            //     'event_id' => $event->id,
            //     'status' => 'Approved', // required so create() passes the status check
            //     // 👇 add other required columns (venue_name, hours, fee, etc.)
            // ]);

            $controlNo = '022485'; // sample employee control no.

            // -----------------------------------------------------
            // 3. Learner Progress Report
            // -----------------------------------------------------
            $formSubmit1 = EmployeeFormSubmission::create([
                'event_id' => 1,
                'event_schedule_id' => 1,
                'form_name' => 'Learner Progress Report', // match actual formName() used
                'control_no' => $controlNo,
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            $learnerForm = LearnerProgressForm::create([
                'employee_form_submission_id' => $formSubmit1->id,
                'form_name' => 'Learner Progress Report',
                'control_no' => $controlNo,
                'learner' => 'Cliford M. Millan',
                'lnd_attended' => 'Basic Supervisory Course',
                'date_of_attendance' => '2026-06-15',
                'delivering_service_excellence_competency' => 4,
                'exemplifying_integrity_competency' => 5,
                'interpersonal_skills_competency' => 4,
                'planning_organizing_competency' => 3,
                // 'monitoring_evaluation_competency' => 3,
                'records_management_competency' => 4,
                'partnering_networking_competency' => 4,
                'process_management_competency' => 3,
                'managing_performance_coaching_results_competency' => 4,
                'building_collaborative_inclusive_working_relationships_competency' => 5,
                'thinking_strategically_creatively_competency' => 4,
                'problem_solving_decision_making_competency' => 4,
            ]);

            CoreProgress::create([
                'learner_progress_form_id' => $learnerForm->id,
                'delivering_service_excellence' => true,
                'exemplifying_integrity' => true,
                'interpersonal_skills' => false,
            ]);

            LeadershipProgress::create([
                'learner_progress_form_id' => $learnerForm->id,
                'managing_performance_coaching_results' => true,
                'building_collaborative_inclusive_working_relationships' => true,
                'thinking_strategically_creatively' => false,
                'problem_solving_decision_making' => true,
            ]);

            TechnicalProgress::create([
                'learner_progress_form_id' => $learnerForm->id,
                'planning_organizing' => true,
                'monitoring_evaluation' => false,
                'records_management' => true,
                'partnering_networking' => false,
                'process_management' => true,
            ]);

            // -----------------------------------------------------
            // 4. Learning Application Monitoring Report
            // -----------------------------------------------------
            $formSubmit2 = EmployeeFormSubmission::create([
                'event_id' => 1,
                'event_schedule_id' => 1,
                'form_name' => 'Learning Application Monitoring Report',
                'control_no' => $controlNo,
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            $monitoringForm = LearningApplicationMonitoringForm::create([
                'employee_form_submission_id' => $formSubmit2->id,
                'form_name' => 'Learning Application Monitoring Report',
                'control_no' => $controlNo,
                'learner' => 'Cliford M. Millan',
                'lnd_attended' => 'Basic Supervisory Course',
                'date_of_attendance' => '2026-06-15',
                'competency_developed_acquired' => 'Improved records management skills',
                'goals' => 'Apply learned skills in daily operations',
                'performance_indicator' => 'Reduced processing time by 20%',
                'learning_strategies_applied' => 'Peer coaching and job application',
                'required_resources' => 'Updated SOP manual, workstation access',
                'target_date_completion' => 'Within 1 month after training',
                'status_as_of_v1' => 'Ongoing',
                'status_as_of_v2' => 'On track',
            ]);

            CoreMonitoring::create([
    'learning_application_monitoring_form_id' => $monitoringForm->id, // 👈 fixed
                'delivering_service_excellence' => true,
                'exemplifying_integrity' => true,
                'interpersonal_skills' => true,
            ]);

            LeadershipMonitoring::create([
    'learning_application_monitoring_form_id' => $monitoringForm->id, // 👈 fixed
                'managing_performance_coaching_results' => false,
                'building_collaborative_inclusive_working_relationships' => true,
                'thinking_strategically_creatively' => true,
                'problem_solving_decision_making' => false,
            ]);

            TechnicalMonitoring::create([
    'learning_application_monitoring_form_id' => $monitoringForm->id, // 👈 fixed
                'planning_organizing' => true,
                'monitoring_evaluation' => true,
                'records_management' => true,
                'partnering_networking' => false,
                'process_management' => false,
            ]);

            // -----------------------------------------------------
            // 5. Learning Application Plan
            // -----------------------------------------------------
            $formSubmit3 = EmployeeFormSubmission::create([
                'event_id' => 1,
                'event_schedule_id' => 1,
                'form_name' => 'Learning Application Plan',
                'control_no' => $controlNo,
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            $planForm = LearningApplicationPlanForm::create([
                'employee_form_submission_id' => $formSubmit3->id,
                'form_name' => 'Learning Application Plan',
                'control_no' => $controlNo,
                'office' => 'City ICT Management Office',
                'learner' => 'Cliford M. Millan',
                'title_of_intervention' => 'Basic Supervisory Course',
                'date_conducted' => '2026-06-15',
                'venue' => 'City Training Hall',
                'foundation' => true,
                'techinal' => true,
                'supervisory' => false,
                'managerial' => false,
                'significant_learning_insight' => 'Learned effective time management techniques.',
            ]);

            FoundationCompetencie::create([
                'learning_application_plan_form_id' => $planForm->id,
                'delivering_service_excellence' => true,
                'exemplifying_integrity' => true,
                'interpersonal_skills' => false,
            ]);

            TechnicalCompetencie::create([
                'learning_application_plan_form_id' => $planForm->id,
                'planning_organizing' => true,
                'monitoring_evaluation' => false,
                'records_management' => true,
                'partnering_networking' => false,
                'process_management' => true,
                'attention_detail' => true,
            ]);

            ManagerialCompetencie::create([
                'learning_application_plan_form_id' => $planForm->id,
                'managing_performance_coaching_results' => false,
                'building_collaborative_inclusive_working_relationships' => true,
                'thinking_strategically_creatively' => false,
                'problem_solving_decision_making' => true,
            ]);

            SupervisoryCompetencie::create([
                'learning_application_plan_form_id' => $planForm->id,
                'supervisory_managing_performance_coaching_results' => false,
                'supervisory_building_collaborative_inclusive_working_relationships' => false,
            ]);

            LearningStrategiesImplemented::create([
                'learning_application_plan_form_id' => $planForm->id,
                'immediate_application_skills' => true,
                'knowledge_sharing' => true,
                'peer_coaching_collaboration' => false,
                'develop_office_policies_guidelines' => false,
                'create_pilot_project' => false,
                'include_ipcr' => true,
            ]);

            PerformanceIndicator::create([
                'learning_application_plan_form_id' => $planForm->id,
                'strategic_functions' => true,
                'core_functions' => true,
                'support_functions' => false,
            ]);

            BeneficiariesStrategieApplied::create([
                'learning_application_plan_form_id' => $planForm->id,
                'employees_staff' => true,
                'office_department' => true,
                'city_government_organization' => false,
                'clients_stakeholders_general_public' => false,
            ]);

            ResourcesUtilized::create([
                'learning_application_plan_form_id' => $planForm->id,
                'digital_technologies' => true,
                'physical_printed_resources' => false,
                'human_resources_organizational_support' => true,
                'financial_logistical_support' => false,
                'policy_process_resources' => false,
            ]);

            TargetDateCompletion::create([
                'learning_application_plan_form_id' => $planForm->id,
                'within_2_weeks_after_training' => false,
                'within_1_month_after_training' => true,
                'within_2_months_after_training' => false,
                'within_3_months_after_training' => false,
            ]);

            // -----------------------------------------------------
            // 6. Learning Implementation Report
            // -----------------------------------------------------
            $formSubmit4 = EmployeeFormSubmission::create([
                'event_id' => 1,
                'event_schedule_id' => 1,
                'form_name' => 'Learning Implementation Report',
                'control_no' => $controlNo,
                'status' => 'Pending',
                'submitted_at' => now(),
            ]);

            $implementationForm = LearningImplementationForm::create([
                'employee_form_submission_id' => $formSubmit4->id,
                'form_name' => 'Learning Implementation Report',
                'control_no' => $controlNo,
                'learner' => 'Cliford M. Millan',
                'lnd_attended' => 'Basic Supervisory Course',
                'date_of_attendance' => '2026-06-15',
                'competency_developed_acquired' => 'Records management and process improvement',
                'learning_strategies_applied' => 'Applied new filing system in the office',
                'resources_used' => 'Updated SOP manual, digital filing tools',
                'beneficiaries_strategies_applied' => 'Office staff and walk-in clients',
                'performance_indicators_behavior_toward_work' => 'Improved turnaround time for document requests',
                'financial_aid_training_attended' => 'None',
                'return_financial_aid' => 'N/A',
            ]);

            CoreImplementation::create([
                'learning_implementation_form_id' => $implementationForm->id,
                'delivering_service_excellence' => true,
                'exemplifying_integrity' => true,
                'interpersonal_skills' => true,
            ]);

            LeadershipImplementation::create([
                'learning_implementation_form_id' => $implementationForm->id,
                'managing_performance_coaching_results' => false,
                'building_collaborative_inclusive_working_relationships' => true,
                'thinking_strategically_creatively' => false,
                'problem_solving_decision_making' => true,
            ]);

            TechinicalImplementation::create([
                'learning_implementation_form_id' => $implementationForm->id,
                'planning_organizing' => true,
                'monitoring_evaluation' => true,
                'records_management' => true,
                'partnering_networking' => false,
                'process_management' => false,
            ]);
        });

        $this->command->info('LND form submissions seeded successfully.');
    }
}