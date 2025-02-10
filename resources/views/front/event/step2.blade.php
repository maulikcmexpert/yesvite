<div class="step_2">
    <section class="collection-wrapper">
        <div class="">
            <div class="content">

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

            <div class="filter-main-wrp">
                <div class="filters-drp">
                    <h5>Filter By</h5>
                    <div class="filter-dropdowns create-event-filter">
                        <div class="dropdown">
                            <button type="button" class="btn dropdown-toggle " data-bs-toggle="dropdown">
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
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s"
                                            id="Allcat">
                                    </div>
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($categories as $category)
                                            <div class="accordion-item">
                                                <h2 class="accordion-header" id="heading{{ $category->id }}">
                                                    <button class="accordion-button" type="button"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#collapse{{ $category->id }}"
                                                        aria-expanded="true"
                                                        aria-controls="collapse{{ $category->id }}">
                                                        {{ $category->category_name }}
                                                    </button>
                                                </h2>
                                                <div id="collapse{{ $category->id }}"
                                                    class="accordion-collapse collapse"
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
                <h5 class="total-items ms-auto total_design_count">{{ $imagecount }} Items</h5>
            </div>

            <div class="row list_all_design_catgeory">

                @foreach ($categories as $category)
                    @foreach ($category->subcategory as $subcategory)
                        @foreach ($subcategory->textdatas as $image)
                            <div id="design_category"
                                class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mt-xl-4 mt-sm-4 mt-4  image-item all_designs"
                                data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subcategory->id }}"
                                data-category_name="{{ $category->category_name }}">
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
                @endforeach


            </div>


            <div class="row list_all_design_catgeory_new">
                <p id="allchecked" style="display:none"><i class="fa-solid fa-arrow-left"></i></p>
                <h5 id="category_name" style="display:none">Test category</h5>
                @foreach ($categories as $category)
                    @foreach ($category->subcategory as $subcategory)
                        @foreach ($subcategory->textdatas as $image)
                            <div style="display: none"
                                class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mt-xl-4 mt-sm-4 mt-4  image-item-new all_designs"
                                data-category-id="{{ $category->id }}" data-subcategory-id="{{ $subcategory->id }}">

                                <a href="javascript:;" class="collection-card card-blue">
                                    <div class="card-img edit_design_tem design-card"
                                        data-image="{{ asset('storage/canvas/' . $image->image) }}"
                                        data-shape_image="{{ $image->shape_image != '' ? asset('storage/canvas/' . $image->shape_image) : '' }}"
                                        data-json="{{ json_encode($image->static_information) }}"
                                        data-id="{{ $image->id }}">
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
    </section>
</div>
@push('scripts')
    <script>
        $(document).ready(function() {
            // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);
            $('input[name="design_subcategory"]').prop('checked', true);
            $('#Allcat').prop('checked', true);

            $('#Allcat').on('change', function() {
                $('.image-item-new').hide();
                $("#category_name").hide();
                $("#allchecked").hide();
                if ($(this).is(':checked')) {
                    $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', true);
                    $('.image-item').show();
                    var visibleItems = $('.all_designs:visible').length;
                    $('.total_design_count').text(visibleItems + ' Items');




                } else {
                    $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
                    $('.image-item').hide();
                    var visibleItems = $('.all_designs:visible').length;
                    $('.total_design_count').text(visibleItems + ' Items');




                }
            });

            $(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function() {
                $('.image-item-new').hide();
                $("#category_name").hide();
                $("#allchecked").hide();
                // If all individual checkboxes are checked, check "All Categories"
                const totalCheckboxes = $('input[name="design_subcategory"]:not(#Allcat)').length;
                const checkedCheckboxes = $('input[name="design_subcategory"]:not(#Allcat):checked').length;

                if (checkedCheckboxes === totalCheckboxes) {
                    $('#Allcat').prop('checked', true);
                } else {
                    $('#Allcat').prop('checked', false);
                }

                // Filter images based on checked categories
                if (checkedCheckboxes > 0) {
                    $('.image-item').hide(); // Hide all images first
                    $('input[name="design_subcategory"]:not(#Allcat):checked').each(function() {
                        const categoryId = $(this).data('category-id');
                        const subcategoryId = $(this).data('subcategory-id');

                        // Show images matching the selected categories and subcategories
                        $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                            .show();
                        var visibleItems = $('.all_designs:visible').length;
                        $('.total_design_count').text(visibleItems + ' Items');
                    });
                } else {
                    $('.image-item').hide(); // Hide all images if no checkboxes are checked
                    var visibleItems = $('.all_designs:visible').length;
                    $('.total_design_count').text(visibleItems + ' Items');
                }
            });
            $('#resetCategories').on('click', function(e) {
                $('.image-item-new').hide();
                $("#category_name").hide();
                $("#allchecked").hide();
                e.preventDefault();
                $('#Allcat').prop('checked', false);
                $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);
                $('.image-item').hide();
                var visibleItems = $('.all_designs:visible').length;
                $('.total_design_count').text(visibleItems + ' Items');
            });

            document.querySelectorAll('.collection-menu').forEach((button) => {
                button.addEventListener('click', (event) => {
                    event.stopPropagation();
                });
            });

            const $cookiesBox = $('.cookies-track');

            if (!localStorage.getItem('cookiesBoxDismissed')) {
                setTimeout(() => {
                    $cookiesBox.addClass('active');
                }, 500);
            }

            $('.close-btn').on('click', function() {
                $cookiesBox.removeClass('active');
                localStorage.setItem('cookiesBoxDismissed', 'true');
            });

            $(document).on('input', '#search_design_category', function() {
                $('.image-item-new').hide();
                $("#category_name").hide();
                $("#allchecked").hide();
                var search_value = $(this).val();
                $('#home_loader').css('display', 'flex');
                $.ajax({
                    url: base_url + "search_design",
                    method: 'GET',
                    data: {
                        search: search_value
                    },
                    success: function(response) {

                        if (response.view) {
                            $('.list_all_design_catgeory').html('');
                            $('.list_all_design_catgeory').html(response.view);
                            $('#home_loader').css('display', 'none');
                            $('.total_design_count').text(response.count + ' Items')

                        } else {
                            $('.list_all_design_catgeory').html('No Design Found');
                            $('.total_design_count').text(response.count + ' Items')
                            $('#home_loader').css('display', 'none');
                        }
                    },
                    error: function(error) {
                        toastr.error('Some thing went wrong');
                    }
                });
            });


            $(document).on('click', '#design_category', function() {

                $('.image-item-new').hide();
                $('.image-item').hide();
                const categoryId = $(this).data('category-id');
                const subcategoryId = $(this).data('subcategory-id');
                const category_name = $(this).data('category_name');
                $("#category_name").show();
                $("#allchecked").show();
                $("#category_name").text(category_name);


                $(`.image-item-new[data-category-id="${categoryId}"]`)
                    .show();
                var visibleItems = $('.all_designs:visible').length;
                $('.total_design_count').text(visibleItems + ' Items');
            });
        });

        $(document).on('click', '#allchecked', function() {
            allCheckFun()
        })

        function allCheckFun() {
            $('.image-item-new').hide();
            $("#category_name").hide();
            $("#allchecked").hide();
            $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', true);
            $('.image-item').show();
            var visibleItems = $('.all_designs:visible').length;
            $('.total_design_count').text(visibleItems + ' Items');
        }
    </script>
@endpush
