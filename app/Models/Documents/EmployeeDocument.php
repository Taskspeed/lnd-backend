<?php

namespace App\Models\Documents;

use App\Models\Documents\Document;
use Illuminate\Database\Eloquent\Model;

class EmployeeDocument extends Model
{
    //

    protected $table = 'employee_documents';

    protected $fillable = [
        'event_id',
        'event_schedule_id',
        'control_no',
        'remarks',
        'status'
    ];

    protected $casts = [
        'event_id' => 'integer',
        'event_schedule_id' => 'integer',
    ];

    protected $hidden = [
        'created_at',
        'updated_at'
    ];
    
    public function documents()
    {
        return $this->hasMany(Document::class);
    }
}
