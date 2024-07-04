<style>
    /* Style for the event cards */
    .card {
        border: 1px solid #ccc;
        border-radius: 8px;
        margin-bottom: 20px;
    }

    .card-header {
        background-color: #f0f0f0;
        padding: 15px;
    }

    .card-header .media {
        align-items: center;
    }

    .card-header h3 {
        margin-bottom: 5px;
    }

    .card-header p {
        color: #888;
        font-size: 14px;
    }

    .card-header img {
        width: 50px;
        /* Adjust the size of the images */
        height: 50px;
        border-radius: 50%;
    }

    .card-body {
        padding: 15px;
    }

    .card-body h5 {
        font-size: 16px;
        margin-bottom: 5px;
    }

    .card-body p {
        color: #888;
        font-size: 14px;
    }

    .card-sponsor {
        padding: 15px;
        background-color: #f9f9f9;
        border-top: 1px solid #ccc;
    }

    .card-sponsor h4 {
        font-size: 18px;
        margin-bottom: 10px;
    }

    .card-sponsor-img {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        margin-top: 10px;
    }

    .card-sponsor-img a {
        margin-right: 10px;
    }

    .card-sponsor-img img {
        width: 40px;
        /* Adjust the size of sponsor images */
        height: 40px;
        border-radius: 50%;
    }

    .card-footer {
        background-color: #f0f0f0;
        padding: 15px;
        border-bottom-left-radius: 8px;
        border-bottom-right-radius: 8px;
    }

    .card-footer ul {
        display: flex;
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .card-footer ul li {
        margin-right: 15px;
    }

    .card-footer a {
        color: #333;
        text-decoration: none;
    }

    .pull-right {
        float: right;
    }

    /* Additional styling for icons */
    .fa {
        margin-right: 5px;
    }

    .owl-carousel1 {
        position: relative;
    }

    .owl-carousel1 {
        position: relative;
    }

    .owl-carousel1 .owl-nav {
        position: absolute;
        top: 35%;
        width: 100%;
        transform: translateY(-50%);
    }

    .owl-carousel1 .owl-nav button.owl-prev {
        position: absolute;
        left: 0;
        color: #fff;
        font-size: 47px;
        background: transparent !important;
    }

    .owl-carousel1 .owl-nav button.owl-next {
        position: absolute;
        right: 0;
        color: #fff;
        font-size: 47px;
        background: transparent !important;
    }

    .event-card .card-footer i {
        color: #E73080;
    }

    .event-card .card-header p {
        color: #E73080;
    }

    .card-header h3 {
        text-transform: capitalize;
        font-size: 28px;
    }

    .eventWrpper {
        background-color: #fff;
        padding: 20px;
        border-radius: 10px;
        border: 1px solid #f5f5f5;
    }

    .event-card-img {
        width: 100%;
        height: 300px;
        object-fit: contain;
    }

    .event-card-img img {
        object-fit: contain;
        width: 100%;
        height: 100%;
    }

    /* .poll {
        padding: 20px;
    } */

    .poll ul {
        border: 1px solid #ccc;
        padding: 15px !important;
        border-radius: 5px;
    }

    .poll ul li {
        list-style-type: none;
        border-radius: 5px;
        margin-bottom: 10px;
    }

    .poll ul li:last-child {
        margin-bottom: 0;
    }

    .poll ul li label {
        width: 100%;
        color: #000 !important;
    }

    p.poll-details {
        color: #000 !important
    }

    .poll .progress {
        border-radius: 5px;
    }


    /* (A) MATERIAL ICONS */
    .aWrap .svg-inline--fa {
        color: white !important;
    }

    /* (B) WRAPPER */
    .aWrap {
        font-family: Arial, Helvetica, sans-serif;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px 10px;
        margin: 5px 0;
        border-radius: 10px;
        background: black;
        gap: 1rem;
        margin: 20px 15px;
    }

    .aWrap,
    .aWrap * {
        box-sizing: border-box;
    }

    /* (C) PLAY/PAUSE BUTTON */
    .aPlay {
        padding: 0;
        margin: 0;
        background: 0;
        border: 0;
        cursor: pointer;
    }

    /* (D) TIME */
    .aCron {
        font-size: 12px;
        color: #cbcbcb;
        margin: 0 10px;
        display: flex;
    }

    /* (E) RANGE SLIDERS */
    /* (E1) HIDE DEFAULT */
    .aWrap input[type="range"] {
        appearance: none;
        border: none;
        outline: none;
        box-shadow: none;
        width: 60px;
        padding: 0;
        margin: 0;
        background: 0;
    }

    .range,
    .range-volume {
        position: relative;
        display: flex;
        align-items: center;
    }

    .range input,
    .range-volume input {
        position: relative;
        z-index: 1;
    }

    .range .change-range,
    .range-volume .change-range {
        position: absolute;
        left: 0;
        top: 0;
        height: 6px;
        width: 0px;
        background-color: rgb(187, 187, 187);
        border-radius: 10px 0 0 10px;
    }

    .range-volume .change-range {
        height: 10px;
        width: 95%;
    }

    .under-ranger {
        position: absolute;
        left: 0;
        top: 0;
        height: 6px;
        width: 100%;
        background-color: rgb(63, 63, 63);
        border-radius: 10px;
    }

    .range-volume .under-ranger {
        height: 10px;
    }

    .aWrap input[type="range"]::-webkit-slider-thumb {
        appearance: none;
    }

    /* (E2) CUSTOM SLIDER TRACK */
    .aWrap input[type="range"]::-webkit-slider-runnable-track {
        background: transparent;
        height: 6px;
        border-radius: 10px;
    }

    /* (E3) CUSTOM SLIDER BUTTON */
    .aWrap input[type="range"]::-webkit-slider-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 0;
        background: #fff;
        position: relative;
        cursor: pointer;
        margin-top: -5px;
    }

    .aWrap input[type="range"]::-moz-range-thumb {
        width: 16px;
        height: 16px;
        border-radius: 50%;
        border: 0;
        background: #fff;
        position: relative;
        cursor: pointer;
        margin-top: -5px;
    }

    /* (F) VOLUME */
    .aVolIco {
        margin: 0 10px;
        cursor: pointer;
    }

    .aPlayIco i {
        color: #fff;
    }

    input.aVolume {
        width: 60px !important;
    }

    .aVolume::-webkit-slider-runnable-track {
        height: 10px !important;
    }

    .aVolume::-webkit-slider-thumb {
        margin-top: -3px !important;
    }

    .aVolume::-moz-range-thumb {
        margin-top: -3px !important;
    }

    .volume-container {
        display: flex;
        align-items: center;
    }

    .slick-slider .slick-prev,
    .slick-slider .slick-next {
        z-index: 100;
        font-size: 2.5em;
        height: 40px;
        width: 40px;
        margin-top: -20px;
        color: #B7B7B7;
        position: absolute;
        top: 50%;
        text-align: center;
        color: #000;
        opacity: .3;
        transition: opacity .25s;
        cursor: pointer;
    }

    .slick-slider .slick-prev:hover,
    .slick-slider .slick-next:hover {
        opacity: .65;
    }

    .slick-slider .slick-prev {
        left: 0;
    }

    .slick-slider .slick-next {
        right: 0;
    }

    /* #detail .product-images {
  width: 100%;
  margin: 0 auto;
  border:1px solid #eee;
} */
    #detail .product-images li,
    #detail .product-images figure,
    #detail .product-images a,
    #detail .product-images img {
        display: block;
        outline: none;
        border: none;
    }

    #detail .product-images .main-img-slider figure {
        margin: 0 auto;
        padding: 0 2em;
    }

    #detail .product-images .main-img-slider figure a {
        cursor: pointer;
        cursor: -webkit-zoom-in;
        cursor: -moz-zoom-in;
        cursor: zoom-in;
    }

    #detail .product-images .main-img-slider figure a img {
        width: 100%;
        max-width: 400px;
        margin: 0 auto;
    }

    #detail .product-images .thumb-nav {
        margin: 0 auto;
        padding: 10px 0px;
        max-width: 600px;
    }

    #detail .product-images .thumb-nav li {
        display: block;
        margin: 0 auto;
        cursor: pointer;
    }

    #detail .product-images .thumb-nav li img {
        display: block;
        width: 100%;
        max-width: 75px;
        margin: 0 auto;
        border: 2px solid transparent;
        -webkit-transition: border-color .25s;
        -ms-transition: border-color .25s;
        -moz-transition: border-color .25s;
        transition: border-color .25s;
    }

    #detail .product-images .thumb-nav li:hover,
    #detail .product-images .thumb-nav li:focus {
        border-color: #999;
    }

    #detail .product-images .thumb-nav li.slick-current img {
        border-color: #d12f81;
    }


    .event_postsmain {
        border: 1px solid #E73080;
        padding: 15px;
        border-radius: 10px;
        height: 100%;
    }

    .main-img-slider .slick-list.draggable a {
        height: 180px;
        margin: 0px auto;
    }

    .main-img-slider .slick-list.draggable a img {
        width: 100% !important;
        height: 100% !important;
        border-radius: 5px;
        object-fit: cover;
    }

    .thumb-nav .slick-list.draggable .slick-slide {
        height: 50px;
    }

    .thumb-nav .slick-list.draggable .slick-slide img {
        width: 100% !important;
        height: 100% !important;
        border-radius: 5px;
    }

    .event_posts_right p {
        max-width: 600px;
        overflow: hidden;
        /* white-space: nowrap; */
        text-overflow: ellipsis;
        display: -webkit-box;
        -webkit-line-clamp: 2;
        -webkit-box-orient: vertical;
    }

    .event_posts_creator_img {
        width: 100%;
        max-width: 50px;
        height: 50px;
    }

    .event_posts_creator_img img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    img {
        width: 100%;
        height: 100%;
    }

    .event_posts_right ul {
        display: flex;
        align-items: center;
        gap: 20px;
        padding-left: 0px;
        margin-bottom: 30px !important;
    }

    .event_posts_right ul li {
        list-style: none;
    }

    .event_posts_right ul li a {
        color: var(--primaryColor);
    }

    .event_posts_creator {
        display: flex;
        align-items: start;
        gap: 15px;
    }

    .event_posts_creator_content h6 {
        text-transform: capitalize;
        font-size: 18px;
        font-weight: 600;
    }

    .event_posts_creator_content p {
        margin-bottom: 0px;
        font-size: 14px;
    }

    .event_postsmain .aPlay {
        font-size: 12px;
    }

    .event_postsmain .aWrap {
        padding: 10px 10px;
        margin: 5px 0;
        gap: 5px;
        margin: 10px 5px;
    }

    .event_postsmain .aWrap input[type="range"] {
        width: 45px;
    }

    .event_postsmain input.aVolume {
        width: 45px !important;
    }

    .event_postsmain .aCron {
        margin: 0 5px;
    }

    .event_postsmain .aVolIco {
        margin: 0 10px;
        color: #fff;
        font-size: 12px;
    }

    .slick-slider .slick-prev,
    .slick-slider .slick-next {
        display: none !important;
    }

    /* .poll .progress {
        border-radius: 5px;
        padding: 3px;
        font-size: 8px;
        line-height: 13px !important;
    }
    .progress-bar{
        border-radius: 3px;
        line-height: 13px !important;
    } */
    .like-popup-modal .modal-title {
        font-size: 18px;
        color: var(--primaryColor);
    }

    .like-popup-modal .modal-header .close {
        color: var(--primaryColor);
        opacity: 1 !important;
    }

    .like-wrapper-left .like-wrapper-icon {
        width: 100%;
        max-width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    .like-wrapper-left .like-wrapper-icon img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .like-wrapper-left {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .like-wrapper-content h6 {
        font-size: 14px;
        margin-bottom: 0px;
    }

    .like-wrapper-main {
        padding-left: 0px;
        min-height: 100px;
        max-height: 300px;
        overflow: hidden;
        overflow-y: scroll;
    }

    .like-wrapper-main li {
        list-style: none;
        margin-right: 10px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 7px 0px;
        transition: 0.2s;
    }

    .like-popup-modal .modal-dialog {
        max-width: 500px;
        height: 100%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-top: 0px;
        margin-bottom: 0px;
    }

    .like-wrapper-right li {
        margin-bottom: 0px;
        box-shadow: none;
        -webkit-box-shadow: none;
        -moz-box-shadow: none;
        border: none;
        padding: none;
        border-radius: none;
    }

    .like-wrapper-main::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    .like-wrapper-main::-webkit-scrollbar {
        width: 6px;
        background-color: #F5F5F5;
    }

    .like-wrapper-main::-webkit-scrollbar-thumb {
        background-color: var(--primaryColor);
    }

    .comment-user-info .like-wrapper-icon {
        width: 100%;
        max-width: 30px;
        height: 30px;
        border-radius: 50%;
    }

    .comment-user-info .like-wrapper-icon img {
        width: 100%;
        height: 100%;
        border-radius: 50%;
    }

    .like-wrapper-content h6 {
        font-size: 14px;
        margin-bottom: 0px;
    }

    .comment-user-info {
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .comment-wrapper-main {
        padding-left: 0px;
        min-height: 100px;
        max-height: 350px;
        overflow: hidden;
        overflow-y: scroll;
    }

    .comment-wrapper-main::-webkit-scrollbar-track {
        -webkit-box-shadow: inset 0 0 6px rgba(0, 0, 0, 0.3);
        background-color: #F5F5F5;
    }

    .comment-wrapper-main::-webkit-scrollbar {
        width: 6px;
        background-color: #F5F5F5;
    }

    .comment-wrapper-main::-webkit-scrollbar-thumb {
        background-color: var(--primaryColor);
    }


    .comment-wrapper-main li {
        list-style: none;
        margin-right: 10px;
    }

    .user-comment p {
        font-size: 13px;
        margin-top: 10px;
        margin-bottom: 0px;
    }

    .comment-wrapper {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
        padding: 7px 0px;
    }

    .sub-comment-wrapper {
        padding-left: 20px;
        padding-bottom: 15px;
    }

    .sub-comment-wrapper li {
        padding-top: 10px;
        padding-bottom: 10px;
    }

    .sub-comment-wrapper li:last-child {
        border-bottom: 1px solid rgba(0, 0, 0, 0.1);
    }

    .modal-close-btn {
        font-size: 14px;
        line-height: normal;
        padding: 5px 15px;
        border-radius: 5px;
        background: #6c757d;
        color: #fff;
        border: 1px solid #6c757d;
    }

    .modal-save-btn {
        font-size: 14px;
        line-height: normal;
        padding: 5px 15px;
        border-radius: 5px;
        background: var(--ButtonColor);
        color: #fff;
        border: 1px solid var(--ButtonColor);
    }

    @media only screen and (max-width: 1399px) {
        .event_postsmain .aVolIco {
            margin: 0px 5px;
        }

        .event_posts_right_content {
            margin-top: 20px;
        }
    }

    @media only screen and (max-width: 1199px) {
        .event_posts_right_content {
            margin-top: 20px;
        }

        .event_postsmain .aWrap {
            width: 200px;
        }

        .main-img-slider .slick-list.draggable a {
            height: 230px;
            margin: 0px auto;
        }
    }

    @media only screen and (max-width: 575px) {
        .event_posts_creator_img {
            width: 100%;
            max-width: 40px;
            height: 40px;
        }

        .event_posts_creator_content h6 {
            text-transform: capitalize;
            font-size: 16px;
            font-weight: 600;
            margin-bottom: 5px;
        }

        .event_posts_creator_content p {
            margin-bottom: 0px;
            font-size: 12px;
        }

        .event_posts_creator {
            display: flex;
            align-items: start;
            gap: 10px;
        }

        .main-img-slider .slick-list.draggable a {
            height: 200px;
            margin: 0px auto;
        }
    }
</style>


<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/user_post_report')}}">User Post Reports</a></li>


                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div><!-- /.col -->
                <!-- <div class="col-sm-6">
                    <div class="text-right">
                        <a class="btn btn-primary" href="{{URL::to('admin/category/create')}}">Add</a>
                    </div>
                </div> -->
            </div><!-- /.row -->
        </div><!-- /.container-fluid -->
    </div>
    <div class="eventWrpper mb-5">
        <div class="row">
            @if($reportDetail != NULL)


            <div class="col-md-6 mb-4">
                <div class="event_postsmain">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

                                @if($reportDetail->specific_report == '1')

                                <!-- image -->
                                @if($reportDetail->post_image->type == 'image')
                                <img src="{{ asset('storage/post_image/'.$reportDetail->post_image->post_image)}}">
                                @endif
                                <!-- image -->

                                <!-- video -->
                                @if($reportDetail->post_image->type == 'video')

                                @endif
                                <!-- video -->
                                @else

                                @if($reportDetail->event_posts->post_type == '1')


                                <div class="col-xl-6 col-lg-12 col-md-12">
                                    <div class="event_posts_left">
                                        <div class="product-images demo-gallery">
                                            <!-- Begin Product Images Slider -->
                                            <div class="main-img-slider">
                                                @foreach($reportDetail->event_posts->post_image as $key=>$postImg)
                                                <a data-fancybox="gallery" href="{{ asset('public/storage/post_image/'.$postImg->post_image)}}"><img src="{{ asset('public/storage/post_image/'.$postImg->post_image)}}" /></a>
                                                @endforeach
                                            </div>
                                            <!-- End Product Images Slider -->
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @if($reportDetail->event_posts->post_type == '2')
                                <div class="col-xl-6 col-lg-12 col-md-12">
                                    <main id="app">
                                        <div class="post">
                                            <section class="poll">
                                                <p class="poll-details">
                                                    {{$reportDetail->event_posts->event_post_poll->poll_question }} • Ends in
                                                    {{$reportDetail->event_posts->event_post_poll->poll_duration}}
                                                </p>
                                                <ul class="poll-choices p-0">
                                                    @foreach($reportDetail->event_posts->event_post_poll->event_poll_option as $optionVal)
                                                    <li class="poll-choice choice-1">
                                                        <label for="choice-1">
                                                            <div class="poll-result">
                                                                <div class="star">
                                                                    <div></div>
                                                                </div>
                                                            </div>
                                                            <div class="poll-label">
                                                                <div class="answer">{{$optionVal->option}}</div>
                                                            </div>
                                                            <div class="progress">

                                                                <div class="progress-bar" style="width: <?= round(getOptionTotalVote($optionVal->id) / getOptionAllTotalVote($reportDetail->event_posts->event_post_poll->id) * 100); ?>%">
                                                                    <?= round(getOptionTotalVote($optionVal->id) / getOptionAllTotalVote($reportDetail->event_posts->event_post_poll->id) * 100) . "%"; ?>
                                                                </div>
                                                            </div>
                                                        </label>
                                                    </li>
                                                    @endforeach
                                                </ul>
                                            </section>
                                        </div>
                                    </main>
                                </div>
                                @endif
                                @if($reportDetail->event_posts->post_type == '3')
                                <div class="col-xl-6 col-lg-12 col-md-12">
                                    <div class="aWrap" data-src="{{ asset('public/storage/event_post_recording/'.$reportDetail->event_posts->post_recording)}}">
                                        <button class="aPlay" disabled><span class="aPlayIco"><i class="fa fa-play"></i></span></button>
                                        <div class="range">
                                            <span class="under-ranger"></span>
                                            <input class="aSeek" type="range" min="0" value="0" step="1" disabled><span class="change-range"></span>
                                        </div>
                                        <div class="aCron">
                                            <span class="aNow"></span> / <span class="aTime"></span>
                                        </div>
                                        <div class="volume-container">
                                            <span class="aVolIco"><i class="fa fa-volume-up"></i></span>
                                            <div class="range-volume">
                                                <span class="under-ranger"></span>
                                                <input class="aVolume" type="range" min="0" max="1" value="1" step="0.1" disabled><span class="change-range"></span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif
                                @endif
                                <div class="col-xl-6 col-lg-12 col-md-12">
                                    <div class="event_posts_right">
                                        <div class="event_posts_right_content">
                                            <h6>
                                                {{ $reportDetail->event_posts->post_message}}
                                            </h6>
                                            <!-- <button>Read More</button> -->
                                        </div>

                                        <div class="event_posts_creator">


                                            <div class="event_posts_creator_img user-img">
                                                @if($reportDetail->event_posts->user->profile != "")
                                                <img src="{{ asset('storage/profile/'.$reportDetail->event_posts->user->profile)}}" alt="placeholder image" />
                                                @else
                                                @php $initials = strtoupper($reportDetail->event_posts->user->firstname[0]) . strtoupper($reportDetail->event_posts->user->lastname[0]);

                                                $fontColor = "fontcolor" . strtoupper($reportDetail->event_posts->user->firstname[0]);
                                                @endphp
                                                <h5 class="{{$fontColor}}"> {{ $initials }}</h5>
                                                @endif
                                            </div>
                                            <div class="event_posts_creator_content">
                                                <h6>{{$reportDetail->event_posts->user->firstname.' '.$reportDetail->event_posts->user->lastname}}</h6>
                                                <p>{{$reportDetail->posttime}}</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            @endif
            <div class="col-md-6 mb-4">
                <div class="event_postsmain">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="row">

                                <div class="col-lg-12 col-md-12">
                                    <div class="event_posts_right ">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="event_posts_right_content">
                                                    <h6>
                                                        Event Name
                                                    </h6>
                                                    <h5>{{$reportDetail->events->event_name}}</h5>

                                                    <!-- <button>Read More</button> -->
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="event_posts_right_content">
                                                    <h6>
                                                        Reported By <span>({{ $reportDetail->created_at }})</span>
                                                    </h6>
                                                    <div class="event_posts_creator">


                                                        <div class="event_posts_creator_img user-img">
                                                            @if($reportDetail->users->profile != "")
                                                            <img src="{{ asset('storage/profile/'.$reportDetail->users->profile)}}" alt="placeholder image" />
                                                            @else
                                                            @php $initials = strtoupper($reportDetail->users->firstname[0]) . strtoupper($reportDetail->users->lastname[0]);

                                                            $fontColor = "fontcolor" . strtoupper($reportDetail->users->firstname[0]);
                                                            @endphp
                                                            <h5 class="{{$fontColor}}"> {{ $initials }}</h5>
                                                            @endif
                                                        </div>
                                                        <div class="event_posts_creator_content">
                                                            <h6>{{$reportDetail->users->firstname.' '.$reportDetail->users->lastname}}</h6>
                                                            <p>{{$reportDetail->report_posttime}}</p>
                                                        </div>
                                                    </div>
                                                    <!-- <button>Read More</button> -->
                                                </div>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-0 mb-4">
                <button type="button" class="btn btn-danger deletePrayer" data-id="{{encrypt($reportDetail->id)}}" data-bs-toggle="modal" data-bs-target="#deleteModal">
                    <i class="ri-delete-bin-5-fill"></i>
                </button>
                <!-- <a type="button" class="btn btn-danger" href="{{route('delete_post_report',['id' => encrypt($reportDetail->id) ])}}" data-id="{{$reportDetail->id}}" class="DeleteReport_post">Delete</a> -->
            </div>
        </div>

    </div>
</div>
<div class="modal fade" id="deleteModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Delete Prayer</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                    <i class="bi bi-x-circle"></i>
                </button>
            </div>
            <div class="modal-body">
                <p class="pt-3">Are you sure you want to delete this Prayer ?</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn modal-primary-btn btn-primary save-btn" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn modal-secondary-btn deleteUrl btn-danger save-btn">Delete</a>
            </div>
        </div>
    </div>
</div>