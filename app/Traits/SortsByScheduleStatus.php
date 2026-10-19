<?php

namespace App\Traits;

use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;

trait SortsByScheduleStatus
{
    /** ---------- EXISTING (walang bago dito) ---------- */

    protected function sortByScheduleStatus(Collection $schedules): Collection
    {
        $order = ['up-coming' => 1, 'ongoing' => 2, 'complete' => 3];

        return $schedules
            ->sortBy(fn($s) => [
                $order[$s->computedStatus] ?? 1,
                $s->scheduleDateTime->min('schedule_date'),
            ])
            ->values();
    }

    protected function paginateCollection(Collection $items, int $perPage = 10, ?int $page = null): LengthAwarePaginator
    {
        $page ??= LengthAwarePaginator::resolveCurrentPage();

        return new LengthAwarePaginator(
            $items->forPage($page, $perPage)->values(),
            $items->count(),
            $perPage,
            $page,
            ['path' => LengthAwarePaginator::resolveCurrentPath()]
        );
    }

    protected function paginateByScheduleStatus(Collection $schedules, int $perPage = 10): LengthAwarePaginator
    {
        return $this->paginateCollection($this->sortByScheduleStatus($schedules), $perPage);
    }


}
