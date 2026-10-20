<?php

namespace App\Services\HR\Certification;

use App\Models\Employee\NominatedEmployee;
use App\Models\RSP\vwEmployee;
use Carbon\Carbon;
use Illuminate\Http\Request;

class CertificateService
{

    public function certificate(string $controlNo, ?string $search = null, int $perPage = 10)
    {
        $query = NominatedEmployee::with(['event' => function ($q) {
            $q->with('schedule')->select('id', 'title_name', 'learning_intervention',);
        }])
            ->select('id', 'event_id', 'control_no', 'full_name', 'nominate_status', 'certificate_issued', 'created_at')
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
        // pinakabagong created_at kada control_no
        $latestReleases = NominatedEmployee::where('nominate_status', 'Approved')
            ->where('certificate_issued', true)
            ->selectRaw('control_no, MAX(created_at) as latest_created_at')
            ->groupBy('control_no')
            ->pluck('latest_created_at', 'control_no'); // [control_no => date]

        $employees = vwEmployee::select('ControlNo', 'name', 'position', 'status')
            ->whereIn('ControlNo', $latestReleases->keys())
            ->when($request->filled('search'), function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%');
            })
            ->orderBy('name')
            ->paginate($perPage);

        $employees->through(function ($employee) use ($latestReleases) {
            $employee->photo_url = $employee->ControlNo
                ? url("/api/event/employee/{$employee->ControlNo}/photo")
                : null;

            $latest = $latestReleases[$employee->ControlNo] ?? null;

            $employee->certificate_date_release = $latest
                ? Carbon::parse($latest)->format('F d, Y')
                : null;

            return $employee;
        });

        return $employees;
    }
}
