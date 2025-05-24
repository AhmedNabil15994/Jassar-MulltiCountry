<?php

namespace Modules\Package\Http\Controllers\Dashboard;


use Illuminate\Support\Arr;
use Illuminate\Routing\Controller;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;
use Modules\Catalog\Repositories\Dashboard\CategoryRepository;
use Modules\Catalog\Repositories\Dashboard\ProductRepository;
use Modules\Core\Traits\Dashboard\CrudDashboardController;
use Modules\Package\Transformers\Dashboard\PackagePricesResource;

class PackageController extends Controller
{
    use CrudDashboardController;

    use CrudDashboardController {
        CrudDashboardController::__construct as private __tConstruct;
    }

    public function __construct()
    {
        $this->__tConstruct();
        $this->product = new ProductRepository();
    }

    public function extraData($model)
    {
        $products = $this->product->getAllActive();
        return [
            'model'          => $model,
            'products'     => $products,
            'package_prices' => PackagePricesResource::collection($model->prices)->jsonSerialize(),
        ];
    }




}
