<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningImplementationReportRequest;
use App\Models\Forms\LAP\LearningApplicationPlan;
use App\Models\Forms\LIR\LearningImplementationReport;
use App\Services\Forms\LearningImplementationReportService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeLearningImplementationReportController extends Controller
{


    use ApiResponseTrait;

    protected LearningImplementationReportService $LearningImplementationReportService;

    public function __construct(LearningImplementationReportService $LearningImplementationReportService)
    {
        $this->LearningImplementationReportService = $LearningImplementationReportService;
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
    public function store(LearningImplementationReportRequest $request)
    {
        //
        $validated = $request->validated();

        try {
            $result = $this->LearningImplementationReportService->create($validated);
            return $this->successMessage($result, 'success create', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $eventId, string $formName, string $controlNo)
    {
        $learning_implementation = LearningImplementationReport::with([
            'coreImplementation',
            'coreImplementation',
            'learderShipImplementation'
        ])
            ->where('event_id', $eventId)
            ->where('form_name', $formName)
            ->where('control_no', $controlNo)->first();

        if (!$learning_implementation) {
            return $this->errorMessage('Learning implementation id not found', 400);
        }

        return $this->successMessage($learning_implementation, 'success fetch');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearningImplementationReport $request, int $learningImplementationId)
    {
        //
        $validated = $request->validated();

        try {
            $result =  $this->LearningImplementationReportService->edit($learningImplementationId, $validated);

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

            $result = $this->LearningImplementationReportService->delete($learningImplementationId);
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }
}
