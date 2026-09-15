<?php

namespace App\Models\Forms\LAP;

use Illuminate\Database\Eloquent\Model;

class BeneficiariesStrategieApplied extends Model
{
    //

    protected $table = 'beneficiaries_strategie_applied';

    protected $fillable = [
        'learning_application_plan_form_id',
        'employees_staff',
        'office_department',
        'city_government_organization',
        'clients_stakeholders_general_public'

    ];
          protected $hidden = [ 'created_at', 'updated_at'];

          protected $casts = [
                    'learning_application_plan_form_id' => 'integer',
        'employees_staff'=> 'boolean',
        'office_department'=> 'boolean',
        'city_government_organization' => 'boolean',
        'clients_stakeholders_general_public' => 'boolean',
          ];
}
