
                @foreach ($categories as $category)
                @foreach ($category->subcategory as $subcategory)
                    @foreach ($subcategory->textdatas as $image)
                        <div class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item"
                            data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                            data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subcategory->id }}">
                            <a href="#" class="collection-card card-blue">
                                <div class="card-img">
                                    <img src="{{ asset('storage/canvas/' . $image->filled_image) }}"
                                        alt="shower-card">
                                </div>
                                <h4>{{ $category->category_name }}</h4>
                            </a>
                        </div>
                    @endforeach
                @endforeach
            @endforeach