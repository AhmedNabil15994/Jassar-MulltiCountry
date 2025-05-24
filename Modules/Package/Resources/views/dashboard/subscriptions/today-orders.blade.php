@extends('apps::dashboard.layouts.app')
@section('title', __('package::dashboard.subscriptions.routes.index'))
@section('content')
<div class="page-content-wrapper">
  <div class="page-content">
    <div class="page-bar">
      <ul class="page-breadcrumb">
        <li>
          <a href="{{ url(route('dashboard.home')) }}">{{ __('apps::dashboard.index.title') }}</a>
          <i class="fa fa-circle"></i>
        </li>
        <li>
          <a href="#">{{ __('package::dashboard.subscriptions.routes.index') }}</a>
        </li>
      </ul>
    </div>

    <div class="row">
      <div class="col-md-12">
        <div class="portlet light bordered">

          {{-- @can('add_subscriptions')
          <div class="table-toolbar">
            <div class="row">
              <div class="col-md-6">
                <div class="btn-group">
                  <a href="{{ url(route('dashboard.subscriptions.create')) }}"
                    class="btn sbold green">
                    <i class="fa fa-plus"></i> {{ __('apps::dashboard.buttons.add_new') }}
                  </a>
                </div>
              </div>
            </div>
          </div>
          @endcan --}}


          {{-- DATATABLE FILTER --}}
          <div class="row">
            <div class="portlet box grey-cascade">
              <div class="portlet-title">
                <div class="caption">
                  <i class="fa fa-gift"></i>
                  {{ __('apps::dashboard.datatable.search') }}
                </div>
                <div class="tools">
                  <a href="javascript:;"
                    class="collapse"
                    data-original-title=""
                    title=""> </a>
                </div>
              </div>
              <div class="portlet-body">
                <div id="filter_data_table">
                  <div class="panel-body">
                    <form id="formFilter"
                      class="horizontal-form">
                      <div class="form-body">
                        <div class="row">
                          <div class="col-md-3">
                            <div class="form-group">
                              <label class="control-label">
                                {{ __('apps::dashboard.datatable.form.date_range') }}
                              </label>
                              <div id="reportrange"
                                class="btn default form-control">
                                <i class="fa fa-calendar"></i> &nbsp;
                                <span> </span>
                                <b class="fa fa-angle-down"></b>
                                <input type="hidden"
                                  name="from">
                                <input type="hidden"
                                  name="to">
                              </div>
                            </div>
                          </div>
                            
                          <div class="col-md-3">
                            <div class="form-group">
                                <label class="control-label">
                                    {{__('package::dashboard.subscriptions.datatable.package')}}
                                </label>
                                <select name="package_id"
                                    id="single"
                                    class="form-control select2">
                                    <option value="">
                                        all
                                    </option>
                                    @foreach ($packages as $package)
                                      <option value="{{ $package->id }}">
                                          {{ $package->title }}
                                      </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                          @include('package::dashboard.subscriptions.filter')
                        </div>
                      </div>
                    </form>
                    <div class="form-actions">
                      <button class="btn btn-sm green btn-outline filter-submit margin-bottom"
                        id="search">
                        <i class="fa fa-search"></i>
                        {{ __('apps::dashboard.datatable.search') }}
                      </button>
                      <button class="btn btn-sm red btn-outline filter-cancel">
                        <i class="fa fa-times"></i>
                        {{ __('apps::dashboard.datatable.reset') }}
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <x-dashboard-package-count :packages="$packages" subscriptionrelation="to_day"/>
          
          {{-- END DATATABLE FILTER --}}

          <div class="portlet-title">
            <div class="caption font-dark">
              <i class="icon-settings font-dark"></i>
              <span class="caption-subject bold uppercase">
                {{ __('package::dashboard.subscriptions.routes.index') }}
              </span>
            </div>
          </div>

          {{-- DATATABLE CONTENT --}}
          <div class="portlet-body">
            <table class="table table-striped table-bordered table-hover"
              id="dataTable">
              <thead>
                <tr>
                  <th>
                    <a href="javascript:;"
                      onclick="CheckAll()">
                      {{ __('apps::dashboard.buttons.select_all') }}
                    </a>
                  </th>
                  <th>{{ __('package::dashboard.subscriptions.datatable.package') }}</th>
                  <th>{{ __('package::dashboard.subscriptions.datatable.user') }}</th>
                  <th>{{ __('package::dashboard.subscriptions.datatable.start_at') }}</th>
                  <th>{{ __('package::dashboard.subscriptions.datatable.end_at') }}</th>
                  <th>{{ __('package::dashboard.subscriptions.datatable.pause') }}</th>
                  <th>{{ __('package::dashboard.subscriptions.datatable.note') }}</th>
                  <th data-priority="1">
                    {{ __('package::dashboard.subscriptions.datatable.options') }}
                  </th>
                </tr>
              </thead>
            </table>
          </div>
          <div class="row">
            <div class="form-group">
              <button type="submit"
                id="deleteChecked"
                class="btn red btn-sm"
                onclick="deleteAllChecked('{{ url(route('dashboard.subscriptions.deletes')) }}')">
                {{ __('apps::dashboard.datatable.delete_all_btn') }}
              </button>

              <button 
                type="button"
                class="btn btn-info btn-sm" id="print_btn"
                onclick="printAllChecked('{{ url(route('dashboard.subscriptions.print')) }}')">
                {{__('apps::dashboard.datatable.print')}}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop


@section('scripts')

<script>
  function tableGenerate(data='') {

      var dataTable =
      $('#dataTable').DataTable({
          "createdRow": function( row, data, dataIndex ) {
             if ( data["deleted_at"] != null ) {
                $(row).addClass('danger');
             }
          },
          ajax : {
              url   : "{{ url(route('dashboard.subscriptions.today_orders.datatable')) }}",
              type  : "GET",
              data  : {
                  req : data,
              },
          },
          language: {
              url:"https://cdn.datatables.net/plug-ins/1.10.16/i18n/{{ucfirst(LaravelLocalization::getCurrentLocaleName())}}.json"
          },
          stateSave: true,
          processing: true,
          serverSide: true,
          responsive: !0,
          order     : [[ 1 , "desc" ]],
          columns: [
            {data: 'id' 		 	        , className: 'dt-center'},
      			{data: 'package_id' 			      , className: 'dt-center'},
            {data: 'user_id' 	        , className: 'dt-center'},
            {data: 'start_at' 		  , className: 'dt-center'},
            {data: 'end_at' 		  , className: 'dt-center'},
            {data: 'is_pause' 		  , orderable: false , className: 'dt-center'},
            {data: 'note' 		  , orderable: false , className: 'dt-center'},
            {data: 'address',className: 'dt-center',width: '13%',responsivePriority: 1},
      		],
          columnDefs: [
            {
      				targets: 0,
      				width: '30px',
      				className: 'dt-center',
      				orderable: false,
      				render: function(data, type, full, meta) {
      					return `<label class="mt-checkbox mt-checkbox-single mt-checkbox-outline">
                          <input type="checkbox" value="${data}" class="group-checkable" name="ids">
                          <span></span>
                        </label>
                      `;
      				},
      			},
          ],
          dom: 'Bfrtip',
          lengthMenu: [
              [ 10, 25, 50 , 100 , 500 ],
              [ '10', '25', '50', '100' , '500']
          ],
  				buttons:[
  					{
    						extend: "pageLength",
                className: "btn blue btn-outline",
                text: "{{__('apps::dashboard.datatable.pageLength')}}",
                eexportOptions: {
                    stripHtml : false,
                    columns: ':visible',
                    columns: [ 1 , 2 , 3 , 4, 6, 7]
                }
  					},
  					{
    						extend: "print",
                className: "btn blue btn-outline" ,
                text: "{{__('apps::dashboard.datatable.print')}}",
                eexportOptions: {
                    stripHtml : false,
                    columns: ':visible',
                    columns: [ 1 , 2 , 3 , 4, 6, 7]
                }
  					},

  					{
  							extend: "excel",
                className: "btn blue btn-outline " ,
                text: "{{__('apps::dashboard.datatable.excel')}}",
                eexportOptions: {
                    stripHtml : false,
                    columns: ':visible',
                    columns: [ 1 , 2 , 3 , 4, 6, 7]
                }
  					},
  					{
  							extend: "colvis",
                className: "btn blue btn-outline",
                text: "{{__('apps::dashboard.datatable.colvis')}}",
                eexportOptions: {
                    stripHtml : false,
                    columns: ':visible',
                    columns: [ 1 , 2 , 3 , 4, 5 , 6, 7]
                }
  					}
  				]
      });
  }

  jQuery(document).ready(function() {
  	tableGenerate();
  });
</script>

@stop
