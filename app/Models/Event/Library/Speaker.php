<?php

namespace App\Models\Event\Library;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;


class Speaker extends Model
{
  //

  protected $table = 'speakers';

  protected $fillable = [
    'first_name',
    'middle_name',
    'last_name',
    'suffix',
    'gender',
    'birth_date',
    'email',
    'contact_number',
    'profile_photo',
    'is_active',
  ];



  protected $appends = ['speakerId', 'speaker_name', 'profile_photo_url'];
  protected $hidden = [
    'id',
    'created_at',
    'updated_at'
  ];



  public function getSpeakerIdAttribute()
  {
    return $this->id;
  }

  public function getProfilePhotoUrlAttribute(): ?string
  {
    return $this->profile_photo
      ? Storage::disk('public')->url($this->profile_photo)
      : null;
  }

  public function getSpeakerNameAttribute(): string
  {
    return trim(implode(' ', array_filter([
      $this->first_name,
      $this->middle_name,
      $this->last_name,
      $this->suffix,
    ])));
  }

  public function employment()
  {
    return $this->hasOne(SpeakerEmployment::class);
  }

  public function professionalInfo()
  {
    return $this->hasOne(SpeakerProfessionalInfo::class);
  }
}
