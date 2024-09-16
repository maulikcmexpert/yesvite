<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/template/')}}/css/post_temp_6.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <img src="{{asset('assets/template/')}}/images/post_temp_6/post_temp_6_foot_img.svg" alt="" class="post_temp_6_foot_img">
            <img src="{{asset('assets/template/')}}/images/post_temp_6/post_temp_6_head_img.svg" alt="" class="post_temp_6_head_img">

            <div class="birthday-card-main-content">
                <h2 class="titlename">Birthday Party</h2>
                <div class="birthday-card-main-content-name">
                    <div class="birthday-card-center-img">
                        <img src="{{asset('assets/template/')}}/images/post_temp_6/post_temp_6_center_circle.png" alt="" class="post_temp_6_center_circle">
                        <img src="{{asset('assets/template/')}}/images/post_temp_6/post_temp_6_center_img.png" alt="" class="post_temp_6_center_img">
                    </div>
                    <h4>
                        <img src="{{asset('assets/template/')}}/images/post_temp_6/post_temp_6_name_img.svg" alt="" class="post_temp_6_name_img">
                        Avery Davis
                    </h4>

                </div>
            </div>

            <div class="birthday-card-main-content-detail">
                <h5>Come join us for an evening of
                    entertainment, food, and festivities.
                </h5>
            </div>
            <div class="birthday-card-address">
                <h6 class="e_date_time">Sunday, 23 June 2023 at 8 PM</h6>
                <p class="event_address">123 Anywhere St, Any City, ST 12345</p>
            </div>
        </div>
    </section>
    
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="{{asset('assets/template/')}}/jquery.arctext.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}

    <script>
        $(".titlename").arctext({
            radius: 100,
            dir: 1
        });
    </script>
    </body>
</html>