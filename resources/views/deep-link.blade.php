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
    let fallbackTriggered = false;  // Fallback flag

    // Use `pagehide` to detect if the app opened successfully
    const onPageHide = () => {
        appOpened = true;
    };

    window.addEventListener('pagehide', onPageHide);

    // Open the app
    const now = Date.now();
    window.location.href = appLink;

    // Force fallback if app doesn't open
    const fallbackTimeout = setTimeout(() => {
        if (!appOpened && !fallbackTriggered) {
            fallbackTriggered = true;  // Mark fallback as triggered
            window.location.href = appStoreLink;  // Redirect to App Store
        }

        // Clean up event listener
        window.removeEventListener('pagehide', onPageHide);
    }, 1500);

    // Ensure cleanup in case of browser inconsistencies
    window.addEventListener('blur', () => {
        clearTimeout(fallbackTimeout);
        window.removeEventListener('pagehide', onPageHide);
    });
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
