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

            // Detect if the app opened successfully
            const onPageHide = () => {
                appOpened = true;  // App opened successfully
            };
            
            window.addEventListener('pagehide', onPageHide);

            // Use a hidden iframe to open the app
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            document.body.appendChild(iframe);
            iframe.src = appLink;

            // Fallback: Redirect to App Store automatically if the app doesn't open
            const fallbackTimer = setTimeout(() => {
                if (!appOpened) {
                    window.location.href = appStoreLink;  // Automatic redirect to App Store
                }

                // Cleanup
                window.removeEventListener('pagehide', onPageHide);
                document.body.removeChild(iframe);
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
