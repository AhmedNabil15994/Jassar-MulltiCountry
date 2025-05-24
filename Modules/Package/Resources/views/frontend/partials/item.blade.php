{{--<div class="product-block">--}}
{{--  <a href="{{ route('frontend.packages.show',$package->id) }}" class="img-block">--}}
{{--    <img class="img-fluid" src="{{$package->getFirstMediaUrl('images')}}" alt="" />--}}
{{--  </a>--}}
{{--  <div class="content-block">--}}
{{--    <a class="pro-name" href="{{ route('frontend.packages.show',$package->id) }}">{{ $package->title }}</a>--}}
{{--    @if($package->is_free)--}}
{{--      <div class="form-group" style="margin-bottom: 0px">--}}
{{--          <select onchange="changeItemData(this)" style="height:36px;line-height: 26px;padding: 0rem 1rem;" class="select2_package form-control">--}}

{{--            <option value="">{{__('select Duration')}}</option>--}}
{{--            @foreach($package->prices as $priceItem)--}}
{{--              <option {{$loop->first ? 'selected' : ''}} value="{{$package->id}}" data-item="{{$priceItem}}">{{$priceItem->subscribe_duration_desc}}</option>--}}
{{--            @endforeach--}}
{{--          </select>--}}
{{--      </div>--}}
{{--      @php $packagePrice = $package->prices()->first(); @endphp--}}
{{--      <div class="price_data" style="display:{{$packagePrice ? 'block':'none'}};margin-top: 21px;">--}}
{{--        <div class="block">--}}
{{--          <h4 class="inner-title"> {{optional($packagePrice)->subscribe_duration_desc}}</h4>--}}
{{--        </div>--}}
{{--        <div class="the_price">{!! $packagePrice ? $packagePrice->active_price['price_html'] : '' !!}</div>--}}

{{--        <a class="btn addto-cart theme-btn" href="{{ $packagePrice ? route('frontend.packages.subscribeForm',$packagePrice->id) : '#' }}">--}}
{{--          {{ __('Subscribe now') }}--}}
{{--        </a>--}}
{{--      </div>--}}
{{--    @endif--}}
{{--  </div>--}}
{{--</div>--}}



<a href="{{ route('frontend.packages.show',$package->id) }}">
  <div class="course-one__single">
    <div class="course-one__image">
      <img src="{{$package->getFirstMediaUrl('images')}}" alt="{{ $package->title }}">
    </div>
    <div class="course-one__content">
      @if($package->is_free)
      <div class="course-one__category"> {{ __('free package') }} </div>
      @endif
      <h2 class="course-one__title title-name">{{ $package->title }}</h2>
      <p>{{ $package->description }} </p>
      @php $packagePrice = $package->prices()->first(); @endphp
        <div class="price_data" style="display:{{$packagePrice ? 'block':'none'}};margin-top: 21px;">
          <div class="form-group" style="margin-bottom: 0px">
            <select style="height:36px;line-height: 26px;padding: 0rem 1rem;" class="select2_package form-control">

              <option value="">{{__('select offer')}}</option>
              @foreach($package->prices as $priceItem)
                <option {{$loop->first ? 'selected' : ''}} value="{{$priceItem->id}}" data-item="{{$priceItem->active_price['price_html']}}">{{$priceItem->subscribe_duration_desc}}</option>
              @endforeach
            </select>
          </div>
{{--          <div class="block">--}}
{{--            <h4 class="inner-title"> {{optional($packagePrice)->subscribe_duration_desc}}</h4>--}}
{{--          </div>--}}
          <div class="the_price mt-15">{!! $packagePrice ? $packagePrice->active_price['price_html'] : '' !!}</div>
          @if(!auth()->check()||(auth()->id() && !auth()->user()->can('dashboard_access')))
          <a class="btn addto-cart theme-btn" href="{{ $packagePrice ? route('frontend.cart.add',['id'=>$packagePrice->id,'type'=>'package']) : '#' }}">
            {{ __('Subscribe now') }}
          </a>
          @endif
        </div>
    </div>
  </div>
</a>

