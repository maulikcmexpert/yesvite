<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="{{URL::to('/admin')}}" class="h1"><b>Admin</b></a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Register Your Self</p>
  
        <form action="{{URL::to('admin/register')}}" method="post" id="registerPost">
            @csrf
        
            <div class="input-group mb-3">
                <input name="name" type="text"  value="{{ old('name') }}" class="form-control" placeholder="name">
                <div class="input-group-append">
                  <div class="input-group-text">
                    <span class="fas fa-user"></span>
                  </div>
                </div>
            </div>
            <label id="name-error" class="error" for="name">@error('name'){{$message}}@enderror</label>

          <div class="input-group mb-3">
            <input name="email" type="email"  value="{{ old('email') }}" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <label id="email-error" class="error" for="email">@error('email'){{$message}}@enderror</label>

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
            <div class="col-8">
              
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Sign In</button>
            </div>
            <!-- /.col -->
          </div>
        </form>
  
        
        <!-- /.social-auth-links -->
  
        
        <p class="mb-0">
          <a href="{{URL::to('admin/login')}}" class="text-center">Already have account? Click to login...</a>
        </p>
      </div>
      <!-- /.card-body -->
    </div>
    <!-- /.card -->
  </div>