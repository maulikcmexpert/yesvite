<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
  
    @isset($css)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css}}" />
    @endisset
    @isset($css1)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css1}}" />
    @endisset
    <x-front.header title={{$title}} page={{$page}} />
</head>

<body>
    <!-- <div id="home_loader" style="display: none;">
        <img src="{{asset('assets/front/loader.gif')}}" alt="loader" style="width:146px;height:146px;z-index:1000">
    </div> -->
    <div class="loader" id="home_loader" style="display: none;">
        <div></div>
        <div></div>
        <div></div>
        <div></div>
    </div>

    <x-front.bodyheader title={{$title}} />

    @include($page)
{{--   
    <x-front.bodyfooter page={{$page}} />

    <x-front.footer :js="$js ?? []" /> --}}
    <x-front.bodyfooter page="{{ $page }}" />

    <x-front.footer :js="$js ?? []" page="{{ $page }}" />
</body>

</html>