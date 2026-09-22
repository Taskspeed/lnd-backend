<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;



    // ilang approved na form ang kailangan bago ma-release ang certificate
    private const REQUIRED_APPROVALS = 4;

    public function index(Request $request)
    {
        $list = NominatedEmployee::query()
            ->where('nominate_status', 'Approved')
            // only include nominees who actually have at least one form submission
            ->whereHas('formSubmissions', function ($q) use ($request) {
                $q->when(
                    $request->filled('event_schedule_id'),
                    fn($q2) => $q2->where('event_schedule_id', $request->event_schedule_id)
                );
            })
            ->with(['formSubmissions' => function ($q) use ($request) {
                $q->when(
                    $request->filled('event_schedule_id'),
                    fn($q2) => $q2->where('event_schedule_id', $request->event_schedule_id)
                );
            }])
            ->get()
            ->map(function ($nominee) {
                $submissions = $nominee->formSubmissions;

                $pending  = $submissions->where('status', 'Pending')->count();
                $approved = $submissions->where('status', 'Approved')->count();
                $returned = $submissions->where('status', 'Returned')->count();

                // control_no / event_schedule_id dapat magkapareho sa lahat ng
                // submissions ng isang nominee, kaya kunin na lang natin sa una
                $first = $submissions->first();

                return [
                    'control_no'         => $first->control_no,
                    'full_name'          => $nominee->full_name,
                    'event_schedule_id'  => (int) $first->event_schedule_id,
                    'pending'            => $pending,
                    'approved'           => $approved,
                    'returned'           => $returned,
                    'required_approvals' => self::REQUIRED_APPROVALS,
                    'certificate_status' => $approved >= self::REQUIRED_APPROVALS
                        ? 'Complete'
                        : 'Incomplete',
                ];
            })
            ->sortBy('control_no')
            ->values();

        return $this->successMessage($list, 'list of employee form submitted', 200);
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
