<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class ResourcesUtilized extends Model
{
    //

    protected $table = 'resources_utilized';

    protected $fillable = [
        'learning_application_plan_form_id',
        'digital_technologies',
        'physical_printed_resources',
        'human_resources_organizational_support',
        'financial_logistical_support',
        'policy_process_resources'

    ];
}
