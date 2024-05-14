<!DOCTYPE html>
<html lang="en">

<head>
    <title>login</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- custom-style -->
    <link href="{{ asset('public/assets/front/css/style.css')}}" rel="stylesheet">

    <!-- font-awesome-cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">


</head>

<body>


    @yield('content')


    <!-- <footer>
        <div class="container">
            <div class="footer-content d-flex justify-content-between">
                <ul class="nav">
                    <li class="nav-item">
                        <a class="nav-link" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Blog</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Help</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">API</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Privacy</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Term</a>
                    </li>
                </ul>
                <div class="footer-meta d-flex align-items-center">
                    <select class="form-select">
                        <option>English</option>
                        <option>Frach</option>
                    </select>
                    <a href="mailto:© 2023 Instagram from Meta">© 2023 Instagram from Meta</a>
                </div>
            </div>
        </div>
    </footer> -->


    <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jquery-cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- custom-js -->
    <script src="{{ asset('public/assets/front/js/common.js')}}"></script>
    <script>
        // ========= show-password ===========
        $(".toggle-password").click(function() {
            $(this).toggleClass("fa-eye-slash fa-eye");
            var input = $($(this).attr("toggle"));
            if (input.attr("type") == "password") {
                input.attr("type", "text");
            } else {
                input.attr("type", "password");
            }
        });
    </script>
    <script src="{{ asset('assets/admin/js/jquery-validate.js') }}"></script>

    <script src="{{ asset('assets/admin/js/jquery-validate-additional.js') }}"></script>
    @if(isset($js))

    @foreach($js as $value)

    <script src="{{ asset('assets/front') }}/js/{{$value}}.js"></script>

    @endforeach

    @endif
</body>

</html>