<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Models\Event\Library\EventType;
use App\Services\Event\Library\EventTypeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventTypeController extends Controller
{

    use ApiResponseTrait;

    protected EventTypeService $service;

    public function __construct(EventTypeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $type = $this->service->index();

            if ($type->isEmpty()) {
                return $this->infoMessage('No records found', 200);
            }

            return $this->successMessage($type, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage('Failed to retrieve event types', 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string'
        ]);

        try {
            $type = $this->service->create($validatedData);
            return $this->successMessage($type, 'Created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $typeId)
    {
        $validatedData = $request->validate([
            'type_name' => 'required|string'
        ]);

        try {
            $type = $this->service->update($typeId, $validatedData);
            return $this->successMessage($type, 'Updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $typeId)
    {
        try {
            $type = $this->service->destroy($typeId);
            return $this->successMessage($type, 'Deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
