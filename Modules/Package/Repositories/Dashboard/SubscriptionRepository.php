<?php

namespace Modules\Package\Repositories\Dashboard;

use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Package\Entities\Subscription;

class SubscriptionRepository extends CrudRepository
{

    public function __construct($model = null)
    {
        $this->model = new Subscription();
    }

    public function appendSearch(&$query, $request): \Illuminate\Database\Eloquent\Builder
    {
        $query->orWhere(function ($query) use ($request) {
            $query->whereHas('user',function($query) use ($request) {

                $query->where('name', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('mobile', 'like', '%' . $request->input('search.value') . '%');
                $query->orWhere('email', 'like', '%' . $request->input('search.value') . '%');
            });
        });
        return $query;
    }

    public function appendFilter(&$query, $request): \Illuminate\Database\Eloquent\Builder
    {
        // dd($request['req']);
        if (isset($request['req']['package_id']) ) {
            $query->where('package_id', $request['req']['package_id']);
        }

        return $query
            ->when(
                data_get($request, 'req.is_default') == 'on',
                fn ($q) => $q->where('is_default', 1)
            )
            ->when(
                data_get($request, 'req.can_order_in'),

                function ($q, $val) {
                    return  $q->whereDoesntHave('suspensions', function ($q) use ($val) {
                        return $q->where(function ($q) use ($val) {
                            return $q->where('suspensions.start_at', "<=", $val)
                                ->where('suspensions.end_at', ">=", $val);
                        });
                    });
                }
            );
    }

    public function monthlyOrders()
    {
        $data["orders_dates"] = $this->getModel()
            ->select(DB::raw("DATE_FORMAT(created_at,'%Y-%m') as date"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at,'%Y-%m')"))
            ->pluck('date');

        $ordersIncome = $this->getModel()

            ->select(DB::raw("sum(price) as profit"))
            ->groupBy(DB::raw("DATE_FORMAT(created_at, '%Y-%m')"))
            ->get();

        $data["profits"] = json_encode(array_pluck($ordersIncome, 'profit'));

        return $data;
    }



    public function getOrdersQuery()
    {


        return      $data = $this->getModel()
            ->where(function ($query) {
                if (request()->get('from')) {
                    $query->whereDate('created_at', '>=', request()->get('from'));
                }

                if (request()->get('to')) {
                    $query->whereDate('created_at', '<=', request()->get('to'));
                }
            });
    }

    public function totalTodayProfit()
    {
        return $this->getModel()
            
            ->whereDate("created_at", \DB::raw('CURDATE()'))
            ->sum('price');
    }

    public function totalMonthProfit()
    {
        return $this->getModel()

            ->whereMonth("created_at", date("m"))
            ->whereYear("created_at", date("Y"))
            ->sum('price');
    }

    public function totalYearProfit()
    {
        return $this->getModel()
            ->whereYear("created_at", date("Y"))
            ->sum('price');
    }

    public function completeOrders()
    {
        $orders = $this->getModel()->count();

        return $orders;
    }

    public function totalProfit()
    {
        return $this->getModel()->sum('price');
    }
}
