<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
   
    <script>
        function openApp() {
            const now = Date.now();
            let appOpened = false;  // Flag to track if the app was opened

            const appLink = "your-app-scheme://somepage";   // Deep link to open the app
            const appStoreLink = "https://apps.apple.com/app/6736650042";  // App Store link

            // Attempt to open the app
            window.location.href = appLink;

            // Event listener to detect if the app is opened
            const onPageHide = () => {
                appOpened = true;
            };
            window.addEventListener('pagehide', onPageHide);

            // Fallback: Redirect to App Store if the app doesn't open
            setTimeout(() => {
                const elapsed = Date.now() - now;

                if (!appOpened && elapsed < 1500) {
                    window.location.href = appStoreLink;  // Redirect to App Store
                }

                // Clean up event listener
                window.removeEventListener('pagehide', onPageHide);
            }, 1500);

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
