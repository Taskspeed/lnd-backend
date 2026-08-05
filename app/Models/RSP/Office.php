<?php

namespace App\Models\RSP;

use Illuminate\Database\Eloquent\Model;

class Office extends Model
{
    //
    protected $connection = 'second_db';
    protected $table = 'offices';
    
    protected $appends = ['officeId'];
    protected $hidden = ['id'];

    public function getOfficeIdAttribute()
    {
        return $this->id;
    }
}
