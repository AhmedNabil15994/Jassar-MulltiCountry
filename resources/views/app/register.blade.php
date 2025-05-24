<!DOCTYPE html>
<html lang="ar" dir="rtl">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Toucart | Register</title>
        <meta name="description" content="">
        <link rel="icon" href="{{ asset('app/images/logo.png') }}">
        <link rel="stylesheet" href="{{ asset('app/css/themify-icons.css') }}">
        <link rel="stylesheet" href="{{ asset('app/css/linearicons.min.css') }}">
        <link rel="stylesheet" href="{{ asset('app/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('app/css/owl.carousel.min.css') }}">
        <link rel="stylesheet" href="{{ asset('app/css/bootstrap-select.min.css') }}">
        <link rel="stylesheet" href="{{ asset('app/css/sweetalert.css') }}">
        <link rel="stylesheet" href="{{ asset('app/css/style.css') }}">

        {!! htmlScriptTagJsApi([
            'form_id' => 'register-form'
        ]) !!}
    </head>
    <body>
        <div class="wrapper">
            <img class="top-shape" src="{{ asset('app/images/top shape.png') }}" alt="" />
            <ul class="animate-particle">
                <li><img src="{{ asset('app/images/ta1.png') }}" alt></li>
                <li><img src="{{ asset('app/images/ta4.png') }}" alt></li>
                <li><img src="{{ asset('app/images/ta2.png') }}" alt></li>
                <li><img src="{{ asset('app/images/ta3.png') }}" alt></li>
                <li><img src="{{ asset('app/images/cm1.png') }}" alt></li>
                <li class="bubble"></li>
            </ul>
            <div class="container position-relative">
                <div class="row">
                    <div class="col-md-6">
                        <a href="#" class="head"><img class="img-fluid" src="{{ asset('app/images/logo.png') }}" alt></a>
                        <div class="form-container">
                            <h2>مرحبا فيك في <b>متجر توكان</b></h2>

                            @if ($errors->any())
                                <div class="finderror">
                                    <p class="error" style="display: block;">يرجي التأكد من بعض البيانات.</p>
                                </div>
                                <br>
                            @endif

                            {{-- <form id="register-form" action="{{ route('app.register.post') }}" method="post"> --}}
                            <form id="register-form" action="/api/register" method="post">
                                @csrf
                                <div class="form-group @error('name') finderror @enderror" data-register-name>
                                    <label>اسم المتجر <span class="required">*</span></label>
                                    <div class="form-withinput">
                                        <i class="lnr lnr-store"></i>
                                        <input required name="name" value="{{ old('name') }}" type="text" class="form-control" placeholder="متجر..">
                                    </div>

                                    @error('name')
                                    <p class="error">{{ $message }}</p>
                                    @else
                                    <p class="error"></p>
                                    @enderror
                                </div>
                                <div class="form-group @error('subdomain') finderror @enderror" data-register-subdomain>
                                    <label>رابط المتجر <span class="required">*</span></label>
                                    <div class="d-flex domain-input">
                                        <span class="domain-inputname">.tocaan.com</span>
                                        <div class="form-withinput flex-1">
                                            <i class="lnr lnr-link"></i>
                                            <input required id="subdomain" name="subdomain" value="{{ old('subdomain') }}" type="text" pattern="[a-zA-Z]+" class="form-control text-left" placeholder="store-name"
                                                   oninvalid="this.setCustomValidity('يجب ادخال حروف انجليزية فقط بدون مسافات او ارقام او حروف عربية')"
                                                   onchange="try {
                  setCustomValidity('')
              } catch (e) {
              }"
                                                   oninput="setCustomValidity(' ')"/>
                                        </div>
                                    </div>
                                    <p class="input-desc">سيكون هو رابط المتجر الذي يمكن للعملاء الدخول عليه للطلب. يجب استخدام الأحرف الانجليزية والارقام</p>

                                    @error('subdomain')
                                    <p class="error">{{ $message }}</p>
                                    @else
                                    <p class="error"></p>
                                    @enderror
                                </div>
                                <div class="form-group @error('account_type_id') finderror @enderror" data-register-account_type_id>
                                    <label> نوع النشاط <span class="required">*</span></label>
                                    <select id="account_type_id" name="account_type_id" class="selectpicker form-control" required>
                                        <option value="" data-content=" اختر نوع النشاط"></option>
                                        @foreach ($account_types as $type)
                                        <option data-content="<i class='lnr lnr-user'></i> {{ $type->name }}" value="{{ $type->id }}"{{ (old('account_type_id') == $type->id || request()->query('type') === $type->slug) ? ' selected' : ''}}></option>
                                        @endforeach
                                        {{-- <option data-content="<i class='lnr lnr-user'></i> فرد" value="individual"></option>
                                        <option data-content="<i class='lnr lnr-graduation-hat'></i> مؤسسة تعليمية" value="educational"></option>
                                        <option data-content="<i class='lnr lnr-users'></i> شركة" value="organization"></option>
                                        <option data-content="<i class='lnr lnr-heart-pulse'></i> مؤسسة خيرية" value="non-profit"></option> --}}
                                    </select>

                                    @error('account_type_id')
                                    <p class="error">{{ $message }}</p>
                                    @else
                                    <p class="error"></p>
                                    @enderror
                                </div>
                                
                                @isset($plans)
                                <div class="form-group @error('plan_id') finderror @enderror" data-register-plan_id>
                                    <label> نوع الباقة <span class="required">*</span></label>
                                    <select id="plan_id" name="plan_id" class="selectpicker form-control" required disabled>
                                        <option value="" data-content=" اختر نوع الباقة"></option>
                                        @foreach ($plans as $plan)
                                        <option data-content=" {{ $plan->name }} -  <b class='price'>{{ number_format($plan->price, 2, '.', ',') }} دينار كويتي</b>" value="{{ $plan->id }}"{{ old('plan_id') == $plan->id ? ' selected' : ''}}></option>
                                        @endforeach
                                        {{-- <option data-content=" باقة اسبوعية -  <b class='price'>50 دينار كويتي</b>" value="plan-a"></option>
                                        <option data-content=" باقة شهرية -  <b class='price'>70 دينار كويتي</b>" value="plan-b"></option>
                                        <option data-content=" باقة سنوية -  <b class='price'>100 دينار كويتي</b>" value="plan-c"></option> --}}
                                    </select>

                                    @error('plan_id')
                                    <p class="error">{{ $message }}</p>
                                    @else
                                    <p class="error"></p>
                                    @enderror
                                </div>
                                @endisset
                                <div class="row">
                                    <!--                                    <div class="col-md-6">
                                                                            <div class="form-group">
                                                                                <label>  مدير المتجر <span class="required">*</span></label>
                                                                                <div class="form-withinput">
                                                                                    <i class="lnr lnr-user"></i>
                                                                                    <input type="email" class="form-control" placeholder="info@market.com" />
                                                                                </div>
                                                                            </div>
                                                                        </div>-->
                                    <div class="col-md-12">
                                        <div class="form-group @error('phone') finderror @enderror" data-register-phone>
                                            <label>  رقم الجوال <span class="required">*</span></label>
                                            <div class="form-withinput select-phone d-flex">
                                                <i class="ti-mobile"></i>
                                                <input dir="ltr" required name="phone" value="{{ old('phone') }}" type="text" class="form-control" placeholder="25698744" />
                                                <select name="phone_prefix" class="selectpicker form-control" required>
                                                    <option data-content="<img class='flag' src='{{ asset('app/images/flags/kw.svg') }}'> +965" value="+965"></option>
                                                    <option data-content="<img class='flag' src='{{ asset('app/images/flags/eg.svg') }}'> +20" value="+20"></option>
                                                    <option data-content="<img class='flag' src='{{ asset('app/images/flags/sa.svg') }}'> +966" value="+966"></option>
                                                    <option data-content="<img class='flag' src='{{ asset('app/images/flags/ae.svg') }}'> +971" value="+971"></option>
                                                    <option data-content="<img class='flag' src='{{ asset('app/images/flags/qa.svg') }}'> +974" value="+974"></option>
                                                    <option data-content="<img class='flag' src='{{ asset('app/images/flags/bh.svg') }}'> +973" value="+973"></option>
                                                    {{-- <option data-content="<img class='flag' src='{{ asset('app/images/flags/us.svg') }}'> +1" value="+1"></option> --}}
                                                    {{-- <option data-content="<img class='flag' src='{{ asset('app/images/flags/ca.svg') }}'> +1" value="+1"></option> --}}
                                                    {{-- <option data-content="<img class='flag' src='{{ asset('app/images/flags/ba.svg') }}'> + 070"></option> --}}
                                                    {{-- <option data-content="<img class='flag' src='{{ asset('app/images/flags/fr.svg') }}'> + 080"></option> --}}
                                                </select>
                                            </div>

                                            @error('phone')
                                            <p class="error">{{ $message }}</p>
                                            @else
                                            <p class="error"></p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group @error('email') finderror @enderror" data-register-email>
                                            <label> البريد الالكتروني <span class="required">*</span></label>
                                            <div class="form-withinput">
                                                <i class="ti-email"></i>
                                                <input name="email" value="{{ old('email') }}" type="email" class="form-control" required placeholder="info@market.com" />
                                            </div>

                                            @error('email')
                                            <p class="error">{{ $message }}</p>
                                            @else
                                            <p class="error"></p>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group @error('password') finderror @enderror" data-register-password>
                                            <label>  كلمة المرور <span class="required">*</span></label>
                                            <div class="form-withinput">
                                                <i class="ti-key"></i>
                                                <input name="password" type="password" class="form-control" required placeholder="********" />
                                            </div>

                                            @error('password')
                                            <p class="error">{{ $message }}</p>
                                            @else
                                            <p class="error"></p>
                                            @enderror
                                        </div>
                                    </div>
                                </div>
                                {{-- <div class="form-group have-copoun">
                                    <label>لديك كوبون دعوة؟</label>
                                    <div class="form-group @error('promo_code') finderror @enderror">
                                        <div class="d-flex domain-input">
                                            <div class="flex-1">
                                                <input name="promo_code" type="text" class="form-control" placeholder="كوبون الدعوة (اختياري)" />
                                            </div>
                                            <span class="domain-inputname confirm-btn">تطبيق</span>
                                        </div>
                                        <p class="input-desc"> الرجاء الضغط على زر الكوبون للحصول على الخصم</p>

                                        @error('promo_code')
                                        <p class="error">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div> --}}
                                <div class="form-group">
                                    <div class="form-group">
                                        <p class="input-desc">بالتسجيل ف انا اوافق على كافة <a href="#">الشروط و الاحكام</a> و <a href="#">سياسة الخصوصية</a></p>
                                    </div>
                                </div>
                                {{-- <div class="form-group @error('g-recaptcha-response') finderror @enderror" data-register-g-recaptcha-response>
                                    {!! htmlFormSnippet() !!}
                                    <p class="error"></p>
                                </div> --}}

                                {{-- <button class="btn-submit btn-block" type="submit"><span>انشاء المتجر</span></button> --}}

                                @include('apps::dashboard.layouts._ajax-msg')
                                <center>

                                    <div class="spinner-border" role="status" id ="btn-spinner" style="display:none;color: #1574F6;">
                                        <span class="sr-only"></span>
                                    </div>
                                </center>
                                <div id="btn-text">

                                {!! htmlFormButton('<span>انشاء المتجر</span>
                                ', [
                                    'id' => 'submit',
                                    'class' => 'btn-submit btn-block',
                                    'data-callback' => 'my_recaptcha_callback',
                                ]) !!}
                                </div>
                            </form>
                        </div>
                        <p class="copyright">All right received © <a href="https://tocaan.com">Tocaan</a></p>
                    </div>
                    <div class="col-md-6 testi">
                        <div class="testinomial-container">
                            <div class="testinomials owl-carousel">
                                <div class="item text-center">
                                    <div class="testinomial-content">
                                        <p>لقد عانيت من الاسعار المرتفعة التي تعوق اطلاق أي متجر في بدايته، لكن سلة وفرت لي كل شئ في مكان واحد حتى وفرت علي ان استعين بمحاسب فهي تعطي تقارير كاملة عن المبيعات ونسبة الربح اليومية والشهرية والسنوية بنقرة واحدة</p>
                                        <h3>متجر سوق</h3>
                                    </div>
                                    <div class="img-block">
                                        <img class="img-fluid" src="{{ asset('app/images/1.jpg') }}" alt=""/>
                                    </div>
                                </div>
                                <div class="item text-center">
                                    <div class="testinomial-content">
                                        <p>لقد عانيت من الاسعار المرتفعة التي تعوق اطلاق أي متجر في بدايته، لكن سلة وفرت لي كل شئ في مكان واحد حتى وفرت علي ان استعين بمحاسب فهي تعطي تقارير كاملة عن المبيعات ونسبة الربح اليومية والشهرية والسنوية بنقرة واحدة</p>
                                        <h3>متجر امازون</h3>
                                    </div>
                                    <div class="img-block">
                                        <img class="img-fluid" src="{{ asset('app/images/2.jpg') }}" alt=""/>
                                    </div>
                                </div>
                                <div class="item text-center">
                                    <div class="testinomial-content">
                                        <p>لقد عانيت من الاسعار المرتفعة التي تعوق اطلاق أي متجر في بدايته، لكن سلة وفرت لي كل شئ في مكان واحد حتى وفرت علي ان استعين بمحاسب فهي تعطي تقارير كاملة عن المبيعات ونسبة الربح اليومية والشهرية والسنوية بنقرة واحدة</p>
                                        <h3>متجر جوميا</h3>
                                    </div>
                                    <div class="img-block">
                                        <img class="img-fluid" src="{{ asset('app/images/3.jpg') }}" alt=""/>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Start JS FILES -->
        <script src="{{ asset('app/js/jquery.min.js') }}"></script>
        <script src="{{ asset('app/js/popper.min.js') }}"></script>
        <script src="{{ asset('app/js/bootstrap.min.js') }}"></script>
        <script src="{{ asset('app/js/owl.carousel.min.js') }}"></script>
        <script src="{{ asset('app/js/sweetalert.min.js') }}"></script>
        <script src="{{ asset('app/js/bootstrap-select.min.js') }}"></script>
        {{-- <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.1.5/dist/sweetalert2.all.min.js"></script> --}}
        <script>
            

        function displayMissing(data)
        {
            swal({
                            title: data.message,
                            text: "",
                            type: "error",
                            showConfirmButton: true,
                            showCancelButton: false,
                            closeOnConfirm: true,
                            closeOnCancel: false
            });
        }



        function redirect(data) {
            if (data['url'] && data['url'] != '') {
                var url = data['url'];

                if (url) {
                    if (data['blank'] && data['blank'] == true) {

                        window.open(url, '_blank');
                    } else {
                        window.location.replace(url);
                    }
                }
            }
        }
    // Alerts & Others
    function displayErrors(data) {

        var getJSON = $.parseJSON(data.responseText);

        // jQuery.each(getJSON.errors, function (index, value) {
        //     if (value.length !== 0) {
        //         $('[data-name="' + index + '"]').parent().addClass('has-error');
        //         $('[data-name="' + index + '"]').closest('.form-group').find('.help-block').html(value);
        //     }
        // });

        var output = "<div class='alert alert-danger'><ul>";
        for (var error in getJSON.errors) {
            output += "<li>" + getJSON.errors[error] + "</li>";
        }
        output += "</ul></div>";

        $('#result').slideDown('fast', function () {
            $('#result').html(output);
            $('.progress-info').hide();
            $('.progress-bar').width('0%');
        }).delay(5000).slideUp('slow');

    }

        // ADD FORM
        $('#register-form').on('submit', function (e) {
            e.preventDefault();

            var url = $(this).attr('action');
            var method = $(this).attr('method');
            $.ajax({

                url: url,
                type: method,
                dataType: 'JSON',
                data: new FormData(this),
                contentType: false,
                cache: false,
                processData: false,

                beforeSend: function () {
                    $('#submit').prop('disabled', true);
                    $('#btn-text').hide();
                    $('#btn-spinner').show();
                },
                success: function (data) {

                    $('#submit').prop('disabled', false);
                    $('#btn-text').show();
                    $('#btn-spinner').hide();

                    if (data[0] == true) {
                        redirect(data);

                        if(data['url'] && data['url'] != ''){
                            swal({
                                title: data.message,
                                text: "",
                                type: "success",
                                showConfirmButton: false,
                                showCancelButton: false,
                                closeOnConfirm: false,
                                closeOnCancel: false
                            })
                        }else{
                            swal({
                                title: data.message,
                                text: "",
                                type: "success",
                                showConfirmButton: true,
                                showCancelButton: false,
                                closeOnConfirm: true,
                                closeOnCancel: false
                            })
                        }

                        
                        resetForm();
                        resetErrors();
                    } else {
                        displayMissing(data);
                    }
                },
                error: function (data) {

                    $('#submit').prop('disabled', false);
                    $('#btn-text').show();
                    $('#btn-spinner').hide();
                    displayErrors(data);

                },
            });

        });

          (function ($) {
              'use strict';
              $('.testinomials').owlCarousel({
                  responsiveClass: true,
                  nav: false,
                  dots: true,
                  margin: 20,
                  animateOut: 'fadeOut',
                  autoplay: 3000,
                  autoplayTimeout: 3000,
                  smartSpeed: 3000,
                  paginationSpeed: 3000,
                  rtl: true,
                  items: 1,
                  responsive: {
                      0: {
                          items: 1,
                      },
                      600: {
                          items: 1
                      },
                      1200: {
                          items: 1
                      }
                  }
              });
          })(jQuery);
          // $('.have-copoun label').on("click", function (e) {
          //     $('.have-copoun .form-group').slideToggle("slow");
          // });
          // $(".have-copoun input").focus(function () {
          //     $('.have-copoun .input-desc').show("slow");
          // });
          $(".form-control").on("focus", function (e) {
              $(this).closest('.form-group').addClass("finderror");
          });
          $('.selectpicker').selectpicker({
              size: 5
          });
//            $('.btn-submit').on("click", function (e) {
//                swal({
//                    title: "",
//                    text: "تم اضافة متجرك بنجاح سيتم الاتصال بك فور الموافقة",
//                    type: "success",
//                    showCancelButton: false,
//                    closeOnConfirm: false,
//                    confirmButtonText: 'حسناً',
//                    animation: false,
//                    customClass: {
//                        popup: 'animated tada'
//                    }
//
//                })
//            });

        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-throttle-debounce/1.1/jquery.ba-throttle-debounce.min.js"></script>

        <script>
function my_recaptcha_callback (token) {
    var $this = $("#register-form");

    // swal("يرجي الإنتظار...", "", "info")
    swal({
        title: "يرجي الإنتظار...",
        text: "",
        type: "info",
        showConfirmButton: false,
        showCancelButton: false,
        closeOnConfirm: false,
        closeOnCancel: false
    })

    $.post($this.attr("action"), $this.serialize(), function(data, textStatus, xhr) {
        // ...
    })
    .done(function (data) {
        $this.find(".finderror").removeClass("finderror")
        $this.trigger("reset");

        if (data.url && data.url.length > 0) {
            setTimeout(function () {
                window.location = data.url;
            }, 5000);

            swal({
                title: "شكرا لك!",
                text: data.message,
                type: "success",
                showConfirmButton: false,
                showCancelButton: false,
                closeOnConfirm: false,
                closeOnCancel: false
            });
        } else {
            swal("شكرا لك!", data.message, "success");
        }
    })
    .fail(function (jqXHR, textStatus, error) {
        // swal("حدث خطأ ما...", "يرجي إعادة المحاولة.", "error");
        swal.close()

        $this.find(".finderror").removeClass("finderror")

        $.each(jqXHR.responseJSON.errors, function (index, item) {
            // console.log(index, "data-register-"+index, item)
            $("[data-register-" + index + "]")
                .addClass("finderror")
                // .closest(".form-group")
                .find(".error")
                .text(item.join())
        })
    })
    .always(function () {
        grecaptcha.reset()
    })
}

(function ($) {
    var $this = $("#register-form"),
        loading = false;

    // Validate domain name

    $("#subdomain").on("keypress", 
        $.debounce( 500, function () {
            if (loading) { return; }

            loading = true;

            $.post("/api/validate-subdomain", { subdomain: $("#subdomain").val() }, function(data, textStatus, xhr) {
                // ...
            })
            .done(function (data) {
                $this.find("[data-register-subdomain].finderror").removeClass("finderror")
            })
            .fail(function (jqXHR, textStatus, error) {
                $this.find("[data-register-subdomain].finderror").removeClass("finderror")

                $.each(jqXHR.responseJSON.errors, function (index, item) {
                    // console.log(index, "data-register-"+index, item)
                    $("[data-register-" + index + "]")
                        .addClass("finderror")
                        .find(".error")
                        .text(item.join())
                })
            })
            .always(function () {
                loading = false;
            })
        })
    )


    // Update plans based on the account type..

    if ($("#account_type_id").val().length > 0) {
        updatePlans();
    }

    $("#account_type_id").on("change", function (e) {
        updatePlans();
    });

    function updatePlans() {
        var $account_type_id = $("#account_type_id"),
            $plan_id = $("#plan_id");

        $plan_id
            .val('')
            .prop('disabled', true)
            .html('<option value="" data-content=" اختر نوع الباقة"></option>')
            .next('.btn')
            .addClass('disabled');

        if (!$account_type_id.val() || $account_type_id.val().length < 1) {
            $plan_id.selectpicker('refresh');
            return;
        }

        $.post("/api/plans", { account_type_id: $("#account_type_id").val() }, function(data, textStatus, xhr) {
            // ...
        })
        .done(function (data) {
            $plan_id
                .prop('disabled', false)
                .html('<option value="" data-content=" اختر نوع الباقة"></option>')
                .next('.btn')
                .removeClass('disabled');


            $.each(data, function(index, item) {
                $plan_id.append('<option data-content=" '+item.name+' -  <b class=price>' + item.price + ' دينار كويتي</b>" value="'+ item.id +'">'+ item.name +'</option>')

            })
        })
        .fail(function (jqXHR, textStatus, error) {
            swal('حدث خطأ ما...', 'يرجي إعادة المحاولة.', 'error');
        })
        .always(function () {
            $plan_id.selectpicker('refresh');
        })
    }

})(jQuery)
        </script>

        @if (session('message'))
            {{-- <p class="success">{{ session('message') }}</p> --}}
            <script>
                swal('شكرا لك!', '{{ session('message') }}', 'success');
            </script>
        @endif
        @if ($errors->any())
            {{-- <p class="success">{{ session('message') }}</p> --}}
            <script>
                swal('حدث خطأ ما...', 'يرجي التأكد من بعض البيانات.', 'error');
            </script>
        @endif
    </body>
</html>
