<?php

namespace Modules\Package\ViewComposers\Dashboard;

use Cache;
use Illuminate\View\View;
use Modules\Package\Repositories\Dashboard\PackageRepository;

class MostPopulerPackageComposer
{
    private $mostPopuler;

    public function __construct(PackageRepository $package)
    {
        $this->mostPopuler = $package->mostPopuler();
    }

    public function compose(View $view)
    {
        $view->with('most_populer', $this->mostPopuler);
    }
}
