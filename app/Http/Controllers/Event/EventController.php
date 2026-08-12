<?php

namespace App\Http\Controllers\Event;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventCreateRequest;
use App\Models\Event\Event;
use App\Services\Event\EventService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventController extends Controller
{
    //
    use ApiResponseTrait;

    protected EventService $eventService;

    public function __construct(EventService $eventSerice)
    {
        $this->eventService = $eventSerice;
    }

    public function index()
    {

        try {
            $result = $this->eventService->listOfEvent();

            return $this->successMessage($result, 'success fetch', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function view(int $eventId)
    {

        try {
            $result = $this->eventService->show($eventId);

            return $this->successMessage($result, 'success fetch', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function store(EventCreateRequest $request)
    {
        $validated = $request->validated();

        try {
            $result = $this->eventService->create($validated);

            return $this->successMessage($result, 'success created', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $eventId)
    {
        $validated = $request->validate([
            'status' => 'required|in:Pending,Complete,Cancel'
        ]);

        try {
            $result = $this->eventService->updateEventStatus($validated, $eventId);

            return $this->successMessage($result, 'success update', 200,);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destory(int $eventId)
    {

        try {
            $result = $this->eventService->delete($eventId);

            return $this->successMessage($result, 'success deleted', 200,);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
