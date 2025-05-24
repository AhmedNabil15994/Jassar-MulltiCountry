<?php

namespace Modules\Core\Traits;

use Illuminate\Support\Facades\Schema;
use \Modules\Area\Entities\Country;

trait DataTable
{
    // DataTable Methods
    public static function drawTable($request, $query, $table = '')
    {
        $sort['col'] = $request->input('columns.' . $request->input('order.0.column') . '.data', 'id');
        $sort['dir'] = $request->input('order.0.dir', 'asc');
        $search = $request->input('search.value');

        $counter = $query->count();

        if (Schema::hasColumn($table, 'total')) {
            if(Schema::hasColumn($table, 'currency_data')){
                $country_id = $request->input('req.country_id');
                if($country_id){
                    $countryObj = Country::with('currency')->find($country_id);
                    $currency = $countryObj->currency->code;
                    $output['recordsTotalSum'] = $query->where('currency_data->selected_currency',$currency)->sum('total') . ' ' . $currency;
                }else{
                    $output['recordsTotalSum'] = __('order::dashboard.select_country_alert');
                }
            }else{
                $output['recordsTotalSum'] = $query->sum('total');
            }
        }

        $output['recordsTotal'] = $counter;
        $output['recordsFiltered'] = $counter;
        $output['draw'] = intval($request->input('draw'));
        $query_model = null;
        if (method_exists($query, 'getModel')) {
            $query_model = $query->getModel();
        }else{
            $output['data'] = $query;
            return  $output;
        }

        if ($query_model   && method_exists($query_model, 'isTranslatableAttribute') && $query_model->isTranslatableAttribute($sort['col'])) {
            $sort["col"]   = $sort["col"]."->".locale();
        }

        // Get Data
        $models = $query
            ->orderBy($sort['col'], $sort['dir'])
//            ->orderBy($order_by_translation_key ?? $sort['col'], $sort['dir'])
            ->skip($request->input('start'))
            ->take($request->input('length', 25))
            ->get();

        $output['data'] = $models;

        return $output;
    }

    // DataTable Methods
    public static function drawPrint($request, $query)
    {
        list($output, $models) = self::getModel($request, $query);

        $output['data'] = $models->get();
        return $output;
    }

    /**
     * @param $request
     * @param $query
     * @return array
     */
    public static function getModel($request, $query): array
    {
        $sort['col'] = $request->input('columns.' . $request->input('order.0.column') . '.data');
        $sort['dir'] = $request->input('order.0.dir');
        $search = $request->input('search.value');

        $counter = $query->count();

        $output['recordsTotal'] = $counter;
        $output['recordsFiltered'] = $counter;
        $output['draw'] = intval($request->input('draw'));

        // Get Data
        $models = $query->orderBy($sort['col'] ?? 'id', $sort['dir'] ?? 'desc');

        return array($output, $models);
    }
}
