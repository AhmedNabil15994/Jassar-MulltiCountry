@extends('apps::dashboard.layouts.app')
@section('title', __('package::dashboard.print.routes.index'))
@section("css")
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.min.css" integrity="sha512-TsNN9S3X3jnaUdLd+JpyR5yVSBvW9M6ruKKqJl5XiBpuzzyIMcBavigTAHaH50MJudhv5XIkXMOwBL7TbhXThQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/easy-autocomplete.themes.min.css" integrity="sha512-5EKwOr+n8VmXDYfE/EObmrG9jmYBj/c1ZRCDaWvHMkv6qIsE60srmshD8tHpr9C7Qo4nXyA0ki22SqtLyc4PRw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/print-js/1.6.0/print.min.css" integrity="sha512-zrPsLVYkdDha4rbMGgk9892aIBPeXti7W77FwOuOBV85bhRYi9Gh+gK+GWJzrUnaCiIEm7YfXOxW8rzYyTuI1A==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<style>
    .item-barcode{
        display: flex;
        justify-content: center;
        align-items: flex-end;
        width: 100%;
    }
    .barcode{
        border: 1px dashed #000;
        padding: 8px 0px;
    }
    .printer-container{
        /* border: 1px solid #000 */
    }
    .label-border-outer{
        border: 1px solid #000
    }
    .label-border-internal{
        display: flex  !important;
        flex-wrap: wrap
        
    }
    .label-print{
        display: inline-block;
        border: 1px dashed gray !important;
        color:#000;
        -webkit-box-sizing: border-box; /* Safari/Chrome, other WebKit */
        -moz-box-sizing: border-box;    /* Firefox, other Gecko */
        box-sizing: border-box;         /* Opera/IE 8+ */
    }
    .label-print .items{
       height: inherit;
       padding: 5px 0;
       display: flex;
       align-items: flex-end;
       flex-wrap: wrap
       
    }
    .page-break  {

        page-break-after:always;
    }
    /* ============ ============ */
    @media print {
        .label , .label-print, .barcode{
              
                border: none !important
        }
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
                float: left;
        }
        .col-sm-12 {
                width: 100%;
        }
        .col-sm-11 {
                width: 91.66666667%;
        }
        .col-sm-10 {
                width: 83.33333333%;
        }
        .col-sm-9 {
                width: 75%;
        }
        .col-sm-8 {
                width: 66.66666667%;
        }
        .col-sm-7 {
                width: 58.33333333%;
        }
        .col-sm-6 {
                width: 50%;
        }
        .col-sm-5 {
                width: 41.66666667%;
        }
        .col-sm-4 {
                width: 33.33333333%;
        }
        .col-sm-3 {
                width: 25%;
        }
        .col-sm-2 {
                width: 16.66666667%;
        }
        .col-sm-1 {
                width: 8.33333333%;
        }
        .barcode-image img {
            width: 70% !important;
        }
        .item-barcode{
            text-align: center;
            
        }
        .printButton{
            display: none !important;
        }
        #printer{
            border: none !important;
            overflow: hidden !important;
        }
}

</style>
@stop
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
                    <a href="#">{{__('package::dashboard.print.routes.index')}}</a>
                </li>
            </ul>
        </div>

        <div class="row">
            <div class="col-md-12">
                <div class="portlet light bordered">

                   
                    {{-- start form --}}
                  <form  class="horizontal-form" id="form-button" >
                    @csrf
                     {{-- DATATABLE FILTER --}}
                     <div class="row">
                        <div class="portlet box grey-cascade">
                            
                            <div class="portlet-title">
                                <div class="caption">
                                    <i class="fa fa-gift"></i>
                                    {{__('package::dashboard.print.datatable.show_in')}}
                                </div>
                                <div class="tools">
                                    <a href="javascript:;" class="collapse" data-original-title="" title=""> </a>
                                </div>
                            </div>
                            <div class="portlet-body">
                                <div id="show_info">
                                    <div class="panel-body">
                                        {{-- start form --}}
                                       <div>
                                           <div class="row">

                                               <div class="col-md-3" style="margin-top: 25px">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <label style="text-transform: capitalize">set Size to Paper</label> 
                                                        </div>
                                                        <div class="col-md-4">
                                                        <input style="width: 50px" type="checkbox" value="1" name="setSizePage" />
                                                        </div>
                                                    </div>   
                                                </div>
                                           </div>
                                       </div>


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
                                       <div class="col-md-5" style="margin-top: 25px">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <label style="text-transform: capitalize">Print Setting</label> 
                                            </div>
                                            <div class="col-md-9">
                                                <select class="form-control" name="print_setting_id">
                                                    @inject('print_settings','Modules\Package\Entities\PrintSetting')
                                                    @foreach ($print_settings->get() as  $item)
                                                         <option
                                                            value="{{$item->id}}"
                                                         >{{$item->name}}</option>
                                                    @endforeach
                                                    
                                                </select>


                                            </div>
                                        </div> 
                                    </div>
                                        {{-- end form --}}
                                        <div class="form-actions" style="margin-top: 25px; display: flex; justify-content: flex-end;">
                                            <button class="btn btn-sm green btn-outline  btn-lg filter-submit margin-bottom" style="width: 400px" id="showResult" type="button">
                                                <i class="fa fa-building"></i>
                                                {{__('package::dashboard.print-settings.datatable.preview')}}
                                            </button>
                                            <button class="btn btn-sm red btn-outline restPrint ">
                                                <i class="fa fa-times"></i>
                                                {{__('apps::dashboard.datatable.reset')}}
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    {{-- END DATATABLE FILTER --}}

                  </form>
                  {{-- end form --}}


                    {{-- <input type="text"  name="search" id="searchProduct" placeholder="Search By Product Name Or Sku"> --}}

                    <div class="portlet-title">
                        <div class="caption font-dark">
                            <i class="icon-settings font-dark"></i>
                            <span class="caption-subject bold uppercase">
                                {{__('package::dashboard.print.routes.index')}}
                            </span>
                        </div>
                    </div>

                    {{-- DATATABLE CONTENT --}}
                    <div class="portlet-body">
                        <div id="printer"></div>
                    </div>
                   
                </div>
            </div>
        </div>
    </div>
</div>
@stop

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/easy-autocomplete/1.3.5/jquery.easy-autocomplete.min.js" integrity="sha512-Z/2pIbAzFuLlc7WIt/xifag7As7GuTqoBbLsVTgut69QynAIOclmweT6o7pkxVoGGfLcmPJKn/lnxyMNKBAKgg==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jQuery.print/1.6.2/jQuery.print.min.js" integrity="sha512-t3XNbzH2GEXeT9juLjifw/5ejswnjWWMMDxsdCg4+MmvrM+MwqGhxlWeFJ53xN/SBHPDnW0gXYvBx/afZZfGMQ==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script>
    //  loaded
    $(function(){
       var products = []
        var _searchInput = $("#searchProduct") ,
            resultBody   = $("#result-body") 
            resultContainer  = $("#result-search")
            printer          = $("#printer")
            ;
      
        // ===========

        function handleChoseProduct(data){
            var tbody = `
                <tr>
                        <td>
                             ${data.title}   
                        </td>
                        <td>
                            <input type="hidden" name="product[${products.length}][id]" value="${data.id}"  class="form-control" />
                            <input type="number" name="product[${products.length}][num]" value="1" min="1" class="form-control" />

                        </td>
                </tr>
            `
            var key = 0
            for (const   variant of data.variations_values) {
                tbody += `
                <tr>
                        <td>
                            ${data.title} <span style="color:red"> ( ${variant.title} ) </span> 
                        </td>
                        <td>
                            <input type="number" name="product[${products.length}][variants][${key}][num]" value="1" min="1" class="form-control" />
                            <input type="hidden" name="product[${products.length}][variants][${key}][id]" value="${variant.id}"  class="form-control" />
                        </td>
                </tr>
                `
                key++;
            }
            // console.log(data, tbody)
            return tbody
        }

        $("#showResult").click(function(event){
            event.preventDefault();

            var _form = $("#form-button")
                $.ajax({
                    url: "{{route('dashboard.print.render.print')}}",
                    type: "post",
                    data: _form.serializeArray() ,
                    success:function(res){
                       printer.html(res.html)
                    }
                });
        })

        $("body").on("click", ".printButton" ,function(event){
            event.preventDefault()
            printer.print()
        })

        $("body").on("click", ".restPrint" ,function(event){
            event.preventDefault()
            printer.html("")
            products = [];
            resultContainer.hide()
            resultBody.html("")
            _searchInput.val("")
            _searchInput.change()


        })
            
        


       

    });


 </script>
 
 

@endpush
