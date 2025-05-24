<div class="d-flex align-items-center justify-content-between">
    <div class="order-number">@lang('front.Order Number'):  <a class="text-muted" href="index.php?page=order-details">#@{{order.id}}</a></div>
    <span class="order-status delivered" :style="'background:'+order.order_status.color">@{{order.order_status.title}}</span>
</div>
<div class="d-flex align-items-end justify-content-between">
    <ul class="options">
        <li><i class="ti-layers-alt"></i> @{{order.products}} @lang('front.items')</li>
        <li><i class="ti-location-pin"></i> @{{order.address.city_name}},  @{{order.address.country.title}}</li>
        <li><i class="ti-time"></i> @{{order.created_at}}</li>
    </ul>
        <a class="btn theme-btn-sec" :href="order.show_route">@lang('front.View Details')</a>
</div>