<?php

namespace App\Models\Event\Assessment;

use Illuminate\Database\Eloquent\Model;

class AssessmentQuestion extends Model
{
    //

    protected $table = 'assessment_questions';

    protected $fillable = [
        'assessment_id',
        'question',
        'question_type',
        'points',
        'sort_order',


    ];

    protected $casts = [
        'points' => 'integer',
        'assessment_id' => 'integer',
        'sort_order' => 'integer'

    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    public function options() { return $this->hasMany(AssessmentOption::class); }

}
