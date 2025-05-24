<ul class="nav nav-tabs">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <li class="@if($code == locale()) active @endif">
            <a data-toggle="tab" href="#first_{{$code}}_{{$item ? $item->id : ''}}">
                {{$code}}
            </a>
        </li>
    @endforeach
</ul>

<div class="tab-content">
    @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
        <div class="tab-pane fade in {{ $code == locale() ? 'active' : '' }}" id="first_{{ $code }}_{{$item ? $item->id : ''}}">
            {!! field('headermenus')->text(
                'label[' . $code . ']',
                 'Label-' . $code,
                 $item ? $item->getTranslation('label', $code): null,
                ['data-name' => 'label.' . $code]
            ) !!}
            {!! field('headermenus')->text("url[{$code}]", "URL-{$code}",$item ? $item->getTranslation('link', $code): null,['data-name' => 'url.' . $code]) !!}
        </div>
    @endforeach
</div>

<input type="hidden" name="type" value="{{$item ? $item->type : $type}}">
<input type="hidden" name="menu" value="{{$item ? $item->menu: request()->input("menu")}}">
{!! field('headermenus')->text("class", "css class",$item ? $item->class : null) !!}
