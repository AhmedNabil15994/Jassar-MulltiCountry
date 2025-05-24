
@switch($link['type'])
    @case('category_list')
        <x-dukaan-header-category-list-index :link="$link"/>
    @break
    @default
        <li><a href="{{$link['data']['custom_link']}}">{{$link['title']}}</a></li>
    @break

@endswitch
