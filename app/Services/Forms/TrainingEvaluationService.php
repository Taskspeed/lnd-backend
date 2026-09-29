<?php

namespace App\Services\Forms;

use App\Models\Forms\RSE\TrainingEvaluationSpeakers;

class TrainingEvaluationService
{
    public function create(?array $validated)
    {

        $trainingEvaluation = TrainingEvaluationSpeakers::create([

            'event'                     => $validated['event'],
            'date'                      => $validated['date'],
            'venue'                     => $validated['venue'] ?? null,
            'learning_service_provider' =>  $validated['learning_service_provider'] ?? null,
            'comments'                  =>  $validated['comments'] ?? null,
        ]);

        // speaker
        foreach ($validated['speaker'] as $item) {
            $trainingEvaluation->speakerEvaluation()->create([
                'training_evaluation_speaker_id' => $trainingEvaluation->id,
                'event_speaker_id'          => $item['event_speaker_id'],
                'mastery_of_subject'          => $item['mastery_of_subject'],
                'ability_to_involve'    => $item['ability_to_involve'],
                'session_interest'             => $item['session_interest'],
                'handling_questions'          => $item['handling_questions'],
                'voice_projection' => $item['voice_projection'],
                'communication' => $item['communication'],
                'practical_relevance' => $item['practical_relevance'],
                'comments' => $item['comments'] ?? null,

            ]);
        }

        return $trainingEvaluation;
    }
}
