<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\TrainingEvaluationRequest;
use App\Models\Forms\RSE\TrainingEvaluationSpeakers;
use App\Services\Forms\TrainingEvaluationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeTrainingEvaluationController extends Controller
{

    use ApiResponseTrait;

    protected TrainingEvaluationService $trainingService;

    public function __construct(TrainingEvaluationService $trainingService)
    {
        $this->trainingService = $trainingService;
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
    public function store(TrainingEvaluationRequest $request)
    {
        //
        $validated = $request->validated();

        $data = $this->trainingService->create($validated);

        return $this->successMessage($data,'Submitted successfully', 200);
    }

    /**
     * Display the specified resource.
     */
    // public function show(int $trainingEvaluationSpeakerId)
    // {
    //     //
    //     $data = TrainingEvaluationSpeakers::with('speakerEvaluation')->where('id',$trainingEvaluationSpeakerId)->first();
    //      return $this->successMessage($data,'Submitted successfully', 200);
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
