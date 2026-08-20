<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventAddScheduleRequest;
use App\Http\Requests\Event\EventUpdateScheduleRequest;
use App\Services\Event\ScheduleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{

    use ApiResponseTrait;


    protected ScheduleService $scheduleService;

    public function __construct(ScheduleService $scheduleService)
    {
       $this->scheduleService = $scheduleService;
    }
   
    /**
     * Store a newly created resource in storage.
     */
   public function store(EventAddScheduleRequest $request)
    {
        $validated = $request->validated();

        try {
            $result = $this->scheduleService->addSchedule($validated);

            return $this->successMessage($result, 'success created', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
      public function edit(EventUpdateScheduleRequest $request, int $eventScheduleId)
    {
        $validated = $request->validated();

        try {
            $result = $this->scheduleService->editSchedule($eventScheduleId,$validated);

            return $this->successMessage($result, 'success update', 200,);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }


    public function update(Request $request, int $eventId)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Complete,Cancel'
        ]);

        try {
            $result = $this->scheduleService->updateEventSchedule($validated, $eventId);

            return $this->successMessage($result, 'success update', 200,);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
     public function destory(int $eventScheduleId)
    {

        try {
            $result = $this->scheduleService->delete($eventScheduleId);

            return $this->successMessage($result, 'success deleted', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
