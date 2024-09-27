<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/template')}}">Template List</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">



        <div class="card card-primary categoryCard">

            <div class="card-header">

                <h3 class="card-title">Add Template</h3>

            </div>





            <form method="post" action="{{ route('create_template.store')}}" id="templateForm" enctype="multipart/form-data">

                @csrf

                <div class="card-body row" id="appendHtml">
                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Design</label>

                            <select class="form-control design_id" id="event_design_category_id" name="event_design_category_id">

                                <option value="">Select Design</option>

                                @foreach($getDesignData as $cat)

                                <option value="{{$cat->id}}">{{$cat->category_name}}</option>

                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('event_design_category_id.*') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Design</label>
                            <select class="form-control event_design_subcategory_id" id="event_design_sub_category_id" name="event_design_sub_category_id">

                                <option value="">Select subcategory</option>

                                @foreach($getsubcatData as $cat)

                                <option value="{{$cat->id}}">{{$cat->subcategory_name}}</option>

                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('event_design_sub_category_id.*') }}</span>
                        </div>
                    </div>
                    <div class="col-lg-3 mb-3">


                        <div class="form-group">

                            <label for="exampleInputEmail1">Image</label>
                            <input type="file" class="form-control image" name="image" placeholder="choose image" id="image" value="">
                            <span class="text-danger">{{ $errors->first('image.*') }}</span>
                            <img id="add_preview_image" src="" alt="Template Image" width="100" class="mt-2" style="display: none;">



                        </div>

                    </div>
                    <div class="col-lg-3 mb-3">


                        <div class="form-group">

                            <label for="exampleInputEmail1">Filled Image</label>
                            <input type="file" class="form-control image" name="filled_image" placeholder="choose image" id="filled_image" value="">
                            <span class="text-danger">{{ $errors->first('filled_image.*') }}</span>
                            <img id="add_preview_filled_image" src="" alt="Template Image" width="100" class="mt-2" style="display: none;">



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