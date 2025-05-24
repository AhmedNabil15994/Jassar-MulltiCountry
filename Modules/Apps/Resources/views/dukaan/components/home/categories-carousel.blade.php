<div class="section-block">
    <div class="container">
        <div class="section-title d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $home->title }}</h2>
            <a class="seemore-link" href="{{ route('frontend.categories.products') }}"><span>@lang('front.See more')</span></a>
        </div>
        <div class="home-categories owl-carousel">
            @foreach ($records as $k => $record)
                <x-dukaan-category-item :category="$record"/>
            @endforeach
        </div>
    </div>
</div>