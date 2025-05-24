<?php

namespace Modules\Apps\Repositories\Dashboard;
use Modules\Catalog\Entities\Category;
use Modules\Page\Entities\Page;
use Tocaan\Menu\Models\MenuItems;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Modules\Core\Repositories\Dashboard\CrudRepository;

class HeadermenuRepository extends CrudRepository
{
    public function createOrUpdate(Request $request, $itemId = null)
    {
        DB::beginTransaction();
        $model = MenuItems::find($itemId) ?? new MenuItems;
        $data = $this->getData($request,$itemId ?'update':'create'); 

        try {
            
            foreach($data as $key => $value){
                $model->$key = $value;
            }
            $model->save();
            DB::commit();
            
            return $model;
        } catch (\Exception $e) {

            DB::rollback();
            throw $e;
        }
    }

    private function getData($request,$action)
    {
        switch($request->type){
            case 'custom_link':
                $data = [
                    'label' => $request->label,
                    'type' => 'custom_link',
                    'link' => $request->url,
                ];
                break;
            case 'category':
                $data = [
                    'label' => optional(Category::find($request->category_id))->title,
                    'type' => 'category',
                    'itemable_type' => Category::class,
                    'itemable_id' => $request->category_id,
                ];
                break;
            case 'category_list':

                $data = [
                    'label' => $request->label,
                    'type' => 'category_list',
                    'json_data' => [
                        'menu_type' => $request->menu_type,
                    ],
                ];
                if($request->category_id)
                    $data['json_data']['categories'] = $request->category_id;
                break;
            case 'page':
                $data = [
                    'label' => $request->label,
                    'type' => 'page',
                    'itemable_type' => Page::class,
                    'itemable_id' => $request->page_id,
                ];
                break;
        }

        if($action == 'update'){

            $data['sort'] = MenuItems::getNextSortRoot($request->menu) + 1;
    
        }else{

            $data['menu'] = (int)$request->menu;
        }

        $data['class'] = $request->class;
        return $data;
    }
}
