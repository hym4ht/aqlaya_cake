<?php

namespace App\Services;

use Carbon\Carbon;

class LeadTimeService
{
    public function minimumOrderDate(): Carbon
    {
        return now()->startOfDay()->addDays(2);
    }

    public function isAllowed(string $date): bool
    {
        return Carbon::parse($date)->startOfDay()->greaterThanOrEqualTo($this->minimumOrderDate());
    }
}
