<?php

namespace App\Models\Forms\LPR;

use Illuminate\Database\Eloquent\Model;

class LeadershipProgress extends Model
{
    //

    protected $table = 'leadership_progress';

    protected $fillable = [
        'learner_progress_report_id',
        'managing_performance_coaching_results',
        'building_collaborative_inclusive_working_relationships',
        'thinking_strategically_creatively',
        'problem_solving_decision_making'
    ];
}
