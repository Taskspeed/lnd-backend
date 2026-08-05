<?php

namespace App\Models\RSP;

use Illuminate\Database\Eloquent\Model;

class vwEmployee extends Model
{
    //
    protected $connection = 'second_db';
    protected $table = 'vwEmployee'; 
}
