<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
   
    <script>
        function openApp() {
    const appLink = "comappyesvite://somepage";
    const appStoreLink = "https://apps.apple.com/app/6736650042";
    
    let appOpened = false;
    const timeout = 1500;  // Fallback timeout

    // Create an invisible iframe for app opening
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    document.body.appendChild(iframe);

    // Use page visibility API to detect if the app opened
    const onVisibilityChange = () => {
        if (document.hidden) {
            appOpened = true;
            window.location.href = appLink;  // Redirect to App Store

        }
    };
    
    document.addEventListener('visibilitychange', onVisibilityChange);

    // Open the app using the iframe
    iframe.src = appLink;
    alert(appOpened);

    // Fallback to App Store after timeout
    setTimeout(() => {
        if (!appOpened) {
            window.location.href = appStoreLink;  // Redirect to App Store
        }

        // Cleanup
        document.removeEventListener('visibilitychange', onVisibilityChange);
        document.body.removeChild(iframe);
    }, timeout);
}
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
        //         if (!appOpened && elapsed < 1500) {
        //             window.location.href = appStoreLink;  // App Store redirect
        //         }

        //         // Clean up event listener
        //         window.removeEventListener('pagehide', onPageHide);
        //     }, 1500);
        // }
    </script>
    {{-- <script>
        function openApp() {
            // Try opening the Yesvite app
            window.location.href = "comappyesvite://somepage";

            // If the app is not installed, redirect to the App Store after 2 seconds
            setTimeout(function() {
                window.location.href = "https://apps.apple.com/app/6736650042";
            }, 2000);
        }
    </script> --}}
</head>
<body onload="openApp()">
    <p>If the app does not open, <a href="https://apps.apple.com/app/6736650042">click here to download it</a>.</p>
</body>
</html>
