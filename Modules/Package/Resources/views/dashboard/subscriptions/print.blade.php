<div id="invoice">
  <div class="ticket">


          <!-- <img src="/poss/images/logo2.png" width="50" height="50" alt=""> -->
          <div id="header" style="border-bottom: 1px solid #cbcbcb;padding:0px 15px 0px 15px">
              <h3>{{ setting('app_name',locale()) }}</h3>
          </div>
          @foreach($subscriptions as $subscription)
            <div id="contact-us" style="padding:0px 15px 0px 15px;border:0.5px solid black;margin:5px">
                <p>
                  : {{$subscription['package_id']}}</br>
                   : {{$subscription['user_id']}}</br>
                   : {{$subscription['start_at']}}</br>
                   : {{$subscription['end_at']}}</br>
                   : {{$subscription['is_pause']}}</br>
                   : {{$subscription['note']}}</br>
                </p>
            </div>
          @endforeach
            
          <p style="text-align: center;">
              Saved By {{auth()->user()->name}}
              </br>
              {{Carbon\Carbon::now()->toDateString()}}
          </p>
      <p style="text-align: center;">
      Thank You for Choosing Us.
      </p>
  </div>
</div>