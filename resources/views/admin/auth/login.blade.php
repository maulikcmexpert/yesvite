<div class="login-box">

  <!-- /.login-logo -->

  <div class="card card-outline card-primary">

    <div class="card-header text-center">


      <img src="{{asset('storage/yesvitelogo.png')}}" alt="Yesvite Logo" class="brand-image elevation-3" style="opacity: .8'">


    </div>

    <div class="card-body">





      <form action="{{URL::to('admin/login')}}" method="post" id="loginForm">

        @csrf

        <div class="input-group mb-3">

          <input name="email" type="email" value="{{ (old('email')!='')?old('email'):Cookie::get('email') }}" class="form-control" placeholder="Email">

          <div class="input-group-append">

            <div class="input-group-text">

              <span class="fas fa-envelope"></span>

            </div>

          </div>

        </div>

        <label id="email-error" class="error" for="email">@error('email'){{$message}}@enderror</label>



        <div class="input-group mb-3">

          <input name="password" type="password" value="{{ (old('password')!='')?old('password'):Cookie::get('password') }}" class="form-control" placeholder="Password">

          <div class="input-group-append">

            <div class="input-group-text">

              <span class="fas fa-lock"></span>

            </div>

          </div>

        </div>

        <label id="password-error" class="error" for="password">@error('password'){{$message}}@enderror</label>

        <div class="row">

          <div class="col-8">

            <div class="icheck-primary">

              <input type="checkbox" id="remember" name="remember" {{(Cookie::get('email')!='')?'CHECKED':''}}>

              <label for="remember">

                Remember Me

              </label>

            </div>

          </div>

          <!-- /.col -->

          <div class="col-4">

            <button type="submit" class="btn btn-primary btn-block">Sign In</button>

          </div>

          <!-- /.col -->

        </div>

      </form>





      <!-- /.social-auth-links -->



      <!-- <p class="mb-1">

        <a href="{{URL::to('admin/forgotpassword')}}">I forgot my password</a>

      </p> -->

      <!-- <p class="mb-0">

        <a href="{{URL::to('admin/register')}}" class="text-center">Register a new membership</a>

      </p> -->

    </div>

    <!-- /.card-body -->

  </div>

  <!-- /.card -->

</div>