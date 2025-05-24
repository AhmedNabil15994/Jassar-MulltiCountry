<?php

namespace Modules\Package\Http\Controllers\Dashboard;


use Illuminate\Routing\Controller;
use Modules\Package\Entities\Subscription;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Modules\Package\Transformers\Dashboard\SubscriptionResource;

class SuspensionController extends Controller
{
    use CrudDashboardController;
    public function extraData($model)
    {
        return [
            'subscriptions' => Subscription::where('is_default', 1)
                ->with('user')
                ->get()
        ];
    }
}
