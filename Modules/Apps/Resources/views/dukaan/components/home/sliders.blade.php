@if(count($records))
    <div class="home-slider owl-carousel">
        @foreach($records as $k => $record)
            @foreach($record->adverts()->active()->Started()->Unexpired()->orderBy('sort')->get(['id']) as $k => $advert)
                <a class="d-block slide" href="{{$advert->url}}">
                    <img class="img-fluid" src="{{$advert->image_path}}" alt="" />
                </a>
            @endforeach
        @endforeach
    </div>
@endif