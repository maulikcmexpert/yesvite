@foreach ($categories as $category)
            @foreach ($category->subcategory as $subcategory)
                @foreach ($subcategory->textdatas as $image)
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mt-xl-4 mt-sm-4 mt-4  image-item all_designs"
                        data-category-id="{{ $category->id }}" 
                        data-subcategory-id="{{ $subcategory->id }}" 
                       >
                        <a href="javascript:;" class="collection-card card-blue">
                            <div class="card-img edit_design_tem design-card"  
                            data-image="{{ asset('storage/canvas/' . $image->image) }}"
                                data-shape_image="{{ $image->shape_image != '' ? asset('storage/canvas/' . $image->shape_image) : '' }}"
                                data-json="{{ json_encode($image->static_information) }}"
                                data-id="{{ $image->id }}">
                                <img src="{{ asset('storage/canvas/' . $image->filled_image) }}"
                                    alt="shower-card">
                            </div>
                            <h4>{{ $category->category_name }}</h4>
                        </a>
                    </div>
                @endforeach
            @endforeach
        @endforeach