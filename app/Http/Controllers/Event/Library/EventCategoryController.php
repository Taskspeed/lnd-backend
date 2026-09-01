<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Services\Event\Library\EventCategoryService;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

class EventCategoryController extends Controller
{
    //

     use ApiResponseTrait;

    protected EventCategoryService $service;

    public function __construct(EventCategoryService $service)
    {
        $this->service = $service;
    }

    public function index(Request $request)
    {
         $search  = $request->query('search'); 
        try {
            $category = $this->service->index($search);

            if ($category->isEmpty()) {
                return $this->infoMessage('No records found', 200);
            }

            return $this->successMessage($category, 'Success', 200);
        } catch (\Exception $e) {
            return $this->errorMessage('Failed to retrieve event types', 500);
        }
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string'
        ]);

        try {
            $category = $this->service->create($validatedData);
            return $this->successMessage($category, 'Created successfully', 201);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(Request $request, int $categoryId)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string'
        ]);

        try {
            $category = $this->service->update($categoryId, $validatedData);
            return $this->successMessage($category, 'Updated successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $categoryId)
    {
        try {
            $category = $this->service->destroy($categoryId);
            return $this->successMessage($category, 'Deleted successfully', 200);
        } catch (\Exception $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }
}
