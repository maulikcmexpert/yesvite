<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/users')}}">Users</a></li>
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

            <form method="post" action="{{ route('users.store')}}" id="addUser_form" enctype="multipart/form-data">

                @csrf

                <div class="card-body row">

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="firstname">First Name</label>
                            <input type="text" class="form-control firstname" name="firstname" placeholder="Enter First Name" value="">
                            <span class="text-danger">{{ $errors->first('firstname') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="lastname">Last Name</label>
                            <input type="text" class="form-control lastname" name="lastname" placeholder="Enter Last Name" value="">
                            <span class="text-danger">{{ $errors->first('lastname') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="email">Email</label>
                            <input type="text" class="form-control email" name="email" placeholder="Enter Email Address" value="">
                            <span class="text-danger">{{ $errors->first('email') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="phone_number">Phone Number</label>
                            <input type="text" class="form-control phone_number" name="phone_number" placeholder="Enter Phone Number" value="" oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                            <span class="text-danger">{{ $errors->first('phone_number') }}</span>
                        </div>
                    </div>

                    <div class="col-lg-3 col-md-6 mb-3">
                        <div class="form-group">
                            <label for="require_new_password">
                                <input type="checkbox" name="require_new_password" id="require_new_password">
                                Require New Password on Login
                            </label>
                        </div>
                    </div>



                </div>

                <div class="card-footer">
                    <input type="button" class="btn btn-primary" id="UserAdd" value="Add">
                </div>

            </form>

        </div>





    </div>

</div>