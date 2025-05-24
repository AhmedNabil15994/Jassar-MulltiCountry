<?php

namespace Modules\Apps\Http\Controllers\Dashboard;

use Illuminate\Http\Request;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Illuminate\Support\Facades\Cache;
use Tocaan\Menu\Controllers\MenuController;

class HeadermenuController extends MenuController
{
    use CrudDashboardController{
        __construct as __crudConstruct;
    }

    function __construct(){
        $this->__crudConstruct();
        $tenantSubdomain = app('currentTenant')->subdomain;
        Cache::forget("{$tenantSubdomain}_front_header_links");
    }

    public function createOrUpdate(Request $request, $countryPrefix,$itemId = null)
    {
        try {
            if($request->has('type')){
                $rules = config('menu.add_items_forms')[$request->type]['validation'];
            }else{
                $rules = ['type' => 'required'];
            }
            $request->validate($rules);

            $create = $this->repository->createOrUpdate($request, $itemId);

            if ($create) {
                return $this->createdResponse($create, [true, __('apps::dashboard.messages.created')]);
            }

            return $this->createError($create, [false, __('apps::dashboard.messages.failed')]);
        } catch (\PDOException $e) {

            return $this->createError(null, [false, $e->errorInfo[2]]);
        }
    }
}
