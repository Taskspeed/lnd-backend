<?php

namespace App\Http\Controllers\Erms\Form;

use App\Http\Controllers\Controller;
use App\Http\Requests\Form\LearnerProgressFormRequest;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Services\Forms\LearnerProgressFormService;
use App\Traits\ApiResponseTrait;


class EmployeeLearnerProgressFormController extends Controller
{

    use ApiResponseTrait;

    protected LearnerProgressFormService $learnProgressFormService;

    public function __construct(LearnerProgressFormService $learnProgressFormService)
    {
        $this->learnProgressFormService = $learnProgressFormService;
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
    public function store(LearnerProgressFormRequest $request)
    {
        $validated = $request->validated();

        try {
            $result = $this->learnProgressFormService->create($validated);

            return $this->successMessage($result, 'success created', 201);
        } catch (\Exception $e) {

            return $this->errorMessage($e->getMessage(), 409);
        }
    }

    /**
     * Display the specified resource.
     */
        public function show(int $learnerProgressFormId)
    {
        $learnerProgressForm = LearnerProgressForm::with(['coreProgress', 'leaderShipProgress', 'technicalProgress'])
            ->find($learnerProgressFormId);                                                                              

        if (!$learnerProgressForm) {
            return $this->errorMessage('Learner progress report form  not found', 400);
        }

        $status = EmployeeFormSubmission::find($learnerProgressForm->employee_form_submission_id);

        return $this->successMessage([
            'form' => $learnerProgressForm,
            'status' => $status,
        ], 'Success fetch', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(LearnerProgressFormRequest $request, int $LearnerProgressFormId)
    {
        $validated = $request->validated();

        try {
            $result =  $this->learnProgressFormService->edit($LearnerProgressFormId, $validated);

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
    public function destroy(int $LearnerProgressFormId)
    {
        try {
            $result = $this->learnProgressFormService->delete($LearnerProgressFormId);
            return $this->successMessage($result, 'success delete', 200);
        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return $this->errorMessage($e->getMessage(), 404);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 400);
        }
    }
}
