{{-- <div class="step_2" style="display: none;"> --}}
<div class="step_2">

    <div class="main-content-right">
        <div class="new_event_detail_form choose-design-form">
            <form action="">
                <h3>Choose Design</h3>



                <div class="row">
                    <div class="col-12 mb-4">
                        <div class="choose-design-search-setting">
                            <div class="position-relative w-100">
                                <input type="search" class="searchCategory" placeholder="Search name"
                                    class="form-control">
                                <span class="choose-design-seachicon">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>
                            <svg class="cursor-pointer" width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path d="M18.3335 5.4165H13.3335" stroke="#F73C71" stroke-width="1.5"
                                    stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M4.99984 5.4165H1.6665" stroke="#F73C71" stroke-width="1.5"
                                    stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M8.33317 8.33333C9.944 8.33333 11.2498 7.0275 11.2498 5.41667C11.2498 3.80584 9.944 2.5 8.33317 2.5C6.72234 2.5 5.4165 3.80584 5.4165 5.41667C5.4165 7.0275 6.72234 8.33333 8.33317 8.33333Z"
                                    stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M18.3333 14.5835H15" stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path d="M6.6665 14.5835H1.6665" stroke="#F73C71" stroke-width="1.5"
                                    stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M11.6667 17.4998C13.2775 17.4998 14.5833 16.194 14.5833 14.5832C14.5833 12.9723 13.2775 11.6665 11.6667 11.6665C10.0558 11.6665 8.75 12.9723 8.75 14.5832C8.75 16.194 10.0558 17.4998 11.6667 17.4998Z"
                                    stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </div>
                    </div>
                    <div class="designCategory">
                        <label for="" class="custome-designcategory">
                            Upload own design/card
                            <input type="file" name="custom_template" id="custom_template" accept=".jpg,.jpeg,.png"/>
                        </label>
                        @if (isset($design_category))
                            @foreach ($design_category as $category)
                                @foreach ($category->subcategory as $subcategory)
                                    @if (isset($subcategory->textdatas) && $subcategory->textdatas->isNotEmpty())
                                        <div class="col-12 subcategory-section" id="subcategory_{{ $subcategory->id }}">
                                            <div class="choose-design-cards-wrp">
                                                <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                                                    <ol class="breadcrumb">
                                                        <li class="breadcrumb-item"><a
                                                                href="#">{{ $category->category_name }}</a></li>
                                                        <li class="breadcrumb-item active" aria-current="page">
                                                            {{ $subcategory->subcategory_name }}</li>
                                                    </ol>
                                                    <h6>{{ isset($subcategory->textdatas) ? count($subcategory->textdatas) : 0 }}
                                                        Designs</h6>
                                                </nav>
                                                <div class="choose-design-cards">
                                                    @foreach ($subcategory->textdatas as $temp)
                                                        <div class="edit_design_tem design-card" data-bs-toggle="modal"
                                                            type="button" data-template="template_1"
                                                            data-image="{{ asset('storage/canvas/' . $temp->image) }}"
                                                            data-shape_image="{{ $temp->shape_image != '' ? asset('storage/canvas/' . $temp->shape_image) : '' }}"
                                                            data-json="{{ json_encode($temp->static_information) }}"
                                                            data-id="{{ $temp->id }}">
                                                            <img src="{{ asset('storage/canvas/' . $temp->filled_image) }}"
                                                                alt="">
                                                        </div>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                @endforeach
                            @endforeach
                        @endif
                    </div>
                </div>
            </form>
        </div>
    </div>


</div>
