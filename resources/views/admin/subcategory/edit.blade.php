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

                <h3 class="card-title">Edit Category</h3>

            </div>

            <form method="post" action="{{ route('subcategory.update',$subcatId)}}" id="updateSubCatForm">

                @csrf

                @method('PUT')

                <input type="hidden" name="id" id="subcatId" value="{{ $subcatId }}">

                

                <div class="card-body">
                    <div class="form-group">

                        <label for="exampleInputEmail1">Category</label>

                        <select class="form-control event_design_category_id" id="event_design_category_id" name="event_design_category_id">

                            <option value="">Select Category</option>

                            @foreach($getCatData as $cat)

                            <option {{($cat->id == $getSubCatDetail->event_design_category_id)?"selected":""}} value="{{$cat->id}}">{{$cat->category_name}}</option>

                            @endforeach



                        </select>

                        <span class="text-danger">{{ $errors->first('event_design_category_id') }}</span>

                    </div>

                    <div class="form-group">

                        <label for="exampleInputEmail1">Subcategory Name</label>

                        <input type="text" class="form-control subcategory_name" name="subcategory_name" placeholder="Enter Subcategory Name" value="{{ $getSubCatDetail->subcategory_name}}">

                        <span class="text-danger">{{ $errors->first('subcategory_name') }}</span>



                    </div>

                </div>





                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" value="Update">

                </div>

            </form>

        </div>





    </div>

</div>