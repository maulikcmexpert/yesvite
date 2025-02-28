<div class="step_2">
    <section class="collection-wrapper">
        <div class="">
            <div class="content">

                <div class="position-relative search-wrapper">
                    <div class="position-relative">
                        <input type="search" id="search_design_category" placeholder="Search design categories"
                            class="" autocomplete="off">
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
                    <div id="filtered_results" class="filtered-results-container d-none"></div>

                </div>
            </div>

            <div class="filter-main-wrp categoryNew">
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
                <h5 class="total-items ms-auto total_design_count">{{ $imagecount }} Items</h5>
            </div>

            <label for="" class="custome-designcategory">
                Upload own design/card
                <input type="file" name="custom_template" id="custom_template" accept=".jpg,.jpeg,.png" />
            </label>

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
                                    'static_information' => json_encode($image->static_information),
                                    'shape_image' =>
                                        $image->shape_image != '' ? asset('storage/canvas/' . $image->shape_image) : '',
                                    'image_path' => asset('storage/canvas/' . $image->filled_image),
                                    'image' => asset('storage/canvas/' . $image->image),
                                ]);
                            }
                        }
                    }

                    shuffle($randomIds);
                    $randomIds = array_slice($randomIds, 0, 30);

                    // $randomImages = $allImages->shuffle()->take(30);

                @endphp

                @foreach ($allImages as $image)
                    <div class="col-xl-2 col-lg-3 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item all_designs
                         {{ in_array($image['imageId'], $randomIds) ? 'default_show' : 'd-none' }} "
                        data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                        data-category-id="{{ $image['category_id'] }}"
                        data-subcategory-id="{{ $image['subcategory_id'] }}"
                        data-category_name="{{ $image['category_name'] }}">

                        <div class="card-img collection-card card-blue edit_design_tem design-card"
                            data-image="{{ $image['image'] }}" data-shape_image="{{ $image['shape_image'] }}"
                            data-json="{{ $image['static_information'] }}" data-id="{{ $image['imageId'] }}">
                            <img src="{{ $image['image_path'] }}" alt="shower-card">
                        </div>

                    </div>
                @endforeach
            </div>


            {{-- <div class="row list_all_design_catgeory_new">
                <div class="d-flex align-items-center mb-2" style="gap: 15px">
                    <p id="allchecked" data-categoryid="0" data-subcategoryid="0" style="display:none"><i
                            class="fa-solid fa-arrow-left" style="color: #212529; cursor: pointer;"></i></p>
                    <h5 id="category_name" class="mb-0" style="display:none">Test category</h5>
                </div>
                <div class="row list_all_design_wrp">
                    @foreach ($categories as $category)
                        @foreach ($category->subcategory as $subcategory)
                            @foreach ($subcategory->textdatas as $image)
                                <div style="display: none"
                                    class="col-xxl-2 col-xl-3 col-lg-4 col-md-3 col-sm-4 col-6 mt-xl-4 mt-sm-4 mt-4  image-item-new all_designs"
                                    data-category-id="{{ $category->id }}"
                                    data-subcategory-id="{{ $subcategory->id }}">

                                    <a href="javascript:;" class="collection-card card-blue">
                                        <div class="card-img edit_design_tem design-card"
                                            data-image="{{ asset('storage/canvas/' . $image->image) }}"
                                            data-shape_image="{{ $image->shape_image != '' ? asset('storage/canvas/' . $image->shape_image) : '' }}"
                                            data-json="{{ json_encode($image->static_information) }}"
                                            data-id="{{ $image->id }}">
                                            <img src="{{ asset('storage/canvas/' . $image->filled_image) }}"
                                                alt="shower-card">
                                        </div>
                                        {{-- <h4>{{ $category->category_name }}</h4>
                                    </a>
                                </div>
                            @endforeach
                        @endforeach
                    @endforeach
                </div>

            </div> --}}
        </div>
    </section>
</div>
@push('scripts')
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

        $(document).ready(function() {
            const urlParams = new URLSearchParams(window.location.search);
            const designId = urlParams.get('design_id'); // Get 'design_id' from URL

            if (designId) {
                // Find the element with class 'edit_design_tem' and matching data-id, then trigger click
                $('.edit_design_tem[data-id="' + designId + '"]').trigger('click');
                urlParams.delete('design_id');
                const newUrl = window.location.pathname + (urlParams.toString() ? '?' + urlParams.toString() : '');
                window.history.replaceState(null, '', newUrl);
            }
            $(".default_show").show();

            $('input[name="design_subcategory"]').prop('checked', false);
            $('#Allcat').prop('checked', false);


            updateTotalCount();


            // $('input[type="checkbox"]:not(#Allcat)').prop('checked', true);

            $('#Allcat').on('change', function() {
                $(".image-item").show(); // Show all default images



                if ($(this).is(':checked')) {
                    // Show all default images and hide new images
                    $(".default_show").show();
                    $('.image-item-new').hide();


                    // Hide category name and checkbox container
                    $("#category_name").hide();
                    $("#allchecked").hide();

                    // Check all subcategory checkboxes
                    $('input[name="design_subcategory"]').prop('checked', true);
                } else {
                    // Uncheck all subcategories
                    $('input[name="design_subcategory"]').prop('checked', false);

                    $(".image-item").removeClass('d-none');
                    // Hide all images
                    $(".default_show").show();
                    $('.image-item-new').hide();
                }

                updateTotalCount();
            });

            // Handle individual subcategory checkbox change
            $(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function() {
                $(".image-item").hide(); // Hide all default images
                $(".image-item-new").hide(); // Hide all new images

                let default_s = 0;

                $('input[name="design_subcategory"]:checked').each(function() {
                    default_s++;
                    $(".image-item").removeClass('d-none');
                    const categoryId = $(this).data('category-id');
                    const subcategoryId = $(this).data('subcategory-id');

                    // Show filtered images matching checked categories and subcategories
                    $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                        .show();

                });

                if (default_s == 0) {
                    $(".image-item").removeClass('d-none');
                    $(".default_show").show();
                }
                updateTotalCount();
            });

            // Function to update total count of visible items
            function updateTotalCount() {
                var visibleItems = $('.image-item:visible, .image-item-new:visible').length;
                $('.total_design_count').text(visibleItems + ' Items');
            }


            $('#resetCategories').on('click', function(e) {
                e.preventDefault();
                $(".categoryNew").show();
                $(".subcategoryNew").hide();
                $(".image-item-new").hide(); // Hide filtered items
                $(".image-item").show(); // Show default images
                $("#category_name").hide();
                $("#allchecked").hide();
                $("#Allcat").prop("checked", false);
                $('input[name="design_subcategory"]:not(#Allcat)').prop('checked', false);

                var visibleItems = $('.image-item:visible').length;
                $('.total_design_count').text(visibleItems + ' Items');
            });

            $('#filtered_results').hide();

            $('#filtered_results').addClass('d-none'); // Ensure it is hidden by default

            $('#search_design_category').on('keyup', function() {
                let query = $(this).val().toLowerCase().trim();
                let results = '';

                if (query.length > 0) {
                    $('#filtered_results').removeClass('d-none'); // Show results container
                    designData.forEach(category => {
                        if (category.name.toLowerCase().includes(query)) {
                            results +=
                                `<div class="search-item category" data-category-id="${category.id}" data-name="${category.name}">${category.name}</div>`;
                        }
                        category.subcategories.forEach(subcategory => {
                            if (subcategory.name.toLowerCase().includes(
                                    query)) {
                                results +=
                                    `<div class="search-item subcategory" data-id="${subcategory.id}" data-category-id="${category.id}" data-name="${subcategory.name}">${subcategory.name}</div>`;
                            }
                        });
                    });

                    $('#filtered_results').html(results);
                } else {
                    $('#filtered_results').html('').addClass('d-none'); // Hide when empty
                    $('input[name="design_subcategory"]').prop('checked', false);
                    $('.default_show').show();
                    updateTotalCount();
                }
            });

            // Handle item selection from search results
            $(document).on('click', '.search-item', function() {
                let selectedText = $(this).data('name');
                let categoryId = $(this).data('category-id');
                let subcategoryId = $(this).data('id');

                $('#search_design_category').val(selectedText);
                $('#filtered_results').html('').addClass(
                    'd-none'); // Hide results after selection
                $('.image-item').hide();

                if (categoryId && subcategoryId) {
                    $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                        .show();
                } else if (categoryId) {
                    $(`.image-item[data-category-id="${categoryId}"]`).show();
                }

                $(`input[name="design_subcategory"][data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                    .prop('checked', true);

                $('.total_design_count').text($('.image-item:visible').length + ' Items');
            });

            // Hide results when input is cleared
            $('#search_design_category').on('input', function() {
                let query = $(this).val().trim();
                if (query === '') {
                    $('#filtered_results').html('').addClass('d-none'); // Hide when cleared
                    $(".default_show").show();
                    $('input[name="design_subcategory"]').prop('checked', false);
                    updateTotalCount();
                }
            });


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




        $(document).on('change', 'input[name="design_subcategory"]:not(#Allcat)', function() {
            $(".image-item").hide(); // Hide default images
            $(".image-item-new").hide(); // Hide new items initially

            $('input[name="design_subcategory"]:checked').each(function() {
                const categoryId = $(this).data('category-id');
                const subcategoryId = $(this).data('subcategory-id');

                // Show filtered images
                $(`.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                    .show();
            });

            var visibleItems = $('.image-item-new:visible').length;
            $('.total_design_count').text(visibleItems + ' Items');
        });



        $(document).on('click', '#allchecked', function() {
            const categoryId = $(this).attr('data-categoryid');
            const subcategoryId = $(this).attr('data-subcategoryid');
            allCheckFun(categoryId, subcategoryId)
        })

        function allCheckFun(categoryIds, subcategoryIds) {
            $('input[name="design_subcategory_new"]').prop('checked', false)
            // $('input[name="design_subcategory"]').prop('checked', true)
            $(".categoryNew").show();
            $(".subcategoryNew").hide();
            $(".image-item-new").hide();
            $("#category_name").hide();
            $("#allchecked").hide();
            // $('input[name="design_subcategory"]:not(#Allcat)').prop("checked", true);
            // $("#Allcat").prop('checked', true)
            // $('.image-item').show();
            // var visibleItems = $('.all_designs:visible').length;
            // $('.total_design_count').text(visibleItems + ' Items');


            $('input[name="design_category"]:not(#Allcat):checked').each(
                function() {

                    const categoryId = $(this).data("category-id");

                    const subcategoryId = $(this).data("subcategory-id");

                    // // Show images matching the selected categories and subcategories
                    $(`.image-item[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
                        .show();
                    var visibleItems = $(".all_designs:visible").length;
                    $(".total_design_count").text(visibleItems + " Items");
                }
            );

            // let totalCheckboxes = $('input[name="design_subcategory_new"]:not(#Allcat)').length;

            // let checkedCheckboxes = $(`.subcategory_${categoryIds}:not(#Allcat):checked`).length;

            // if(checkedCheckboxes == 0){
            //     $('.categoryChecked_'+categoryIds).prop('checked',false);
            //     $(`.image-item[data-category-id="${categoryIds}"]`).hide();
            // }

            $(`.subcategoryChecked_${subcategoryIds}:checked`).each(function() {

                $(`.image-item-new[data-category-id="${categoryIds}"][data-subcategory-id="${subcategoryIds}"]`)
                    .show();
                $('.subcategoryChecked_' + subcategoryIds).prop('checked', false)
            });

            if ($("#search_design_category").val() == "") {
                return
            }
            $("#search_design_category").val('')
            let search_value = '';
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
                        $('.total_design_count').text(response.total_textdatas + ' Items')

                    } else {
                        $('.list_all_design_catgeory').html('No Design Found');
                        $('.total_design_count').text(response.total_textdatas + ' Items')
                        $('#home_loader').css('display', 'none');
                    }
                },
                error: function(error) {
                    toastr.error('Some thing went wrong');
                }
            });
        }


        // $(document).on(
        //     "change",
        //     'input[name="design_subcategory_new"]:not(#Allcat)',
        //     function () {
        //         $(".image-item-new").hide();
        //         $("#category_name").show();
        //         $("#allchecked").show();
        //         // If all individual checkboxes are checked, check "All Categories"
        //         const totalCheckboxes = $(
        //             'input[name="design_subcategory_new"]:not(#Allcat)'
        //         ).length;
        //         const checkedCheckboxes = $(
        //             'input[name="design_subcategory_new"]:not(#Allcat):checked'
        //         ).length;



        //         // Filter images based on checked categories
        //         if (checkedCheckboxes > 0) {
        //             $(".image-item").hide(); // Hide all images first
        //             $('input[name="design_subcategory_new"]:not(#Allcat):checked').each(
        //                 function () {
        //                     const categoryId = $(this).data("category-id");
        //                     const subcategoryId = $(this).data("subcategory-id");

        //                     $(`.image-item-new[data-category-id="${categoryId}"][data-subcategory-id="${subcategoryId}"]`)
        //                         .show();

        //                     var visibleItems = $(".all_designs:visible").length;
        //                     $(".total_design_count").text(visibleItems + " Items");
        //                 }
        //             );
        //         } else {
        //             $(".image-item-new").hide(); // Hide all images if no checkboxes are checked
        //             var visibleItems = $(".all_designs:visible").length;
        //             $(".total_design_count").text(visibleItems + " Items");
        //         }
        //     }
        // );

        $("#resetCategoriesNew").on("click", function(e) {



            e.preventDefault();
            $("#Allcat").prop("checked", false);
            $('input[name="design_subcategory_new"]:not(#Allcat)').prop(
                "checked",
                false
            );
            $(".image-item-new").hide();
            var visibleItems = $(".all_designs:visible").length;
            $(".total_design_count").text(visibleItems + " Items");
        });
    </script>
@endpush
