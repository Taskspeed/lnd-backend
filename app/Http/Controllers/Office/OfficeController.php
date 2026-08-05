<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\RSP\Office;
use App\Models\RSP\vwEmployee;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OfficeController extends Controller
{
    //
    use ApiResponseTrait;

    public function index()
    {

        $offices = Office::select('id', 'office_name')->get();

        return $this->successMessage($offices, 'success fetch', 200);
    }

    public function show()
    {
        $user = Auth::user();

        $employee = vwEmployee::select('ControlNo', 'name', 'office', 'position')->where('office', $user->office)->get();

        return $this->successMessage($employee, 'success fetch', 200);
    }
}
