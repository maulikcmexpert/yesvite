<div class="container-fluid">
    
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/category')}}">Category List</a></li>
                        
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

            <form method="post" action="{{ route('category.update',$catId)}}" id="updateCatForm">

                @csrf

                @method('PUT')

                <input type="hidden" name="id" id="catId" value="{{ $catId }}">

                <div class="card-body row">

                    <div class="col-lg-4">

                        <div class="form-group">

                            <label for="exampleInputEmail1">Category Name</label>

                            <input type="text" class="form-control category_name" name="category_name" placeholder="Enter Category Name" value="{{ $getCatDetail->category_name}}">

                            <span class="text-danger">{{ $errors->first('category_name.*') }}</span>
                        
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