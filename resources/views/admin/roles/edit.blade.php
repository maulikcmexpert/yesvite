{{dd('get_role_data');}}
<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/roles')}}">Role List</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">

        <div class="card card-primary  categoryCard">

            <div class="card-header">

                <h3 class="card-title">Edit Role</h3>

            </div>


            <form method="post" action="{{ route('roles.store')}}" id="roleStoreForm">

                @csrf

                <div class="card-body row" id="appendHtml">

                    <div class="col-lg-4">
                        <div class="form-group">

                            <label for="exampleInputEmail1">Name</label>

                            <input type="text" class="form-control" name="name" placeholder="Enter Name" value="">

                            <span class="text-danger">{{ $errors->first('event_type.*') }}</span>

                        </div>
                    </div>


                    <div class="col-lg-4">
                        <div class="form-group">

                            <label for="exampleInputEmail1">Email</label>

                            <input type="text" class="form-control email" name="email" placeholder="Enter Email" value="{{ old('event_type.*')}}">

                            <span class="text-danger">{{ $errors->first('event_type.*') }}</span>

                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">

                            <label for="exampleInputEmail1">Password</label>

                            <input type="password" class="form-control" name="password" placeholder="Enter Password" value="{{ old('event_type.*')}}">

                            <span class="text-danger">{{ $errors->first('event_type.*') }}</span>

                        </div>
                    </div>

                    <div class="col-lg-4">
                        <div class="form-group">

                            <label for="exampleInputEmail1">Role</label>

                            <input type="text" class="form-control" name="role" value="Designer" placeholder="Enter Role" readonly/>

                            <span class="text-danger">{{ $errors->first('event_type.*') }}</span>

                        </div>
                    </div>

                </div>

                {{-- <div class="text-center">

                    <button type="button" class="btn btn-primary" id="addMoreEventType">Add More </button>

                </div> --}}



                <div class="card-footer">

                    <input type="submit" class="btn btn-primary" id="EventTypeAdd" value="Add">

                </div>

            </form>

        </div>

    </div>

</div>



<div style="display: none;" id="AddHtml">
    <div class="col-lg-4 mb-3">
        <div class="form-group">

        <label for="exampleInputEmail1">Event Type</label>
        <input type="text" class="form-control event_type" name="event_type[]" placeholder="Enter Event Type" value="{{ old('event_type.*')}}">

        <span class="text-danger">{{ $errors->first('event_type.*') }}</span>

        <!-- <div class="remove"> -->

        <i class="fa-solid fa-xmark text-danger remove"></i>

        <!-- </div> -->

        </div>
    </div>
</div>