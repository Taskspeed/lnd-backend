<?php

namespace App\Http\Controllers\Erms\Assessment;

use App\Http\Controllers\Controller;
use App\Models\Event\Assessment\Assessment;
use App\Models\Event\Assessment\AssessmentAnswer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AssessmentController extends Controller
{
    public function submitAssessmentAnswer(Request $request, int $assessmentId): JsonResponse
    {
        $validated = $request->validate([
            'nominated_employee_id'                  => ['required', 'integer', 'min:1'],
            'answers'                                => ['required', 'array', 'min:1'],
            'answers.*.assessment_question_id'       => ['required', 'integer', 'distinct'],
            'answers.*.assessment_option_ids'        => ['required', 'array', 'min:1'],
            'answers.*.assessment_option_ids.*'      => ['required', 'integer', 'distinct'],
        ]);

        $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);
        $questions  = $assessment->questions->keyBy('id');
        $employeeId = $validated['nominated_employee_id'];

        // Isang beses lang puwedeng sumagot
        $alreadyAnswered = AssessmentAnswer::where('nominated_employee_id', $employeeId)
            ->whereIn('assessment_question_id', $questions->keys())
            ->exists();

        if ($alreadyAnswered) {
            return response()->json([
                'success' => false,
                'message' => 'You have already submitted this assessment.',
            ], 409);
        }

        // Validate: ang question ay galing sa assessment na ito, at ang options ay galing sa question
        foreach ($validated['answers'] as $answer) {
            $question = $questions->get($answer['assessment_question_id']);

            if (! $question) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid question for this assessment.',
                ], 422);
            }

            $validOptionIds = $question->options->pluck('id');
            foreach ($answer['assessment_option_ids'] as $optionId) {
                if (! $validOptionIds->contains($optionId)) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Invalid option for question ' . $question->id . '.',
                    ], 422);
                }
            }

            // Multiple choice = isang sagot lang
            if ($question->question_type === 'multiple_choice'
                && count($answer['assessment_option_ids']) !== 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Question ' . $question->id . ' accepts only one answer.',
                ], 422);
            }
        }

        $earnedTotal = 0;
        $totalPoints = (int) $questions->sum('points');

        DB::transaction(function () use ($validated, $questions, $employeeId, &$earnedTotal) {
            foreach ($validated['answers'] as $answer) {
                $question = $questions->get($answer['assessment_question_id']);

                $selected = collect($answer['assessment_option_ids'])->map(fn ($id) => (int) $id)->sort()->values();
                $correct  = $question->options->where('is_correct', true)->pluck('id')->map(fn ($id) => (int) $id)->sort()->values();

                // All-or-nothing: dapat eksaktong tugma ang napili sa tamang sagot
                $isCorrect    = $selected->all() === $correct->all();
                $earnedPoints = $isCorrect ? (int) $question->points : 0;
                $earnedTotal += $earnedPoints;
                $optionsById = $question->options->keyBy('id');

                foreach ($selected as $index => $optionId) {
                    AssessmentAnswer::create([
                        'assessment_question_id' => $question->id,
                        'assessment_option_id'   => $optionId,
                        'nominated_employee_id'  => $employeeId,
                        'answer_text'            => $optionsById[$optionId]->option_text, // <-- galing sa DB
                        'is_correct'             => $isCorrect,
                        // points ng question ay nasa unang row lang para tama ang SUM(earned_points)
                        'earned_points'          => $index === 0 ? $earnedPoints : 0,
                    ]);
                }
            }
        });

        return response()->json([
            'success' => true,
            'message' => 'Assessment submitted successfully.',
            'data'    => [
                'assessment_id' => $assessment->id,
                'score'         => $earnedTotal,
                'total_points'  => $totalPoints,
                'passing_score' => (int) $assessment->passing_score,
                'passed'        => $earnedTotal >= (int) $assessment->passing_score,
            ],
        ], 201);
    }
public function myResult(Request $request, int $assessmentId): JsonResponse
{
    $employeeId = (int) $request->query('nominated_employee_id');

    $assessment = Assessment::with('questions.options')->findOrFail($assessmentId);
    $questions  = $assessment->questions->sortBy('sort_order')->values();

    $answers = AssessmentAnswer::where('nominated_employee_id', $employeeId)
        ->whereIn('assessment_question_id', $questions->pluck('id'))
        ->get();

    if ($answers->isEmpty()) {
        return response()->json(['success' => false, 'message' => 'No submission found.'], 404);
    }

    $byQuestion = $answers->groupBy('assessment_question_id');
    $score      = (int) $answers->sum('earned_points');

    return response()->json([
        'success' => true,
        'message' => 'Assessment result',
        'data'    => [
            'assessmentId'  => $assessment->id,
            'event_name'    => $assessment->event_name,
            'title'         => $assessment->title,
            'passing_score' => (int) $assessment->passing_score,
            'score'         => $score,
            'total_points'  => (int) $questions->sum('points'),
            'passed'        => $score >= (int) $assessment->passing_score,

            // { questionId: [optionId, ...] } -> int, hindi string
            'my_answers'    => $byQuestion->map(
                fn ($g) => $g->pluck('assessment_option_id')->map(fn ($id) => (int) $id)->values()
            ),

            'questions'     => $questions->map(function ($q) use ($byQuestion) {
                $mine = $byQuestion->get($q->id);

                return [
                    'id'            => $q->id,
                    'question'      => $q->question,
                    'question_type' => $q->question_type,
                    'points'        => (int) $q->points,
                    'sort_order'    => $q->sort_order,
                    'answered'      => $mine !== null,
                    'is_correct'    => $mine ? (bool) $mine->first()->is_correct : false,
                    'earned_points' => $mine ? (int) $mine->sum('earned_points') : 0,
                    'options'       => $q->options->map(fn ($o) => [
                        'id'          => (int) $o->id,
                        'option_text' => $o->option_text,
                        'is_correct'  => (bool) $o->is_correct,
                    ])->values(),
                ];
            })->values(),
        ],
    ]);
}
}

