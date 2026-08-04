<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Services\Event\Library\EventTitleService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventTitleController extends Controller
{
    //
     use ApiResponseTrait;

    protected EventTitleService $service;

    public function __construct(EventTitleService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        try {
            $title = $this->service->index();

            if ($title->isEmpty()) {
                return $this->infoMessage('No records found', 200);
            }

            return $this->successMessage($title, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage('Failed to retrieve event types', 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'title_name' => 'required|string'
        ]);

        try {
            $title = $this->service->create($validatedData);
            return $this->successMessage($title, 'Created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $titleId)
    {
        $validatedData = $request->validate([
            'title_name' => 'required|string'
        ]);

        try {
            $title = $this->service->update($titleId, $validatedData);
            return $this->successMessage($title, 'Updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $titleId)
    {
        try {
            $title = $this->service->destroy($titleId);
            return $this->successMessage($title, 'Deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
