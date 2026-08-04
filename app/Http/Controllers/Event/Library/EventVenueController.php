<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Services\Event\Library\EventVenueService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventVenueController extends Controller
{
    //

        
    use ApiResponseTrait;

    protected EventVenueService $service;

    public function __construct(EventVenueService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $venue = $this->service->index();

            if ($venue->isEmpty()) {
                return $this->infoMessage('No records found', 200);
            }

            return $this->successMessage($venue, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage('Failed to retrieve event types', 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'venue_name' => 'required|string'
        ]);

        try {
            $venue = $this->service->create($validatedData);
            return $this->successMessage($venue, 'Created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $venueId)
    {
        $validatedData = $request->validate([
            'venue_name' => 'required|string'
        ]);

        try {
            $venue = $this->service->update($venueId, $validatedData);
            return $this->successMessage($venue, 'Updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $venueId)
    {
        try {
            $venue = $this->service->destroy($venueId);
            return $this->successMessage($venue, 'Deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
