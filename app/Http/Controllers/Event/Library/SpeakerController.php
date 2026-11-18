<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventAddSpeakerRequest;
use App\Http\Requests\Event\EventUpdateSpeakerRequest;
use App\Models\Event\EventSpeaker;
use App\Models\Event\Library\Speaker;
use App\Services\Event\Library\SpeakerService;
use App\Traits\ApiResponseTrait;
use App\Traits\FormatsDateRanges;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Concerns\FormatsMessages;

class SpeakerController extends Controller
{
    //

    use ApiResponseTrait;
    use FormatsDateRanges;

    protected SpeakerService $speakerService;
    public function __construct(SpeakerService $speakerService)
    {
        $this->speakerService = $speakerService;
    }

    public function index()
    {
        return $this->successMessage(
            $this->speakerService->listSpeakers(),
            'Fetch list Speaker successfully',
            201
        );
    }

    public function store(EventAddSpeakerRequest $request)
    {
        try {
            $speaker = $this->speakerService->storeSpeaker(
                $request->validated(),
                $request->file('profile_photo')
            );

            return $this->successMessage($speaker, 'Speaker created successfully', 201);
        } catch (\Throwable $e) {
            return $this->errorMessage('Unable to create speaker. Please try again.', 500);
        }
    }

    public function update(EventUpdateSpeakerRequest $request, int $speakerId)
    {
        try {
            $speaker = $this->speakerService->updateSpeaker(
                $speakerId,
                $request->validated(),
                $request->file('profile_photo'),
                $request->boolean('remove_photo')
            );

            if (!$speaker) {
                return $this->errorMessage('Speaker not found', 404);
            }

            return $this->successMessage($speaker, 'Speaker updated successfully', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $speakerId)
    {
        try {
            if (!$this->speakerService->deleteSpeaker($speakerId)) {
                return $this->errorMessage('Speaker not found', 404);
            }

            return $this->successMessage(null, 'Speaker deleted successfully', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function show(int $speakerId)
    {
        return $this->successMessage(
            $this->speakerService->getSpeaker($speakerId),
            'Speaker Profile',
            200
        );
    }

    public function fetchSpeaker(Request $request)
    {
        return $this->successMessage(
            $this->speakerService->searchSpeakers($request->query('search')),
            'Fetch list Speaker successfully',
            200
        );
    }

    public function history(Request $request, int $speakerId)
    {
        $data = $this->speakerService->getHistory(
            $speakerId,
            max((int) $request->query('page', 1), 1),
            max((int) $request->query('per_page', 5), 1),
            trim((string) $request->query('search', '')),
            $request->url(),
            $request->query()
        );

        return $this->successMessage($data, 'Speaker history', 200);
    }
}
