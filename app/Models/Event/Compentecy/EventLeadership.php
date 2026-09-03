<?php

namespace App\Models\Event\Compentecy;

use Illuminate\Database\Eloquent\Model;

class EventLeadership extends Model
{
    //

      protected $table = 'event_leadership_competencies';

  protected $fillable = [
        'event_schedule_id',
        'managing_performance_coaching_results',
        'building_collaborative_inclusive_working_relationships',
        'thinking_strategically_creatively',
        'problem_solving_decision_making'
    ];
}
