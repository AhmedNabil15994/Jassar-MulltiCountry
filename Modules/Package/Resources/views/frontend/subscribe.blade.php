@extends('apps::frontend.layouts.app')
@section( 'title',$package->title)
@section( 'content')
<div class="inner-page">
  <div class="container">
    <form action="{{ route('frontend.packages.subscribe',$packagePrice->id) }}" method="post" id="updateForm">
      @csrf
      <input type="hidden" name="start_date" value="{{ date('Y-m-d') }}">
      <div class="row cart-page">

        <div class="col-md-4">
          <div class="order-summery">
            <h2>{{ __('Your subscribtion Summery') }}</h2>
            <div class="cart-summery-content">
              <div class="cart-list">
                <div class="cart-item">
                  <div class="d-flex">
                    <div class="img-block">
                      <img src="{{ asset($package->getFirstMediaUrl('images')) }}" alt="">
                    </div>
                    <div>
                      <h3>{{ $package->title }}</h3>
                      {!! $packagePrice->active_price['price_html'] !!}
                    </div>
                  </div>
                </div>
              </div>
              <ul class="order-total">
                <li class="total-amount">
                  <div id="loaderCouponDiv" style="display: none;margin: 0px 137px;" class="row" >
{{--                    <x-front-sppiner-loader/>--}}
                  </div>
                  <div id="couponForm">
                    <div class="d-flex promo-code align-items-center justify-content-between">
                        <div class="d-flex mb-20 promo-code align-items-center">
                          <div id="coupon_input">
                            <span class="d-inline-block right-side flex-1 " >
                              <span class="d-inline-block right-side flex-1 form-group" >
                                <input type="text" id="txtCouponCode" name="coupon_code" class="form-control-sm" style="border: 0.2px solid #d5dae2;"
                                    placeholder=" {{__('enter discount number')}}">
                            </span>
                            <span class="d-inline-block left-side">
                              <button class="btn btn-success" id="btnCheckCoupon" style="margin: 10px;" type="submit">{{__('Save')}}</button>
                            </span>
                          </div>
                          <div id="coupon_success" class="coupon_success">

                          </div>

                          <span class="coupon_success d-inline-block left-side remove" style="display:none !important;cursor: pointer;" onclick="removeCoupon()" title="ازالة الكوبون"><i
                                  class="ti-close"></i></span>
                        </div>
                    </div>
                </div>
                </li>
              </ul>
              <ul class="order-total">
                <li class="total-amount">
                  <h5>{{ __('Total amount') }}</h5>
                  {!! $packagePrice->active_price['price_html'] !!}
                </li>
              </ul>
              <button class="btn theme-btn d-block mt-20 submit" id="submit" type="submit">
                <span class="text">{{ __('subscribe now') }}</span>
                <span class="btn_loader" style="display: none">
{{--                  <x-front-btn-loader/>--}}
                </span>
              </button>
            </div>
          </div>
        </div>
        <div class="col-md-8">
          <div class="checkout-block">
            <div id="result" style="display: none"></div>
            <div class="head">
                <h3>{{__('Address')}}</h3>
            </div>

            <div class="row" id="theApp">

                <div class="form-group col-md-6">
                    <select id="country_id"  class="normalSelect2 form-control" style="padding: 0.2rem 1rem;" name="country_id">
                        <option value="">{{__('select country')}}..</option>
                        <option v-for="country in countries" :value="country.id">@{{country.title}}</option>
                    </select>
                </div>

                <div class="form-group col-md-6" v-if="!get_cities_loader">
                    <select id="state_id"  name="state_id"  class="form-control" style="padding: 0.2rem 1rem;">
                      <option value="">{{__('select state')}}..</option>
                        <optgroup v-for="city in cities" :label="city.title" :data-select2-id="city.id">
                            <option v-for="state in city.states" :value="state.id" :data-select2-id="state.id">@{{state.title}}</option>
                        </optgroup>
                    </select>
                </div>

                <div class="form-group col-md-6" v-else>
                    <div class="row">
{{--                        <x-front-sppiner-loader/>--}}
                    </div>
                </div>

                <div class="form-group col-md-6">
                    <input type="text" name="street" class="form-control" name="text" placeholder="{{__('Streat')}}">
                </div>

                <div class="form-group col-md-6">
                    <input type="text" name="block" class="form-control" name="text" placeholder="{{__('Block No.')}}">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" name="building" placeholder="{{__('Building No., Floor, Flat No.')}}">
                </div>

                <div class="form-group">
                  <textarea name="note" class="form-control" placeholder="{{__('Note')}}"></textarea>
                </div>
            </div>

            {{-- ********************************* --}}
            <div class="head">
              <h3>{{ __('subscribtion Date') }}</h3>
            </div>
            <div class="choose-time active">
              <div class="shipped-address">
                <h5 class="address-name">{{ __('Choose Date') }}</h5>
                <div id="datepicker"></div>
              </div>
            </div>
          </div>
        </div>

      </div>
    </form>
  </div>
</div>
@endsection
@push('js')
<script>



const { createApp } = Vue;
 let app = createApp({
        data() {
            return {
              countries: @json($countries),
              get_cities_loader: false,
              cities: [],
            };
        },
        mounted() {
          $('.normalSelect2').select2();
          $(document.body).on("change","#country_id",function(){
            app.getCities(this.value);
          });
        },
        methods: {
          getCities(countryId,state_id = null){
            this.get_cities_loader = true;
            axios.get("#",{params: {country_id: countryId}}).then(response => {

                this.cities = response.data;
                this.get_cities_loader = false;

            }).finally(function() {

                $('#country_id').val(countryId);
                if(state_id)
                    $('#state_id').val(state_id);

            });
          },
        },
    }).mount('#theApp');

  $("#datepicker").change(function () {
    $('[name="start_date"]').val($(this).val())
  });


  $(document).on('click', '#btnCheckCoupon', function (e) {

    var action = '{{route('frontend.check_coupon',$packagePrice->id)}}';
    var code = $('#txtCouponCode').val();

    e.preventDefault();

    if (code !== '') {

        $('#loaderCouponDiv').show();
        $('#couponForm').hide();

        $.ajax({
            method: "get",
            url: action,
            data: {
                "code": code,
            },
            beforeSend: function () {
            },
            success: function (data) {
                $('#coupon_input').hide();
                $('#coupon_success').html(data.data.message)
                $('.coupon_success').show();
            },
            error: function (data) {
                displayErrorsMsg(data);
            },
            complete: function (data) {

                $('#loaderCouponDiv').hide();
                $('#couponForm').show();
                var getJSON = $.parseJSON(data.responseText);
                if (getJSON.data) {
                    showCouponContainer(getJSON.data.coupon_value, getJSON.data.total);
                }

            },
        });
    } else {
        $('#txtCouponCode').focus();
    }

  });



  function removeCoupon() {
    $('#txtCouponCode').val('');
    $('#coupon_success').text('');
    $('.coupon_success').text('').hide();
    $('#coupon_input').show();
  }


  function displaySuccessMsg(data) {
        Swal.fire({
            position: 'center',
            icon: 'success',
            title: data,
            showConfirmButton: false,
            timer: 2000
        });
    }



    function displayErrorsMsg(data, icon = 'error') {
        // console.log('errors::', $.parseJSON(data.responseText));

        var getJSON = $.parseJSON(data.responseText);

        var output = '<ul>';

        if (typeof getJSON.errors == 'string') {
            output += "<li>" + getJSON.errors + "</li>";
        } else {
            // if (getJSON.errors.hasOwnProperty("code")) {
            //     output += "<li>" + getJSON.errors['code'][0] + "</li>";
            // } else {

            for (var error in getJSON.errors) {
                output += "<li>" + getJSON.errors[error] + "</li>";
            }

            // }
        }

        output += '</ul>';

        var wrapper = document.createElement('div');
        wrapper.innerHTML = output;
        Swal.fire({
            position: 'center',
            icon: icon,
            title: wrapper
        });
    }
</script>
@endpush
