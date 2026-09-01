<?php

namespace App\Services\Event\Library;

use App\Models\Event\Library\EventCategory;
use Illuminate\Support\Facades\DB;

class EventCategoryService
{
    
   public function index(?string $search = null)
    {
        
        $query = EventCategory::query();

        if (!empty($search)) {
            $query->where('category_name', 'like', "%{$search}%");
        }

        $category = $query->orderBy('category_name')
            ->get();

        return $category;
    }


    public function create(array $validateData)
    {
        return DB::transaction(function () use ($validateData) {
            $category = EventCategory::create($validateData);

            if (!$category) {
                throw new \Exception('Failed to create event category');
            }

            return $category;
        });
    }

    public function update(int $categoryId, array $validateData)
    {
        return DB::transaction(function () use ($categoryId, $validateData) {
            $category = EventCategory::find($categoryId);

            if (!$category) {
                throw new \Exception('Event category not found');
            }

            $category->update($validateData);

            return $category;
        });
    }

    public function destroy(int $categoryId)
    {
        $category = EventCategory::find($categoryId);

        if (!$category) {
            throw new \Exception('Event category not found');
        }

        $category->delete();

        return $category;
    }
}
