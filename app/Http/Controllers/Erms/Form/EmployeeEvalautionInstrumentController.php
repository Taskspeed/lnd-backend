<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\EvaluationInstrumentRequest;
use App\Models\Forms\LES\EvaluationInstrument;
use App\Services\EmployeeFileServices;
use App\Services\Forms\EvaluationInstrumentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeEvalautionInstrumentController extends Controller
{

    use ApiResponseTrait;

    protected EvaluationInstrumentService $evaluationService;
    protected EmployeeFileServices $employeeFileService;

    public function __construct(EvaluationInstrumentService $evaluationService, EmployeeFileServices $employeeFileService)
    {
        $this->evaluationService = $evaluationService;
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
    public function store(EvaluationInstrumentRequest $request)
    {
        //

        $validated = $request->validated();

        $data = $this->evaluationService->create($validated);

        return $this->successMessage($data, 'Submitted successfully', 200);
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


    public function upload(Request $request, string $evaluationInstrumentId)
    {
        $validated = $request->validate([
            'image'             => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'event_schedule_id' => 'required|integer',
        ]);

        try {
            $result = $this->evaluationService->uploadImage(
                (int) $evaluationInstrumentId,   // <- i-cast dito
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


    public function viewImage(int $evaluationInstrumentId)
    {
        $form = EvaluationInstrument::findOrFail($evaluationInstrumentId);
        $path = $form->getRawOriginal('image');

        abort_unless($path && $this->employeeFileService->exists($path), 404, 'File not found.');

        return Storage::disk('local')->response($path);
    }
}
