<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/template/')}}/css/post_temp_3.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <img src="{{asset('assets/template/')}}/images/post_temp_3/post_temp_3_bg_img.png" alt="" class="post_temp_3_bg_img">
            <div class="birthday-card-head">
                <h4>
                    <span><img src="{{asset('assets/template/')}}/images/post_temp_3/post_temp_3_title_bg.svg" alt=""></span>
                    Organizers
                </h4>
                <p>Cloud Nine events</p>
            </div>
            <div class="birthday-card-middle">
                <img src="{{asset('assets/template/')}}/images/post_temp_3/post_temp_3_circle_img.svg" alt="" class="post_temp_3_circle_img">
                {{-- <h3>MUSIC FESTIVAL</h3> --}}
                <h3 class="event_name"></h3>
   

            </div>
            <div class="birthday-card-date-time">
                <div class="birthday-card-date-time-inner">
                    <img src="{{asset('assets/template/')}}/images/post_temp_3/post_temp_3_squar_img.svg" alt="" class="post_temp_3_squar_img">
                    {{-- <h5>DEC, 2024 SUNDAY</h5> --}}
                    <h5 class="e_date"></h5>

                </div>
                <div class="birthday-card-date-time-inner">
                    <img src="{{asset('assets/template/')}}/images/post_temp_3/post_temp_3_squar_img.svg" alt="" class="post_temp_3_squar_img">
                    {{-- <h5>9:00 PM to 1:00 AM</h5> --}}
                    <h5 class="event_time"></h5>

                    
                </div>
            </div>
            <div class="birthday-card-address">
                <img src="{{asset('assets/template/')}}/images/post_temp_3/post_temp_3_footer_img.svg" alt="" class="post_temp_3_footer_img">
                {{-- <p>Suite 189 148 Medhurst Wall,
                    South Orvilletown, SC 38348</p> --}}
                    <p class="event_address"></p>

            </div>
        </div>
    </section>
    
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    </body>
</html>