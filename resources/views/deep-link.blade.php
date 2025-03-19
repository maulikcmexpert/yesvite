<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
    
    
    <script>
        let appOpened = false;

        function openApp() {
            const now = Date.now();

            // Listen for visibility change
            document.addEventListener("visibilitychange", () => {
                if (document.hidden) {
                    appOpened = true;  // The app is opened
                }
            });

            // Try to open the app
            window.location.href = "comappyesvite://somepage";

            // Wait for 2 seconds and check if the app opened
            setTimeout(() => {
                const elapsed = Date.now() - now;
                if (!appOpened && elapsed < 2000) {
                    // If app didn't open, redirect to the App Store
                    window.location.href = "https://apps.apple.com/app/6736650042";
                }
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
