<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/design_style')}}">Design Style List</a></li>
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

            <form method="post" action="{{ route('design_style.store')}}" id="designStyleForm">

                @csrf

                <div class="card-body row" id="appendHtml">

                    <div class="col-lg-3">
                        <div class="form-group">

                            <label for="exampleInputEmail1">Design Name</label>

                            <input type="text" class="form-control design_name" name="design_name[]" placeholder="Enter Design Name" value="{{ old('design_name.*')}}">

                            <span class="text-danger">{{ $errors->first('design_name.*') }}</span>
                            
                        </div>
                    </div>
                </div>



                <div class="text-center">

                    <button type="button" class="btn btn-primary" id="addMoreCat">Add More </button>

                </div>



                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" id="cateAdd" value="Add">



                </div>

            </form>

        </div>





    </div>

</div>



<div style="display: none;" id="AddHtml">
    <div class="col-lg-3 mb-3">
        <div class="form-group">

            <label for="">Design Name</label>

            <input type="text" class="form-control design_name" name="design_name[]" placeholder="Enter Design Name">

            <span class="text-danger">{{ $errors->first('design_name.*') }}</span>

            <!-- <div class="remove"> -->

            <i class="fa-solid fa-xmark text-danger remove"></i>

            <!-- </div> -->

        </div>
    </div>

</div>