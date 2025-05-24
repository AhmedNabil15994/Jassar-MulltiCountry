<?php

namespace Modules\Apps\Components\Dukaan\Layouts\HeaderMenu;

use Modules\Apps\Transformers\Frontend\HeaderLinkResource;
use Illuminate\View\Component;
use Tocaan\Menu\Models\Menus;
use Tocaan\Menu\Models\MenuItems;
use Illuminate\Support\Facades\Cache;

class HeaderMenu extends Component
{

    public $header_links;
    public $depth;
    private $parents_steps = [];
    public $tenantSubdomain;

    /**
     * Create the component instance.
     *
     */
    public function __construct()
    {
        $this->tenantSubdomain = app('currentTenant')->subdomain;
        $this->header_links = $this->getHeaderLinks();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\View\View|\Closure|string
     */
    public function render()
    {
        return view('apps::dukaan.components.layouts.header-menu.header-menu');
    }



    private function getHeaderLinks(){
        $front_header_links = [];
        $menu = optional(Menus::first())->id;
        if($menu){
            $output_front_header_links = [];
            $front_header_links = HeaderLinkResource::collection((new MenuItems)->getall($menu))->jsonSerialize();
            foreach($front_header_links as $header_link){

                if($header_link['depth'] == 0){

                    array_push($output_front_header_links,$header_link);
                }else{
                    $last_item = $output_front_header_links[count($output_front_header_links) - 1];

                    $newChildren = $this->addNestedHeaderChild($last_item['children'],$header_link,$header_link['depth']);

                    $output_front_header_links[count($output_front_header_links) - 1]['children'] = $newChildren;
                }
            }
            $front_header_links = $output_front_header_links;
        }

        return $front_header_links;
    }

    protected function addNestedHeaderChild($currentChild,$targetAppendItem,$depth,$beforChild = 'first',$action = 'down'): array{

        if($beforChild == 'first'){
            $this->depth = $depth;
            $this->parents_steps = [];
            $beforChild = [];
        }

        if($depth > 1){
            $depth--;
            if(!count($currentChild)){
                array_push($currentChild,[
                    "id"   => null ,
                    'title' => '',
                    'depth' => $currentChild[count($currentChild) - 1]['depth'] + 1,
                    'class' => '',
                    'type' => 'custom_link',
                    'data' => ['custom_link' => '#'],
                    'children' => [],
                ]);
            }

            $beforChild = $currentChild;
            $this->parents_steps["depth_{$currentChild[0]['depth']}"] = $beforChild;
            $currentChild = $currentChild[count($currentChild) - 1]['children'];

            return $this->addNestedHeaderChild($currentChild,$targetAppendItem,$depth,$beforChild,$action);
        }else{
            if($action == 'down'){

                array_push($currentChild,$targetAppendItem);
                $action = 'up';

                return $this->addNestedHeaderChild($currentChild,$targetAppendItem,$depth,$beforChild,$action);
            }else{
                $lastItemAppended = $currentChild[count($currentChild) - 1];

                if($lastItemAppended['depth'] == 1){
                    return $currentChild;
                }else{
                    $beforChildChildren = $beforChild[count($beforChild) - 1];
                    $currentChildChildren = $currentChild[count($currentChild) - 1];

                    if(count($currentChildChildren) && $currentChildChildren['id'] == $beforChildChildren['id'])
                        $beforChild[count($beforChild) - 1]['children'] = $currentChildChildren['children'];
                    else
                        $beforChild[count($beforChild) - 1]['children'] = $currentChild;

                    $parentDepth = $lastItemAppended['depth'] - 1;

                    return $this->addNestedHeaderChild($beforChild,$targetAppendItem,$depth,$this->parents_steps["depth_{$parentDepth}"],$action);
                }
            }
        }

    }
}
