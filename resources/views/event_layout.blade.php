<!DOCTYPE html>
<html lang="en">

<head>


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

    <!-- <link rel="stylesheet" href="{{ asset('assets/event/css/message.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('assets/event/css/new_event_detail.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/notification.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/pick_card.css') }}">

    <!-- <link rel="stylesheet" href="{{ asset('assets/event/css/privacy.css') }}">

<link rel="stylesheet" href="{{ asset('assets/event/css/privay-policy.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('assets/event/css/profile.css') }}">

    <!-- <link rel="stylesheet" href="{{ asset('assets/event/css/rsvp.css') }}"> -->

    <link rel="stylesheet" href="{{ asset('assets/event/css/setting.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/style.css') }}">

    <link rel="stylesheet" href="{{ asset('assets/event/css/variable.css') }}">

    @isset($css)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css}}" />
    @endisset
    @isset($css1)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css1}}" />
    @endisset
    <x-front.header title={{$title}} />
</head>

<body>
    <div id="loader" style="display: none;">
        <img src="{{asset('assets/front/loader.gif')}}" alt="loader" style="width:146px;height:146px;z-index:1000">
    </div>
    @include($page)
    <x-front.footer :js="$js ?? []" />
</body>

</html>