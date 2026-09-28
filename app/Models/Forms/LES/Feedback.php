<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class Feedback extends Model
{
    //

    protected $table = 'feedbacks';

    protected $fillable = [
        'evaluation_instrument_id',
        'excellent',
        'high_average',
        'good',
        'poor',
        'fair',
        'highly_recommended',
        'not_a_priority',
    ];
}
