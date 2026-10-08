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

       protected $casts  = [
        'evaluation_instrument_id' => 'integer',
        'excellent'=> 'boolean',
        'high_average'=> 'boolean',
        'good'=> 'boolean',
        'poor'=> 'boolean',
        'fair'=> 'boolean',
        'highly_recommended'=> 'boolean',
        'not_a_priority'=> 'boolean',
    ];

    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
