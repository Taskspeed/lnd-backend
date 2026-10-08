<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class Topic extends Model
{
    //

    protected $table = 'topics';

    protected $fillable = [
        'evaluation_instrument_id',
        'question',
        'strongly_agree',
        'agree',
        'disagree',
        'strongly_disagree',
    ];

        protected $casts = [
        'strongly_agree' => 'boolean',
        'agree'=> 'boolean',
        'disagree'=> 'boolean',
        'strongly_disagree'=> 'boolean',
        'evaluation_instrument_id' => 'integer',
    ];

    
    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
