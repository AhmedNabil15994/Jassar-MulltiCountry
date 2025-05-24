<?php

namespace Modules\Package\Repositories\Frontend;

use Modules\Core\Repositories\Dashboard\CrudRepository;
use Modules\Package\Entities\Offer;
use Modules\Package\Entities\Package as Model;
use Modules\Package\Entities\Package;
use Modules\Package\Entities\PackagePrice;

class OfferRepository extends CrudRepository
{
    public function getModel()
    {
        $this->model = new Offer;

        return $this->model;
    }

    public function getAllOffers()
    {
        return  $this->getModel()->latest()->active()
            ->when(request('s'), function ($q, $val) {
                $q->search($val);
            })->where('country_id',COUNTRY_ID);
    }

    public function findOfferById($id)
    {
        return $this->getModel()->with(['country.currency','products','freeProducts'])->active()->find($id);
    }

    public function findOfferBySlug($slug)
    {
        return $this->getModel()->with(['country.currency','products','freeProducts'])->active()->anyTranslation('title', $slug)->first();
    }

}
