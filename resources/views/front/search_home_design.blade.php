
    @foreach ($categories as $category)
        @foreach ($category->subcategory as $subcategory)
            @foreach ($subcategory->textdatas as $image)
                <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-4 image-item-new"
                     data-category-id="{{ $category->id }}"
                     data-subcategory-id="{{ $subcategory->id }}">
                    <div class="card-img collection-card">
                        <img src="{{ $image->filled_image }}" alt="Design Image">
                    </div>
                </div>
            @endforeach
        @endforeach
    @endforeach
