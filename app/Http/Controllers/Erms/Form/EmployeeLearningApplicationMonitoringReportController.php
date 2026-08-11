<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningApplicationMonitoringRequest;
use App\Models\Forms\LAMR\LearningApplicationMonitoringReport;
use App\Services\Forms\LearningApplicationMonitoringService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeLearningApplicationMonitoringReportController extends Controller
{

    use ApiResponseTrait;

    protected LearningApplicationMonitoringService $learningApplicationMonitoringService;

    public function __construct(LearningApplicationMonitoringService $learningApplicationMonitoringService)
    {
        $this->learningApplicationMonitoringService = $learningApplicationMonitoringService;
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
    public function store(LearningApplicationMonitoringRequest $request)
    {
        //

        $validated = $request->validated();
        try {
            $result = $this->learningApplicationMonitoringService->create($validated);

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

        $learnera_application_monitoring = LearningApplicationMonitoringReport::with(['coreMonitoring', 'leaderShipMonitoring', 'technicalMonitoring'])
            ->where('event_id', $eventId)
            ->where('forms_name', $formName)
            ->where('control_no', $controlNo)->first();

        if (!$learnera_application_monitoring) {
            return $this->errorMessage('Learning application monitoring report id not found', 400);
        }

        return $this->successMessage($learnera_application_monitoring, 'success fetch');
    }

    /**
     * Update the specified resource in storage.
     */
     public function update(LearningApplicationMonitoringRequest $request, int $learningApplicationMonitoringId)
    {
        $validated = $request->validated();

        try {
            $result =  $this->learningApplicationMonitoringService->edit($learningApplicationMonitoringId, $validated);

            return $this->successMessage($result, 'success update', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $learningApplicationMonitoringId)
    {

        try {
            
            $result = $this->learningApplicationMonitoringService->delete($learningApplicationMonitoringId);
            return $this->successMessage($result,'success delete',200);
        
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(),400);
        }
    }
}
