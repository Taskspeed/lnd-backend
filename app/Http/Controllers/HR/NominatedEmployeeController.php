<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\RSP\yOffice;
use App\Notifications\NominationDisapproved;
use App\Notifications\NominationStatusUpdated;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class NominatedEmployeeController extends Controller
{

    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        public function show(int $nominatedEmployeeId)
        {
            $employee = NominatedEmployee::find($nominatedEmployeeId);

            if (!$employee) {
                return $this->errorMessage('Employee are not Nominated', 404);
            }

            $officeAbbr = yOffice::select('Descriptions', 'Abbr')
                ->where('Descriptions', $employee->office)
                ->first();

            // i-attach ang abbr sa response — dagdag na field lang, hindi binabago ang model mismo
            $data = $employee->toArray();
            $data['office_abbr'] = $officeAbbr->Abbr ?? null;

            return $this->successMessage($data, 'Success', 200);
        }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $nominatedEmployeeId)
    {
        $validated = $request->validate([
            'nominate_status' => 'required|string|in:Approved,Disapproved',
        ]);

        $employee = NominatedEmployee::find($nominatedEmployeeId);

        if (!$employee) {
            return $this->errorMessage('Employee not found', 404);
        }

        $employee->update([
            'nominate_status' => $validated['nominate_status'],
        ]);

        // Palaging i-notify ang office admin(s) ng office ng employee
        $officeAdmins = \App\Models\User::where('office', $employee->office)
            ->role('office_admin')
            ->get();

        if ($officeAdmins->isNotEmpty()) {
            foreach ($officeAdmins as $admin) {
                $admin->notify(new NominationStatusUpdated($employee));
            }
        } else {
            Log::info("No office admin found for office \"{$employee->office}\" — status update notification for {$employee->full_name} (control_no {$employee->control_no}) skipped.");
        }

        return $this->successMessage($employee, 'Success', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
