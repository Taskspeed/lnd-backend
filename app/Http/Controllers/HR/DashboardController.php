<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\Event\EventScheduleDateTime;
use App\Services\HR\Dashboard\DashboardService;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    use ApiResponseTrait;

    protected DashboardService $dashboardService;


    public function __construct(DashboardService $dashboardService)
    {
        $this->dashboardService = $dashboardService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $today = now()->startOfDay();
        $perPage = $request->input('per_page', 10);

        $result = $this->dashboardService->upComingEvent($today, $perPage);

        return $this->successMessage($result, 'success', 200);
    }


    public function calendar(Request $request)
    {
        $validated = $request->validate([
            'year' => 'nullable|integer|min:2000|max:2100',
            'month' => 'nullable|integer|min:1|max:12',
        ]);

        $year = $validated['year'] ?? now()->year;
        $month = $validated['month'] ?? now()->month;

        $result = $this->dashboardService->eventDate($year, $month);

        return $this->successMessage($result, 'success', 200);
    }
}
