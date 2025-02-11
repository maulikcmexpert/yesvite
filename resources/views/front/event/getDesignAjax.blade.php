@foreach ($categories as $category)               
@php
    $firstSubcategory = $category->subcategory->first(); // Get the first subcategory
    $firstTextData = $firstSubcategory ? $firstSubcategory->textdatas->first() : null; // Get first textdata
@endphp
@if ($firstTextData)
        <div id="design_category" class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mt-xl-4 mt-sm-4 mt-4  image-item all_designs"
            data-category-id="{{ $category->id }}" 
            data-subcategory-id="{{ $firstSubcategory->id }}" 
            data-category_name="{{ $category->category_name }}" 
           >
            <a href="javascript:;" class="collection-card card-blue">
                <div class="card-img design-card">
                    <img src="{{ asset('storage/canvas/' . $firstTextData->filled_image) }}"
                        alt="shower-card">
                </div>
                <h4>{{ $category->category_name }}</h4>
            </a>
        </div>
@endif
@endforeach