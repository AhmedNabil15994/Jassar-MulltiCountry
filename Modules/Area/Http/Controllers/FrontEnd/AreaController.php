<?php

namespace Modules\Area\Http\Controllers\FrontEnd;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Modules\Area\Entities\State;
use Modules\Shipping\Traits\ShippingTrait;

class AreaController extends Controller
{
    use ShippingTrait;

    public function getChildAreaByParent(Request $request)
    {
        $this->setShippingTypeByRequest($request);
        return response()->json([State::where('country_id',$request->country_id)->get()]);
    }
}
