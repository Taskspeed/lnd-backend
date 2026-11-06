<?php

namespace App\Models\Bpm;

use Illuminate\Database\Eloquent\Model;

class Bpm extends Model
{
    //
    protected $table = 'bpms';

    protected $fillable = [
        'controlNo',
        'systolic_pressure',
        'diastolic_pressure',
        'bpm_date_id'
    ];
}
