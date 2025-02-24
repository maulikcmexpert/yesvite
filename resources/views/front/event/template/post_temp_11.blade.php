<!DOCTYPE html>
<html lang="en">
    <head>
        <title></title>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{asset('assets/template/')}}/css/post_temp_11.css" rel="stylesheet">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    </head>
    <body>

    <section class="main-seciton">
        <div class="birthday-card-main">
            <img src="{{asset('assets/template/')}}/images/post_temp_11/post_temp_11_bg_img.png" alt="" class="post_temp_11_bg_img">
            <div class="birthday-card-content">
                <h2 class="event_name">baby shower</h2>
                <h5>please join us in honoring</h5>
                <h3 class="titlename">Kerry Carlson</h3>
                <p class="e_details">saturday jaunary 19,2016, 11:30am at the turner home w.main street</p>
            </div>
        </div>
    </section>
    
    {{-- <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script> --}}
    <script src="{{asset('assets/template/')}}/jquery.arctext.js"></script>
    {{-- <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script> --}}
    <script>
        $(".titlename").arctext({
            radius: 600,
            dir: 1
        });
    </script>
    </body>
</html>