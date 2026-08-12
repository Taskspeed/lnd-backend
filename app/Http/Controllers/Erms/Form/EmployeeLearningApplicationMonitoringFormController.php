<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningApplicationMonitoringFormRequest;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Services\Forms\LearningApplicationMonitoringFormService;
use App\Traits\ApiResponseTrait;


class EmployeeLearningApplicationMonitoringFormController extends Controller
{

    use ApiResponseTrait;

    protected LearningApplicationMonitoringFormService $learningApplicationMonitoringFormService;

    public function __construct(LearningApplicationMonitoringFormService $learningApplicationMonitoringFormService)
    {
        $this->learningApplicationMonitoringFormService = $learningApplicationMonitoringFormService;
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
    public function store(LearningApplicationMonitoringFormRequest $request)
    {
        //

        $validated = $request->validated();
        try {
            $result = $this->learningApplicationMonitoringFormService->create($validated);

            return $this->successMessage($result, 'success create', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 409);
        }
    }

    /**
     * Display the specified resource.
     */
     public function show(int $eventId, string $formName, string $controlNo)
    {

        $learnera_application_monitoring = LearningApplicationMonitoringForm::with(['coreMonitoring', 'leaderShipMonitoring', 'technicalMonitoring'])
            ->where('event_id', $eventId)
            ->where('form_name', $formName)
            ->where('control_no', $controlNo)->first();

        if (!$learnera_application_monitoring) {
            return $this->errorMessage('Learning application monitoring report id not found', 400);
        }

        return $this->successMessage($learnera_application_monitoring, 'success fetch');
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(LearningApplicationMonitoringFormRequest $request, int $learningApplicationMonitoringFormId)
    {
        $validated = $request->validated();

        try {
            $result =  $this->learningApplicationMonitoringFormService->edit($learningApplicationMonitoringFormId, $validated);

            return $this->successMessage($result, 'success update', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $learningApplicationMonitoringFormId)
    {

        try {
            
            $result = $this->learningApplicationMonitoringFormService->delete($learningApplicationMonitoringFormId);
            return $this->successMessage($result,'success delete',200);
        
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(),400);
        }
    }
}
