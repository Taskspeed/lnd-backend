<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\EvaluationInstrumentRequest;
use App\Services\Forms\EvaluationInstrumentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EmployeeEvalautionInstrumentController extends Controller
{

    use ApiResponseTrait;

    protected EvaluationInstrumentService $evaluationService;

    public function __construct(EvaluationInstrumentService $evaluationService)
    {
        $this->evaluationService = $evaluationService;
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
    public function store(EvaluationInstrumentRequest $request)
    {
        //

        $validated = $request->validated();

        $data = $this->evaluationService->create($validated);

        return $this->successMessage($data, 'Submitted successfully' ,200);


    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

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
