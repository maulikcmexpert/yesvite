<!DOCTYPE html>
<html lang="en">

<head>
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