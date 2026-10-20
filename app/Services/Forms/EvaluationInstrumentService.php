<?php

namespace App\Services\Forms;

use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\Forms\LES\EvaluationInstrument;
use App\Traits\FormSubmittedNotification;
use App\Traits\ResubmitsForm;
use Illuminate\Support\Facades\DB;

class EvaluationInstrumentService
{


    use ResubmitsForm;
    use FormSubmittedNotification;

    private function formName()
    {
        return 'Level 1 Evaluation Instrument';
    }

    public function create(?array $validated)
    {
        return DB::transaction(function () use ($validated) {

            $event = Event::find($validated['event_id'] ?? null);
            if (!$event) {
                throw new \Exception('Event not found.');
            }

            $event_schedule = EventSchedule::where('id', $validated['event_schedule_id'] ?? null)
                ->where('event_id', $event->id)
                ->first();
            if (!$event_schedule) {
                throw new \Exception('Event schedule not found.');
            }

            $form_submit = $this->upsertSubmission($event, $event_schedule, $validated);

            $evaluation = EvaluationInstrument::updateOrCreate(
                ['employee_form_submission_id' => $form_submit->id],
                [
                    'event'                => $validated['event'],
                    'date'                 => $validated['date'],
                    'venue'                => $validated['venue'],
                    'name_of_participant'  => $validated['name_of_participant'],
                    'comments_suggestions' => $validated['comments_suggestions'] ?? null,
                    'control_no'           => $validated['control_no'] ?? null,
                    'form_name'            => $this->formName(),
                ]
            );

            // Linisin ang lumang child rows para hindi mag-duplicate kapag nag-resubmit
            $evaluation->facilitator()->delete();
            $evaluation->topic()->delete();
            $evaluation->venue_evaluation()->delete();
            $evaluation->feedback()->delete();

            foreach ($validated['facilitator'] as $item) {
                $evaluation->facilitator()->create($this->agreementRow($item));
            }

            foreach ($validated['topic'] as $item) {
                $evaluation->topic()->create($this->agreementRow($item));
            }

            foreach ($validated['venue_feedback'] as $item) {
                $evaluation->venue_evaluation()->create($this->agreementRow($item));
            }

            foreach ($validated['feedback'] as $item) {
                $evaluation->feedback()->create([
                    'excellent'          => $item['excellent'] ?? false,
                    'high_average'       => $item['high_average'] ?? false,
                    'good'               => $item['good'] ?? false,
                    'fair'               => $item['fair'] ?? false,
                    'poor'               => $item['poor'] ?? false,
                    'highly_recommended' => $item['highly_recommended'] ?? false,
                    'not_a_priority'     => $item['not_a_priority'] ?? false,
                ]);
            }
            
            // notification for admin form
            if ($form_submit->wasRecentlyCreated) {
                DB::afterCommit(fn() => $this->notifyHrAdmins($event, $event_schedule, $form_submit, $this->formName()));
            }

            return [$evaluation, $form_submit];
        });
    }

    // Pareho ang structure ng 3 sections kaya isang helper na lang
    private function agreementRow(array $item): array
    {
        return [
            'question'          => $item['question'],
            'strongly_agree'    => $item['strongly_agree'] ?? false,
            'agree'             => $item['agree'] ?? false,
            'disagree'          => $item['disagree'] ?? false,
            'strongly_disagree' => $item['strongly_disagree'] ?? false,
        ];
    }
}
