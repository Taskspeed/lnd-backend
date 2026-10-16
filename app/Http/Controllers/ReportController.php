<?php

namespace App\Http\Controllers;

use App\Models\Report;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    use ApiResponseTrait;

    public function store(Request $request)
    {

        $validateData = $request->validate([
            'report_name' => 'required|string',
        ]);

        return DB::transaction(function () use ($validateData) {
            $report = Report::create($validateData);

            if (!$report) {
                throw new \Exception('Failed to create  report');
            }

           return $this->successMessage($report, 'success created', 202);
        });
    }

    public function index()
    {
        $report = Report::all();

        return $this->successMessage($report, 'fetch report list', 200);
    }
}
