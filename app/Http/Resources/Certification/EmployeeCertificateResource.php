<?php

namespace App\Http\Resources\Certification;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class EmployeeCertificateResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'event_id'              => $this->event_id,
            'nominated_employee_id' => $this->id, // NominatedEmployee PK
            'certificate_issued'    => $this->certificate_issued,
            'title_name'            => $this->event?->title_name,
            'learning_intervention' => $this->event?->learning_intervention,
            'type_name' => $this->event?->schedule?->first()?->type_name,
    
        ];
    }
}
