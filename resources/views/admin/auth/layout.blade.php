<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>{{ config('app.name') }} Login</title>
  <meta name="csrf-token" content="{{ csrf_token() }}" />

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="{{asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">
  <!-- Theme style -->
  <link rel="stylesheet" href="{{asset('assets/admin/assets/css/adminlte.min.css')}}">
</head>
<body class="hold-transition login-page">
    <input type="hidden" id="base_url" value="{{URL::to('/')}}" >
    @yield('content')
 
<!-- /.login-box -->

<!-- jQuery -->
<script src="{{asset('assets/admin/plugins/jquery/jquery.min.js')}}"></script>
<!-- Bootstrap 4 -->
<script src="{{asset('assets/admin/plugins/bootstrap/js/bootstrap.bundle.min.js')}}"></script>
<!-- AdminLTE App -->
<script src="{{asset('assets/admin/assets/js/adminlte.min.js')}}"></script>
<script src="{{ asset('assets/admin/js/jquery-validate.js') }}"></script>
<script src="{{ asset('assets/admin/js/jquery-validate-additional.js') }}"></script>
@if(isset($js))
    @foreach($js as $value)
        <script src="{{ asset('assets/admin/') }}/js/{{$value}}.js"></script>
    @endforeach
@endif
</body>
</html>
