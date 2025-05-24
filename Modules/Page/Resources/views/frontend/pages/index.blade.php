@extends('apps::dukaan.components.layouts.main')
@section('title', __('apps::frontend.home.title'))
@section('content')
<div class="inner-page">
    <div class="container">
        <div class='row'>
            <h1>{{ $page->title }}</h1>
            {!! $page->description !!}
        </div>
    </div>
</div>

@endsection