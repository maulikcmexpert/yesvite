{{-- <!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>

     <script>
        function openApp() {
            // Try opening the Yesvite app
            window.location.href ="comappyesvite://open";

            // If the app is not installed, redirect to the App Store after 2 seconds
            setTimeout(function() {
                window.location.href = "https://apps.apple.com/app/6736650042";
            }, 2000);
        }
    </script>
    
</head>
<body  onload="openApp()">
    <p>Redirecting...</p>
</body>
</html> --}}
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            const appDeepLink = "comappyesvite://open";
            const universalLink = "https://yesvite.cmexpertiseinfotech.in/redirect";   // For Safari reliability
            const fallbackUrl = "https://apps.apple.com/app/6736650042";
            
            const isIOS = /iPhone|iPad|iPod/.test(navigator.userAgent);
            const firstTimeKey = "yesviteFirstVisit";
            const fallbackTimeout = localStorage.getItem(firstTimeKey) ? 2000 : 3000;  // Longer timeout on first visit
            let appOpened = false;

            // Mark first-time visit
            if (!localStorage.getItem(firstTimeKey)) {
                localStorage.setItem(firstTimeKey, "true");
            }

            // Detect if the app opens (user leaves the page)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    appOpened = true;
                }
            });

            const openApp = () => {
                if (isIOS) {
                    // Use Universal Link for iOS
                    window.location.href = universalLink;
                } else {
                    // Use Deep Link for Android
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = appDeepLink;
                    document.body.appendChild(iframe);
                }

                // Fallback after timeout
                setTimeout(() => {
                    if (!appOpened) {
                        window.location.href = fallbackUrl;
                    }
                }, fallbackTimeout);
            };

            openApp();
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
