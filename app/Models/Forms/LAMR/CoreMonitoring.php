<?php

namespace App\Models\Forms\LAMR;

use Illuminate\Database\Eloquent\Model;

class CoreMonitoring extends Model
{
    //

    protected $table = 'core_monitoring';

    protected $fillable = [
        'learning_application_monitoring_report_id',
        'delivering_service_excellence',
        'exemplifying_integrity',
        'interpersonal_skills'
    ];
}
