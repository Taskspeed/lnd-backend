<?php

namespace App\Models\Event;

use Illuminate\Database\Eloquent\Model;

class Form extends Model
{
    //
     protected $table = 'forms';


    protected $fillable = [
        'form_name'
    ];
    protected $appends = ['formId'];
    protected $hidden = ['id'];

    public function getFormIdAttribute()
    {
        return $this->id;
    }
}
