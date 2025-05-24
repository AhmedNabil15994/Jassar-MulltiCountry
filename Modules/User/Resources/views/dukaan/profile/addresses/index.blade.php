@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class='row'>
            <div class="col-md-3">
                <x-dukaan-user-menu/>
            </div>
            <div class="col-md-9">
                <x-dukaan-address-content/>
            </div>
        </div>
    </div>
</div>

@endsection
@section('vuejs')
@include('user::dukaan.profile.addresses.scripts')
    <script>
        function VueData(data){
            
            return addressData(data);
        }

        function vueMounted(){

            addressMounted();
        }

        function VueMethods(methods){

            return addressMethods(methods);
        }
    </script>

@endsection