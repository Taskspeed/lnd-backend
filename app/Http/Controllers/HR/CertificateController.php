<?php

namespace App\Http\Controllers\HR;

use App\Http\Controllers\Controller;

use App\Http\Resources\Certification\EmployeeCertificateResource;
use App\Jobs\SendCertificateEmail;
use App\Mail\CertificateMail;
use App\Models\Employee\Certificate;
use App\Models\Employee\NominatedEmployee;
use App\Models\Event\EmployeeFormSubmission;
use App\Models\Event\Event;
use App\Models\Event\EventSchedule;
use App\Models\RSP\vwEmployee;
use App\Models\RSP\xPersonalAddt;
use App\Models\RSP\xTraining;
use App\Notifications\CertificationReceivedNotification;
use App\Services\HR\Certification\CertificateService;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class CertificateController extends Controller
{

    use ApiResponseTrait;
    use FormatsDateRanges;


    protected CertificateService $certificateService;

    public function __construct(CertificateService $certificateService)
    {
        $this->certificateService = $certificateService;
    }


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

        $pdfContent = Pdf::loadView('certificates.certificate', $data)
            ->setPaper('a4', 'landscape')
            ->output();

        // 1. i-save sa storage + certificate table
        $certificate = $this->storeCertificate(
            $pdfContent,
            $nominee->control_no,
            $data['trainingTitle'],
            $data['hours'],
            $data['conducted_by'],
            $data['type_name'],

        );
        $this->storeTraining(
            $nominee->control_no,
            $data['trainingTitle'],
            $data['hours'],
            $data['conducted_by'],
            $data['type_name'],
            $data['dateFrom'],
            $data['dateTo'],
            $data['dates'],
        );

        // 2. i-queue ang email gamit ang parehong PDF
        Mail::to($nomineeEmail->EmailAdd)
            ->queue(new CertificateMail(
                $pdfContent,
                $data['recipientName'],
                $data['trainingTitle'],
                $data['signatoryName'],
                $data['signatoryTitle'],
                $data['dateIssued'],
            ));

        $nominee->update(['certificate_issued' => true]);

        return $this->successMessage($certificate, 'Certificate queued for sending', 200);
    }


    // store certificate on certificate 
    private function storeCertificate(
        string $pdfContent,
        string $controlNo,
        string $title,
        int $hours,
        string $conducted_by,
        string $type_name,

    ): Certificate {
        $path = "certificates/{$controlNo}/" . Str::uuid() . '.pdf';
        Storage::disk('public')->put($path, $pdfContent);

        try {
            $certificate = Certificate::create([
                'control_no'       => $controlNo,
                'title'            => $title,
                'certificate_path' => $path,
                'hours'            => $hours,
                'conducted_by'     => $conducted_by,
                'activity'         => $type_name,
            ]);
        } catch (\Throwable $e) {
            // huwag mag-iwan ng orphan file kapag pumalya ang DB
            Storage::disk('public')->delete($path);
            throw $e;
        }

        $this->notifyCertificate($certificate, $controlNo, $title);

        return $certificate;
    }

    private function notifyCertificate(
        Certificate $certificate,
        string $controlNo,
        string $title,

    ): void {
        try {
            $notification = new CertificationReceivedNotification(
                certificateId: $certificate->id,
                controlNo: $controlNo,
                title: $title

            );

            DatabaseNotification::create([
                'id'              => (string) Str::uuid(),
                'type'            => CertificationReceivedNotification::class,
                'notifiable_type' => NominatedEmployee::class, // pareho sa submission notification
                'notifiable_id'   => $controlNo,
                'data'            => $notification->toArray(new \stdClass),
            ]);
        } catch (\Throwable $e) {
            Log::warning('Failed to send certificate notification', [
                'certificate_id' => $certificate->id,
                'error'          => $e->getMessage(),
            ]);
        }
    }

   private function buildCertificateData(int $nominatedEmployeeId): array
{
    $nominee = NominatedEmployee::findOrFail($nominatedEmployeeId);

    $event    = Event::find($nominee->event_id);
    $schedule = EventSchedule::with('scheduleDateTime')->find($nominee->event_schedule_id);

    $dates = $schedule?->scheduleDateTime?->pluck('schedule_date')->filter()->sort()->values();

    return [
        'recipientName'  => $nominee->full_name,
        'trainingTitle'  => $event?->title_name ?? '',
        'signatoryName'  => 'Nick Sherlock',
        'signatoryTitle' => 'Manager',
        'dateIssued'     => now()->format('F d, Y'),
        'hours'          => $schedule?->hours ?? '',
        'conducted_by'   => $schedule?->conducted_by ?? '',
        'type_name'      => $schedule?->type_name ?? '',
        'dateFrom'       => $dates?->first()?->format('m/d/Y'),
        'dateTo'         => $dates?->last()?->format('m/d/Y'),
        'dates'          => $schedule
            ? $this->formatDateRanges($schedule->scheduleDateTime)
            : null,   // hal. "October 1 - 2, 2026"
    ];
}


 private function storeTraining(
    string $controlNo, string $title, int $hours, string $conductedBy,
    string $typeName, ?string $dateFrom, ?string $dateTo, ?string $dates,
) {
    try {
        $training = xTraining::firstOrCreate(
            ['ControlNo' => $controlNo, 'Training' => $title, 'DateFrom' => $dateFrom],
            [
                'NumHours'  => $hours,
                'Conductor' => $conductedBy,
                'DateTo'    => $dateTo,
                'type'      => $typeName,
                'Dates'     => $dates,
            ]
        );

        Log::info('storeTraining', [
            'created'  => $training->wasRecentlyCreated, // true = bagong insert, false = existing na
            'training' => $training->toArray(),
        ]);

        return $training;
    } catch (\Throwable $e) {
        Log::error('storeTraining failed: ' . $e->getMessage());
        throw $e;
    }
}
    
    public function employeeCertificateRelease(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        $data = $this->certificateService->certificateRelease($perPage, $request);

        return $this->successMessage($data, 'Employee have Certificate', 200);
    }

    public function employeeListOfCertificate(string $controlNo, Request $request)
    {
        $search  = $request->query('search');
        $perPage = (int) $request->query('per_page', 10);

        $certificate = $this->certificateService->certificate($controlNo, $search, $perPage);

        $certificate->getCollection()->transform(
            fn($item) => new EmployeeCertificateResource($item)
        );

        return $this->successMessage($certificate, 'Employee have Certificate', 200);
    }
}
