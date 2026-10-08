<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class Facilitator extends Model
{
    //

    protected $table = 'facilitators';

    protected $fillable = [
        'evaluation_instrument_id',
        'question',
        'strongly_agree',
        'agree',
        'disagree',
        'strongly_disagree',
    ];
    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'strongly_agree' => 'boolean',
        'agree'=> 'boolean',
        'disagree'=> 'boolean',
        'strongly_disagree'=> 'boolean',
        'evaluation_instrument_id' => 'integer'
    ];
}
