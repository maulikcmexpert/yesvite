<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
    
    
    <script>
        function openApp() {
            const now = Date.now();
            let timeout;

            // Create an iframe to trigger the app launch
            const iframe = document.createElement("iframe");
            iframe.style.display = "none";
            iframe.src = "comappyesvite://somepage";
            document.body.appendChild(iframe);

            // Use a timer to detect if the app opened
            timeout = setTimeout(() => {
                const elapsed = Date.now() - now;
                if (elapsed < 1500) {
                    // App did not open, redirect to App Store
                    window.location.href = "https://apps.apple.com/app/6736650042";
                }
            }, 1200);

            // Cleanup iframe after 2 seconds
            setTimeout(() => {
                document.body.removeChild(iframe);
                clearTimeout(timeout);
            }, 2000);
        }
    </script>
    {{-- <script>
        function openApp() {
            // Try opening the Yesvite app
            window.location.href = "comappyesvite://somepage";

            // If the app is not installed, redirect to the App Store after 2 seconds
            setTimeout(function() {
                window.location.href = "https://apps.apple.com/app/6736650042";
            }, 2000);
        }
    </script> --}}
</head>
<body onload="openApp()">
    <p>If the app does not open, <a href="https://apps.apple.com/app/6736650042">click here to download it</a>.</p>
</body>
</html>
