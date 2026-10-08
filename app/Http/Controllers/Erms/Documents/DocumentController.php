<?php

namespace App\Http\Controllers\Erms\Documents;

use App\Http\Controllers\Controller;
use App\Http\Requests\Document\DocumentStoreRequest;
use App\Models\Documents\Document;
use App\Models\Documents\EmployeeDocument;
use App\Services\Documents\DocumentService;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;


class DocumentController extends Controller
{
    use ApiResponseTrait;

    protected DocumentService $documentService;

    public function __construct(DocumentService $documentService)
    {
        $this->documentService = $documentService;
    }


    public function storeDocument(DocumentStoreRequest $request)
    {
        $validated = $request->validated();

         $employeeDocument = $this->documentService->document($validated);
    
        return $this->successMessage(
            $employeeDocument->load('documents'),
            'Documents submitted successfully.'
        );
    }

    public function getImage(int $eventId, int $eventScheduleId, string $controlNo)
    {
        $document = EmployeeDocument::with('documents')
            ->where('event_id', $eventId)
            ->where('event_schedule_id', $eventScheduleId)
            ->where('control_no', $controlNo)
            ->first();

        return $this->successMessage($document, 'Document Fetch', 200);
    }

    public function viewFile(Document $document)
    {
        $path = $document->file_path;

        abort_unless($path && Storage::disk('public')->exists($path), 404, 'File not found.');

        return Storage::disk('public')->response($path);
    }

    public function deleteDocument(Document $document)
    {
        // TODO: authorization check (dapat kanya o HR lang)

        $path = $document->file_path;
        $parent = $document->employeeDocument;

        DB::transaction(function () use ($document, $parent) {
            $document->delete();

            // kung wala nang natirang file, burahin na rin ang parent record
            if ($parent && $parent->documents()->count() === 0) {
                $parent->delete();
            }
        });

        // burahin ang file sa disk pagkatapos lang mag-commit ang DB
        if ($path) {
            Storage::disk('public')->delete($path);
        }

        return $this->successMessage(null, 'Document deleted successfully.');
    }

    
}
