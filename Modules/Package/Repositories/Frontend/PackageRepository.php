<?php

namespace Modules\Package\Repositories\Frontend;

use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Package\Entities\Package as Model;
use Modules\Package\Entities\Package;
use Modules\Package\Entities\PackagePrice;

class PackageRepository extends CrudRepository
{
    public function getModel()
    {
        $this->model = new Package;

        return $this->model;
    }

    public function getAllPackages()
    {
        return  $this->getModel()->latest()->active()
            ->when(request('s'), function ($q, $val) {
                $q->search($val);
            })->where('country_id',COUNTRY_ID)
            ->when(
                request('price_from') && request('price_to'),
                function ($q){
                    $q->whereBetween('price',  [request('price_from'), request('price_to')]);
                });
    }

    public function findPackageById($id)
    {
        return $this->getModel()->with(['country.currency','products'])->active()->find($id);
    }
}
