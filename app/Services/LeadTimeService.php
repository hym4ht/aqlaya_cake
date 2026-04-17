<?php

namespace App\Services;

use Carbon\Carbon;

class LeadTimeService
{
    public function minimumOrderDate(int $leadTimeDays = 2): Carbon
    {
        return now()->startOfDay()->addDays($leadTimeDays);
    }

    public function isAllowed(string $date, int $leadTimeDays = 2): bool
    {
        return Carbon::parse($date)->startOfDay()->greaterThanOrEqualTo($this->minimumOrderDate($leadTimeDays));
    }
}
