@foreach ($categories as $category)
    @php
        $firstSubcategory = $category->subcategory->first();
        $firstTextData = $firstSubcategory ? $firstSubcategory->textdatas->first() : null;
    @endphp

    @if ($firstTextData)
    <div id="design_category" class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item all_designs"
        data-category-id="{{ $category->id }}">
        <a href="javascript:;" class="collection-card card-blue">
            <div class="card-img">
                <img src="{{ asset('storage/canvas/' . $firstTextData->filled_image) }}" alt="shower-card">
            </div>
            <h4>{{ $category->category_name }}</h4>
        </a>
    </div>

    <!-- Subcategories (Initially Hidden) -->
    <div class="subcategoryNew" data-category-id="{{ $category->id }}" style="display: none;">
        @foreach ($category->subcategory as $subcategory)
            <div class="subcategory-item">
                <h5>{{ $subcategory->name }}</h5>
                <!-- Add subcategory-related content here -->
            </div>
        @endforeach
    </div>
    @endif
@endforeach
