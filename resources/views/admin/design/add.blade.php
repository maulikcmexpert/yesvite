<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/design_style')}}">Design List</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="col-md-12 pl-0">

        <div class="card card-primary mt-4 categoryCard">

            <div class="card-header">

                <h3 class="card-title">{{$title}}</h3>

            </div>

            <form method="post" action="{{ route('design.store')}}" id="designForm" enctype="multipart/form-data">

                @csrf

                <div class="card-body row">

                    <div class="col-lg-4 mb-3">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Design Category</label>

                            <select class="form-control" name="event_design_category_id" id="event_design_category_id">
                                <option value="">Select design Category</option>
                                @foreach($getCatData as $value)
                                <option value="{{ $value->id}}">{{ $value->category_name}}</option>
                                @endforeach
                            </select>
                            <span class="text-danger">{{ $errors->first('event_design_category_id') }}</span>
                        </div>
                    </div>
                     
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label for="exampleInputEmail1">Design Subcategory</label>
                            <select class="form-control" name="event_design_subcategory_id" id="event_design_subcategory_id">
                                <option value="">Select Design Subcategory</option>

                            </select>
                            <span class="text-danger">{{ $errors->first('event_design_subcategory_id') }}</span>
                        </div>
                    </div>
                    
                    <div class="col-lg-4">
                        <div class="form-group">

                        <label for="exampleInputEmail1">Design Style</label>

                        <select class="form-control" name="event_design_style_id" id="event_design_style_id">
                            <option value="">Select Design Style</option>
                            @foreach($getDesignStyleData as $value)
                            <option value="{{ $value->id}}">{{ $value->design_name}}</option>
                            @endforeach
                        </select>

                        <span class="text-danger">{{ $errors->first('event_design_subcategory_id') }}</span>

                        </div>
                    </div>

                    <div class="col-lg-12 mb-3">
                        <div class="form-group row">
                            <div class="col-lg-6">
                                <label for="exampleInputEmail1">Upload Design</label>
                                <input type="file" class="form-control image" name="image" id="selectImage">
                                <span class="text-danger">{{ $errors->first('image') }}</span>
                            </div>
                            <div class="col-lg-6">
                                <img id="preview" src="#" alt="your image" class="mt-3" style="display:none;" width="100px" />
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12">
                    <div class="form-group colorGrp">


                        <label for="exampleInputEmail1">Design Colors</label>
                        <div class="row">
                            <div class="checkboxes-wrp">


                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_one1" class="newcheckbox event_design_color form-check-input" name="event_design_color[]" value="#DB5B5B">
                                    <label for="effect_one1">
                                        <div class="color-box" style="background-color: #DB5B5B;"></div>
                                    </label>
                                </div>

                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_two2" class="newcheckbox event_design_color" name="event_design_color[]" value="#84DB5B">
                                    <label for="effect_two2">
                                        <div class="color-box" style="background-color: #84DB5B;"></div>
                                    </label>
                                </div>

                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee3" class="newcheckbox event_design_color" name="event_design_color[]" value="#5BDBCC">
                                    <label for="effect_thee3">
                                        <div class="color-box" style="background-color: #5BDBCC;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee4" class="newcheckbox event_design_color" name="event_design_color[]" value="#DB5BB0">
                                    <label for="effect_thee4">
                                        <div class="color-box" style="background-color: #DB5BB0;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee5" class="newcheckbox event_design_color" name="event_design_color[]" value="#F2F2F2">
                                    <label for="effect_thee5">
                                        <div class="color-box" style="background-color:#F2F2F2;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee6" class="newcheckbox event_design_color" name="event_design_color[]" value="#202020">
                                    <label for="effect_thee6">
                                        <div class="color-box" style="background-color:#202020;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee7" class="newcheckbox event_design_color" name="event_design_color[]" value="#E5AA13">
                                    <label for="effect_thee7">
                                        <div class="color-box" style="background-color:#E5AA13;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee8" class="newcheckbox event_design_color" name="event_design_color[]" value="#FFEA31">
                                    <label for="effect_thee8">
                                        <div class="color-box" style="background-color:#FFEA31;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee9" class="newcheckbox event_design_color" name="event_design_color[]" value="#5B60DB">
                                    <label for="effect_thee9">
                                        <div class="color-box" style="background-color:#5B60DB;"></div>
                                    </label>
                                </div>
                                <div class="tile">
                                    <span>
                                        <i class="fas fa-check"></i>
                                    </span>
                                    <input type="checkbox" id="effect_thee10" class="newcheckbox event_design_color" name="event_design_color[]" value="#1F11C4">
                                    <label for="effect_thee10">
                                        <div class="color-box" style="background-color:#1F11C4;"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <label id="event_design_color[]-error" class="error" for="event_design_color[]" style=""></label>
                        </div>
                    </div>


                    
                </div>

                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" id="cateAdd" value="Add">



                </div>

            </form>

        </div>





    </div>

</div>