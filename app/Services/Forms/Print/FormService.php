<?php

namespace App\Services\Forms\Print;

use App\Models\Event\EmployeeFormSubmission;
use App\Models\Event\EventSchedule;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Models\Forms\LES\EvaluationInstrument;
use App\Models\Forms\LIR\LearningImplementationForm;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Models\Forms\RSE\TrainingEvaluationSpeakers;
use Illuminate\Database\Eloquent\Model;

class FormService
{
    /* =====================================================================
       LEARNER PROGRESS REPORT (LPR)
    ===================================================================== */

    private const CORE = [
        'delivering_service_excellence' => 'Delivery Service Excellence',
        'exemplifying_integrity'        => 'Exemplifying Integrity',
        'interpersonal_skills'          => 'Interpersonal Skills',
    ];

    private const TECHNICAL = [
        'planning_organizing'   => 'Planning & Organizing',
        'monitoring_evaluation' => 'Monitoring & Evaluation',
        'records_management'    => 'Records Management',
        'partnering_networking' => 'Partnering & Networking',
        'process_management'    => 'Process Management',
        'attention_details'     => 'Attention to Detail',
    ];

    private const LEADERSHIP = [
        'thinking_strategically_creatively'                      => 'Thinking Strategically and Creativity',
        'problem_solving_decision_making'                        => 'Problem Solving & Decision Making',
        'building_collaborative_inclusive_working_relationships' => 'Building Collaborative & Inclusive Working Relationships',
        'managing_performance_coaching_results'                  => 'Managing Performance & Coaching for Results',
    ];

    // column => label, sa order ng template
    private const RATINGS = [
        'delivering_service_excellence_competency'                          => 'Delivering Service Excellence',
        'exemplifying_integrity_competency'                                 => 'Exemplifying Integrity',
        'interpersonal_skills_competency'                                   => 'Interpersonal Skills',
        'monitoring_evaluation_competency'                                  => 'Monitoring & Evaluation',
        'records_management_competency'                                     => 'Records Management',
        'attention_detail_competency'                                       => 'Attention To Detail',
        'thinking_strategically_creatively_competency'                      => 'Thinking Strategically and Creativity',
        'problem_solving_decision_making_competency'                        => 'Problem Solving & Decision Making',
        'building_collaborative_inclusive_working_relationships_competency' => 'Building Collaborative & Inclusive Working Relationships',
        'managing_performance_coaching_results_competency'                  => 'Managing Performance & Coaching For Result',
        'planning_organizing_competency'                                    => 'Planning & Organizing',
        'process_management_competency'                                     => 'Process Management',
        'partnering_networking_competency'                                  => 'Partnering & Networking',
    ];


    /* =====================================================================
       HELPERS
    ===================================================================== */

    // label => true/false. Hindi mag-e-error kung hindi model ang pumasok.
    private function flags(mixed $src, array $map): array
    {
        $out = [];
        foreach ($map as $col => $label) {
            $value = $src instanceof Model ? $src->{$col} : null;
            $out[$label] = filter_var($value, FILTER_VALIDATE_BOOLEAN);
        }
        return $out;
    }

    // Kukunin ang naka-load na relation, kahit may column na kapareho ang pangalan.
    private function rel(Model $model, string $name): ?Model
    {
        return $model->relationLoaded($name) ? $model->getRelation($name) : null;
    }

    // label => 1..5 (o null)
    private function ratings(Model $report): array
    {
        $out = [];
        foreach (self::RATINGS as $col => $label) {
            $out[$label] = ((int) ($report->{$col} ?? 0)) ?: null;
        }
        return $out;
    }

    // approved lang ang pwedeng i-print
    private function approvedSubmission(int $submissionId): EmployeeFormSubmission
    {
        $submission = EmployeeFormSubmission::findOrFail($submissionId);
        abort_unless($submission->status === 'Approved', 403, 'Only approved forms can be printed.');

        return $submission;
    }

    /* =====================================================================
       DATA PARA SA PDF
    ===================================================================== */

    public function learnerProgressData(int $submissionId): array
    {
        $submission = $this->approvedSubmission($submissionId);

        $report = LearnerProgressForm::with(['coreProgress', 'leaderShipProgress', 'technicalProgress'])
            ->where('employee_form_submission_id', $submission->getKey())
            ->firstOrFail();

        return [
            'learner'          => $report->learner,
            'lndAttended'      => $report->lnd_attended,
            'dateOfAttendance' => $report->date_of_attendance,
            'core'             => $this->flags($report->coreProgress, self::CORE),
            'technical'        => $this->flags($report->technicalProgress, self::TECHNICAL),
            'leadership'       => $this->flags($report->leaderShipProgress, self::LEADERSHIP),
            'ratings'          => $this->ratings($report),
            'remarks'          => $report->remarks,
        ];
    }

    /* =====================================================================
       LEARNING APPLICATION PLAN (LAP)
    ===================================================================== */

    private const FOUNDATION = [
        'delivering_service_excellence' => 'Delivering Service Excellence',
        'exemplifying_integrity'        => 'Exemplifying Integrity',
        'interpersonal_skills'          => 'Interpersonal Skills',
    ];

    private const LAP_TECHNICAL = [
        'planning_organizing'   => 'Planning and Organizing',
        'monitoring_evaluation' => 'Monitoring and Evaluation',
        'records_management'    => 'Records Management',
        'partnering_networking' => 'Partnering and Networking',
        'process_management'    => 'Process Management',
        'attention_detail'      => 'Attention to Detail', // singular sa LAP
    ];

    private const SUPERVISORY = [
        'supervisory_managing_performance_coaching_results'                  => 'Managing Performance and Coaching Results',
        'supervisory_building_collaborative_inclusive_working_relationships' => 'Building Collaborative and Inclusive Working Relationships',
    ];

    private const MANAGERIAL = [
        'managing_performance_coaching_results'                  => 'Managing Performance and Coaching Results',
        'building_collaborative_inclusive_working_relationships' => 'Building Collaborative and Inclusive Working Relationships',
        'thinking_strategically_creatively'                      => 'Thinking Strategically and Creatively',
        'problem_solving_decision_making'                        => 'Problem Solving and Decision Making',
    ];

    // TODO: palitan ng tunay na column names ang 5 ito
    private const STRATEGIES = [
        'immediate_application_skills' => 'Immediate Application of new skills acquired',
        'knowledge_sharing'     => 'Knowledge Sharing',
        'peer_coaching_collaboration'         => 'Peer Coaching and Collaboration',
        'develop_office_policies_guidelines'      => 'Develop office policies and guidelines',
        'create_pilot_project'         => 'Create a Pilot Project',
        'include_ipcr'          => 'Include in the IPCR',
    ];

    private const KPI = [
        'strategic_functions' => 'Strategic Functions',
        'core_functions'      => 'Core Functions',
        'support_functions'   => 'Support Functions',
    ];

    private const RESOURCES = [
        'digital_technologies' => 'Digital & Technologies',
        'physical_printed_resources'     => 'Physical & Printed Resources',
        'human_resources_organizational_support'      => 'Human Resources & Organizational Support',
        'financial_logistical_support' => 'Financial & Logistical Support',
        'policy_process_resources'       => 'Policy & Process Resources',
    ];

    private const BENEFICIARIES = [
        'employees_staff'      => 'Employees and Staff',
        'supervisors_managers' => 'Supervisors and Managers',
        'office_department'    => 'The Office/Department',
        'city_government_organization'      => 'The City Government/Organization',
        'clients_stakeholders_general_public'       => 'Clients, Stakeholders & the General Public',
    ];

    private const TARGET_DATES = [
        'within_2_weeks_after_training'  => 'Within 2 weeks after the training',
        'within_1_month_after_training'  => 'Within 1 month after the training',
        'within_2_months_after_training' => 'Within 2 months after the training',
        'within_3_months_after_training' => 'Within 3 months after the training',
    ];


    public function learningApplicationPlanData(int $submissionId): array
    {
        $submission = $this->approvedSubmission($submissionId);

        // palitan ang mga relation name kung iba sa model mo
        $plan = LearningApplicationPlanForm::with([
            'foundation',
            'technical',
            'supervisory',
            'managerial',
            'learningStrategies',
            'performanceIndicator',
            'resources',
            'beneficiaries',
            'targetCompletion',
        ])->where('employee_form_submission_id', $submission->getKey())->firstOrFail();

        $foundation  = $this->flags($this->rel($plan, 'foundation'), self::FOUNDATION);
        $technical   = $this->flags($this->rel($plan, 'technical'), self::LAP_TECHNICAL);
        $supervisory = $this->flags($this->rel($plan, 'supervisory'), self::SUPERVISORY);
        $managerial  = $this->flags($this->rel($plan, 'managerial'), self::MANAGERIAL);

        return [
            'office'        => $plan->office,
            'learner'       => $plan->learner,
            'title'         => $plan->title_of_intervention,
            'dateConducted' => $plan->date_conducted,
            'venue'         => $plan->venue,
            'categories'    => [
                'Foundation'  => in_array(true, $foundation, true),
                'Technical'   => in_array(true, $technical, true),
                'Supervisory' => in_array(true, $supervisory, true),
                'Managerial'  => in_array(true, $managerial, true),
            ],
            'foundation'    => $foundation,
            'technical'     => $technical,
            'supervisory'   => $supervisory,
            'managerial'    => $managerial,
            'insight'       => $plan->significant_learning_insight,
            'strategies'    => $this->flags($this->rel($plan, 'learningStrategies'), self::STRATEGIES),
            'kpi'           => $this->flags($this->rel($plan, 'performanceIndicator'), self::KPI),
            'resources'     => $this->flags($this->rel($plan, 'resources'), self::RESOURCES),
            'beneficiaries' => $this->flags($this->rel($plan, 'beneficiaries'), self::BENEFICIARIES),
            'targetDates'   => $this->flags($this->rel($plan, 'targetCompletion'), self::TARGET_DATES),
        ];
    }

    /* =====================================================================
   LEARNING IMPLEMENTATION REPORT (LIR)
===================================================================== */

    // column => label, sa order ng template
    private const LIR_CORE = [
        'delivering_service_excellence' => 'Delivery Service Excellence',
        'exemplifying_integrity'        => 'Exemplifying Integrity',
        'interpersonal_skills'          => 'Interpersonal Skills',
    ];

    private const LIR_TECHNICAL = [
        'planning_organizing'   => 'Planning & Organizing',
        'monitoring_evaluation' => 'Monitoring & Evaluation',
        'records_management'    => 'Records Management',
        'partnering_networking' => 'Partnering & Networking',
        'process_management'    => 'Process Management',
    ];

    private const LIR_LEADERSHIP = [
        'thinking_strategically_creatively'                      => 'Thinking Strategically and Creativity',
        'problem_solving_decision_making'                        => 'Problem Solving & Decision Making',
        'building_collaborative_inclusive_working_relationships' => 'Building Collaborative & Inclusive Working Relationships',
        'managing_performance_coaching_results'                  => 'Managing Performance & Coaching for Results',
    ];

    public function learningImplementationData(int $submissionId): array
    {
        $submission = $this->approvedSubmission($submissionId);

        // palitan ang model at relation names kung iba sa schema mo
        $report = LearningImplementationForm::with([
            'coreImplementation',
            'technicalImplementation',
            'learderShipImplementation',
        ])->where('employee_form_submission_id', $submission->getKey())->firstOrFail();

        return [
            'learner'               => $report->learner,
            'lndAttended'           => $report->lnd_attended,
            'dateOfAttendance'      => $report->date_of_attendance,
            'competencyDeveloped'   => $report->competency_developed,
            'core'                  => $this->flags($this->rel($report, 'coreImplementation'), self::LIR_CORE),
            'technical'             => $this->flags($this->rel($report, 'technicalImplementation'), self::LIR_TECHNICAL),
            'leadership'            => $this->flags($this->rel($report, 'learderShipImplementation'), self::LIR_LEADERSHIP),

            'strategies'            => $report->learning_strategies_applied,
            'resources'             => $report->resources_used,
            'beneficiaries'         => $report->beneficiaries_strategies_applied,
            'kpi'                   => $report->performance_indicators_behavior_toward_work,
            'financialAid'          => $report->financial_aid_training_attended,
            'returnOfFinancialAid'  => $report->return_financial_aid,
        ];
    }


    /* =====================================================================
   LEARNING APPLICATION MONITORING REPORT (LAMR)
===================================================================== */

    private const LAMR_CORE = [
        'delivering_service_excellence' => 'Delivery Service Excellence',
        'exemplifying_integrity'        => 'Exemplifying Integrity',
        'interpersonal_skills'          => 'Interpersonal Skills',
    ];

    private const LAMR_TECHNICAL = [
        'planning_organizing'   => 'Planning & Organizing',
        'monitoring_evaluation' => 'Monitoring & Evaluation',
        'records_management'    => 'Records Management',
        'partnering_networking' => 'Partnering & Networking',
        'process_management'    => 'Process Management',
    ];

    private const LAMR_LEADERSHIP = [
        'thinking_strategically_creatively'                      => 'Thinking Strategically and Creativity',
        'problem_solving_decision_making'                        => 'Problem Solving & Decision Making',
        'building_collaborative_inclusive_working_relationships' => 'Building Collaborative & Inclusive Working Relationships',
        'managing_performance_coaching_results'                  => 'Managing Performance & Coaching for Results',
    ];

    public function learningApplicationMonitoringData(int $submissionId): array
    {
        $submission = $this->approvedSubmission($submissionId);

        $report = LearningApplicationMonitoringForm::with([
            'coreMonitoring',
            'technicalMonitoring',
            'leaderShipMonitoring',
        ])->where('employee_form_submission_id', $submission->getKey())->firstOrFail();

        return [
            'learner'             => $report->learner,
            'lndAttended'         => $report->lnd_attended,
            'dateOfAttendance'    => $report->date_of_attendance,
            'competencyDeveloped' => $report->competency_developed_acquired,
            'core'                => $this->flags($this->rel($report, 'coreMonitoring'), self::LAMR_CORE),
            'technical'           => $this->flags($this->rel($report, 'technicalMonitoring'), self::LAMR_TECHNICAL),
            'leadership'          => $this->flags($this->rel($report, 'leaderShipMonitoring'), self::LAMR_LEADERSHIP),
            'goals'               => $report->goals,
            'kpi'                 => $report->performance_indicator,
            'strategies'          => $report->learning_strategies_applied,
            'resources'           => $report->required_resources,
            'targetDate'          => $report->target_date_completion,
            'statusV1'            => $report->status_as_of_v1,
            'statusV2'            => $report->status_as_of_v2,
            'remarks'             => $report->remarks,
        ];
    }

    /* =====================================================================
   RESOURCE SPEAKER EVALUATION
===================================================================== */

    // column => label, sa order ng form
    private const SPEAKER_FACTORS = [
        'mastery_of_subject'  => '1. Mastery of the subject matter',
        'ability_to_involve'  => '2. Ability to involve and interact with participants',
        'session_interest'    => '3. Ability to make sessions interesting',
        'handling_questions'  => "4. Ability to handle participants' questions",
        'voice_projection'    => '5. Voice projection',
        'communication'       => '6. Ability to communicate ideas clearly and effectively',
        'practical_relevance' => '7. Ability to relate principles and concepts to practical and on-the-job situations and issues',
        'over_all_rating'     => 'Overall Rating',
    ];

    private function scheduleSpeakerNames(EmployeeFormSubmission $submission): array
{
    $schedule = EventSchedule::with('scheduleDateTime.speakers')
        ->find($submission->event_schedule_id);

    if (! $schedule) {
        return [];
    }

    return $schedule->scheduleDateTime
        ->sortBy('id')
        ->flatMap(fn ($dateTime) => $dateTime->speakers)
        ->unique('speaker_id')
        ->pluck('speaker_name')
        ->values()
        ->all();
}

public function resourceSpeakerEvaluationData(int $submissionId): array
{
    $submission = $this->approvedSubmission($submissionId);

    $form = TrainingEvaluationSpeakers::with('speakerEvaluation')
        ->where('employee_form_submission_id', $submission->getKey())
        ->firstOrFail();

    // kung ilan ang speaker evaluation, yun lang ang lalabas
    $evals = $form->speakerEvaluation->sortBy('id')->values();
    $names = $this->scheduleSpeakerNames($submission);

    $rows = [];
    foreach (self::SPEAKER_FACTORS as $col => $label) {
        $rows[$label] = $evals
            ->map(fn ($eval) => ((int) $eval->{$col}) ?: null)
            ->all();
    }

    return [
        'event'       => $form->event,
        'date'        => $form->date,
        'venue'       => $form->venue,
        'provider'    => $form->learning_service_provider,
        'speakers'    => $evals->keys()->map(fn ($i) => $names[$i] ?? '')->all(),
        'rows'        => $rows,
        'comments'    => $evals->map(fn ($eval) => $eval->comments ?? '')->all(),
    ];
}

/* =====================================================================
   LEVEL I EVALUATION INSTRUMENT
===================================================================== */

// 4 = Strongly Agree ... 1 = Strongly Disagree
private const LEVEL1_SCALE = [
    4 => 'Strongly Agree',
    3 => 'Agree',
    2 => 'Disagree',
    1 => 'Strongly Disagree',
];

// boolean column => value, sa order ng scale
private const LEVEL1_COLUMNS = [
    'strongly_agree'    => 4,
    'agree'             => 3,
    'disagree'          => 2,
    'strongly_disagree' => 1,
];

// relation => [heading, sub] (ang mga tanong ay galing sa database)
private const LEVEL1_SECTIONS = [
    'topic'            => ['heading' => 'Topic',                             'sub' => null],
    'facilitator'      => ['heading' => 'Facilitators',                      'sub' => 'Personnel Development Committee/CHRMO-HRD'],
    'venue_evaluation' => ['heading' => 'Venue/Platform and Time/Duration', 'sub' => null],
];

// feedback column => label
private const LEVEL1_OVERALL = [
    'excellent'    => 'Excellent',
    'high_average' => 'High Average',
    'good'         => 'Good',
    'poor'         => 'Poor',
    'fair'         => 'Fair',
];
private const LEVEL1_VENUE_LABELS = [
    'Venue is conducive for learning',
    'Time allotted is appropriate for learning',
];

private const LEVEL1_RECOMMEND = [
    'highly_recommended' => 'Highly Recommended',
    'not_a_priority'     => 'Can be considered an option but not a Priority',
];

// isang row ng topic/facilitator/venue -> 4..1 (o null kung walang tinick)
private function level1Value(Model $row): ?int
{
    foreach (self::LEVEL1_COLUMNS as $col => $value) {
        if ($row->{$col}) {
            return $value;
        }
    }
    return null;
}

public function evaluationInstrumentData(int $submissionId): array
{
    $submission = $this->approvedSubmission($submissionId);

    $form = EvaluationInstrument::with(['topic', 'facilitator', 'venue_evaluation', 'feedback'])
        ->where('employee_form_submission_id', $submission->getKey())
        ->firstOrFail();

    $sections = [];
    foreach (self::LEVEL1_SECTIONS as $relation => $meta) {
        $items = [];
    foreach ($form->getRelation($relation)->sortBy('id')->values() as $i => $row) {
    $text = $row->question;

    // kung numero lang ang laman (hindi tanong), gamitin ang label ng form
    if ($relation === 'venue_evaluation' && (! is_string($text) || is_numeric($text))) {
        $text = self::LEVEL1_VENUE_LABELS[$i] ?? $text;
    }

    $items[] = [
        'text'  => $text,
        'value' => $this->level1Value($row),
    ];
}

        $sections[] = [
            'heading' => $meta['heading'],
            'sub'     => $meta['sub'],
            'items'   => $items,
        ];
    }

    // hasMany ang feedback pero isang row lang ang inaasahan
    $feedback = $form->getRelation('feedback')->first();

    return [
        'event'       => $form->event,
        'date'        => $form->date,
        'venue'       => $form->venue,
        'participant' => $form->name_of_participant,
        'scale'       => self::LEVEL1_SCALE,
        'sections'    => $sections,
        'overall'     => $this->flags($feedback, self::LEVEL1_OVERALL),
        'recommend'   => $this->flags($feedback, self::LEVEL1_RECOMMEND),
        'comments'    => $form->comments_suggestions,
    ];
}
}
