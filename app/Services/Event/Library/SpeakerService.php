<?php

namespace App\Services\Event\Library;

use App\Models\Event\EventSpeaker;
use App\Models\Event\Library\Speaker;
use App\Traits\FormatsDateRanges;
use Illuminate\Http\UploadedFile;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpeakerService
{
    use FormatsDateRanges;

    public function listSpeakers(): Collection
    {
        return Speaker::select('id', 'first_name', 'middle_name', 'last_name', 'gender')
            ->with(['employment' => function ($query) {
                $query->select('id', 'speaker_id', 'agency', 'position', 'employment_type');
            }])
            ->get();
    }

    public function storeSpeaker(array $validated, ?UploadedFile $photo = null): Speaker
    {
        $photoPath = null;

        try {
            if ($photo) {
                $photoPath = $photo->store('speakers', 'public');
            }

            return DB::transaction(function () use ($validated, $photoPath) {
                $speaker = Speaker::create([
                    'first_name'     => $validated['first_name'],
                    'middle_name'    => $validated['middle_name'] ?? null,
                    'last_name'      => $validated['last_name'],
                    'suffix'         => $validated['suffix'] ?? null,
                    'gender'         => $validated['gender'],
                    'birth_date'     => $validated['birth_date'],
                    'email'          => $validated['email'] ?? null,
                    'contact_number' => $validated['contact_number'] ?? null,
                    'profile_photo'  => $photoPath,
                ]);

                $speaker->employment()->create([
                    'position'        => $validated['position'] ?? null,
                    'office'          => $validated['office'] ?? null,
                    'employment_type' => $validated['employment_type'] ?? null,
                    'salary_grade'    => $validated['salary_grade'] ?? null,
                    'agency'          => $validated['agency'] ?? null,
                ]);

                $speaker->professionalInfo()->create([
                    'professional_title'  => $validated['professional_title'] ?? null,
                    'specialization'      => $validated['specialization'] ?? null,
                    'biography'           => $validated['biography'] ?? null,
                    'years_of_experience' => $validated['years_of_experience'] ?? null,
                ]);

                return $speaker->load(['employment', 'professionalInfo']);
            });
        } catch (\Throwable $e) {
            // Pumalya ang database: burahin ang na-upload na file
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            throw $e; // ang controller ang magre-respond
        }
    }

    /**
     * @return Speaker|null  null kung hindi nahanap ang speaker
     */
    public function updateSpeaker(
        int $speakerId,
        array $validated,
        ?UploadedFile $photo = null,
        bool $removePhoto = false
    ): ?Speaker {
        $speaker = Speaker::find($speakerId);

        if (!$speaker) {
            return null;
        }

        $oldPhotoPath = $speaker->profile_photo;
        $newPhotoPath = null;

        try {
            if ($photo) {
                $newPhotoPath = $photo->store('speakers', 'public');
            }

            $result = DB::transaction(function () use ($speaker, $validated, $newPhotoPath, $removePhoto) {
                $speakerData = Arr::only($validated, [
                    'first_name',
                    'middle_name',
                    'last_name',
                    'suffix',
                    'gender',
                    'birth_date',
                    'email',
                    'contact_number',
                ]);

                // hiwalay ang handling ng 'profile_photo'
                if ($newPhotoPath) {
                    $speakerData['profile_photo'] = $newPhotoPath;
                } elseif ($removePhoto) {
                    $speakerData['profile_photo'] = null;
                }

                $employmentData = Arr::only($validated, [
                    'position',
                    'office',
                    'employment_type',
                    'salary_grade',
                    'agency',
                ]);

                $professionalData = Arr::only($validated, [
                    'professional_title',
                    'specialization',
                    'biography',
                    'years_of_experience',
                ]);

                if ($speakerData) {
                    $speaker->update($speakerData);
                }

                if ($employmentData) {
                    $speaker->employment()->updateOrCreate([], $employmentData);
                }

                if ($professionalData) {
                    $speaker->professionalInfo()->updateOrCreate([], $professionalData);
                }

                return $speaker->load(['employment', 'professionalInfo']);
            });

            // tagumpay ang database, ligtas nang burahin ang lumang litrato
            if ($oldPhotoPath && ($newPhotoPath || $removePhoto)) {
                Storage::disk('public')->delete($oldPhotoPath);
            }

            return $result;
        } catch (\Throwable $e) {
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            throw $e;
        }
    }

    /**
     * @return bool  false kung hindi nahanap ang speaker
     */
    public function deleteSpeaker(int $speakerId): bool
    {
        $speaker = Speaker::find($speakerId);

        if (!$speaker) {
            return false;
        }

        $photoPath = $speaker->profile_photo;

        DB::transaction(function () use ($speaker) {
            // Tahasang burahin ang child rows. Hindi nakakasama kahit may cascade na.
            $speaker->employment()->delete();
            $speaker->professionalInfo()->delete();
            $speaker->delete();
        });

        if ($photoPath) {
            Storage::disk('public')->delete($photoPath);
        }

        return true;
    }

    public function getSpeaker(int $speakerId): ?Speaker
    {
        return Speaker::with(['employment', 'professionalInfo'])->find($speakerId);
    }

    public function searchSpeakers(?string $search = null): Collection
    {
        return Speaker::select('id', 'first_name', 'middle_name', 'last_name')
            ->with(['employment' => function ($query) {
                $query->select('id', 'speaker_id', 'agency', 'position', 'employment_type');
            }])
            ->when($search, function ($query, $search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) LIKE ?", ["%{$search}%"]);
                });
            })
            ->get();
    }

    public function getHistory(
        int $speakerId,
        int $page,
        int $perPage,
        string $search,
        string $path,
        array $query = []
    ): array {
        $rows = EventSpeaker::with([
            'dateTime' => function ($query) {
                $query->with([
                    'eventSchedule' => function ($query) {
                        $query->with('event')->select('id', 'event_id', 'venue_name', 'type_name');
                    },
                ])->select('id', 'event_schedule_id', 'schedule_date');
            },
        ])
            ->where('speaker_id', $speakerId)
            ->get();

        // Isang row kada schedule (hindi kada petsa)
        $allEvents = $rows
            ->filter(fn($r) => $r->dateTime?->eventSchedule)
            ->groupBy(fn($r) => $r->dateTime->event_schedule_id)
            ->map(function ($items, $scheduleId) {
                $schedule = $items->first()->dateTime->eventSchedule;

                return [
                    'schedule_id' => $scheduleId,
                    'event_id'    => $schedule->event_id,
                    'title_name'  => $schedule->event?->title_name,
                    'type_name'   => $schedule->type_name,
                    'venue_name'  => $schedule->venue_name,
                    // "November 29 - 30, 2026"
                    'date_range'  => $this->formatDateRanges($items->pluck('dateTime')),
                    'days'        => $items->count(),
                ];
            })
            ->sortByDesc('schedule_id')
            ->values();

        // Search: event name (title_name) lang
        $events = $search === ''
            ? $allEvents
            : $allEvents
                ->filter(fn($e) => mb_stripos((string) $e['title_name'], $search) !== false)
                ->values();

        $paginator = new LengthAwarePaginator(
            $events->forPage($page, $perPage)->values(),
            $events->count(),
            $perPage,
            $page,
            [
                'path'  => $path,
                'query' => $query,
            ]
        );

        return [
            // Kinukuwenta sa LAHAT ng event, kaya hindi nagbabago kapag nag-search
            'stats' => [
                'total_events' => $allEvents->count(),
                'by_type'      => $allEvents
                    ->groupBy(fn($e) => $e['type_name'] ?: 'Others')
                    ->map->count(),
            ],
            'events' => $paginator,
        ];
    }
}