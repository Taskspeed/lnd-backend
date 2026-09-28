<?php

namespace App\Models\Forms\LES;

use Illuminate\Database\Eloquent\Model;

class EvaluationInstrument extends Model
{
    //

    protected $table = 'evaluation_instruments';

    protected $fillable = [
        'event',
        'date',
        'venue',
        'name_of_participant',
        'comments_suggestions',
    ];


    public function facilitator()
    {
        return $this->hasOne(Facilitator::class);
    }

    public function topic()
    {
        return $this->hasOne(Topic::class);
    }

    public function feedback()
    {
        return $this->hasOne(Feedback::class);
    }

      public function venue()
    {
        return $this->hasOne(Venue::class);
    }
}
