<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Services\Event\Library\EventModeService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventModeController extends Controller
{
    //
    
    use ApiResponseTrait;

    protected EventModeService $service;

    public function __construct(EventModeService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $mode = $this->service->index();

            if ($mode->isEmpty()) {
                return $this->infoMessage('No records found', 200);
            }

            return $this->successMessage($mode, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage('Failed to retrieve event types', 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'mode_name' => 'required|string'
        ]);

        try {
            $mode = $this->service->create($validatedData);
            return $this->successMessage($mode, 'Created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $modeId)
    {
        $validatedData = $request->validate([
            'mode_name' => 'required|string'
        ]);

        try {
            $mode = $this->service->update($modeId, $validatedData);
            return $this->successMessage($mode, 'Updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $modeId)
    {
        try {
            $mode = $this->service->destroy($modeId);
            return $this->successMessage($mode, 'Deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
