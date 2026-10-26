<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class EventAssessment extends Model
{
    //

    protected $table = 'event_assessments';

    protected $fillable = [
        'event_schedule_id',
        'pre_test_assessment',
        'post_test_assessment'
    ];


 protected $appends = ['event_assessment_id',];
    public function getEventAssessmentIdAttribute()
    {
        return $this->id;
    }

    protected $casts = [
        'event_schedule_id' => 'integer'
    ];

    protected $hidden = [
        'created_at',
        'updated_at',
        'id'

    ];
}
