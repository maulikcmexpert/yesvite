<!DOCTYPE html>
<html lang="en">

<head>
    <title></title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('assets/template/css/post_temp_1.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
</head>

<body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <svg class="blue-bg" preserveAspectRatio="none" viewBox="0 0 460 409" fill="none"
                xmlns="http://www.w3.org/2000/svg">
                <mask id="mask0_10_1757" style="mask-type:luminance" maskUnits="userSpaceOnUse" x="-1" y="0"
                    width="461" height="409">
                    <path d="M-0.0149841 409V0.579758L229.99 55.0756L460 0.579758V409H-0.0149841Z" fill="white" />
                </mask>
                <g mask="url(#mask0_10_1757)">
                    <path d="M460 409H-0.0149841V0.987267H460V409Z" fill="#3B95B3" />
                </g>
            </svg>
            <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_top_head_img.svg') }}" alt=""
                class="post_temp_1_top_head_img">
            <div class="main-center-img">
                <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_ballon_left.svg') }}" alt=""
                    class="post_temp_1_ballon_left">
                <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_ballon_right.svg') }}" alt=""
                    class="post_temp_1_ballon_right">
                <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_center_img.png') }}" alt="">
            </div>
            <div class="main-center-name-wrp">
                {{-- <h3 class="titlename">Aaron Loeb</h3> --}}
                <h3 class="titlename"></h3>
                <img src="{{ asset('assets/template/images/post_temp_1/post_temp_1_name_img.svg') }}" alt=""
                    class="post_temp_1_name_img">
            </div>
            <div class="birthday-card-main-content">
                {{-- <h2 class="event_name">Birthday party</h2> --}}
                <h2 class="event_name"></h2>
                <div class="birthday-card-content-inner">
                    <div class="birthday-card-content-inner-date">
                        {{-- <h4>december</h4>
                        <h6>05</h6> --}}

                        <h4 class="event_date"></h4>

                    </div>
                    <div class="birthday-card-content-inner-info">
                        {{-- <p class="event_address">123 Anywhere st., any city, st 12345</p> --}}
                        <p class="event_address"></p>
                        {{-- <h3>04:00 <span>P.M.</span></h3> --}}
                        <h3 class="event_time"></h3>

                    </div>
                </div>
            </div>
            <p class="footer-text">r.s.v.p. to: +123-456-7890</p>
        </div>
    </section>

    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="{{ asset('assets/template/jquery.arctext.js') }}"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}

    <script>
        $(".titlename").arctext({
            radius: 400,
            dir: -1
        });
    </script>
</body>

</html>
