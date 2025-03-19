<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open in App</title>

     <script>
        function openApp() {
            // Try opening the Yesvite app
            window.location.href ="comappyesvite://open";

            // If the app is not installed, redirect to the App Store after 2 seconds
            setTimeout(function() {
                window.location.href = "https://apps.apple.com/app/6736650042";
            }, 2000);
        }
    </script>
    
</head>
<body  onload="openApp()">
    <p>Redirecting...</p>
</body>
</html>
