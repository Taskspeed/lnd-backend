<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningApplicationPlanFormRequest;
use App\Models\Forms\LAP\LearningApplicationPlanForm;
use App\Services\Forms\LearningApplicationPlanFormService;
use App\Traits\ApiResponseTrait;


class EmployeeLearningApplicationPlanFormController extends Controller
{

    use ApiResponseTrait;

    protected LearningApplicationPlanFormService $LearningApplicationPlanFormService;

    public function __construct(LearningApplicationPlanFormService $LearningApplicationPlanFormService)
    {
        $this->LearningApplicationPlanFormService = $LearningApplicationPlanFormService;
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

        return $this->successMessage($result, 'success create', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $eventId, string $formName, string $controlNo)
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
        ])
            ->where('event_id', $eventId)
            ->where('form_name', $formName)
            ->where('control_no', $controlNo)->first();

        if (!$learning_application_plan) {
            return $this->errorMessage('Learning application plan id not found', 400);
        }

        return $this->successMessage($learning_application_plan, 'success fetch');
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
}
