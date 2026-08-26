<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Event\EmployeeFormSubmission;
use App\Services\HR\Submission\EmployeeSubmissionService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeFormSubmissionController extends Controller
{

    use ApiResponseTrait;

    protected EmployeeSubmissionService $employeeSubmissionService;

    public function __construct(EmployeeSubmissionService $employeeSubmissionService)
    {
        $this->employeeSubmissionService = $employeeSubmissionService;
    }

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
    public function show(int $employeeFormSubmissionId)
    {
        //
        

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, int $employeeFormSubmissionId)
    {
        //
        $validated = $request->validate([
            'status' => 'required|in:Returned,Approved,Pending',
            'remarks' => 'nullable|string'
        ]);

        try {
            $result = $this->employeeSubmissionService->updateSubmission($validated,$employeeFormSubmissionId);

        return $this->successMessage($result,'success updated',200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(),404);
        }

      
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
