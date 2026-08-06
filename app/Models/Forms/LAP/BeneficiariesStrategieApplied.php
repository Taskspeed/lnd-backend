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
}
