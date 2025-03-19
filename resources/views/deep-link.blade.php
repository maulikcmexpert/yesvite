<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
    <script>
        function openApp() {
            const appLink = "comappyesvite://somepage";
            const appStoreLink = "https://apps.apple.com/app/6736650042";
            
            let appOpened = false;

            // Use both `visibilitychange` and `blur` to detect app opening
            const onVisibilityChange = () => {
                if (document.hidden) {
                    appOpened = true;  // App opened successfully
                }
            };

            const onBlur = () => {
                appOpened = true;  // App opened successfully
            };

            document.addEventListener('visibilitychange', onVisibilityChange);
            window.addEventListener('blur', onBlur);

            // Open the app using iframe for better compatibility
            const iframe = document.createElement('iframe');
            iframe.style.display = 'none';
            iframe.src = appLink;
            document.body.appendChild(iframe);

            const startTime = Date.now();

            // Fallback to App Store
            setTimeout(() => {
                const elapsed = Date.now() - startTime;

                if (!appOpened && elapsed < 1600) {
                    window.location.href = appStoreLink;  // Redirect to App Store
                }

                // Cleanup
                document.removeEventListener('visibilitychange', onVisibilityChange);
                window.removeEventListener('blur', onBlur);
                document.body.removeChild(iframe);
            }, 1500);
        }
    </script>
</head>
<body onload="openApp()">
    <p>If the app does not open, <a href="https://apps.apple.com/app/6736650042">click here to download it</a>.</p>
</body>
</html>
