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


    protected $appends = ['leadership_progress_id'];
    protected $hidden = ['id', 'created_at', 'updated_at'];

    public function getLeaderShipProgressIdAttribute()
    {
        return $this->id;
    }

    protected $casts = [
        'learner_progress_report_id' => 'integer',
        'managing_performance_coaching_results' => 'boolean',
        'building_collaborative_inclusive_working_relationships' => 'boolean',
        'thinking_strategically_creatively' => 'boolean',
        'problem_solving_decision_making'  => 'boolean',
    ];
}
