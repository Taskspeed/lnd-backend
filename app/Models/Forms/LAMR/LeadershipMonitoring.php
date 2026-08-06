<?php

namespace App\Models\Forms\LAMR;

use Illuminate\Database\Eloquent\Model;

class LeadershipMonitoring extends Model
{
    //

    protected $table = 'leadership_monitoring';

    protected $fillable = [
        'learning_application_monitoring_report_id',
        'managing_performance_coaching_results',
        'building_collaborative_inclusive_working_relationships',
        'thinking_strategically_creatively',
        'problem_solving_decision_making'
    ];
}
