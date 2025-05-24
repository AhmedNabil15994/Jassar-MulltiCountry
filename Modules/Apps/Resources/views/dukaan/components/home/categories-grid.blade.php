@php
    $numOfColumns = $home->grid_columns_count ?? 4;
    $bootstrapColWidth = 12 / $numOfColumns;
@endphp

<div class="section-block categories-grid">
    <div class="container">
        <div class="section-title d-flex justify-content-between align-items-center">
            <h2 class="mb-0">{{ $home->title }}</h2>
            <a class="seemore-link" href="{{ route('frontend.categories.products') }}"><span>@lang('front.See more')</span></a>
        </div>
        <div class="row">
            @foreach ($records as $k => $record)
            <div class="col-md-{{ $bootstrapColWidth ?? '2' }} col-6">
                <x-dukaan-category-item :category="$record"/>
            </div>
            @endforeach
        </div>
    </div>
</div>