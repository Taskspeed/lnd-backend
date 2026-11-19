<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearningApplicationMonitoringFormRequest;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LAMR\LearningApplicationMonitoringForm;
use App\Services\EmployeeFileServices;
use App\Services\Forms\LearningApplicationMonitoringFormService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class EmployeeLearningApplicationMonitoringFormController extends Controller
{

    use ApiResponseTrait;

    protected LearningApplicationMonitoringFormService $learningApplicationMonitoringFormService;
    protected EmployeeFileServices $employeeFileService;

    public function __construct(
        LearningApplicationMonitoringFormService $learningApplicationMonitoringFormService,
        EmployeeFileServices $employeeFileService
    ) {
        $this->learningApplicationMonitoringFormService = $learningApplicationMonitoringFormService;
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
    public function store(LearningApplicationMonitoringFormRequest $request)
    {
        //

        $validated = $request->validated();
        try {
            $result = $this->learningApplicationMonitoringFormService->create($validated);

            return $this->successMessage($result, 'Submitted successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 409);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(int $learningMonitoringFormId)
    {

        $learnera_application_monitoring = LearningApplicationMonitoringForm::with(['coreMonitoring', 'leaderShipMonitoring', 'technicalMonitoring'])
            ->find($learningMonitoringFormId);

        if (!$learnera_application_monitoring) {
            return $this->errorMessage('Learning application monitoring report id not found', 400);
        }

        $status = EmployeeFormSubmission::find($learnera_application_monitoring->employee_form_submission_id);


        return $this->successMessage([
            'form' => $learnera_application_monitoring,
            'status' => $status,
        ], 'Success fetch', 200);
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
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }


    public function upload(Request $request, int $learningApplicationMonitoringFormId)
    {
        $validated = $request->validate([
            'image'             => 'required|image|mimes:jpg,jpeg,png|max:10240',
            'event_schedule_id' => 'required|integer',
        ]);

        try {
            $result = $this->learningApplicationMonitoringFormService->uploadImage(
                (int) $learningApplicationMonitoringFormId,   // <- i-cast dito
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

    public function viewImage(int $learningApplicationMonitoringFormId)
    {
        $form = LearningApplicationMonitoringForm::findOrFail($learningApplicationMonitoringFormId);
        $path = $form->getRawOriginal('image');

        abort_unless($path && $this->employeeFileService->exists($path), 404, 'File not found.');

        return Storage::disk('local')->response($path);
    }
}
