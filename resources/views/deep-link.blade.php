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

            // Handle iOS separately due to Safari's quirks
            const isIOS = /iPhone|iPad|iPod/.test(userAgent);
            const isAndroid = /Android/.test(userAgent);

            const tryOpenApp = () => {
                if (isIOS) {
                    // Open app using a new window for better reliability on iOS
                    window.location.href = deepLink;
                } else if (isAndroid) {
                    // Use an iframe on Android
                    const iframe = document.createElement('iframe');
                    iframe.style.display = 'none';
                    iframe.src = deepLink;
                    document.body.appendChild(iframe);
                }
            };

            // Fallback to store if the app doesn't open
            const handleFallback = () => {
                const elapsed = Date.now() - start;

                // If the app is not opened (user did not leave the page)
                if (elapsed < fallbackTimeout + 100) {
                    window.location.href = fallbackUrl;
                }
            };

            // Check if user left the page (app opened)
            document.addEventListener('visibilitychange', () => {
                if (document.hidden) {
                    appOpened = true;
                }
            });

            // Try opening the app
            tryOpenApp();

            // Trigger fallback only if app is not opened
            setTimeout(() => {
                if (!appOpened) {
                    handleFallback();
                }
            }, fallbackTimeout);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
