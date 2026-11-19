<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningApplicationPlanFormRequest;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Services\EmployeeFileServices;
use App\Services\Forms\LearningApplicationPlanFormService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EmployeeLearningApplicationPlanFormController extends Controller
{

    use ApiResponseTrait;

    protected LearningApplicationPlanFormService $LearningApplicationPlanFormService;
    protected EmployeeFileServices $employeeFileService;

    public function __construct(LearningApplicationPlanFormService $LearningApplicationPlanFormService, EmployeeFileServices $employeeFileService)
    {
        $this->LearningApplicationPlanFormService = $LearningApplicationPlanFormService;
        $this->employeeFileService = $employeeFileService;
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
    public function store(LearningApplicationPlanFormRequest $request)
    {
        //
        $validated = $request->validated();

        $result = $this->LearningApplicationPlanFormService->create($validated);

        return $this->successMessage($result, 'Submitted successfully', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $learningApplicationPlanFormId)
    {
        $learning_application_plan = LearningApplicationPlanForm::with([
            'foundation',
            'technical',
            'managerial',
            'supervisory',
            'learningStrategies',
            'performanceIndicator',
            'beneficiaries',
            'resources',
            'targetCompletion',
        ])->find($learningApplicationPlanFormId);

        if (!$learning_application_plan) {
            return $this->errorMessage('Learning application plan id not found', 400);
        }

        $status = EmployeeFormSubmission::find($learning_application_plan->employee_form_submission_id);


        return $this->successMessage([
            'form' => $learning_application_plan,
            'status' => $status,
        ], 'Success fetch', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearningApplicationPlanFormRequest $request, int $learningApplicationPlanId)
    {
        //
        $validated = $request->validated();

        try {
            $result =  $this->LearningApplicationPlanFormService->edit($learningApplicationPlanId, $validated);

            return $this->successMessage($result, 'success update', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $learningApplicationPlanId)
    {

        try {

            $result = $this->LearningApplicationPlanFormService->delete($learningApplicationPlanId);
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    public function upload(Request $request, int $learningApplicationPlanId)
    {
        $validated = $request->validate([
            'image'             => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'event_schedule_id' => 'required|integer',
        ]);

        try {
            $result = $this->LearningApplicationPlanFormService->uploadImage(
                (int) $learningApplicationPlanId,   // <- i-cast dito
                $validated,
                $this->employeeFileService
            );

            return $this->successMessage($result, 'Successfully uploaded image.', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorMessage('Form record not found.', 404);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }


    public function viewImage(int $learningApplicationPlanId)
    {
        $form = LearningApplicationPlanForm::findOrFail($learningApplicationPlanId);
        $path = $form->getRawOriginal('image');

        abort_unless($path && $this->employeeFileService->exists($path), 404, 'File not found.');

        return Storage::disk('local')->response($path);
    }
}
