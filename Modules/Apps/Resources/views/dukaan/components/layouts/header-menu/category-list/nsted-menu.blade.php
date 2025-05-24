@foreach($categories as $category)

    @php 
        $children = Modules\Catalog\Transformers\Frontend\CategoryResource::
        collection(Modules\Catalog\Entities\Category::active()->where('category_id',$category['id'])->whereIn('id', $ids)->get())->jsonSerialize();
    @endphp
    @if(count($children))                                  
        <li class="menu-item arrowleft">
            <a href="{{route('frontend.categories.products',$category['slug'])}}" class="dropdown-toggle">{{$category['title']}}</a>
            <ul class="submenu dropdown-menu nsted">
                <x-dukaan-header-category-list-nsted-menu :categories="$children" :ids="$ids"/>
            </ul>
        </li>
    @else
        <li class="menu-item"><a href="{{route('frontend.categories.products',$category['slug'])}}"> {{$category['title']}} </a></li>
    @endif
@endforeach