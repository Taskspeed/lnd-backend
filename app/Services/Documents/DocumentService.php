<?php

namespace App\Services\Documents;

use App\Models\Documents\EmployeeDocument;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class DocumentService
{
    public function document(array $validated)
    {
       

        $storedPaths = [];

        try {
            $employeeDocument = DB::transaction(function () use ($validated, &$storedPaths) {

                // kung may existing na (same event, schedule, control_no) gamitin ito,
                // kung wala pa, gagawa ng bago
                $employeeDocument = EmployeeDocument::firstOrCreate(
                    [
                        'event_id' => $validated['event_id'],
                        'event_schedule_id' => $validated['event_schedule_id'],
                        'control_no' => $validated['control_no'],
                    ],
                    ['status' => 'Pending']
                );

                // may bagong upload, kaya ibalik sa Pending para ma-review ulit ng HR
                if (! $employeeDocument->wasRecentlyCreated) {
                    $employeeDocument->update(['status' => 'Pending']);
                }

                $directory = "documents/{$validated['control_no']}/{$validated['event_id']}/{$validated['event_schedule_id']}";

                foreach ($validated['document'] as $item) {
                    $file = $item['file_path'];

                    $filename = Str::uuid() . '.' . $file->getClientOriginalExtension();

                    $path = $file->storeAs($directory, $filename, 'public');
                    $storedPaths[] = $path;

                    // idadagdag lang sa existing record, hindi papalitan ang mga luma
                    $employeeDocument->documents()->create([
                        'file_path' => $path,
                        'title' => $item['title'],
                    ]);
                }

                return $employeeDocument;
            });
        } catch (\Throwable $e) {
            // linisin ang na-upload na files kapag nag-fail ang DB
            Storage::disk('public')->delete($storedPaths);
            throw $e;
        }

        return  $employeeDocument;
         
    }
}
