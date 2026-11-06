<?php

namespace App\Models\Bpm;

use Illuminate\Database\Eloquent\Model;

class BpmDates extends Model
{
    //

    protected $table  = 'bpm_dates';

    protected $fillable = [

        'month',
        'year',
        'week',
        'office'

    ];

    protected $appends = ['bpm_date_id'];
    protected $hidden = ['id', 'updated_at'];
    protected $casts = [
        'bpm_date_id' => 'integer',
        'week' => 'integer',
        'created_at' => 'date:F j, Y '
    ];



    public function getBpmDateIdAttribute()
    {
        return $this->id;
    }
}
