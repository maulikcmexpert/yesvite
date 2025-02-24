<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/template/')}}/css/post_temp_9.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <img src="{{asset('assets/template/')}}/images/post_temp_9/post_temp_9_bg_img.png" alt="" class="post_temp_9_bg_img">
            <div class="birthday-card-content">
                <h5>join us for</h5>
                <h2 class="titlename">bob's birthday</h2>
                <div class="text-center">
                    <img src="{{asset('assets/template/')}}/images/post_temp_9/post_temp_9_center_img.png" alt="">
                </div>
                <h3>bob's boat house</h3>
                <h4 class="e_date">12/12/2024</h4>
                <h6 class="event_time">5:00 pm</h6>
            </div>
        </div>
    </section>
    
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="{{asset('assets/template/')}}/jquery.arctext.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    <script>
        $(".titlename").arctext({
            radius: 200,
            dir: 1
        });
    </script>
    </body>
</html>