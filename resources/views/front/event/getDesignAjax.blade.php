@foreach ($categories as $category)
@foreach ($category->subcategory as $subcategory)
    @foreach ($subcategory->textdatas as $image)
        <div id="design_category" class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mt-xl-4 mt-sm-4 mt-4  image-item all_designs"
            data-category-id="{{ $category->id }}" 
            data-subcategory-id="{{ $subcategory->id }}" 
            data-category_name="{{ $category->category_name }}" 
           >
            <a href="javascript:;" class="collection-card card-blue">
                <div class="card-img design-card">
                    <img src="{{ asset('storage/canvas/' . $image->filled_image) }}"
                        alt="shower-card">
                </div>
                <h4>{{ $category->category_name }}</h4>
            </a>
        </div>
        @continue
        @endforeach
    @continue
    @endforeach
@continue
@endforeach