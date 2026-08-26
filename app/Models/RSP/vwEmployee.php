<?php

namespace App\Models\RSP;


use App\Models\RSP\xTraining;
use Illuminate\Database\Eloquent\Model;

class vwEmployee extends Model
{
    //
    protected $connection = 'second_db';
    protected $table = 'vwEmployee'; 

    public function xTraining(){ return $this->hasMany(
            xTraining::class,
            'ControlNo', // foreign key on xTrainings table
            'ControlNo'  // local key on vwEmployee table
        );
    }
}
