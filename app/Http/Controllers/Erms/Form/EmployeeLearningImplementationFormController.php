<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningImplementationFormRequest;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Models\Forms\LAP\LearningApplicationPlan;
use App\Models\Forms\LIR\LearningImplementationReport;
use App\Services\Forms\LearningImplementationFormService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeLearningImplementationFormController extends Controller
{


    use ApiResponseTrait;

    protected LearningImplementationFormService $LearningImplementationFormService;

    public function __construct(LearningImplementationFormService $LearningImplementationFormService)
    {
        $this->LearningImplementationFormService = $LearningImplementationFormService;
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
    public function store(LearningImplementationFormRequest $request)
    {
        //
        $validated = $request->validated();

        try {
            $result = $this->LearningImplementationFormService->create($validated);
            return $this->successMessage($result, 'success create', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $learningImplementationFormId)
    {
        $learning_implementation = LearningApplicationMonitoringForm::with([
            'coreImplementation',
            'coreImplementation',
            'learderShipImplementation',
            'technicalImplementation'
        ])->find($learningImplementationFormId);

        if (!$learning_implementation) {
            return $this->errorMessage('Learning implementation id not found', 400);
        }

        $status = EmployeeFormSubmission::find($learning_implementation->employee_form_submission_id);


        return $this->successMessage([
            'form' => $learning_implementation,
            'status' => $status,
        ], 'Success fetch', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearningImplementationFormRequest $request, int $learningImplementationId)
    {
        //
        $validated = $request->validated();

        try {
            $result =  $this->LearningImplementationFormService->edit($learningImplementationId, $validated);

            return $this->successMessage($result, 'success update', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $learningImplementationId)
    {

        try {

            $result = $this->LearningImplementationFormService->delete($learningImplementationId);
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }
}
