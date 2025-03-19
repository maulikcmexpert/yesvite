<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
  
<script>
    document.getElementById('open-app-btn').addEventListener('click', () => {
        const appLink = "comappyesvite://open";
        const fallbackUrl = "https://apps.apple.com/app/6736650042";

        let appOpened = false;
        const startTime = Date.now();

        // Create a hidden iframe to open the app
        const iframe = document.createElement('iframe');
        iframe.style.display = 'none';
        iframe.src = appLink;
        document.body.appendChild(iframe);

        // Detect app opening by checking elapsed time
        setTimeout(() => {
            const elapsed = Date.now() - startTime;

            // If the app didn't open, redirect to the App Store
            if (!appOpened && elapsed < 2500) {  
                window.location.href = fallbackUrl;
            }

            // Clean up
            document.body.removeChild(iframe);
        }, 2500);

        // Listen for page visibility changes
        const onVisibilityChange = () => {
            if (document.hidden) {
                appOpened = true;  // App successfully opened
            }
        };

        document.addEventListener('visibilitychange', onVisibilityChange);
        window.addEventListener('pagehide', onVisibilityChange);
    });
</script>

    {{-- <script>
      
   
        function openApp() {
            const appLink = "comappyesvite://somepage";
            const appStoreLink = "https://apps.apple.com/app/6736650042";
            
            let appOpened = false;

            // Use `pagehide` to detect if the app opened successfully
            const onPageHide = () => {
                appOpened = true;
            };

            window.addEventListener('pagehide', onPageHide);

            // Open the app
            const now = Date.now();
            // alert(now);

            window.location.href = appLink;

      
            setTimeout(() => {
                const elapsed = Date.now() - now;
                if (!appOpened) {
                    window.location.href = appStoreLink;  // App Store redirect
                }

                // Clean up event listener
                window.removeEventListener('pagehide', onPageHide);
            }, 1500);
        }
    </script> --}}
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
{{-- <body onload="openApp()"> --}}
<body>
    <p>If the app does not open, <a href="https://apps.apple.com/app/6736650042">click here to download it</a>.</p>
</body>
</html>
