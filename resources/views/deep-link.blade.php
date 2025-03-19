<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            var now = new Date().getTime();
            var fallbackTimeout = 2000;  // Fallback after 2 seconds

            // Attempt to open the app using iframe to prevent double redirection
            var iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = "{{ $deepLink }}";
            document.body.appendChild(iframe);

            // Fallback to App Store if the app is not installed
            setTimeout(function () {
                var elapsedTime = new Date().getTime() - now;

                // Only go to the App Store if the app didn't open
                if (elapsedTime < fallbackTimeout + 100) {
                    window.location.href = "{{ $fallbackUrl }}";
                }
            }, fallbackTimeout);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
