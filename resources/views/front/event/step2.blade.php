<div class="step_2">
<section class="collection-wrapper">
    <div class="">
        <div class="content">
            
            <div class="position-relative search-wrapper">
                <input type="search" id="search_design_category" placeholder="Search design categories" class="">
                <span class="">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                           
                                @foreach ($categories as $textData)
                                        <div class="accordion-item">
                                            <h2 class="accordion-header" id="heading{{ $textData->categories->id }}">
                                                <button class="accordion-button" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ $textData->categories->id }}" aria-expanded="true"
                                                    aria-controls="collapse{{ $textData->categories->id }}">
                                                    {{ $textData->categories->category_name }}
                                                </button>
                                            </h2>
                                            <div id="collapse{{ $textData->categories->id }}"
                                                class="accordion-collapse collapse"
                                                aria-labelledby="heading{{ $textData->categories->id }}"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <ul>
                                                            <li>
                                                                <div class="d-flex align-items-center justify-content-between">
                                                                    <label class="form-check-label" for="subcategory{{ $textData->subcategories->id }}">
                                                                        {{ $textData->subcategories->subcategory_name }}
                                                                    </label>
                                                                    <input class="form-check-input" type="checkbox"
                                                                        id="subcategory{{ $textData->subcategories->id }}"
                                                                        data-category-id="{{ $textData->categories->id }}"
                                                                        data-subcategory-id="{{ $textData->subcategories->id }}">
                                                                </div>
                                                            </li>
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
            <h5 class="total-items ms-auto total_design_count">{{count($categories)}} Items</h5>
        </div>
        {{-- {{ dd($categories);}} --}}
        <div class="row list_all_design_catgeory">
            @foreach ($categories as $textData)
        
                    <div class="col-xxl-2 col-xl-3 col-lg-4 col-md-4 col-sm-6 col-6 mt-xl-4 mt-sm-4 mt-4 wow fadeInDown image-item all_designs"
                        data-wow-duration="2s" data-wow-delay="0" data-wow-offset="0"
                        data-category-id="{{ $textData->categories->id }}" data-subcategory-id="{{ $textData->subcategories->id }}">
                        <a href="javascript:;" class="collection-card card-blue">
                            <div class="card-img edit_design_tem design-card" data-image="{{ asset('storage/canvas/' . $textData->image) }}"
                                data-shape_image="{{ $textData->shape_image != '' ? asset('storage/canvas/' . $textData->shape_image) : '' }}"
                                data-json="{{ json_encode($textData->static_information) }}"
                                data-id="{{ $textData->id }}">
                                <img src="{{ asset('storage/canvas/' . $textData->filled_image) }}" alt="shower-card">
                            </div>
                            <h4>{{ $textData->categories->category_name }}</h4>
                        </a>
                    </div>
        
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

    $('#Allcat').on('change', function() {
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

    $('.close-btn').on('click', function () {
        $cookiesBox.removeClass('active');
        localStorage.setItem('cookiesBoxDismissed', 'true');
    });

    $(document).on('input','#search_design_category',function(){
        var search_value=$(this).val();
        $('#home_loader').css('display','flex');
        $.ajax({
            url: base_url + "search_features", 
            method: 'GET',
            data: { search: search_value}, 
            success: function (response) {
                console.log("Remove successful: ", response);
    
                if (response.view) {
                 $('.list_all_design_catgeory').html('');
                 $('.list_all_design_catgeory').html(response.view);
                 $('#home_loader').css('display','none');
                 $('.total_design_count').text(response.count +' Items')
                    
                } else {
                    $('.list_all_design_catgeory').html('No Design Found');
                    $('.total_design_count').text(response.count +' Items')
                    $('#home_loader').css('display','none');
                }
            },
            error: function (error) {
               toastr.error('Some thing went wrong');
            }
        });
    });
});
    </script>
@endpush