@if (count($home_sections))
    @foreach ($home_sections as $home)
        <x-dukaan-home-section :home="$home"/>
    @endforeach
@endif

<x-dukaan-add-cart-modal/>