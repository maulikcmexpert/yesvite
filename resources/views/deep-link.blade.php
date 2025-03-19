<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            const deepLink = "{{ $deepLink }}";
            const fallbackUrl = "{{ $fallbackUrl }}";
            const fallbackTimeout = 2000;  // Fallback after 2 seconds
            const userAgent = navigator.userAgent || navigator.vendor || window.opera;

            let appOpened = false;
            const start = Date.now();

            const isIOS = /iPhone|iPad|iPod/.test(userAgent);
            const isAndroid = /Android/.test(userAgent);

            // Add hidden image trick to detect app launch
            const img = new Image();
            img.src = deepLink;
            
            img.onerror = () => {
                // If image fails to load, app is likely not installed
                appOpened = false;
            };

            img.onload = () => {
                appOpened = true;  // App opened successfully
            };

            const tryOpenApp = () => {
                if (isIOS) {
                    // iOS uses window.location for reliable deep linking
                    window.location.href = deepLink;
                } else if (isAndroid) {
                    // Android uses iframe for better compatibility
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = deepLink;
                    document.body.appendChild(iframe);
                }
            };

            // Fallback handler
            const handleFallback = () => {
                const elapsed = Date.now() - start;

                // If app is not opened, redirect to fallback
                if (!appOpened && elapsed >= fallbackTimeout) {
                    window.location.href = fallbackUrl;
                }
            };

            // Visibility change listener
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    appOpened = true;  // App opened successfully
                }
            });

            // Attempt to open the app
            tryOpenApp();

            // Trigger fallback if the app doesn't open
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
