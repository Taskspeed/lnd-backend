<?php

namespace App\Traits;

use Carbon\Carbon;

trait FormatsDateRanges
{
    protected function formatDateRanges($scheduleDateTimes)
    {
        if ($scheduleDateTimes->isEmpty()) {
            return null;
        }

        $sorted = $scheduleDateTimes
            ->map(fn($item) => Carbon::parse($item->schedule_date)->startOfDay())
            ->sort()
            ->values();

        $ranges = [];
        $rangeStart = $sorted[0];
        $rangeEnd = $sorted[0];

        foreach ($sorted->slice(1) as $current) {
            if ($rangeEnd->copy()->addDay()->isSameDay($current)) {
                // consecutive, extend range
                $rangeEnd = $current;
            } else {
                $ranges[] = $this->formatRange($rangeStart, $rangeEnd);
                $rangeStart = $current;
                $rangeEnd = $current;
            }
        }
        $ranges[] = $this->formatRange($rangeStart, $rangeEnd);

        return implode(', ', $ranges);
    }

    protected function formatRange(Carbon $start, Carbon $end)
    {
        if ($start->isSameDay($end)) {
            return $start->format('F j, Y'); // August 25, 2026
        }

        if ($start->isSameMonth($end)) {
            return $start->format('F j') . ' - ' . $end->format('j, Y'); // August 25 - 26, 2026
        }

        if ($start->isSameYear($end)) {
            return $start->format('F j') . ' - ' . $end->format('F j, Y'); // August 30 - September 2, 2026
        }

        return $start->format('F j, Y') . ' - ' . $end->format('F j, Y'); // diff year
    }
}