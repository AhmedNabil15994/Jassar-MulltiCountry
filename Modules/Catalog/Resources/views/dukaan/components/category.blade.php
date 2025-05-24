

<a class="category-block" href='{{ route('frontend.categories.products', $category->slug) }}'>
    <div class="img-block">
        <img class="img-fluid" 
        src="{{ $category->image_path }}" alt="{{ $category->title }}" />
    </div>
    <h3>
        {{ $category->title }}
    </h3>
</a>