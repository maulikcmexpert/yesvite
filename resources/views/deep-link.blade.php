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

            // Detect if the app opens successfully
            const onPageHide = () => {
                appOpened = true;  
                clearTimeout(fallbackTimer);  // Prevent App Store redirect
            };

            window.addEventListener('pagehide', onPageHide);

            // Attempt to open the app
            window.location.href = appLink;

            // Fallback to the App Store if the app doesn't open
            const fallbackTimer = setTimeout(() => {
                if (!appOpened) {
                    window.location.href = appStoreLink;
                }
            }, 1500);

            // Clean up after 2 seconds
            setTimeout(() => {
                window.removeEventListener('pagehide', onPageHide);
            }, 2000);
        }
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
