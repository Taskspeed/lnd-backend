<?php

namespace App\Models\Employee;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    //

    protected $table = 'certificates';

    protected $fillable = [
        'certificate_path',
        'title',
        'control_no',
        'hours',
        'conducted_by',
        'activity'
    ];

    protected $casts = [
        'created_at' => 'date:F d, Y'
    ];

    protected $hidden = [
        'updated_at',
        'id',
    ];


    public function getCertificateIdAttribute()
    {
        return $this->id;
    }

    
    protected $appends = ['file_url', 'file_type','certificate_id'];

    // URL ng API endpoint (hindi direct storage), para dumaan sa auth
    public function getFileUrlAttribute(): string
    {
        return url("/api/erms/certificate/file/{$this->id}");
    }

    public function getFileTypeAttribute(): string
    {
        return strtolower(pathinfo($this->certificate_path, PATHINFO_EXTENSION)) === 'pdf' ? 'pdf' : 'img';
    }


}
