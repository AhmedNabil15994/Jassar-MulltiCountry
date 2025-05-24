<div class="content-sidebar">
  <button class="btn close-modal"><i class="ti-close"></i> {{ __('Close') }}</button>
  <form>
    <div class="panel-group" id="accordionNo">
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"><a data-bs-toggle="collapse" href="#collapseBrand" class="collapseWill">
              {{ __('categories') }} <span class="colles-block"></span> </a></h4>
        </div>
        <div id="collapseBrand" class="panel-collapse collapse show">
          <div class="panel-body">
            <div class="smoothscroll maxheight300">
              <div class="checkboxes one-in-row">
                @foreach($categories as $key => $category)
                <input {{ !empty(request()->get('categories')) &&
                in_array($category->id , (array)request()->get('categories')) ? 'checked': '' }}
                id="check-a" type="checkbox" name="categories[]" value="{{ $category->id }}">
                <label for="check-a"> {{ $category->title }} <span class="counter-num">({{ $category->packages_count }})</span></label>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h4 class="panel-title"><a class="collapseWill" data-bs-toggle="collapse" href="#collapsePrice">
              {{ __('Price') }} <span class="colles-block"></span> </a></h4>
        </div>
        <div id="collapsePrice" class="panel-collapse collapse show">
          <div class="panel-body">
            <div class="filter-price">
              <div class="filter-options-content">
                <div class="price_slider_wrapper">
                  <div data-label-reasult="" data-min="0" data-max="5000" data-unit="{{ __('kwd') }} " class="slider-range-price"
                    data-value-min="{{ !empty(request()->get('price_from')) ? request()->get('price_from') : '0.00' }}"
                    data-value-max="{{ !empty(request()->get('price_to')) ? request()->get('price_to') : '5000.00' }}">
                  </div>
                  <div class="price_slider_amount">
                    <div style="" class="price_label">
                      <span class="from">
                        {{ __('kwd') }}
                        {{ !empty(request()->get('price_from')) ? request()->get('price_from') : '0.00' }}
                      </span>
                      -<span class="to">
                        {{ __('kwd') }}
                        {{ !empty(request()->get('price_to')) ? request()->get('price_to') : '5000.00' }}
                      </span>
                    </div>
                  </div>
                  <div id="hiddenPriceSliderAmount">
                    <input type="hidden" id="priceFrom" name="price_from"
                      value="{{ !empty(request()->get('price_from')) ? request()->get('price_from') : '0.00' }}">
                    <input type="hidden" id="priceTo" name="price_to" value="{{ !empty(request()->get('price_to')) ? request()->get('price_to') : '5000.00' }}">
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <button type="submit" class="btn btn-them main-custom-btn" style="width: 100%; margin-bottom: 10px;background-color: black"
      href="{{route('frontend.packages.index' , !empty($category) ? $category->slug : null)}}">
      <i class="fa fa-filter"></i>
      {{__('filter')}}
    </button>

    <a class="btn theme-btn-sec btn-block" style="    width: 100%;"
      href="{{route('frontend.packages.index' , !empty($category) ? $category->slug : null)}}">
      {{__('clear')}}
    </a>
  </form>
</div>
@push('js')
<script>
  // Slider range price

        $('.slider-range-price').each(function () {
            var min = parseInt($(this).data('min'));
            var max = parseInt($(this).data('max'));
            var unit = $(this).data('unit');
            var value_min = parseInt($(this).data('value-min'));
            var value_max = parseInt($(this).data('value-max'));
            var label_reasult = $(this).data('label-reasult');
            var t = $(this);
            $(this).slider({
                range: true,
                min: min,
                max: max,
                values: [value_min, value_max],
                slide: function (event, ui) {
                    var result = label_reasult + " <span>" + unit + ui.values[0] + ' </span> - <span> ' + unit + ui.values[1] + '</span>';
                    t.closest('.price_slider_wrapper').find('.price_slider_amount').html(result);
                    /************* Edited By Mahmoud Elzohairy **************/
                    t.closest('.price_slider_wrapper').find('#hiddenPriceSliderAmount #priceFrom').val(ui.values[0]);
                    t.closest('.price_slider_wrapper').find('#hiddenPriceSliderAmount #priceTo').val(ui.values[1]);
                }
            });
        });
</script>

@endpush
