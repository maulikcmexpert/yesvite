{{-- {{dd($getTemData->subcategories);}} --}}
@php
$pivotSubcategoryIds = $getTemData->subcategories->pluck('id')->toArray();
// dd($pivotSubcategoryIds);
@endphp

<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/create_template')}}">Template List</a>
                        </li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="card card-primary categoryCard add-template-wrp">
            <div class="card-header">
                <h3 class="card-title">Edit Template</h3>
            </div>

            <!-- Form for editing template -->
            <form method="post" action="{{ route('create_template.update', $getTemData->id)}}" id="templateEditForm"
                enctype="multipart/form-data">
                @csrf
                @method('PUT')

                <div class="card-body row" id="appendHtml">
                    <!-- Design Selection -->
                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="design_id">Design</label>
                            <select class="form-control design_id" id="event_design_category_id"
                                name="event_design_category_id">
                                <!-- <option value="">Select Design</option> -->
                                @foreach($getDesignData as $design)
                                <option value="{{ $design->id }}"
                                    {{ $design->id == $getTemData->event_design_category_id ? 'selected' : '' }}>
                                    {{ $design->category_name }}
                                </option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('event_design_category_id') }}</span>
                        </div>
                    </div>

                    <!-- Subcategory Selection -->
                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="event_design_subcategory_id">Subcategory</label>

                            <div class="col-md-12">
                                <div class="wrapper">
                                    <button class="form-control toggle-next select-subcat-btn ellipsis"
                                        type="button">Select subbcategory</button>
                                    <div class="checkboxes select-subcat-inner" id="Lorems">


                                        <div class="inner-wrap" id="event_design_subcategory_id">
                                            @foreach($getSubCatDetail as $subcategory)
                                            <label><input type="checkbox" value="{{ $subcategory->id }}"
                                                    class="ckkBox val" name="subcategory[]"
                                                    {{ in_array($subcategory->id, $pivotSubcategoryIds) ? 'checked' : '' }} /><span>
                                                    {{ $subcategory->subcategory_name }}</span> </label><br>
                                            @endforeach
                                        </div>
                                    </div>
                                </div>
                            </div>
                            {{-- <select class="form-control event_design_subcategory_id" id="event_design_sub_category_id" name="event_design_sub_category_id">
                                @foreach($getSubCatDetail as $subcategory)
                               
                                <option value="{{ $subcategory->id }}"
                            {{ $subcategory->id == $getTemData->event_design_sub_category_id ? 'selected' : '' }}>
                            {{ $subcategory->subcategory_name }}
                            </option>
                            @endforeach
                            </select> --}}
                            <span class="text-danger">{{ $errors->first('event_design_sub_category_id') }}</span>
                        </div>
                    </div>

                    <!-- Image Upload -->
                    <!-- <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control image" id="upload_image" name="image">
                            @if($getTemData->image)
                            <img src="{{ asset('storage/canvas/'. $getTemData->image) }}" alt="Template Image" width="100" class="mt-2">
                            @else
                            <img id="preview_image" src="" alt="Template Image" width="100" class="mt-2" style="display: none;">
                            @endif
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        </div>
                    </div> -->

                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="image">Image</label>
                            <input type="file" class="form-control image" id="upload_image" name="image">
                            @if($getTemData->image)
                            <img id="preview_image" src="{{ asset('storage/canvas/' . $getTemData->image) }}"
                                alt="Template Image" width="100" class="mt-2">
                            @else
                            <img id="preview_image" src="" alt="Template Image" width="100" class="mt-2"
                                style="display: none;">
                            @endif
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        </div>
                    </div>


                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="image">Filled Image</label>
                            <input type="file" class="form-control image" id="upload_filled_image" name="filled_image">
                            @if($getTemData->filled_image)
                            <img id="preview_filled_image"
                                src="{{ asset('storage/canvas/'. $getTemData->filled_image) }}" alt="Template Image"
                                width="100" class="mt-2">
                            @else
                            <img id="preview_filled_image" src="" alt="Template Image" width="100" class="mt-2"
                                style="display: none;">
                            @endif
                            <span class="text-danger">{{ $errors->first('image') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 mb-3">


                        <div class="form-group">
                            <label for="exampleInputEmail1">Tags</label>
                            <input type="text" id="tags" name="tags" class="form-control" data-role="tagsinput"
                                value="{{$getTemData->tags}}" />
                            <span class="text-danger">{{ $errors->first('tags.*') }}</span>
                        </div>

                    </div>
                </div>

                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" value="Update">
                </div>
            </form>
        </div>
    </div>
</div>