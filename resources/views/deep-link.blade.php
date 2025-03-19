<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            var clicked = false;
            var fallbackTimeout = 1500; // 1.5 seconds

            // Detect if the page is hidden (app opened)
            document.addEventListener("visibilitychange", function () {
                if (document.hidden) {
                    clicked = true;  // App opened
                }
            });

            // Try to open the app using hidden iframe
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = "{{ $deepLink }}";  // Your deep link
            document.body.appendChild(iframe);

            // Fallback to App Store if the app doesn't open
            setTimeout(function () {
                if (!clicked) {
                    window.location.href = "{{ $fallbackUrl }}";  // App Store URL
                }
            }, fallbackTimeout);

            // Cleanup the iframe
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
