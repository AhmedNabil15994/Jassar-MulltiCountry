<?php

namespace Modules\Package\Http\Controllers\Dashboard;

use Illuminate\Routing\Controller;
use Modules\Package\Entities\Subscription;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Illuminate\Http\Request;
use Modules\Core\Traits\DataTable;
use Modules\Package\Transformers\Dashboard\SubscriptionResource;

class SubscriptionController extends Controller
{
    use CrudDashboardController;


    public function todayOrders()
    {
        return view('package::dashboard.subscriptions.today-orders');
    }

    public function toDayDatatable(Request $request)
    {
        $datatable = DataTable::drawTable($request, $this->repository->QueryTable($request)->Today());

        $resource = $this->model_resource;
        $datatable['data'] = $resource::collection($datatable['data']);

        return Response()->json($datatable);
    }

    public function getSubscriptionById($id)
    {
        return response()->json(Subscription::find($id));
    }

    public function print(Request $request)
    {
        $subscriptions = SubscriptionResource::collection(Subscription::whereIn('id',$request->ids)->latest()->get())->jsonSerialize();
        return Response()->json([true, 'print' => view('package::dashboard.subscriptions.print',compact('subscriptions'))->render()]);
    }
}
