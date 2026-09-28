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
}
