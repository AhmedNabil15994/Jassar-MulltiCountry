<?php

namespace Modules\Area\Repositories\FrontEnd;

use Modules\Area\Entities\City;
use Modules\Area\Entities\State;

class AreaRepository
{
    protected $city;
    protected $state;

    function __construct()
    {
        $this->city = new City;
        $this->state = new State;
    }

    public function getChildAreaByParent($request, $order = 'id', $sort = 'desc')
    {
        return $this->city->active()->has('activeStates')->with(['states'])->where('country_id', $request->country_id)->orderBy($order, $sort)->get();
    }


}
