<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/template/')}}/css/post_temp_2.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <img src="{{asset('assets/template/')}}/images/post_temp_2/post_temp_2_bg_img.svg" alt="" class="post_temp_2_bg_img">
            <div class="birthday-card-head">
                <img src="{{asset('assets/template/')}}/images/post_temp_2/post_temp_2_head_star.svg" alt="" class="post_temp_2_head_star">
                {{-- <h2>New Year Party</h2> --}}
                <h2 class="event_name"></h2>

            </div>
            <div class="birthday-card_middle">
                <img src="{{asset('assets/template/')}}/images/post_temp_2/post_temp_2_glass_img.svg" alt="" class="post_temp_2_glass_img">
                <h4>
                    <span>
                        <svg preserveAspectRatio="none" viewBox="0 0 113 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M113 0H110.816H2.18357H0L5.24058 10L0 20H2.18357H110.816H113L107.759 10L113 0Z" fill="#FCEA89"/>
                        </svg>
                    </span>
                    Organizers
                </h4>
                <p>Cloud Nine events</p>

            </div>
            <div class="birthday-card_date_time">
                <h5 class="e_date">DEC, 2024 <span>SUNDAY</span></h5>
                {{-- <h5>9:00 PM to 1:00 AM</h5> --}}
                {{-- <h5 class="event_date"></h5> --}}
                <h5 class="event_time"></h5>

            </div>
            <div class="birthday-card-address">
                <img src="{{asset('assets/template/')}}/images/post_temp_2/post_temp_2_footer_star.svg" alt="" class="post_temp_2_footer_star">
                {{-- <p>Suite 189 148 Medhurst Wall, South Orvilletown, SC 38348</p> --}}
                <p class="event_address"></p>
            </div>
        </div>
    </section>
    </body>
</html>