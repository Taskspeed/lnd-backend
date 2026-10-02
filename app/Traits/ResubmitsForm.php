<?php

namespace App\Traits;

use App\Models\Event\EmployeeFormSubmission;

trait ResubmitsForm
{
    /**
     * Gumagawa ng bagong submission, o nire-reset sa Pending kung Returned.
     * Magte-throw kung Pending/Approved na (hindi na pwedeng baguhin).
     */
    protected function upsertSubmission($event, $event_schedule, array $validated): EmployeeFormSubmission
    {
        $key = [
            'event_id'          => $event->id,
            'event_schedule_id' => $event_schedule->id,
            'form_name'         => $this->formName(),
            'control_no'        => $validated['control_no'] ?? null,
        ];

        $existing = EmployeeFormSubmission::where($key)->lockForUpdate()->first();

        if ($existing && strtolower($existing->status) !== 'returned') {
            throw new \Exception('This form has already been submitted and cannot be modified.');
        }

        return EmployeeFormSubmission::updateOrCreate($key, [
            'status'       => 'Pending',
            'submitted_at' => now(),
            // 'remarks'   => null, // optional: i-clear ang remarks ng reviewer
        ]);
    }
}