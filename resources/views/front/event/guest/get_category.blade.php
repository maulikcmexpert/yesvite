
@if (isset($design_category))
    @foreach ($design_category as $category)
        @foreach ($category->subcategory as $subcategory)
            @if (isset($subcategory->textdatas) && $subcategory->textdatas->isNotEmpty())
                <div class="col-12 subcategory-section" id="subcategory_{{ $subcategory->id }}">
                    <div class="choose-design-cards-wrp">
                        <nav style="--bs-breadcrumb-divider: '>';" aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="#">{{ $category->category_name }}</a></li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    {{ $subcategory->subcategory_name }}</li>
                            </ol>
                            <h6>{{ isset($subcategory->textdatas) ? count($subcategory->textdatas) : 0 }}
                                Designs</h6>
                        </nav>
                        <div class="choose-design-cards">
                            @foreach ($subcategory->textdatas as $temp)
                                <div class="edit_design_tem design-card" data-bs-toggle="modal" type="button"
                                    data-template="template_1"
                                    data-image="{{ asset('storage/canvas/' . $temp->image) }}"
                                    data-shape_image="{{ $temp->shape_image != '' ? asset('storage/canvas/' . $temp->shape_image) : '' }}"
                                    data-json="{{ json_encode($temp->static_information) }}"
                                    data-id="{{ $temp->id }}">
                                    <img src="{{ asset('storage/canvas/' . $temp->filled_image) }}" alt="">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif
        @endforeach
    @endforeach
@endif
