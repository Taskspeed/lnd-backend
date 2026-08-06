<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class FoundationCompetencie extends Model
{
    //
    protected $table = 'foundation_competencies';

    protected $fillable = [
        'learning_application_plan_form_id',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills'
    ];
}
