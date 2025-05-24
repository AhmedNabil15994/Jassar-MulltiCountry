@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title') )
@section('content')
<div class="inner-page">
    <div class="container">
        <div class="row">   
            <div class="col-md-12">
                {{-- <div class="category-head">
                    <button class="btn filter-btn"><i class="ti-filter"></i> Filter</button>
                    <p class="showing-num mb-0">Showing 1–12 of 20 results</p>
                    <div class="pro-sorting">
                        <select class="form-control single-select">
                            <option vlaue="1">Default Sorting</option>
                            <option vlaue="2">Latest added</option>
                            <option vlaue="3">Price height to low</option>
                            <option vlaue="4">Price low to height</option>
                        </select>
                    </div>
                </div> --}}

                <div class="products-container">
                    <div class="row">
                        @foreach ($products as  $product)
                            <div class="col-md-2 col-6">
                                <x-dukaan-product-item :product="$product"/>
                            </div>
                        @endforeach
                    </div>
                </div>
                
                <x-dukaan-paginator :paginator="$products"/>
            </div>
        </div>
    </div>
</div>

<x-dukaan-add-cart-modal/>
    
@endsection
