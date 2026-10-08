<?php

namespace App\Models\Documents;

use App\Models\Documents\EmployeeDocument;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    //

    protected $table = 'documents';

    protected $fillable = [
        'employee_document_id',
        'file_path',
        'title'

    ];

    protected $casts = [
        'employee_document_id' => 'integer',
    ];


    protected $hidden = [
        'created_at',
        'updated_at'
    ];


    protected $appends = ['file_url', 'file_type'];

    // URL ng API endpoint (hindi direct storage), para dumaan sa auth
    public function getFileUrlAttribute(): string
    {
        return url("/api/erms/document/file/{$this->id}");
    }

    public function getFileTypeAttribute(): string
    {
        return strtolower(pathinfo($this->file_path, PATHINFO_EXTENSION)) === 'pdf' ? 'pdf' : 'img';
    }

    public function employeeDocument()
    {
        return $this->belongsTo(EmployeeDocument::class, 'employee_document_id');
    }
}
