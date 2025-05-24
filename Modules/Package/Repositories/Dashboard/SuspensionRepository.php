<?php

namespace Modules\Package\Repositories\Dashboard;

use Carbon\CarbonPeriod;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Core\Traits\CoreHelpers;

class SuspensionRepository extends CrudRepository
{
    use CoreHelpers;
    public function modelCreated($model, $request, $is_created = true): void
    {

        $generateDaysCount = $this->getDaysCount($model->start_at, $model->end_at);
        $model->subscription()->increment('end_at', $generateDaysCount);
    }

    public function getDaysCount($start, $end)
    {
        $daysCount = 0;
        $period = CarbonPeriod::create($start, $end);
        foreach ($period as $date) {
            if (!$this->isClosedOn($date)) {
                $daysCount++;
            }
        }
        return   $daysCount;
    }
}
