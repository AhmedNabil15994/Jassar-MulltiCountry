<script src="/dukaan/{{locale()}}/js/jquery.min.js"></script>
<script src="/dukaan/{{locale()}}/js/popper.min.js"></script>
<script src="/dukaan/{{locale()}}/js/bootstrap.min.js"></script>
<script src="/dukaan/{{locale()}}/js/wow.min.js"></script>
<script src="/dukaan/{{locale()}}/js/owl.carousel.min.js"></script>
<script src="/dukaan/{{locale()}}/js/jquery.parallax-scroll.js"></script>
<script src="/dukaan/{{locale()}}/js/select2.min.js"></script>
<script src="/dukaan/{{locale()}}/js/bootstrap-select.js"></script>
<script src="/dukaan/{{locale()}}/js/jquery.mousewheel.min.js"></script>
<script src="/dukaan/{{locale()}}/js/jquery.mCustomScrollbar.js"></script>
<script src="/dukaan/{{locale()}}/js/smoothproducts.min.js"></script>
<script src="/dukaan/{{locale()}}/js/jQuery.print.min.js"></script>
<script src="/dukaan/{{locale()}}/js/custom-{{locale()}}.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/js/toastr.min.js"></script>
<script src="/dukaan/js/main.js"></script>

{{-- Start - Bind Js Code From Dashboard Daynamic --}}
{!! setting('custom_codes.js_before_body') ?? null !!}
{{-- End - Bind Js Code From Dashboard Daynamic --}}

<script  src="/dukaan/js/vue@3.2.40/dist/vue.global.js"></script>
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<x-dukaan-scripts/>

<script src="/dukaan/{{locale()}}/js/jquery-ui.min.js"></script>
<script src="/dukaan/{{locale()}}/js/script-{{locale()}}.js"></script>
@stack('scripts')
<script>
    $("#datepicker").datepicker({
        minDate: 0,
    });
    $('#datepicker').datepicker('setDate', new Date());
    $('#datepicker').change(function() {
        startDate = $(this).datepicker('getDate');
    })
</script>
