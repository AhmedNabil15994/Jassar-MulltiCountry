@foreach($categories as $category)

    @php 
        $children = Modules\Catalog\Transformers\Frontend\CategoryResource::
        collection(Modules\Catalog\Entities\Category::active()->where('category_id',$category['id'])->whereIn('id', $ids)->get())->jsonSerialize();
    @endphp
    @if(count($children)) 
        @if($first)
            <div class="categories-col">
                <div class="col-list">
                    <h5>{{$category['title']}}</h5>
                    <x-dukaan-header-category-list-mega-menu :categories="$children" :ids="$ids" :first="false"/>
                </div>
            </div>      
        @else
            <a href="{{route('frontend.categories.products',$category['slug'])}}"> {{$category['title']}}</a>
            <x-dukaan-header-category-list-mega-menu :categories="$children" :ids="$ids" :first="false"/>
        @endif
    @else
        <a href="{{route('frontend.categories.products',$category['slug'])}}"> {{$category['title']}}</a>
    @endif
@endforeach