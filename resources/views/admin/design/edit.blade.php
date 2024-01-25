<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/design')}}">Design List</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="col-md-12 pl-0">

        <div class="card card-primary categoryCard">
            <div class="card-header">
                <h3 class="card-title">Edit Design</h3>
            </div>


            <form method="post" action="{{ route('design.update',$designId)}}" id="updateDesignForm" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" id="designId" value="{{ $designId }}">
                <div class="card-body">
                    <div class="form-group">

                        <label for="exampleInputEmail1">Design Category</label>

                        <select class="form-control" name="event_design_category_id" id="event_design_category_id">
                            <option value="">Select design Category</option>
                            @foreach($getCatData as $value)
                            <option {{($value->id == $getDesignDetail->event_design_category_id)?"selected":""}} value="{{ $value->id}}">{{ $value->category_name}}</option>
                            @endforeach
                        </select>


                        <span class="text-danger">{{ $errors->first('event_design_category_id') }}</span>



                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1">Design Subcategory</label>
                        <input type="hidden" value="{{$getDesignDetail->event_design_subcategory_id}}" id="selectedSubCatId">
                        <select class="form-control" name="event_design_subcategory_id" id="event_design_subcategory_id">
                            <option value="">Select Design Subcategory</option>

                        </select>


                        <span class="text-danger">{{ $errors->first('event_design_subcategory_id') }}</span>

                    </div>


                    <div class="form-group">

                        <label for="exampleInputEmail1">Design Style</label>

                        <select class="form-control" name="event_design_style_id" id="event_design_style_id">
                            <option value="">Select Design Style</option>
                            @foreach($getDesignStyleData as $value)
                            <option {{($getDesignDetail->event_design_style_id == $value->id)?"selected":""}} value="{{ $value->id}}">{{ $value->design_name}}</option>
                            @endforeach
                        </select>


                        <span class="text-danger">{{ $errors->first('event_design_subcategory_id') }}</span>

                    </div>




                    <div class="form-group">

                        <label for="exampleInputEmail1">Upload Design</label>

                        <input type="file" class="form-control image" name="image" id="selectImage">
                        <input type="hidden" name="oldImage" value="{{$getDesignDetail->image}}">
                        <span class="text-danger">{{ $errors->first('image') }}</span>
                    </div>
                    @if($getDesignDetail->image !="" || $getDesignDetail->image !=null)
                    <img id="preview" src="{{asset('public/storage/event_design_template/' . $getDesignDetail->image)}}" alt="your image" class="mt-3" width="100px" />
                    @else
                    <img id="preview" src="{{asset('public/storage/no_image.png')}}" alt="your image" class="mt-3" style="display:block;" width="100px" />
                    @endif

                    <div class="form-group colorGrp mt-4">


                        <label for="exampleInputEmail1">Design Colors</label>
                        <div class="row">
                            <div class="checkboxes-wrp">
                                <div class="tile {{(in_array('#DB5B5B',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_one" class="form-check-input newcheckbox event_design_color" name="event_design_color[]" value="#DB5B5B" {{(in_array('#DB5B5B',$getDesignColors))?"checked":""}}>
                                    <label for="effect_one">
                                        <div class="color-box" style="background-color: #DB5B5B;"></div>
                                    </label>
                                </div>

                                <div class="tile {{(in_array('#84DB5B',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_two" class="newcheckbox event_design_color" name="event_design_color[]" value="#84DB5B" {{(in_array('#84DB5B',$getDesignColors))?"checked":""}}>
                                    <label for="effect_two">
                                        <div class="color-box" style="background-color: #84DB5B;"></div>
                                    </label>
                                </div>

                                <div class="tile {{(in_array('#5BDBCC',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_thee" class="newcheckbox event_design_color" name="event_design_color[]" value="#5BDBCC" {{(in_array('#5BDBCC',$getDesignColors))?"checked":""}}>
                                    <label for="effect_thee">
                                        <div class="color-box" style="background-color: #5BDBCC;"></div>
                                    </label>
                                </div>

                                <div class="tile {{(in_array('#DB5BB0',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_four" class="newcheckbox event_design_color" name="event_design_color[]" value="#DB5BB0" {{(in_array('#DB5BB0',$getDesignColors))?"checked":""}}>
                                    <label for="effect_four">
                                        <div class="color-box" style="background-color: #DB5BB0;"></div>
                                    </label>
                                </div>
                                <div class="tile {{(in_array('#F2F2F2',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_five" class="newcheckbox event_design_color" name="event_design_color[]" value="#F2F2F2" {{(in_array('#F2F2F2',$getDesignColors))?"checked":""}}>
                                    <label for="effect_five">
                                        <div class="color-box" style="background-color: #F2F2F2;"></div>
                                    </label>
                                </div>
                                <div class="tile {{(in_array('#202020',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_six" class="newcheckbox event_design_color" name="event_design_color[]" value="#202020" {{(in_array('#202020',$getDesignColors))?"checked":""}}>
                                    <label for="effect_six">
                                        <div class="color-box" style="background-color: #202020"></div>
                                    </label>
                                </div>
                                <div class="tile {{(in_array('#E5AA13',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_seven" class="newcheckbox event_design_color" name="event_design_color[]" value="#E5AA13" {{(in_array('#E5AA13',$getDesignColors))?"checked":""}}>
                                    <label for="effect_seven">
                                        <div class="color-box" style="background-color: #E5AA13;"></div>
                                    </label>
                                </div>
                                <div class="tile {{(in_array('#FFEA31',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_eight" class="newcheckbox event_design_color" name="event_design_color[]" value="#FFEA31" {{(in_array('#FFEA31',$getDesignColors))?"checked":""}}>
                                    <label for="effect_eight">
                                        <div class="color-box" style="background-color: #FFEA31;"></div>
                                    </label>
                                </div>
                                <div class="tile {{(in_array('#5B60DB',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_nine" class="newcheckbox event_design_color" name="event_design_color[]" value="#5B60DB" {{(in_array('#5B60DB',$getDesignColors))?"checked":""}}>
                                    <label for="effect_nine">
                                        <div class="color-box" style="background-color: #5B60DB;"></div>
                                    </label>
                                </div>
                                <div class="tile {{(in_array('#1F11C4',$getDesignColors))?'selected':''}}">

                                    <input type="checkbox" id="effect_ten" class="newcheckbox event_design_color" name="event_design_color[]" value="#1F11C4" {{(in_array('#1F11C4',$getDesignColors))?"checked":""}}>
                                    <label for="effect_ten">
                                        <div class="color-box" style="background-color: #1F11C4;"></div>
                                    </label>
                                </div>
                            </div>
                        </div>
                        <label id="event_design_color[]-error" class="error" for="event_design_color[]" style=""></label>
                    </div>


                    <div class="card-footer pl-0">
                        <input type="submit" class="btn btn-primary" value="Update">
                    </div>
            </form>
        </div>


    </div>
</div>