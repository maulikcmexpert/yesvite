<title>{{$title}}</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="csrf-token" content="{{ csrf_token() }}" />
<!-- Latest compiled and minified CSS -->
<link rel="icon" type="image/x-icon" href="{{ asset('storage/favicon.png')}}">
<!-- custom-style -->

<link rel="stylesheet" href="{{asset('assets/front/css/wallcss/bootstrap.min.css')}}" />
<link rel="stylesheet" href="{{asset('assets/front/css/wallcss/main.css')}}" />
{{-- <link rel="stylesheet" href="{{ asset('assets/front/css/wallcss/main.css')}}" /> --}}
<link href="{{asset('assets/front/css/style.css')}}" rel="stylesheet">
<link href="{{asset('assets/front/css/transactionhistory.css')}}" rel="stylesheet">
<link rel="stylesheet" href="{{asset('assets/front/css/new_css/bootstrap.min.css')}}" />

{{-- <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> --}}
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.css" integrity="sha512-2eMmukTZtvwlfQoG8ztapwAH5fXaQBzaMqdljLopRSA0i6YKM8kBAOrSSykxu9NN9HrtD45lIqfONLII2AFL/Q==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://code.jquery.com/ui/1.14.1/themes/base/jquery-ui.css" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" crossorigin="anonymous">
<!-- font-awesome-cdn -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css" crossorigin="anonymous">

<!-- ==== animation ===== -->
<link rel="stylesheet" href="{{asset('assets/front/css/animate.css')}}">
<input type="hidden" id="base_url" value="{{url('/')}}/" />

<!-- owl-carousel -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/OwlCarousel2/2.3.4/assets/owl.carousel.min.css" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.css" crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/owl-carousel/1.3.3/owl.theme.min.css" crossorigin="anonymous">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/intl-tel-input/17.0.13/css/intlTelInput.css" crossorigin="anonymous">

<link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css" crossorigin="anonymous"/>

<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" crossorigin="anonymous">
{{-- <link rel="stylesheet" href="http://fabricjs.com/css/googlefonts.css"> --}}
@if(config('app.url') == 'https://yesvite.cmexpertiseinfotech.in')
<link rel="stylesheet" href="{{ asset('assets/event/css/stylesheet.css') }}">
@else
<link rel="stylesheet" href="{{ asset('assets/event/css/stylesheet_live.css') }}">
@endif

@if(isset($page) && $page=="front.profile")
    <link rel="stylesheet" href="{{ asset('assets/event/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/event/css/contact.css') }}">
@endif






<link href="https://cdn.jsdelivr.net/bootstrap.timepicker/0.2.6/css/bootstrap-timepicker.min.css" rel="stylesheet" crossorigin="anonymous"/>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" crossorigin="anonymous">

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.8.1/css/all.min.css' crossorigin="anonymous">

<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datetimepicker/4.17.47/css/bootstrap-datetimepicker.min.css' crossorigin="anonymous">

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/magnific-popup.js/1.1.0/magnific-popup.min.css" crossorigin="anonymous">



<link rel="stylesheet" href="{{asset('assets/front/css/wall.css')}}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css"
integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg=="
crossorigin="anonymous" referrerpolicy="no-referrer" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" />
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/apexcharts/4.0.0/apexcharts.min.css" />
