<?php

namespace App\Models\RSP;

use Illuminate\Database\Eloquent\Model;

class xCivilService extends Model
{
    //

      protected $connection = 'second_db';

    protected $table = 'xCivilService'; 

    protected $casts = [
      // 'Dates' =>'date:F d, Y'
    ];

}
