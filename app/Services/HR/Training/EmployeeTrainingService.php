<?php

namespace App\Services\HR\Training;

use App\Models\RSP\vwEmployee;
use App\Traits\FormatsDateRanges;
use Carbon\Carbon;
use Illuminate\Http\Request;

class EmployeeTrainingService
{

    use FormatsDateRanges;
    
    public function employeeTrainings(string $office, string $titleName, Request $request)
    {

        $employee = vwEmployee::with([
            'xCivilService',
            'xTraining' => function ($query) use ($titleName) {
                $query->select('ControlNo', 'training', 'Dates', 'NumHours', 'Conductor', 'DateFrom', 'DateTo', 'Type')
                    ->where('training', $titleName);
            },

            'xTrainingHistory' => function ($query) {
                $query->select('ControlNo', 'training', 'NumHours', 'Conductor', 'DateFrom', 'DateTo', 'Type');
            },
        ])
            ->select('ControlNo', 'name', 'office', 'position', 'status', 'sg', 'level')
            ->where('office', $office)
            ->when($request->filled('sg'), function ($query) use ($request) {
                $sg = trim((string) $request->query('sg'));

                if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $sg, $m)) {
                    $min = min((int) $m[1], (int) $m[2]);
                    $max = max((int) $m[1], (int) $m[2]);
                    $query->whereIn('sg', array_map('strval', range($min, $max)));
                } elseif (ctype_digit($sg)) {
                    $query->where('sg', $sg);
                }
            })
            ->get()
            ->map(function ($emp) {
                $emp->trainingCount = $emp->xTraining->count();
                $emp->isAlreadyTrained = $emp->trainingCount > 0;
                $emp->previous_training = $this->getLatestTraining($emp->xTrainingHistory);

                $emp->unsetRelation('xTrainingHistory'); // para hindi sumama sa response
                return $emp;
            });

        if ($employee->isEmpty()) {
            throw new \Exception('No record employee found', 404);
        }

        return $employee;
    }

    private function getLatestTraining($trainings): ?array
    {
        $latest = $trainings
            ->map(fn($t) => [
                'model' => $t,
                'from'  => $this->safeParseDate($t->DateFrom)?->startOfDay(),
                'to'    => $this->safeParseDate($t->DateTo)?->startOfDay(),
            ])
            ->filter(fn($row) => $row['to'] || $row['from'])
            ->sortByDesc(fn($row) => ($row['to'] ?? $row['from'])->timestamp)
            ->first();

        if (!$latest) {
            return null;
        }

        $t = $latest['model'];

        // kung isa lang ang meron, gamitin yun para sa both
        $from = $latest['from'] ?? $latest['to'];
        $to   = $latest['to'] ?? $latest['from'];

        return [
            'training'  => $t->training,
            'Date'     => $this->formatRange($from, $to),
            'NumHours'  => $t->NumHours,
            'Conductor' => $t->Conductor,
            'Type'      => $t->Type,
            'DateFrom'  => $latest['from']?->format('F d, Y'),
            'DateTo'    => $latest['to']?->format('F d, Y'),
        ];
    }

    private function safeParseDate($value): ?Carbon
    {
        if (empty($value)) {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
