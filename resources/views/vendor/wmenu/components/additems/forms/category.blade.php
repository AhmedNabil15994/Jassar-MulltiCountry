<input type="hidden" name="type" value="{{$type}}">
<input type="hidden" name="menu" value="{{$item ? $item->menu: request()->input("menu")}}">
{!! field('headermenus')->select('category_id', 'category' , $categories->pluck('title','id')->toArray() ,
$item ? $item->itemable_id : null,['id' => 'category']) !!}

{!! field('headermenus')->text("class", "css class",$item ? $item->class : null) !!}