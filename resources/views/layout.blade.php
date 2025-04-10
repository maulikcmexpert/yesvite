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
              fontFamily: `"Source Sans Pro", "Arial", sans-serif`, // (string) Font family for banner texts, defaults to system safe fonts
              fallbackFontFamily: 'sans-serif', // (string) Font family for fallback icon, safe options are serif and sans-serif
              appName: 'ML', // (string) Initials for fallback icon.  Recommended 2 characters. Fallback Image uses button text and bg color
              textColor: '#222', // (string) Banner texts color (any color property value)
              buttonColor: '#000000', // (string) Button color (any background property value)
              buttonText: 'Get', // (string) Button text
              buttonTextColor: '#fff', // (string) Button Text Color (any color property value)
              iconUrl: 'https://is1-ssl.mzstatic.com/image/thumb/Purple113/v4/c4/a1/70/c4a1704e-ed21-abde-cc5b-20c33be2c6a7/AppIcon-0-0-1x_U007emarketing-0-0-0-7-0-0-sRGB-0-0-0-GLES2_U002c0-512MB-85-220-0-0.png/230x0w.webp', // (string) Icon url, defaults to avatar with appName. You can use the app logo in the appstore/playstore
              textHeading: 'Download our App!', // (string) Heading Text
              textDescription: 'Get it now, download today', // (string) Description text
              bannerColor: '#fff', // (string) Banner BG color
              linkIos: 'https://apps.apple.com/app/6736650042', // (string) Link for iOS 
              linkAndroid: 'https://play.google.com', // (string) Link for Android 
              position: 'top',
              animation: 'fadeIn', // (string) Banner animation, default 'fadeIn'. 'fadeIn' | 'scaleUp' | 'slideBottom' | 'slideTop' | 'slideLeft' | 'slideRight' | null,
              display: 'onLoad', // (string) Display options, default 'onLoad'. 'onLoad' | 'onScrollDown' | 'onScrollUp'
              radius: '0', // (string) Banner radius with units
              delay: 0, // (number) defines how much time to wait until the element shows up
              shadow: true, // (boolean) If true applies soft shadow, true | false
              useSession: true, // (boolean) If true, after closed, Banner is not shown upon page reload. Default: true
              zindex: 999999 // (number) Sets the z-index of the element
              deepLink: {
                        ios: 'comappyesvite://', // Replace with your iOS deep link URL scheme
                        android: 'intent://yesvite.com/somepage#Intent;scheme=https;package=com.yesvite.android;end;', // Replace with your Android intent URL
                        }
        }
    
    
        //   function addSmartBanner() {
        //   // only shows the banner in mobile devices & if not the app
        //     if(!deviceData.isMobile || deviceData.isCanvas ){
        //         return
        //     }
        //     new SmartBanner(SBoptions).init();
        // }
        function openAppOrStore() {
                const isIOS = /iPhone|iPad|iPod/i.test(navigator.userAgent);
                const isAndroid = /Android/i.test(navigator.userAgent);
                const now = new Date().getTime();
                let timeout;

                if (isIOS) {
                    // Try deep linking
                    window.location = SBoptions.deepLink.ios;

                    // If app is not opened in 2s, redirect to store
                    timeout = setTimeout(() => {
                    window.location = SBoptions.linkIos;
                    }, 2000);
                } else if (isAndroid) {
                    // Use intent for Android
                    window.location = SBoptions.deepLink.android;

                    timeout = setTimeout(() => {
                    window.location = SBoptions.linkAndroid;
                    }, 2000);
                }
                }

          window.addEventListener('load', openAppOrStore);
    </script>

</body>

</html>
