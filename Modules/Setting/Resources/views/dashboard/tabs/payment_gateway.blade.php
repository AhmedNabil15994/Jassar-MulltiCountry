<div class="tab-pane fade" id="payment_gateway">

    <ul class="nav nav-tabs">
        <li class="active">
            <a data-toggle="tab" href="#cache">Cash</a>
        </li>
        <li>
            <a data-toggle="tab" href="#UPayment">UPayment</a>
        </li>
        <li>
            <a data-toggle="tab" href="#Tap">Tap</a>
        </li>
        <li>
            <a data-toggle="tab" href="#Myfatoorah">Myfatoorah</a>
        </li>
    </ul>

    <div class="tab-content">
        <div id="cache" class="tab-pane fade in active">
            @include('setting::dashboard.tabs.gatways.cache')
        </div>
        <div id="UPayment" class="tab-pane fade">
            @include('setting::dashboard.tabs.gatways.upayment')
        </div>

        <div id="Tap" class="tab-pane fade">
            @include('setting::dashboard.tabs.gatways.tab')
        </div>

        <div id="Myfatoorah" class="tab-pane fade">
            @include('setting::dashboard.tabs.gatways.fatoorah')
        </div>
    </div>
</div>
