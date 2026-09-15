<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class LearningStrategiesImplemented extends Model
{
    //

    protected $table = 'learning_strategies_implemented';

    protected $fillable = [
        'learning_application_plan_form_id',
        'immediate_application_skills',
        'knowledge_sharing',
        'peer_coaching_collaboration',
        'develop_office_policies_guidelines',
        'create_pilot_project',
        'include_ipcr'

    ];
          protected $hidden = [ 'created_at', 'updated_at'];

          protected $casts = [
               'learning_application_plan_form_id' => 'integer',
        'immediate_application_skills'=> 'boolean',
        'knowledge_sharing'=> 'boolean',
        'peer_coaching_collaboration'=> 'boolean',
        'develop_office_policies_guidelines'=> 'boolean',
        'create_pilot_project'=> 'boolean',
        'include_ipcr'=> 'boolean',
          ];
}
