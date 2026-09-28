<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    //

    protected $table = 'venues';

    protected $fillable = [
        'evaluation_instrument_id',
        'question',
        'strongly_agree',
        'agree',
        'disagree',
        'strongly_disagree',
    ];
}
