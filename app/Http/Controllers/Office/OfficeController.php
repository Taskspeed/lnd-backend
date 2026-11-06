<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\RSP\Office;
use App\Models\RSP\vwEmployee;
use App\Models\RSP\xPersonal;
use App\Traits\ApiResponseTrait;
use App\Traits\ResolvesOfficeAbbr;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeController extends Controller
{
    //
    use ApiResponseTrait;
    use ResolvesOfficeAbbr;

    public function index()
    {

        $offices = Office::select('id', 'office_name')->get();

        return $this->successMessage($offices, 'success fetch', 200);
    }

    // public function show()
    // {
    //     $user = Auth::user();

    //     $employee = vwEmployee::select('ControlNo', 'name', 'office', 'position')->where('office', $user->office)->get();

    //     $additional_info = xPersonal::select('Controlno','Sex','BirthDate')->where('ControlNo',$employee->ControlNo)->first();

    //     return $this->successMessage($employee, 'success fetch', 200);
    // }

    public function show()
    {
        $user = Auth::user();

        $employees = vwEmployee::select('ControlNo', 'name', 'office', 'position', 'status')
            ->where('office', $user->office)
            ->get();

        $personals = xPersonal::select('ControlNo', 'Sex', 'BirthDate')
            ->whereIn('ControlNo', $employees->pluck('ControlNo'))
            ->get()
            ->keyBy('ControlNo');

        $data = $employees->map(function ($emp) use ($personals) {
            $personal = $personals->get($emp->ControlNo);

            return [
                'ControlNo'   => $emp->ControlNo,
                'name'        => $emp->name,
                'office'      => $emp->office,
                'office_abbr' => $this->officeAbbr($emp->office),
                'designation' => $emp->position,
                'status'      => $emp->status,
                'sex'         => $personal?->Sex,
                'age'         => $personal?->BirthDate
                    ? Carbon::parse($personal->BirthDate)->age
                    : null,
            ];
        })->values();

        return $this->successMessage($data, 'success fetch', 200);
    }

    public function employee(Request $request, string $office)
    {
        $request->validate([
            'search'   => 'nullable|string|max:100',
            'per_page' => 'nullable|integer|min:1|max:100',
        ]);

        $employee = vwEmployee::select('ControlNo', 'name', 'office', 'position','status')
            ->where('office', $office)
            ->when($request->filled('search'), fn($q) => $q->where('name', 'like', '%' . trim($request->search) . '%'))
            ->orderBy('name')
            ->paginate($request->input('per_page', 10));

        return $this->successMessage($employee, 'success fetch', 200);
    }
}
