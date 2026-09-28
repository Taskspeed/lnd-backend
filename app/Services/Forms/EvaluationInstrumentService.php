<?php

namespace App\Services\Forms;

use App\Models\Forms\LES\EvaluationInstrument;
use Illuminate\Support\Facades\DB;

class EvaluationInstrumentService
{
    public function create(?array $validated)
    {
        return DB::transaction(function () use ($validated) {
            $evaluation = EvaluationInstrument::create([
                'event'                => $validated['event'],
                'date'                 => $validated['date'],
                'venue'                => $validated['venue'],
                'name_of_participant'  => $validated['name_of_participant'],
                'comments_suggestions' => $validated['comments_suggestions'] ?? null,
            ]);

            // facilitator
            foreach ($validated['facilitator'] as $item) {
                $evaluation->facilitator()->create([
                    'evaluation_instrument_id' => $evaluation->id,
                    'question'          => $item['question'] ?? null,
                    'strongly_agree'    => $item['strongly_agree'] ?? false,
                    'agree'             => $item['agree'] ?? false,
                    'disagree'          => $item['disagree'] ?? false,
                    'strongly_disagree' => $item['strongly_disagree'] ?? false,
                ]);
            }

            // topic
            foreach ($validated['topic'] as $item) {
                $evaluation->topic()->create([
                    'evaluation_instrument_id' => $evaluation->id,
                    'question'          => $item['question'],
                    'strongly_agree'    => $item['strongly_agree'] ?? false,
                    'agree'             => $item['agree'] ?? false,
                    'disagree'          => $item['disagree'] ?? false,
                    'strongly_disagree' => $item['strongly_disagree'] ?? false,
                ]);
            }

            foreach ($validated['venue_feedback'] as $item) {
                $evaluation->venue()->create([
                    'evaluation_instrument_id' => $evaluation->id,
                    'question'          => $item['question'],
                    'strongly_agree'    => $item['strongly_agree'] ?? false,
                    'agree'             => $item['agree'] ?? false,
                    'disagree'          => $item['disagree'] ?? false,
                    'strongly_disagree' => $item['strongly_disagree'] ?? false,
                ]);
            }


            foreach ($validated['feedback'] as $item) {
                $evaluation->feedback()->create([
                    'evaluation_instrument_id' => $evaluation->id,
                    'excellent'          => $item['excellent'] ?? false,
                    'high_average'    => $item['high_average'] ?? false,
                    'good'             => $item['good'] ?? false,
                    'fair'          => $item['fair'] ?? false,
                    'poor' => $item['poor'] ?? false,
                    'highly_recommended' => $item['highly_recommended'] ?? false,
                    'not_a_priority' => $item['not_a_priority'] ?? false,
                ]);
            }

            return $evaluation;
        });
    }
}
