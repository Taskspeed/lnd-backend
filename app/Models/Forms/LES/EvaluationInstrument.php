<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class EvaluationInstrument extends Model
{
    //

    protected $table = 'evaluation_instruments';

    protected $fillable = [
    'employee_form_submission_id',
        'form_name',
        'control_no',
        'event',
        'date',
        'venue',
        'name_of_participant',
        'comments_suggestions',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    public function facilitator()
    {
        return $this->hasMany(Facilitator::class);
    }

    public function topic()
    {
        return $this->hasMany(Topic::class);
    }

    public function feedback()
    {
        return $this->hasMany(Feedback::class);
    }

      public function venue_evaluation()
    {
        return $this->hasMany(Venue::class);
    }
}
