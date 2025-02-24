<!DOCTYPE html>
<html lang="en">

<head>

    {{-- <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/spectrum-colorpicker2/dist/spectrum.min.css" stylesheet.crossOrigin = "anonymous"> --}}
    <link rel="stylesheet" href="{{ asset('assets/event/css/spectrum.min.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/about-us.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/account.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/animate.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/change-pwd.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/common.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/contact.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/edit-profile.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/font.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/footer.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/guestheader.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/home.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/login.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/message.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/new_event_detail.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/notification.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/pick_card.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/privacy.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/privay-policy.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/profile.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/rsvp.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/setting.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/variable.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/edit-design.css') }}">
    
    {{-- <link rel="stylesheet" href="{{ asset('assets/css/event/style1.css') }}"> --}}
    
    <link rel="stylesheet" href="{{ asset('assets/event/css/swiper-bundle.min.css') }}">
    {{-- <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@11/swiper-bundle.min.css" /> --}}



    @isset($css)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css}}" />
    @endisset
    @isset($css1)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css1}}" />
    @endisset

    <script src="https://cdnjs.cloudflare.com/ajax/libs/fabric.js/5.3.1/fabric.min.js"></script>
    <script src="https://cdn.rawgit.com/naptha/tesseract.js/1.0.10/dist/tesseract.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html-to-image/1.11.11/html-to-image.min.js"></script>

    <x-front.header title={{$title}} page="" />
</head>

<body>
<main>
    <!-- <div id="loader" style="display: none;">
        <img src="{{asset('assets/front/loader.gif')}}" alt="loader" style="width:146px;height:146px;z-index:1000">
    </div> -->
    <div class="loader" id="loader" style="display: none;">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>
    @include($page)
   

</main>
    {{-- <x-front.footer :js="$js ?? []" /> --}}
    <x-front.footer :js="$js ?? []" page="{{ $page }}" />

    <script src="{{ asset('assets/event/js/script.js') }}"></script>
    <script src="{{ asset('assets/event/js/common.js') }}"></script>
    <script src="{{ asset('assets/event/js/wow.min.js') }}"></script>
    
</body>

</html>