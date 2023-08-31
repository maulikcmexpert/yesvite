<div class="login-box">
    <!-- /.login-logo -->
    <div class="card card-outline card-primary">
      <div class="card-header text-center">
        <a href="../../index2.html" class="h1"><b>Admin</b>LTE</a>
      </div>
      <div class="card-body">
        <p class="login-box-msg">Enter your registered email</p>
  
        <form action="{{URL::to('admin/forgotpassword')}}" method="post" id="forgotForm">
            @csrf
          <div class="input-group mb-3">
            <input name="email" type="email"  value="{{ old('email') }}" class="form-control" placeholder="Email">
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-envelope"></span>
              </div>
            </div>
          </div>
          <label id="email-error" class="error" for="email">@error('email'){{$message}}@enderror</label>
        <div class="row">
            <div class="col-8">
              
            </div>
            <!-- /.col -->
            <div class="col-4">
              <button type="submit" class="btn btn-primary btn-block">Send mail</button>
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