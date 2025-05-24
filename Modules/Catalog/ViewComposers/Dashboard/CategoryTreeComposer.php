<?php

namespace Modules\Catalog\ViewComposers\Dashboard;

use Modules\Catalog\Repositories\Dashboard\CategoryRepository as Category;
use Illuminate\View\View;
use Modules\Catalog\Transformers\Dashboard\CategoryTreeResource;
use Cache;

class CategoryTreeComposer
{
    public $mainCategories;
    public $sharedActiveCategories;
    public $allCategories;

    public function __construct(Category $category)
    {
        $this->mainCategories = $category->mainCategories();
        $this->allCategories = $category->getAll();
    }

    /**
     * Bind data to the view.
     *
     * @param View $view
     * @return void
     */
    public function compose(View $view)
    {
        $view->with(['treeMainCategories' => CategoryTreeResource::collection($this->mainCategories)->jsonSerialize(), 'treeAllCategories' => $this->allCategories->pluck('title','id')->toArray()]);
    }
}
