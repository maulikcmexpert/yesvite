<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Open Yesvite App</title>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const appLink = "{{ $deepLink }}";
            const fallbackUrl = "{{ $fallbackUrl }}";
    
            let appOpened = false;
    
            // Create a hidden iframe for reliable app opening
            const openApp = () => {
                const iframe = document.createElement('iframe');
                iframe.style.display = 'none';
                document.body.appendChild(iframe);
                iframe.src = appLink;
    
                // Listen for visibility change and pagehide
                const onAppOpen = () => {
                    appOpened = true;  // App opened successfully
                };
    
                window.addEventListener('pagehide', onAppOpen);
                document.addEventListener('visibilitychange', () => {
                    if (document.visibilityState === 'hidden') {
                        appOpened = true;  
                    }
                });
    
                // Remove iframe after some time
                setTimeout(() => {
                    document.body.removeChild(iframe);
                }, 1000);
            };
    
            // Attempt to open the app
            openApp();
    
            // Fallback to App Store only if the app did not open
            setTimeout(() => {
                if (!appOpened) {
                    window.location.href = fallbackUrl;  
                }
            }, 2500);  // Extended delay for reliability (2.5 seconds)
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
