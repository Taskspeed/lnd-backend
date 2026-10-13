<?php

namespace App\Http\Controllers\Erms\Certificate;

use App\Http\Controllers\Controller;
use App\Models\Employee\Certificate;
use App\Traits\ApiResponseTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    use ApiResponseTrait;

    public function myCertificate(string $control_no, Request $request)
    {
         $perPage = $request->input('per_page', 5);
           $certificates = Certificate::where('control_no', $control_no)
        ->orderByDesc('id') // palitan kung iba ang primary key / gusto mong sort
        ->paginate($perPage);

        return $this->successMessage($certificates, 'list of certificate', 200);
    }

    public function viewFile(Certificate $certificate)
    {
        $path = $certificate->certificate_path;

        abort_unless($path && Storage::disk('public')->exists($path), 404, 'File not found.');

        return Storage::disk('public')->response($path);
    }
}
