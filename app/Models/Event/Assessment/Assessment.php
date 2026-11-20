<?php

namespace App\Models\Event\Assessment;

use Illuminate\Database\Eloquent\Model;

class Assessment extends Model
{
    //

    protected $table = 'assessments';

    protected $fillable = [
        'event_schedule_id',
        'description',
        'title',
        'passing_score'


    ];

       protected $hidden = [
        'created_at',
        'updated_at',
        'id'
    ];

    
    protected $appends = ['assessmentId'];
    protected $casts = [
        'event_schedule_id' => 'integer',
        'passing_score' => 'integer',
    ];

    public function getassessmentIdAttribute()
    {
        return $this->id;
    }

    public function questions() { return $this->hasMany(AssessmentQuestion::class); }

}
