<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Jobs\SendCertificateEmail;
use App\Mail\CertificateMail;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\RSP\xPersonalAddt;
use App\Traits\ApiResponseTrait;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class CertificateController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    use ApiResponseTrait;


    // walang epekto sa DB, walang email — panonood lang
    public function preview(int $nominatedEmployeeId)
    {
        $data = $this->buildCertificateData($nominatedEmployeeId);

        $pdf = Pdf::loadView('certificates.certificate', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('certificate-preview.pdf');
    }

    // ito na ang talagang nag-eemail
    public function send(int $nominatedEmployeeId)
    {
        $nominee = NominatedEmployee::findOrFail($nominatedEmployeeId);

        $nomineeEmail = xPersonalAddt::where('ControlNo', $nominee->control_no)->first();

        if (!$nomineeEmail || !$nomineeEmail->EmailAdd) {
            return $this->errorMessage('No email address found for this employee', 422);
        }

        $data = $this->buildCertificateData($nominatedEmployeeId);

        $pdf = Pdf::loadView('certificates.certificate', $data)
            ->setPaper('a4', 'landscape');

        Mail::to($nomineeEmail->EmailAdd)
            ->queue(new CertificateMail(
                $pdf->output(),
                $data['recipientName'],
                $data['trainingTitle'],
                $data['signatoryName'],
                $data['signatoryTitle'],
                $data['dateIssued'],
            ));

        $nominee->update(['certificate_issued' => true]);

        return $this->successMessage(null, 'Certificate queued for sending', 200);
    }

    private function buildCertificateData(int $nominatedEmployeeId)
    {
        $nominee = NominatedEmployee::with('event')->findOrFail($nominatedEmployeeId);

        return [
            'recipientName'  => $nominee->full_name,
            'trainingTitle'  => $nominee->event->title_name ?? '',
            'signatoryName'  => 'Nick Sherlock',
            'signatoryTitle' => 'Manager',
            'dateIssued'     => now()->format('F d, Y'),
        ];
    }
}
