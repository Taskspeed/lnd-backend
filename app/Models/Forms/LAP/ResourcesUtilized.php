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
          protected $hidden = [ 'created_at', 'updated_at'];

          protected $casts = [
              'learning_application_plan_form_id' => 'integer',
        'digital_technologies' => 'boolean',
        'physical_printed_resources'=> 'boolean',
        'human_resources_organizational_support'=> 'boolean',
        'financial_logistical_support'=> 'boolean',
        'policy_process_resources'=> 'boolean',
          ];
}
