<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="apple-itunes-app" content="app-id=6736650042">
        {{-- <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no">
        <meta name="apple-itunes-app" content="app-id=6736650042"> --}}

    @isset($css)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css}}" />
    @endisset
    @isset($css1)
    <link rel="stylesheet" href="{{asset('assets')}}/front/css/{{$css1}}" />
    @endisset
    <x-front.header title={{$title}} page={{$page}} />
</head>

<body>
    <main>
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
</main>
@if($page != 'front.event_wall.event_wall' &&  $page != 'front.event_wall.event_potluck' && $page != 'front.event_wall.event_about' &&
$page != 'front.event_wall.event_guest' && $page != 'front.event_wall.event_photos')
<x-front.bodyfooter page="{{ $page }}" />
@endif
    <x-front.footer :js="$js ?? []" page="{{ $page }}" />
    <script type="module" src="https://cdn.jsdelivr.net/npm/@mobiloud/ml-smart-banner@latest/dist/ml-smart-banner.min.js"></script>

    <script>
       const SBoptions = {
                fontFamily: `"Source Sans Pro", "Arial", sans-serif`,
                fallbackFontFamily: 'sans-serif',
                appName: 'ML',
                textColor: '#222',
                buttonColor: '#000000',
                buttonText: 'Get',
                buttonTextColor: '#fff',
                iconUrl: 'https://is1-ssl.mzstatic.com/image/thumb/Purple113/v4/c4/a1/70/c4a1704e-ed21-abde-cc5b-20c33be2c6a7/AppIcon-0-0-1x_U007emarketing-0-0-0-7-0-0-sRGB-0-0-0-GLES2_U002c0-512MB-85-220-0-0.png/230x0w.webp',
                textHeading: 'Download our App!',
                textDescription: 'Get it now, download today',
                bannerColor: '#fff',
                linkIos: 'https://apps.apple.com/app/6736650042',
                linkAndroid: 'https://play.google.com',
                position: 'top',
                animation: 'fadeIn',
                display: 'onLoad',
                radius: '0',
                delay: 0,
                shadow: true,
                useSession: true,
                zindex: 999999,

                // ✅ Correctly nested deepLink
                deepLink: {
                    ios: 'comappyesvite://',
                    android: 'intent://yesvite.com/somepage#Intent;scheme=https;package=com.yesvite.android;end;'
                }
                };

    
        //   function addSmartBanner() {
        //   // only shows the banner in mobile devices & if not the app
        //     if(!deviceData.isMobile || deviceData.isCanvas ){
        //         return
        //     }
        //     new SmartBanner(SBoptions).init();
        // }
        function openAppOrRedirect() {
            const userAgent = navigator.userAgent || navigator.vendor || window.opera;
            const isIOS = /iPad|iPhone|iPod/.test(userAgent);
            const isAndroid = /Android/.test(userAgent);

            if (isIOS) {
                window.location = SBoptions.deepLink.ios;
                setTimeout(() => {
                window.location = SBoptions.linkIos;
                }, 1500);
            } else if (isAndroid) {
                window.location = SBoptions.deepLink.android;
                setTimeout(() => {
                window.location = SBoptions.linkAndroid;
                }, 1500);
            } else {
                // Desktop fallback
                window.location = SBoptions.linkAndroid;
            }
            }

          window.addEventListener('load', openAppOrRedirect);
    </script>

</body>

</html>
