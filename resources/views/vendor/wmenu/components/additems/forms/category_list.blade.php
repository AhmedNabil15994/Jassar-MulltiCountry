@if(!$item)
    <div id="menu-item-url-wrap" style="max-height: 250px;overflow: auto;direction: ltr; margin-bottom: 26px;">
                    
        @include('catalog::dashboard.products.components.categories-tree.tree',
        ['selectedCats' => $item && isset($item->json_data['categories']) ? $item->json_data['categories'] : []])
    </div>
@else
{!! field('headermenus')->multiSelect('category_id','Categories', $categories->pluck('title','id')->toArray(),
                $item && isset($item->json_data['categories']) ? $item->json_data['categories'] : [],
                ['id' => 'edit_category_id']
            ) !!}
@endif

<ul class="nav nav-tabs">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <li class="@if($code == locale()) active @endif">
            <a data-toggle="tab" href="#category_list_{{$code}}_{{$item ? $item->id : ''}}">
                {{$code}}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <div class="tab-pane fade in {{ $code == locale() ? 'active' : '' }}" id="category_list_{{ $code }}_{{$item ? $item->id : ''}}">
            {!! field('headermenus')->text(
                'label[' . $code . ']',
                 'Label-' . $code,
                $item ? $item->getTranslation('label', $code): null,
                ['data-name' => 'label.' . $code]
            ) !!}
        </div>
    @endforeach
</div>

<input type="hidden" name="type" value="{{$type}}">
<input type="hidden" name="menu" value="{{$item ? $item->menu: request()->input("menu")}}">
{!! field('headermenus')->text("class", "css class",$item ? $item->class : null) !!}

<div>
    <label class="mt-radio" style="margin-right: 5px;margin-left: 5px;">
        <input type="radio" name="menu_type" data-name="type" value="nsted_menu"
         {{$item && isset($item->json_data['menu_type']) && $item->json_data['menu_type'] == 'nsted_menu' ? 'checked' : ''}}>
            Nsted menu
        <span></span>
    </label>

    <label class="mt-radio" style="margin-right: 5px;margin-left: 5px;">
        <input type="radio" name="menu_type" data-name="type" value="mega_menu"
        {{$item && isset($item->json_data['menu_type']) && $item->json_data['menu_type'] == 'mega_menu' ? 'checked' : ''}}>
            Mega menu
        <span></span>
    </label>
    <span class="help-block" style=""></span>
</div>