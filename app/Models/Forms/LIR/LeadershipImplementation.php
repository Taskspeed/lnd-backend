<?php

namespace App\Models\Forms\LIR;

use Illuminate\Database\Eloquent\Model;

class LeadershipImplementation extends Model
{
    //
    protected $table = 'leadership_implementation';

    protected $fillable = [
        'learning_implementation_report_id',
        'managing_performance_coaching_results',
        'building_collaborative_inclusive_working_relationships',
        'thinking_strategically_creatively',
        'problem_solving_decision_making',
    ];
}
