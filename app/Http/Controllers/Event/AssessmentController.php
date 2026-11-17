<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\AssessmentAddRequest;
use App\Models\Event\Assessment\Assessment;
use App\Traits\ApiResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    //
    use ApiResponseTrait;

    public function getAssessment(int $assessmentId)
    {

        $assessment = Assessment::with([
            'questions.options'
        ])->find($assessmentId);


        return $this->successMessage($assessment, 'fetch assessment');
    }

    public function printAssessment(int $assessmentId)
    {
        $assessment = Assessment::with([
            'questions' => fn($q) => $q->orderBy('sort_order'),
            'questions.options',
        ])->findOrFail($assessmentId);

        return Pdf::loadView('assessment.assessment', ['assessment' => $assessment])
            ->setPaper('legal', 'portrait') // long bond paper, lengthwise
            ->stream("assessment-{$assessmentId}.pdf");
    }
}
