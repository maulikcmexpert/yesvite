  <!-- ========== footer ======= -->
  @php
      $getSocialLink = getSocialLink();
    //   dd($getSocialLink->facebook_link);
  @endphp
  <footer>
      <div class="container-fluid">
          <div class="footer-content d-flex justify-content-between">
              <a href="{{(Auth::guard('web')->check())?route('profile'):route('front.home')}}" class="footer-logo">
                  {{-- <svg width="129" height="36" viewBox="0 0 129 36" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M8.86965 6.81981H8.48313C8.25473 6.80224 8.35136 6.58262 8.35136 6.58262C8.5007 6.23124 8.6149 6.09947 8.43042 5.95013C8.0966 5.68659 7.8155 5.3967 7.5871 5.08924C6.42753 3.52558 6.71742 1.58418 7.79793 0.837483C8.42163 0.407037 9.33524 0.407037 9.95894 0.837483C11.0395 1.58418 11.3206 3.52558 10.1698 5.08924C9.94137 5.3967 9.66905 5.68659 9.32645 5.95013C9.13319 6.09947 9.25617 6.22245 9.40551 6.58262C9.40551 6.58262 9.50214 6.79345 9.27374 6.81981H8.88722H8.86965Z" fill="#ECB015" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M25.9168 9.75338C25.9168 9.05061 26.1627 8.40934 26.5844 7.9174C27.1115 7.2849 27.9021 6.8896 28.7893 6.8896C30.3706 6.8896 31.6619 8.17215 31.6619 9.76217H34.6575C34.6575 6.52064 32.0309 3.89404 28.7893 3.89404C26.6986 3.89404 24.8714 4.98334 23.826 6.62606C23.7733 6.7139 23.7206 6.80175 23.6679 6.8896C23.1935 7.7417 22.9212 8.7168 22.9212 9.76217H25.9168V9.75338Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M30.3552 22.1547C29.7227 27.5484 25.146 31.7299 19.5853 31.7299C14.0246 31.7299 9.3073 27.4255 8.78901 21.9175L12.013 21.3465L11.2663 17.1475L0.00439453 19.1416L0.751087 23.3406L4.57239 22.6642C5.45086 30.175 11.8373 35.9992 19.5853 35.9992C27.3333 35.9992 34.0009 29.9115 34.6509 22.1547C34.6861 21.733 34.7036 21.3114 34.7036 20.8809C34.7036 20.4505 34.6861 20.0464 34.6509 19.6423H30.3552C30.3992 20.0464 30.4255 20.4593 30.4255 20.8809C30.4255 21.3026 30.3992 21.733 30.3552 22.1547Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M115.199 20.3698C115.199 24.4371 117.958 26.8968 121.955 26.8968C123.966 26.8968 126.048 26.0271 127.34 24.1384L124.335 23.0491C123.861 23.5937 123.018 23.9715 121.849 23.9715C120.461 23.9715 119.117 23.1282 118.994 21.3888H128.104C128.35 17.0404 125.846 13.9395 121.797 13.9395C118.195 13.9395 115.19 16.4431 115.19 20.3698H115.199ZM121.902 16.7417C123.369 16.7417 124.555 17.5587 124.608 19.1487H118.994C119.214 17.4621 120.488 16.7417 121.902 16.7417Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M105.518 17.1664H107.556V23.1224C107.556 26.0301 108.602 26.6011 111.624 26.6011H114.004V23.5265H112.915C111.703 23.5265 111.325 23.3069 111.325 22.1385V17.1752H114.004V14.2939H111.325V11.2192H107.548V14.2939H105.51V17.1752L105.518 17.1664Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M102.208 12.2458C103.447 12.2458 104.44 11.2268 104.44 10.0145C104.44 8.80222 103.447 7.7832 102.208 7.7832C100.97 7.7832 99.9771 8.77586 99.9771 10.0145C99.9771 11.2531 100.97 12.2458 102.208 12.2458Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M97.9688 17.1636H100.306V26.5983H104.1V14.2822H97.9688V17.1636Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M88.2859 26.5983H92.5288L96.9475 14.2822H93.0559L90.4205 22.6715L87.7939 14.2822H83.8936L88.2859 26.5983Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M77.1936 26.8968C80.4966 26.8968 83.2286 25.4824 83.2286 22.5747C83.2286 20.5103 81.8407 19.6494 78.8627 19.096L76.5787 18.6744C75.4894 18.4723 75.0677 18.1824 75.0677 17.6817C75.0677 17.0404 75.7617 16.689 77.1058 16.689C78.2214 16.689 79.1701 16.935 79.4161 17.831L82.5961 16.6627C81.6738 14.7301 79.4952 13.9307 77.1321 13.9307C74.0751 13.9307 71.4748 15.2484 71.4748 17.831C71.4748 19.8954 73.1175 21.1077 75.4982 21.503L77.7031 21.872C79.1174 22.1179 79.6182 22.4693 79.6182 22.9876C79.6182 23.7606 78.5025 24.1296 77.2814 24.1296C75.8671 24.1296 74.7515 23.6289 74.3737 22.5923L71.1674 23.7607C72.1073 25.825 74.321 26.888 77.2024 26.888L77.1936 26.8968Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M57.18 20.3698C57.18 24.4371 59.9384 26.8968 63.9354 26.8968C65.947 26.8968 68.029 26.0271 69.3203 24.1384L66.316 23.0491C65.8416 23.5937 64.9983 23.9715 63.8299 23.9715C62.442 23.9715 61.0979 23.1282 60.9749 21.3888H70.0846C70.3306 17.0404 67.8269 13.9395 63.7772 13.9395C60.1755 13.9395 57.1712 16.4431 57.1712 20.3698H57.18ZM63.8826 16.7417C65.3497 16.7417 66.5356 17.5587 66.5883 19.1487H60.9749C61.1946 17.4621 62.4683 16.7417 63.8826 16.7417Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M43.9792 14.2822L48.6965 26.5192L48.5735 26.8442C48.2046 27.7842 47.9323 27.8632 46.3422 27.8632H45.5956V30.5953H46.8078C50.0142 30.5953 50.9278 29.7256 52.1225 26.5983L56.8399 14.2822H52.7902L50.4359 21.9776L48.0289 14.2822H43.9792Z" fill="black" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M3.12968 9.63483C3.36687 9.67875 3.32295 9.83688 3.3493 10.2234C3.3493 10.2234 3.36687 10.4518 3.5777 10.364L3.72704 10.2849L6.16038 15.2658H6.22187L6.27458 15.2394L3.83245 10.2322L3.77975 10.2585L3.98179 10.1619C4.17505 10.0477 4.00815 9.89837 4.00815 9.89837C3.72704 9.64361 3.56891 9.58212 3.67433 9.36251C3.86759 8.98477 3.99936 8.60703 4.06964 8.24686C4.45616 6.36695 3.39322 4.7418 2.11067 4.52218C1.7505 4.46069 1.38154 4.50461 1.01259 4.68909C-0.91124 5.64661 -0.0327782 9.06383 3.12968 9.63483Z" fill="#3ABEEA" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M9.31463 9.63483C9.07745 9.67875 9.12137 9.83688 9.09502 10.2234C9.09502 10.2234 9.07745 10.4518 8.86662 10.364L8.71728 10.2849L6.28394 15.2658H6.22245L6.16974 15.2394L8.61186 10.2322L8.66457 10.2585L8.46252 10.1619C8.26926 10.0477 8.43617 9.89837 8.43617 9.89837C8.71728 9.64361 8.8754 9.58212 8.76998 9.36251C8.57672 8.98477 8.44495 8.60703 8.37468 8.24686C7.98815 6.36695 9.05109 4.7418 10.3336 4.52218C10.6938 4.46069 11.0628 4.50461 11.4317 4.68909C13.3556 5.64661 12.4771 9.06383 9.31463 9.63483Z" fill="#27B076" />
                      <path opacity="0.21" fill-rule="evenodd" clip-rule="evenodd" d="M8.10115 3.07294C8.21535 2.9763 8.46131 3.08172 8.66336 3.31012C8.85662 3.53852 8.9269 3.80206 8.8127 3.89869C8.6985 3.99532 8.45253 3.8899 8.25048 3.6615C8.05722 3.4331 7.98695 3.16957 8.10115 3.07294Z" fill="white" />
                      <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M8.51964 4.21417C8.57235 4.17025 8.68655 4.21417 8.7744 4.31959C8.86224 4.425 8.89738 4.54799 8.84468 4.59191C8.79197 4.63584 8.67777 4.59191 8.58992 4.4865C8.50208 4.38108 8.47572 4.2581 8.51964 4.21417Z" fill="white" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M6.02645 6.30565H5.63993C5.41153 6.28808 5.50816 6.06846 5.50816 6.06846C5.6575 5.71708 5.7717 5.58531 5.58722 5.43597C5.2534 5.17243 4.9723 4.88254 4.7439 4.57508C3.58433 3.01142 3.87422 1.07002 4.95473 0.323323C5.57843 -0.107123 6.49203 -0.107123 7.11574 0.323323C8.19625 1.07002 8.47736 3.01142 7.32657 4.57508C7.09817 4.88254 6.82585 5.17243 6.48325 5.43597C6.28999 5.58531 6.41297 5.70829 6.56231 6.06846C6.56231 6.06846 6.65894 6.27929 6.43054 6.30565H6.04402H6.02645Z" fill="#ECB015" />
                      <path d="M6.32789 10.9731H6.22247V15.2688H6.32789V10.9731Z" fill="#ECB015" />
                      <path fill-rule="evenodd" clip-rule="evenodd" d="M4.28744 9.54549C4.88479 10.0462 5.47336 10.4854 5.92138 10.8105C6.17613 11.0037 6.02679 11.1706 5.84232 11.645C5.84232 11.645 5.72812 11.9261 6.02679 11.9525H6.63293C6.94039 11.9349 6.81741 11.645 6.81741 11.645C6.62415 11.1706 6.47481 11.0037 6.73835 10.8105C7.18636 10.4854 7.77493 10.0462 8.37228 9.54549C9.26832 8.78123 10.2522 7.85884 10.7705 6.7959C12.176 3.9409 9.48793 -0.012174 6.33425 2.46509C3.18058 -0.012174 0.483699 3.9409 1.88924 6.7959C2.40753 7.85006 3.40019 8.78123 4.29622 9.54549H4.28744Z" fill="#EA555C" />
                  </svg> --}}

<svg width="140" height="65" viewBox="0 0 140 65" fill="none" xmlns="http://www.w3.org/2000/svg">
    <rect width="128.118" height="36" fill="white"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M115.196 20.3691C115.196 24.4363 117.955 26.896 121.952 26.896C123.963 26.896 126.045 26.0264 127.337 24.1377L124.332 23.0484C123.858 23.593 123.015 23.9708 121.846 23.9708C120.458 23.9708 119.114 23.1274 118.991 21.3881H128.101C128.347 17.0397 125.843 13.9387 121.794 13.9387C118.192 13.9387 115.188 16.4423 115.188 20.3691H115.196ZM121.899 16.741C123.366 16.741 124.552 17.558 124.605 19.148H118.991C119.211 17.4614 120.485 16.741 121.899 16.741Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M105.517 17.1647H107.555V23.1207C107.555 26.0284 108.6 26.5994 111.622 26.5994H114.003V23.5248H112.913C111.701 23.5248 111.323 23.3052 111.323 22.1368V17.1735H114.003V14.2921H111.323V11.2175H107.546V14.2921H105.508V17.1735L105.517 17.1647Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M102.212 12.2452C103.45 12.2452 104.443 11.2262 104.443 10.0139C104.443 8.80161 103.45 7.78259 102.212 7.78259C100.973 7.78259 99.9805 8.77525 99.9805 10.0139C99.9805 11.2525 100.973 12.2452 102.212 12.2452Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M97.9688 17.1632H100.305V26.5979H104.1V14.2819H97.9688V17.1632Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M88.2907 26.5979H92.5337L96.9524 14.2819H93.0608L90.4254 22.6712L87.7988 14.2819H83.8984L88.2907 26.5979Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M77.1942 26.8979C80.4972 26.8979 83.2292 25.4836 83.2292 22.5759C83.2292 20.5115 81.8413 19.6506 78.8633 19.0972L76.5793 18.6755C75.49 18.4735 75.0683 18.1836 75.0683 17.6829C75.0683 17.0416 75.7623 16.6902 77.1064 16.6902C78.222 16.6902 79.1708 16.9362 79.4167 17.8322L82.5967 16.6638C81.6744 14.7312 79.4958 13.9318 77.1327 13.9318C74.0757 13.9318 71.4754 15.2495 71.4754 17.8322C71.4754 19.8966 73.1182 21.1089 75.4988 21.5042L77.7037 21.8731C79.118 22.1191 79.6188 22.4705 79.6188 22.9888C79.6188 23.7618 78.5031 24.1308 77.2821 24.1308C75.8677 24.1308 74.7521 23.63 74.3744 22.5935L71.168 23.7618C72.1079 25.8262 74.3216 26.8891 77.203 26.8891L77.1942 26.8979Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M57.1807 20.3691C57.1807 24.4363 59.939 26.896 63.936 26.896C65.9477 26.896 68.0297 26.0264 69.321 24.1377L66.3167 23.0484C65.8423 23.593 64.999 23.9708 63.8306 23.9708C62.4426 23.9708 61.0986 23.1274 60.9756 21.3881H70.0853C70.3312 17.0397 67.8276 13.9387 63.7779 13.9387C60.1762 13.9387 57.1719 16.4423 57.1719 20.3691H57.1807ZM63.8833 16.741C65.3504 16.741 66.5363 17.558 66.589 19.148H60.9756C61.1952 17.4614 62.469 16.741 63.8833 16.741Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M43.9766 14.2819L48.6939 26.5188L48.5709 26.8439C48.202 27.7838 47.9296 27.8629 46.3396 27.8629H45.5929V30.5949H46.8052C50.0116 30.5949 50.9252 29.7252 52.1199 26.5979L56.8372 14.2819H52.7875L50.4333 21.9772L48.0263 14.2819H43.9766Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M8.86739 6.81743H8.48087C8.25247 6.79986 8.3491 6.58024 8.3491 6.58024C8.49844 6.22886 8.61264 6.09709 8.42816 5.94775C8.09435 5.68421 7.81324 5.39432 7.58484 5.08686C6.42527 3.5232 6.71516 1.58179 7.79567 0.835103C8.41938 0.404657 9.33298 0.404657 9.95668 0.835103C11.0372 1.58179 11.3183 3.5232 10.1675 5.08686C9.93911 5.39432 9.66679 5.68421 9.32419 5.94775C9.13093 6.09709 9.25391 6.22007 9.40325 6.58024C9.40325 6.58024 9.49988 6.79107 9.27148 6.81743H8.88496H8.86739Z" fill="#ECB015"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M25.9174 9.75155C25.9174 9.04878 26.1634 8.4075 26.5851 7.91557C27.1121 7.28307 27.9028 6.88777 28.79 6.88777C30.3712 6.88777 31.6626 8.17032 31.6626 9.76034H34.6581C34.6581 6.51881 32.0315 3.89221 28.79 3.89221C26.6993 3.89221 24.8721 4.9815 23.8267 6.62423C23.774 6.71207 23.7213 6.79992 23.6686 6.88777C23.1942 7.73987 22.9219 8.71497 22.9219 9.76034H25.9174V9.75155Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M30.3548 22.1554C29.7223 27.5492 25.1455 31.7307 19.5848 31.7307C14.0242 31.7307 9.30681 27.4262 8.78852 21.9182L12.0125 21.3472L11.2658 17.1482L0.00390625 19.1423L0.750599 23.3413L4.57191 22.6649C5.45037 30.1758 11.8368 36 19.5848 36C27.3328 36 34.0004 29.9122 34.6504 22.1554C34.6856 21.7338 34.7031 21.3121 34.7031 20.8817C34.7031 20.4512 34.6856 20.0471 34.6504 19.643H30.3548C30.3987 20.0471 30.425 20.46 30.425 20.8817C30.425 21.3033 30.3987 21.7338 30.3548 22.1554Z" fill="black"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M3.12968 9.63361C3.36687 9.67753 3.32294 9.83566 3.3493 10.2222C3.3493 10.2222 3.36687 10.4506 3.5777 10.3627L3.72704 10.2837L6.16038 15.2645H6.22187L6.27458 15.2382L3.83245 10.231L3.77975 10.2573L3.98179 10.1607C4.17505 10.0465 4.00814 9.89715 4.00814 9.89715C3.72704 9.64239 3.56891 9.5809 3.67433 9.36129C3.86759 8.98355 3.99936 8.60581 4.06964 8.24564C4.45616 6.36573 3.39322 4.74058 2.11067 4.52096C1.7505 4.45947 1.38154 4.50339 1.01259 4.68787C-0.91124 5.64539 -0.0327782 9.06261 3.12968 9.63361Z" fill="#3ABEEA"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.31286 9.63361C9.07568 9.67753 9.1196 9.83566 9.09325 10.2222C9.09325 10.2222 9.07568 10.4506 8.86485 10.3627L8.71551 10.2837L6.28217 15.2645H6.22068L6.16797 15.2382L8.61009 10.231L8.6628 10.2573L8.46075 10.1607C8.26749 10.0465 8.4344 9.89715 8.4344 9.89715C8.71551 9.64239 8.87363 9.5809 8.76821 9.36129C8.57495 8.98355 8.44318 8.60581 8.37291 8.24564C7.98638 6.36573 9.04932 4.74058 10.3319 4.52096C10.692 4.45947 11.061 4.50339 11.43 4.68787C13.3538 5.64539 12.4753 9.06261 9.31286 9.63361Z" fill="#27B076"/>
    <path opacity="0.21" fill-rule="evenodd" clip-rule="evenodd" d="M8.10188 3.07171C8.21608 2.97508 8.46205 3.0805 8.66409 3.3089C8.85735 3.5373 8.92763 3.80084 8.81343 3.89747C8.69923 3.9941 8.45326 3.88868 8.25122 3.66028C8.05795 3.43188 7.98768 3.16835 8.10188 3.07171Z" fill="white"/>
    <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M8.51696 4.21295C8.56967 4.16903 8.68387 4.21295 8.77171 4.31837C8.85956 4.42378 8.8947 4.54677 8.84199 4.59069C8.78928 4.63461 8.67508 4.59069 8.58724 4.48528C8.49939 4.37986 8.47304 4.25688 8.51696 4.21295Z" fill="white"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M6.02755 6.30516H5.64103C5.41263 6.28759 5.50926 6.06797 5.50926 6.06797C5.65859 5.71659 5.77279 5.58482 5.58832 5.43548C5.2545 5.17194 4.97339 4.88205 4.74499 4.57459C3.58543 3.01093 3.87532 1.06953 4.95583 0.322835C5.57953 -0.107612 6.49313 -0.107612 7.11684 0.322835C8.19735 1.06953 8.47846 3.01093 7.32767 4.57459C7.09927 4.88205 6.82695 5.17194 6.48435 5.43548C6.29109 5.58482 6.41407 5.7078 6.56341 6.06797C6.56341 6.06797 6.66004 6.2788 6.43164 6.30516H6.04512H6.02755Z" fill="#ECB015"/>
    <path d="M6.32807 10.9709H6.22266V15.2666H6.32807V10.9709Z" fill="#ECB015"/>
    <path fill-rule="evenodd" clip-rule="evenodd" d="M4.28799 9.54683C4.88534 10.0476 5.47391 10.4868 5.92193 10.8118C6.17668 11.0051 6.02734 11.172 5.84286 11.6464C5.84286 11.6464 5.72866 11.9275 6.02734 11.9538H6.63348C6.94094 11.9362 6.81796 11.6464 6.81796 11.6464C6.6247 11.172 6.47536 11.0051 6.7389 10.8118C7.18691 10.4868 7.77548 10.0476 8.37283 9.54683C9.26886 8.78257 10.2527 7.86018 10.771 6.79725C12.1766 3.94225 9.48848 -0.0108312 6.3348 2.46643C3.18113 -0.0108312 0.484249 3.94225 1.88979 6.79725C2.40808 7.8514 3.40074 8.78257 4.29677 9.54683H4.28799Z" fill="#EA555C"/>
    <g filter="url(#filter0_d_7906_43769)">
    <path d="M88 39.5C88 34.8056 91.8056 31 96.5 31H128V38C128 43.5228 123.523 48 118 48H96.5C91.8056 48 88 44.1944 88 39.5Z" fill="#FF4F84" shape-rendering="crispEdges"/>
    <path d="M95.5859 43V35.9541H98.6523C99.9805 35.9541 100.815 36.6377 100.815 37.7217V37.7314C100.815 38.5029 100.229 39.1621 99.4531 39.2646V39.2939C100.435 39.3672 101.143 40.0605 101.143 40.9834V40.9932C101.143 42.2285 100.21 43 98.7061 43H95.5859ZM98.2715 37.0479H97.0605V38.8984H98.0908C98.9209 38.8984 99.3652 38.5518 99.3652 37.9414V37.9316C99.3652 37.3701 98.96 37.0479 98.2715 37.0479ZM98.2666 39.8994H97.0605V41.9014H98.3301C99.1748 41.9014 99.6387 41.5547 99.6387 40.9004V40.8906C99.6387 40.2461 99.1699 39.8994 98.2666 39.8994ZM102.482 43V35.9541H107.15V37.1699H103.957V38.8594H106.97V40.0117H103.957V41.7842H107.15V43H102.482ZM110.399 43V37.1699H108.363V35.9541H113.915V37.1699H111.874V43H110.399ZM113.824 43L116.285 35.9541H118.019L120.475 43H118.927L118.395 41.291H115.904L115.372 43H113.824ZM117.135 37.3213L116.246 40.1875H118.053L117.164 37.3213H117.135Z" fill="white"/>
    </g>
    <defs>
    <filter id="filter0_d_7906_43769" x="76" y="24" width="64" height="41" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
    <feFlood flood-opacity="0" result="BackgroundImageFix"/>
    <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
    <feOffset dy="5"/>
    <feGaussianBlur stdDeviation="6"/>
    <feComposite in2="hardAlpha" operator="out"/>
    <feColorMatrix type="matrix" values="0 0 0 0 0.809701 0 0 0 0 0.14034 0 0 0 0 0.341909 0 0 0 0.24 0"/>
    <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_7906_43769"/>
    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_7906_43769" result="shape"/>
    </filter>
    </defs>
    </svg>
    
              </a>
              <ul class="nav">
                  <li class="nav-item">
                      <a class="nav-link" href="{{route('about')}}">About us</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('faq')}}">FAQ</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="https://support.yesvite.com">Help Center</a>
                  </li>
                  <li class="nav-item">
                      <a class="nav-link" href="{{ route('contact') }}">Contact Us</a>
                  </li>
              </ul>
          </div>
          <div class="footer-bottom d-flex justify-content-between flex-wrap">
              <ul class="footer-social-link">
                {{-- @if(isset($getSocialLink->x_link) && $data->x_link != null) --}}
                  <li>
                      {{-- <a href="{{ route('event.event_detail', 3775 ) }}"> --}}
                        <a href="#">

                          <span>
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M18.901 1.15381H22.581L14.541 10.3438L24 22.8468H16.594L10.794 15.2628L4.156 22.8468H0.474L9.074 13.0168L0 1.15481H7.594L12.837 8.08681L18.901 1.15381ZM17.61 20.6448H19.649L6.486 3.24081H4.298L17.61 20.6448Z" fill="black" />
                              </svg>
                          </span>
                      </a>
                  </li>
                  {{-- @endif --}}

                  {{-- @if(isset($getSocialLink->x_link) && $data->x_link != null) --}}
                  <li>
                    {{-- <a href="{{ route('event.event_guest', 3775 )}}"> --}}

                      <a href="#">
                          <span>
                              <svg width="14" height="24" viewBox="0 0 14 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M9.34922 13.8015H12.3492L13.5492 9.00146H9.34922V6.60147C9.34922 5.36547 9.34922 4.20147 11.7492 4.20147H13.5492V0.169465C13.158 0.117865 11.6808 0.00146484 10.1208 0.00146484C6.86282 0.00146484 4.54922 1.98986 4.54922 5.64146V9.00146H0.949219V13.8015H4.54922V24.0015H9.34922V13.8015Z" fill="#1877F2" />
                              </svg>
                          </span>
                      </a>
                  </li>
                  {{-- @endif --}}
                  <li>
                      <a href="#">
                          <span>
                              <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <g clip-path="url(#clip0_3391_343)">
                                      <path d="M2.00026 1.63427C0.11426 3.59327 0.50026 5.67427 0.50026 11.9963C0.50026 17.2463 -0.41574 22.5093 4.37826 23.7483C5.87526 24.1333 19.1393 24.1333 20.6343 23.7463C22.6303 23.2313 24.2543 21.6123 24.4763 18.7893C24.5073 18.3953 24.5073 5.60427 24.4753 5.20227C24.2393 2.19527 22.3883 0.462267 19.9493 0.111267C19.3903 0.0302671 19.2783 0.0062671 16.4103 0.0012671C6.23726 0.0062671 4.00726 -0.446733 2.00026 1.63427Z" fill="url(#paint0_linear_3391_343)" />
                                      <path d="M12.4984 3.14039C8.86736 3.14039 5.41936 2.81739 4.10236 6.19739C3.55836 7.59339 3.63736 9.40639 3.63736 12.0024C3.63736 14.2804 3.56436 16.4214 4.10236 17.8064C5.41636 21.1884 8.89236 20.8644 12.4964 20.8644C15.9734 20.8644 19.5584 21.2264 20.8914 17.8064C21.4364 16.3964 21.3564 14.6104 21.3564 12.0024C21.3564 8.54039 21.5474 6.30539 19.8684 4.62739C18.1684 2.92739 15.8694 3.14039 12.4944 3.14039H12.4984ZM11.7044 4.73739C19.2784 4.72539 20.2424 3.88339 19.7104 15.5804C19.5214 19.7174 16.3714 19.2634 12.4994 19.2634C5.43936 19.2634 5.23636 19.0614 5.23636 11.9984C5.23636 4.85339 5.79636 4.74139 11.7044 4.73539V4.73739ZM17.2284 6.20839C16.6414 6.20839 16.1654 6.68439 16.1654 7.27139C16.1654 7.85839 16.6414 8.33439 17.2284 8.33439C17.8154 8.33439 18.2914 7.85839 18.2914 7.27139C18.2914 6.68439 17.8154 6.20839 17.2284 6.20839ZM12.4984 7.45139C9.98536 7.45139 7.94836 9.48939 7.94836 12.0024C7.94836 14.5154 9.98536 16.5524 12.4984 16.5524C15.0114 16.5524 17.0474 14.5154 17.0474 12.0024C17.0474 9.48939 15.0114 7.45139 12.4984 7.45139ZM12.4984 9.04839C16.4034 9.04839 16.4084 14.9564 12.4984 14.9564C8.59436 14.9564 8.58836 9.04839 12.4984 9.04839Z" fill="white" />
                                  </g>
                                  <defs>
                                      <linearGradient id="paint0_linear_3391_343" x1="2.04628" y1="22.4684" x2="24.3517" y2="3.16325" gradientUnits="userSpaceOnUse">
                                          <stop stop-color="#FFDD55" />
                                          <stop offset="0.5" stop-color="#FF543E" />
                                          <stop offset="1" stop-color="#C837AB" />
                                      </linearGradient>
                                      <clipPath id="clip0_3391_343">
                                          <rect width="24" height="24" fill="white" transform="translate(0.5 0.00146484)" />
                                      </clipPath>
                                  </defs>
                              </svg>
                          </span>
                      </a>
                  </li>
                  <li>
                      <a href="#">
                          <span>
                              <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path fill-rule="evenodd" clip-rule="evenodd" d="M8.9148 8.36427H13.3716V10.5843C14.0136 9.30747 15.66 8.16027 18.1332 8.16027C22.8744 8.16027 24 10.7019 24 15.3651V24.0015H19.2V16.4271C19.2 13.7715 18.558 12.2739 16.9236 12.2739C14.6568 12.2739 13.7148 13.8879 13.7148 16.4259V24.0015H8.9148V8.36427ZM0.684 23.7975H5.484V8.16027H0.684V23.7975ZM6.1716 3.06147C6.17178 3.46379 6.09199 3.86215 5.93686 4.23337C5.78174 4.60459 5.55438 4.94128 5.268 5.22387C4.68768 5.80061 3.90217 6.12345 3.084 6.12147C2.26727 6.12092 1.48357 5.7989 0.9024 5.22507C0.617054 4.94152 0.390463 4.60445 0.235612 4.23318C0.0807618 3.86191 0.000694958 3.46373 0 3.06147C0 2.24907 0.324 1.47147 0.9036 0.897865C1.48426 0.323255 2.26829 0.00110579 3.0852 0.00146514C3.9036 0.00146514 4.6884 0.324265 5.268 0.897865C5.8464 1.47147 6.1716 2.24907 6.1716 3.06147Z" fill="#0B66C2" />
                              </svg>
                          </span>
                      </a>
                  </li>
              </ul>
              <div class="app-store d-flex gap-2">
                  <a href="#" class="google-app">
                      <img src="{{asset('assets/front/image/google-app.png')}}" alt="google-app">
                  </a>
                  <a href="#" class="mobile-app">
                      <img src="{{asset('assets/front/image/mobile-app.png')}}" alt="mobile-app">
                  </a>
              </div>
          </div>
          <div class="footer-copyright">
              <p>Copyright © {{date('Y')}} Yesvite. All Rights Reserved.</p>
              <ul class="ps-0">
                  <li>
                      <a href="{{route('term_and_condition')}}">Terms of Service</a>
                  </li>
                  <li>
                      <a href="{{route('privacy_policy')}}">Privacy Policy</a>
                  </li>
              </ul>
          </div>
      </div>
  </footer>