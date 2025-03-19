<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            const deepLink = "{{ $deepLink }}";         // Deep link for your app (myapp://)
            const universalLink = "{{ $universalLink }}"; // Use universal link for iOS (https://myapp.com/redirect)
            const fallbackUrl = "{{ $fallbackUrl }}";    // App Store or Play Store
            const fallbackTimeout = 2000;                // Fallback delay
            const userAgent = navigator.userAgent || navigator.vendor || window.opera;

            let appOpened = false;
            const start = Date.now();

            const isIOS = /iPhone|iPad|iPod/.test(userAgent);
            const isAndroid = /Android/.test(userAgent);

            // Function to try opening the app
            const tryOpenApp = () => {
                if (isIOS) {
                    // iOS: Use Universal Link first to avoid the invalid URL alert
                    window.location.href = universalLink;
                } else if (isAndroid) {
                    // Android: Use iframe deep link
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = deepLink;
                    document.body.appendChild(iframe);
                }
            };

            // Fallback handler
            const handleFallback = () => {
                const elapsed = Date.now() - start;

                // If the app didn't open, redirect to fallback
                if (!appOpened && elapsed >= fallbackTimeout) {
                    window.location.href = fallbackUrl;
                }
            };

            // Detect app opening via visibility change
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    appOpened = true;  // App opened successfully
                }
            });

            // Open the app and fallback if not installed
            tryOpenApp();

            // Fallback logic
            setTimeout(() => {
                handleFallback();
            }, fallbackTimeout);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
