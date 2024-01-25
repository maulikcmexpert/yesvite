<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/subcategory')}}">Subcategory List</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">



        <div class="card card-primary categoryCard">

            <div class="card-header">

                <h3 class="card-title">Add Subcategory</h3>

            </div>





            <form method="post" action="{{ route('subcategory.store')}}" id="subCategoryForm">

                @csrf

                <div class="card-body">

                    <div class="form-group">

                        <label for="exampleInputEmail1">Category</label>

                        <select class="form-control event_design_category_id" id="event_design_category_id" name="event_design_category_id">

                            <option value="">Select Category</option>

                            @foreach($getCatData as $cat)

                            <option value="{{$cat->id}}">{{$cat->category_name}}</option>
    
                            @endforeach
                        </select>

                        <span class="text-danger">{{ $errors->first('event_design_category_id.*') }}</span>



                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1">Subcategory Name</label>

                        <input type="text" class="form-control subcategory_name" name="subcategory_name[]" placeholder="Enter Subcategory Name" value="{{ old('subcategory_name.*')}}">

                        <span class="text-danger">{{ $errors->first('subcategory_name.*') }}</span>



                    </div>

                    <div id="appendHtml">



                    </div>

                </div>



                <div class="text-right">

                    <button type="button" class="btn btn-primary" id="addMoreSubCat">Add More </button>

                </div>



                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" id="subCateAdd" value="Add">

                </div>

            </form>

        </div>

    </div>

</div>



<div style="display: none;" id="AddHtml">

    <div class="form-group">

        <label for="">Subcategory Name</label>

        <input type="text" class="form-control subcategory_name" name="subcategory_name[]" placeholder="Enter Subcategory Name">

        <span class="text-danger">{{ $errors->first('subcategory_name.*') }}</span>

        <!-- <div class="remove"> -->

        <i class="fa-solid fa-xmark text-danger remove"></i>

        <!-- </div> -->

    </div>

</div>