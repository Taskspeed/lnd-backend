<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningApplicationPlanRequest;
use App\Models\Forms\LAP\LearningApplicationPlan;
use App\Services\Forms\LearningApplicationPlanService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeLearningApplicationPlanController extends Controller
{

    use ApiResponseTrait;

    protected LearningApplicationPlanService $learningApplicationPlanService;

    public function __construct(LearningApplicationPlanService $learningApplicationPlanService)
    {
        $this->learningApplicationPlanService = $learningApplicationPlanService;
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
    public function store(LearningApplicationPlanRequest $request)
    {
        //
        $validated = $request->validated();

        $result = $this->learningApplicationPlanService->create($validated);

        return $this->successMessage($result, 'success create', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $eventId, string $formName, string $controlNo)
    {
        $learning_application_plan = LearningApplicationPlan::with([
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
    public function update(LearningApplicationPlanRequest $request, int $learningApplicationPlanId)
    {
        //
        $validated = $request->validated();

        try {
            $result =  $this->learningApplicationPlanService->edit($learningApplicationPlanId, $validated);

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

            $result = $this->learningApplicationPlanService->delete($learningApplicationPlanId);
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }
}
