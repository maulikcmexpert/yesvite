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

            // Detect if the app opens
            const onPageHide = () => {
                appOpened = true;  // App opened successfully
            };

            window.addEventListener('pagehide', onPageHide);

            // Open the app
            const now = Date.now();
            window.location.href = appLink;

            // Fallback: If the app doesn't open, go to the App Store
            setTimeout(() => {
                const elapsed = Date.now() - now;

                if (!appOpened && elapsed < 1500) {
                    window.location.href = appStoreLink;  // Redirect to App Store
                }

                // Clean up the event listener
                window.removeEventListener('pagehide', onPageHide);
            }, 1500);
        }
    </script>
    {{-- <script>
        function openApp() {
            const appLink = "comappyesvite://somepage";
            const appStoreLink = "https://apps.apple.com/app/6736650042";
            
            let appOpened = false;

            // Use `pagehide` to detect if the app opened successfully
            const onPageHide = () => {
                appOpened = true;
            };

            window.addEventListener('pagehide', onPageHide);

            // Open the app
            const now = Date.now();
            window.location.href = appLink;

            // Fallback: Redirect to App Store if app doesn't open
            setTimeout(() => {
                const elapsed = Date.now() - now;

                if (!appOpened && elapsed < 1500) {
                    window.location.href = appStoreLink;  // App Store redirect
                }

                // Clean up event listener
                window.removeEventListener('pagehide', onPageHide);
            }, 1500);
        }
    </script> --}}
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
