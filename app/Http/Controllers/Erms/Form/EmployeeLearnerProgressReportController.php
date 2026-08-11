<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearnerProgressReportRequest;
use App\Models\Forms\LPR\LearnerProgressReport;
use App\Services\Forms\LearnerProgressReportService;
use App\Traits\ApiResponseTrait;


class EmployeeLearnerProgressReportController extends Controller
{

    use ApiResponseTrait;

    protected LearnerProgressReportService $learnProgressReportService;

    public function __construct(LearnerProgressReportService $learnProgressReportService)
    {
        $this->learnProgressReportService = $learnProgressReportService;
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
    public function store(LearnerProgressReportRequest $request)
    {
        $validated = $request->validated();

        try {
            $result = $this->learnProgressReportService->create($validated);

            return $this->successMessage($result, 'success created', 201);
        } catch (\Exception $e) {

            return $this->errorMessage($e->getMessage(), 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $eventId, string $formName, string $controlNo)
    {

        $learnerProgressReport = LearnerProgressReport::with(['coreProgress', 'leaderShipProgress', 'technicalProgress'])
            ->where('event_id', $eventId)
            ->where('form_name', $formName)
            ->where('control_no', $controlNo)->first();

        if (!$learnerProgressReport) {
            return $this->errorMessage('Learner progrees report id not found', 400);
        }

        return $this->successMessage($learnerProgressReport, 'success fetch');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearnerProgressReportRequest $request, int $learnerProgressReportId)
    {
        $validated = $request->validated();

        try {
            $result =  $this->learnProgressReportService->edit($learnerProgressReportId, $validated);

            return $this->successMessage($result, 'success update', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorMessage($e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $learnerProgressReportId)
    {
        try {
            $result = $this->learnProgressReportService->delete($learnerProgressReportId);
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorMessage($e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }
}
