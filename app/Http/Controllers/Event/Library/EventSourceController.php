<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Services\Event\Library\EventSourceService;
use App\Services\Event\Library\EventTypeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventSourceController extends Controller
{

      use ApiResponseTrait;

    protected EventSourceService $service;

    public function __construct(EventSourceService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $source = $this->service->index();

            if ($source->isEmpty()) {
                return $this->infoMessage('No records found', 200);
            }

            return $this->successMessage($source, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage('Failed to retrieve event types', 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'source_name' => 'required|string'
        ]);

        try {
            $source = $this->service->create($validatedData);
            return $this->successMessage($source, 'Created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $sourceId)
    {
        $validatedData = $request->validate([
            'source_name' => 'required|string'
        ]);

        try {
            $source = $this->service->update($sourceId, $validatedData);
            return $this->successMessage($source, 'Updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $sourceId)
    {
        try {
            $source = $this->service->destroy($sourceId);
            return $this->successMessage($source, 'Deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
