<?php

namespace App\Http\Controllers\File;

use App\Http\Controllers\Controller;
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

    public function nomination(int $eventId, int $eventScheduleId)
    {
        $event = $this->pdfService->nominationPdf($eventId, $eventScheduleId);

        $pdf = Pdf::loadView('forms.nomination', ['event' => $event])
            ->setPaper('a4', 'portrait');

        return $pdf->stream('nomination.pdf');
    }
}
