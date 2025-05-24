<?php

namespace Modules\Package\ViewComposers\Dashboard;

use Modules\Package\Repositories\Dashboard\PackageRepository as Repo;
use Illuminate\View\View;
use Cache;

class PackageComposer
{
    public $models = [];

    public function __construct(Repo $repo)
    {
        $this->models =  $repo->getAllActive();
    }

    public function compose(View $view)
    {
        $view->with('packages', $this->models);
    }
}
