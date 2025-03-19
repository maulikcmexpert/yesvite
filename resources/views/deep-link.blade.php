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

            // Detect visibility change to confirm app open event
            const onVisibilityChange = () => {
                if (document.hidden) {
                    appOpened = true;
                }
            };
            
            document.addEventListener('visibilitychange', onVisibilityChange);

            // Open the app
            const now = Date.now();
            window.location.href = appLink;

            // Automatically redirect to App Store if the app is not installed
            setTimeout(() => {
                if (!appOpened) {
                    window.location.href = appStoreLink;  // App Store fallback
                }
            }, 1500);

            // Clean up the event listener
            setTimeout(() => {
                document.removeEventListener('visibilitychange', onVisibilityChange);
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
