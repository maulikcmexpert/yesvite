<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    <script>
        window.onload = function () {
            let opened = false;  
            const fallbackTimeout = 1500;  // 1.5 seconds

            // Detect app opening (blur triggers if app opens)
            window.addEventListener('blur', () => {
                opened = true;  // App opened successfully
            });

            // Attempt to open the app using hidden iframe
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = "{{ $deepLink }}";  // Deep link to your app
            document.body.appendChild(iframe);

            // Fallback to App Store if the app doesn't open
            setTimeout(() => {
                if (!opened) {
                    window.location.href = "{{ $fallbackUrl }}";  // App Store URL
                }
                document.body.removeChild(iframe);  // Clean up iframe
            }, fallbackTimeout);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
