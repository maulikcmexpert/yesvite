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
            const fallbackTimeout = 2000;  // 2 seconds fallback delay

            let opened = false;

            // Detect visibility change (if user switches to the app, the page becomes hidden)
            const handleVisibilityChange = () => {
                if (document.hidden) {
                    opened = true;  // App opened successfully
                }
            };

            document.addEventListener("visibilitychange", handleVisibilityChange);

            // Try opening the app in an iframe (invisible)
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = deepLink;
            document.body.appendChild(iframe);

            // Fallback to store if the app doesn't open
            setTimeout(() => {
                if (!opened) {
                    window.location.href = fallbackUrl;
                }
            }, fallbackTimeout);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
