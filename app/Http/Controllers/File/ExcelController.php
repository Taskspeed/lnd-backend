<?php

namespace App\Http\Controllers\File;

use App\Exports\AgencyLearningDevelopmentExport;
use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\RSP\vwEmployee;
use App\Services\Excel\ExcelService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ExcelController extends Controller


{

    protected ExcelService $excelService;

    public function __construct(ExcelService $excelService)
    {
        $this->excelService = $excelService;
    }
    
    public function agencyLearningDevelopmentMonitoringReport(Request $request)
    {
        $year = (int) $request->query('year', now()->year);

        $employees = $this->excelService
            ->generateAgencyLearningDevelopmentMonitoringReport($year);

        if ($request->query('format') === 'json') {
            return response()->json($employees);
        }

        return Excel::download(
            new AgencyLearningDevelopmentExport($employees, $year),
            "AGENCY_LEARNING_AND_DEVELOPMENT_MONITORING_REPORT_{$year}.xlsx"
        );
    }
}
