<?php

namespace App\Traits;

use Carbon\Carbon;

trait TimeFormat
{
public function toTime24(?string $time): ?string
{
    return $time
        ? Carbon::createFromFormat('h:i A', $time)->format('H:i:s')
        : null;
}
}
