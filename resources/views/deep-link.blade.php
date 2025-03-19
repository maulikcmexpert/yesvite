<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>
    {{-- <script>
        window.onload = function () {
            var now = new Date().getTime();
            var fallbackTimeout = 2000;  // Fallback after 2 seconds

            // Attempt to open the mobile app using the deep link
            window.location.href = "{{ $deepLink }}";

            // If the app is not installed, redirect to the fallback URL
            setTimeout(function () {
                if (new Date().getTime() - now < fallbackTimeout + 100) {
                    window.location.href = "{{ $fallbackUrl }}";
                }
            }, fallbackTimeout);
        };
    </script> --}}
    <script>
        window.onload = function () {
            var now = new Date().getTime();
            var fallbackTimeout = 2000;
    
            window.location.href ="comappyesvite://open";
    
            setTimeout(function () {
                if (new Date().getTime() - now < fallbackTimeout + 100) {
                    window.location.href = "https://apps.apple.com/app/6736650042";
                }
            }, fallbackTimeout);
        };
    </script>
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
