
@if(count($categories))
  <div class="row">
    <div class="portlet box grey-cascade">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-settings font-dark"></i>
          {{ __('categories') }}
        </div>
        <div class="tools">
          <a href="javascript:;"
            class="collapse"
            data-original-title=""
            title=""> </a>
        </div>
      </div>
      <div class="portlet-body" style="padding: 0px">
        <div id="filter_data_table">
          <div class="panel-body">
            <div class="row">
              @php $colorClasses = ['yellow-lemon','blue','green','red','yellow-casablanca']; @endphp
              @foreach($categories as $category)
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="margin-top: 10px">
                  <a class="dashboard-stat dashboard-stat-v2 {{$colorClasses[array_rand($colorClasses)]}}" href="#">
                    <div class="visual">
                    </div>
                    <div class="details">
                      <div class="number">
                        @if($subscriptionrelation == 'all')
                          <span data-counter="counterup" data-value="{{$category->subscriptions->count()}}">0</span>
                        @else
                          <span data-counter="counterup" data-value="{{$category->to_day_subscriptions->count()}}">0</span>
                        @endif
                      </div>
                      <div class="desc">{{$category->title}}</div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif

@if(count($packages))
  <div class="row">
    <div class="portlet box grey-cascade">
      <div class="portlet-title">
        <div class="caption">
          <i class="icon-settings font-dark"></i>
          {{ __('Packages') }}
        </div>
        <div class="tools">
          <a href="javascript:;"
            class="collapse"
            data-original-title=""
            title=""> </a>
        </div>
      </div>
      <div class="portlet-body" style="padding: 0px">
        <div id="filter_data_table">
          <div class="panel-body">
            <div class="row">
              @php $colorClasses = ['yellow-lemon','blue','green','red','yellow-casablanca']; @endphp
              @foreach($packages as $package)
                <div class="col-lg-2 col-md-2 col-sm-6 col-xs-12" style="margin-top: 10px">
                  <a class="dashboard-stat dashboard-stat-v2 {{$colorClasses[array_rand($colorClasses)]}}" href="#">
                    <div class="visual">
                    </div>
                    <div class="details">
                      <div class="number">

                        @if($subscriptionrelation == 'all')
                          <span data-counter="counterup" data-value="{{$package->subscriptions()->count()}}">0</span>
                        @else
                          <span data-counter="counterup" data-value="{{$package->toDaySubscriptions()->count()}}">0</span>
                        @endif
                      </div>
                      <div class="desc">{{$package->title}}</div>
                    </div>
                  </a>
                </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endif