<?php

namespace Modules\Apps\Components\Dukaan\Home;

use Illuminate\View\Component;
use Modules\Vendor\Entities\Vendor;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Entities\Product;

class HomeSection extends Component
{

    public $home;
    public $records;
    public $type;
    /**
     * Create the component instance.
     *
     */
    public function __construct($home)
    {
        $type = $home->type;
        $this->home = $home;
        $this->type = $type;

        switch ($type) {
            case 'categories':
                $records = $this->getCategories();
                break;
            case 'products':
                $records = $this->getProducts();
                break;
            default:
                $records = $home->$type;
                break;
        }

        $this->records = isset($records) && $records ? $records : $home->$type;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view("apps::dukaan.components.home.{$this->type}");
    }

    private function getCategories()
    {
        $type = $this->type;

        if($this->home->$type()->count()){
            
            return Category::active()->with('media')
            ->MainCategories()
                ->whereHas('products',function ($q){
                    $q->where('products.country_id',COUNTRY_ID);
                })
            ->get(['id','title','slug']);
        }
    }

    private function getProducts()
    {
        $type = $this->type;
        $select = ['id','title','slug','price'];
        $with = ['media','offer','tags'];

        if($this->home->$type()->count()){
            return $this->home->$type()->with($with)->wherePivot('status',1)->withCount(['variants'])->get($select);
        }else{
            return Product::with($with)->active()
                ->where('country_id',COUNTRY_ID)
            ->withCount(['variants'])
            ->orderByRaw('RAND()')
            ->take(24)
            ->get($select);
        }
    }

}
