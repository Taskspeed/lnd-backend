<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Bpm\Bpm;
use App\Models\Bpm\BpmDates;
use App\Models\Bpm\Medical;
use App\Models\RSP\vwEmployee;

use App\Models\RSP\xPersonal;
use App\Traits\ApiResponseTrait;
use App\Traits\ResolvesOfficeAbbr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class BpmController extends Controller
{

    use ApiResponseTrait;
    use ResolvesOfficeAbbr;

    public function getListBpm(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'year'     => 'nullable|integer',
            'quarter'  => 'nullable|integer|in:1,2,3,4',
            'month'    => 'nullable|string',
            'week'     => 'nullable|integer|min:1|max:5',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $quarterMonths = [
            1 => ['January', 'February', 'March'],
            2 => ['April', 'May', 'June'],
            3 => ['July', 'August', 'September'],
            4 => ['October', 'November', 'December'],
        ];

        $bpm = BpmDates::where('office', $user->office)
            ->when($request->filled('year'), fn($q) => $q->where('year', $request->year))
            ->when($request->filled('quarter'), fn($q) => $q->whereIn('month', $quarterMonths[(int) $request->quarter]))
            ->when($request->filled('month'), fn($q) => $q->where('month', $request->month))
            ->when($request->filled('week'), fn($q) => $q->where('week', $request->week))
            ->orderByDesc('id')
            ->paginate($request->input('per_page', 10));

        return $this->successMessage($bpm, 'list of bpm submitted', 200);
    }

    public function storeBpm(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'month' => 'required|string', // 0-based, tugma sa Vue (0 = January)
            'year'  => 'required|integer|min:2000|max:2100',
            'week'  => 'required|integer|between:1,5',

            'employee'                       => 'required|array|min:1',
            'employee.*.controlNo'           => 'required|string',
            'employee.*.medical_history'     => 'nullable|string|max:255',
            'employee.*.systolic_pressure'   => 'nullable|integer|between:0,300|required_with:employee.*.diastolic_pressure',
            'employee.*.diastolic_pressure'  => 'nullable|integer|between:0,200|required_with:employee.*.systolic_pressure',
        ]);

        // 1. Bawal ang duplicate na period para sa office
        $exists = BpmDates::where('office', $user->office)
            ->where('month', $validated['month'])
            ->where('year', $validated['year'])
            ->where('week', $validated['week'])
            ->exists();

        if ($exists) {
            return response()->json([
                'message' => 'BPM record for this period already exists.',
            ], 422);
        }

        // 3. Isang transaction para all-or-nothing
        $bpmDate =     DB::transaction(function () use ($validated, $user) {
            $bpmDate = BpmDates::create([
                'month'  => $validated['month'],
                'year'   => $validated['year'],
                'week'   => $validated['week'],
                'office' => $user->office,
            ]);

            $now = now();

            $records = collect($validated['employee'])->map(fn($emp) => [
                'bpm_date_id'        => $bpmDate->id,
                'controlNo'          => $emp['controlNo'],
                // 'medical_history'    => $emp['medical_history'] ?? null,
                'systolic_pressure'  => $emp['systolic_pressure'] ?? null,
                'diastolic_pressure' => $emp['diastolic_pressure'] ?? null,
                'created_at'         => $now,
                'updated_at'         => $now,
            ])->all();

            Bpm::insert($records);
            return $bpmDate; // ito ang mapupunta sa $bpmDate sa labas
        });

        return $this->successMessage($bpmDate, 'BPM record saved', 201);
    }


    public function viewBpm(int $bpmDateId)
    {

        // 1. Hanapin ang period, at siguraduhing sa office niya
        $bpmDate = BpmDates::where('id', $bpmDateId)
            ->first();

        if (!$bpmDate) {
            return response()->json(['message' => 'BPM record not found.'], 404);
        }

        // 2. BP records ng period na ito
        $records = Bpm::where('bpm_date_id', $bpmDate->id)->get();
        $controlNos = $records->pluck('controlNo');

        // 3. Employee details (isang query, naka-key by ControlNo)
        $employees = vwEmployee::select('ControlNo', 'name', 'office', 'position', 'status')
            ->whereIn('ControlNo', $controlNos)
            ->get()
            ->keyBy('ControlNo');

        // 4. Sex at BirthDate (isang query din)
        $personals = xPersonal::select('ControlNo', 'Sex', 'BirthDate')
            ->whereIn('ControlNo', $controlNos)
            ->get()
            ->keyBy('ControlNo');

        // 5. Pagsamahin
        $data = $records->map(function ($record) use ($employees, $personals) {
            $emp = $employees->get($record->controlNo);
            $personal = $personals->get($record->controlNo);

            return [
                'ControlNo'          => $record->controlNo,
                'name'               => $emp?->name,
                'office'             => $emp?->office,
                'designation'        => $emp?->position,
                'status'             => $emp?->status,
                'sex'                => $personal?->Sex,
                'age'                => $personal?->BirthDate
                    ? Carbon::parse($personal->BirthDate)->age
                    : null,
                'systolic_pressure'  => $record->systolic_pressure,
                'diastolic_pressure' => $record->diastolic_pressure,
            ];
        })->values();

        return $this->successMessage([
            'bpm_date_id' => $bpmDate->id,
            'month'       => $bpmDate->month,
            'year'        => $bpmDate->year,
            'week'        => $bpmDate->week,
            'employees'   => $data,
        ], 'fetch the bpm record', 200);
    }

    public function deleteBpm(int $bpmDateId)
    {
        $bpm = BpmDates::find($bpmDateId);

        if (!$bpm) {
            return $this->errorMessage('BPM record not found', 404);
        }

        DB::transaction(function () use ($bpm) {
            // $bpm->readings()->delete(); // kung may child records at walang cascade
            $bpm->delete();
        });

        return $this->successMessage(null, 'BPM record deleted successfully', 200);
    }

    public function updateBpm(Request $request, int  $bpmDateId)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'employee'                      => 'required|array|min:1',
            'employee.*.controlNo'          => 'required|string',
            // 'employee.*.medical_history'    => 'nullable|string|max:255',
            'employee.*.systolic_pressure'  => 'nullable|integer|between:0,300|required_with:employee.*.diastolic_pressure',
            'employee.*.diastolic_pressure' => 'nullable|integer|between:0,200|required_with:employee.*.systolic_pressure',
        ]);

        // Dapat sa office ng user ang record
        $bpmDate = BpmDates::where('id', $bpmDateId)
            ->where('office', $user->office)
            ->firstOrFail();

        DB::transaction(function () use ($validated, $bpmDate) {
            foreach ($validated['employee'] as $emp) {
                Bpm::updateOrCreate(
                    [
                        'bpm_date_id' => $bpmDate->id,
                        'controlNo'   => $emp['controlNo'],
                    ],
                    [
                        'systolic_pressure'  => $emp['systolic_pressure'] ?? null,
                        'diastolic_pressure' => $emp['diastolic_pressure'] ?? null,
                        // 'medical_history' => $emp['medical_history'] ?? null,
                    ]
                );
            }

            $bpmDate->touch(); // i-update ang updated_at ng record
        });

        return $this->successMessage($bpmDate->fresh(), 'BPM record updated', 200);
    }



    public function bpmSubmittedByOffice(Request $request)
    {

        $request->validate([
            'office'     => 'nullable|string',
            'year'     => 'nullable|integer',
            'quarter'  => 'nullable|integer|in:1,2,3,4',
            'month'    => 'nullable|string',
            'week'     => 'nullable|integer|min:1|max:5',

        ]);

        $quarterMonths = [
            1 => ['January', 'February', 'March'],
            2 => ['April', 'May', 'June'],
            3 => ['July', 'August', 'September'],
            4 => ['October', 'November', 'December'],
        ];

        $bpm = BpmDates::when($request->filled('office'), fn($q) => $q->where('office', $request->office))
            ->when($request->filled('year'), fn($q) => $q->where('year', $request->year))
            ->when($request->filled('quarter'), fn($q) => $q->whereIn('month', $quarterMonths[(int) $request->quarter]))
            ->when($request->filled('month'), fn($q) => $q->where('month', $request->month))
            ->when($request->filled('week'), fn($q) => $q->where('week', $request->week))
            ->orderByDesc('id')
            ->paginate($request->input('per_page', 10));

        return $this->successMessage($bpm, 'list of bpm submitted', 200);
    }
}
