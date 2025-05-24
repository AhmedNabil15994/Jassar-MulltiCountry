<div>
   
    <button class="btn btn-block btn-info btn-lg printButton " style="margin-bottom: 10px" >Print <i class="fa fa-print"></i></button>
    <div class="printer-container"  >
        @php($loop_count = 0)
         {{-- check is is new paper --}}
         
        @foreach ($subscriptions as $subscription)
            {{-- render itme product --}}
            @if($subscription)

                @php($loop_count++)
                @if (is_new_paper($print_setting_details, $loop_count))
                {{-- Actual Paper --}}
                    <div style="
                    @if(!$print_setting_details->is_continuous) height:{{$print_setting_details->paper_height}}in !important; @endif width:{{$print_setting_details->paper_width}}in !important; line-height: 16px !important;
                    @if(!$print_setting_details->is_continuous)padding-top:{{$print_setting_details->top_margin}}in !important; padding-bottom:{{$print_setting_details->top_margin}}in !important; padding-left:{{$print_setting_details->left_margin}}in !important;padding-right:{{$print_setting_details->left_margin}}in !important;@endif
                    " 
                    class=" label-border-outer @if(!$request->setSizePage && ($total_qty >  $print_setting_details->stickers_in_one_sheet + $loop_count)) page-break  @endif">
                @endif

                @if(is_new_row($print_setting_details, $loop_count))
                    {{-- Paper Internal --}}
                    <div 
                        style=""
                        class="label-border-internal">

                @endif


                <div class="label-print" style="
                        height:{{$print_setting_details->height}}in !important; width:{{$print_setting_details->width}}in !important; 
                            margin-left:{{$print_setting_details->col_distance}}in !important;
                            margin-top:{{$print_setting_details->row_distance}}in !important; 
                        " class="text-center" >
                    <div class="items" >
                        <div style="padding:0px 15px 0px 15px;border:0.5px solid black">

                            <p>
                                <img class="img-fluid" src="{{setting('logo')?asset(setting('logo')):asset('frontend/images/logo.png')}}" alt="" style="width:{{$print_setting_details->paper_width/2}}in !important"/>
                                <br>
                              <strong>{{ __('package::dashboard.subscriptions.datatable.package')}}</strong>: {{$subscription['package_id']}}</br>
                              <strong>{{ __('package::dashboard.subscriptions.datatable.user') }}</strong>: {{$subscription['user_id']}}</br>
                              <strong>{{ __('package::dashboard.subscriptions.datatable.start_at')}}</strong>: {{$subscription['start_at']}}</br>
                              <strong>{{ __('package::dashboard.subscriptions.datatable.end_at') }}</strong>: {{$subscription['end_at']}}</br>
                              <strong>{{ __('package::dashboard.subscriptions.datatable.pause') }}</strong>: {{$subscription['is_pause']}}</br>
                              <strong>{{ __('package::dashboard.subscriptions.datatable.note') }} </strong>: {{$subscription['note']}}</br>
                            </p>
                        </div>
                    </div>

                    
                </div>

                @if (is_end_row($print_setting_details, $loop_count))
                    {{-- Paper Internal --}}
                </div>
                {{-- <div class="page-break"></div> --}}
                @endif
                
                @if(is_paper_end($print_setting_details, $loop_count))
                    {{-- Actual Paper --}}
                    </div>
                @endif

            @endif
        @endforeach

        @if (!is_end_row($print_setting_details, $loop_count))
                           {{-- Paper Internal --}}
              </div sss>
        @endif

        @if($print_setting_details->is_continuous  || !is_paper_end($print_setting_details, $loop_count))
            {{-- Actual Paper --}}
            </div>
        @endif

        
    
    </div>
</div>

<style>
    @media print{
	    .content-wrapper{
	      border-left: none !important; /*fix border issue on invoice*/
	    }
	    .label-border-outer{
	        border: none !important;
	    }
	    .label-border-internal{
	        border: none !important;
	    }
	    .sticker-border{
	        border: none !important;
	    }
	    #preview_box{
	        padding-left: 0px !important;
	    }
	    #toast-container{
	        display: none !important;
	    }
	    .tooltip{
	        display: none !important;
	    }
	    .btn{
	    	display: none !important;
	    }
	}

    @page {
        @if($request->setSizePage)
		size: {{$print_setting_details->paper_width}}in @if( !$print_setting_details->is_continuous && $print_setting_details->paper_height ){{$print_setting_details->paper_height}}in @else {{$paper_height}}in @endif;
        @endif
		/*width: {{$print_setting_details->paper_width}}in !important;*/
		/*height:@if($print_setting_details->paper_height != 0){{$print_setting_details->paper_height}}in !important @else auto @endif;*/
		
        margin-top: 0in;
		margin-bottom: 0in;
		margin-left: 0in;
		margin-right: 0in;

        font-size: 12pt;
		
		@if($print_setting_details->is_continuous)
			/*page-break-inside : avoid !important;*/
		@endif
	}
  
</style>