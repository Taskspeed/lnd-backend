<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
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
    public function preview( int $nominatedEmployeeId)
    {
        $data = $this->buildCertificateData($nominatedEmployeeId);

        $pdf = Pdf::loadView('certificates.certificate', $data)
            ->setPaper('a4', 'landscape');

        return $pdf->stream('certificate-preview.pdf');
    }

    // ito na ang talagang nag-eemail
    // public function send(int $nominatedEmployeeId)
    // {
    //     $nominee = NominatedEmployee::with('user')->findOrFail($nominatedEmployeeId);
    //     $data = $this->buildCertificateData($nominatedEmployeeId);

    //     $pdf = Pdf::loadView('certificates.certificate', $data)
    //         ->setPaper('a4', 'landscape');

    //     Mail::to($nominee->user->email)
    //         ->send(new CertificateMail($pdf->output(), $nominee->full_name));

    //     $nominee->update(['certificate_issued' => true]);

    //     return $this->successMessage(null, 'Certificate sent successfully', 200);
    // }

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
