<section class="banner-wrapper">
    <img src="{{ asset('assets/front/image/left-banner.png') }}" alt="left-banner" class="left-img wow fadeInLeft"
        data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
    <img src="{{ asset('assets/front/image/right-banner.png') }}" alt="right-banner" class="right-img wow fadeInRight"
        data-wow-duration="5s" data-wow-delay="0" data-wow-offset="0">
    <div class="container">
        <div class="banner-content">
            <h1 class="wow fadeInDown" data-wow-duration="1s" data-wow-delay="0" data-wow-offset="0">Celebrate Every
                Moment with Ease!</h1>
            <p class="wow fadeInDown" data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0">Stress-free
                event planning starts here! Our user-friendly app handles everything from invites to
                decorations, so you can relax and enjoy your celebration</p>
            <div class="app-store d-flex justify-content-center gap-2">
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
        <div class="banner-img">
            <img src="{{ asset('assets/front/image/birthday-card.png') }}" alt="birthday-card">
        </div>
    </div>
</section>
<section class="collection-wrapper">
    <div class="container">
        <div class="content">
            <h2>Find the Perfect <br> Design in Our Collection</h2>
            <p>Customizable Designs to Reflect Your Unique Event</p>
            <div class="position-relative search-wrapper">
                <input type="search" id="search_design_category" placeholder="Search design categories" class="">
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
                                                                    <input class="form-check-input"
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
        <div style="display: none" class="filter-main-wrp subcategoryNew">
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
                                <div class="accordion" id="accordionExample">

                                    @foreach ($categories as $category)
                                    <div class="accordion-item category category_{{$category->id}}">
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
                                                                    <input class="form-check-input subcategory_{{$subcategory->id}}"
                                                                        name="design_subcategory_new" type="checkbox"
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
        {{-- {{ dd($categories);}} --}}
        <div class="row list_all_design_catgeory">
            @foreach ($categories as $category)
                @php
                    $firstSubcategory = $category->subcategory->first(); // Get the first subcategory
                    $firstTextData = $firstSubcategory ? $firstSubcategory->textdatas->first() : null; // Get first textdata
                @endphp

                @if ($firstTextData)
                    <div id="design_category"
                        class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item all_designs"
                        data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                        data-category-id="{{ $category->id }}" data-subcategory-id="{{ $firstSubcategory->id }}"
                        data-category_name="{{ $category->category_name }}">
                        <a href="#" class="collection-card card-blue">
                            <div class="card-img">
                                <img src="{{ asset('storage/canvas/' . $firstTextData->filled_image) }}"
                                    alt="shower-card">
                            </div>
                            <h4>{{ $category->category_name }}</h4>
                        </a>
                    </div>
                @endif
            @endforeach


        </div>


        <div class="row list_all_design_catgeory_new">
            <div class="d-flex align-items-center" style="gap: 15px">
                <p id="allchecked" style="display:none"><i class="fa-solid fa-arrow-left"
                        style="color: #212529; cursor: pointer;"></i></p>
                <h5 id="category_name" class="mb-0" style="display:none">Test category</h5>
            </div>
            @foreach ($categories as $category)
                @foreach ($category->subcategory as $subcategory)
                    @foreach ($subcategory->textdatas as $image)
                        <div style="display: none"
                            class="col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item-new all_designs"
                            data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subcategory->id }}">

                            <a href="javascript:;" class="collection-card card-blue">
                                <div class="card-img">
                                    <img src="{{ asset('storage/canvas/' . $image->filled_image) }}"
                                        alt="shower-card">
                                </div>
                                {{-- <h4>{{ $category->category_name }}</h4> --}}
                            </a>
                        </div>
                    @endforeach
                @endforeach
            @endforeach


        </div>



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
