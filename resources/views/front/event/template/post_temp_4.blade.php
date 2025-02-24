<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/template/')}}/css/post_temp_4.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <img src="{{asset('assets/template/')}}/images/post_temp_4/post_temp_4_bg.png" alt="" class="post_temp_4_bg">
            <div class="birthday-card-head">
                {{-- <h2>VELENTINE’S COCKTAIL PARTY</h2> --}}
                <h2 class="event_name"></h2>

            </div>
            <div class="birthday-card-middle">
                <img src="{{asset('assets/template/')}}/images/post_temp_4/post_temp_4_glass_img.svg" alt="" class="post_temp_4_glass_img">
                <div class="birthday-card-middle-content">
                    <h4>Organizers</h4>
                    <p>Crafted Celebrations</p>


                    <div class="birthday-card_date_time">
                        {{-- <h5>DEC, 2024 <span>SUNDAY</span></h5>
                        <h5>9:00 PM to 1:00 AM</h5> --}}

                        <h5 class="event_date"></h5>
                        <h5 class="event_time"></h5>

                    </div>

                </div>
            </div>
            <div class="birthday-card-address">
                {{-- <p>Suite 189 148 Medhurst Wall, South Orvilletown, SC 38348</p> --}}
                <p class="event_address"></p>

            </div>
        </div>
    </section>
    
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    </body>
</html>