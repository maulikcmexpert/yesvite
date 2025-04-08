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
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">



        <div class="card card-primary categoryCard add-template-wrp">

            <div class="card-header">

                <h3 class="card-title">Add Template</h3>

            </div>





            <form method="post" action="{{ route('create_template.store')}}" id="templateForm"
                enctype="multipart/form-data">

                @csrf

                <div class="card-body row" id="appendHtml">
                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Category</label>

                            <select class="form-control design_id" id="event_design_category_id"
                                name="event_design_category_id">

                                <option value="">Select Category</option>

                                @foreach($getDesignData as $cat)

                                <option value="{{$cat->id}}">{{$cat->category_name}}</option>

                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('event_design_category_id.*') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Sub Category</label>
                            {{-- <select class="form-control event_design_subcategory_id" id="event_design_sub_category_id" name="event_design_sub_category_id"> --}}
                            {{-- <select class="form-control event_design_subcategory_id" id="event_design_sub_category_id" name="event_design_sub_category_id[]" multiple>

                                <option value="">Select subcategory</option> --}}

                            {{-- </select> --}}

                            <div class="wrapper new-wrp">
                                <button class="form-control toggle-next select-subcat-btn ellipsis" type="button">Select
                                    subbcategory <i class="fa-solid fa-angle-down"></i></button>
                                <div class="checkboxes select-subcat-inner edit-subcategory-drp" id="Sub Category">


                                    <div class="inner-wrap" id="event_design_sub_category_id">


                                        {{-- <label>
                                        <input type="checkbox" value="dolor" class="ckkBox val" />
                                        <span>Dolor (34) </span>
                                      </label>
                            
                                      <label>
                                        <input type="checkbox" value="lorem" class="ckkBox val" />
                                        <span>Lorem (234)</span>
                                      </label>
                            
                                      <label>
                                        <input type="checkbox" value="ipsum" class="ckkBox val" />
                                        <span>Ipsum (12)</span>
                                      </label>
                                      
                                      <label>
                                        <input type="checkbox" value="dolor" class="ckkBox val" />
                                        <span>Dolor 2 (34) </span>
                                      </label>
                            
                                      <label>
                                        <input type="checkbox" value="lorem" class="ckkBox val" />
                                        <span>Lorem 2 (234)</span>
                                      </label>
                            
                                      <label>
                                        <input type="checkbox" value="ipsum" class="ckkBox val" />
                                        <span>Ipsum 2 (12)</span>
                                      </label> --}}
                                    </div>
                                </div>
                            </div>
                            <span class="text-danger subcategory_error_bx">{{ $errors->first('event_design_sub_category_id.*') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">


                        <div class="form-group">

                            <label for="exampleInputEmail1">Image</label>
                            <input type="file" class="form-control image" name="image" placeholder="choose image"
                                id="image" value="">
                            <span class="text-danger">{{ $errors->first('image.*') }}</span>
                            <img id="add_preview_image" src="" alt="Template Image" width="100" class="mt-2"
                                style="display: none;">



                        </div>

                    </div>
                    <div class="col-lg-3 mb-3">


                        <div class="form-group">

                            <label for="exampleInputEmail1">Filled Image</label>
                            <input type="file" class="form-control image" name="filled_image" placeholder="choose image"
                                id="filled_image" value="">
                            <span class="text-danger">{{ $errors->first('filled_image.*') }}</span>
                            <img id="add_preview_filled_image" src="" alt="Template Image" width="100" class="mt-2"
                                style="display: none;">



                        </div>

                    </div>

                    <div class="col-lg-3 mb-3">


                        <div class="form-group">
                            <label for="exampleInputEmail1">Tags</label>
                            <input type="text" id="tags" name="tags" class="form-control" data-role="tagsinput" />
                            <span class="text-danger">{{ $errors->first('tags.*') }}</span>
                        </div>

                    </div>
                </div>





                {{-- <div class="text-center">

                    <button type="button" class="btn btn-primary" id="addMoreTemplate">Add More </button>

                </div> --}}



                <div class="card-footer">
                    <input type="button" class="btn btn-primary" id="templateAdd" value="Add">
                </div>

            </form>

        </div>

    </div>

</div>



<div style="display: none;" id="AddHtml">
    <div class="col-lg-3 mb-3">
        <div class="form-group">
            <label for="">Image</label>
            <input type="file" class="form-control image" name="image" placeholder="Enter image ">
            <span class="text-danger">{{ $errors->first('image.*') }}</span>
            <i class="fa-solid fa-xmark text-danger remove"></i> <!-- Remove button -->
        </div>
    </div>
</div>