{{-- {{dd(session()->get('user_ids'))}} --}}
{{-- {{dd($inviteduser)}} --}}
{{-- <!DOCTYPE html>
<html lang="en">

<head>
    <title>New Event Detail</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <!-- Latest compiled and minified CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <!-- custom-style -->
    <link href="./assets/css/style.css" rel="stylesheet">

    <!-- font-awesome-cdn -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">

</head>

<body>
    <!-- ============ header ========= -->
    --}}
<header class="login-header new_event_detail_header">
    <div class="container-fluid">
        <nav class="navbar navbar-expand-md navbar-dark">
            <div class="event-detail-logo">
                <a class="navbar-brand" href="home.html">
                    <svg width="35" height="36" viewBox="0 0 35 36" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.86776 6.81736H8.48123C8.25283 6.7998 8.34947 6.58018 8.34947 6.58018C8.4988 6.2288 8.613 6.09703 8.42853 5.94769C8.09471 5.68415 7.8136 5.39426 7.5852 5.0868C6.42564 3.52313 6.71553 1.58173 7.79603 0.835042C8.41974 0.404595 9.33334 0.404595 9.95705 0.835042C11.0376 1.58173 11.3187 3.52313 10.1679 5.0868C9.93948 5.39426 9.66716 5.68415 9.32456 5.94769C9.1313 6.09703 9.25428 6.22001 9.40362 6.58018C9.40362 6.58018 9.50025 6.79101 9.27185 6.81736H8.88533H8.86776Z"
                            fill="#ECB015" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M25.9167 9.75143C25.9167 9.04866 26.1627 8.40738 26.5843 7.91544C27.1114 7.28295 27.902 6.88764 28.7893 6.88764C30.3705 6.88764 31.6618 8.1702 31.6618 9.76021H34.6574C34.6574 6.51869 32.0308 3.89209 28.7893 3.89209C26.6985 3.89209 24.8713 4.98138 23.826 6.62411C23.7733 6.71195 23.7205 6.7998 23.6678 6.88764C23.1935 7.73975 22.9211 8.71484 22.9211 9.76021H25.9167V9.75143Z"
                            fill="black" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M30.3534 22.1557C29.7209 27.5494 25.1441 31.7309 19.5835 31.7309C14.0228 31.7309 9.30547 27.4264 8.78718 21.9185L12.0111 21.3475L11.2644 17.1484L0.00256348 19.1425L0.749256 23.3416L4.57056 22.6652C5.44903 30.176 11.8354 36.0002 19.5835 36.0002C27.3315 36.0002 33.999 29.9125 34.6491 22.1557C34.6842 21.734 34.7018 21.3123 34.7018 20.8819C34.7018 20.4515 34.6842 20.0474 34.6491 19.6433H30.3534C30.3973 20.0474 30.4237 20.4602 30.4237 20.8819C30.4237 21.3036 30.3973 21.734 30.3534 22.1557Z"
                            fill="black" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M3.12968 9.63336C3.36687 9.67729 3.32294 9.83541 3.3493 10.2219C3.3493 10.2219 3.36687 10.4503 3.5777 10.3625L3.72704 10.2834L6.16038 15.2643H6.22187L6.27458 15.238L3.83245 10.2307L3.77975 10.2571L3.98179 10.1604C4.17505 10.0462 4.00815 9.8969 4.00815 9.8969C3.72704 9.64215 3.56891 9.58066 3.67433 9.36104C3.86759 8.9833 3.99936 8.60556 4.06964 8.2454C4.45616 6.36549 3.39322 4.74033 2.11067 4.52072C1.7505 4.45923 1.38154 4.50315 1.01259 4.68763C-0.91124 5.64515 -0.0327782 9.06236 3.12968 9.63336Z"
                            fill="#3ABEEA" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M9.31469 9.63336C9.07751 9.67729 9.12143 9.83541 9.09508 10.2219C9.09508 10.2219 9.07751 10.4503 8.86668 10.3625L8.71734 10.2834L6.284 15.2643H6.22251L6.1698 15.238L8.61192 10.2307L8.66463 10.2571L8.46258 10.1604C8.26932 10.0462 8.43623 9.8969 8.43623 9.8969C8.71734 9.64215 8.87546 9.58066 8.77005 9.36104C8.57678 8.9833 8.44502 8.60556 8.37474 8.2454C7.98822 6.36549 9.05115 4.74033 10.3337 4.52072C10.6939 4.45923 11.0628 4.50315 11.4318 4.68763C13.3556 5.64515 12.4772 9.06236 9.31469 9.63336Z"
                            fill="#27B076" />
                        <path opacity="0.21" fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.10298 3.07196C8.21718 2.97533 8.46315 3.08074 8.66519 3.30914C8.85845 3.53754 8.92873 3.80108 8.81453 3.89771C8.70033 3.99434 8.45436 3.88893 8.25231 3.66053C8.05905 3.43213 7.98878 3.16859 8.10298 3.07196Z"
                            fill="white" />
                        <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.51842 4.21271C8.57113 4.16879 8.68533 4.21271 8.77318 4.31812C8.86102 4.42354 8.89616 4.54652 8.84346 4.59045C8.79075 4.63437 8.67655 4.59045 8.5887 4.48503C8.50086 4.37962 8.4745 4.25663 8.51842 4.21271Z"
                            fill="white" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M6.02767 6.30516H5.64115C5.41275 6.28759 5.50938 6.06797 5.50938 6.06797C5.65872 5.71659 5.77292 5.58482 5.58844 5.43548C5.25462 5.17194 4.97352 4.88205 4.74512 4.57459C3.58555 3.01093 3.87544 1.06953 4.95595 0.322835C5.57965 -0.107612 6.49326 -0.107612 7.11696 0.322835C8.19747 1.06953 8.47858 3.01093 7.32779 4.57459C7.09939 4.88205 6.82707 5.17194 6.48447 5.43548C6.29121 5.58482 6.41419 5.7078 6.56353 6.06797C6.56353 6.06797 6.66016 6.2788 6.43176 6.30516H6.04524H6.02767Z"
                            fill="#ECB015" />
                        <path d="M6.32795 10.9707H6.22253V15.2664H6.32795V10.9707Z" fill="#ECB015" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M4.28933 9.54695C4.88668 10.0477 5.47525 10.4869 5.92327 10.8119C6.17802 11.0052 6.02868 11.1721 5.84421 11.6465C5.84421 11.6465 5.73001 11.9276 6.02868 11.9539H6.63482C6.94229 11.9364 6.8193 11.6465 6.8193 11.6465C6.62604 11.1721 6.4767 11.0052 6.74024 10.8119C7.18825 10.4869 7.77682 10.0477 8.37418 9.54695C9.27021 8.78269 10.2541 7.86031 10.7724 6.79737C12.1779 3.94237 9.48982 -0.0107092 6.33615 2.46655C3.18247 -0.0107092 0.485591 3.94237 1.89113 6.79737C2.40942 7.85152 3.40208 8.78269 4.29811 9.54695H4.28933Z"
                            fill="#EA555C" />
                    </svg>
                </a>
                <span>Create New Event</span>
            </div>
            <a class="navbar-brand mobile-header-logo" href="home.html">
                <svg width="129" height="36" viewBox="0 0 129 36" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.86965 6.81981H8.48313C8.25473 6.80224 8.35136 6.58262 8.35136 6.58262C8.5007 6.23124 8.6149 6.09947 8.43042 5.95013C8.0966 5.68659 7.8155 5.3967 7.5871 5.08924C6.42753 3.52558 6.71742 1.58418 7.79793 0.837483C8.42163 0.407037 9.33524 0.407037 9.95894 0.837483C11.0395 1.58418 11.3206 3.52558 10.1698 5.08924C9.94137 5.3967 9.66905 5.68659 9.32645 5.95013C9.13319 6.09947 9.25617 6.22245 9.40551 6.58262C9.40551 6.58262 9.50214 6.79345 9.27374 6.81981H8.88722H8.86965Z"
                        fill="#ECB015" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M25.9168 9.75338C25.9168 9.05061 26.1627 8.40934 26.5844 7.9174C27.1115 7.2849 27.9021 6.8896 28.7893 6.8896C30.3706 6.8896 31.6619 8.17215 31.6619 9.76217H34.6575C34.6575 6.52064 32.0309 3.89404 28.7893 3.89404C26.6986 3.89404 24.8714 4.98334 23.826 6.62606C23.7733 6.7139 23.7206 6.80175 23.6679 6.8896C23.1935 7.7417 22.9212 8.7168 22.9212 9.76217H25.9168V9.75338Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M30.3552 22.1547C29.7227 27.5484 25.146 31.7299 19.5853 31.7299C14.0246 31.7299 9.3073 27.4255 8.78901 21.9175L12.013 21.3465L11.2663 17.1475L0.00439453 19.1416L0.751087 23.3406L4.57239 22.6642C5.45086 30.175 11.8373 35.9992 19.5853 35.9992C27.3333 35.9992 34.0009 29.9115 34.6509 22.1547C34.6861 21.733 34.7036 21.3114 34.7036 20.8809C34.7036 20.4505 34.6861 20.0464 34.6509 19.6423H30.3552C30.3992 20.0464 30.4255 20.4593 30.4255 20.8809C30.4255 21.3026 30.3992 21.733 30.3552 22.1547Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M115.199 20.3698C115.199 24.4371 117.958 26.8968 121.955 26.8968C123.966 26.8968 126.048 26.0271 127.34 24.1384L124.335 23.0491C123.861 23.5937 123.018 23.9715 121.849 23.9715C120.461 23.9715 119.117 23.1282 118.994 21.3888H128.104C128.35 17.0404 125.846 13.9395 121.797 13.9395C118.195 13.9395 115.19 16.4431 115.19 20.3698H115.199ZM121.902 16.7417C123.369 16.7417 124.555 17.5587 124.608 19.1487H118.994C119.214 17.4621 120.488 16.7417 121.902 16.7417Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M105.518 17.1664H107.556V23.1224C107.556 26.0301 108.602 26.6011 111.624 26.6011H114.004V23.5265H112.915C111.703 23.5265 111.325 23.3069 111.325 22.1385V17.1752H114.004V14.2939H111.325V11.2192H107.548V14.2939H105.51V17.1752L105.518 17.1664Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M102.208 12.2458C103.447 12.2458 104.44 11.2268 104.44 10.0145C104.44 8.80222 103.447 7.7832 102.208 7.7832C100.97 7.7832 99.9771 8.77586 99.9771 10.0145C99.9771 11.2531 100.97 12.2458 102.208 12.2458Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M97.9688 17.1636H100.306V26.5983H104.1V14.2822H97.9688V17.1636Z" fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M88.2859 26.5983H92.5288L96.9475 14.2822H93.0559L90.4205 22.6715L87.7939 14.2822H83.8936L88.2859 26.5983Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M77.1936 26.8968C80.4966 26.8968 83.2286 25.4824 83.2286 22.5747C83.2286 20.5103 81.8407 19.6494 78.8627 19.096L76.5787 18.6744C75.4894 18.4723 75.0677 18.1824 75.0677 17.6817C75.0677 17.0404 75.7617 16.689 77.1058 16.689C78.2214 16.689 79.1701 16.935 79.4161 17.831L82.5961 16.6627C81.6738 14.7301 79.4952 13.9307 77.1321 13.9307C74.0751 13.9307 71.4748 15.2484 71.4748 17.831C71.4748 19.8954 73.1175 21.1077 75.4982 21.503L77.7031 21.872C79.1174 22.1179 79.6182 22.4693 79.6182 22.9876C79.6182 23.7606 78.5025 24.1296 77.2814 24.1296C75.8671 24.1296 74.7515 23.6289 74.3737 22.5923L71.1674 23.7607C72.1073 25.825 74.321 26.888 77.2024 26.888L77.1936 26.8968Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M57.18 20.3698C57.18 24.4371 59.9384 26.8968 63.9354 26.8968C65.947 26.8968 68.029 26.0271 69.3203 24.1384L66.316 23.0491C65.8416 23.5937 64.9983 23.9715 63.8299 23.9715C62.442 23.9715 61.0979 23.1282 60.9749 21.3888H70.0846C70.3306 17.0404 67.8269 13.9395 63.7772 13.9395C60.1755 13.9395 57.1712 16.4431 57.1712 20.3698H57.18ZM63.8826 16.7417C65.3497 16.7417 66.5356 17.5587 66.5883 19.1487H60.9749C61.1946 17.4621 62.4683 16.7417 63.8826 16.7417Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M43.9792 14.2822L48.6965 26.5192L48.5735 26.8442C48.2046 27.7842 47.9323 27.8632 46.3422 27.8632H45.5956V30.5953H46.8078C50.0142 30.5953 50.9278 29.7256 52.1225 26.5983L56.8399 14.2822H52.7902L50.4359 21.9776L48.0289 14.2822H43.9792Z"
                        fill="black" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M3.12968 9.63483C3.36687 9.67875 3.32295 9.83688 3.3493 10.2234C3.3493 10.2234 3.36687 10.4518 3.5777 10.364L3.72704 10.2849L6.16038 15.2658H6.22187L6.27458 15.2394L3.83245 10.2322L3.77975 10.2585L3.98179 10.1619C4.17505 10.0477 4.00815 9.89837 4.00815 9.89837C3.72704 9.64361 3.56891 9.58212 3.67433 9.36251C3.86759 8.98477 3.99936 8.60703 4.06964 8.24686C4.45616 6.36695 3.39322 4.7418 2.11067 4.52218C1.7505 4.46069 1.38154 4.50461 1.01259 4.68909C-0.91124 5.64661 -0.0327782 9.06383 3.12968 9.63483Z"
                        fill="#3ABEEA" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M9.31463 9.63483C9.07745 9.67875 9.12137 9.83688 9.09502 10.2234C9.09502 10.2234 9.07745 10.4518 8.86662 10.364L8.71728 10.2849L6.28394 15.2658H6.22245L6.16974 15.2394L8.61186 10.2322L8.66457 10.2585L8.46252 10.1619C8.26926 10.0477 8.43617 9.89837 8.43617 9.89837C8.71728 9.64361 8.8754 9.58212 8.76998 9.36251C8.57672 8.98477 8.44495 8.60703 8.37468 8.24686C7.98815 6.36695 9.05109 4.7418 10.3336 4.52218C10.6938 4.46069 11.0628 4.50461 11.4317 4.68909C13.3556 5.64661 12.4771 9.06383 9.31463 9.63483Z"
                        fill="#27B076" />
                    <path opacity="0.21" fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.10115 3.07294C8.21535 2.9763 8.46131 3.08172 8.66336 3.31012C8.85662 3.53852 8.9269 3.80206 8.8127 3.89869C8.6985 3.99532 8.45253 3.8899 8.25048 3.6615C8.05722 3.4331 7.98695 3.16957 8.10115 3.07294Z"
                        fill="white" />
                    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.51964 4.21417C8.57235 4.17025 8.68655 4.21417 8.7744 4.31959C8.86224 4.425 8.89738 4.54799 8.84468 4.59191C8.79197 4.63584 8.67777 4.59191 8.58992 4.4865C8.50208 4.38108 8.47572 4.2581 8.51964 4.21417Z"
                        fill="white" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.02645 6.30565H5.63993C5.41153 6.28808 5.50816 6.06846 5.50816 6.06846C5.6575 5.71708 5.7717 5.58531 5.58722 5.43597C5.2534 5.17243 4.9723 4.88254 4.7439 4.57508C3.58433 3.01142 3.87422 1.07002 4.95473 0.323323C5.57843 -0.107123 6.49203 -0.107123 7.11574 0.323323C8.19625 1.07002 8.47736 3.01142 7.32657 4.57508C7.09817 4.88254 6.82585 5.17243 6.48325 5.43597C6.28999 5.58531 6.41297 5.70829 6.56231 6.06846C6.56231 6.06846 6.65894 6.27929 6.43054 6.30565H6.04402H6.02645Z"
                        fill="#ECB015" />
                    <path d="M6.32789 10.9731H6.22247V15.2688H6.32789V10.9731Z" fill="#ECB015" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.28744 9.54549C4.88479 10.0462 5.47336 10.4854 5.92138 10.8105C6.17613 11.0037 6.02679 11.1706 5.84232 11.645C5.84232 11.645 5.72812 11.9261 6.02679 11.9525H6.63293C6.94039 11.9349 6.81741 11.645 6.81741 11.645C6.62415 11.1706 6.47481 11.0037 6.73835 10.8105C7.18636 10.4854 7.77493 10.0462 8.37228 9.54549C9.26832 8.78123 10.2522 7.85884 10.7705 6.7959C12.176 3.9409 9.48793 -0.012174 6.33425 2.46509C3.18058 -0.012174 0.483699 3.9409 1.88924 6.7959C2.40753 7.85006 3.40019 8.78123 4.29622 9.54549H4.28744Z"
                        fill="#EA555C" />
                </svg>
            </a>

            <h5>Detail Events</h5>

            <div class="dropdown">
                <div class="new_event_detail_header-right dropdown-toggle" id="dropdownMenuButton1"
                    data-bs-toggle="dropdown">
                    <div class="new_event_detail_header-right-wrp">
                        <h6 class="current_step">1 of 4</h6>
                        <h4 class="event_create_percent">25%</h4>
                        <i class="fa-solid fa-angle-down"></i>
                    </div>
                    <span id="close_createEvent"><i class="fa-solid fa-xmark"></i></span>
                </div>
                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                    <li>
                        <a class="dropdown-item" href="#">
                            <span>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.57751 3.40771C7.93585 5.70771 9.80251 7.46605 12.1192 7.69938"
                                        stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M8.71939 2.19924L1.87772 9.4409C1.61939 9.7159 1.36939 10.2576 1.31939 10.6326L1.01106 13.3326C0.902725 14.3076 1.60272 14.9742 2.56939 14.8076L5.25272 14.3492C5.62772 14.2826 6.15272 14.0076 6.41106 13.7242L13.2527 6.48257C14.4361 5.23257 14.9694 3.80757 13.1277 2.0659C11.2944 0.340903 9.90272 0.949237 8.71939 2.19924Z"
                                        stroke="#F73C71" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            Event Details
                        </a>
                    </li>
                    <li>
                        <div class="dropdown-item active">
                            <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M2.91667 17.0835C3.60834 17.7752 4.725 17.7752 5.41667 17.0835L16.25 6.2502C16.9417 5.55853 16.9417 4.44186 16.25 3.7502C15.5583 3.05853 14.4417 3.05853 13.75 3.7502L2.91667 14.5835C2.225 15.2752 2.225 16.3919 2.91667 17.0835Z"
                                        stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M15.0083 7.4917L12.5083 4.9917" stroke="#0F172A" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M7.08334 2.03317L8.33334 1.6665L7.96668 2.9165L8.33334 4.1665L7.08334 3.79984L5.83334 4.1665L6.20001 2.9165L5.83334 1.6665L7.08334 2.03317Z"
                                        stroke="#0F172A" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M3.75 7.03317L5 6.6665L4.63333 7.9165L5 9.1665L3.75 8.79984L2.5 9.1665L2.86667 7.9165L2.5 6.6665L3.75 7.03317Z"
                                        stroke="#0F172A" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M16.25 11.2002L17.5 10.8335L17.1333 12.0835L17.5 13.3335L16.25 12.9668L15 13.3335L15.3667 12.0835L15 10.8335L16.25 11.2002Z"
                                        stroke="#0F172A" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            Design <i class="fa-solid fa-angle-down"></i>
                        </div>
                        <ul class="dropdown-sub-menu">
                            <li>
                                <a href="" class="dropdown-item">Pick Card</a>
                            </li>
                            <li>
                                <a href="" class="dropdown-item">Edit Design</a>
                            </li>
                        </ul>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <span>
                                <svg width="18" height="20" viewBox="0 0 18 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M7.99998 9.99984C10.3012 9.99984 12.1666 8.13436 12.1666 5.83317C12.1666 3.53198 10.3012 1.6665 7.99998 1.6665C5.69879 1.6665 3.83331 3.53198 3.83331 5.83317C3.83331 8.13436 5.69879 9.99984 7.99998 9.99984Z"
                                        stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M0.841675 18.3333C0.841675 15.1083 4.05 12.5 8 12.5C8.8 12.5 9.57501 12.6083 10.3 12.8083"
                                        stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path
                                        d="M16.3334 14.9998C16.3334 15.2665 16.3 15.5248 16.2334 15.7748C16.1584 16.1082 16.025 16.4332 15.85 16.7165C15.275 17.6832 14.2167 18.3332 13 18.3332C12.1417 18.3332 11.3667 18.0081 10.7834 17.4748C10.5334 17.2581 10.3167 16.9998 10.15 16.7165C9.84168 16.2165 9.66669 15.6248 9.66669 14.9998C9.66669 14.0998 10.025 13.2749 10.6084 12.6749C11.2167 12.0499 12.0667 11.6665 13 11.6665C13.9834 11.6665 14.875 12.0915 15.475 12.7749C16.0084 13.3665 16.3334 14.1498 16.3334 14.9998Z"
                                        stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M14.2417 14.9834H11.7584" stroke="#0F172A" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13 13.7666V16.2583" stroke="#0F172A" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            Guests
                        </a>
                    </li>
                    <li>
                        <a class="dropdown-item" href="#">
                            <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10 12.5C11.3807 12.5 12.5 11.3807 12.5 10C12.5 8.61929 11.3807 7.5 10 7.5C8.61929 7.5 7.5 8.61929 7.5 10C7.5 11.3807 8.61929 12.5 10 12.5Z"
                                        stroke="black" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M1.66666 10.7334V9.2667C1.66666 8.40003 2.37499 7.68336 3.24999 7.68336C4.75832 7.68336 5.37499 6.6167 4.61666 5.30836C4.18332 4.55836 4.44166 3.58336 5.19999 3.15003L6.64166 2.32503C7.29999 1.93336 8.14999 2.1667 8.54166 2.82503L8.63332 2.98336C9.38332 4.2917 10.6167 4.2917 11.375 2.98336L11.4667 2.82503C11.8583 2.1667 12.7083 1.93336 13.3667 2.32503L14.8083 3.15003C15.5667 3.58336 15.825 4.55836 15.3917 5.30836C14.6333 6.6167 15.25 7.68336 16.7583 7.68336C17.625 7.68336 18.3417 8.3917 18.3417 9.2667V10.7334C18.3417 11.6 17.6333 12.3167 16.7583 12.3167C15.25 12.3167 14.6333 13.3834 15.3917 14.6917C15.825 15.45 15.5667 16.4167 14.8083 16.85L13.3667 17.675C12.7083 18.0667 11.8583 17.8334 11.4667 17.175L11.375 17.0167C10.625 15.7084 9.39166 15.7084 8.63332 17.0167L8.54166 17.175C8.14999 17.8334 7.29999 18.0667 6.64166 17.675L5.19999 16.85C4.44166 16.4167 4.18332 15.4417 4.61666 14.6917C5.37499 13.3834 4.75832 12.3167 3.24999 12.3167C2.37499 12.3167 1.66666 11.6 1.66666 10.7334Z"
                                        stroke="black" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                            Event Settings
                        </a>
                    </li>
                </ul>
            </div>

        </nav>
    </div>
</header>

<main class="main-content-wrp">
    <div class="main-content-sidebar">
        <div class="new-event-sidebar-wrp">
            <div class="new-event-sidebar-menu">
                <ul>
                    <!-- ---for active menu add active class--- -->
                    <li class="li_event_detail">
                        <div class="menu-circle-wrp side-bar-list active">
                            <span></span>
                            <h3>Event Details</h3>
                        </div>
                    </li>
                    <li class="li_design">
                        <div class="menu-circle-wrp side-bar-list">
                            <span></span>
                            <h3>Design</h3>
                        </div>
                        <ul class="new-event-sidebar-sub-menu">
                            <li>
                                <div class="side-bar-sub-list">
                                    <h3>Pick Design</h3>
                                </div>
                            </li>
                            <li>
                                <div class="side-bar-sub-list">
                                    <h3>Edit Design</h3>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <li class="li_guest">
                        <div class="menu-circle-wrp side-bar-list">
                            <span></span>
                            <h3>Guests</h3>
                        </div>
                    </li>
                    <li class="li_setting">
                        <div class="menu-circle-wrp side-bar-list">
                            <span></span>
                            <h3>Settings</h3>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    @include('front.event.step1')

    @include('front.event.step2')

    @include('front.event.step3')

    @include('front.event.step4')

    @include('front.event.final_checkout')

    </div>

</main>



<div id="sidebar" class="sidebar choose-design-sidebar" style="right: 0px; width: 100%;display:none;">
    <div class="setting-category-wrp choose-design-form activity-schedule-inner ">
        <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
            <h5>Category</h5>
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
        <h3>Create Custom Own Design</h3>

        <div class="accordion" id="accordionExample">
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseOne" aria-expanded="false">
                        <div>
                            Birthdays
                        </div>
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
                <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                    style="">
                    <div class="accordion-body">
                        <ul>
                            <li>All Birthdays</li>
                            <li>Baby Birthday</li>
                            <li>Kids Birthdays</li>
                            <li>Mans Birthday</li>
                            <li>Womans Birthday</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseTwo" aria-expanded="false">
                        <div>
                            Birthdays
                        </div>
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
                <div id="collapseTwo" class="accordion-collapse collapse" data-bs-parent="#accordionExample"
                    style="">
                    <div class="accordion-body">
                        <ul>
                            <li>All Birthdays</li>
                            <li>Baby Birthday</li>
                            <li>Kids Birthdays</li>
                            <li>Mans Birthday</li>
                            <li>Womans Birthday</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseThree" aria-expanded="false">
                        <div>
                            Birthdays
                        </div>
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
                <div id="collapseThree" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <ul>
                            <li>All Birthdays</li>
                            <li>Baby Birthday</li>
                            <li>Kids Birthdays</li>
                            <li>Mans Birthday</li>
                            <li>Womans Birthday</li>
                        </ul>
                    </div>
                </div>
            </div>
            <div class="accordion-item">
                <div class="accordion-header">
                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                        data-bs-target="#collapseFour" aria-expanded="false">
                        <div>
                            Birthdays
                        </div>
                        <i class="fa-solid fa-angle-down"></i>
                    </button>
                </div>
                <div id="collapseFour" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                    <div class="accordion-body">
                        <ul>
                            <li>All Birthdays</li>
                            <li>Baby Birthday</li>
                            <li>Kids Birthdays</li>
                            <li>Mans Birthday</li>
                            <li>Womans Birthday</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div style="display:none;" class="modal fade choose-design-modal" id="exampleModal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Choose this design?</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            @if(!session('design_closed'))
            <div class="alert-box d-flex align-items-center" id="design_tip_bar">
                <span class="me-3">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.99984 0.666992C4.40817 0.666992 0.666504 4.40866 0.666504 9.00033C0.666504 13.592 4.40817 17.3337 8.99984 17.3337C13.5915 17.3337 17.3332 13.592 17.3332 9.00033C17.3332 4.40866 13.5915 0.666992 8.99984 0.666992ZM8.37484 5.66699C8.37484 5.32533 8.65817 5.04199 8.99984 5.04199C9.3415 5.04199 9.62484 5.32533 9.62484 5.66699V9.83366C9.62484 10.1753 9.3415 10.4587 8.99984 10.4587C8.65817 10.4587 8.37484 10.1753 8.37484 9.83366V5.66699ZM9.7665 12.6503C9.72484 12.7587 9.6665 12.842 9.5915 12.9253C9.50817 13.0003 9.4165 13.0587 9.3165 13.1003C9.2165 13.142 9.10817 13.167 8.99984 13.167C8.8915 13.167 8.78317 13.142 8.68317 13.1003C8.58317 13.0587 8.4915 13.0003 8.40817 12.9253C8.33317 12.842 8.27484 12.7587 8.23317 12.6503C8.1915 12.5503 8.1665 12.442 8.1665 12.3337C8.1665 12.2253 8.1915 12.117 8.23317 12.017C8.27484 11.917 8.33317 11.8253 8.40817 11.742C8.4915 11.667 8.58317 11.6087 8.68317 11.567C8.88317 11.4837 9.1165 11.4837 9.3165 11.567C9.4165 11.6087 9.50817 11.667 9.5915 11.742C9.6665 11.8253 9.72484 11.917 9.7665 12.017C9.80817 12.117 9.83317 12.2253 9.83317 12.3337C9.83317 12.442 9.80817 12.5503 9.7665 12.6503Z"
                            fill="#1C8B5C" />
                    </svg>
                </span>
                <p>Tip: Click text box then move around,can also pinch to resize the font size.</p>
                <span class="ms-3" id="design_tip_bar_close">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.33398 1.33301L10.6667 10.6657" stroke="#1C8B5C" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1.33331 10.6657L10.666 1.33301" stroke="#1C8B5C" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </div>
            @endif
            <div class="modal-body">
                <div class="modal-choose-design-wrp" id="download_image">
                    <div class="modal-design-card">
                        {{-- <div class="birthday-card-main" id="photo">
                            <svg class="blue-bg" preserveAspectRatio="none" viewBox="0 0 460 409" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <mask id="mask0_10_1757" style="mask-type:luminance" maskUnits="userSpaceOnUse"
                                    x="-1" y="0" width="461" height="409">
                                    <path d="M-0.0149841 409V0.579758L229.99 55.0756L460 0.579758V409H-0.0149841Z"
                                        fill="white"></path>
                                </mask>
                                <g mask="url(#mask0_10_1757)">
                                    <path d="M460 409H-0.0149841V0.987267H460V409Z" fill="#3B95B3"></path>
                                </g>
                            </svg>
                            <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_top_head_img.svg') }}"
                        alt="" class="post_temp_1_top_head_img">
                        <div class="main-center-img">
                            <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_ballon_left.svg') }}"
                                alt="" class="post_temp_1_ballon_left">
                            <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_ballon_right.svg') }}"
                                alt="" class="post_temp_1_ballon_right">
                            <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_center_img.png') }}"
                                alt="">
                        </div>
                        <div class="main-center-name-wrp">
                            <h3 class="titlename"></h3>
                            <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_name_img.svg') }}"
                                alt="" class="post_temp_1_name_img">
                        </div>
                        <div class="birthday-card-main-content">
                            <h2 class="event_name"></h2>
                            <div class="birthday-card-content-inner">
                                <div class="birthday-card-content-inner-date">
                                    <h4 class="event_date"></h4>
                                </div>
                                <div class="birthday-card-content-inner-info">
                                    <p class="event_address"></p>
                                    <h3 class="event_time"></h3>
                                </div>
                            </div>
                        </div>
                        <p class="footer-text">r.s.v.p. to: +123-456-7890</p>
                    </div> --}}
                </div>
            </div>
            <div class="modal-btn">
                <button class="edit-btn">Edit Design</button>
                <button class="cmn-btn store_desgin_temp">Next: Guests</button>
            </div>
        </div>
    </div>
</div>
</div>


<div id="sidebar_allow_limit" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar">

        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <h5>Add +1 Limit</h5>
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="limits-count">
            <h5>+1 Limit</h5>
            <div class="qty-container">
                <button class="qty-btn-minus allow_limit_btn" type="button"><i class="fa fa-minus"></i></button>
                <input type="number" name="qty" id="allow_limit_count" value="0" class="input-qty" />
                <button class="qty-btn-plus allow_limit_btn" type="button"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn save_allow_limit">Save</a>
    </div>
</div>
<div id="sidebar_thankyou_card" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <h5>Thank you card</h5>
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        @if(!session('thankyou_card_closed'))
        <div class="alert-box d-flex align-items-center" id="thankyou_card_popup">
            <span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.99984 0.666992C4.40817 0.666992 0.666504 4.40866 0.666504 9.00033C0.666504 13.592 4.40817 17.3337 8.99984 17.3337C13.5915 17.3337 17.3332 13.592 17.3332 9.00033C17.3332 4.40866 13.5915 0.666992 8.99984 0.666992ZM8.37484 5.66699C8.37484 5.32533 8.65817 5.04199 8.99984 5.04199C9.3415 5.04199 9.62484 5.32533 9.62484 5.66699V9.83366C9.62484 10.1753 9.3415 10.4587 8.99984 10.4587C8.65817 10.4587 8.37484 10.1753 8.37484 9.83366V5.66699ZM9.7665 12.6503C9.72484 12.7587 9.6665 12.842 9.5915 12.9253C9.50817 13.0003 9.4165 13.0587 9.3165 13.1003C9.2165 13.142 9.10817 13.167 8.99984 13.167C8.8915 13.167 8.78317 13.142 8.68317 13.1003C8.58317 13.0587 8.4915 13.0003 8.40817 12.9253C8.33317 12.842 8.27484 12.7587 8.23317 12.6503C8.1915 12.5503 8.1665 12.442 8.1665 12.3337C8.1665 12.2253 8.1915 12.117 8.23317 12.017C8.27484 11.917 8.33317 11.8253 8.40817 11.742C8.4915 11.667 8.58317 11.6087 8.68317 11.567C8.88317 11.4837 9.1165 11.4837 9.3165 11.567C9.4165 11.6087 9.50817 11.667 9.5915 11.742C9.6665 11.8253 9.72484 11.917 9.7665 12.017C9.80817 12.117 9.83317 12.2253 9.83317 12.3337C9.83317 12.442 9.80817 12.5503 9.7665 12.6503Z"
                        fill="#1C8B5C" />
                </svg>
            </span>
            <p>You can choose an existing template or create a new template.</p>
            <span class="ms-3" id="close_thankyou_card_popup">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.33398 1.33301L10.6667 10.6657" stroke="#1C8B5C" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M1.33331 10.6657L10.666 1.33301" stroke="#1C8B5C" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
        @endif
        <div class="list_thankyou_card">

        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn thankyou_card thankyou_card_add_form">Add Thankyou card</a>
    </div>
</div>
</div>
<div id="sidebar_add_thankyou_card" class="sidebar setting-side-wrp">
    <div class="d-flex align-items-center justify-content-between toggle-wrp">
        <div class="d-flex align-items-center">
            <a href="#" class="me-3" onclick="toggleSidebar('sidebar_thankyou_card')">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </a>
            <h5>Create new thank you card </h5>
        </div>
        <button class="close-btn" onclick="toggleSidebar('')">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
                <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                    stroke-linejoin="round" />
            </svg>
        </button>
    </div>
    <div class="guest-group-name login-form-wrap no-border-wrp">
        <form action="">
            <div class="input-form">
                <input class="form-control" oninput="clearError(this)" type="text" id="thankyou_templatename"
                    name="text1">
                {{-- <label for="email" class="floating-label">Template Name <span>*</span></label> --}}
                <label for="code" class="form-label input-field floating-label about-label">Template Name </label>

                <label for="thankyou_templatename" id="template_name_error" class="common_error"></label>
                <input type="hidden" id="edit_template_id">
            </div>
            <div class="input-form">
                <input class="form-control" oninput="clearError(this)" type="number" id="thankyou_when_to_send"
                    name="text2">
                {{-- <label for="email" class="floating-label">When to send (Hours after event) <span>*</span></label> --}}
                <label for="code" class="form-label input-field floating-label about-label">When to send (Hours
                    after event) </label>

                <label for="thankyou_when_to_send" id="when_to_send_error" class="common_error"></label>

            </div>
            <div class="input-form message-textarea mb-0">
                <textarea name="" oninput="clearError(this)" class="inputText" id="message_for_thankyou" rows="5"
                    cols="50"></textarea>
                <label for="code" class="form-label input-field floating-label about-label">message</label>
                <label for="message_for_thankyou" id="thankyou_message_error" class="common_error"></label>

            </div>
        </form>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn add_thankyou_card">Add Thankyou
            card</a>
    </div>
</div>

<div id="sidebar_add_co_host" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <div class="d-flex align-items-center">
                <a href="#" class="me-3 add_co_host_off" >
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h5>Add a Co-Host</h5>
            </div>
            <button class="close-btn add_co_host_off" >
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>

        @if(!session('co_host_closed'))
        <div class="alert-box d-flex align-items-center" id="co_host_tip">
            <span class="me-3">
                <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M8.99984 0.666992C4.40817 0.666992 0.666504 4.40866 0.666504 9.00033C0.666504 13.592 4.40817 17.3337 8.99984 17.3337C13.5915 17.3337 17.3332 13.592 17.3332 9.00033C17.3332 4.40866 13.5915 0.666992 8.99984 0.666992ZM8.37484 5.66699C8.37484 5.32533 8.65817 5.04199 8.99984 5.04199C9.3415 5.04199 9.62484 5.32533 9.62484 5.66699V9.83366C9.62484 10.1753 9.3415 10.4587 8.99984 10.4587C8.65817 10.4587 8.37484 10.1753 8.37484 9.83366V5.66699ZM9.7665 12.6503C9.72484 12.7587 9.6665 12.842 9.5915 12.9253C9.50817 13.0003 9.4165 13.0587 9.3165 13.1003C9.2165 13.142 9.10817 13.167 8.99984 13.167C8.8915 13.167 8.78317 13.142 8.68317 13.1003C8.58317 13.0587 8.4915 13.0003 8.40817 12.9253C8.33317 12.842 8.27484 12.7587 8.23317 12.6503C8.1915 12.5503 8.1665 12.442 8.1665 12.3337C8.1665 12.2253 8.1915 12.117 8.23317 12.017C8.27484 11.917 8.33317 11.8253 8.40817 11.742C8.4915 11.667 8.58317 11.6087 8.68317 11.567C8.88317 11.4837 9.1165 11.4837 9.3165 11.567C9.4165 11.6087 9.50817 11.667 9.5915 11.742C9.6665 11.8253 9.72484 11.917 9.7665 12.017C9.80817 12.117 9.83317 12.2253 9.83317 12.3337C9.83317 12.442 9.80817 12.5503 9.7665 12.6503Z"
                        fill="#1C8B5C" />
                </svg>
            </span>
            <p>Co-Host will get an invite and if they agree they will get access to the event as a host.</p>
            <span class="ms-3" id="co_host_tip_close">
                <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.33398 1.33301L10.6667 10.6657" stroke="#1C8B5C" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M1.33331 10.6657L10.666 1.33301" stroke="#1C8B5C" stroke-width="1.5"
                        stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </span>
        </div>
        @endif
        <ul class="nav nav-tabs" id="myTab" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active" id="contact-tab" data-bs-toggle="tab" data-bs-target="#contact"
                    type="button" role="tab" aria-controls="#contact" aria-selected="true">Yestive
                    Contacts</button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link" id="phone-tab" data-bs-toggle="tab" data-bs-target="#phone" type="button"
                    role="tab" aria-controls="phone" aria-selected="false">Phone Contacts</button>
            </li>
        </ul>
        <div class="tab-content" id="myTabContent">
            <div class="tab-pane fade show active" id="contact" role="tabpanel" aria-labelledby="contact-tab">


                <div class="guest-contacts-wrp" style="display: none">
                    <div class="guest-contact">
                        <div class="guest-img">
                            <img src="./assets/image/user-img.svg" alt="guest-img">
                            <a href="#" class="close">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1.8999" y="1" width="16" height="16" rx="8"
                                        fill="#F73C71" />
                                    <rect x="1.8999" y="1" width="16" height="16" rx="8"
                                        stroke="white" stroke-width="2" />
                                    <path d="M7.56689 6.66699L12.2332 11.3333" stroke="white" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.56656 11.3333L12.2329 6.66699" stroke="white" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                        <h6 class="guest-name">Silvia Alegra</h6>
                    </div>
                </div>

                <div class="position-relative">
                    <input type="search" placeholder="Search name" class="form-control co_host_search">
                    <span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>



                <div class="user-contacts list_all_invited_user">






                </div>
            </div>
            <div class="tab-pane fade" id="phone" role="tabpanel" aria-labelledby="phone-tab">


                <div class="guest-contacts-wrp">
                    <div class="guest-contact">
                        <div class="guest-img">
                            <img src="./assets/image/user-img.svg" alt="guest-img">
                            <a href="#" class="close">
                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <rect x="1.8999" y="1" width="16" height="16" rx="8"
                                        fill="#F73C71" />
                                    <rect x="1.8999" y="1" width="16" height="16" rx="8"
                                        stroke="white" stroke-width="2" />
                                    <path d="M7.56689 6.66699L12.2332 11.3333" stroke="white" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.56656 11.3333L12.2329 6.66699" stroke="white" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                </svg>
                            </a>
                        </div>
                        <h6 class="guest-name">Silvia Alegra</h6>
                    </div>
                </div>

                <div class="position-relative">
                    <input type="search" placeholder="Search name" class="form-control">
                    <span>
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>

                <div class="user-contacts">
                    <div class="users-data">
                        <div class="d-flex align-items-start">
                            <div class="contact-img">
                                <img src="./assets/image/user-img.svg" alt="contact-img">
                            </div>
                            <div class="text-start">
                                <h5>Silvia Alegra</h5>
                                <div>
                                    <a href="mailto:silvia@gmail.com"><svg class="me-1" width="14"
                                            height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        silvia@gmail.com</a>
                                </div>
                                <div>
                                    <a href="tel">
                                        <svg width="14" class="me-1" height="14" viewBox="0 0 14 14"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                                fill="black" />
                                        </svg>
                                        1-800-5587</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-3">
                            <div class="right-note mb-2">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s" checked="">
                            </div>
                            <div class="right-note ms-auto">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                            </div>
                        </div>
                    </div>
                    <div class="users-data">
                        <div class="d-flex align-items-start">
                            <div class="contact-img">
                                <img src="./assets/image/user-img.svg" alt="contact-img">
                            </div>
                            <div class="text-start">
                                <h5>Silvia Alegra</h5>
                                <div>
                                    <a href="mailto:silvia@gmail.com"><svg class="me-1" width="14"
                                            height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        silvia@gmail.com</a>
                                </div>
                                <div>
                                    <a href="tel">
                                        <svg width="14" class="me-1" height="14" viewBox="0 0 14 14"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                                fill="black" />
                                        </svg>
                                        1-800-5587</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-3">
                            <div class="right-note mb-2">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                            </div>
                            <div class="right-note ms-auto">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s" checked="">
                            </div>
                        </div>
                    </div>
                    <div class="users-data">
                        <div class="d-flex align-items-start">
                            <div class="contact-img">
                                <img src="./assets/image/user-img.svg" alt="contact-img">
                            </div>
                            <div class="text-start">
                                <h5>Silvia Alegra</h5>
                                <div>
                                    <a href="mailto:silvia@gmail.com"><svg class="me-1" width="14"
                                            height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        silvia@gmail.com</a>
                                </div>
                                <div>
                                    <a href="tel">
                                        <svg width="14" class="me-1" height="14" viewBox="0 0 14 14"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                                fill="black" />
                                        </svg>
                                        1-800-5587</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-3">
                            <div class="right-note mb-2">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                            </div>
                            <div class="right-note ms-auto">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                            </div>
                        </div>
                    </div>
                    <div class="users-data">
                        <div class="d-flex align-items-start">
                            <div class="contact-img">
                                <img src="./assets/image/user-img.svg" alt="contact-img">
                            </div>
                            <div class="text-start">
                                <h5>Silvia Alegra</h5>
                                <div>
                                    <a href="mailto:silvia@gmail.com"><svg class="me-1" width="14"
                                            height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                                stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg>
                                        silvia@gmail.com</a>
                                </div>
                                <div>
                                    <a href="tel">
                                        <svg width="14" class="me-1" height="14" viewBox="0 0 14 14"
                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                                fill="black" />
                                        </svg>
                                        1-800-5587</a>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex flex-column mt-3">
                            <div class="right-note mb-2">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                            </div>
                            <div class="right-note ms-auto">
                                <input class="form-check-input" type="checkbox" name="Guest RSVP’s" checked="">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn save_event_co_host">Save</a>
    </div>
</div>

<div id="sidebar_gift_registry" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <h5>Gift Registry</h5>
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div id="registry_list">
            {{-- <div class="trgistry-content d-flex justify-content-between">
                <div>
                    <h5>Jeremy Zucker</h4>
                    <a href="#">jeremyzucker.shop.amazon.com</a>
                </div>
                <div>
                    <div class="d-flex ms-auto">
                        <a href="#" class="me-3"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.1665 1.66699H7.49984C3.33317 1.66699 1.6665 3.33366 1.6665 7.50033V12.5003C1.6665 16.667 3.33317 18.3337 7.49984 18.3337H12.4998C16.6665 18.3337 18.3332 16.667 18.3332 12.5003V10.8337" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3666 2.51639L6.7999 9.08306C6.5499 9.33306 6.2999 9.82472 6.2499 10.1831L5.89157 12.6914C5.75823 13.5997 6.3999 14.2331 7.30823 14.1081L9.81657 13.7497C10.1666 13.6997 10.6582 13.4497 10.9166 13.1997L17.4832 6.63306C18.6166 5.49972 19.1499 4.18306 17.4832 2.51639C15.8166 0.849722 14.4999 1.38306 13.3666 2.51639Z" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.4248 3.45801C12.9831 5.44967 14.5415 7.00801 16.5415 7.57467" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a >
                        <a href="#">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.0835 4.14199L7.26683 3.05033C7.40016 2.25866 7.50016 1.66699 8.9085 1.66699H11.0918C12.5002 1.66699 12.6085 2.29199 12.7335 3.05866L12.9168 4.14199" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.7082 7.61621L15.1665 16.0079C15.0748 17.3162 14.9998 18.3329 12.6748 18.3329H7.32484C4.99984 18.3329 4.92484 17.3162 4.83317 16.0079L4.2915 7.61621" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.6084 13.75H11.3834" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.9165 10.417H12.0832" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a >
                    </div>
                </div>
            </div> --}}
            {{-- <div class="trgistry-content d-flex justify-content-between">
                <div>
                    <h5>Silvia Alegra</h4>
                    <a href="#">silviaalegra.shop.amazon.com</a>
                </div>
                <div>
                    <div class="d-flex ms-auto">
                        <a href="#" class="me-3"><svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.1665 1.66699H7.49984C3.33317 1.66699 1.6665 3.33366 1.6665 7.50033V12.5003C1.6665 16.667 3.33317 18.3337 7.49984 18.3337H12.4998C16.6665 18.3337 18.3332 16.667 18.3332 12.5003V10.8337" stroke="#CBD5E1" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M13.3666 2.51639L6.7999 9.08306C6.5499 9.33306 6.2999 9.82472 6.2499 10.1831L5.89157 12.6914C5.75823 13.5997 6.3999 14.2331 7.30823 14.1081L9.81657 13.7497C10.1666 13.6997 10.6582 13.4497 10.9166 13.1997L17.4832 6.63306C18.6166 5.49972 19.1499 4.18306 17.4832 2.51639C15.8166 0.849722 14.4999 1.38306 13.3666 2.51639Z" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            <path d="M12.4248 3.45801C12.9831 5.44967 14.5415 7.00801 16.5415 7.57467" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                        <a href="#">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.0835 4.14199L7.26683 3.05033C7.40016 2.25866 7.50016 1.66699 8.9085 1.66699H11.0918C12.5002 1.66699 12.6085 2.29199 12.7335 3.05866L12.9168 4.14199" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M15.7082 7.61621L15.1665 16.0079C15.0748 17.3162 14.9998 18.3329 12.6748 18.3329H7.32484C4.99984 18.3329 4.92484 17.3162 4.83317 16.0079L4.2915 7.61621" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8.6084 13.75H11.3834" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M7.9165 10.417H12.0832" stroke="#E03137" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn add_new_gift_registry">Add new gift registry</a>
    </div>
</div>

<div id="sidebar_gift_registry_item" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <div class="d-flex align-items-center">
                <a href="#" class="me-3" onclick="toggleSidebar('sidebar_gift_registry')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h5>Create Gift Registry</h5>
            </div>

            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="guest-group-name login-form-wrap add-category-new-wrp">
            <form action="" class="registry-form">
                <div class="input-form">
                    <input class="form-control" type="text" id="recipient_name" maxlength="30" onkeyup="clearError(this)"
                        name="recipient_name">
                    <label for="email" class="floating-label">Recipients name <span>*</span></label>
                    <span class="sub-con recipient-name-con">0/30</span>
                    <label for="email" id="recipient_name_error" class="common_error"></label>
                    <input type="hidden" id="registry_item_id" />

                </div>
                <div class="input-form">
                    <input class="form-control" type="text" id="registry_link" name="registry_link"
                        onkeyup="clearError(this)">
                    <label for="email" class="floating-label">Registry Link <span>*</span></label>
                    <label for="email" id="registry_link_error"></label>

                </div>
            </form>
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn add_gift_item_btn">Save</a>
    </div>
</div>

<div id="sidebar_addcategory" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar add_category">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <div class="d-flex align-items-center">
                <a href="#" class="me-3" onclick="toggleSidebar('sidebar_potluck')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                            stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
                <h5>Add New Category</h5>
            </div>

            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
        </div>
        <div class="guest-group-name login-form-wrap add-category-new-wrp">
            <form action="" class="registry-form ">
                <div class="input-form">
                    <input class="form-control" type="text" id="categoryName" maxlength="30" onkeyup="clearError(this)"
                        name="text1" placeholder="Categories: e.g., Appetizers, Salads, Drinks, etc">
                    <span class="sub-con pot-cate-name">0/30</span>
                    <label for="categoryName" id="categoryNameError"></label>
                    <input type="hidden" id="hidden_category_name" />
                    <input type="hidden" id="hidden_category_quantity" />
                </div>
                <div class="qty-container">
                    <button class="qty-btn-minus-qty" type="button" onclick="clearError()"><i
                            class="fa fa-minus"></i></button>
                    <input type="number" name="qty" id="category_quantity" value="1" class="input-qty">
                    <button class="qty-btn-plus-qty" type="button" onclick="clearError()"><i
                            class="fa fa-plus"></i></button>
                </div>
                <label for="category_quantity" id="category_quantity_error"></label>
            </form>
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn add_category_btn">Save</a>
    </div>
    <div class="new-event-btn">
        {{-- <a href="#" class="cmn-btn add_category_item_btn" style="display: none;">Save</a> --}}
    </div>
</div>

<div id="sidebar_potluck" class="sidebar setting-side-wrp">
    <div class="sidebar-content setting-sidebar">
        <div>
            <div class="d-flex align-items-center justify-content-between toggle-wrp">
                <div class="d-flex align-items-center">
                    <a href="#" class="me-3" onclick="toggleSidebar('')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </a>
                    <h5>Create Potluck</h5>
                </div>

                <button class="close-btn" onclick="toggleSidebar('')">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            @if(!session('potluck_closed'))
            <div class="alert-box d-flex align-items-center" id="potluck_tip_bar">
                <span class="me-3">
                    <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.99984 0.666992C4.40817 0.666992 0.666504 4.40866 0.666504 9.00033C0.666504 13.592 4.40817 17.3337 8.99984 17.3337C13.5915 17.3337 17.3332 13.592 17.3332 9.00033C17.3332 4.40866 13.5915 0.666992 8.99984 0.666992ZM8.37484 5.66699C8.37484 5.32533 8.65817 5.04199 8.99984 5.04199C9.3415 5.04199 9.62484 5.32533 9.62484 5.66699V9.83366C9.62484 10.1753 9.3415 10.4587 8.99984 10.4587C8.65817 10.4587 8.37484 10.1753 8.37484 9.83366V5.66699ZM9.7665 12.6503C9.72484 12.7587 9.6665 12.842 9.5915 12.9253C9.50817 13.0003 9.4165 13.0587 9.3165 13.1003C9.2165 13.142 9.10817 13.167 8.99984 13.167C8.8915 13.167 8.78317 13.142 8.68317 13.1003C8.58317 13.0587 8.4915 13.0003 8.40817 12.9253C8.33317 12.842 8.27484 12.7587 8.23317 12.6503C8.1915 12.5503 8.1665 12.442 8.1665 12.3337C8.1665 12.2253 8.1915 12.117 8.23317 12.017C8.27484 11.917 8.33317 11.8253 8.40817 11.742C8.4915 11.667 8.58317 11.6087 8.68317 11.567C8.88317 11.4837 9.1165 11.4837 9.3165 11.567C9.4165 11.6087 9.50817 11.667 9.5915 11.742C9.6665 11.8253 9.72484 11.917 9.7665 12.017C9.80817 12.117 9.83317 12.2253 9.83317 12.3337C9.83317 12.442 9.80817 12.5503 9.7665 12.6503Z"
                            fill="#1C8B5C" />
                    </svg>
                </span>
                <p>First add categories then you can add individual items under those categories.</p>
                <span class="ms-3" id="potluck_tip">
                    <svg width="12" height="12" viewBox="0 0 12 12" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.33398 1.33301L10.6667 10.6657" stroke="#1C8B5C" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M1.33331 10.6657L10.666 1.33301" stroke="#1C8B5C" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
            </div>
            @endif
            <div class="potluck-category">
                <h5>Potluck Categories</h5>
                <input type="hidden" id="category_count" value="0">
                {{-- <div class="category-main-dishesh"> 
                <div class="category-list">
                    <div class="list-header">
                        <span class="me-1 list-sub-head">0</span>
                        <div>
                            <h5>sdsdsfddsf</h5>
                            <p>Total Commited</p>
                        </div>
                        <div class="ms-auto d-flex align-items-center ">
                            <span class="me-3">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71"/>
                                </svg>
                            </span>
                            <h6 class="me-3">7 Missing</h6>
                            <a href="#" class="me-3">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z" fill="#F73C71"/>
                                </svg>
                            </a>
                            <a href="#" type="button" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.0835 4.14102L7.26683 3.04935C7.40016 2.25768 7.50016 1.66602 8.9085 1.66602L11.0918 1.66602C12.5002 1.66602 12.6085 2.29102 12.7335 3.05768L12.9168 4.14102" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.7082 7.61719L15.1665 16.0089C15.0748 17.3172 14.9998 18.3339 12.6748 18.3339H7.32484C4.99984 18.3339 4.92484 17.3172 4.83317 16.0089L4.2915 7.61719" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.6084 13.75H11.3834" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.9165 10.416H12.0832" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                  </div>
                    <div class="list-body d-flex align-items-center">
                        <span class="me-2">
                            <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M6.99984 14.166C10.6732 14.166 13.6665 11.1727 13.6665 7.49935C13.6665 3.82602 10.6732 0.832682 6.99984 0.832682C3.3265 0.832682 0.33317 3.82602 0.33317 7.49935C0.33317 11.1727 3.3265 14.166 6.99984 14.166ZM7.49984 10.166C7.49984 10.4393 7.27317 10.666 6.99984 10.666C6.7265 10.666 6.49984 10.4393 6.49984 10.166V6.83268C6.49984 6.55935 6.7265 6.33268 6.99984 6.33268C7.27317 6.33268 7.49984 6.55935 7.49984 6.83268V10.166ZM6.3865 4.57935C6.41984 4.49268 6.4665 4.42602 6.5265 4.35935C6.59317 4.29935 6.6665 4.25268 6.7465 4.21935C6.8265 4.18602 6.91317 4.16602 6.99984 4.16602C7.0865 4.16602 7.17317 4.18602 7.25317 4.21935C7.33317 4.25268 7.4065 4.29935 7.47317 4.35935C7.53317 4.42602 7.57984 4.49268 7.61317 4.57935C7.6465 4.65935 7.6665 4.74602 7.6665 4.83268C7.6665 4.91935 7.6465 5.00602 7.61317 5.08602C7.57984 5.16602 7.53317 5.23935 7.47317 5.30602C7.4065 5.36602 7.33317 5.41268 7.25317 5.44602C7.09317 5.51268 6.9065 5.51268 6.7465 5.44602C6.6665 5.41268 6.59317 5.36602 6.5265 5.30602C6.4665 5.23935 6.41984 5.16602 6.3865 5.08602C6.35317 5.00602 6.33317 4.91935 6.33317 4.83268C6.33317 4.74602 6.35317 4.65935 6.3865 4.57935Z" fill="#FD5983"/>
                            </svg>
                        </span>
                        <p>Nobody has added anything yet</h5>
                    </div>
                    <div class="list-slide">
                        <div class="accordion accordion-flush" id="accordioncatList">
                            <div class="accordion-item red-border">
                              <h2 class="accordion-header" id="lumpia">
                                <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#lumpia-collapseOne" aria-expanded="false" aria-controls="lumpia-collapseOne">
                                    <div class="d-flex align-items-center">
                                        <span class="me-1 list-sub-head">0</span>
                                        <div>
                                            <h5>Lumpia</h5>
                                            <p>Requested by: Pristia Candra</p>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="me-3">
                                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71"/>
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="ms-auto">
                                        <h6>0/7</h6>
                                    </div>
                                </button>
                              </h2>
                              <div id="lumpia-collapseOne" class="accordion-collapse collapse" aria-labelledby="lumpia" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="accordion-body-content">
                                        <img src="./assets/image/user-img.svg" alt="">
                                        <h5>Emilia Clark</h5>
                                        <span class="ms-auto">1</span>
                                    </div>
                                    <div class="accordion-body-content limits-count">
                                        <img src="./assets/image/user-img.svg" alt="">
                                        <h5>Pristia Candra</h5>
                                        <div class="qty-container ms-auto">
                                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="qty" value="0" class="input-qty"/>
                                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="d-flex">
                                            <a href="#" class="me-3"> 
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                            <a href="#">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                        </div>
 
                    </div>
                </div>
                <a href="#" class="listing-arrow">
                    <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.9401 1.71289L8.05006 6.60289C7.47256 7.18039 6.52756 7.18039 5.95006 6.60289L1.06006 1.71289" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
           </div> --}}


                {{-- <div class="category-main-dishesh"> 
                <div class="category-list">
                    <div class="list-header">
                        <span class="me-1 list-sub-head">7</span>
                        <div>
                            <h5>Appetizers</h5>
                            <p>Total Committed </p>
                        </div>
                        <div class="ms-auto d-flex align-items-center ">
                            <span class="me-3">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71"/>
                                </svg>
                            </span>
                            <h6 class="me-3">1 Missing</h6>
                            <a href="#" class="me-3">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z" fill="#F73C71"/>
                                </svg>
                            </a>
                            <a href="#">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.0835 4.14102L7.26683 3.04935C7.40016 2.25768 7.50016 1.66602 8.9085 1.66602L11.0918 1.66602C12.5002 1.66602 12.6085 2.29102 12.7335 3.05768L12.9168 4.14102" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M15.7082 7.61719L15.1665 16.0089C15.0748 17.3172 14.9998 18.3339 12.6748 18.3339H7.32484C4.99984 18.3339 4.92484 17.3172 4.83317 16.0089L4.2915 7.61719" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M8.6084 13.75H11.3834" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                    <path d="M7.9165 10.416H12.0832" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </a>
                        </div>
                    </div>
                    <div class="list-slide">
                        <div class="accordion accordion-flush" id="accordioncatList">
                            <div class="accordion-item green-border">
                                <h2 class="accordion-header" id="Bruschetta">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#Bruschetta-collapseOne" aria-expanded="false" aria-controls="Bruschetta-collapseOne">
                                        <div class="d-flex align-items-center">
                                            <span class="me-1 list-sub-head">3</span>
                                            <div>
                                                <h5>Bruschetta</h5>
                                                <p>Requested by: Pristia Candra</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ms-auto">
                                            <div>
                                                <span class="me-3">
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div>
                                                <h6>3/3</h6>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="Bruschetta-collapseOne" class="accordion-collapse collapse show" aria-labelledby="Bruschetta" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="accordion-body-content">
                                            <img src="./assets/image/user-img.svg" alt="">
                                            <h5>Emilia Clark</h5>
                                            <span class="ms-auto">1</span>
                                        </div>
                                        <div class="accordion-body-content limits-count">
                                            <img src="./assets/image/user-img.svg" alt="">
                                            <h5>Pristia Candra</h5>
                                            <div class="qty-container ms-auto">
                                                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                                <input type="number" name="qty" value="0" class="input-qty"/>
                                                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="d-flex">
                                                <a href="#" class="me-3"> 
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                                <a href="#">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item red-border">
                                <h2 class="accordion-header" id="lumpa">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#lumpa-collapseOne" aria-expanded="false" aria-controls="lumpa-collapseOne">
                                        <div class="d-flex align-items-center">
                                            <span class="me-1 list-sub-head">2</span>
                                            <div>
                                                <h5>Lumpia</h5>
                                                <p>Requested by: Pristia Candra</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ms-auto">
                                            <div>
                                                <span class="me-3">
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div>
                                                <h6>2/3</h6>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="lumpa-collapseOne" class="accordion-collapse collapse show" aria-labelledby="lumpa" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <div class="accordion-body-content">
                                            <img src="./assets/image/user-img.svg" alt="">
                                            <h5>Emilia Clark</h5>
                                            <span class="ms-auto">1</span>
                                        </div>
                                        <div class="accordion-body-content limits-count">
                                            <img src="./assets/image/user-img.svg" alt="">
                                            <h5>Pristia Candra</h5>
                                            <div class="qty-container ms-auto">
                                                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                                <input type="number" name="qty" value="0" class="input-qty"/>
                                                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="d-flex">
                                                <a href="#" class="me-3"> 
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                                <a href="#">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="accordion-item green-border">
                                <h2 class="accordion-header" id="quesadillas">
                                    <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#quesadillas-collapseOne" aria-expanded="false" aria-controls="quesadillas-collapseOne">
                                        <div class="d-flex align-items-center">
                                            <span class="me-1 list-sub-head">2</span>
                                            <div>
                                                <h5>Quesadillas</h5>
                                                <p>Requested by: Pristia Candra</p>
                                            </div>
                                        </div>
                                        <div class="d-flex align-items-center ms-auto">
                                            <div>
                                                <span class="me-3">
                                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"/>
                                                    </svg>
                                                </span>
                                            </div>
                                            <div>
                                                <h6>2/2</h6>
                                            </div>
                                        </div>
                                    </button>
                                </h2>
                                <div id="quesadillas-collapseOne" class="accordion-collapse collapse show" aria-labelledby="quesadillas" data-bs-parent="#accordionFlushExample">
                                    <div class="accordion-body">
                                        <!-- <div class="accordion-body-content">
                                            <img src="./assets/image/user-img.svg" alt="">
                                            <h5>Emilia Clark</h5>
                                            <span class="ms-auto">1</span>
                                        </div> -->
                                        <div class="accordion-body-content limits-count">
                                            <img src="./assets/image/user-img.svg" alt="">
                                            <h5>Pristia Candra</h5>
                                            <div class="qty-container ms-auto">
                                                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                                <input type="number" name="qty" value="0" class="input-qty"/>
                                                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                            <div class="d-flex">
                                                <a href="#" class="me-3"> 
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                                <a href="#">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
 
                    </div>
                </div>
                <a href="#" class="listing-arrow">
                    <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.9401 1.71289L8.05006 6.60289C7.47256 7.18039 6.52756 7.18039 5.95006 6.60289L1.06006 1.71289" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <div class="category-main-dishesh"> 
                <div class="category-list">
                    <div class="list-header">
                        <span class="me-1 list-sub-head">5</span>
                        <div>
                            <h5>Sodas</h5>
                            <p>Total Committed </p>
                        </div>
                        <div class="ms-auto d-flex align-items-center ">
                            <span class="me-3">
                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"/>
                                </svg>
                            </span>
                            <h6 class="me-3 missing-green">0 Missing</h6>
                            <a href="#" class="me-3">
                                <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z" fill="#F73C71"/>
                                </svg>
                            </a>
                            <div class="dropdown">
                                <a href="#" class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M11.6665 4.16667C11.6665 3.25 10.9165 2.5 9.99984 2.5C9.08317 2.5 8.33317 3.25 8.33317 4.16667C8.33317 5.08333 9.08317 5.83333 9.99984 5.83333C10.9165 5.83333 11.6665 5.08333 11.6665 4.16667Z" fill="#0F172A"/>
                                        <path d="M11.6665 15.8327C11.6665 14.916 10.9165 14.166 9.99984 14.166C9.08317 14.166 8.33317 14.916 8.33317 15.8327C8.33317 16.7493 9.08317 17.4993 9.99984 17.4993C10.9165 17.4993 11.6665 16.7493 11.6665 15.8327Z" fill="#0F172A"/>
                                        <path d="M11.6665 10.0007C11.6665 9.08398 10.9165 8.33398 9.99984 8.33398C9.08317 8.33398 8.33317 9.08398 8.33317 10.0007C8.33317 10.9173 9.08317 11.6673 9.99984 11.6673C10.9165 11.6673 11.6665 10.9173 11.6665 10.0007Z" fill="#0F172A"/>
                                    </svg>
                                </a>
                                <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">
                                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.4998 18.9577L7.49984 18.9577C2.97484 18.9577 1.0415 17.0243 1.0415 12.4993L1.0415 7.49935C1.0415 2.97435 2.97484 1.04102 7.49984 1.04102L9.1665 1.04102C9.50817 1.04102 9.7915 1.32435 9.7915 1.66602C9.7915 2.00768 9.50817 2.29102 9.1665 2.29102L7.49984 2.29102C3.65817 2.29102 2.2915 3.65768 2.2915 7.49935L2.2915 12.4993C2.2915 16.341 3.65817 17.7077 7.49984 17.7077L12.4998 17.7077C16.3415 17.7077 17.7082 16.341 17.7082 12.4993L17.7082 10.8327C17.7082 10.491 17.9915 10.2077 18.3332 10.2077C18.6748 10.2077 18.9582 10.491 18.9582 10.8327L18.9582 12.4993C18.9582 17.0243 17.0248 18.9577 12.4998 18.9577Z" fill="#94A3B8"/>
                                                        <path d="M7.08311 14.7424C6.57478 14.7424 6.10811 14.5591 5.76645 14.2258C5.35811 13.8174 5.18311 13.2258 5.27478 12.6008L5.63311 10.0924C5.69978 9.60911 6.01645 8.98411 6.35811 8.64245L12.9248 2.07578C14.5831 0.417448 16.2664 0.417448 17.9248 2.07578C18.8331 2.98411 19.2414 3.90911 19.1581 4.83411C19.0831 5.58411 18.6831 6.31745 17.9248 7.06745L11.3581 13.6341C11.0164 13.9758 10.3914 14.2924 9.90811 14.3591L7.39978 14.7174C7.29145 14.7424 7.18311 14.7424 7.08311 14.7424ZM13.8081 2.95911L7.24145 9.52578C7.08311 9.68411 6.89978 10.0508 6.86645 10.2674L6.50811 12.7758C6.47478 13.0174 6.52478 13.2174 6.64978 13.3424C6.77478 13.4674 6.97478 13.5174 7.21645 13.4841L9.72478 13.1258C9.94145 13.0924 10.3164 12.9091 10.4664 12.7508L17.0331 6.18411C17.5748 5.64245 17.8581 5.15911 17.8998 4.70911C17.9498 4.16745 17.6664 3.59245 17.0331 2.95078C15.6998 1.61745 14.7831 1.99245 13.8081 2.95911Z" fill="#94A3B8"/>
                                                        <path d="M16.5418 8.19124C16.4835 8.19124 16.4251 8.18291 16.3751 8.16624C14.1835 7.54957 12.4418 5.80791 11.8251 3.61624C11.7335 3.28291 11.9251 2.94124 12.2585 2.84124C12.5918 2.74957 12.9335 2.94124 13.0251 3.27457C13.5251 5.04957 14.9335 6.45791 16.7085 6.95791C17.0418 7.04957 17.2335 7.39957 17.1418 7.73291C17.0668 8.01624 16.8168 8.19124 16.5418 8.19124Z" fill="#94A3B8"/>
                                                    </svg>
                                                </span>
                                                <h6>Edit</p>
                                            </div>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="#">
                                            <div class="d-flex align-items-center">
                                                <span class="me-2">
                                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    </svg>
                                                </span>
                                                <h6>Delete</p>
                                            </div>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                  </div>
                    <div class="list-slide">
                        <div class="accordion accordion-flush" id="accordioncatList">
                            <div class="accordion-item green-border">
                              <h2 class="accordion-header" id="Coke">
                                <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#Coke-collapseOne" aria-expanded="false" aria-controls="Coke-collapseOne">
                                    <div class="d-flex align-items-center">
                                        <span class="me-1 list-sub-head">0</span>
                                        <div>
                                            <h5>Coke</h5>
                                            <p>Requested by: Pristia Candra</p>
                                        </div>
                                    </div>
                                    <div class="ms-auto d-flex align-items-center">
                                        <div >
                                            <span class="me-3">
                                                <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path>
                                                </svg>
                                            </span>
                                        </div>
                                        <div class="ms-auto">
                                            <h6>2/2</h6>
                                        </div>
                                    </div>
                                </button>
                              </h2>
                              <div id="Coke-collapseOne" class="accordion-collapse collapse" aria-labelledby="Coke" data-bs-parent="#accordionFlushExample">
                                <div class="accordion-body">
                                    <div class="accordion-body-content limits-count">
                                        <img src="./assets/image/user-img.svg" alt="">
                                        <h5>Pristia Candra</h5>
                                        <div class="qty-container ms-auto">
                                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="qty" value="0" class="input-qty"/>
                                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                        </div>
                                        <div class="d-flex">
                                            <a href="#" class="me-3"> 
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                            <a href="#">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                              </div>
                            </div>
                            <div class="accordion-item green-border">
                                <h2 class="accordion-header" id="Rootbeer">
                                  <button class="accordion-button collapsed " type="button" data-bs-toggle="collapse" data-bs-target="#Rootbeer-collapseOne" aria-expanded="false" aria-controls="Rootbeer-collapseOne">
                                      <div class="d-flex align-items-center">
                                          <span class="me-1 list-sub-head">2</span>
                                          <div>
                                              <h5>Rootbeer</h5>
                                              <p>Requested by: Pristia Candra</p>
                                          </div>
                                      </div>
                                        <div class="ms-auto d-flex align-items-center">
                                            <div>
                                                <span class="me-3">
                                                      <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                          <path d="M7.00016 0.333984C3.32683 0.333984 0.333496 3.32732 0.333496 7.00065C0.333496 10.674 3.32683 13.6673 7.00016 13.6673C10.6735 13.6673 13.6668 10.674 13.6668 7.00065C13.6668 3.32732 10.6735 0.333984 7.00016 0.333984ZM10.1868 5.46732L6.40683 9.24732C6.3135 9.34065 6.18683 9.39398 6.0535 9.39398C5.92016 9.39398 5.7935 9.34065 5.70016 9.24732L3.8135 7.36065C3.62016 7.16732 3.62016 6.84732 3.8135 6.65398C4.00683 6.46065 4.32683 6.46065 4.52016 6.65398L6.0535 8.18732L9.48016 4.76065C9.6735 4.56732 9.9935 4.56732 10.1868 4.76065C10.3802 4.95398 10.3802 5.26732 10.1868 5.46732Z" fill="#23AA26"></path>
                                                      </svg>
                                                </span>
                                              </div>
                                            <div class="ms-auto">
                                                <h6>3/3</h6>
                                            </div>
                                        </div>
                                  </button>
                                </h2>
                                <div id="Rootbeer-collapseOne" class="accordion-collapse collapse" aria-labelledby="Rootbeer" data-bs-parent="#accordionFlushExample">
                                  <div class="accordion-body">
                                      <div class="accordion-body-content limits-count">
                                          <img src="./assets/image/user-img.svg" alt="">
                                          <h5>Pristia Candra</h5>
                                          <div class="qty-container ms-auto">
                                              <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                              <input type="number" name="qty" value="0" class="input-qty"/>
                                              <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                          </div>
                                          <div class="d-flex">
                                              <a href="#" class="me-3"> 
                                                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                      <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                      <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                  </svg>
                                              </a>
                                              <a href="#">
                                                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                      <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                      <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                      <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                      <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                      <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                  </svg>
                                              </a>
                                          </div>
                                      </div>
                                  </div>
                                </div>
                              </div>
                        </div>
 
                    </div>
                </div>
                <a href="#" class="listing-arrow">
                    <svg width="14" height="8" viewBox="0 0 14 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12.9401 1.71289L8.05006 6.60289C7.47256 7.18039 6.52756 7.18039 5.95006 6.60289L1.06006 1.71289" stroke="#CBD5E1" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
           </div>
           <div class="category-main-dishesh"> 
            <div class="category-list">
                <div class="list-header">
                    <span class="me-1 list-sub-head">0</span>
                    <div>
                        <h5>Desserts</h5>
                        <p>Total Commited</p>
                    </div>
                    <div class="ms-auto d-flex align-items-center ">
                        <!-- <span class="me-3">
                            <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M13.5067 9.61399L9.23998 1.93398C8.66665 0.900651 7.87332 0.333984 6.99998 0.333984C6.12665 0.333984 5.33332 0.900651 4.75998 1.93398L0.493318 9.61399C-0.0466816 10.594 -0.106682 11.534 0.326652 12.274C0.759985 13.014 1.61332 13.4207 2.73332 13.4207H11.2667C12.3867 13.4207 13.24 13.014 13.6733 12.274C14.1067 11.534 14.0467 10.5873 13.5067 9.61399ZM6.49998 5.00065C6.49998 4.72732 6.72665 4.50065 6.99998 4.50065C7.27332 4.50065 7.49998 4.72732 7.49998 5.00065V8.33398C7.49998 8.60732 7.27332 8.83398 6.99998 8.83398C6.72665 8.83398 6.49998 8.60732 6.49998 8.33398V5.00065ZM7.47332 10.8073C7.43998 10.834 7.40665 10.8607 7.37332 10.8873C7.33332 10.914 7.29332 10.934 7.25332 10.9473C7.21332 10.9673 7.17332 10.9807 7.12665 10.9873C7.08665 10.994 7.03998 11.0007 6.99998 11.0007C6.95998 11.0007 6.91332 10.994 6.86665 10.9873C6.82665 10.9807 6.78665 10.9673 6.74665 10.9473C6.70665 10.934 6.66665 10.914 6.62665 10.8873C6.59332 10.8607 6.55998 10.834 6.52665 10.8073C6.40665 10.6807 6.33332 10.5073 6.33332 10.334C6.33332 10.1607 6.40665 9.98732 6.52665 9.86065C6.55998 9.83399 6.59332 9.80732 6.62665 9.78065C6.66665 9.75398 6.70665 9.73398 6.74665 9.72065C6.78665 9.70065 6.82665 9.68732 6.86665 9.68065C6.95332 9.66065 7.04665 9.66065 7.12665 9.68065C7.17332 9.68732 7.21332 9.70065 7.25332 9.72065C7.29332 9.73398 7.33332 9.75398 7.37332 9.78065C7.40665 9.80732 7.43998 9.83399 7.47332 9.86065C7.59332 9.98732 7.66665 10.1607 7.66665 10.334C7.66665 10.5073 7.59332 10.6807 7.47332 10.8073Z" fill="#F73C71"/>
                            </svg>
                        </span>
                        <h6 class="me-3">7 Missing</h6> -->
 
                        <a href="#" class="me-3">
                            <svg width="22" height="22" viewBox="0 0 22 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M10.9998 0.166016C5.03067 0.166016 0.166504 5.03018 0.166504 10.9993C0.166504 16.9685 5.03067 21.8327 10.9998 21.8327C16.969 21.8327 21.8332 16.9685 21.8332 10.9993C21.8332 5.03018 16.969 0.166016 10.9998 0.166016ZM15.3332 11.8118H11.8123V15.3327C11.8123 15.7768 11.444 16.1452 10.9998 16.1452C10.5557 16.1452 10.1873 15.7768 10.1873 15.3327V11.8118H6.6665C6.22234 11.8118 5.854 11.4435 5.854 10.9993C5.854 10.5552 6.22234 10.1868 6.6665 10.1868H10.1873V6.66602C10.1873 6.22185 10.5557 5.85352 10.9998 5.85352C11.444 5.85352 11.8123 6.22185 11.8123 6.66602V10.1868H15.3332C15.7773 10.1868 16.1457 10.5552 16.1457 10.9993C16.1457 11.4435 15.7773 11.8118 15.3332 11.8118Z" fill="#F73C71"/>
                            </svg>
                        </a>
                        <div class="dropdown">
                            <a href="#" class="dropdown-toggle" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M11.6665 4.16667C11.6665 3.25 10.9165 2.5 9.99984 2.5C9.08317 2.5 8.33317 3.25 8.33317 4.16667C8.33317 5.08333 9.08317 5.83333 9.99984 5.83333C10.9165 5.83333 11.6665 5.08333 11.6665 4.16667Z" fill="#0F172A"/>
                                    <path d="M11.6665 15.8327C11.6665 14.916 10.9165 14.166 9.99984 14.166C9.08317 14.166 8.33317 14.916 8.33317 15.8327C8.33317 16.7493 9.08317 17.4993 9.99984 17.4993C10.9165 17.4993 11.6665 16.7493 11.6665 15.8327Z" fill="#0F172A"/>
                                    <path d="M11.6665 10.0007C11.6665 9.08398 10.9165 8.33398 9.99984 8.33398C9.08317 8.33398 8.33317 9.08398 8.33317 10.0007C8.33317 10.9173 9.08317 11.6673 9.99984 11.6673C10.9165 11.6673 11.6665 10.9173 11.6665 10.0007Z" fill="#0F172A"/>
                                </svg>
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton1">
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M12.4998 18.9577L7.49984 18.9577C2.97484 18.9577 1.0415 17.0243 1.0415 12.4993L1.0415 7.49935C1.0415 2.97435 2.97484 1.04102 7.49984 1.04102L9.1665 1.04102C9.50817 1.04102 9.7915 1.32435 9.7915 1.66602C9.7915 2.00768 9.50817 2.29102 9.1665 2.29102L7.49984 2.29102C3.65817 2.29102 2.2915 3.65768 2.2915 7.49935L2.2915 12.4993C2.2915 16.341 3.65817 17.7077 7.49984 17.7077L12.4998 17.7077C16.3415 17.7077 17.7082 16.341 17.7082 12.4993L17.7082 10.8327C17.7082 10.491 17.9915 10.2077 18.3332 10.2077C18.6748 10.2077 18.9582 10.491 18.9582 10.8327L18.9582 12.4993C18.9582 17.0243 17.0248 18.9577 12.4998 18.9577Z" fill="#94A3B8"/>
                                                    <path d="M7.08311 14.7424C6.57478 14.7424 6.10811 14.5591 5.76645 14.2258C5.35811 13.8174 5.18311 13.2258 5.27478 12.6008L5.63311 10.0924C5.69978 9.60911 6.01645 8.98411 6.35811 8.64245L12.9248 2.07578C14.5831 0.417448 16.2664 0.417448 17.9248 2.07578C18.8331 2.98411 19.2414 3.90911 19.1581 4.83411C19.0831 5.58411 18.6831 6.31745 17.9248 7.06745L11.3581 13.6341C11.0164 13.9758 10.3914 14.2924 9.90811 14.3591L7.39978 14.7174C7.29145 14.7424 7.18311 14.7424 7.08311 14.7424ZM13.8081 2.95911L7.24145 9.52578C7.08311 9.68411 6.89978 10.0508 6.86645 10.2674L6.50811 12.7758C6.47478 13.0174 6.52478 13.2174 6.64978 13.3424C6.77478 13.4674 6.97478 13.5174 7.21645 13.4841L9.72478 13.1258C9.94145 13.0924 10.3164 12.9091 10.4664 12.7508L17.0331 6.18411C17.5748 5.64245 17.8581 5.15911 17.8998 4.70911C17.9498 4.16745 17.6664 3.59245 17.0331 2.95078C15.6998 1.61745 14.7831 1.99245 13.8081 2.95911Z" fill="#94A3B8"/>
                                                    <path d="M16.5418 8.19124C16.4835 8.19124 16.4251 8.18291 16.3751 8.16624C14.1835 7.54957 12.4418 5.80791 11.8251 3.61624C11.7335 3.28291 11.9251 2.94124 12.2585 2.84124C12.5918 2.74957 12.9335 2.94124 13.0251 3.27457C13.5251 5.04957 14.9335 6.45791 16.7085 6.95791C17.0418 7.04957 17.2335 7.39957 17.1418 7.73291C17.0668 8.01624 16.8168 8.19124 16.5418 8.19124Z" fill="#94A3B8"/>
                                                </svg>
                                            </span>
                                            <h6>Edit</p>
                                        </div>
                                    </a>
                                </li>
                                <li>
                                    <a class="dropdown-item" href="#">
                                        <div class="d-flex align-items-center">
                                            <span class="me-2">
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                    <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </span>
                                            <h6>Delete</p>
                                        </div>
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
              </div>
                <div class="list-body d-flex align-items-center">
                    <span class="me-2">
                        <svg width="14" height="15" viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.99984 14.166C10.6732 14.166 13.6665 11.1727 13.6665 7.49935C13.6665 3.82602 10.6732 0.832682 6.99984 0.832682C3.3265 0.832682 0.33317 3.82602 0.33317 7.49935C0.33317 11.1727 3.3265 14.166 6.99984 14.166ZM7.49984 10.166C7.49984 10.4393 7.27317 10.666 6.99984 10.666C6.7265 10.666 6.49984 10.4393 6.49984 10.166V6.83268C6.49984 6.55935 6.7265 6.33268 6.99984 6.33268C7.27317 6.33268 7.49984 6.55935 7.49984 6.83268V10.166ZM6.3865 4.57935C6.41984 4.49268 6.4665 4.42602 6.5265 4.35935C6.59317 4.29935 6.6665 4.25268 6.7465 4.21935C6.8265 4.18602 6.91317 4.16602 6.99984 4.16602C7.0865 4.16602 7.17317 4.18602 7.25317 4.21935C7.33317 4.25268 7.4065 4.29935 7.47317 4.35935C7.53317 4.42602 7.57984 4.49268 7.61317 4.57935C7.6465 4.65935 7.6665 4.74602 7.6665 4.83268C7.6665 4.91935 7.6465 5.00602 7.61317 5.08602C7.57984 5.16602 7.53317 5.23935 7.47317 5.30602C7.4065 5.36602 7.33317 5.41268 7.25317 5.44602C7.09317 5.51268 6.9065 5.51268 6.7465 5.44602C6.6665 5.41268 6.59317 5.36602 6.5265 5.30602C6.4665 5.23935 6.41984 5.16602 6.3865 5.08602C6.35317 5.00602 6.33317 4.91935 6.33317 4.83268C6.33317 4.74602 6.35317 4.65935 6.3865 4.57935Z" fill="#FD5983"/>
                        </svg>
                    </span>
                    <p>Nobody has added anything yet</h5>
                </div>
            </div>
       </div> --}}
            </div>
        </div>


        <!-- ========== add-item-dishesh sidebar ============= -->

        <!-- <div class="item-dishesh-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <div class="d-flex align-items-center">
                <a href="#" class="me-3" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <h5>Add Item Under: Main Dishes</h5>
            </div>
 
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
        <div class="guest-group-name login-form-wrap">
            <form action="" class="registry-form">
                <div class="input-form">
                    <input class="form-control" type="text" id="text1" name="text1" placeholder="Description">
                    <span class="sub-con">9/30</span>
                </div>
            </form>
        </div>
        <div class="d-flex align-items-center justify-content-between item-dishes-toggle">
            <h5>I'll bring this item</h5>
            <div class="toggle-button-cover">
                <div class="button-cover">
                    <div class="button r" id="button-1">
                        <input type="checkbox" class="checkbox" />
                        <div class="knobs"></div>
                        <div class="layer"></div>
                    </div>
                </div>
            </div>
        </div>
        <div class="item-on">
            <img src="./assets/image/user-img.svg" alt="">
            <h5>Pristia Candra</h5>
            <div class="qty-container ms-auto">
                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                <input type="number" name="qty" value="0" class="input-qty"/>
                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
            </div>
            <div class="d-flex">
                <a href="#" class="me-3">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.84006 3.73283L3.36673 9.52616C3.16006 9.74616 2.96006 10.1795 2.92006 10.4795L2.6734 12.6395C2.58673 13.4195 3.14673 13.9528 3.92006 13.8195L6.06673 13.4528C6.36673 13.3995 6.78673 13.1795 6.9934 12.9528L12.4667 7.15949C13.4134 6.15949 13.8401 5.01949 12.3667 3.62616C10.9001 2.24616 9.78673 2.73283 8.84006 3.73283Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M7.92657 4.69922C8.21324 6.53922 9.70657 7.94588 11.5599 8.13255" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
                <a href="#">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M14 3.98763C11.78 3.76763 9.54667 3.6543 7.32 3.6543C6 3.6543 4.68 3.72096 3.36 3.8543L2 3.98763" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M5.66669 3.31398L5.81335 2.44065C5.92002 1.80732 6.00002 1.33398 7.12669 1.33398H8.87335C10 1.33398 10.0867 1.83398 10.1867 2.44732L10.3334 3.31398" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M12.5667 6.09375L12.1334 12.8071C12.06 13.8537 12 14.6671 10.14 14.6671H5.86002C4.00002 14.6671 3.94002 13.8537 3.86668 12.8071L3.43335 6.09375" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.88666 11H9.10666" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M6.33331 8.33398H9.66665" stroke="#F73C71" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
        </div>
        <div class="total-quantity">
            <div class="d-flex">
                <h5 class="me-2">Total quantity for item you’d like (You + Guests)</h5>
                <a href="#">
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.99984 18.3337C14.5832 18.3337 18.3332 14.5837 18.3332 10.0003C18.3332 5.41699 14.5832 1.66699 9.99984 1.66699C5.4165 1.66699 1.6665 5.41699 1.6665 10.0003C1.6665 14.5837 5.4165 18.3337 9.99984 18.3337Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10 6.66699V10.8337" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M9.99561 13.333H10.0031" stroke="#94A3B8" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </a>
            </div>
            <div class="qty-container">
                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                <input type="number" name="qty" value="0" class="input-qty"/>
                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
            </div>
        </div>
    </div> -->
    </div>

    <div class="new-event-btn">
        <a href="#" class="cmn-btn open_addcategory" onclick="toggleSidebar('sidebar_addcategory')">Add new
            category</a>
    </div>
</div>


<div id="sidebar_groups" class="sidebar setting-side-wrp new-sidebar-group-wrp">

    <div class="sidebar-content guest-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <h5>Groups</h5>
            <button class="close-btn group_toggle_close_btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
        <div class="position-relative">
            <input type="search" placeholder="Search name" class="form-control" id="group_toggle_search">
            <span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                    <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </span>
        </div>
        <div class="group_list group_search_list_toggle">
            @foreach ($groups as $group)
            <div class="group-card added_group{{ $group->id }} listgroups" data-id="{{ $group->id }}">
                <div class="view_members" data-id="{{ $group->id }}">
                    <h4>{{ $group->name }}</h4>
                    <p>{{ $group->group_members_count }} Guests</p>
                </div>
                <span class="ms-auto me-3">
                    <svg width="16" id="delete_group" data-id="{{ $group->id }}" height="17"
                        viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M14 4.48665C11.78 4.26665 9.54667 4.15332 7.32 4.15332C6 4.15332 4.68 4.21999 3.36 4.35332L2 4.48665"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                        </path>
                        <path
                            d="M5.66699 3.81301L5.81366 2.93967C5.92033 2.30634 6.00033 1.83301 7.12699 1.83301H8.87366C10.0003 1.83301 10.087 2.33301 10.187 2.94634L10.3337 3.81301"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                        </path>
                        <path
                            d="M12.5669 6.59375L12.1336 13.3071C12.0603 14.3537 12.0003 15.1671 10.1403 15.1671H5.86026C4.00026 15.1671 3.94026 14.3537 3.86693 13.3071L3.43359 6.59375"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round">
                        </path>
                        <path d="M6.88672 11.5H9.10672" stroke="#94A3B8" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                        <path d="M6.33301 8.83301H9.66634" stroke="#94A3B8" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
                <span class="view_members" data-id="{{ $group->id }}">
                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.94043 13.7797L10.2871 9.43306C10.8004 8.91973 10.8004 8.07973 10.2871 7.56639L5.94043 3.21973"
                            stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                </span>
            </div>
            @endforeach

        </div>


        <!-- =========================================  group-sidebar ===================================== -->

        <!-- <div class="groupsidebar">
            <div class="d-flex align-items-center justify-content-between toggle-wrp">
                <h5 >Create New Group</h5>
                <button class="close-btn" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="guest-group-name login-form-wrap">
                <form action="">
                    <div class="input-form">
                        <input class="form-control" type="email" id="email" name="email">
                        <label for="email" class="floating-label">Group Name <span>*</span></label>
                    </div>
                </form>
            </div>
        </div> -->
    </div>

    <div class="new-event-btn">
        <a href="#" class="cmn-btn new_group">Add new
            Group</a>
    </div>
</div>



<div id="sidebar_invite_group_member" class="sidebar setting-side-wrp">

    <div class="sidebar-content guest-sidebar">
        <div class="d-flex align-items-center justify-content-between toggle-wrp">
            <h5>Groups</h5>
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </button>
        </div>
        <div class="position-relative">
            <input type="search" placeholder="Search name" class="form-control">
            <span>
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                    </path>
                    <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round"></path>
                </svg>
            </span>
        </div>
        <div class="group_list get_all_group_member_list">

        </div>


        <!-- =========================================  group-sidebar ===================================== -->

        <!-- <div class="groupsidebar">
            <div class="d-flex align-items-center justify-content-between toggle-wrp">
                <h5 >Create New Group</h5>
                <button class="close-btn" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </button>
            </div>
            <div class="guest-group-name login-form-wrap">
                <form action="">
                    <div class="input-form">
                        <input class="form-control" type="email" id="email" name="email">
                        <label for="email" class="floating-label">Group Name <span>*</span></label>
                    </div>
                </form>
            </div>
        </div> -->
    </div>

    <div class="new-event-btn">
        <a href="#" class="cmn-btn new_group" onclick="toggleSidebar('sidebar_add_groups')">Add new
            Group</a>
    </div>
</div>

<div id="sidebar_add_groups" class="sidebar setting-side-wrp new-sidebar-group-wrp">
    <div class="sidebar-content guest-sidebar">
        <div class="groupsidebar">
            <div class="d-flex align-items-center justify-content-between toggle-wrp">
                <div class="d-flex align-items-center">
                    <a href="#" class="me-3 group_toggle_close_btn" onclick="toggleSidebar('sidebar_groups')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <h5>Create New Group</h5>
                </div>
                <button class="close-btn group_toggle_close_btn" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="guest-group-name login-form-wrap new-group-name-wrp">
                <form action="">
                    <div class="input-form">
                        <input class="form-control" type="text" id="new_group_name"
                            oninput="clearError(this)" name="new_group_name">
                        <label for="email" class="floating-label">Group Name <span>*</span></label>
                        <label for="new_group_name" id="group_name_error"></label>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn add_new_group">Add new
            Group</a>
    </div>
</div>

<div id="sidebar_add_group_member" class="sidebar setting-side-wrp new-sidebar-group-wrp">
    <div class="sidebar-content guest-sidebar">
        <div class="groupsidebar">
            <div class="d-flex align-items-center justify-content-between toggle-wrp">
                <div class="d-flex align-items-center">
                    <a href="#" class="me-3 group_toggle_close_btn" onclick="toggleSidebar('sidebar_groups')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <h5>Add group member</h5>
                </div>
                <button class="close-btn group_toggle_close_btn" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="guest-group-name">
                <div class="user-contacts" id="groupUsers">
                    {{-- @php
                        $i = 0;
                    @endphp
                    @foreach ($yesvite_user as $user)
                        @php
                            $i++;
                        @endphp 
                        <div class="users-data">
                            <div class="d-flex align-items-start">
                                <div class="contact-img">
                                    <img src="{{ asset('assets/event/image/user-img.svg') }}" alt="contact-img">

                </div>
                <div class="text-start">
                    <h5>{{ $user->firstname }}
                        {{ $user->lastname }}
                    </h5>
                    @if (isset($user->email) && $user->email != '')
                    <div>
                        <a href="mailto:silvia@gmail.com">
                            <svg class="me-1" width="14" height="14"
                                viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                    stroke="black" stroke-miterlimit="10"
                                    stroke-linecap="round" stroke-linejoin="round" />
                                <path
                                    d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                    stroke="black" stroke-miterlimit="10"
                                    stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                            {{ $user->email }}</a>
                    </div>
                    @endif
                    @if (isset($user->phone_number) && $user->phone_number != '')
                    <div>
                        <a href="tel">
                            <svg class="me-1" width="14" height="14"
                                viewBox="0 0 14 14" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                    fill="black" />
                            </svg>
                            {{ $user->phone_number }}</a>
                    </div>
                    @endif

                </div>
            </div>
            <div class="d-flex flex-column user_choice_group" data-id="user-{{ $user->id }}">
                @if (isset($user->email) && $user->email != '')
                <div class="right-note d-flex mb-2">
                    <span>Member</span>
                    <span class="mx-3">
                        <img src="{{ asset('assets/event/image/small-logo.svg') }}"
                            alt="logo">
                    </span>
                    <input class="form-check-input user_group_member user_choice"
                        type="checkbox" name="add_by_email[]" data-preferby="email"
                        data-id="user-{{ $user->id }}" data-email="{{ $user->email }}"
                        value="{{ $user->id }}">
                </div>
                @endif
                @if (isset($user->phone_number) && $user->phone_number != '')
                <div class="right-note ms-auto">
                    <input class="form-check-input user_group_member user_choice"
                        type="checkbox" name="add_by_mobile[]"
                        data-preferby="phone" data-mobile="{{ $user->phone_number }}"
                        value="{{ $user->id }}">
                </div>
                @endif
            </div>
        </div>
        @endforeach --}}
    </div>
</div>
</div>
</div>
<div class="new-event-btn">
    <a href="#" class="cmn-btn add_new_group_member">Add new
        Group</a>
</div>
</div>

<div id="sidebar_list_group_member" class="sidebar setting-side-wrp">
    <div class="sidebar-content guest-sidebar">
        <div class="groupsidebar">
            <div class="d-flex align-items-center justify-content-between toggle-wrp">
                <div class="d-flex align-items-center">
                    <a href="#" class="me-3 group_toggle_close_btn" onclick="toggleSidebar('sidebar_groups')">
                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.57 5.92969L3.5 11.9997L9.57 18.0697" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M20.4999 12H3.66992" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                    </a>
                    <h5>Invite group member</h5>
                </div>
                <button class="close-btn group_toggle_close_btn" onclick="toggleSidebar()">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
            <div class="guest-group-name">
                <div class="user-contacts-sidebar new-invite-group-member-wrp">

                </div>
            </div>
        </div>
    </div>
    <div class="new-event-btn">
        <a href="#" class="cmn-btn invite_group_member">Invite member</a>
    </div>
</div>

<div id="sidebar_potluck_overlay" class="overlay" onclick="toggleSidebar('')"></div>
<div id="sidebar_addcategory_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_addcategoryitem_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_allow_limit_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_thankyou_card_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_add_co_host_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_gift_registry_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_gift_registry_item_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_gift_registry_item_edit_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_add_thankyou_card_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_groups_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_add_groups_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_add_group_member_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_invite_group_member_overlay" class="overlay" onclick="toggleSidebar()"></div>









{{-- <!-- Latest compiled JavaScript -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- jquery-cdn -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>

    <!-- custom-js -->
    <script src="./assets/js/common.js"></script>
</body>

</html> --}}