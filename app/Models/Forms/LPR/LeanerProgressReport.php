<?php

namespace App\Models\Forms\LPR;

use Illuminate\Database\Eloquent\Model;

class LeanerProgressReport extends Model
{
    //
    protected $table = 'learner_progress_reports';

    protected $fillable = [
        'event_id',
        'forms_name',
        'control_no',
        'learner',
        'lnd_attended',
        'date_of_attendance',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills',
        'planning_organizing',
        'records_management',
        'partnering_networking',
        'process_management',
        'managing_performance_coaching_results',
        'building_collaborative_inclusive_working_relationships',
        'thinking_strategically_creatively',
        'problem_solving_decision_making',
        'remarks'
    ];
}
