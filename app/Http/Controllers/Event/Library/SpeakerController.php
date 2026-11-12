<?php

namespace App\Http\Controllers\Event\Library;

use App\Http\Controllers\Controller;
use App\Http\Requests\Event\EventAddSpeakerRequest;
use App\Http\Requests\Event\EventUpdateSpeakerRequest;
use App\Models\Event\Library\Speaker;
use App\Traits\ApiResponseTrait;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SpeakerController extends Controller
{
    //

    use ApiResponseTrait;

    public function index()
    {
        $speaker = Speaker::select('id', 'first_name', 'middle_name', 'last_name', 'gender')->with('employment', function ($query) {
            $query->select('id', 'speaker_id', 'agency', 'position', 'employment_type');
        })->get();


        return $this->successMessage($speaker, 'Fetch list Speaker successfully', 201);
    }

    public function store(EventAddSpeakerRequest $request)
    {
        $photoPath = null; // nasa labas ng try para magamit sa catch

        try {
            $validated = $request->validated();

            if ($request->hasFile('profile_photo')) {
                $photoPath = $request->file('profile_photo')->store('speakers', 'public');
            }

            $result = DB::transaction(function () use ($validated, $photoPath) {
                $speaker = Speaker::create([
                    'first_name'     => $validated['first_name'],
                    'middle_name'    => $validated['middle_name'] ?? null,
                    'last_name'      => $validated['last_name'],
                    'suffix'         => $validated['suffix'] ?? null,
                    'gender'         => $validated['gender'],
                    'birth_date'     => $validated['birth_date'],
                    'email'          => $validated['email'] ?? null,
                    'contact_number' => $validated['contact_number'] ?? null,
                    'profile_photo'  => $photoPath, // path na lang, hindi na ang file
                ]);

                $employment = $speaker->employment()->create([
                    'position'        => $validated['position'] ?? null,
                    'office'          => $validated['office'] ?? null,
                    'employment_type' => $validated['employment_type'] ?? null,
                    'salary_grade'    => $validated['salary_grade'] ?? null,
                ]);

                $professional = $speaker->professionalInfo()->create([
                    'professional_title'  => $validated['professional_title'] ?? null,
                    'specialization'      => $validated['specialization'] ?? null,
                    'biography'           => $validated['biography'] ?? null,
                    'years_of_experience' => $validated['years_of_experience'] ?? null,
                ]);

                return [
                    'speaker'      => $speaker,
                    'employment'   => $employment,
                    'professional' => $professional,
                ];
            });

            return $this->successMessage($result, 'Speaker created successfully', 201);
        } catch (\Throwable $e) {
            // pumalya ang database, burahin ang na-upload na file
            if ($photoPath) {
                Storage::disk('public')->delete($photoPath);
            }

            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function update(EventUpdateSpeakerRequest $request, int $speakerId)
    {
        $newPhotoPath = null;

        try {
            $speaker = Speaker::find($speakerId);

            if (!$speaker) {
                return $this->errorMessage('Speaker not found', 404);
            }

            $validated   = $request->validated();
            $oldPhotoPath = $speaker->profile_photo;
            $removePhoto  = $request->boolean('remove_photo');

            if ($request->hasFile('profile_photo')) {
                $newPhotoPath = $request->file('profile_photo')->store('speakers', 'public');
            }

            $result = DB::transaction(function () use ($speaker, $validated, $newPhotoPath, $removePhoto) {
                // wala na ang 'profile_photo' dito, hiwalay ang handling niya
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

            return $this->successMessage($result, 'Speaker updated successfully', 200);
        } catch (\Throwable $e) {
            if ($newPhotoPath) {
                Storage::disk('public')->delete($newPhotoPath);
            }

            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function destroy(int $speakerId)
    {
        try {
            $speaker = Speaker::find($speakerId);

            if (!$speaker) {
                return $this->errorMessage('Speaker not found', 404);
            }

            DB::transaction(function () use ($speaker) {
                // Tahasang burahin ang child rows. Hindi nakakasama kahit may cascade na.
                $speaker->employment()->delete();
                $speaker->professionalInfo()->delete();
                $speaker->delete();
            });

            // Burahin ang litrato kung may na-upload (ayusin ang disk ayon sa setup mo)
            if ($speaker->profile_photo) {
                Storage::disk('public')->delete($speaker->profile_photo);
            }

            return $this->successMessage(null, 'Speaker deleted successfully', 200);
        } catch (\Throwable $e) {
            return $this->errorMessage($e->getMessage(), 500);
        }
    }

    public function show(int $speakerId)
    {

        $speaker = Speaker::with(['employment', 'professionalInfo'])->find($speakerId);

        return $this->successMessage($speaker, 'Speaker Profile', 200);
    }
}
