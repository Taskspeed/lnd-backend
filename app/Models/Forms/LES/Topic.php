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
}
