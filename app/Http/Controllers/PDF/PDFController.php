<?php

namespace App\Http\Controllers\PDF;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\Event;
use App\Models\RSP\xPersonal;
use App\Models\RSP\yOffice;
use App\Services\PDF\PDFService;
use Barryvdh\DomPDF\Facade\Pdf;


class PDFController extends Controller

{

    protected PDFService $pdfService;

     public function __construct(PDFService $pdfService)
     {
            $this->pdfService = $pdfService;
     }


    public function attendanceSheet(int $eventId, int $eventSchduleId, int $scheduleDateTimeId)
    {

        $data = $this->pdfService->attendance($eventId, $eventSchduleId, $scheduleDateTimeId);

        $pdf = Pdf::loadView('forms.attendance-sheet', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('attendance-sheet.pdf');
    }


   
}
