<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        {{-- <li class="breadcrumb-item"><a href="{{URL::to('/admin/category')}}">Category</a></li> --}}
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>


    <div class="col-md-12">



        <div class="card card-primary  categoryCard">

            <div class="card-header">

                <h3 class="card-title">Change Password</h3>

            </div>





            <form method="post" action="{{ route('changePassword')}}" id="ChangePasswordForm">

                @csrf
                
                <div class="card-body row"  id="appendHtml">

                    <div class="col-3">

                        <div class="form-group">

                            <label for="exampleInputEmail1">Current Password</label>

                            <input type="password" class="form-control current_password" name="current_password" id="current_password" placeholder="Enter Current Password" value="">

                            <span class="text-danger">{{ $errors->first('current_password') }}</span>
                        
                        </div>

                        <div class="form-group">

                            <label for="exampleInputEmail1">New Password</label>

                            <input type="password" class="form-control new_password" name="new_password" id="new_password" placeholder="Enter New Password" value="">

                            <span class="text-danger">{{ $errors->first('new_password') }}</span>
                        
                        </div>

                        <div class="form-group">

                            <label for="exampleInputEmail1">Confirm New Password</label>

                            <input type="password" class="form-control confirm_password" name="confirm_password" id="confirm_password"placeholder="Enter again new password" value="">

                            <span class="text-danger">{{ $errors->first('confirm_password') }}</span>
                        
                        </div>


                    </div>
                    
                   

                </div>





                <div class="card-footer">
                    <input type="submit" class="btn btn-primary" id="admin_change_password" value="Update Password">
                </div>

            </form>

        </div>





    </div>

</div>



<div style="display: none;" id="AddHtml">
  
       <div class="col-lg-3 mb-3">
            <div class="form-group">

                <label for="">Category Name</label>

                <input type="text" class="form-control category_name" name="category_name[]" placeholder="Enter Category Name">

                <span class="text-danger">{{ $errors->first('category_name.*') }}</span>

                <!-- <div class="remove"> -->

                <i class="fa-solid fa-xmark text-danger remove"></i>

                 <!-- </div> -->

            </div>
       </div>
   
    

</div>