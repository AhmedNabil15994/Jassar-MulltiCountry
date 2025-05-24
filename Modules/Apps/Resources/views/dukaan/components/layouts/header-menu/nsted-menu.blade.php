@foreach($link['children'] as $new_link)
    @if(count($new_link['children']))                                  
        <li class="menu-item arrowleft">
            <a href="{{$new_link['data']['custom_link']}}" class="dropdown-toggle">{{$new_link['title']}}</a>
            <ul class="submenu dropdown-menu nsted">
                <x-dukaan-header-nsted-menu :link="$new_link"/> 
            </ul>
        </li>
    @else
        <li class="menu-item"><a href="{{$new_link['data']['custom_link']}}"> {{$new_link['title']}} </a></li>
    @endif
@endforeach