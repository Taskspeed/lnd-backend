<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\RSP\vwEmployee;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class OfficeEmployeeController extends Controller
{

    use ApiResponseTrait;

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $office, Request $request)
    {
        $titleName = $request->query('title');

        $employee = vwEmployee::with(['xCivilService', 'xTraining' => function ($query) use ($titleName) {
            $query->select('ControlNo', 'training', 'Dates', 'NumHours', 'Conductor', 'DateFrom', 'DateTo', 'Type')
                ->where('training', $titleName);
        }])
            ->select('ControlNo', 'name', 'office', 'position', 'status', 'sg', 'level')
            ->where('office', $office)
            ->when($request->filled('sg'), function ($query) use ($request) {
                $sg = trim((string) $request->query('sg'));

                // Range: "16-25" (pwede ring "16 - 25" o "25-16")
                if (preg_match('/^(\d+)\s*-\s*(\d+)$/', $sg, $m)) {
                    $min = min((int) $m[1], (int) $m[2]);
                    $max = max((int) $m[1], (int) $m[2]);

                    // ["16", "17", ..., "25"]
                    $values = array_map('strval', range($min, $max));

                    $query->whereIn('sg', $values);
                }
                // Exact: "16"
                elseif (ctype_digit($sg)) {
                    $query->where('sg', $sg);
                }
            })
            ->get()
            ->map(function ($emp) {
                $emp->trainingCount = $emp->xTraining->count();
                $emp->isAlreadyTrained = $emp->trainingCount > 0;
                return $emp;
            });

        if ($employee->isEmpty()) {
            return $this->errorMessage('No record employee found', 404);
        }

        return $this->successMessage($employee, 'Success fetch list of employee', 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
