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
        window.location.href ="comappyesvite://open";
    </script> --}}
    <script>
        window.onload = function () {
            var now = new Date().getTime();
            var fallbackTimeout = 1000; // Increased timeout to 3 seconds

            window.location.href ="comappyesvite://open";

            setTimeout(function () {
                if (new Date().getTime() - now < fallbackTimeout + 100) {
                    window.location.href = "{{ $fallbackUrl }}"; // Your App Store link
                }
            }, fallbackTimeout);
        };
    </script>
    
</head>
<body>
    <p>Redirecting...</p>
</body>
</html>
