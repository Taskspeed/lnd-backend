<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearnerProgressReportRequest;
use App\Models\Forms\LPR\CoreProgress;
use App\Models\Forms\LPR\LeanerProgressReport;
use App\Models\Forms\LPR\LearnerProgressReport;
use App\Services\Forms\LearnerProgressReportService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

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

      
        $result = $this->learnProgressReportService->create($validated);

        return $this->successMessage($result, 'success created', 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(int $eventId, string $formName,string $controlNo)
    {
        
        $learnerProgressReportId = LearnerProgressReport::with(['coreProgress','leaderShipProgress','technicalProgress'])
        ->where('event_id',$eventId)
        ->where('forms_name',$formName)
        ->where('control_no',$controlNo)->first();

        return $this->successMessage($learnerProgressReportId,'success fetch');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearnerProgressReportRequest $request, int $learnerProgressReportId)
    {
        $validated = $request->validated();

        $result =  $this->learnProgressReportService->edit($learnerProgressReportId,$validated);

     return $this->successMessage($result, 'success update', 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $learnerProgressReportId)
    {
        //

    $result =  $this->learnProgressReportService->delete($learnerProgressReportId);

         
     return $this->successMessage($result, 'success delete', 200);
    }
}
