<?php

namespace App\Models\Forms\LIR;

use Illuminate\Database\Eloquent\Model;

class CoreImplementation extends Model
{
    //

    protected $table = 'core_implementation';

    protected $fillable = [
        'learning_implementation_report_id',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills'
    ];
}
