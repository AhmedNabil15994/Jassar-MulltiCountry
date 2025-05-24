@if($link['data']['menu_type'] == 'nsted_menu')

    <li class="nsted-menu">
        <a href="#">{{$link['title']}}</a>

        <ul class="submenu dropdown-menu">   
            <x-dukaan-header-category-list-nsted-menu :categories="$link['data']['main_categories']" :ids="$link['data']['ids']"/>
        </ul>
    </li>
@else

    <li>
        <a href="#" class="more-items">{{$link['title']}} <i class="ti-more"></i></a>
        <div class="dropdown-menu mega-menu"> 
            <div class="d-flex justify-content-between">
                <x-dukaan-header-category-list-mega-menu :categories="$link['data']['main_categories']" :ids="$link['data']['ids']" :first="true"/>                                
            </div>
        </div>
    </li>
@endif