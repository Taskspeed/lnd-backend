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
    public function show(string $office)
    {
        $employee = vwEmployee::select('ControlNo', 'office', 'position', 'name', 'status')
            ->where('office', $office)
            ->get();

        if ($employee->isEmpty()) {
            return $this->errorMessage('No record employee found', 404);
        }

        return $this->successMessage($employee, 'success fetch', 200);
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
