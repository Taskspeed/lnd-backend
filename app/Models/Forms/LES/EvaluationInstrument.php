<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class EvaluationInstrument extends Model
{
    //

    protected $table = 'evaluation_instruments';

    protected $fillable = [
        'event',
        'date',
        'venue',
        'name_of_participant',
        'comments_suggestions',
    ];
}
