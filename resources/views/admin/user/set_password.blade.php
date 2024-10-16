<div class="container-fluid">
    
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/subcategory')}}">Set User Temporary Password</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">

        <div class="card card-primary categoryCard">

            <div class="card-header">

                <h3 class="card-title">Set User Temporary Password</h3>

            </div>

            <form method="post" action="{{route('users.update',encrypt($user_id))}}" id="updatePasswordForm">

                @csrf

                @method('PUT')

                <input type="hidden" name="id" id="subcatId" value="">

                

                <div class="card-body row">
                
                    <div class="col-lg-3">
                        <div class="form-group">

                            <label for="exampleInputEmail1">Password</label>

                            <input type="text" class="form-control subcategory_name" name="password" placeholder="Enter Temporary Password" value="">

                            <span class="text-danger"></span>

                        </div>
                    </div>
                    <div class="col-lg-3">
                        <div class="form-group">
                            <label for="require_new_password">
                                <input type="checkbox" name="require_new_password" id="require_new_password">
                                Require New Password on Login
                            </label>
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