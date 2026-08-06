<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;

class TimeFormatCast implements CastsAttributes
{
   
    public function get($model, string $key, $value, array $attributes)
    {
        return $value ? Carbon::parse($value)->format('h:i A') : null;
    }

    public function set($model, string $key, $value, array $attributes)
    {
        // kapag nag-sasave, i-parse pabalik sa H:i:s format na kelangan ng SQL Server
        return $value ? Carbon::parse($value)->format('H:i:s') : null;
    }
}
