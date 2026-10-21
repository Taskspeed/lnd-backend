<?php

namespace App\Services\Forms;

use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\Forms\RSE\TrainingEvaluationSpeakers;
use App\Models\RSP\vwEmployee;
use App\Models\User;
use App\Notifications\EmployeeSubmitFormNotification;
use App\Traits\FormSubmittedNotification;
use App\Traits\ResubmitsForm;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;

class TrainingEvaluationService

{
    use ResubmitsForm;
    use FormSubmittedNotification;

    private function formName()
    {
        return 'Resources Speaker Evaluation Form';
    }

    public function create(?array $validated)
    {
        return DB::transaction(function () use ($validated) {

            $event = Event::where('id', $validated['event_id'] ?? null)->first();

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

            $trainingEvaluation = TrainingEvaluationSpeakers::updateOrCreate(
                ['employee_form_submission_id' => $form_submit->id],
                [
                    'form_name'                 => $this->formName(),
                    'control_no'                => $validated['control_no'] ?? null,
                    'event'                     => $validated['event'],
                    'date'                      => $validated['date'],
                    'venue'                     => $validated['venue'] ?? null,
                    'learning_service_provider' => $validated['learning_service_provider'] ?? null,
                    // 'comments'                  => $validated['comments'] ?? null,
                ]
            );


            foreach ($validated['speaker'] as $item) {
                $trainingEvaluation->speakerEvaluation()->updateOrCreate(
                    [
                        'mastery_of_subject'  => $item['mastery_of_subject'],
                        'ability_to_involve'  => $item['ability_to_involve'],
                        'session_interest'    => $item['session_interest'],
                        'handling_questions'  => $item['handling_questions'],
                        'voice_projection'    => $item['voice_projection'],
                        'communication'       => $item['communication'],
                        'practical_relevance' => $item['practical_relevance'],
                        'over_all_rating'      => $item['over_all_rating'],
                        'comments'            => $item['comments'] ?? null,
                    ]
                );
            }

            // notification for admin form
            if ($form_submit->wasRecentlyCreated) {
                DB::afterCommit(fn() => $this->notifyHrAdmins($event, $event_schedule, $form_submit, $this->formName()));
            }

            return $trainingEvaluation->load('speakerEvaluation');
        });
    }
}
