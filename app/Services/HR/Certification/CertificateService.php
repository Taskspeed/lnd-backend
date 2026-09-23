<?php

namespace App\Services\HR\Certification;

use App\Models\Employee\NominatedEmployee;
use App\Models\RSP\vwEmployee;
use Illuminate\Http\Request;

class CertificateService
{

    public function certificate(string $controlNo, ?string $search = null, int $perPage = 10)
    {
        $query = NominatedEmployee::with(['event' => function ($q) {
            $q->with('schedule')->select('id', 'title_name', 'learning_intervention');
        }])
            ->select('id', 'event_id', 'control_no', 'full_name', 'nominate_status', 'certificate_issued')
            ->where('nominate_status', 'Approved')
            ->where('certificate_issued', true)
            ->where('control_no', $controlNo);

        if (filled($search)) {
            $query->whereHas('event', function ($q) use ($search) {
                $q->where('title_name', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage)->withQueryString();
    }


    public function certificateRelease(int $perPage, Request $request)
    {


        $controlNos = NominatedEmployee::where('nominate_status', 'Approved')
            ->where('certificate_issued', true)
            ->distinct()
            ->pluck('control_no');

        $employees = vwEmployee::select('ControlNo', 'name', 'position', 'status')
            ->whereIn('ControlNo', $controlNos)
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage);

        return $employees;
    }
}
