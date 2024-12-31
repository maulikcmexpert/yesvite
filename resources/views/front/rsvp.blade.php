{{-- {{                dd($eventInfo);}} --}}
@php
    use Carbon\Carbon;
@endphp
<x-front.advertise />
<section class="rsvp-wrp new-main-content">
   <!-- ===main-section-start=== -->
   <div class="rsvp-tab-wrp event-center-tabs-main">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" id="nav-invite-tab" data-bs-toggle="tab" data-bs-target="#nav-invite" type="button" role="tab" aria-controls="nav-invite" aria-selected="true">
                Invite
            </button>
            <button class="nav-link" id="nav-messaging-tab" data-bs-toggle="tab" data-bs-target="#nav-messaging" type="button" role="tab" aria-controls="nav-messaging" aria-selected="false">
                Messaging
            </button>
        </div>
    </nav>   
    <div class="container">     
        <div class="tab-content" id="nav-tabContent">
            <div class="tab-pane fade show active" id="nav-invite" role="tabpanel" aria-labelledby="nav-invite-tab">
                <section class="rsvp-wrp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 mb-lg-0 mb-sm-4 mb-md-4 mb-0">
                                <div class="rsvp-slider owl-carousel owl-theme {{($eventInfo['guest_view']['event_images']!="" && count($eventInfo['guest_view']['event_images']) > 1 )?'rsvp-slide':''}} " >    
                                    @if ($eventInfo['guest_view']['event_images']!="")
                                        @foreach($eventInfo['guest_view']['event_images'] as $value)
                                        <div class="item">
                                            <div class="rsvp-img">
                                                <img src="{{ asset($value)}}" alt="birth-card">
                                            </div>
                                        </div>
                                        @endforeach
                                    @endif
                                </div>
                            </div>
                            <div class="col-lg-7">
                                 <div class="rsvp-form">
                                     <h5 class="title">RSVP</h5>
                                     <div class="author-wrp">
                                         <div class="author-img">
                                            @if ($eventInfo['guest_view']['user_profile'] != '')
                                                <img src="{{ $eventInfo['guest_view']['user_profile']}}" alt="">
                                            @else
                                            @php
                                                $firstInitial = !empty($eventInfo['guest_view']['host_first_name']) ? strtoupper($eventInfo['guest_view']['host_first_name'][0]) : '';
                                                $lastInitial = !empty($eventInfo['guest_view']['host_last_name']) ? strtoupper($eventInfo['guest_view']['host_last_name'][0]) : '';
                                                $initials = $firstInitial . $lastInitial;
                                                $fontColor = 'fontcolor' . $firstInitial;
                                            @endphp
                                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                            @endif
                                         </div>
                                         <div class="author-title">
                                             <h4>{{$eventInfo['guest_view']['event_name']}}</h4>
                                             <p><span>Hosted by:</span>{{ $eventInfo['guest_view']['hosted_by']}}</p>
                                         </div>
                                     </div>
                                     @if($eventInfo['guest_view']['message_to_guests'] != null || $eventInfo['guest_view']['message_to_guests'] != "")
                                     <div class="thank-card">
                                         <p>{{$eventInfo['guest_view']['message_to_guests'] }}</p>
                                     </div>
                                     @endif
                                     @php
                                        $i = 1;
                                     @endphp
                                     @if($eventInfo['guest_view']['event_detail'])
                                     <div class="event-detail">
                                         <h5>Event Details</h5>
                                         <div class="d-flex flex-wrap px-2">
                                             <div class="d-flex align-items-center justify-content-between {{($i<= 3)?'w-100 mb-2':'w-100'}}">
                                                @foreach($eventInfo['guest_view']['event_detail'] as $val)
                                                <h6>{{$val}}</h6>
                                                @php $i++; @endphp
                                                @endforeach
                                             </div>
                                             {{-- <div class="d-flex align-items-center justify-content-between w-100">
                                                 <h6>Adults & Kids</h6>
                                                 <h6>Potluck Event</h6>
                                             </div> --}}
                                         </div>
                                     </div>
                                     @endif
                                     <div class="rsvp-hosted-by-date-time-wrp">
                                         <div class="hosted-by-date-time rsvp-hosted-by-date-time">
                                             <div class="hosted-by-date-time-left">
                                             <div class="hosted-by-date-time-left-icon">
                                                 <svg width="30" height="30" viewBox="0 0 30 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                 <path d="M10 2.5V6.25" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M20 2.5V6.25" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M4.375 11.3633H25.625" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M26.25 10.625V21.25C26.25 25 24.375 27.5 20 27.5H10C5.625 27.5 3.75 25 3.75 21.25V10.625C3.75 6.875 5.625 4.375 10 4.375H20C24.375 4.375 26.25 6.875 26.25 10.625Z" stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M19.6181 17.125H19.6294" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M19.6181 20.875H19.6294" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M14.9951 17.125H15.0063" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M14.9951 20.875H15.0063" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M10.3681 17.125H10.3794" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M10.3681 20.875H10.3794" stroke="black" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 </svg>
                                             </div>
                                             <div class="hosted-by-date-time-content">
                                                 <h6>Date</h6>
                                                 <h3>{{Carbon::parse($eventInfo['guest_view']['event_date'])->format('F j, Y')}}</h3>
                                             </div>
                                             </div>
                                             <div class="hosted-by-date-time-left">
                                             <div class="hosted-by-date-time-left-icon">
                                                 <svg width="31" height="30" viewBox="0 0 31 30" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                 <path d="M28 15C28 21.9 22.4 27.5 15.5 27.5C8.6 27.5 3 21.9 3 15C3 8.1 8.6 2.5 15.5 2.5C22.4 2.5 28 8.1 28 15Z" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 <path d="M20.1371 18.9742L16.2621 16.6617C15.5871 16.2617 15.0371 15.2992 15.0371 14.5117V9.38672" stroke="black" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                 </svg>
                                             </div>
                                             <div class="hosted-by-date-time-content">
                                                 <h6>Time</h6>
                                                 {{-- <h3>8:00 to 10:00PM</h3> --}}
                                                 <h3>{{ $eventInfo['guest_view']['event_time'] }}</h3>
                                             </div>
                                             </div>
                                         </div>
                                         <a href="#" class="add-calender btn">Add to calendar 
                                             <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                             <path d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             <path d="M5.33203 8H10.6654" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             <path d="M8 10.6668V5.3335" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                             </svg>
                                         </a>
                                     </div>
                                     <div class="host-users-detail rsvp-host-users-detail cmn-card">
                                         <h4 class="title">Your hosts</h4>
                                         <div class="host-user-con-box">
                                            <div class="host-user-con">
                                                <div class="img-wrp">
                                                    @if ($eventInfo['guest_view']['user_profile'] != '')
                                                        {{-- <img src="./assets/img/host-img.png" alt="host-img">                                  --}}
                                                        <img src="{{ $eventInfo['guest_view']['user_profile']}}" alt="">
                                                    @else
                                                    @php
                                                        $firstInitial = !empty($eventInfo['guest_view']['host_first_name']) ? strtoupper($eventInfo['guest_view']['host_first_name'][0]) : '';
                                                        $lastInitial = !empty($eventInfo['guest_view']['host_last_name']) ? strtoupper($eventInfo['guest_view']['host_last_name'][0]) : '';
                                                        $initials = $firstInitial . $lastInitial;
                                                        $fontColor = 'fontcolor' . $firstInitial;
                                                    @endphp
                                                    <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                    @endif
                                                </div>
                                                <h5>{{ $eventInfo['guest_view']['hosted_by']}}</h5>
                                                <span>Host</span>
                                                <a href="#" class="msg-btn">Message</a>
                                            </div>
                                            @if(!empty($eventInfo['guest_view']['co_hosts']))
                                            @php
                                                $coHost = $eventInfo['guest_view']['co_hosts'][0];
                                            @endphp
                                            <div class="host-user-con">
                                                <div class="img-wrp">
                                                @if ($coHost['profile'] != '')
                                                    <img src="{{$coHost['profile']}}" alt="host-img">  
                                                @else
                                                @php
                                                    $firstInitial = !empty($coHost['first_name']) ? strtoupper($coHost['first_name'][0]) : '';
                                                    $lastInitial = !empty($coHost['last_name']) ? strtoupper($coHost['last_name'][0]) : '';
                                                    $initials = $firstInitial . $lastInitial;
                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                @endphp
                                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                @endif
                                                </div>
                                                <h5>{{$coHost['name']}}</h5>
                                                <span>Co-host</span>
                                                <a href="#" class="msg-btn">Message</a>
                                            </div>
                                            @endif
                                         </div>
                                         {{-- <p>“Thanks everyone for RSVP'ing on time.<br> I hope everyone can make it to this special day of ours!”</p> --}}
                                         <p>“{{$eventInfo['guest_view']['message_to_guests']}}”</p>
                                     </div>
                                     <div class="location-wrp cmn-card rsvp-location-wrp">
                                         <h4 class="title">Event Location</h4>
                                         <h5>{{$eventInfo['guest_view']['event_location_name']}}</h5>
                                         {{-- <p>2369 Graystone Lakes Maconey, CA 90210</p> --}}
                                         <p>{{$eventInfo['guest_view']['address_1']}} {{$eventInfo['guest_view']['city']}}, {{$eventInfo['guest_view']['state']}} {{$eventInfo['guest_view']['zip_code']}}</p>

                                         <div id="map">
                                           <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.835434509374!2d144.9630579153168!3d-37.81410797975195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d1b1f5f1f1f1!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1611815623456!5m2!1sen!2sau" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe>
                                           <img src="./assets/img/location-marker.svg" alt="marker" class="marker">
                                         </div>
                                         <a href="#" class="direction-btn">Directions</a>
                                     </div>
                                     <div class="guest-user-list rsvp-guest-user-list-wrp cmn-card">
                                         <div class="rsvp-guest-user-list-title">
                                           <h5 class="heading">Guest List ({{count($getInvitedusers['invited_user_id'])}} Guests)</h5>
                                           <a href="#" data-bs-toggle="modal" data-bs-target="#rsvp-guest-list-modal">See All</a>
                                         </div>
                                         <div>
                                            @foreach ($getInvitedusers['invited_user_id'] as $guest_data )
                                            <div class="guest-user-box">
                                              <div class="guest-list-data">
                                                <a href="#" class="guest-img">
                                                @if ($guest_data['profile'] != '')
                                                    <img src="{{$guest_data['profile']}}" alt="guest-img">
                                                    @else
                                                    @php
                                                        $firstInitial = !empty($guest_data['first_name']) ? strtoupper($guest_data['first_name'][0]) : '';
                                                        $lastInitial = !empty($guest_data['last_name']) ? strtoupper($guest_data['last_name'][0]) : '';
                                                        $initials = $firstInitial . $lastInitial;
                                                        $fontColor = 'fontcolor' . $firstInitial;
                                                    @endphp
                                                         <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                @endif
                                                </a>
                                                <div class="w-100">
                                                  <div class="d-flex flex-column">
                                                      <a href="#" class="guest-name">{{$guest_data['first_name']}} {{$guest_data['last_name']}}</a>
                                                      <span class="guest-email">{{$guest_data['email']}}</span>
                                                  </div>
                                                
                                                  {{-- @if($guest_data['rsvp_status']=="1") --}}
                                                  <div class="sucess-rsvp-wrp">
                                                    <div class="d-flex align-items-center">
                                                      <h5 class="green d-flex align-items-center">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                        <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                        <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                        <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                        </svg> Succesful</h5>
                                                      <h5 class="ms-auto">Read, RSVP’d</h5>
                                                    </div>
                                                </div>
                                                  <div class="sucess-yes">
                                                    <h5 class="green">RSVP'D YES</h5>
                                                    <div class="sucesss-cat ms-auto">
                                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                        </svg>
                                                        <h5>{{$guest_data['adults']}} Adults</h5>
                                                        <h5>{{$guest_data['kids']}} Kids</h5>
                                                    </div>
                                                  </div>
                                                  {{-- @elseif ($guest_data['rsvp_status']=="0")
                                                  <div class="sucess-yes">
                                                    <h5 class="green">RSVP'D NO</h5>
                                                    <div class="sucesss-cat ms-auto">
                                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                            </svg>
                                                            <h5>{{$guest_data['adults']}} Adults</h5>
                                                            <h5>{{$guest_data['kids']}} Kids</h5>
                                                    </div>
                                                  @else
                                                  </div><div class="sucess-yes">
                                                    <h5 class="green">NO REPLY</h5>
                                                    <div class="sucesss-cat ms-auto">
                                                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                            </svg>
                                                            <h5>{{$guest_data['adults']}} Adults</h5>
                                                            <h5>{{$guest_data['kids']}} Kids</h5>
                                                    </div>
                                                  </div>
                                                  @endif --}}
                                                  <div class="rsvp-guest-user-replay">
                                                        @if($guest_data['message_to_host']!="")
                                                            <h6>“ {{$guest_data['message_to_host']}} “</h6>
                                                        @endif
                                                  </div>
                                                </div>
                                              </div>
                                              
                                            </div>
                                            @endforeach
                                           {{-- <div class="guest-user-box">
                                             <div class="guest-list-data">
                                               <a href="#" class="guest-img">
                                                 <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                               </a>
                                               <div class="w-100">
                                                 <div class="d-flex flex-column">
                                                     <a href="#" class="guest-name">Pristia Candra</a>
                                                     <span class="guest-email">jamesclark@gmail.com</span>
                                                 </div>
                                                 <div class="sucess-rsvp-wrp">
                                                     <div class="d-flex align-items-center">
                                                       <h5 class="green d-flex align-items-center">
                                                         <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                         <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                         <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                         <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                         <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                         </svg> Succesful</h5>
                                                       <h5 class="ms-auto">Read, RSVP’d</h5>
                                                     </div>
                                                 </div>
                                                 <div class="sucess-yes">
                                                 <h5 class="green">YES</h5>
                                                 <div class="sucesss-cat ms-auto">
                                                     <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                     <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                     <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                     <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                     <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                     </svg>
                                                     <h5>3 Adults</h5>
                                                     <h5>2 Kids</h5>
                                                 </div>
                                                 </div>
                                                 <div class="rsvp-guest-user-replay">
                                                     <h6>“ Thanks guys for the invite!  We’ll be there “</h6>
                                                 </div>
                                               </div>
                                             </div>
                                             
                                           </div>
                                           <div class="guest-user-box">
                                             <div class="guest-list-data">
                                               <a href="#" class="guest-img">
                                                 <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                               </a>
                                               <div class="w-100">
                                                 <div class="d-flex flex-column">
                                                     <a href="#" class="guest-name">Pristia Candra</a>
                                                     <span class="guest-email">jamesclark@gmail.com</span>
                                                 </div>
                                                 <div class="sucess-rsvp-wrp">
                                                     <div class="d-flex align-items-center">
                                                       <h5 class="green d-flex align-items-center">
                                                         <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                         <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                         <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                         <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                         <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                         </svg> Succesful</h5>
                                                       <h5 class="ms-auto">Read, RSVP’d</h5>
                                                     </div>
                                                 </div>
                                                 <div class="sucess-yes">
                                                 <h5 class="green">YES</h5>
                                                 <div class="sucesss-cat ms-auto">
                                                     <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                     <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                     <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                     <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                     <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                     </svg>
                                                     <h5>3 Adults</h5>
                                                     <h5>2 Kids</h5>
                                                 </div>
                                                 </div>
                                                 <div class="rsvp-guest-user-replay">
                                                     <h6>“ Thanks guys for the invite!  We’ll be there “</h6>
                                                 </div>
                                               </div>
                                             </div>
                                             
                                           </div> --}}
                                         </div>
                                     </div>
                                     @if(!empty($eventInfo['guest_view']['event_schedule']))
                                     <div class="schedule-wrp rsvp-schedule-wrp cmn-card">
                                         <h4 class="title">Schedule</h4>
                                         <span class="timing">10:30 AM - 4:00 PM</span>
                                         <div>
                                         
                                             {{-- <div class="shedule-manage-timing">
                                                 <div class="shedule-timing">
                                                     <h6>10 AM</h6>
                                                 </div>
                                                 <div class="shedule-box green">
                                                     <div class="shedule-box-left">
                                                       <h6>Start</h6>
                                                       <span>10:00 AM - 11:00AM</span>
                                                     </div>
                                                     <span class="hrs ms-auto">1h</span>
                                                 </div>
                                                 <img src="./assets/img/timing-line.svg" alt="timing">
                                             </div> --}}
                                             @foreach ($eventInfo['guest_view']['event_schedule'] as $schedule )
                                               @if($schedule['type']=="1")
                                                    <div class="shedule-manage-timing">
                                                        <div class="shedule-timing">
                                                            <h6>{{$schedule['start_time']}}</h6>
                                                        </div>
                                                        <div class="shedule-box green">
                                                            <div class="shedule-box-left">
                                                            <h6>{{$schedule['activity_title']}}</h6>
                                                            @if($schedule['end_time'])
                                                            <span>{{$schedule['start_time']}} - {{$schedule['end_time']}}</span>
                                                            @endif                                                            </div>
                                                            <span class="hrs ms-auto">1h</span>
                                                        </div>
                                                        <img src="./assets/img/timing-line.svg" alt="timing">
                                                    </div>
                                                @elseif ($schedule['type']=="2")
                                                    <div class="shedule-manage-timing">
                                                        <div class="shedule-timing">
                                                            <h6>{{$schedule['start_time']}}</h6>
                                                        </div>
                                                        <div class="shedule-box yellow">
                                                            <div class="shedule-box-left">
                                                                <h6>{{$schedule['activity_title']}}</h6>
                                                                @if($schedule['end_time'])
                                                                <span>{{$schedule['start_time']}} - {{$schedule['end_time']}}</span>
                                                                @endif
                                                            </div>
                                                            <span class="hrs ms-auto">1h</span>
                                                        </div>
                                                        <img src="./assets/img/timing-line.svg" alt="timing">
                                                        </div>
                                               @else

                                                    <div class="shedule-manage-timing">
                                                        <div class="shedule-timing">
                                                            <h6>{{$schedule['start_time']}}</h6>
                                                        </div>
                                                        <div class="shedule-box red">
                                                            <div class="shedule-box-left">
                                                                <h6>{{$schedule['activity_title']}}</h6>
                                                                @if($schedule['end_time'])
                                                                <span>{{$schedule['end_time']}}</span>
                                                                @endif                                                            </div>
                                                            <span class="hrs ms-auto">30m</span>
                                                        </div>
                                                        </div>
                                            @endif
                                             @endforeach
                                             {{-- <div class="shedule-manage-timing">
                                               <div class="shedule-timing">
                                                   <h6>12 AM</h6>
                                               </div>
                                               <div class="shedule-box pink">
                                                   <div class="shedule-box-left">
                                                     <h6>Cake Time</h6>
                                                     <span>12:00 AM - 1:00 PM</span>
                                                   </div>
                                                   <span class="hrs ms-auto">1h</span>
                                               </div>
                                               <img src="./assets/img/timing-line.svg" alt="timing">
                                             </div>
                                             <div class="shedule-manage-timing">
                                               <div class="shedule-timing">
                                                   <h6>1 AM</h6>
                                               </div>
                                               <div class="shedule-box blue">
                                                   <div class="shedule-box-left">
                                                     <h6>Main Event</h6>
                                                     <span>1:00 AM - 3:30 PM</span>
                                                   </div>
                                                   <span class="hrs ms-auto">1h 30m</span>
                                               </div>
                                               <img src="./assets/img/timing-line.svg" alt="timing">
                                             </div> --}}
                                             {{-- <div class="shedule-manage-timing">
                                               <div class="shedule-timing">
                                                   <h6>3 AM</h6>
                                               </div>
                                               <div class="shedule-box red">
                                                   <div class="shedule-box-left">
                                                     <h6>Ends</h6>
                                                     <span>4:00 PM</span>
                                                   </div>
                                                   <span class="hrs ms-auto">30m</span>
                                               </div>
                                             </div> --}}
                                         </div>
                                     </div>
                                     @endif
                                     
                                     @if(!empty($eventInfo['guest_view']['gift_registry']))
                                     <div class="rsvp-app new-rsvp-app">
                                        <h4 class="title">Host created registries</h4>
                                         <div class="row">
                                            @foreach ($eventInfo['guest_view']['gift_registry'] as $gift )
                                            <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-3">
                                                <div class="target d-flex gap-3 align-items-center">

                                                    @php
                                                    $logo="";
                                                    if($gift['registry_recipient_name']=="amazon"){
                                                        $logo=asset('assets/amazon.jpg');
                                                    }elseif ($gift['registry_recipient_name']=="target") {
                                                        $logo=asset('assets/target.jpg');
                                                    }
                                                    @endphp
                                                   <img src="{{$logo}}" alt="">
                                                    <div>
                                                        <h5>{{$gift['registry_recipient_name']}}</h5>
                                                        <p>View their wish list</p>
                                                    </div>
                                                </div>
                                           </div> 
                                            @endforeach
                                            
                                            {{-- <div class="col-lg-6 col-md-6 col-sm-6">
                                             <div class="target d-flex gap-3 align-items-center">
                                                <img src="./assets/img/rsvp-amazon-img.png" alt="">
                                                <div>
                                                     <h5>Amazon</h5>
                                                     <p>View their wish list</p>
                                                 </div>
                                             </div>
                                        </div> --}}
                                         </div>
                                     </div>
                                     @endif
                                     @if($eventInfo['guest_view']['event_potluck']!="0")
                                     <div class="note-wrp rsvp-note-wrp">
                                         <h5><span>Note:</span> This is a Potluck Event</h5>
                                         <p>Sign Up on iOS or Android Apps to let them know what you will be brining.</p>
                                     </div>
                                     @endif
                                 </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
            <div class="tab-pane fade" id="nav-messaging" role="tabpanel" aria-labelledby="nav-messaging-tab">
            </div>
        </div>
    </div>
    <div class="rsvp-footer-btn-wrp">
        <div class="container">
            <div class="rsvp-footer-btn">
                <h3>RSVP</h3>
                <div class="d-flex align-items-center justify-content-end gap-3 w-100">
                    <button class="cmn-btn check_rsvp_yes" data-event_id="{{encrypt($event_id)}}" data-user_id="{{encrypt($user_id)}}" data-bs-toggle="modal" data-bs-target="#rsvp-yes-modal">Yes</button>
                    <button class="cmn-btn cmn-no-btn check_rsvp_no" data-event_id="{{encrypt($event_id)}}" data-user_id="{{encrypt($user_id)}}"  data-bs-toggle="modal" data-bs-target="#rsvp-no-modal">No</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- about yes rsvp Modal -->
<div class="modal fade cmn-modal about-rsvp rsvp-yes-modal" id="rsvp-yes-modal" tabindex="-1" aria-labelledby="aboutsuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
      
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <div>
                            <h4 class="modal-title" id="aboutsuccessLabel">RSVP’ing: <span>Yes</span></h4>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form method="POST" action="{{ route('rsvp.store') }}" id="rsvpYesForm">
                        @csrf
                        <input type="hidden" value="{{encrypt($user_id)}}" name="user_id" id="user_id"/>
                        <input type="hidden" value="{{encrypt($event_id)}}" name="event_id" id="event_id"/>
                        <input type="hidden" value="1" name="rsvp_status" id="rsvp_status"/>
                    <div class="modal-body">
                        <div class="d-flex align-items-center rsvp-yes-profile">
                            @if ($eventInfo['guest_view']['user_profile'] != '')
                                <img src="{{ $eventInfo['guest_view']['user_profile']}}" alt="rs-img" class="about-rs-img">
                            @else
                            @php
                                $firstInitial = !empty($eventInfo['guest_view']['host_first_name']) ? strtoupper($eventInfo['guest_view']['host_first_name'][0]) : '';
                                $lastInitial = !empty($eventInfo['guest_view']['host_last_name']) ? strtoupper($eventInfo['guest_view']['host_last_name'][0]) : '';
                                $initials = $firstInitial . $lastInitial;
                                $fontColor = 'fontcolor' . $firstInitial;
                            @endphp
                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                            @endif    
                            <div>
                                <h4 class="modal-title" id="aboutsuccessLabel">{{$eventInfo['guest_view']['event_name']}}</h4>
                                <p>Hosted by: <span>{{$eventInfo['guest_view']['hosted_by']}}</span></p>
                            </div>
                        </div>
                      
                                    <div class="rsvp-custom-radio guest-rsvp-attend">
                                        <h6>Your Info</h6>
                                        <div class="rsvp-input-form row">
                                            <div class="col-lg-6">
                                                <div class="input-form">
                                                    <input type="text" name="firstname" id="firstname" class="form-control inputText" >
                                                    <label for="Fname" class="form-label input-field floating-label">First Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-form">
                                                    <input type="text"  name="lastname" id="lastname"  class="form-control inputText" >
                                                    <label for="Fname" class="form-label input-field floating-label">Last Name</label>
                                                </div>
                                            </div>
                                            <div class="col-lg-12">
                                                <div class="input-form">
                                                    <input type="email"  name="email" id="email" value="{{(isset($email)&&$email!="")?$email:""}}" class="form-control inputText" readonly>
                                                    <label for="Fname" class="form-label input-field floating-label">Email Address</label>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="rsvp-guest">
                                        <h5>Guests</h5>
                                        <div class="rsvp-guest-count">
                                        <div>
                                            <h6>Adults</h6>
                                            <div class="qty-container ms-auto">
                                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="adults" id="adults" value="0" class="input-qty">
                                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div>
                                            <h6>Kids</h6>
                                            <div class="qty-container ms-auto">
                                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="kids" id="kids" value="0" class="input-qty">
                                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="rsvp-msgbox">
                                        <h5>Message</h5>
                                        <div class="input-form">
                                            <textarea name="" id="" class="form-control inputText" id="message_to_host" name="message_to_host" message=""></textarea>
                                            <label for="Fname" class="form-label input-field floating-label">Message with your RSVP</label>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <span class="d-flex align-items-center">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.9987 1.3335C4.32536 1.3335 1.33203 4.32683 1.33203 8.00016C1.33203 11.6735 4.32536 14.6668 7.9987 14.6668C11.672 14.6668 14.6654 11.6735 14.6654 8.00016C14.6654 4.32683 11.672 1.3335 7.9987 1.3335ZM7.4987 5.3335C7.4987 5.06016 7.72536 4.8335 7.9987 4.8335C8.27203 4.8335 8.4987 5.06016 8.4987 5.3335V8.66683C8.4987 8.94016 8.27203 9.16683 7.9987 9.16683C7.72536 9.16683 7.4987 8.94016 7.4987 8.66683V5.3335ZM8.61203 10.9202C8.5787 11.0068 8.53203 11.0735 8.47203 11.1402C8.40536 11.2002 8.33203 11.2468 8.25203 11.2802C8.17203 11.3135 8.08536 11.3335 7.9987 11.3335C7.91203 11.3335 7.82536 11.3135 7.74536 11.2802C7.66536 11.2468 7.59203 11.2002 7.52536 11.1402C7.46536 11.0735 7.4187 11.0068 7.38536 10.9202C7.35203 10.8402 7.33203 10.7535 7.33203 10.6668C7.33203 10.5802 7.35203 10.4935 7.38536 10.4135C7.4187 10.3335 7.46536 10.2602 7.52536 10.1935C7.59203 10.1335 7.66536 10.0868 7.74536 10.0535C7.90536 9.98683 8.09203 9.98683 8.25203 10.0535C8.33203 10.0868 8.40536 10.1335 8.47203 10.1935C8.53203 10.2602 8.5787 10.3335 8.61203 10.4135C8.64536 10.4935 8.66536 10.5802 8.66536 10.6668C8.66536 10.7535 8.64536 10.8402 8.61203 10.9202Z" fill="#E2E8F0"/>
                                            </svg></span>
                                            <h6>This message will be visible to all guests.</h6>
                                        </div>
                                    </div>
                                    <div class="rsvp-yes-notification-wrp">
                                        <h5>Notifications</h5>
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="notifications[]" value="" id="flexCheckDefault">
                                            <label class="form-check-label" for="flexCheckDefault">
                                                All event activity <br>
                                                (Wall posts, potluck activity,  photo uploads, event updates, messages)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" name="notifications[]" type="checkbox" value="" id="flexCheckDefault1">
                                            <label class="form-check-label" for="flexCheckDefault1">
                                                All event activity <br>
                                            </label>
                                        </div>
                                    </div>
                    </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary yes_rsvp_btn" data-bs-dismiss="modal">RSVP</button>
                                </div>
                        </form>
                </div>
    </div>
</div>

<!-- about no rsvp Modal -->
<div class="modal fade cmn-modal about-rsvp rsvp-yes-modal rsvp-no-modal" id="rsvp-no-modal" tabindex="-1" aria-labelledby="aboutsuccessLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
       

            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <div>
                        <h4 class="modal-title" id="aboutsuccessLabel">RSVP’ing: <span>No</span></h4>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="{{ route('rsvp.store') }}" id="rsvpNoForm">
                    @csrf
                    <input type="hidden" value="{{encrypt($user_id)}}" name="user_id" id="user_id"/>
                    <input type="hidden" value="{{encrypt($event_id)}}" name="event_id" id="event_id"/>
                    <input type="hidden" value="0" name="rsvp_status" id="rsvp_status"/>
                        <div class="modal-body">
                            <div class="d-flex align-items-center rsvp-yes-profile">
                                @if ($eventInfo['guest_view']['user_profile'] != '')
                                    <img src="{{ $eventInfo['guest_view']['user_profile']}}" alt="rs-img" class="about-rs-img">
                                @else
                                @php
                                    $firstInitial = !empty($eventInfo['guest_view']['host_first_name']) ? strtoupper($eventInfo['guest_view']['host_first_name'][0]) : '';
                                    $lastInitial = !empty($eventInfo['guest_view']['host_last_name']) ? strtoupper($eventInfo['guest_view']['host_last_name'][0]) : '';
                                    $initials = $firstInitial . $lastInitial;
                                    $fontColor = 'fontcolor' . $firstInitial;
                                @endphp
                                    <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                @endif                                  <div>
                                        <h4 class="modal-title" id="aboutsuccessLabel">{{$eventInfo['guest_view']['event_name']}}</h4>
                                    <p>Hosted by: <span>{{$eventInfo['guest_view']['hosted_by']}}</span></p>
                                </div>
                            </div>
                        
                            <div class="rsvp-custom-radio guest-rsvp-attend">
                                <h6>Your Info</h6>
                                <div class="rsvp-input-form row">
                                    <div class="col-lg-6">
                                        <div class="input-form">
                                            <input type="text" name="firstname" id="firstname" class="form-control inputText" >
                                            <label for="Fname" class="form-label input-field floating-label">First Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-form">
                                            <input type="text" name="lastname" id="lastname" class="form-control inputText" >
                                            <label for="Fname" class="form-label input-field floating-label">Last Name</label>
                                        </div>
                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-form">
                                            <input type="email" name="email" value="{{(isset($email)&&$email!="")?$email:""}}" class="form-control inputText" readonly>
                                            <label for="Fname" class="form-label input-field floating-label">Email Address</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="rsvp-msgbox">
                                <h5>Message</h5>
                                <div class="input-form">
                                    <textarea name="" id="" class="form-control inputText" id="message_to_host" name="message_to_host" required=""></textarea>
                                    <label for="Fname" class="form-label input-field floating-label">Message with your RSVP</label>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="d-flex align-items-center">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.9987 1.3335C4.32536 1.3335 1.33203 4.32683 1.33203 8.00016C1.33203 11.6735 4.32536 14.6668 7.9987 14.6668C11.672 14.6668 14.6654 11.6735 14.6654 8.00016C14.6654 4.32683 11.672 1.3335 7.9987 1.3335ZM7.4987 5.3335C7.4987 5.06016 7.72536 4.8335 7.9987 4.8335C8.27203 4.8335 8.4987 5.06016 8.4987 5.3335V8.66683C8.4987 8.94016 8.27203 9.16683 7.9987 9.16683C7.72536 9.16683 7.4987 8.94016 7.4987 8.66683V5.3335ZM8.61203 10.9202C8.5787 11.0068 8.53203 11.0735 8.47203 11.1402C8.40536 11.2002 8.33203 11.2468 8.25203 11.2802C8.17203 11.3135 8.08536 11.3335 7.9987 11.3335C7.91203 11.3335 7.82536 11.3135 7.74536 11.2802C7.66536 11.2468 7.59203 11.2002 7.52536 11.1402C7.46536 11.0735 7.4187 11.0068 7.38536 10.9202C7.35203 10.8402 7.33203 10.7535 7.33203 10.6668C7.33203 10.5802 7.35203 10.4935 7.38536 10.4135C7.4187 10.3335 7.46536 10.2602 7.52536 10.1935C7.59203 10.1335 7.66536 10.0868 7.74536 10.0535C7.90536 9.98683 8.09203 9.98683 8.25203 10.0535C8.33203 10.0868 8.40536 10.1335 8.47203 10.1935C8.53203 10.2602 8.5787 10.3335 8.61203 10.4135C8.64536 10.4935 8.66536 10.5802 8.66536 10.6668C8.66536 10.7535 8.64536 10.8402 8.61203 10.9202Z" fill="#E2E8F0"/>
                                    </svg></span>
                                    <h6>This message will be visible to all guests.</h6>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary no_rsvp_btn" data-bs-dismiss="modal">RSVP</button>
                        </div>
                </form>
            </div>
        </form>
    </div>
</div>

<!-- about no rsvp Modal -->
<div class="modal fade cmn-modal about-rsvp rsvp-yes-modal" id="rsvp-guest-list-modal" tabindex="-1" aria-labelledby="aboutsuccessLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
      <div class="modal-content">
          <div class="modal-header">
              <div class="d-flex align-items-center">
                  <div>
                    <h4 class="modal-title" id="aboutsuccessLabel">Guest List ({{count($getInvitedusers['invited_user_id'])}} Guests)</h4>
                  </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="guest-user-list rsvp-guest-user-list-wrp">
                @foreach ($getInvitedusers['invited_user_id'] as $guest_data )

                    <div class="guest-user-box">
                        <div class="guest-list-data">
                        <a href="#" class="guest-img">
                            @if ($guest_data['profile'] != '')
                            <img src="{{$guest_data['profile']}}" alt="guest-img">
                            @else
                            @php
                                $firstInitial = !empty($guest_data['first_name']) ? strtoupper($guest_data['first_name'][0]) : '';
                                $lastInitial = !empty($guest_data['last_name']) ? strtoupper($guest_data['last_name'][0]) : '';
                                $initials = $firstInitial . $lastInitial;
                                $fontColor = 'fontcolor' . $firstInitial;
                            @endphp
                                 <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                        @endif                        </a>
                        <div class="w-100">
                            <div class="d-flex flex-column">
                                <a href="#" class="guest-name">{{$guest_data['first_name']}} {{$guest_data['last_name']}}</a>
                                <span class="guest-email">{{$guest_data['email']}}</span>
                            </div>
                            @if($guest_data['rsvp_status']=="1")
                            <div class="sucess-yes">
                            <h5 class="green">RSVP'D YES</h5>
                            <div class="sucesss-cat ms-auto">
                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                </svg>
                                <h5>{{$guest_data['adults']}} Adults</h5>
                                <h5>{{$guest_data['kids']}} Kids</h5>
                            </div>
                            </div>
                            @elseif ($guest_data['rsvp_status']=="1")
                            <div class="sucess-yes">
                                <h5 class="green">RSVP'D NO</h5>
                                <div class="sucesss-cat ms-auto">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                        </svg>
                                        <h5>{{$guest_data['adults']}} Adults</h5>
                                        <h5>{{$guest_data['kids']}} Kids</h5>
                                </div>
                             </div>
                            @else
                            <div class="sucess-yes">
                                <h5 class="green">NO Reply</h5>
                                <div class="sucesss-cat ms-auto">
                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                        </svg>
                                        <h5>{{$guest_data['adults']}} Adults</h5>
                                        <h5>{{$guest_data['kids']}} Kids</h5>
                                </div>
                             </div>
                            @endif
                            <div class="rsvp-guest-user-replay">
                                @if($guest_data['message_to_host']!="")
                                <h6>“ {{$guest_data['message_to_host']}} “</h6>
                            @endif
                         </div>
                        </div>
                        </div>
                        
                    </div>
                @endforeach
              {{-- <div class="guest-user-box">
                <div class="guest-list-data">
                  <a href="#" class="guest-img">
                    <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                  </a>
                  <div class="w-100">
                    <div class="d-flex flex-column">
                        <a href="#" class="guest-name">Pristia Candra</a>
                        <span class="guest-email">jamesclark@gmail.com</span>
                    </div>
                    
                    <div class="sucess-yes">
                    <h5 class="green">YES</h5>
                    <div class="sucesss-cat ms-auto">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                        </svg>
                        <h5>3 Adults</h5>
                        <h5>2 Kids</h5>
                    </div>
                    </div>
                    <div class="rsvp-guest-user-replay">
                        <h6>“ Thanks guys for the invite!  We’ll be there “</h6>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="guest-user-box">
                <div class="guest-list-data">
                  <a href="#" class="guest-img">
                    <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                  </a>
                  <div class="w-100">
                    <div class="d-flex flex-column">
                        <a href="#" class="guest-name">Pristia Candra</a>
                        <span class="guest-email">jamesclark@gmail.com</span>
                    </div>
                    
                    <div class="sucess-yes">
                    <h5 class="green">YES</h5>
                    <div class="sucesss-cat ms-auto">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                        </svg>
                        <h5>3 Adults</h5>
                        <h5>2 Kids</h5>
                    </div>
                    </div>
                    <div class="rsvp-guest-user-replay">
                        <h6>“ Thanks guys for the invite!  We’ll be there “</h6>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="guest-user-box">
                <div class="guest-list-data">
                  <a href="#" class="guest-img">
                    <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                  </a>
                  <div class="w-100">
                    <div class="d-flex flex-column">
                        <a href="#" class="guest-name">Pristia Candra</a>
                        <span class="guest-email">jamesclark@gmail.com</span>
                    </div>
                    
                    <div class="sucess-yes">
                    <h5 class="green">YES</h5>
                    <div class="sucesss-cat ms-auto">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                        </svg>
                        <h5>3 Adults</h5>
                        <h5>2 Kids</h5>
                    </div>
                    </div>
                    <div class="rsvp-guest-user-replay">
                        <h6>“ Thanks guys for the invite!  We’ll be there “</h6>
                    </div>
                  </div>
                </div>
                
              </div>
              <div class="guest-user-box">
                <div class="guest-list-data">
                  <a href="#" class="guest-img">
                    <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                  </a>
                  <div class="w-100">
                    <div class="d-flex flex-column">
                        <a href="#" class="guest-name">Pristia Candra</a>
                        <span class="guest-email">jamesclark@gmail.com</span>
                    </div>
                    
                    <div class="sucess-yes">
                    <h5 class="green">YES</h5>
                    <div class="sucesss-cat ms-auto">
                        <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                        <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                        </svg>
                        <h5>3 Adults</h5>
                        <h5>2 Kids</h5>
                    </div>
                    </div>
                    <div class="rsvp-guest-user-replay">
                        <h6>“ Thanks guys for the invite!  We’ll be there “</h6>
                    </div>
                  </div>
                </div>
                
              </div> --}}
            </div>
          </div>
      </div>
  </div>
</div>
</section>