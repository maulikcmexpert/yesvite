<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{URL::to('/admin')}}" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Update Password</p>
  
        <form action="{{URL::current()}}" method="post" id="updatePassForm">
            @csrf
            <div class="input-group mb-3">
                <input name="password" type="password" value="{{ old('password') }}" id="password" class="form-control" placeholder="Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <label id="password-error" class="error" for="password">@error('password'){{$message}}@enderror</label>
    
              <div class="input-group mb-3">
                <input name="confirm_password" type="password" value="{{ old('confirm_password') }}" class="form-control" placeholder="Confirm Password">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-lock"></span>
                  </div>
                </div>
              </div>
              <label id="confirm_password-error" class="error" for="confirm_password">@error('confirm_password'){{$message}}@enderror</label>
            <div class="row">
                <div class="col-6">
                
                </div>
                <!-- /.col -->
                <div class="col-6">
                    <button type="submit" class="btn btn-primary btn-block">Update Password</button>
                </div>
            <!-- /.col -->
            </div>
        </form>
  
        
        <!-- /.social-auth-links -->
  
         
        <p class="mb-0">
          <a href="{{URL::to('admin/login')}}" class="text-center">Login?</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>