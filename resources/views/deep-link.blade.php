<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            var now = new Date().getTime();
            var fallbackTimeout = 1500; // 1.5 seconds

            // Create an iframe to attempt deep linking
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = "{{ $deepLink }}";  // Deep link
            document.body.appendChild(iframe);

            // Fallback to App Store if app is not installed
            setTimeout(function () {
                if (new Date().getTime() - now < fallbackTimeout + 100) {
                    window.location.href = "{{ $fallbackUrl }}";  // App Store URL
                }
            }, fallbackTimeout);

            // Clean up the iframe after the timeout
            setTimeout(() => {
                document.body.removeChild(iframe);
            }, fallbackTimeout + 500);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
