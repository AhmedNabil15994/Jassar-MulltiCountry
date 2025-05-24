<ul class="nav nav-tabs">
    @foreach (array_keys(config('laravellocalization.supportedLocales')) as $code)
        <li class="@if ($code==locale()) active @endif">
            <a data-toggle="tab"
               href="#first_{{ $code }}">{{ __('catalog::dashboard.products.form.tabs.input_lang', ['lang' => $code]) }}</a>
        </li>
    @endforeach
</ul>

<div class="tab-content">
  @foreach (config('laravellocalization.supportedLocales') as $code => $lang)
  <div class="tab-pane fade in {{ $code == locale() ? 'active' : '' }}" id="first_{{ $code }}">
    {!! field()->text(
    'title[' . $code . ']',
    __('package::dashboard.packages.form.title') . '-' . $code,
    $model ? $model->getTranslation('title', $code) : '',
    ['data-name' => 'title.' . $code],
    ) !!}
    {!! field()->ckEditor5(
    'description[' . $code . ']',
    __('package::dashboard.packages.form.description') . '-' . $code,
    $model ? $model->getTranslation('description', $code) : '',
    ['data-name' => 'description.' . $code,'class' => 'form-control'],
    ) !!}
  </div>
  @endforeach
</div>


<div class="form-group">
    <label class="col-md-2">
        {{ __('package::dashboard.packages.form.country') }}
    </label>
    <div class="col-md-9">
        <select name="country_id" class="form-control select2">
            @foreach($countries as $code => $country)
                @if(((auth()->user()->is_internal && in_array($country->id , setting('supported_countries'))) ||
                    (!auth()->user()->is_internal && in_array($country->id , auth()->user()->setting->countries ?? []))))
                    <option data-currency="{{$country->currency->code}}" value="{{$country->id}}" {{$model->country_id == $country->id ? 'selected' : ''}}>{{$country->title}}</option>
                @endif
            @endforeach
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-md-2">
        {{ __('package::dashboard.packages.form.products') }}
    </label>
    <div class="col-md-9">
        <select name="settings[products][]" class="form-control select2">
            @if($model && $model->country_id)
            @foreach ($products as $code => $product)
                <option value="{{ $product->id }}" {{$model && $model->settings->products ? (in_array($product->id,$model->settings->products) ? 'selected' : '') : '' }} data-price="{{$product->price}}">
                    {{ $product->title }}
                </option>
            @endforeach
            @endif
        </select>
    </div>
</div>

<div class="form-group">
    <label class="col-md-2">
        {{ __('package::dashboard.offers.form.free_products') }}
    </label>
    <div class="col-md-9">
        <select name="settings[free_products][]" class="form-control select2" multiple="">
            @if($model && $model->country_id)
                @foreach ($products as $code => $product)
                    <option value="{{ $product->id }}" {{$model && $model->settings->free_products ? (in_array($product->id,$model->settings->free_products) ? 'selected' : '') : '' }} data-price="{{$product->price}}">
                        {{ $product->title }}
                    </option>
                @endforeach
            @endif
        </select>
    </div>
</div>



{!! field()->number('qty', __('package::dashboard.offers.form.prices.qty')) !!}
{!! field()->number('free_qty', __('package::dashboard.offers.form.prices.free_qty')) !!}
{!! field()->checkBox('status', __('package::dashboard.packages.form.status')) !!}
{!! field()->file('image', __('package::dashboard.packages.form.image'),$model ? $model->getFirstMediaUrl('images') : '') !!}

@if ($model && $model->trashed())
{!! field()->checkBox('trash_restore', __('apps::dashboard.datatable.form.restore')) !!}
@endif



@push('scripts')
<script>
  $('#is_free').bootstrapSwitch({
      onInit:function (e, state){
        if($(this).is(':checked')){
            $('.prices').hide()
        }
      },
      onSwitchChange: function (e, state) {
          $('.prices').toggle()
          if($(this).is(':checked')){
              $('.prices input[name="price"]').val(0)
          }
      }
  });
  let products = [];

  $('select[name="country_id"]').on('change',function (){
      let country_id = $(this).val();
      $.ajax({
         type: "GET",
         url: "{{route('dashboard.products.get-products-ajax')}}",
         data:{
             'country_id': country_id,
         },
          success:function (data){
              let x = '';
              $.each(data.products ,function (index,item){
                 x+="<option value='"+item.id+"'>"+item.title+"</option>";
                 products.push({
                     id: item.id,
                     title: item.title,
                 })
              });

              $('select[name="settings[products][]"]').empty().select2('destroy').append(x).select2()
              $('select[name="settings[free_products][]"]').empty().select2('destroy').append(x).select2()
          }
      });
  });

  $('select[name="settings[products][]"]').on('change',function (){
      let ids = [];
      if($(this).val()){
          $.each($(this).children('option:selected'),function (index,item){
                ids.push(parseInt($(item).val()))
          });
          let x = '';
          $.each(products ,function (index,product){
              if(!ids.includes(product.id)){
                  x+="<option value='"+product.id+"'>"+product.title+"</option>";
              }
          });
          $('select[name="settings[free_products][]"]').empty().select2('destroy').append(x).select2();
      }
  });


</script>
@endpush
