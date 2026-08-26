<?php

namespace App\Models\RSP;


use Illuminate\Database\Eloquent\Model;

class xTraining extends Model
{
    //
    
    protected $connection = 'second_db';

    protected $table = 'xTrainings'; 


    protected $casts = [
        // 'Dates' =>  'date:F d, Y'
    ];
}
