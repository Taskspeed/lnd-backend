<?php

namespace App\Models\Bpm;

use Illuminate\Database\Eloquent\Model;

class Medical extends Model
{
    //

    protected $table = 'medicals';

    protected $fillable = [
        'controlNo',
        'medical_history'
    ];
}
