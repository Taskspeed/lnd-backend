<?php

namespace App\Http\Controllers\Print;

use App\Http\Controllers\Controller;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Forms\LPR\LearnerProgressForm;
use App\Services\Forms\Print\FormService;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Database\Eloquent\Model;

class FormPdfController extends Controller
{
    //

    protected FormService $formService;

    public function __construct(FormService $formService)
    {
        $this->formService = $formService;
    }

    // Controller
    public function learnerProgress(int $submissionId)
    {
        $data = $this->formService->learnerProgressData($submissionId);

        return Pdf::loadView('forms.print.pdf.learner-progress-report', $data)
            ->setPaper('folio', 'portrait')
            ->stream('learner-progress-report.pdf');
    }

    public function learningApplicationPlan(int $submissionId)
    {
        $data = $this->formService->learningApplicationPlanData($submissionId);

        return Pdf::loadView('forms.print.pdf.learning-application-plan', $data)
            ->setPaper('folio', 'portrait')
            ->stream('learning-application-plan.pdf');
    }

    public function learningImplementation(int $submissionId)
    {
        $data = $this->formService->learningImplementationData($submissionId);

        return Pdf::loadView('forms.print.pdf.learning-implementation', $data)
            ->setPaper('folio', 'portrait')
            ->stream('learning-implementation.pdf');
    }

    public function learningApplicationMonitoring(int $submissionId)
    {
        $data = $this->formService->learningApplicationMonitoringData($submissionId);

        return Pdf::loadView('forms.print.pdf.learning-application-monitoring', $data)
            ->setPaper('folio', 'portrait')
            ->stream('learning-application-monitoring.pdf');
    }

    public function resourceSpeakerEvaluation(int $submissionId)
    {
        $data = $this->formService->resourceSpeakerEvaluationData($submissionId);

        return Pdf::loadView('forms.print.pdf.resources-speaker-evaluation', $data)
            ->setPaper('folio', 'portrait')
            ->stream('resource-speaker-evaluation.pdf');
    }

    public function evaluationInstrument(int $submissionId)
    {
        $data = $this->formService->evaluationInstrumentData($submissionId);

        return Pdf::loadView('forms.print.pdf.evaluation-instrument', $data)
            ->setPaper('folio', 'portrait')
            ->stream('evaluation-instrument.pdf');
    }
}
