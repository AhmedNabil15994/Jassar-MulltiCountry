<?php

namespace Modules\Apps\Transformers\Frontend;

use  Illuminate\Http\Resources\Json\JsonResource;
use Modules\Catalog\Entities\Category;
use Modules\Catalog\Transformers\Frontend\CategoryResource;

class HeaderLinkResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request
     * @return array
     */
    public function toArray($request)
    {
        $data = [
            "id"   => $this->id ,
            'title' => $this->label,
            'depth' => $this->depth,
            'class' => $this->class,
            'type' => $this->type,
            'data' => [],
            'children' => [],
        ];

        switch($this->type){
            case 'category_list':
                $ids = isset($this->json_data['categories']) ? $this->json_data['categories'] : [];
                $menu_type = isset($this->json_data['menu_type']) ? $this->json_data['menu_type'] : 'nsted_menu';
                $main_categories = CategoryResource::collection(Category::active()->whereNull('category_id')->whereIn('id', $ids)->get())->jsonSerialize();
                $data['data']['ids'] = $ids;
                $data['data']['main_categories'] = $main_categories;
                $data['data']['menu_type'] = $menu_type;
                $data['data']['custom_link'] = route('frontend.categories.products');
                break;
            case 'category':
                $data['title'] = optional($this->itemable)->title;
                $data['data']['itemable'] = $this->itemable;
                $data['data']['custom_link'] = route("frontend.categories.products",optional($this->itemable)->slug);
                break;
            case 'page':
                $data['data']['itemable'] = $this->itemable;
                $data['data']['custom_link'] = route("frontend.pages.index",optional($this->itemable)->slug);
                break;
            case 'custom_link':
                $data['data']['custom_link'] = \URL::to('/'.locale().'/'.COUNTRY_PREFIX.$this->link);
                break;
        }

        return $data;
    }
}
