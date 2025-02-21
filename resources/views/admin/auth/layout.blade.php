<!DOCTYPE html>

<html lang="en">



<head>

    <meta charset="utf-8">

    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} Login</title>

    <meta name="csrf-token" content="{{ csrf_token() }}" />
    <link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.png')}}">



    <!-- Google Font: Source Sans Pro -->

    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

    <!-- Font Awesome -->

    <link rel="stylesheet" href="{{asset('assets/admin/plugins/fontawesome-free/css/all.min.css')}}">

    <!-- icheck bootstrap -->

    <link rel="stylesheet" href="{{asset('assets/admin/plugins/icheck-bootstrap/icheck-bootstrap.min.css')}}">

    <!-- Theme style -->

    <link rel="stylesheet" href="{{asset('assets/admin/assets/css/adminlte.min.css')}}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" crossorigin="anonymous">

    <style>
        :root {
            --primaryColor: #EA555C;
            --ButtonColor: #EA555C;
            --sidebarNavActive: rgb(231, 50, 128, 0.4);
        }

        .login-box .card-primary.card-outline {
            border-color: var(--ButtonColor) !important;
        }

        .login-box .brand-image {
            width:100% !important;
            max-width: 150px !important;
            box-shadow: inherit !important;
        }

        .login-box .input-group-text {
            border-color: var(--ButtonColor) !important;
            background: var(--ButtonColor) !important;
        }

        .login-box .input-group-text span.fas {
            color: #fff !important;
        }

        .login-box label.error{
            color:red;
            font-size: 14px;
            font-weight: 400 !important;
        }

        .login-box .btn.btn-primary {
            border-color: var(--ButtonColor) !important;
            background: var(--ButtonColor) !important;
        }

        .icheck-primary>input:first-child:checked+label::before {
            background-color: var(--primaryColor) !important;
            border-color: var(--primaryColor) !important;
        }

        .icheck-primary>input:first-child:not(:checked):not(:disabled):hover+label::before {
            border-color: var(--primaryColor) !important;
        }

        input:focus {
            border: 1px solid var(--primaryColor) !important;
        }

        .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
            background-color: var(--BottonColor);
            border-color: transparent !important;
        }

        [class*=icheck-]>input:first-child+label::before {
            border: 2px solid var(--ButtonColor) !important;
        }

        @media only screen and (max-width:575px) {
            .login-box .brand-image {
                max-width: 100px !important;
            }
        }
    </style>
</head>


<body class="hold-transition login-page">

    <input type="hidden" id="base_url" value="{{URL::to('/')}}">

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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-mockjax/2.6.1/jquery.mockjax.min.js" integrity="sha512-LbhUoRYSZ3tFp6RrcQOwGL2P/SlbfF9B+2yiJAMcJhuxJQTgvzWaG6W+XxX9t8+aQ8z+zUxx/XQ0fOo1/ft4tA==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    @if(isset($js))

    @foreach($js as $value)

    <script src="{{ asset('assets/admin/') }}/js/{{$value}}.js"></script>

    @endforeach

    @endif

<script>
        $(document).ready(function () {
            toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                        };
                    @if (session('msg'))
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                        };
                        toastr.success("{{ session('msg') }}");
                    @endif
                    @if (session('msg_error'))
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                        };
                        toastr.error("{{ session('msg_error') }}");
                    @endif
                    @if (session('error'))
                        toastr.options = {
                            "closeButton": true,
                            "progressBar": true,
                            "positionClass": "toast-top-right",
                        };
                        toastr.error("{{ session('error') }}");
                    @endif
                });
</script>
</body>



</html>