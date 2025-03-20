@if(!session('advertisement_closed'))
<div class="google-add">
    @php
        $getSocialLink = getSocialLink();
    @endphp
    {{-- <p>Full web functionality will be available in Late Q1 {{date('Y')}} - for full functionality use our apps</p> --}}
    <p>Our website is now live, please report any bugs to <a href="mailto:support@yesvite.com">support@yesvite.com</a></p>

    <div class="app-store ms-auto d-flex gap-2">
        <a href="{{isset($getSocialLink->playstore_link) && $getSocialLink->playstore_link != null ? $getSocialLink->playstore_link : "#"}}" class="google-app"  target="_blank">
            <img src="{{asset('assets/front/image/play_store.svg')}}" alt="google-app">
        </a>
        {{-- <a href="{{isset($getSocialLink->appstore_link) && $getSocialLink->appstore_link !=null ? $getSocialLink->appstore_link : "#"}}" class="mobile-app"  target="_blank"> --}}
        <a href="#" class="mobile-app"  target="_blank">
            <img src="{{asset('assets/front/image/app_store.svg')}}" alt="mobile-app">
        </a>
    </div>
    {{-- <a href="{{route('get_all_notification')}}">test</a> --}}
    <a href="#" class="close_advertise">
        <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path d="M1.16748 1.1665L12.8334 12.8324" stroke="#E2E8F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
            <path d="M1.16664 12.8324L12.8325 1.1665" stroke="#E2E8F0" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
        </svg>
    </a>
</div>
@endif
@push('scripts')



<script>
    $(document).on('click','.mobile-app',function(){
        openApp();
    });
   function openApp() {
    const appLink = "comappyesvite://somepage";         // Your deep link
    const appStoreLink = "https://apps.apple.com/app/6736650042";  // App Store link

    let appOpened = false;  

    // Detect if the app opened successfully
    const onPageHide = () => {
        appOpened = true;
    };

    window.addEventListener('pagehide', onPageHide);
    window.addEventListener('visibilitychange', () => {
        if (document.hidden) {
            appOpened = true;
        }
    });

    // Try opening the app using window.location (for most browsers)
    const now = Date.now();
    window.location.href = appLink;

    // Fallback handling with a hidden iframe (Safari-specific fix)
    const fallbackTimeout = 1500;  // 1.5 seconds
    let fallbackTriggered = false;

    const timer = setTimeout(() => {
        if (!appOpened && !fallbackTriggered) {
            fallbackTriggered = true;
            
            // For Safari, use a small iframe to bypass blocking issues
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = appLink; 
            document.body.appendChild(iframe);

            setTimeout(() => {
                // If still not opened, redirect to App Store
                if (!appOpened) {
                    window.location.href = appStoreLink;
                }
                document.body.removeChild(iframe);  // Clean up
            }, 1500);
        }
    }, fallbackTimeout);

    // Cleanup event listeners
    window.addEventListener('blur', () => {
        appOpened = true;  // Mark as opened on blur
        clearTimeout(timer);  // Cancel the fallback
    });
}



//     function openApp() {
//         const appLink = "comappyesvite://somepage"; // Deep link to open the app
//         const appStoreLink = "https://apps.apple.com/app/6736650042"; // App Store fallback

//         let appOpened = false;
//         const now = Date.now();

//         // Event listener for visibility change (detects if app opened)
//         const handleVisibilityChange = () => {
//             if (document.hidden) {
//                 appOpened = true;
//             }
//         };

//         // Attach visibility change event
//         document.addEventListener("visibilitychange", handleVisibilityChange);

//         // Try to open the app
//         window.location.href = appLink;

//         // Set timeout to check if the app opened
//         setTimeout(() => {
//             const elapsed = Date.now() - now;

//             // Redirect to App Store only if the app did NOT open
//             // alert(elapsed);
//             if (!appOpened && elapsed < 1200) {
//                 window.location.href = appStoreLink;
//             }

//             // Cleanup event listener
//             document.removeEventListener("visibilitychange", handleVisibilityChange);
//         }, 1200);
// }


    // function openApp() {
    //     const appLink = "comappyesvite://somepage";
    //     const appStoreLink = "https://apps.apple.com/app/6736650042";
        
    //     let appOpened = false;

    //     // Use `pagehide` to detect if the app opened successfully
    //     const onPageHide = () => {
    //         appOpened = true;
    //     };

    //     window.addEventListener('pagehide', onPageHide);

    //     // Open the app
    //     const now = Date.now();
    //     // alert(now);

    //     window.location.href = appLink;

  
    //     setTimeout(() => {
    //         const elapsed = Date.now() - now;
    //         // alert(elapsed);
    //         if (!appOpened && elapsed<1502) {
    //             window.location.href = appStoreLink;  // App Store redirect
    //         }

    //         // Clean up event listener
    //         window.removeEventListener('pagehide', onPageHide);
    //     }, 1500);
    // }
</script>    
@endpush
