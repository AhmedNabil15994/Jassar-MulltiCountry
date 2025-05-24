<ul class="nav nav-tabs">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <li class="@if($code == locale()) active @endif">
            <a data-toggle="tab" href="#the_page_{{$code}}_{{$item ? $item->id : ''}}">
                {{$code}}
            </a>
        </li>
    @endforeach
</ul>
<input type="hidden" name="type" value="{{$type}}">
<input type="hidden" name="menu" value="{{$item ? $item->menu: request()->input("menu")}}">

{!! field('headermenus')->select('page_id', 'Page' , $pages->pluck('title','id')->toArray() ,$item ? $item->itemable_id : null) !!}
<div class="tab-content">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <div class="tab-pane fade in {{ $code == locale() ? 'active' : '' }}" id="the_page_{{ $code }}_{{$item ? $item->id : ''}}">
            {!! field('headermenus')->text(
                'label[' . $code . ']',
                 'Label-' . $code,
                $item ? $item->getTranslation('label', $code): null,
                ['data-name' => 'label.' . $code],
            ) !!}
        </div>
    @endforeach
</div>
{!! field('headermenus')->text("class", "css class",$item ? $item->class : null) !!}
