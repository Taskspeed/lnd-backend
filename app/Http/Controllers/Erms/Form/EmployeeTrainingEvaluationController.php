<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\TrainingEvaluationRequest;
use App\Models\Forms\RSE\TrainingEvaluationSpeakers;
use App\Services\EmployeeFileServices;
use App\Services\Forms\TrainingEvaluationService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EmployeeTrainingEvaluationController extends Controller
{

    use ApiResponseTrait;

    protected TrainingEvaluationService $trainingService;
    protected EmployeeFileServices $employeeFileService;

    public function __construct(TrainingEvaluationService $trainingService, EmployeeFileServices $employeeFileService)
    {
        $this->trainingService = $trainingService;
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
    public function store(TrainingEvaluationRequest $request)
    {
        //
        $validated = $request->validated();

        $data = $this->trainingService->create($validated);

        return $this->successMessage($data, 'Submitted successfully', 200);
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

    public function upload(Request $request, string $trainingEvaluationSpeakerId)
    {
        $validated = $request->validate([
            'image'             => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'event_schedule_id' => 'required|integer',
        ]);

        try {
            $result = $this->trainingService->uploadImage(
                (int) $trainingEvaluationSpeakerId,   // <- i-cast dito
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

    public function viewImage(int $trainingEvaluationSpeakerId)
    {
        $form = TrainingEvaluationSpeakers::findOrFail($trainingEvaluationSpeakerId);
        $path = $form->getRawOriginal('image');

        abort_unless($path && $this->employeeFileService->exists($path), 404, 'File not found.');

        return Storage::disk('local')->response($path);
    }
}
