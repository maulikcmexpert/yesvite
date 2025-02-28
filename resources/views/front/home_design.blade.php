{{--
<section class="home-web-tabs-wrp">
    <div class="container">
        <nav>
            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                <button class="nav-link active" data-tab="explore-designs-tab" id="nav-explore-designs-tab" data-bs-toggle="tab" data-bs-target="#nav-explore-designs" type="button" role="tab" aria-controls="nav-explore-designs" aria-selected="true">
                    Explore Designs
                </button>

                <button class="nav-link" id="nav-profile-home-tab" data-bs-toggle="tab" data-bs-target="#nav-profile-home" type="button" role="tab" aria-controls="nav-profile-home" aria-selected="false" tabindex="-1">
                    Profile Home
                </button>
            </div>
        </nav>
        <div class="tab-content">
            <div class="tab-pane fade show active" id="nav-explore-designs" role="tabpanel" aria-labelledby="explore-designs-tab">
                design tab
            </div>
            <div class="tab-pane fade" id="nav-profile-home" role="tabpanel" aria-labelledby="nav-profile-home-tab">
                profile tab
            </div>
        </div>
    </div>
</section> --}}

<section class="collection-wrapper">
    <div class="container">
        <div class="content">
            <h2>Find the Perfect <br> Design in Our Collection</h2>
            <p>Customizable Designs to Reflect Your Unique Event</p>
            <div class="position-relative search-wrapper">
                <input type="search" id="search_design_category" placeholder="Search design categories" class="">
                <div id="filtered_results" class="filtered-results-container"></div>
                <span class="">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                        </path>
                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
        </div>
        {{-- {{$getDesignData}} --}}
        <div class="filter-main-wrp categoryNew">
            <div class="filters-drp">
                <h5>Filter By</h5>
                <div class="filter-dropdowns">
                    <div class="dropdown">
                        <button type="button" class="btn dropdown-toggle" data-bs-toggle="dropdown">
                            Categories
                        </button>
                        <div class="dropdown-menu collection-menu">
                            <div class="filter-head">
                                <h5>Categories</h5>
                                <a href="#" class="reset-btn" id="resetCategories">Reset</a>
                            </div>
                            <div class="filter-categories">
                                <div class="d-flex align-items-center justify-content-between">
                                    <label class="form-check-label" for="Allcat">All Categories</label>
                                    <input class="form-check-input" type="checkbox" name="Guest RSVP’s" id="Allcat">
                                </div>
                                <div class="accordion" id="accordionExample">

                                    @foreach ($categories as $category)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $category->id }}">
                                                <button class="accordion-button" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $category->id }}" aria-expanded="true"
                                                    aria-controls="collapse{{ $category->id }}">
                                                    {{ $category->category_name }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $category->id }}" class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $category->id }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                        @foreach ($category->subcategory as $subcategory)
                                                            <li>
                                                                <div
                                                                    class="d-flex align-items-center justify-content-between">
                                                                    <label class="form-check-label"
                                                                        for="subcategory{{ $subcategory->id }}">
                                                                        {{ $subcategory->subcategory_name }}
                                                                    </label>
                                                                    <input
                                                                        class="form-check-input categoryChecked_{{ $category->id }}"
                                                                        name="design_subcategory" type="checkbox"
                                                                        id="subcategory{{ $subcategory->id }}"
                                                                        data-category-id="{{ $category->id }}"
                                                                        data-subcategory-id="{{ $subcategory->id }}">
                                                                </div>
                                                            </li>
                                                        @endforeach
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <h5 class="total-items ms-auto total_design_count">{{ $count }} Items</h5>
        </div>


            <div class="row list_all_design_catgeory">
                @php
                    $allImages = collect([]);
                    $randomIds = [];
                    foreach ($categories as $category) {
                        foreach ($category->subcategory as $subcategory) {
                            foreach ($subcategory->textdatas as $image) {
                                $randomIds[] = $image->id;
                                $allImages->push([
                                    'imageId' => $image->id,
                                    'category_id' => $category->id,
                                    'subcategory_id' => $subcategory->id,
                                    'category_name' => $category->category_name,
                                    'image_path' => asset('storage/canvas/' . $image->filled_image),
                                ]);
                            }
                        }
                    }

                    shuffle($randomIds);
                    $randomIds = array_slice($randomIds, 0, 30);

                    // $randomImages = $allImages->shuffle()->take(30);
                @endphp

                @foreach ($allImages as $image)
                    <div
                        class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item all_designs  {{in_array($image['imageId'], $randomIds) ? 'default_show' : 'd-none'}}"
                        data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                        data-category-id="{{ $image['category_id'] }}"
                        data-subcategory-id="{{ $image['subcategory_id'] }}"
                        data-category_name="{{ $image['category_name'] }}"  >

                        <div class="card-img collection-card card-blue">
                            <img src="{{ $image['image_path'] }}" alt="shower-card">
                        </div>

                    </div>
                @endforeach
            </div>


        {{-- <div class="row list_all_design_catgeory search_category">
            @foreach ($categories as $category)
                @foreach ($category->subcategory as $subcategory)
                    @foreach ($subcategory->textdatas as $image)
                        <div id="design_category" style="display:none"
                            class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item-new all_designs"
                            data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                            data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subcategory->id }}"
                            data-category_name="{{ $category->category_name }}">

                            <div class="card-img collection-card card-blue">
                                <img src="{{ asset('storage/canvas/' . $image->filled_image) }}" alt="shower-card">
                            </div>

                        </div>
                    @endforeach
                @endforeach
            @endforeach


        </div> --}}



    </div>
    </div>
</section>


<section class="landing-footer">
    <div class="container-fluid">
        <div class="platform-wrp">
            <div class="row">
                <div class="col-lg-7 mb-lg-0 mb-4">
                    <div class="platform-content">
                        <h2>The best platform to manage all your events</h2>
                        <p>Customizable Designs to Reflect Your Unique Event</p>
                        <div class="app-store d-flex gap-2">
                            <a href="{{ isset($getSocialLink->playstore_link) && $getSocialLink->playstore_link != null ? $getSocialLink->playstore_link : '#' }}"
                                class="google-app">
                                <img src="{{ asset('assets/front/image/google-app.png') }}" alt="google-app">
                            </a>
                            <a href="{{ isset($getSocialLink->appstore_link) && $getSocialLink->appstore_link != null ? $getSocialLink->appstore_link : '#' }}"
                                class="mobile-app">
                                <img src="{{ asset('assets/front/image/mobile-app.png') }}" alt="mobile-app">
                            </a>
                        </div>
                    </div>
                </div>
                <div class="col-lg-5">
                    <div class="row">
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="platform-img">
                                <img src="{{ asset('assets/front/image/platform-img1.png') }}" alt="platform-img">
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                            <div class="platform-img"></div>
                            <img src="{{ asset('assets/front/image/platform-img2.png') }}" alt="platform-img">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>
</section>

@push('scripts')
{{-- <script>
    var designData = [];
    var is_random = @php
    echo json_encode($randomIds);
    @endphp
    alert(is_random)
        @foreach ($categories as $category)
        var categoryData = {
            id: {{ $category->id }},
            name: "{{ $category->category_name }}",
            subcategories: []
        };

        @foreach ($category->subcategory as $subcategory)
            var subcategoryData = {
                id: {{ $subcategory->id }},
                name: "{{ $subcategory->subcategory_name }}",
                images: []
            };

            @foreach ($subcategory->textdatas as $image)
                subcategoryData.images.push({
                    id: {{ $image->id }},
                    image_path: "{{ asset('storage/canvas/' . $image->filled_image) }}"
                });
            @endforeach

            categoryData.subcategories.push(subcategoryData);
        @endforeach

        designData.push(categoryData);
    @endforeach

    console.log(designData); // Check output in browser console
</script> --}}

<script>
    var designData = [];

    // Correcting the PHP to JavaScript variable conversion
    var is_random = {!! json_encode($randomIds) !!};


    @foreach ($categories as $category)
        var categoryData = {
            id: {{ $category->id }},
            name: "{{ $category->category_name }}",
            subcategories: []
        };

        @foreach ($category->subcategory as $subcategory)
            var subcategoryData = {
                id: {{ $subcategory->id }},
                name: "{{ $subcategory->subcategory_name }}",
                images: []
            };

            @foreach ($subcategory->textdatas as $image)
                subcategoryData.images.push({
                    id: {{ $image->id }},
                    image_path: "{{ asset('storage/canvas/' . $image->filled_image) }}"
                });
            @endforeach

            categoryData.subcategories.push(subcategoryData);
        @endforeach

        designData.push(categoryData);
    @endforeach

    console.log(designData); // Check output in browser console
</script>

@endpush

