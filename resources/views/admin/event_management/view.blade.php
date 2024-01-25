<div class="container-fluid">
    <h1 class="m-0 ProductTitle">{{$title}}</h1>
    <div class="content-header p-0">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right w-100">
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/dashboard')}}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{URL::to('/admin/events')}}">Event Lists</a></li>
                        <li class="breadcrumb-item active">{{$title}}</li>
                    </ol>
                </div>
                <div class="col-sm-6">
                    <div class="text-right">
                        <a class="btn btn-primary" href="{{URL::to('/admin/events/event_posts/'.$event_id)}}">See Posts</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .event-img-wrp {
            display: flex;
            align-items: center;
            gap: 15px;
            border-radius: 5px;
            padding: 10px;
            /* flex-wrap:wrap; */
            overflow-x: scroll;
        }

        .event-img-wrp img {
            width: 100%;
            height: 100%;
            border-radius: 5px;
            max-width: 220px;
            min-height: 200px;
        }

        /* .dateTime-wrp {
            border: 2px solid #ec5b99;
            border-radius: 20px;
            padding: 15px 20px;
        } */

        .location-card {
            position: relative;
            align-items: center;
            /* border: 2px solid #ec5b99;
            border-radius: 20px !important;
            padding: 15px 20px !important; */
        }

        .event-card {
            position: relative;
        }

        .event-card::after {
            content: '';
            position: absolute;
            width: 2px;
            height: 30px;
            background-color: #474747;
            right: 0;
        }

        .event-card .media-body .text-primary {
            color: #000 !important;
            font-weight: 600;       
        }

        .event-card:last-child::after {
            display: none;
        }

        .event-table,
        .event-table td,
        .event-table th {
            border: 1px solid #ccc !important;
        }

        .event-contentWrp {
            margin-left: 10px;
            width: 100%;
        }
        
        .event-content{
            padding-left: 17px;
            padding-top: 15px;
        }

        .event-content h4 {
            font-size: 18px;
            margin-bottom: 0;
            font-weight: 600;
            margin-top: 0 !important;
        }

        .location-card span br {
            display: none;
        }

        .event-img-wrp::-webkit-scrollbar {
            width: 5px;
            height: 5px;
            background-color: #f5f5f5;
            /* or add it to the track */
        }

        .event-img-wrp::-webkit-scrollbar-thumb {
            background: #000
        }

        .location-card .event-size-1{
            max-width: 500px;
            display: block;
        }

        .card .owl-carousel.owl-drag .owl-item{
            width:auto !important;
        }

        .card .owl-carousel.owl-drag .owl-item .item{
            width: 300px !important;
            height:200px !important;
            display: block;
            overflow: hidden;
            position: relative;
            
            border-radius: 1px;
        }

        .card .owl-carousel.owl-drag .owl-item .item img{
            /* bottom: 0; */    
            width: 100%;
            height: auto;
            position: absolute;
            z-index: 0;
            margin:0;
            padding:0;
            -webkit-transition: top 5s;
            -moz-transition: top 5s;
            -ms-transition: top 5s;
            -o-transition: top 5s;
            transition: bottom 5s;
            border-radius: 5px;
            cursor:pointer;
        }

        /* .card .owl-carousel.owl-drag .owl-item .item:hover img {
            bottom: 0;
            -webkit-transition: all 5s;
            -moz-transition: all 5s;
            -ms-transition: all 5s;
            -o-transition: all 5s;
            transition: all 5s;
        } */

    </style>

    <?php

    ?>
    <div class="form-head mb-4 d-flex flex-wrap align-items-center">

    </div>
    <div class="row">
        <div class="col-xl-12 col-xxl-8">
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="eventsMain d-flex">
                                    <div class="event-content">
                                        <div class="title-wrp d-flex align-items">
                                            <svg class="mr-2" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="25" height="25" version="1.1" x="0px" y="0px" viewBox="0 0 100 125" enable-background="new 0 0 100 100" xml:space="preserve"><path d="M85,92.5V20c0-0.7-0.3-1.3-0.7-1.8L71.8,5.7C71.3,5.3,70.7,5,70,5H17.5C16.1,5,15,6.1,15,7.5v85c0,1.4,1.1,2.5,2.5,2.5h65  C83.9,95,85,93.9,85,92.5z M70,11l9,9h-9V11z M80,90H20V10h45v12.5c0,1.4,1.1,2.5,2.5,2.5H80V90z"/><path d="M32.5,40c-1.4,0-2.5,1.1-2.5,2.5s1.1,2.5,2.5,2.5h35c1.4,0,2.5-1.1,2.5-2.5S68.9,40,67.5,40H32.5z"/><path d="M57.5,50h-15c-1.4,0-2.5,1.1-2.5,2.5s1.1,2.5,2.5,2.5h15c1.4,0,2.5-1.1,2.5-2.5S58.9,50,57.5,50z"/></svg>
                                            <div>
                                                <h4 class="fs-28 font-w500  mt-3">{{$eventDetail->event_name}}</h4>
                                                <input type="hidden" id="eventId" value="{{$eventDetail->id}}">
                                            </div>
                                        </div>
                                        <div>
                                            <div class="mt-3">
                                                <div class="location-card d-flex event-card rounded">
                                                    <svg class="mr-3 location" width="20" height="20" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <g clip-path="">
                                                            <path d="M27.5711 13.4286C27.5711 22.4286 15.9997 30.1428 15.9997 30.1428C15.9997 30.1428 4.42822 22.4286 4.42822 13.4286C4.42822 10.3596 5.64735 7.41638 7.81742 5.24632C9.98748 3.07625 12.9307 1.85712 15.9997 1.85712C19.0686 1.85712 22.0118 3.07625 24.1819 5.24632C26.3519 7.41638 27.5711 10.3596 27.5711 13.4286Z" stroke="#194039" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            <path d="M15.9997 17.2857C18.13 17.2857 19.8569 15.5588 19.8569 13.4286C19.8569 11.2983 18.13 9.57141 15.9997 9.57141C13.8695 9.57141 12.1426 11.2983 12.1426 13.4286C12.1426 15.5588 13.8695 17.2857 15.9997 17.2857Z" stroke="#194039" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"></path>
                                                        </g>
                                                        <defs>
                                                            <clipPath id="clip1">
                                                                <rect width="30.8571" height="30.8571" fill="white" transform="translate(0.571289 0.571411)"></rect>
                                                            </clipPath>
                                                        </defs>
                                                    </svg>
                                                    <div class="media-body event-size">
                                                        <!-- <span class="fs-14 d-block mb-1 text-primary">Location</span> -->
                                                        <span class="fs-18 font-w500 event-size-1">
                                                            {!! nl2br($eventDetail->event_location_name . "\n" . $eventDetail->address_1 . ' ' . $eventDetail->address_2 . ' ' . $eventDetail->city . ' ' . $eventDetail->state . ' ' . $eventDetail->pincode) !!}
                                                        </span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="event-content ml-auto">
                                        <div class="dateTime-wrp d-flex align-items-center ml-auto">
                                                <div class="media event-card pr-3 rounded align-items-center">
                                                    <svg class="mr-3" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                                        <path d="M6.66675 1.66602V4.16602" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M13.3333 1.66602V4.16602" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M2.91675 7.57422H17.0834" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M17.5 7.08268V14.166C17.5 16.666 16.25 18.3327 13.3333 18.3327H6.66667C3.75 18.3327 2.5 16.666 2.5 14.166V7.08268C2.5 4.58268 3.75 2.91602 6.66667 2.91602H13.3333C16.25 2.91602 17.5 4.58268 17.5 7.08268Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M13.0788 11.4167H13.0863" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M13.0788 13.9167H13.0863" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M9.99632 11.4167H10.0038" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M9.99632 13.9167H10.0038" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M6.91185 11.4167H6.91933" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M6.91185 13.9167H6.91933" stroke="#0F172A" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="media-body event-size">
                                                        <!-- <span class="fs-14 d-block mb-1 text-primary">Date</span> -->
                                                        <span class="fs-18 font-w500 event-size-1 ">{{ date('l, d F Y',strtotime($eventDetail->start_date))}}</span>
                                                    </div>
                                                </div>
                                                <!-- <div class="media event-card pl-3 rounded">
                                                    <svg class="mr-3 mt-1" xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 21 20" fill="none">
                                                        <path d="M18.8334 9.99935C18.8334 14.5993 15.1001 18.3327 10.5001 18.3327C5.90008 18.3327 2.16675 14.5993 2.16675 9.99935C2.16675 5.39935 5.90008 1.66602 10.5001 1.66602C15.1001 1.66602 18.8334 5.39935 18.8334 9.99935Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M13.5917 12.6495L11.0083 11.1078C10.5583 10.8411 10.1917 10.1995 10.1917 9.67448V6.25781" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <div class="media-body event-size">
                                                        <span class="fs-14 d-block mb-1 text-primary">Time</span> 
                                                        <span class="fs-18 font-w500 event-size-1 ">{{ date('l, d F Y',strtotime($eventDetail->start_date))}}</span>
                                                    </div>
                                                </div> -->

                                        </div>
                                        <div class="mt-3 media event-card rounded">
                                            <svg class="mr-3 " xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 15 15" fill="none">
                                                <path opacity="1" d="M11.204 12.23C10.8832 13.0525 10.1015 13.5833 9.2207 13.5833H5.77903C4.89237 13.5833 4.11653 13.0525 3.7957 12.23C3.47487 11.4017 3.69653 10.4858 4.34987 9.89083L6.71237 7.75H8.2932L10.6499 9.89083C11.3032 10.4858 11.519 11.4017 11.204 12.23Z" fill="none" stroke="#000" />
                                                <path d="M8.5615 11.3304H6.43817C6.2165 11.3304 6.0415 11.1496 6.0415 10.9338C6.0415 10.7121 6.22234 10.5371 6.43817 10.5371H8.5615C8.78317 10.5371 8.95817 10.7179 8.95817 10.9338C8.95817 11.1496 8.77734 11.3304 8.5615 11.3304Z" fill="#000" stroke="#000" />
                                                <path d="M11.2042 3.26935C10.8834 2.44685 10.1017 1.91602 9.22089 1.91602H5.77922C4.89839 1.91602 4.11672 2.44685 3.79589 3.26935C3.48089 4.09768 3.69672 5.01352 4.35589 5.60852L6.71255 7.74935H8.29339L10.6501 5.60852C11.3034 5.01352 11.5192 4.09768 11.2042 3.26935ZM8.56172 4.96685H6.43839C6.21672 4.96685 6.04172 4.78602 6.04172 4.57018C6.04172 4.35435 6.22255 4.17352 6.43839 4.17352H8.56172C8.78339 4.17352 8.95839 4.35435 8.95839 4.57018C8.95839 4.78602 8.77755 4.96685 8.56172 4.96685Z" stroke="#000" fill="none" />
                                            </svg>
                                            <div class="media-body event-size d-flex">
                                                <span class="fs-14 d-block text-primary">Days Till Event</span>
                                                <span class="fs-18 font-w500 event-size-1 ml-2">{{ $eventDetail->till_days}}</span>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xl-8">
                                <div class="card-body d-flex flex-column">
                                    @if(count($eventImage) != 0)
                                    <div class="owl-carousel owl-theme">
                                        @foreach($eventImage as $imgVal)
                                        <div class="item">
                                            <a data-fancybox="gallery" href="{{asset('public/storage/event_images/'.$imgVal->image)}}">
                                                <img src="{{asset('public/storage/event_images/'.$imgVal->image)}}" class="img-fluid" alt="">
                                            </a>
                                            <!-- <img src="https://i.imgur.com/aFFEZ9U.jpg" class="img-fluid" alt=""> -->
                                        </div>
                                        @endforeach
                                    </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-xl-4">

                            </div>
                        </div>

                    </div>
                </div>

                <div class="col-xl-12">



                    <table id="invitedUsersList" class="table table-bordered data-table users-data-table">

                        <thead>

                            <tr>
                                <th>No</th>
                                <th>Username</th>
                                <th>Email</th>
                                <th>RSVP Status</th>
                                <th>Event Posts</th>

                            </tr>

                        </thead>

                        <tbody>

                        </tbody>

                    </table>

                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="event_id" value="{{$event_id}}">