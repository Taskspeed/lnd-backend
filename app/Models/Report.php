<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    //

    protected $table = 'reports';


    protected $fillable = [
        'report_name'
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
}
