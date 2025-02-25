   @php
        use Carbon\Carbon;
            $host_id=$eventInfo['guest_view']['host_id'];
            $host_name=$eventInfo['guest_view']['hosted_by'];
            $host_profile=$eventInfo['guest_view']['user_profile'];

            $co_host_id="";
            $co_host_name="";
            $co_host_profile="";

            
            if(!empty($eventInfo['guest_view']['co_hosts'])){
                $coHost = $eventInfo['guest_view']['co_hosts'][0];
                $co_host_id=$coHost['id'];
                $co_host_name=$coHost['name'];
                $co_host_profile=$coHost['profile'];
            }

            $firstname="";
            $lastname="";
            if(Auth::guard('web')->check()){
                    $user = Auth::guard('web')->user();
                    // dd($userId);
                    $firstname= $user->firstname;   
                    $lastname= $user->lastname;                
             
            }
    @endphp
<x-front.advertise />
<section class="rsvp-wrp new-main-content">
   <!-- ===main-section-start=== -->
   <div class="rsvp-tab-wrp event-center-tabs-main">
    <nav>
        <div class="nav nav-tabs" id="nav-tab" role="tablist">
            <button class="nav-link active" data-tab="invite_tab" id="nav-invite-tab" data-bs-toggle="tab" data-bs-target="#nav-invite" type="button" role="tab" aria-controls="nav-invite" aria-selected="true">
                Invite
            </button>
            @if (Auth::guard('web')->check()) 
            <button class="nav-link" id="nav-messaging-tab" data-bs-toggle="tab" data-bs-target="#nav-messaging" type="button" role="tab" aria-controls="nav-messaging" aria-selected="false">
                Messaging
            </button>
            @endif
            @php
                $userId = 0;
                if(Auth::guard('web')->check()){
                    $userId = Auth::guard('web')->user()->id;                    
                }
            @endphp
        </div>
    </nav>   
    <div class="container">     
        <div class="tab-content" id="nav-tabContent">
            {{-- <input type="text" value="{{$rsvp_status}}" /> --}}
            <div class="tab-pane fade show active" id="nav-invite" role="tabpanel" aria-labelledby="nav-invite-tab">
                <section class="rsvp-wrp">
                    <div class="container">
                        <div class="row">
                            <div class="col-lg-5 mb-lg-0 mb-sm-4 mb-md-4 mb-0">
                                <div class="rsvp-slider owl-carousel owl-theme {{($eventInfo['guest_view']['event_images']!="" && count($eventInfo['guest_view']['event_images']) > 1 )?'slider-count':''}} " >    
                                    @if ($eventInfo['guest_view']['event_images']!="")
                                        @foreach($eventInfo['guest_view']['event_images'] as $value)
                                        <div class="item">
                                            <button class="rsvp-zoom-btn" data-img="{{ asset($value)}}"><img src="{{asset('assets/front/image/rsvp-zoom-icon.svg')}}" alt=""></button>
                                            <div class="rsvp-img open-event-images" data-img="{{ asset($value)}}">
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
                                             <p><span>Hosted by: </span>{{ $eventInfo['guest_view']['hosted_by']}}</p>
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
                                            @php
                                                $eventDetails = $eventInfo['guest_view']['event_detail'];
                                                $chunkedDetails = array_chunk($eventDetails, 2); // Group items in pairs
                                            @endphp
                                        
                                            @foreach($chunkedDetails as $pair)
                                                <div class="d-flex align-items-center justify-content-between w-100 mb-2">
                                                    @foreach($pair as $val)
                                                        <h6>{{ $val }}</h6>
                                                    @endforeach
                                                </div>
                                            @endforeach
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
                                                 @php
                                                    $final_end_date="";
                                                     if($eventInfo['guest_view']['event_end_date']!=""){
                                                        $event_end_date=Carbon::parse($eventInfo['guest_view']['event_end_date'])->format('F j, Y');
                                                        $final_end_date='to '.$event_end_date;
                                                     }
                                                     if($eventInfo['guest_view']['event_end_date']==$eventInfo['guest_view']['event_date']){
                                                        $final_end_date="";
                                                     }
                                                 @endphp
                                                 <h3>{{Carbon::parse($eventInfo['guest_view']['event_date'])->format('F j, Y')}} {{$final_end_date}}</h3>
                                                 <input type="hidden" id="eventDate" name="eventDate" value="{{$eventInfo['guest_view']['event_date']}}">
                                                 <input type="hidden" id="eventEndDate" name="eventDate" value="{{(isset($eventInfo['guest_view']['event_end_date']) && $eventInfo['guest_view']['event_end_date']!="")?$eventInfo['guest_view']['event_end_date']:''}}">
                                                 <input type="hidden" id="eventTime" name="eventTime" value="{{ $eventInfo['guest_view']['event_time'] }}">
                                                 <input type="hidden" id="eventName" name="eventName" value="{{$eventInfo['guest_view']['event_name']}}">
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
                                                @php
                                                    $final_end_time="";
                                                     if($eventInfo['guest_view']['event_end_time']!=""){
                                                        $final_end_time='to '.$eventInfo['guest_view']['event_end_time'];
                                                     }
                                                 @endphp
                                                 <h6>Time</h6>
                                                 {{-- <h3>8:00 to 10:00PM</h3> --}}
                                                 <h3>{{ $eventInfo['guest_view']['event_time'] }} {{$final_end_time}}</h3>
                                             </div>
                                             <input type="hidden" id="eventEndTime" name="eventEndTime" value="{{(isset($eventInfo['guest_view']['event_end_time']) && $eventInfo['guest_view']['event_end_time']!=""?$eventInfo['guest_view']['event_end_time']:'' )}}">
                                             </div>
                                         </div>
                                         <div class="accordion rsvp-calender-accordion" id="accordionExample">
                                            <div class="accordion-item">
                                              <h2 class="accordion-header" id="headingOne">
                                                <button class="accordion-button add-calender btn" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                                                    Add to calendar 
                                                  <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M5.33203 8H10.6654" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    <path d="M8 10.6668V5.3335" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                </button>
                                              </h2>
                                              <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    {{-- <div class="rsvp-calender-body-head">
                                                        <h3>
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M5.33203 8H10.6654" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                <path d="M8 10.6668V5.3335" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                            </svg>
                                                            ADD To
                                                        </h3>
                                                    </div> --}}
                                                 
                                                    <a href="javascript:;" id="openOutlook" class="add-calender btn">
                                                        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M30.4453 12.4023V20.9602L33.4391 22.843C33.5471 22.8667 33.6591 22.8667 33.7672 22.843L46.6391 14.1648C46.6297 13.7437 46.4756 13.3386 46.2027 13.0176C45.9298 12.6966 45.5548 12.4794 45.1406 12.4023H30.4453Z" fill="#0072C6"/>
                                                            <path d="M30.4469 24.1531L33.1766 26.0281C33.3044 26.106 33.4511 26.1471 33.6008 26.1471C33.7504 26.1471 33.8972 26.106 34.025 26.0281C33.5563 26.3109 46.6391 17.625 46.6391 17.625V33.3531C46.6732 33.6718 46.6373 33.9941 46.5338 34.2975C46.4303 34.6009 46.2618 34.878 46.04 35.1094C45.8183 35.3408 45.5486 35.521 45.25 35.6373C44.9513 35.7536 44.6308 35.8033 44.3109 35.7828H30.4453L30.4469 24.1531Z" fill="#0072C6"/>
                                                            <path d="M16.3145 20.2071C15.861 20.2049 15.4153 20.3255 15.0247 20.556C14.6341 20.7865 14.3131 21.1184 14.0958 21.5165C13.5022 22.5718 13.2174 23.7727 13.2739 24.9821C13.2122 26.189 13.4974 27.3884 14.0958 28.4384C14.3122 28.8201 14.6252 29.1381 15.0035 29.3605C15.3818 29.5829 15.8119 29.7018 16.2507 29.7053C16.6894 29.7087 17.1214 29.5967 17.5032 29.3803C17.8849 29.164 18.203 28.851 18.4254 28.4727C19.0173 27.4276 19.2966 26.2345 19.2301 25.0352C19.2979 23.7983 19.0278 22.5663 18.4489 21.4712C18.242 21.0861 17.9338 20.7648 17.5577 20.542C17.1815 20.3192 16.7517 20.2034 16.3145 20.2071Z" fill="#0072C6"/>
                                                            <path d="M3.36719 8.05469V41.5344L28.8359 46.875V3.125L3.36719 8.05469ZM20.4109 30.4547C19.9345 31.1263 19.301 31.6713 18.5657 32.0419C17.8303 32.4126 17.0155 32.5977 16.1922 32.5812C15.3896 32.5955 14.5954 32.4157 13.8773 32.0572C13.1591 31.6986 12.5381 31.1719 12.0672 30.5219C10.9503 28.9625 10.3916 27.0722 10.4812 25.1563C10.3862 23.1463 10.9551 21.1609 12.1 19.5063C12.5825 18.8218 13.2259 18.2665 13.9736 17.8894C14.7213 17.5122 15.5503 17.3249 16.3875 17.3438C17.1843 17.3278 17.9728 17.5076 18.6838 17.8675C19.3949 18.2274 20.0067 18.7563 20.4656 19.4078C21.5714 21.001 22.1206 22.9145 22.0281 24.8516C22.1255 26.847 21.5566 28.8181 20.4109 30.4547Z" fill="#0072C6"/>
                                                        </svg>
                                                        Outlook Calendar
                                                   </a>
                                                   <a href="javascript:;" id="openApple" class="add-calender btn">
                                                        <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path fill-rule="evenodd" clip-rule="evenodd" d="M32.6772 7.98218C34.4999 5.8697 35.7285 2.92745 35.3923 0C32.7657 0.1 29.588 1.67787 27.7047 3.78784C26.0134 5.66032 24.5371 8.65224 24.934 11.5222C27.8639 11.7397 30.8545 10.0971 32.6772 7.98218ZM39.2475 26.5625C39.3208 34.1299 46.1742 36.6472 46.25 36.6797C46.1944 36.8572 45.1554 40.2666 42.64 43.7915C40.4634 46.8365 38.2059 49.8691 34.649 49.9341C31.1554 49.9966 30.0304 47.9492 26.0337 47.9492C22.0394 47.9492 20.7906 49.8688 17.484 49.9963C14.051 50.1188 11.4345 46.7019 9.24274 43.667C4.75809 37.4596 1.33266 26.1252 5.93361 18.4753C8.21891 14.6779 12.3016 12.2695 16.7357 12.2095C20.1055 12.147 23.2883 14.3823 25.3486 14.3823C27.4089 14.3823 31.2767 11.6948 35.3417 12.0898C37.0431 12.1573 41.821 12.7471 44.8874 17.0496C44.6397 17.1971 39.1868 20.2376 39.2475 26.5625Z" fill="black"/>
                                                        </svg>
                                                        Apple Calendar
                                                   </a>
                                                   <a href="javascript:;" id="openGoogle" class="add-calender btn">
                                                    <svg width="50" height="50" viewBox="0 0 50 50" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M46.8739 25.4839C46.8739 23.6853 46.725 22.3728 46.4027 21.0117H25.4453V29.1297H37.7469C37.4989 31.1472 36.1595 34.1853 33.1833 36.2269L33.1416 36.4987L39.768 41.5294L40.227 41.5742C44.4433 37.7583 46.8739 32.1436 46.8739 25.4839Z" fill="#4285F4"/>
                                                        <path d="M25.4483 46.873C31.475 46.873 36.5344 44.9284 40.23 41.5742L33.1862 36.2269C31.3014 37.5152 28.7715 38.4144 25.4483 38.4144C19.5456 38.4144 14.5357 34.5984 12.7498 29.3242L12.488 29.3459L5.59792 34.5717L5.50781 34.8172C9.17841 41.963 16.7181 46.873 25.4483 46.873Z" fill="#34A853"/>
                                                        <path d="M12.7479 29.3266C12.2767 27.9655 12.004 26.5071 12.004 25.0002C12.004 23.4932 12.2767 22.0349 12.7231 20.6738L12.7106 20.3838L5.73414 15.0742L5.50589 15.1806C3.99306 18.146 3.125 21.4758 3.125 25.0002C3.125 28.5244 3.99306 31.8543 5.50589 34.8196L12.7479 29.3266Z" fill="#FBBC05"/>
                                                        <path d="M25.4484 11.5833C29.6398 11.5833 32.4672 13.3576 34.0794 14.8403L40.3789 8.8125C36.51 5.2882 31.4751 3.125 25.4484 3.125C16.7181 3.125 9.17842 8.03469 5.50781 15.1805L12.7251 20.6736C14.5357 15.3993 19.5456 11.5833 25.4484 11.5833Z" fill="#EB4335"/>
                                                    </svg>
                                                       Google Calendar 
                                                </a>
                                                </div>
                                              </div>
                                            </div>

                                          </div>
                                            
                                            <!-- <a href="javascript:;" id="openGoogle" class="add-calender btn">Add to calendar 
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M5.33203 8H10.6654" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6668V5.3335" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a>
                                            <a href="javascript:;" id="openOutlook" class="add-calender btn">Add to OultLook Calender
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M5.33203 8H10.6654" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6668V5.3335" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a>
                                            <a href="javascript:;" id="openApple" class="add-calender btn">Add to Apple Calender
                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M5.33203 8H10.6654" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M8 10.6668V5.3335" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                </svg>
                                            </a> -->
                                     </div>
                                     <div class="host-users-detail rsvp-host-users-detail cmn-card">
                                     @if(!empty($eventInfo['guest_view']['co_hosts']))
                                        <h4 class="title">Your hosts</h4>
                                      @else
                                        <h4 class="title">Your host</h4>
                                      @endif  

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
                                                @if (Auth::guard('web')->check()) 
                                                    @if($is_host=="")
                                                         <a href="javascript:;" class="msg-btn host-msg">Message</a>
                                                    @endif
                                                @endif
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
                                                <h5>{{$coHost['first_name']}} {{$coHost['last_name']}}</h5>
                                                <span>Co-host</span>
                                                @if (Auth::guard('web')->check()) 
                                                <a href="#" class="msg-btn chost-msg">Message</a>
                                                @endif
                                            </div>
                                            @endif
                                         </div>
                                         {{-- <p>“Thanks everyone for RSVP'ing on time.<br> I hope everyone can make it to this special day of ours!”</p> --}}
                                         <p>{{ $eventInfo['guest_view']['message_to_guests'] != "" ? '"' . $eventInfo['guest_view']['message_to_guests'] . '"' : "" }}</p>
                                        </div>
                                     <div class="location-wrp cmn-card rsvp-location-wrp">
                                         <h4 class="title">Event Location</h4>
                                         <h5>{{$eventInfo['guest_view']['event_location_name']}}</h5>
                                         {{-- <p>2369 Graystone Lakes Maconey, CA 90210</p> --}}
                                         @if($eventInfo['guest_view']['address_1']!="")
                                         <p class="evnt-address">{{$eventInfo['guest_view']['address_1']}} {{$eventInfo['guest_view']['city']}}, {{$eventInfo['guest_view']['state']}} {{$eventInfo['guest_view']['zip_code']}}</p>
                                         <input type="hidden" id="event_latitude" value="{{$eventInfo['guest_view']['latitude']}}"/>
                                        <input type="hidden" id="event_logitude" value="{{$eventInfo['guest_view']['logitude']}}"/>
                                        <input type="hidden" id="event_address" value="{{$eventInfo['guest_view']['address_1']}}"/>
                                         <div id="map">

                                                    {{-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3153.835434509374!2d144.9630579153168!3d-37.81410797975195!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x6ad642af0f11fd81%3A0xf577d1b1f5f1f1f1!2sFederation%20Square!5e0!3m2!1sen!2sau!4v1611815623456!5m2!1sen!2sau" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy"></iframe> --}}
                                                    {{-- <iframe 
                                                    src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d14686.389646042744!2d72.511726!3d23.049736!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0x0!2zMjPCsDAyJzU5LjEiTiA3MsKwMzAnMzIuMjkiRQ!5e0!3m2!1sen!2sin!4v1735623416555!5m2!1sen!2sin" 
                                                    width="600" 
                                                    height="450" 
                                                    style="border:0;" 
                                                    allowfullscreen="" 
                                                    loading="lazy" 
                                                    referrerpolicy="no-referrer-when-downgrade">
                                                    </iframe>
                                                        <img src="./assets/img/location-marker.svg" alt="marker" class="marker"> --}}
                                         </div>
                                         <a href="#" class="direction-btn" data-lat="{{$eventInfo['guest_view']['latitude']}}" data-long="{{$eventInfo['guest_view']['logitude']}}">Directions</a>
                                         @endif
                                     </div>
                                     <div class="guest-user-list rsvp-guest-user-list-wrp cmn-card">
                                         <div class="rsvp-guest-user-list-title">
                                           <!-- <h5 class="heading">Guest List ({{ count($getInvitedusers['all_invited_users'] ?? []) }}
                                            Guests)</h5> -->
                                           <h5 class="heading">Guest List</h5>
                                           <a href="#" data-bs-toggle="modal" data-bs-target="#rsvp-guest-list-modal">See All</a>
                                         </div>
                                         <div>
                                    @php
                                        $i=0;
                                    @endphp
                                    @foreach ($getInvitedusers['all_invited_users'] as $guest_data )
                                            @php
                                            
                                            $yes_modal="";
                                            $no_modal="";
                                        
                                            if($user_id==$guest_data['id'])
                                            {
                                                    $yes_modal="#rsvp-yes-modal";
                                                    $no_modal="#rsvp-no-modal"; 
                                            }
                                            if(empty($user_id) && !empty($sync_contact_user_id) && $sync_contact_user_id == $guest_data['id'])
                                            {
                                                    $yes_modal="#rsvp-yes-modal";
                                                    $no_modal="#rsvp-no-modal"; 
                                            }
                                            if($guest_data['rsvp_status']=="1"){
                                                $open_modal=$no_modal;
                                            }elseif($guest_data['rsvp_status']=="0"){
                                                $open_modal=$yes_modal;
                                            }else{
                                                $open_modal="";
                                            }
                                            if($user_id==$guest_data['id']){
                                                $self_status=$guest_data['id'];
                                            }
                                            @endphp

                                             @if($is_host == "1" || 
                                             ($user_id == $guest_data['id']) || 
                                             ($user_id != $guest_data['id'] && ($guest_data['rsvp_status'] == "1" || $guest_data['rsvp_status'] == "0")))
                                            @php
                                                $i++;
                                            @endphp
                                           <div class="guest-user-box">
                                                <div class="guest-list-data">
                                                    <div class="guest-img">
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
                                                    </div>
                                                    <div class="w-100">
                                                    <div class="d-flex align-items-center justify-content-between">
                                                        <div class="d-flex flex-column">
                                                        <p href="#" class="guest-name">{{$guest_data['first_name']}} {{$guest_data['last_name']}}</p>
                                                        <span class="guest-email">{{($guest_data['email']!="")?$guest_data['email']:$guest_data['phone_number']}}</span>
                                                        </div>
                                                        @if($rsvp_status!="" &&($user_id==$guest_data['id']))
                                                            <button class="guest-list-edit-btn" data-bs-toggle="modal" data-bs-target={{$open_modal}}>
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.16406 1.66602H7.4974C3.33073 1.66602 1.66406 3.33268 1.66406 7.49935V12.4993C1.66406 16.666 3.33073 18.3327 7.4974 18.3327H12.4974C16.6641 18.3327 18.3307 16.666 18.3307 12.4993V10.8327" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12.4219 3.45898C12.9802 5.45065 14.5385 7.00898 16.5385 7.57565" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                        @if($rsvp_status!="" && empty($user_id) && !empty($sync_contact_user_id) && $sync_contact_user_id == $guest_data['id'])
                                                            <button class="guest-list-edit-btn" data-bs-toggle="modal" data-bs-target={{$open_modal}}>
                                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M9.16406 1.66602H7.4974C3.33073 1.66602 1.66406 3.33268 1.66406 7.49935V12.4993C1.66406 16.666 3.33073 18.3327 7.4974 18.3327H12.4974C16.6641 18.3327 18.3307 16.666 18.3307 12.4993V10.8327" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                <path d="M12.4219 3.45898C12.9802 5.45065 14.5385 7.00898 16.5385 7.57565" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                                </svg>
                                                            </button>
                                                        @endif
                                                    </div>
                                                    
                                                @if($is_host=="1")
                                                    @if($guest_data['rsvp_status']=="1")
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                                $read="";
                                                                $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                            
                                                            @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="sucess-yes" data-bs-toggle="modal" data-bs-target={{$no_modal}}>
                                                        <h5 class="green">RSVP'd YES</h5>
                                                        <div class="sucesss-cat ms-auto">
                                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                            </svg>
                                                            {{-- <h5>{{$guest_data['adults']}} Adults</h5> --}}
                                                            {{-- <h5>{{$guest_data['kids']}} Kids</h5> --}}

                                                            <h5>
                                                                {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                            </h5>
                                                            <h5>
                                                                {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    @elseif ($guest_data['rsvp_status']=="0")
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                                $read="";
                                                                $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                            
                                                            @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="sucess-no" data-bs-toggle="modal" data-bs-target={{$yes_modal}}>
                                                        <h5>NO</h5>
                                                    </div>
                                                    @else
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                            $read="";
                                                            $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                            @endphp
                                                            <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>

                                                        {{-- <h5 class="ms-auto">Read, RSVP’d</h5> --}}
                                                        </div>
                                                    </div>
                                                    <div class="no-reply">
                                                        <h5>RSVP Not Received</h5>
                                                    </div>
                                                    @endif
                                                @elseif($user_id==$guest_data['id'])
                                                    @if($guest_data['rsvp_status']=="1")
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                                $read="";
                                                                $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                                
                                                            @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="sucess-yes" data-bs-toggle="modal" data-bs-target={{$no_modal}}>
                                                        <h5 class="green">RSVP'd YES</h5>
                                                        <div class="sucesss-cat ms-auto">
                                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                            </svg>
                                                            {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                                            <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                                            <h5>
                                                                {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                            </h5>
                                                            <h5>
                                                                {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    @elseif ($guest_data['rsvp_status']=="0")
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                                $read="";
                                                                $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                                
                                                            @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="sucess-no" data-bs-toggle="modal" data-bs-target={{$yes_modal}}>
                                                        <h5>NO</h5>
                                                    </div>
                                                    @else
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                            $read="";
                                                            $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                            @endphp
                                                            <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>

                                                        {{-- <h5 class="ms-auto">Read, RSVP’d</h5> --}}
                                                        </div>
                                                    </div>
                                                    <div class="no-reply">
                                                        <h5>RSVP Not Received</h5>
                                                    </div>
                                                    @endif
                                                @elseif($rsvp_status!="" && empty($user_id) && !empty($sync_contact_user_id) && $sync_contact_user_id == $guest_data['id'])
                                                    @if($guest_data['rsvp_status']=="1")
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                                $read="";
                                                                $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                                
                                                            @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="sucess-yes" data-bs-toggle="modal" data-bs-target={{$no_modal}}>
                                                        <h5 class="green">RSVP'd YES</h5>
                                                        <div class="sucesss-cat ms-auto">
                                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                            <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                            </svg>
                                                            {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                                            <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                                            <h5>
                                                                {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                            </h5>
                                                            <h5>
                                                                {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                            </h5>
                                                        </div>
                                                    </div>
                                                    @elseif ($guest_data['rsvp_status']=="0")
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                                $read="";
                                                                $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                                
                                                            @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                        </div>
                                                    </div>
                                                    <div class="sucess-no" data-bs-toggle="modal" data-bs-target={{$yes_modal}}>
                                                        <h5>NO</h5>
                                                    </div>
                                                    @else
                                                    <div class="sucess-rsvp-wrp">
                                                        <div class="d-flex align-items-center">
                                                        <h5 class="green d-flex align-items-center">
                                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                            <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                            <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                            <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                            </svg> Successful</h5>
                                                            @php
                                                            $read="";
                                                            $rsvp="";
                                                                if($guest_data['read']=="1"){
                                                                    $read="Read";
                                                                }
                                                                if($guest_data['rsvp_d']=="1"){
                                                                    $rsvp=", RSVP’d";
                                                                }
                                                            @endphp
                                                            <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>

                                                        {{-- <h5 class="ms-auto">Read, RSVP’d</h5> --}}
                                                        </div>
                                                    </div>
                                                    <div class="no-reply">
                                                        <h5>RSVP Not Received</h5>
                                                    </div>
                                                    @endif    
                                                @else
                                                    @if($guest_data['rsvp_status']=="1")
                                                        <div class="sucess-rsvp-wrp">
                                                            <div class="d-flex align-items-center">
                                                            <h5 class="green d-flex align-items-center">
                                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                                <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                                <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                                <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                                </svg> Successful</h5>
                                                                @php
                                                                    $read="";
                                                                    $rsvp="";
                                                                    if($guest_data['read']=="1"){
                                                                        $read="Read";
                                                                    }
                                                                    if($guest_data['rsvp_d']=="1"){
                                                                        $rsvp=", RSVP’d";
                                                                    }
                                                                    
                                                                @endphp
                                                            <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                            </div>
                                                        </div>
                                                        <div class="sucess-yes" data-bs-toggle="modal" data-bs-target={{$no_modal}}>
                                                            <h5 class="green">RSVP'd YES</h5>
                                                            <div class="sucesss-cat ms-auto">
                                                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                                <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                                <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                                <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                                </svg>
                                                                {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                                                <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                                                <h5>
                                                                    {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                                </h5>
                                                                <h5>
                                                                    {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                                </h5>
                                                            </div>
                                                        </div>
                                                        @elseif ($guest_data['rsvp_status']=="0")
                                                        <div class="sucess-rsvp-wrp">
                                                            <div class="d-flex align-items-center">
                                                            <h5 class="green d-flex align-items-center">
                                                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                                <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                                <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                                <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                                </svg> Successful</h5>
                                                                @php
                                                                    $read="";
                                                                    $rsvp="";
                                                                    if($guest_data['read']=="1"){
                                                                        $read="Read";
                                                                    }
                                                                    if($guest_data['rsvp_d']=="1"){
                                                                        $rsvp=", RSVP’d";
                                                                    }
                                                                    
                                                                @endphp
                                                            <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                            </div>
                                                        </div>
                                                        <div class="sucess-no" data-bs-toggle="modal" data-bs-target={{$yes_modal}}>
                                                            <h5>NO</h5>
                                                        </div>
                                                        @endif   
                                                    @endif

                                                <div class="rsvp-guest-user-replay">
                                                    @if($guest_data['message_to_host']!="")
                                                        <h6>“ {{$guest_data['message_to_host']}} “</h6>
                                                    @endif
                                                    </div>
                                                    </div>
                                                </div>
                                                
                                                </div>
                                                @endif
                                            @php
                                            if($i==3){
                                                break;
                                            }
                                            @endphp
                                    @endforeach
                                            {{-- @foreach ($getInvitedusers['invited_guests'] as $guest_data )
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
                                                
                                                
                                                  @if($guest_data['rsvp_status']=="1")
                                                  <div class="sucess-rsvp-wrp">
                                                    <div class="d-flex align-items-center">
                                                      <h5 class="green d-flex align-items-center">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                        <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                        <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                        <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                        </svg> Succesfull</h5>
                                                        @php
                                                            $read="";
                                                            $rsvp="";
                                                            if($guest_data['read']=="1"){
                                                                $read="Read";
                                                            }
                                                            if($guest_data['rsvp_d']=="1"){
                                                                $rsvp=", RSVP’d";
                                                            }
                                                        @endphp
                                                      <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
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
                                                  @elseif ($guest_data['rsvp_status']=="0")
                                                  <div class="sucess-rsvp-wrp">
                                                    <div class="d-flex align-items-center">
                                                      <h5 class="green d-flex align-items-center">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                        <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                        <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                        <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                        </svg> Succesful</h5>.
                                                        @php
                                                            $read="";
                                                            $rsvp="";
                                                            if($guest_data['read']=="1"){
                                                                $read="Read";
                                                            }
                                                            if($guest_data['rsvp_d']=="1"){
                                                                $rsvp=", RSVP’d";
                                                            }
                                                        @endphp
                                                      <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>
                                                    </div>
                                                </div>
                                                <div class="sucess-no">
                                                    <h5>NO</h5>
                                                </div>
                                                  @else
                                                  <div class="sucess-rsvp-wrp">
                                                    <div class="d-flex align-items-center">
                                                      <h5 class="green d-flex align-items-center">
                                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z" fill="#23AA26"></path>
                                                        <path d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z" fill="#23AA26"></path>
                                                        <path d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z" fill="#23AA26"></path>
                                                        <path d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z" fill="#23AA26"></path>
                                                        </svg> Succesful</h5>
                                                        @php
                                                        $read="";
                                                        $rsvp="";
                                                            if($guest_data['read']=="1"){
                                                                $read="Read";
                                                            }
                                                            if($guest_data['rsvp_d']=="1"){
                                                                $rsvp=", RSVP’d";
                                                            }
                                                         @endphp
                                                        <h5 class="ms-auto">{{$read}}{{$rsvp}}</h5>

                                                    </div>
                                                </div>
                                                  <div class="no-reply">
                                                    <h5>RSVP Not Received</h5>
                                                  </div>
                                                  @endif

                                                  <div class="rsvp-guest-user-replay">
                                                        @if(isset($guest_data['message_to_host'])&&$guest_data['message_to_host']!="")
                                                            <h6>“ {{$guest_data['message_to_host']}} “</h6>
                                                        @endif
                                                  </div>
                                                </div>
                                              </div>
                                              
                                            </div>
                                            @endforeach --}}
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
                                         @php
                                            $event_start_time=$eventInfo['guest_view']['event_timings']['start'];
                                            $event_end_time="";
                                            if($eventInfo['guest_view']['event_timings']['end']!=""){
                                                $event_end_time=' - '.$eventInfo['guest_view']['event_timings']['end'];
                                            }
                                         @endphp
                                         <span class="timing">{{$event_start_time}}{{$event_end_time}}</span>
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
                                             @php
                                                 $colors=['yellow','pink','blue'];
                                                 $colorIndex = 0;
                                             @endphp
                                             @foreach ($eventInfo['guest_view']['event_schedule'] as $schedule )

                                             @php
                                                 $colorClass = $colors[$colorIndex % count($colors)];
                                                 $colorIndex++;
                                             @endphp
                                               @if($schedule['type']=="1")
                                                    <div class="shedule-manage-timing">
                                                        <div class="shedule-timing">
                                                            <h6>{{$schedule['start_time']}}</h6>
                                                        </div>
                                                        <div class="shedule-box green">
                                                            <div class="shedule-box-left">
                                                            <h6>{{$schedule['activity_title']}}</h6>
                                                            {{-- @if($schedule['end_time']) --}}
                                                            <span>{{$schedule['start_time']}}</span>
                                                            {{-- @endif       --}}
                                                                                                              </div>
                                                            <span class="hrs ms-auto">{{$schedule['total_time']}}</span>
                                                        </div>
                                                        <img src="{{asset('assets/front/image/timing-line.svg')}}" alt="timing">
                                                    </div>
                                                @elseif ($schedule['type']=="2")
                                                    <div class="shedule-manage-timing">
                                                        <div class="shedule-timing">
                                                            <h6>{{$schedule['start_time']}}</h6>
                                                        </div>
                                                        <div class="shedule-box {{$colorClass}}">
                                                            <div class="shedule-box-left">
                                                                <h6>{{$schedule['activity_title']}}</h6>
                                                                @if($schedule['end_time'])
                                                                <span>{{$schedule['start_time']}} - {{$schedule['end_time']}}</span>
                                                                @endif
                                                            </div>
                                                            <span class="hrs ms-auto">{{$schedule['total_time']}}</span>
                                                        </div>
                                                        <img src="{{asset('assets/front/image/timing-line.svg')}}" alt="timing">
                                                        </div>
                                               @else

                                               @if($schedule['end_time'])
                                                    <div class="shedule-manage-timing">
                                                        <div class="shedule-timing">
                                                            <h6>{{$schedule['end_time']}}</h6>
                                                        </div>

                                                        <div class="shedule-box red">
                                                            <div class="shedule-box-left">
                                                                @if($schedule['end_time'])
                                                                <h6>{{$schedule['activity_title']}}</h6>
                                                                <span>{{$schedule['end_time']}}</span>
                                                                @endif                                    
                                                                                    </div>
                                                            <span class="hrs ms-auto">{{$schedule['total_time']}}</span>
                                                        </div>
                                                    </div>
                                                    @endif
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
                                                <a href="{{$gift['registry_link']}}" target="_blank">

                                                <div class="target d-flex gap-3 align-items-center">

                                                    @php
                                                    $url=$gift['registry_link'];
                                                    $logo="";
                                                    if(strpos($url, 'amazon') !== false ||strpos($url, 'Amazon') !== false){
                                                        $logo=asset('assets/amazon.png');
                                                    }elseif (strpos($url, 'target') !== false ||strpos($url, 'Target') !== false) {
                                                        $logo=asset('assets/target.png');
                                                    }else{
                                                        $logo=asset('assets/other_first.png');
                                                    }

                                                    @endphp
                                                   <img src="{{$logo}}" alt="">
                                                    <div>
                                                        <h5>{{$gift['registry_recipient_name']}}</h5>
                                                        <p>View their wish list</p>
                                                    </div>
                                                </div>
                                            </a>
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
                                         @if(Auth::guard('web')->check())
                                            <h5><span>Note:</span> This is a Potluck Event</h5>
                                        @else
                                            <h5><span>Note:</span> This is a Potluck Event</h5>
                                            <p>Sign Up on iOS or Android Apps to let them know what you will be brining.</p>
                                        @endif

                                        </div>
                                     @endif
                                 </div>
                            </div>
                        </div>
                    </div>
                </section>
            @if($is_host=="")    
                @if($rsvp_status==null || $rsvp_status=="")
                    <div class="rsvp-footer-btn-wrp">
                        <div class="container">
                            <div class="rsvp-footer-btn">
                                <div class="d-flex align-items-center justify-content-end gap-3 w-100">
                                    <h3>RSVP To This Event</h3>
                                    <button class="cmn-btn check_rsvp_yes" data-sync_id="{{($sync_contact_user_id!="")?encrypt($sync_contact_user_id):""}}" data-event_id="{{encrypt($event_id)}}" data-user_id="{{encrypt($user_id)}}" data-bs-toggle="modal" data-bs-target="#rsvp-yes-modal">Yes</button>
                                    <button class="cmn-btn cmn-no-btn check_rsvp_no" data-sync_id="{{($sync_contact_user_id!="")?encrypt($sync_contact_user_id):""}}" data-event_id="{{encrypt($event_id)}}" data-user_id="{{encrypt($user_id)}}"  data-bs-toggle="modal" data-bs-target="#rsvp-no-modal">No</button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            @endif        
            </div>
            <input type="hidden" id="host_id" class="host_id" value="{{$host_id}}"/>
            <input type="hidden" id="host_name" class="host_name" value="{{$host_name}}"/>
            <input type="hidden" id="host_profile" class="host_profile" value="{{$host_profile}}"/>

            <input type="hidden" id="co_host_id" class="co_host_id" value="{{$co_host_id}}"/>
            <input type="hidden" id="co_host_name" class="co_host_name" value="{{$co_host_name}}"/>
            <input type="hidden" id="co_host_profile" class="co_host_profile" value="{{$co_host_profile}}"/>
            <div class="tab-pane fade" id="nav-messaging" role="tabpanel" aria-labelledby="nav-messaging-tab">
                    @if(($rsvp_status=="" && $is_host=="")||($rsvp_status==null &&  $is_host==""))
                            <div class="rsvp-no-msg-wrp">
                                <h3>Messages</h3>
                                <div class="rsvp-no-msg-extra-text">
                                    <p><i class="fa-solid fa-circle-exclamation"></i> To participate in this conversation, you must RSVP.</p>
                                </div>
                            </div>
                            <div class="rsvp-footer-btn-wrp">
                                <div class="container">
                                    <div class="rsvp-footer-btn">
                                        <div class="d-flex align-items-center justify-content-end gap-3 w-100">
                                            <h3>RSVP To This Event</h3>
                                            <button class="cmn-btn check_rsvp_yes" data-sync_id="{{($sync_contact_user_id!="")?encrypt($sync_contact_user_id):""}}" data-event_id="{{encrypt($event_id)}}" data-user_id="{{encrypt($user_id)}}" data-bs-toggle="modal" data-bs-target="#rsvp-yes-modal">Yes</button>
                                            <button class="cmn-btn cmn-no-btn check_rsvp_no" data-sync_id="{{($sync_contact_user_id!="")?encrypt($sync_contact_user_id):""}}" data-event_id="{{encrypt($event_id)}}" data-user_id="{{encrypt($user_id)}}"  data-bs-toggle="modal" data-bs-target="#rsvp-no-modal">No</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                @else
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="massage-notification">
                                {{-- <div class="d-flex align-items-center">
                                    <h5>Messages</h5>
                                    <span class="badge" style="display: none"></span>
                                </div> --}}
                                {{-- <a href="#" class="cmn-btn edit-btn" id="new-message" data-bs-toggle="modal" data-bs-target="#msgBox">
                                    <svg class="me-1" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.4974 18.3327C15.0807 18.3327 18.8307 14.5827 18.8307 9.99935C18.8307 5.41602 15.0807 1.66602 10.4974 1.66602C5.91406 1.66602 2.16406 5.41602 2.16406 9.99935C2.16406 14.5827 5.91406 18.3327 10.4974 18.3327Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M7.16406 10H13.8307" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M10.5 13.3327V6.66602" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg> New Message
                                </a> --}}
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="chat-area">
                                <div class="chat-lists message-chat-lists">
                                    <div class="chat-header">
                                        <div class="position-relative chat-header-searchbar">
                                            <input type="text" placeholder="Search message" name="search_user_from_list" id="serach_user_from_list">
                                            <svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10.0807 17.4993C14.453 17.4993 17.9974 13.9549 17.9974 9.58268C17.9974 5.21043 14.453 1.66602 10.0807 1.66602C5.70847 1.66602 2.16406 5.21043 2.16406 9.58268C2.16406 13.9549 5.70847 17.4993 10.0807 17.4993Z" stroke="#050505" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18.8307 18.3327L17.1641 16.666" stroke="#050505" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class=" chat-functions d-none">
                                            <div class="d-flex align-items-center">
                                                <span class="me-3 bulk-back">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.07 5.92969L4 11.9997L10.07 18.0697" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M21.0019 12H4.17188" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                                <h6 class="check-counter"></h6>
                                            </div>
                                            <div class="ms-auto d-flex gap-3">
                                                <span class="multi-read">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M12.5019 16.3299C10.1119 16.3299 8.17188 14.3899 8.17188 11.9999C8.17188 9.60992 10.1119 7.66992 12.5019 7.66992C14.8919 7.66992 16.8319 9.60992 16.8319 11.9999C16.8319 14.3899 14.8919 16.3299 12.5019 16.3299ZM12.5019 9.16992C10.9419 9.16992 9.67188 10.4399 9.67188 11.9999C9.67188 13.5599 10.9419 14.8299 12.5019 14.8299C14.0619 14.8299 15.3319 13.5599 15.3319 11.9999C15.3319 10.4399 14.0619 9.16992 12.5019 9.16992Z" fill="#64748B" />
                                                        <path d="M12.4981 21.0205C8.73812 21.0205 5.18813 18.8205 2.74812 15.0005C1.68813 13.3505 1.68813 10.6605 2.74812 9.00047C5.19812 5.18047 8.74813 2.98047 12.4981 2.98047C16.2481 2.98047 19.7981 5.18047 22.2381 9.00047C23.2981 10.6505 23.2981 13.3405 22.2381 15.0005C19.7981 18.8205 16.2481 21.0205 12.4981 21.0205ZM12.4981 4.48047C9.26813 4.48047 6.17813 6.42047 4.01813 9.81047C3.26813 10.9805 3.26813 13.0205 4.01813 14.1905C6.17813 17.5805 9.26813 19.5205 12.4981 19.5205C15.7281 19.5205 18.8181 17.5805 20.9781 14.1905C21.7281 13.0205 21.7281 10.9805 20.9781 9.81047C18.8181 6.42047 15.7281 4.48047 12.4981 4.48047Z" fill="#64748B" />
                                                    </svg>
                                                </span>
                                                <span class="multi-pin" changeWith="1">
                                                    <svg class="pin-icn" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M16.3908 4.36734L12.1481 8.60998L7.90549 10.0242L6.49128 11.4384L13.5623 18.5095L14.9766 17.0953L16.3908 12.8526L20.6334 8.60998" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10.0234 14.9746L6.4879 18.5101" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M15.6797 3.66211L21.3365 9.31896" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
            
                                                    <svg class="unpin-icn d-none" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
                                                        <path d="M680-840v80h-40v327l-80-80v-247H400v87l-87-87-33-33v-47h400ZM480-40l-40-40v-240H240v-80l80-80v-46L56-792l56-56 736 736-58 56-264-264h-6v240l-40 40ZM354-400h92l-44-44-2-2-46 46Zm126-193Zm-78 149Z" />
                                                    </svg>
                                                </span>
                                                <span class="multi-mute" changeWith="1">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M15.5 8.36979V7.40979C15.5 4.42979 13.43 3.28979 10.91 4.86979L7.99 6.69979C7.67 6.88979 7.3 6.99979 6.93 6.99979H5.5C3.5 6.99979 2.5 7.99979 2.5 9.99979V13.9998C2.5 15.9998 3.5 16.9998 5.5 16.9998H7.5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10.9062 19.1292C13.4262 20.7092 15.4963 19.5592 15.4963 16.5892V12.9492" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M19.31 9.41992C20.21 11.5699 19.94 14.0799 18.5 15.9999" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M21.6481 7.80078C23.1181 11.2908 22.6781 15.3708 20.3281 18.5008" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M22.5 2L2.5 22" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                                <span class="multi-archive" changeWith="1">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M20 9.2207V18.0007C20 20.0007 19.5 21.0007 17 21.0007H8C5.5 21.0007 5 20.0007 5 18.0007V9.2207" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M5.5 4H19.5C21.5 4 22.5 4.625 22.5 5.875V7.125C22.5 8.375 21.5 9 19.5 9H5.5C3.5 9 2.5 8.375 2.5 7.125V5.875C2.5 4.625 3.5 4 5.5 4Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M10.6797 13H14.3197" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                                <span class="multi-delete">
                                                    <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M21.5036 6.73046C21.4836 6.73046 21.4536 6.73046 21.4236 6.73046C16.1336 6.20046 10.8536 6.00046 5.62358 6.53046L3.58358 6.73046C3.16358 6.77046 2.79358 6.47046 2.75358 6.05046C2.71358 5.63046 3.01358 5.27046 3.42358 5.23046L5.46358 5.03046C10.7836 4.49046 16.1736 4.70046 21.5736 5.23046C21.9836 5.27046 22.2836 5.64046 22.2436 6.05046C22.2136 6.44046 21.8836 6.73046 21.5036 6.73046Z" fill="#64748B" />
                                                        <path d="M9.00074 5.72C8.96074 5.72 8.92074 5.72 8.87074 5.71C8.47074 5.64 8.19074 5.25 8.26074 4.85L8.48074 3.54C8.64074 2.58 8.86074 1.25 11.1907 1.25H13.8107C16.1507 1.25 16.3707 2.63 16.5207 3.55L16.7407 4.85C16.8107 5.26 16.5307 5.65 16.1307 5.71C15.7207 5.78 15.3307 5.5 15.2707 5.1L15.0507 3.8C14.9107 2.93 14.8807 2.76 13.8207 2.76H11.2007C10.1407 2.76 10.1207 2.9 9.97074 3.79L9.74074 5.09C9.68074 5.46 9.36074 5.72 9.00074 5.72Z" fill="#64748B" />
                                                        <path d="M15.7104 22.7496H9.29039C5.80039 22.7496 5.66039 20.8196 5.55039 19.2596L4.90039 9.18959C4.87039 8.77959 5.19039 8.41959 5.60039 8.38959C6.02039 8.36959 6.37039 8.67959 6.40039 9.08959L7.05039 19.1596C7.16039 20.6796 7.20039 21.2496 9.29039 21.2496H15.7104C17.8104 21.2496 17.8504 20.6796 17.9504 19.1596L18.6004 9.08959C18.6304 8.67959 18.9904 8.36959 19.4004 8.38959C19.8104 8.41959 20.1304 8.76959 20.1004 9.18959L19.4504 19.2596C19.3404 20.8196 19.2004 22.7496 15.7104 22.7496Z" fill="#64748B" />
                                                        <path d="M14.1581 17.25H10.8281C10.4181 17.25 10.0781 16.91 10.0781 16.5C10.0781 16.09 10.4181 15.75 10.8281 15.75H14.1581C14.5681 15.75 14.9081 16.09 14.9081 16.5C14.9081 16.91 14.5681 17.25 14.1581 17.25Z" fill="#64748B" />
                                                        <path d="M15 13.25H10C9.59 13.25 9.25 12.91 9.25 12.5C9.25 12.09 9.59 11.75 10 11.75H15C15.41 11.75 15.75 12.09 15.75 12.5C15.75 12.91 15.41 13.25 15 13.25Z" fill="#64748B" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                        <div class="dropdown ms-auto bulk-edit-option">
                                            <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                <svg width="5" height="18" viewBox="0 0 5 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li class="bulk-edit"><a class="dropdown-item" href="#"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M10.499 13.6092C8.50729 13.6092 6.89062 11.9926 6.89062 10.0009C6.89062 8.00924 8.50729 6.39258 10.499 6.39258C12.4906 6.39258 14.1073 8.00924 14.1073 10.0009C14.1073 11.9926 12.4906 13.6092 10.499 13.6092ZM10.499 7.64258C9.19896 7.64258 8.14062 8.70091 8.14062 10.0009C8.14062 11.3009 9.19896 12.3592 10.499 12.3592C11.799 12.3592 12.8573 11.3009 12.8573 10.0009C12.8573 8.70091 11.799 7.64258 10.499 7.64258Z" fill="#94A3B8" />
                                                            <path d="M10.4984 17.5158C7.3651 17.5158 4.40677 15.6824 2.37344 12.4991C1.4901 11.1241 1.4901 8.88242 2.37344 7.49909C4.4151 4.31576 7.37344 2.48242 10.4984 2.48242C13.6234 2.48242 16.5818 4.31576 18.6151 7.49909C19.4984 8.87409 19.4984 11.1158 18.6151 12.4991C16.5818 15.6824 13.6234 17.5158 10.4984 17.5158ZM10.4984 3.73242C7.80677 3.73242 5.23177 5.34909 3.43177 8.17409C2.80677 9.14909 2.80677 10.8491 3.43177 11.8241C5.23177 14.6491 7.80677 16.2658 10.4984 16.2658C13.1901 16.2658 15.7651 14.6491 17.5651 11.8241C18.1901 10.8491 18.1901 9.14909 17.5651 8.17409C15.7651 5.34909 13.1901 3.73242 10.4984 3.73242Z" fill="#94A3B8" />
                                                        </svg>
                                                        Bulk Edit</a>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <input type="hidden" class="senderUser" id="senderUser" value="{{$userId}}" />
                                    <input type="hidden" class="senderUserName" value="{{$userName}}" />
                                    <ul class="chat-list">
            
                                        @php
                                        $i = 0;
                                    
                                        @endphp
            
                                        @foreach ($messages as $k => $message)
                                        @php
                                        @endphp
                                        @if(!isset($message['contactName']) ||!isset($message['conversationId']))
                                        @continue
                                        @endisset
                                        @if ($i == 0 && @$message['isArchive']!="1")
                                        <input type="hidden" class="selected_id" value="{{$k}}" />
                                        <input type="hidden" class="selected_message" value="{{@$message['contactId']}}" />
                                        <input type="hidden" class="selected_name" value="{{@$message['contactName']}}" />
                                        <input type="hidden" id="isGroup" value="{{@$message['group']}}" />
                                        @endif
                                        <div>
                                            <li data-position="{{$i}}" class="{{@$message['unReadCount'] != '0' ?'active':''}} {{$i == 0 ?'active':''}} msg-list {{@$message['isPin']=='1'?'pinned':''}} conversation-{{@$message['conversationId']}} {{@$message['isArchive']=="1"?"archived-list":"unarchived-list"}}" data-userId="{{@$message['contactId']}}" data-msgKey={{$k}} data-search="{{$message['contactName']}}" data-group={{@$message['group']}}>
                                                <div class="ms-1 d-none bulk-check">
                                                    <input class="form-check-input m-0" type="checkbox" name="checked_conversation[]" value="{{$message['conversationId']}}" isGroup="{{@$message['group']}}">
                                                </div>
            
                                                <div class="chat-data d-flex align-items-start">
                                                    <div class="user-img position-relative">
                                                        @if($message['receiverProfile']!=="")
                                                        <img class="img-fluid user-image user-img-{{@$message['contactId']}}" data-id={{@$message['contactId']}} src="{{@$message['receiverProfile']}}" alt="user img">
                                                        @else
                                                        @php
                                                        $contactName = $message['contactName'];
                                                        $words = explode(' ', $contactName);
                                                        $initials = '';
            
                                                        foreach ($words as $word) {
                                                        $initials .= strtoupper(substr($word, 0, 1));
                                                        }
                                                        $initials = substr($initials, 0, 2);
                                                        $fontColor = "fontcolor" . strtoupper($initials[0]);
                                                        @endphp
                                                        <h5 class="{{$fontColor}}">{{$initials}}</h5>
                                                        @endif
                                                        <span class="active"></span>
                                                    </div>
                                                    <a href="javascript:" class="user-detail d-flex ms-3">
                                                        <div class="d-flex align-items-start flex-column tp">
                                                            <div class="d-flex align-items-center gap-2">
                                                                <h3>{{$message['contactName']}}</h3> <span class="host-type d-none"></span>
                                                            </div>
                                                            @php
                                                            $timestamp = $message['timeStamp'] ?? now()->timestamp;
                                                            $timeAgo = Carbon::createFromTimestampMs($timestamp)->diffForHumans();
                                                            @endphp
                                                            <div class="d-flex align-items-center justify-content-between">
                                                                <span class="last-message">{{$message['lastMessage']}}</span>
            
                                                            </div>
                                                        </div>
            
                                                    </a>
                                                    <div class="ms-auto">
                                                        <h6 class="ms-2 time-ago"> {{ $timeAgo }}</h6>
                                                        <div class="d-flex align-items-center justify-content-end">
                                                            <span class="badge ms-2 {{@$message['unReadCount'] == 0 ? 'd-none' : ''}}">{{@$message['unReadCount']}}</span>
                                                            <span class="ms-2 d-flex mt-1 align-items-start justify-content-end pin-svg {{@$message['isPin']=='1'?'':'d-none'}}">
                                                                <svg width="11" height="17" viewBox="0 0 11 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M8.83333 0.5C9.04573 0.500236 9.25003 0.581566 9.40447 0.727374C9.55892 0.873181 9.65186 1.07246 9.66431 1.2845C9.67676 1.49653 9.60777 1.70532 9.47145 1.86819C9.33512 2.03107 9.14175 2.13575 8.93083 2.16083L8.83333 2.16667V6.13667L10.4117 9.29417C10.4552 9.38057 10.4834 9.47391 10.495 9.57L10.5 9.66667V11.3333C10.5 11.5374 10.425 11.7344 10.2894 11.887C10.1538 12.0395 9.96688 12.137 9.76417 12.1608L9.66667 12.1667H6.33333V15.5C6.3331 15.7124 6.25177 15.9167 6.10596 16.0711C5.96015 16.2256 5.76087 16.3185 5.54884 16.331C5.3368 16.3434 5.12802 16.2744 4.96514 16.1381C4.80226 16.0018 4.69759 15.8084 4.6725 15.5975L4.66667 15.5V12.1667H1.33333C1.12922 12.1666 0.932219 12.0917 0.77969 11.9561C0.627161 11.8204 0.529714 11.6335 0.505833 11.4308L0.5 11.3333V9.66667C0.500114 9.57004 0.517032 9.47416 0.55 9.38333L0.588333 9.29417L2.16667 6.135V2.16667C1.95427 2.16643 1.74997 2.0851 1.59553 1.93929C1.44108 1.79349 1.34814 1.59421 1.33569 1.38217C1.32324 1.17014 1.39223 0.96135 1.52855 0.798473C1.66488 0.635595 1.85825 0.53092 2.06917 0.505833L2.16667 0.5H8.83333Z" fill="#94A3B8" />
                                                                </svg>
                                                            </span>
                                                        </div>
                                                    </div>
                                                    <!-- <div class="dropdown ms-auto">
                                                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                            <svg width="5" height="18" viewBox="0 0 5 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            <li><a class="dropdown-item" href="#">Action</a></li>
                                                            <li><a class="dropdown-item" href="#">Another action</a></li>
                                                            <li><a class="dropdown-item" href="#">Something else here</a></li>
                                                        </ul>
                                                    </div> -->
                                                    <div class="dropdown ms-auto text-end">
                                                        <button type="button" class="btn btn-primary dropdown-toggle usr-list-more" data-bs-toggle="dropdown">
                                                            <svg width="5" height="18" viewBox="0 0 5 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                        <ul class="dropdown-menu">
                                                            {{-- <li><a class="dropdown-item" href="#"><svg class="me-2" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M12.5019 16.3299C10.1119 16.3299 8.17188 14.3899 8.17188 11.9999C8.17188 9.60992 10.1119 7.66992 12.5019 7.66992C14.8919 7.66992 16.8319 9.60992 16.8319 11.9999C16.8319 14.3899 14.8919 16.3299 12.5019 16.3299ZM12.5019 9.16992C10.9419 9.16992 9.67188 10.4399 9.67188 11.9999C9.67188 13.5599 10.9419 14.8299 12.5019 14.8299C14.0619 14.8299 15.3319 13.5599 15.3319 11.9999C15.3319 10.4399 14.0619 9.16992 12.5019 9.16992Z" fill="#64748B" />
                                                                    <path d="M12.4981 21.0205C8.73812 21.0205 5.18813 18.8205 2.74812 15.0005C1.68813 13.3505 1.68813 10.6605 2.74812 9.00047C5.19812 5.18047 8.74813 2.98047 12.4981 2.98047C16.2481 2.98047 19.7981 5.18047 22.2381 9.00047C23.2981 10.6505 23.2981 13.3405 22.2381 15.0005C19.7981 18.8205 16.2481 21.0205 12.4981 21.0205ZM12.4981 4.48047C9.26813 4.48047 6.17813 6.42047 4.01813 9.81047C3.26813 10.9805 3.26813 13.0205 4.01813 14.1905C6.17813 17.5805 9.26813 19.5205 12.4981 19.5205C15.7281 19.5205 18.8181 17.5805 20.9781 14.1905C21.7281 13.0205 21.7281 10.9805 20.9781 9.81047C18.8181 6.42047 15.7281 4.48047 12.4981 4.48047Z" fill="#64748B" />
                                                                </svg> Mark as read </a></li> --}}
                                                                <a class="dropdown-item pin-single-conversation" href="#" changewith="{{@$message['isPin']=='1'?'0':'1'}}" data-conversation="{{$message['conversationId']}}">
            
                                                                    <svg class="me-2 pin1-self-icn {{@$message['isPin']=='1'?'d-none':''}}" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M16.3908 4.36734L12.1481 8.60998L7.90549 10.0242L6.49128 11.4384L13.5623 18.5095L14.9766 17.0953L16.3908 12.8526L20.6334 8.60998" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                        <path d="M10.0234 14.9746L6.4879 18.5101" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                        <path d="M15.6797 3.66211L21.3365 9.31896" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                                    </svg>
                                            
                                                                    <svg class="me-2 unpin-single-conversation unpin1-self-icn {{@$message['isPin']=='1'?'':'d-none'}}" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368">
                                                                        <path d="M680-840v80h-40v327l-80-80v-247H400v87l-87-87-33-33v-47h400ZM480-40l-40-40v-240H240v-80l80-80v-46L56-792l56-56 736 736-58 56-264-264h-6v240l-40 40ZM354-400h92l-44-44-2-2-46 46Zm126-193Zm-78 149Z"></path>
                                                                    </svg>
                                            
                                                                    <span>{{@$message['isPin']=='1'?'Unpin':'Pin'}}</span>
                                                                </a>
                                                            <li><a class="dropdown-item mute-single-conversation" changeWith="{{@$message['isMute']=='1'?'0':'1'}}" href="#" data-conversation="{{$message['conversationId']}}">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.33073 14.7926H4.66406C2.6474 14.7926 1.53906 13.6843 1.53906 11.6676V8.33428C1.53906 6.31762 2.6474 5.20928 4.66406 5.20928H5.85573C6.0474 5.20928 6.23906 5.15095 6.40573 5.05095L8.83906 3.52595C10.0557 2.76762 11.2391 2.62595 12.1724 3.14262C13.1057 3.65928 13.6141 4.73428 13.6141 6.17595V6.97595C13.6141 7.31762 13.3307 7.60095 12.9891 7.60095C12.6474 7.60095 12.3641 7.31762 12.3641 6.97595V6.17595C12.3641 5.22595 12.0724 4.51762 11.5641 4.24262C11.0557 3.95928 10.3057 4.08428 9.4974 4.59262L7.06406 6.10928C6.70573 6.34262 6.28073 6.45928 5.85573 6.45928H4.66406C3.3474 6.45928 2.78906 7.01762 2.78906 8.33428V11.6676C2.78906 12.9843 3.3474 13.5426 4.66406 13.5426H6.33073C6.6724 13.5426 6.95573 13.826 6.95573 14.1676C6.95573 14.5093 6.6724 14.7926 6.33073 14.7926Z" fill="#94A3B8" />
                                                                        <path d="M10.9577 17.1577C10.2993 17.1577 9.57434 16.9244 8.84934 16.466C8.55767 16.2827 8.46601 15.8993 8.64934 15.6077C8.83267 15.316 9.21601 15.2243 9.50767 15.4077C10.316 15.9077 11.066 16.041 11.5743 15.7577C12.0827 15.4743 12.3743 14.766 12.3743 13.8243V10.791C12.3743 10.4493 12.6577 10.166 12.9993 10.166C13.341 10.166 13.6243 10.4493 13.6243 10.791V13.8243C13.6243 15.2577 13.1077 16.341 12.1827 16.8577C11.8077 17.0577 11.391 17.1577 10.9577 17.1577Z" fill="#94A3B8" />
                                                                        <path d="M15.5002 13.9586C15.3669 13.9586 15.2419 13.9169 15.1252 13.8336C14.8502 13.6253 14.7919 13.2336 15.0002 12.9586C16.0502 11.5586 16.2752 9.70026 15.6002 8.09193C15.4669 7.77526 15.6169 7.4086 15.9336 7.27526C16.2502 7.14193 16.6169 7.29193 16.7502 7.6086C17.6002 9.62526 17.3086 11.9669 16.0002 13.7169C15.8752 13.8753 15.6919 13.9586 15.5002 13.9586Z" fill="#94A3B8" />
                                                                        <path d="M17.0237 16.0423C16.8903 16.0423 16.7653 16.0007 16.6487 15.9173C16.3737 15.709 16.3153 15.3173 16.5237 15.0423C18.307 12.6673 18.6987 9.48399 17.5487 6.74232C17.4153 6.42565 17.5653 6.05899 17.882 5.92565C18.207 5.79232 18.5653 5.94232 18.6987 6.25899C20.0237 9.40899 19.5737 13.059 17.5237 15.7923C17.407 15.959 17.2153 16.0423 17.0237 16.0423Z" fill="#94A3B8" />
                                                                        <path d="M2.16979 18.9576C2.01146 18.9576 1.85313 18.8992 1.72812 18.7742C1.48646 18.5326 1.48646 18.1325 1.72812 17.8909L18.3948 1.22422C18.6365 0.982552 19.0365 0.982552 19.2781 1.22422C19.5198 1.46589 19.5198 1.86589 19.2781 2.10755L2.61146 18.7742C2.48646 18.8992 2.32812 18.9576 2.16979 18.9576Z" fill="#94A3B8" />
                                                                    </svg>
                                                                    <span>{{@$message['isMute']=='1'?'Unmute':'Mute'}}</span></a>
                                                            </li>
                                                            <li><a class="dropdown-item archive-single1-conversation" changeWith="{{@$message['isArchive']=='1'?'0':'1'}}" href="#" data-conversation="{{$message['conversationId']}}">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M16.75 7.68359V15.0003C16.75 16.6669 16.3333 17.5003 14.25 17.5003H6.75C4.66667 17.5003 4.25 16.6669 4.25 15.0003V7.68359" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                        <path d="M4.66406 3.33398H16.3307C17.9974 3.33398 18.8307 3.85482 18.8307 4.89648V5.93815C18.8307 6.97982 17.9974 7.50065 16.3307 7.50065H4.66406C2.9974 7.50065 2.16406 6.97982 2.16406 5.93815V4.89648C2.16406 3.85482 2.9974 3.33398 4.66406 3.33398Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                        <path d="M8.98438 10.834H12.0177" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                    </svg>
                                                                    <span>{{@$message['isArchive']=='1'?'Unarchive':'Archive'}}</span></a>
                                                            </li>
                                                            <li><a class="dropdown-item delete-single-conversation" href="#" data-conversation="{{$message['conversationId']}}" data-isGroup="{{@$message['group']}}">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M18.003 5.60742C17.9863 5.60742 17.9613 5.60742 17.9363 5.60742C13.528 5.16575 9.12798 4.99909 4.76965 5.44075L3.06965 5.60742C2.71965 5.64075 2.41131 5.39075 2.37798 5.04075C2.34465 4.69075 2.59465 4.39075 2.93631 4.35742L4.63631 4.19075C9.06965 3.74075 13.5613 3.91575 18.0613 4.35742C18.403 4.39075 18.653 4.69909 18.6196 5.04075C18.5946 5.36575 18.3196 5.60742 18.003 5.60742Z" fill="#94A3B8" />
                                                                        <path d="M7.58656 4.76602C7.55322 4.76602 7.51989 4.76602 7.47822 4.75768C7.14489 4.69935 6.91156 4.37435 6.96989 4.04102L7.15322 2.94935C7.28656 2.14935 7.46989 1.04102 9.41156 1.04102H11.5949C13.5449 1.04102 13.7282 2.19102 13.8532 2.95768L14.0366 4.04102C14.0949 4.38268 13.8616 4.70768 13.5282 4.75768C13.1866 4.81602 12.8616 4.58268 12.8116 4.24935L12.6282 3.16602C12.5116 2.44102 12.4866 2.29935 11.6032 2.29935H9.41989C8.53656 2.29935 8.51989 2.41602 8.39489 3.15768L8.20322 4.24102C8.15322 4.54935 7.88656 4.76602 7.58656 4.76602Z" fill="#94A3B8" />
                                                                        <path d="M13.174 18.9577H7.82402C4.91569 18.9577 4.79902 17.3493 4.70735 16.0493L4.16569 7.65766C4.14069 7.316 4.40735 7.016 4.74902 6.991C5.09902 6.97433 5.39069 7.23266 5.41569 7.57433L5.95735 15.966C6.04902 17.2327 6.08235 17.7077 7.82402 17.7077H13.174C14.924 17.7077 14.9574 17.2327 15.0407 15.966L15.5824 7.57433C15.6074 7.23266 15.9074 6.97433 16.249 6.991C16.5907 7.016 16.8574 7.30766 16.8324 7.65766L16.2907 16.0493C16.199 17.3493 16.0824 18.9577 13.174 18.9577Z" fill="#94A3B8" />
                                                                        <path d="M11.8844 14.375H9.10938C8.76771 14.375 8.48438 14.0917 8.48438 13.75C8.48438 13.4083 8.76771 13.125 9.10938 13.125H11.8844C12.226 13.125 12.5094 13.4083 12.5094 13.75C12.5094 14.0917 12.226 14.375 11.8844 14.375Z" fill="#94A3B8" />
                                                                        <path d="M12.5807 11.041H8.41406C8.0724 11.041 7.78906 10.7577 7.78906 10.416C7.78906 10.0743 8.0724 9.79102 8.41406 9.79102H12.5807C12.9224 9.79102 13.2057 10.0743 13.2057 10.416C13.2057 10.7577 12.9224 11.041 12.5807 11.041Z" fill="#94A3B8" />
                                                                    </svg>
                                                                    Delete</a>
                                                            </li>
                                                            @if (!isset($message['group']) || $message['group'] == '0')
                                                            <li><a class="dropdown-item block-conversation single" href="#" data-conversation="{{$message['conversationId']}}" user="{{@$message['contactId']}}" blocked="false">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M12.9141 18.9577H8.08073C7.33907 18.9577 6.38906 18.566 5.87239 18.041L2.45573 14.6243C1.93073 14.0993 1.53906 13.1493 1.53906 12.416V7.58269C1.53906 6.84102 1.93073 5.89103 2.45573 5.37436L5.87239 1.95769C6.39739 1.43269 7.3474 1.04102 8.08073 1.04102H12.9141C13.6557 1.04102 14.6057 1.43269 15.1224 1.95769L18.5391 5.37436C19.0641 5.89936 19.4557 6.84935 19.4557 7.58269V12.416C19.4557 13.1577 19.0641 14.1077 18.5391 14.6243L15.1224 18.041C14.5974 18.566 13.6557 18.9577 12.9141 18.9577ZM8.08073 2.29102C7.6724 2.29102 7.03906 2.54935 6.75572 2.84102L3.33907 6.25769C3.05573 6.54936 2.78906 7.17435 2.78906 7.58269V12.416C2.78906 12.8243 3.0474 13.4577 3.33907 13.741L6.75572 17.1577C7.04739 17.441 7.6724 17.7077 8.08073 17.7077H12.9141C13.3224 17.7077 13.9557 17.4493 14.2391 17.1577L17.6557 13.741C17.9391 13.4493 18.2057 12.8243 18.2057 12.416V7.58269C18.2057 7.17435 17.9474 6.54102 17.6557 6.25769L14.2391 2.84102C13.9474 2.55769 13.3224 2.29102 12.9141 2.29102H8.08073Z" fill="#94A3B8" />
                                                                        <path d="M4.6151 16.5254C4.45677 16.5254 4.29844 16.467 4.17344 16.342C3.93177 16.1004 3.93177 15.7004 4.17344 15.4587L15.9568 3.67539C16.1984 3.43372 16.5984 3.43372 16.8401 3.67539C17.0818 3.91706 17.0818 4.31706 16.8401 4.55872L5.05677 16.342C4.93177 16.467 4.77344 16.5254 4.6151 16.5254Z" fill="#94A3B8" />
                                                                    </svg>
                                                                    <span>Block<span></a>
                                                            </li>
                                                            @endif
                                                            <li><a class="dropdown-item report-single-conversation" href="#" data-conversation="{{$message['conversationId']}}">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M10.4974 18.9577C5.55573 18.9577 1.53906 14.941 1.53906 9.99935C1.53906 5.05768 5.55573 1.04102 10.4974 1.04102C15.4391 1.04102 19.4557 5.05768 19.4557 9.99935C19.4557 14.941 15.4391 18.9577 10.4974 18.9577ZM10.4974 2.29102C6.2474 2.29102 2.78906 5.74935 2.78906 9.99935C2.78906 14.2493 6.2474 17.7077 10.4974 17.7077C14.7474 17.7077 18.2057 14.2493 18.2057 9.99935C18.2057 5.74935 14.7474 2.29102 10.4974 2.29102Z" fill="#94A3B8" />
                                                                        <path d="M10.5 11.4577C10.1583 11.4577 9.875 11.1743 9.875 10.8327V6.66602C9.875 6.32435 10.1583 6.04102 10.5 6.04102C10.8417 6.04102 11.125 6.32435 11.125 6.66602V10.8327C11.125 11.1743 10.8417 11.4577 10.5 11.4577Z" fill="#94A3B8" />
                                                                        <path d="M10.4974 14.1664C10.3891 14.1664 10.2807 14.1414 10.1807 14.0997C10.0807 14.0581 9.98906 13.9997 9.90573 13.9247C9.83073 13.8414 9.7724 13.7581 9.73073 13.6497C9.68906 13.5497 9.66406 13.4414 9.66406 13.3331C9.66406 13.2247 9.68906 13.1164 9.73073 13.0164C9.7724 12.9164 9.83073 12.8247 9.90573 12.7414C9.98906 12.6664 10.0807 12.6081 10.1807 12.5664C10.3807 12.4831 10.6141 12.4831 10.8141 12.5664C10.9141 12.6081 11.0057 12.6664 11.0891 12.7414C11.1641 12.8247 11.2224 12.9164 11.2641 13.0164C11.3057 13.1164 11.3307 13.2247 11.3307 13.3331C11.3307 13.4414 11.3057 13.5497 11.2641 13.6497C11.2224 13.7581 11.1641 13.8414 11.0891 13.9247C11.0057 13.9997 10.9141 14.0581 10.8141 14.0997C10.7141 14.1414 10.6057 14.1664 10.4974 14.1664Z" fill="#94A3B8" />
                                                                    </svg>
                                                                    Report</a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                            </li>
                                        </div>
                                        @php
                                        if(@$message['isArchive']!="1"){
                                        $i++;
                                        }
                                        @endphp
                                        @endforeach
                                        @if ($i==0)
                                        <input type="hidden" class="selected_id" />
                                        <input type="hidden" class="selected_message" />
                                        <input type="hidden" class="selected_name" />
                                        <input type="hidden" id="isGroup" />
            
                                        @endif
            
                                    </ul>
                                    <button id="archive-list" list="0" style="display: none;">Archive List</button>
                                </div>
                                <div class="chatbox position-relative w-100 message-view-box">
                                    <div class="msg-head">
                                        <div class="row">
                                            <div class="col-lg-8 col-7">
                                                <a href="javascript:;" class="d-flex align-items-center conversationId" data-bs-toggle="modal" data-bs-target="#listBox">
                                                    <button id="backtomsg-btn">
                                                        <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M10.07 5.92969L4 11.9997L10.07 18.0697" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        <path d="M21.0019 12H4.17188" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                        </svg>
                                                    </button>
                                                    <div class="user-img">
                                                        <img id="selected-user-profile" src="{{asset('assets/front')}}/image/user-img.svg" alt="user-img">
                                                        <!-- <h5 class="fontcolorS">ST</h5> -->
                                                    </div>
                                                    <div class="user-detail">
                                                        <h3 id="selected-user-name">Start new chat</h3>
                                                        <span id="selected-user-lastseen"></span>
                                                        <span class="typing"></span>
                                                    </div>
                                                </a>
                                            </div>
                                            <div class="col-lg-4 col-5">
                                                <div class="dropdown ms-auto text-end">
                                                    <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
                                                        <svg width="5" height="18" viewBox="0 0 5 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        {{-- <li><a class="dropdown-item" href="#"><svg class="me-2" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M12.5019 16.3299C10.1119 16.3299 8.17188 14.3899 8.17188 11.9999C8.17188 9.60992 10.1119 7.66992 12.5019 7.66992C14.8919 7.66992 16.8319 9.60992 16.8319 11.9999C16.8319 14.3899 14.8919 16.3299 12.5019 16.3299ZM12.5019 9.16992C10.9419 9.16992 9.67188 10.4399 9.67188 11.9999C9.67188 13.5599 10.9419 14.8299 12.5019 14.8299C14.0619 14.8299 15.3319 13.5599 15.3319 11.9999C15.3319 10.4399 14.0619 9.16992 12.5019 9.16992Z" fill="#64748B" />
                                                                    <path d="M12.4981 21.0205C8.73812 21.0205 5.18813 18.8205 2.74812 15.0005C1.68813 13.3505 1.68813 10.6605 2.74812 9.00047C5.19812 5.18047 8.74813 2.98047 12.4981 2.98047C16.2481 2.98047 19.7981 5.18047 22.2381 9.00047C23.2981 10.6505 23.2981 13.3405 22.2381 15.0005C19.7981 18.8205 16.2481 21.0205 12.4981 21.0205ZM12.4981 4.48047C9.26813 4.48047 6.17813 6.42047 4.01813 9.81047C3.26813 10.9805 3.26813 13.0205 4.01813 14.1905C6.17813 17.5805 9.26813 19.5205 12.4981 19.5205C15.7281 19.5205 18.8181 17.5805 20.9781 14.1905C21.7281 13.0205 21.7281 10.9805 20.9781 9.81047C18.8181 6.42047 15.7281 4.48047 12.4981 4.48047Z" fill="#64748B" />
                                                                </svg> Mark as read </a></li> --}}
                                                        <li>
                                                            <a class="dropdown-item pin-conversation" href="#">
            
                                                                <svg class="me-2 pin-self-icn" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M16.3908 4.36734L12.1481 8.60998L7.90549 10.0242L6.49128 11.4384L13.5623 18.5095L14.9766 17.0953L16.3908 12.8526L20.6334 8.60998" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M10.0234 14.9746L6.4879 18.5101" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M15.6797 3.66211L21.3365 9.31896" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
            
                                                                <svg class="me-2 unpin-self-icn" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="display: none;">
                                                                    <path d="M680-840v80h-40v327l-80-80v-247H400v87l-87-87-33-33v-47h400ZM480-40l-40-40v-240H240v-80l80-80v-46L56-792l56-56 736 736-58 56-264-264h-6v240l-40 40ZM354-400h92l-44-44-2-2-46 46Zm126-193Zm-78 149Z" />
                                                                </svg>
            
                                                                <span>Pin</span></a>
                                                        </li>
                                                        <li><a class="dropdown-item mute-conversation" href="#">
                                                                <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M6.33073 14.7926H4.66406C2.6474 14.7926 1.53906 13.6843 1.53906 11.6676V8.33428C1.53906 6.31762 2.6474 5.20928 4.66406 5.20928H5.85573C6.0474 5.20928 6.23906 5.15095 6.40573 5.05095L8.83906 3.52595C10.0557 2.76762 11.2391 2.62595 12.1724 3.14262C13.1057 3.65928 13.6141 4.73428 13.6141 6.17595V6.97595C13.6141 7.31762 13.3307 7.60095 12.9891 7.60095C12.6474 7.60095 12.3641 7.31762 12.3641 6.97595V6.17595C12.3641 5.22595 12.0724 4.51762 11.5641 4.24262C11.0557 3.95928 10.3057 4.08428 9.4974 4.59262L7.06406 6.10928C6.70573 6.34262 6.28073 6.45928 5.85573 6.45928H4.66406C3.3474 6.45928 2.78906 7.01762 2.78906 8.33428V11.6676C2.78906 12.9843 3.3474 13.5426 4.66406 13.5426H6.33073C6.6724 13.5426 6.95573 13.826 6.95573 14.1676C6.95573 14.5093 6.6724 14.7926 6.33073 14.7926Z" fill="#94A3B8" />
                                                                    <path d="M10.9577 17.1577C10.2993 17.1577 9.57434 16.9244 8.84934 16.466C8.55767 16.2827 8.46601 15.8993 8.64934 15.6077C8.83267 15.316 9.21601 15.2243 9.50767 15.4077C10.316 15.9077 11.066 16.041 11.5743 15.7577C12.0827 15.4743 12.3743 14.766 12.3743 13.8243V10.791C12.3743 10.4493 12.6577 10.166 12.9993 10.166C13.341 10.166 13.6243 10.4493 13.6243 10.791V13.8243C13.6243 15.2577 13.1077 16.341 12.1827 16.8577C11.8077 17.0577 11.391 17.1577 10.9577 17.1577Z" fill="#94A3B8" />
                                                                    <path d="M15.5002 13.9586C15.3669 13.9586 15.2419 13.9169 15.1252 13.8336C14.8502 13.6253 14.7919 13.2336 15.0002 12.9586C16.0502 11.5586 16.2752 9.70026 15.6002 8.09193C15.4669 7.77526 15.6169 7.4086 15.9336 7.27526C16.2502 7.14193 16.6169 7.29193 16.7502 7.6086C17.6002 9.62526 17.3086 11.9669 16.0002 13.7169C15.8752 13.8753 15.6919 13.9586 15.5002 13.9586Z" fill="#94A3B8" />
                                                                    <path d="M17.0237 16.0423C16.8903 16.0423 16.7653 16.0007 16.6487 15.9173C16.3737 15.709 16.3153 15.3173 16.5237 15.0423C18.307 12.6673 18.6987 9.48399 17.5487 6.74232C17.4153 6.42565 17.5653 6.05899 17.882 5.92565C18.207 5.79232 18.5653 5.94232 18.6987 6.25899C20.0237 9.40899 19.5737 13.059 17.5237 15.7923C17.407 15.959 17.2153 16.0423 17.0237 16.0423Z" fill="#94A3B8" />
                                                                    <path d="M2.16979 18.9576C2.01146 18.9576 1.85313 18.8992 1.72812 18.7742C1.48646 18.5326 1.48646 18.1325 1.72812 17.8909L18.3948 1.22422C18.6365 0.982552 19.0365 0.982552 19.2781 1.22422C19.5198 1.46589 19.5198 1.86589 19.2781 2.10755L2.61146 18.7742C2.48646 18.8992 2.32812 18.9576 2.16979 18.9576Z" fill="#94A3B8" />
                                                                </svg>
                                                                <span>Mute</span></a>
                                                        </li>
                                                        <li><a class="dropdown-item archive-conversation" href="#">
                                                                <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M16.75 7.68359V15.0003C16.75 16.6669 16.3333 17.5003 14.25 17.5003H6.75C4.66667 17.5003 4.25 16.6669 4.25 15.0003V7.68359" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M4.66406 3.33398H16.3307C17.9974 3.33398 18.8307 3.85482 18.8307 4.89648V5.93815C18.8307 6.97982 17.9974 7.50065 16.3307 7.50065H4.66406C2.9974 7.50065 2.16406 6.97982 2.16406 5.93815V4.89648C2.16406 3.85482 2.9974 3.33398 4.66406 3.33398Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                    <path d="M8.98438 10.834H12.0177" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                </svg>
                                                                <span>Archive</span></a>
                                                        </li>
                                                        <li><a class="dropdown-item delete-conversation" href="#">
                                                                <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M18.003 5.60742C17.9863 5.60742 17.9613 5.60742 17.9363 5.60742C13.528 5.16575 9.12798 4.99909 4.76965 5.44075L3.06965 5.60742C2.71965 5.64075 2.41131 5.39075 2.37798 5.04075C2.34465 4.69075 2.59465 4.39075 2.93631 4.35742L4.63631 4.19075C9.06965 3.74075 13.5613 3.91575 18.0613 4.35742C18.403 4.39075 18.653 4.69909 18.6196 5.04075C18.5946 5.36575 18.3196 5.60742 18.003 5.60742Z" fill="#94A3B8" />
                                                                    <path d="M7.58656 4.76602C7.55322 4.76602 7.51989 4.76602 7.47822 4.75768C7.14489 4.69935 6.91156 4.37435 6.96989 4.04102L7.15322 2.94935C7.28656 2.14935 7.46989 1.04102 9.41156 1.04102H11.5949C13.5449 1.04102 13.7282 2.19102 13.8532 2.95768L14.0366 4.04102C14.0949 4.38268 13.8616 4.70768 13.5282 4.75768C13.1866 4.81602 12.8616 4.58268 12.8116 4.24935L12.6282 3.16602C12.5116 2.44102 12.4866 2.29935 11.6032 2.29935H9.41989C8.53656 2.29935 8.51989 2.41602 8.39489 3.15768L8.20322 4.24102C8.15322 4.54935 7.88656 4.76602 7.58656 4.76602Z" fill="#94A3B8" />
                                                                    <path d="M13.174 18.9577H7.82402C4.91569 18.9577 4.79902 17.3493 4.70735 16.0493L4.16569 7.65766C4.14069 7.316 4.40735 7.016 4.74902 6.991C5.09902 6.97433 5.39069 7.23266 5.41569 7.57433L5.95735 15.966C6.04902 17.2327 6.08235 17.7077 7.82402 17.7077H13.174C14.924 17.7077 14.9574 17.2327 15.0407 15.966L15.5824 7.57433C15.6074 7.23266 15.9074 6.97433 16.249 6.991C16.5907 7.016 16.8574 7.30766 16.8324 7.65766L16.2907 16.0493C16.199 17.3493 16.0824 18.9577 13.174 18.9577Z" fill="#94A3B8" />
                                                                    <path d="M11.8844 14.375H9.10938C8.76771 14.375 8.48438 14.0917 8.48438 13.75C8.48438 13.4083 8.76771 13.125 9.10938 13.125H11.8844C12.226 13.125 12.5094 13.4083 12.5094 13.75C12.5094 14.0917 12.226 14.375 11.8844 14.375Z" fill="#94A3B8" />
                                                                    <path d="M12.5807 11.041H8.41406C8.0724 11.041 7.78906 10.7577 7.78906 10.416C7.78906 10.0743 8.0724 9.79102 8.41406 9.79102H12.5807C12.9224 9.79102 13.2057 10.0743 13.2057 10.416C13.2057 10.7577 12.9224 11.041 12.5807 11.041Z" fill="#94A3B8" />
                                                                </svg>
                                                                Delete</a>
                                                        </li>
                                                        <li><a class="dropdown-item block-conversation" href="#">
                                                                <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M12.9141 18.9577H8.08073C7.33907 18.9577 6.38906 18.566 5.87239 18.041L2.45573 14.6243C1.93073 14.0993 1.53906 13.1493 1.53906 12.416V7.58269C1.53906 6.84102 1.93073 5.89103 2.45573 5.37436L5.87239 1.95769C6.39739 1.43269 7.3474 1.04102 8.08073 1.04102H12.9141C13.6557 1.04102 14.6057 1.43269 15.1224 1.95769L18.5391 5.37436C19.0641 5.89936 19.4557 6.84935 19.4557 7.58269V12.416C19.4557 13.1577 19.0641 14.1077 18.5391 14.6243L15.1224 18.041C14.5974 18.566 13.6557 18.9577 12.9141 18.9577ZM8.08073 2.29102C7.6724 2.29102 7.03906 2.54935 6.75572 2.84102L3.33907 6.25769C3.05573 6.54936 2.78906 7.17435 2.78906 7.58269V12.416C2.78906 12.8243 3.0474 13.4577 3.33907 13.741L6.75572 17.1577C7.04739 17.441 7.6724 17.7077 8.08073 17.7077H12.9141C13.3224 17.7077 13.9557 17.4493 14.2391 17.1577L17.6557 13.741C17.9391 13.4493 18.2057 12.8243 18.2057 12.416V7.58269C18.2057 7.17435 17.9474 6.54102 17.6557 6.25769L14.2391 2.84102C13.9474 2.55769 13.3224 2.29102 12.9141 2.29102H8.08073Z" fill="#94A3B8" />
                                                                    <path d="M4.6151 16.5254C4.45677 16.5254 4.29844 16.467 4.17344 16.342C3.93177 16.1004 3.93177 15.7004 4.17344 15.4587L15.9568 3.67539C16.1984 3.43372 16.5984 3.43372 16.8401 3.67539C17.0818 3.91706 17.0818 4.31706 16.8401 4.55872L5.05677 16.342C4.93177 16.467 4.77344 16.5254 4.6151 16.5254Z" fill="#94A3B8" />
                                                                </svg>
                                                                <span>Block<span></a>
                                                        </li>
                                                        <li><a class="dropdown-item report-conversation" href="#">
                                                                <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                    <path d="M10.4974 18.9577C5.55573 18.9577 1.53906 14.941 1.53906 9.99935C1.53906 5.05768 5.55573 1.04102 10.4974 1.04102C15.4391 1.04102 19.4557 5.05768 19.4557 9.99935C19.4557 14.941 15.4391 18.9577 10.4974 18.9577ZM10.4974 2.29102C6.2474 2.29102 2.78906 5.74935 2.78906 9.99935C2.78906 14.2493 6.2474 17.7077 10.4974 17.7077C14.7474 17.7077 18.2057 14.2493 18.2057 9.99935C18.2057 5.74935 14.7474 2.29102 10.4974 2.29102Z" fill="#94A3B8" />
                                                                    <path d="M10.5 11.4577C10.1583 11.4577 9.875 11.1743 9.875 10.8327V6.66602C9.875 6.32435 10.1583 6.04102 10.5 6.04102C10.8417 6.04102 11.125 6.32435 11.125 6.66602V10.8327C11.125 11.1743 10.8417 11.4577 10.5 11.4577Z" fill="#94A3B8" />
                                                                    <path d="M10.4974 14.1664C10.3891 14.1664 10.2807 14.1414 10.1807 14.0997C10.0807 14.0581 9.98906 13.9997 9.90573 13.9247C9.83073 13.8414 9.7724 13.7581 9.73073 13.6497C9.68906 13.5497 9.66406 13.4414 9.66406 13.3331C9.66406 13.2247 9.68906 13.1164 9.73073 13.0164C9.7724 12.9164 9.83073 12.8247 9.90573 12.7414C9.98906 12.6664 10.0807 12.6081 10.1807 12.5664C10.3807 12.4831 10.6141 12.4831 10.8141 12.5664C10.9141 12.6081 11.0057 12.6664 11.0891 12.7414C11.1641 12.8247 11.2224 12.9164 11.2641 13.0164C11.3057 13.1164 11.3307 13.2247 11.3307 13.3331C11.3307 13.4414 11.3057 13.5497 11.2641 13.6497C11.2224 13.7581 11.1641 13.8414 11.0891 13.9247C11.0057 13.9997 10.9141 14.0581 10.8141 14.0997C10.7141 14.1414 10.6057 14.1664 10.4974 14.1664Z" fill="#94A3B8" />
                                                                </svg>
                                                                Report</a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
            
                                    <div class="msg-body" id="msgBody">
                                        <input type="hidden" class="selected_conversasion" />
            
                                        <ul class="msg-lists">
            
                                        </ul>
                                        <div id="msgbox"></div>
                                        <div class="empty-massage">
                                            <div class="empty-img">
                                                <img src="{{asset('assets/front')}}/image/empty-img.png" alt="empty-img">
                                            </div>
                                            <h5>Select Message First</h5>
                                            <p>Please select a message to see the details</p>
                                        </div>
                                    </div>
            
            
            
                                    <div class="msg-footer">
                                        <div id="preview" style="display: none;">
                                            <label id="upload_name"></label>
                                            <img src="" id="preview_img" class="preview_img">
                                            <div id="preview_file">
                                                <button class="file_close">
                                                    <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.4974 0.666016C3.90573 0.666016 0.164062 4.40768 0.164062 8.99935C0.164062 13.591 3.90573 17.3327 8.4974 17.3327C13.0891 17.3327 16.8307 13.591 16.8307 8.99935C16.8307 4.40768 13.0891 0.666016 8.4974 0.666016ZM11.2974 10.916C11.5391 11.1577 11.5391 11.5577 11.2974 11.7993C11.1724 11.9243 11.0141 11.9827 10.8557 11.9827C10.6974 11.9827 10.5391 11.9243 10.4141 11.7993L8.4974 9.88268L6.58073 11.7993C6.45573 11.9243 6.2974 11.9827 6.13906 11.9827C5.98073 11.9827 5.8224 11.9243 5.6974 11.7993C5.45573 11.5577 5.45573 11.1577 5.6974 10.916L7.61406 8.99935L5.6974 7.08268C5.45573 6.84102 5.45573 6.44102 5.6974 6.19935C5.93906 5.95768 6.33906 5.95768 6.58073 6.19935L8.4974 8.11602L10.4141 6.19935C10.6557 5.95768 11.0557 5.95768 11.2974 6.19935C11.5391 6.44102 11.5391 6.84102 11.2974 7.08268L9.38073 8.99935L11.2974 10.916Z" fill="#F73C71" />
                                                    </svg>
                                                </button>
                                                <img src="{{asset('storage/file.png')}}" class="preview_file">
                                                <span id="file_name"></span>
                                            </div>
            
                                        </div>
                                        <input type="hidden" class="file_info">
            
                                        <div class="position-relative audio-music-player" id="musicContainer" style="display: none;">
                                            <div class="audio-container" id="audioContainer">
            
            
            
                                                <div class="music-container wrapper">
                                                    <div class="navigation">
                                                        <button class="action-btn action-btn-big play">
                                                            <i class="fas fa-play"></i>
                                                        </button>
                                                        <div class="music-info">
                                                            <div class="progress-container progress-range">
                                                                <div class="grey-bar">
                                                                    <svg width="800" height="52" viewBox="0 0 1000 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.06263 16.5495C6.06263 15.1414 4.92801 14 3.52839 14C2.12876 14 0.994141 15.1414 0.994141 16.5495V35.4505C0.994141 36.8586 2.12876 38 3.52839 38C4.92801 38 6.06263 36.8586 6.06263 35.4505V16.5495Z" fill="#94A3B8" />
                                                                        <path d="M17.09 22.5495C17.09 21.1414 15.9554 20 14.5557 20C13.1561 20 12.0215 21.1414 12.0215 22.5495V29.4505C12.0215 30.8586 13.1561 32 14.5557 32C15.9554 32 17.09 30.8586 17.09 29.4505V22.5495Z" fill="#94A3B8" />
                                                                        <path d="M28.1334 10.5495C28.1334 9.14143 26.9988 8 25.5992 8C24.1996 8 23.0649 9.14143 23.0649 10.5495V41.4505C23.0649 42.8586 24.1996 44 25.5992 44C26.9988 44 28.1334 42.8586 28.1334 41.4505V10.5495Z" fill="#94A3B8" />
                                                                        <path d="M39.1608 14.5495C39.1608 13.1414 38.0262 12 36.6265 12C35.2269 12 34.0923 13.1414 34.0923 14.5495V37.4505C34.0923 38.8586 35.2269 40 36.6265 40C38.0262 40 39.1608 38.8586 39.1608 37.4505V14.5495Z" fill="#94A3B8" />
                                                                        <path d="M50.1886 12.5495C50.1886 11.1414 49.054 10 47.6544 10C46.2547 10 45.1201 11.1414 45.1201 12.5495V39.4505C45.1201 40.8586 46.2547 42 47.6544 42C49.054 42 50.1886 40.8586 50.1886 39.4505V12.5495Z" fill="#94A3B8" />
                                                                        <path d="M61.2316 16.5495C61.2316 15.1414 60.097 14 58.6973 14C57.2977 14 56.1631 15.1414 56.1631 16.5495V35.4505C56.1631 36.8586 57.2977 38 58.6973 38C60.097 38 61.2316 36.8586 61.2316 35.4505V16.5495Z" fill="#94A3B8" />
                                                                        <path d="M72.2589 19.5495C72.2589 18.1414 71.1243 17 69.7247 17C68.325 17 67.1904 18.1414 67.1904 19.5495V32.4505C67.1904 33.8586 68.325 35 69.7247 35C71.1243 35 72.2589 33.8586 72.2589 32.4505V19.5495Z" fill="#94A3B8" />
                                                                        <path d="M83.2868 16.5495C83.2868 15.1414 82.1521 14 80.7525 14C79.3529 14 78.2183 15.1414 78.2183 16.5495V35.4505C78.2183 36.8586 79.3529 38 80.7525 38C82.1521 38 83.2868 36.8586 83.2868 35.4505V16.5495Z" fill="#94A3B8" />
                                                                        <path d="M94.3297 22.5495C94.3297 21.1414 93.1951 20 91.7955 20C90.3959 20 89.2612 21.1414 89.2612 22.5495V29.4505C89.2612 30.8586 90.3959 32 91.7955 32C93.1951 32 94.3297 30.8586 94.3297 29.4505V22.5495Z" fill="#94A3B8" />
                                                                        <path d="M105.358 10.5495C105.358 9.14143 104.223 8 102.823 8C101.424 8 100.289 9.14143 100.289 10.5495V41.4505C100.289 42.8586 101.424 44 102.823 44C104.223 44 105.358 42.8586 105.358 41.4505V10.5495Z" fill="#94A3B8" />
                                                                        <path d="M116.385 19.5495C116.385 18.1414 115.25 17 113.851 17C112.451 17 111.316 18.1414 111.316 19.5495V32.4505C111.316 33.8586 112.451 35 113.851 35C115.25 35 116.385 33.8586 116.385 32.4505V19.5495Z" fill="#94A3B8" />
                                                                        <path d="M127.428 19.5495C127.428 18.1414 126.294 17 124.894 17C123.494 17 122.36 18.1414 122.36 19.5495V32.4505C122.36 33.8586 123.494 35 124.894 35C126.294 35 127.428 33.8586 127.428 32.4505V19.5495Z" fill="#94A3B8" />
                                                                        <path d="M138.456 16.5495C138.456 15.1414 137.321 14 135.921 14C134.522 14 133.387 15.1414 133.387 16.5495V35.4505C133.387 36.8586 134.522 38 135.921 38C137.321 38 138.456 36.8586 138.456 35.4505V16.5495Z" fill="#94A3B8" />
                                                                        <path d="M146.949 8H146.949C145.549 8 144.415 9.14142 144.415 10.5494V41.4506C144.415 42.8586 145.549 44 146.949 44C148.348 44 149.483 42.8586 149.483 41.4506V10.5494C149.483 9.14142 148.348 8 146.949 8Z" fill="#94A3B8" />
                                                                        <path d="M157.992 8H157.992C156.593 8 155.458 9.14142 155.458 10.5494V41.4506C155.458 42.8586 156.593 44 157.992 44H157.992C159.392 44 160.527 42.8586 160.527 41.4506V10.5494C160.527 9.14142 159.392 8 157.992 8Z" fill="#94A3B8" />
                                                                        <path d="M169.02 8H169.02C167.62 8 166.485 9.14142 166.485 10.5494V41.4506C166.485 42.8586 167.62 44 169.02 44C170.419 44 171.554 42.8586 171.554 41.4506V10.5494C171.554 9.14142 170.419 8 169.02 8Z" fill="#94A3B8" />
                                                                        <path d="M180.047 8H180.047C178.648 8 177.513 9.14142 177.513 10.5494V41.4506C177.513 42.8586 178.648 44 180.047 44C181.447 44 182.582 42.8586 182.582 41.4506V10.5494C182.582 9.14142 181.447 8 180.047 8Z" fill="#94A3B8" />
                                                                        <path d="M191.09 1H191.09C189.691 1 188.556 2.14142 188.556 3.54944V48.4506C188.556 49.8586 189.691 51 191.09 51C192.49 51 193.625 49.8586 193.625 48.4506V3.54944C193.625 2.14142 192.49 1 191.09 1Z" fill="#94A3B8" />
                                                                        <path d="M202.119 8H202.119C200.719 8 199.584 9.14142 199.584 10.5494V41.4506C199.584 42.8586 200.719 44 202.119 44H202.119C203.518 44 204.653 42.8586 204.653 41.4506V10.5494C204.653 9.14142 203.518 8 202.119 8Z" fill="#94A3B8" />
                                                                        <path d="M213.147 8H213.147C211.747 8 210.612 9.14142 210.612 10.5494V41.4506C210.612 42.8586 211.747 44 213.147 44C214.546 44 215.681 42.8586 215.681 41.4506V10.5494C215.681 9.14142 214.546 8 213.147 8Z" fill="#94A3B8" />
                                                                        <path d="M224.189 8H224.189C222.789 8 221.654 9.14142 221.654 10.5494V41.4506C221.654 42.8586 222.789 44 224.189 44C225.588 44 226.723 42.8586 226.723 41.4506V10.5494C226.723 9.14142 225.588 8 224.189 8Z" fill="#94A3B8" />
                                                                        <path d="M235.216 8H235.216C233.817 8 232.682 9.14142 232.682 10.5494V41.4506C232.682 42.8586 233.817 44 235.216 44C236.616 44 237.751 42.8586 237.751 41.4506V10.5494C237.751 9.14142 236.616 8 235.216 8Z" fill="#94A3B8" />
                                                                        <path d="M246.244 8H246.244C244.845 8 243.71 9.14142 243.71 10.5494V41.4506C243.71 42.8586 244.845 44 246.244 44H246.244C247.644 44 248.778 42.8586 248.778 41.4506V10.5494C248.778 9.14142 247.644 8 246.244 8Z" fill="#94A3B8" />
                                                                        <path d="M259.822 10.5494C259.822 9.14142 258.687 8 257.288 8C255.888 8 254.753 9.14142 254.753 10.5494V41.4506C254.753 42.8586 255.888 44 257.288 44C258.687 44 259.822 42.8586 259.822 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M270.85 10.5494C270.85 9.14142 269.715 8 268.315 8C266.916 8 265.781 9.14142 265.781 10.5494V41.4506C265.781 42.8586 266.916 44 268.315 44C269.715 44 270.85 42.8586 270.85 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M279.341 8H279.341C277.942 8 276.807 9.14142 276.807 10.5494V41.4506C276.807 42.8586 277.942 44 279.341 44H279.341C280.741 44 281.876 42.8586 281.876 41.4506V10.5494C281.876 9.14142 280.741 8 279.341 8Z" fill="#94A3B8" />
                                                                        <path d="M290.385 8H290.385C288.986 8 287.851 9.14142 287.851 10.5494V41.4506C287.851 42.8586 288.986 44 290.385 44H290.385C291.785 44 292.92 42.8586 292.92 41.4506V10.5494C292.92 9.14142 291.785 8 290.385 8Z" fill="#94A3B8" />
                                                                        <path d="M301.413 14H301.413C300.014 14 298.879 15.1414 298.879 16.5494V35.4506C298.879 36.8586 300.014 38 301.413 38H301.413C302.813 38 303.947 36.8586 303.947 35.4506V16.5494C303.947 15.1414 302.813 14 301.413 14Z" fill="#94A3B8" />
                                                                        <path d="M312.441 8H312.441C311.041 8 309.907 9.14142 309.907 10.5494V41.4506C309.907 42.8586 311.041 44 312.441 44H312.441C313.841 44 314.975 42.8586 314.975 41.4506V10.5494C314.975 9.14142 313.841 8 312.441 8Z" fill="#94A3B8" />
                                                                        <path d="M326.019 10.5494C326.019 9.14142 324.884 8 323.484 8C322.085 8 320.95 9.14142 320.95 10.5494V41.4506C320.95 42.8586 322.085 44 323.484 44C324.884 44 326.019 42.8586 326.019 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M334.51 8H334.51C333.111 8 331.976 9.14142 331.976 10.5494V41.4506C331.976 42.8586 333.111 44 334.51 44H334.51C335.91 44 337.045 42.8586 337.045 41.4506V10.5494C337.045 9.14142 335.91 8 334.51 8Z" fill="#94A3B8" />
                                                                        <path d="M348.072 10.5494C348.072 9.14142 346.938 8 345.538 8C344.139 8 343.004 9.14142 343.004 10.5494V41.4506C343.004 42.8586 344.139 44 345.538 44C346.938 44 348.072 42.8586 348.072 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M356.582 8H356.582C355.182 8 354.048 9.14142 354.048 10.5494V41.4506C354.048 42.8586 355.182 44 356.582 44H356.582C357.982 44 359.116 42.8586 359.116 41.4506V10.5494C359.116 9.14142 357.982 8 356.582 8Z" fill="#94A3B8" />
                                                                        <path d="M367.61 1H367.61C366.21 1 365.076 2.14142 365.076 3.54944V48.4506C365.076 49.8586 366.21 51 367.61 51H367.61C369.01 51 370.144 49.8586 370.144 48.4506V3.54944C370.144 2.14142 369.01 1 367.61 1Z" fill="#94A3B8" />
                                                                        <path d="M381.172 10.5494C381.172 9.14142 380.037 8 378.638 8C377.238 8 376.104 9.14142 376.104 10.5494V41.4506C376.104 42.8586 377.238 44 378.638 44C380.037 44 381.172 42.8586 381.172 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M389.679 8H389.679C388.28 8 387.145 9.14142 387.145 10.5494V41.4506C387.145 42.8586 388.28 44 389.679 44H389.679C391.079 44 392.214 42.8586 392.214 41.4506V10.5494C392.214 9.14142 391.079 8 389.679 8Z" fill="#94A3B8" />
                                                                        <path d="M403.241 10.5494C403.241 9.14142 402.107 8 400.707 8C399.307 8 398.173 9.14142 398.173 10.5494V41.4506C398.173 42.8586 399.307 44 400.707 44C402.107 44 403.241 42.8586 403.241 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M411.735 8H411.735C410.335 8 409.201 9.14142 409.201 10.5494V41.4506C409.201 42.8586 410.335 44 411.735 44H411.735C413.135 44 414.269 42.8586 414.269 41.4506V10.5494C414.269 9.14142 413.135 8 411.735 8Z" fill="#94A3B8" />
                                                                        <path d="M422.779 8H422.779C421.379 8 420.245 9.14142 420.245 10.5494V41.4506C420.245 42.8586 421.379 44 422.779 44H422.779C424.179 44 425.313 42.8586 425.313 41.4506V10.5494C425.313 9.14142 424.179 8 422.779 8Z" fill="#94A3B8" />
                                                                        <path d="M436.341 16.5494C436.341 15.1414 435.206 14 433.807 14C432.407 14 431.272 15.1414 431.272 16.5494V35.4506C431.272 36.8586 432.407 38 433.807 38C435.206 38 436.341 36.8586 436.341 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M447.369 3.54944C447.369 2.14142 446.234 1 444.835 1C443.435 1 442.3 2.14142 442.3 3.54944V48.4506C442.3 49.8586 443.435 51 444.835 51C446.234 51 447.369 49.8586 447.369 48.4506V3.54944Z" fill="#94A3B8" />
                                                                        <path d="M458.41 10.5494C458.41 9.14142 457.276 8 455.876 8C454.476 8 453.342 9.14142 453.342 10.5494V41.4506C453.342 42.8586 454.476 44 455.876 44C457.276 44 458.41 42.8586 458.41 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M466.904 8H466.904C465.504 8 464.37 9.14142 464.37 10.5494V41.4506C464.37 42.8586 465.504 44 466.904 44H466.904C468.304 44 469.438 42.8586 469.438 41.4506V10.5494C469.438 9.14142 468.304 8 466.904 8Z" fill="#94A3B8" />
                                                                        <path d="M477.932 1H477.932C476.532 1 475.397 2.14142 475.397 3.54944V48.4506C475.397 49.8586 476.532 51 477.932 51H477.932C479.331 51 480.466 49.8586 480.466 48.4506V3.54944C480.466 2.14142 479.331 1 477.932 1Z" fill="#94A3B8" />
                                                                        <path d="M491.51 3.54944C491.51 2.14142 490.375 1 488.976 1C487.576 1 486.441 2.14142 486.441 3.54944V48.4506C486.441 49.8586 487.576 51 488.976 51C490.375 51 491.51 49.8586 491.51 48.4506V3.54944Z" fill="#94A3B8" />
                                                                        <path d="M502.538 16.5494C502.538 15.1414 501.403 14 500.003 14C498.604 14 497.469 15.1414 497.469 16.5494V35.4506C497.469 36.8586 498.604 38 500.003 38C501.403 38 502.538 36.8586 502.538 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M513.564 10.5494C513.564 9.14142 512.429 8 511.029 8C509.63 8 508.495 9.14142 508.495 10.5494V41.4506C508.495 42.8586 509.63 44 511.029 44C512.429 44 513.564 42.8586 513.564 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M524.607 10.5494C524.607 9.14142 523.472 8 522.073 8C520.673 8 519.539 9.14142 519.539 10.5494V41.4506C519.539 42.8586 520.673 44 522.073 44C523.472 44 524.607 42.8586 524.607 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M535.635 10.5494C535.635 9.14142 534.5 8 533.101 8C531.701 8 530.566 9.14142 530.566 10.5494V41.4506C530.566 42.8586 531.701 44 533.101 44C534.5 44 535.635 42.8586 535.635 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M546.663 10.5494C546.663 9.14142 545.528 8 544.128 8C542.729 8 541.594 9.14142 541.594 10.5494V41.4506C541.594 42.8586 542.729 44 544.128 44C545.528 44 546.663 42.8586 546.663 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M555.156 8H555.156C553.757 8 552.622 9.14142 552.622 10.5494V41.4506C552.622 42.8586 553.757 44 555.156 44H555.156C556.556 44 557.691 42.8586 557.691 41.4506V10.5494C557.691 9.14142 556.556 8 555.156 8Z" fill="#94A3B8" />
                                                                        <path d="M568.733 10.5494C568.733 9.14142 567.598 8 566.198 8C564.799 8 563.664 9.14142 563.664 10.5494V41.4506C563.664 42.8586 564.799 44 566.198 44C567.598 44 568.733 42.8586 568.733 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M579.76 10.5494C579.76 9.14142 578.626 8 577.226 8C575.827 8 574.692 9.14142 574.692 10.5494V41.4506C574.692 42.8586 575.827 44 577.226 44C578.626 44 579.76 42.8586 579.76 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M590.788 10.5494C590.788 9.14142 589.654 8 588.254 8C586.854 8 585.72 9.14142 585.72 10.5494V41.4506C585.72 42.8586 586.854 44 588.254 44C589.654 44 590.788 42.8586 590.788 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M601.832 16.5494C601.832 15.1414 600.698 14 599.298 14C597.898 14 596.764 15.1414 596.764 16.5494V35.4506C596.764 36.8586 597.898 38 599.298 38C600.698 38 601.832 36.8586 601.832 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M610.325 1H610.325C608.926 1 607.791 2.14142 607.791 3.54944V48.4506C607.791 49.8586 608.926 51 610.325 51H610.325C611.725 51 612.86 49.8586 612.86 48.4506V3.54944C612.86 2.14142 611.725 1 610.325 1Z" fill="#94A3B8" />
                                                                        <path d="M621.354 8H621.354C619.954 8 618.819 9.14142 618.819 10.5494V41.4506C618.819 42.8586 619.954 44 621.354 44H621.354C622.753 44 623.888 42.8586 623.888 41.4506V10.5494C623.888 9.14142 622.753 8 621.354 8Z" fill="#94A3B8" />
                                                                        <path d="M634.929 16.5494C634.929 15.1414 633.795 14 632.395 14C630.995 14 629.861 15.1414 629.861 16.5494V35.4506C629.861 36.8586 630.995 38 632.395 38C633.795 38 634.929 36.8586 634.929 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M645.957 10.5494C645.957 9.14142 644.823 8 643.423 8C642.023 8 640.889 9.14142 640.889 10.5494V41.4506C640.889 42.8586 642.023 44 643.423 44C644.823 44 645.957 42.8586 645.957 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M656.985 10.5494C656.985 9.14142 655.85 8 654.451 8C653.051 8 651.917 9.14142 651.917 10.5494V41.4506C651.917 42.8586 653.051 44 654.451 44C655.85 44 656.985 42.8586 656.985 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M665.494 8H665.494C664.095 8 662.96 9.14142 662.96 10.5494V41.4506C662.96 42.8586 664.095 44 665.494 44H665.494C666.894 44 668.028 42.8586 668.028 41.4506V10.5494C668.028 9.14142 666.894 8 665.494 8Z" fill="#94A3B8" />
                                                                        <path d="M676.523 14H676.523C675.123 14 673.988 15.1414 673.988 16.5494V35.4506C673.988 36.8586 675.123 38 676.523 38H676.523C677.922 38 679.057 36.8586 679.057 35.4506V16.5494C679.057 15.1414 677.922 14 676.523 14Z" fill="#94A3B8" />
                                                                        <path d="M690.082 10.5494C690.082 9.14142 688.948 8 687.548 8C686.148 8 685.014 9.14142 685.014 10.5494V41.4506C685.014 42.8586 686.148 44 687.548 44C688.948 44 690.082 42.8586 690.082 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M701.126 10.5494C701.126 9.14142 699.991 8 698.592 8C697.192 8 696.058 9.14142 696.058 10.5494V41.4506C696.058 42.8586 697.192 44 698.592 44C699.991 44 701.126 42.8586 701.126 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M712.154 16.5494C712.154 15.1414 711.019 14 709.62 14C708.22 14 707.085 15.1414 707.085 16.5494V35.4506C707.085 36.8586 708.22 38 709.62 38C711.019 38 712.154 36.8586 712.154 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M723.182 16.5494C723.182 15.1414 722.047 14 720.648 14C719.248 14 718.113 15.1414 718.113 16.5494V35.4506C718.113 36.8586 719.248 38 720.648 38C722.047 38 723.182 36.8586 723.182 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M731.692 8H731.691C730.292 8 729.157 9.14142 729.157 10.5494V41.4506C729.157 42.8586 730.292 44 731.691 44H731.692C733.091 44 734.226 42.8586 734.226 41.4506V10.5494C734.226 9.14142 733.091 8 731.692 8Z" fill="#94A3B8" />
                                                                        <path d="M745.252 10.5494C745.252 9.14142 744.117 8 742.717 8C741.318 8 740.183 9.14142 740.183 10.5494V41.4506C740.183 42.8586 741.318 44 742.717 44C744.117 44 745.252 42.8586 745.252 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M756.279 16.5494C756.279 15.1414 755.145 14 753.745 14C752.346 14 751.211 15.1414 751.211 16.5494V35.4506C751.211 36.8586 752.346 38 753.745 38C755.145 38 756.279 36.8586 756.279 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M767.323 10.5494C767.323 9.14142 766.188 8 764.789 8C763.389 8 762.254 9.14142 762.254 10.5494V41.4506C762.254 42.8586 763.389 44 764.789 44C766.188 44 767.323 42.8586 767.323 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M778.351 16.5494C778.351 15.1414 777.216 14 775.816 14C774.417 14 773.282 15.1414 773.282 16.5494V35.4506C773.282 36.8586 774.417 38 775.816 38C777.216 38 778.351 36.8586 778.351 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M789.379 10.5494C789.379 9.14142 788.244 8 786.844 8C785.445 8 784.31 9.14142 784.31 10.5494V41.4506C784.31 42.8586 785.445 44 786.844 44C788.244 44 789.379 42.8586 789.379 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M800.421 10.5494C800.421 9.14142 799.286 8 797.886 8C796.487 8 795.352 9.14142 795.352 10.5494V41.4506C795.352 42.8586 796.487 44 797.886 44C799.286 44 800.421 42.8586 800.421 41.4506V10.5494Z" fill="#94A3B8" />
                                                                        <path d="M811.448 16.5494C811.448 15.1414 810.314 14 808.914 14C807.514 14 806.38 15.1414 806.38 16.5494V35.4506C806.38 36.8586 807.514 38 808.914 38C810.314 38 811.448 36.8586 811.448 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M822.476 16.5494C822.476 15.1414 821.342 14 819.942 14C818.542 14 817.408 15.1414 817.408 16.5494V35.4506C817.408 36.8586 818.542 38 819.942 38C821.342 38 822.476 36.8586 822.476 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M833.52 16.5494C833.52 15.1414 832.385 14 830.985 14C829.586 14 828.451 15.1414 828.451 16.5494V35.4506C828.451 36.8586 829.586 38 830.985 38C832.385 38 833.52 36.8586 833.52 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M844.548 22.5494C844.548 21.1414 843.413 20 842.013 20C840.614 20 839.479 21.1414 839.479 22.5494V29.4506C839.479 30.8586 840.614 32 842.013 32C843.413 32 844.548 30.8586 844.548 29.4506V22.5494Z" fill="#94A3B8" />
                                                                        <path d="M853.041 20H853.041C851.641 20 850.507 21.1414 850.507 22.5494V29.4506C850.507 30.8586 851.641 32 853.041 32H853.041C854.441 32 855.575 30.8586 855.575 29.4506V22.5494C855.575 21.1414 854.441 20 853.041 20Z" fill="#94A3B8" />
                                                                        <path d="M866.617 22.5494C866.617 21.1414 865.483 20 864.083 20C862.683 20 861.549 21.1414 861.549 22.5494V29.4506C861.549 30.8586 862.683 32 864.083 32C865.483 32 866.617 30.8586 866.617 29.4506V22.5494Z" fill="#94A3B8" />
                                                                        <path d="M877.645 22.5494C877.645 21.1414 876.511 20 875.111 20C873.711 20 872.577 21.1414 872.577 22.5494V29.4506C872.577 30.8586 873.711 32 875.111 32C876.511 32 877.645 30.8586 877.645 29.4506V22.5494Z" fill="#94A3B8" />
                                                                        <path d="M888.673 16.5494C888.673 15.1414 887.538 14 886.139 14C884.739 14 883.604 15.1414 883.604 16.5494V35.4506C883.604 36.8586 884.739 38 886.139 38C887.538 38 888.673 36.8586 888.673 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M899.716 16.5494C899.716 15.1414 898.582 14 897.182 14C895.783 14 894.648 15.1414 894.648 16.5494V35.4506C894.648 36.8586 895.783 38 897.182 38C898.582 38 899.716 36.8586 899.716 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M908.21 4H908.21C906.81 4 905.676 5.14142 905.676 6.54944V45.4506C905.676 46.8586 906.81 48 908.21 48H908.21C909.61 48 910.744 46.8586 910.744 45.4506V6.54944C910.744 5.14142 909.61 4 908.21 4Z" fill="#94A3B8" />
                                                                        <path d="M919.238 20H919.238C917.838 20 916.704 21.1414 916.704 22.5494V29.4506C916.704 30.8586 917.838 32 919.238 32H919.238C920.638 32 921.772 30.8586 921.772 29.4506V22.5494C921.772 21.1414 920.638 20 919.238 20Z" fill="#94A3B8" />
                                                                        <path d="M932.814 16.5494C932.814 15.1414 931.679 14 930.28 14C928.88 14 927.746 15.1414 927.746 16.5494V35.4506C927.746 36.8586 928.88 38 930.28 38C931.679 38 932.814 36.8586 932.814 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M943.842 22.5494C943.842 21.1414 942.707 20 941.308 20C939.908 20 938.773 21.1414 938.773 22.5494V29.4506C938.773 30.8586 939.908 32 941.308 32C942.707 32 943.842 30.8586 943.842 29.4506V22.5494Z" fill="#94A3B8" />
                                                                        <path d="M954.87 16.5494C954.87 15.1414 953.735 14 952.336 14C950.936 14 949.801 15.1414 949.801 16.5494V35.4506C949.801 36.8586 950.936 38 952.336 38C953.735 38 954.87 36.8586 954.87 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M963.379 17H963.379C961.979 17 960.845 18.1414 960.845 19.5494V32.4506C960.845 33.8586 961.979 35 963.379 35H963.379C964.779 35 965.913 33.8586 965.913 32.4506V19.5494C965.913 18.1414 964.779 17 963.379 17Z" fill="#94A3B8" />
                                                                        <path d="M974.407 8H974.407C973.007 8 971.873 9.14142 971.873 10.5494V41.4506C971.873 42.8586 973.007 44 974.407 44H974.407C975.806 44 976.941 42.8586 976.941 41.4506V10.5494C976.941 9.14142 975.806 8 974.407 8Z" fill="#94A3B8" />
                                                                        <path d="M987.967 16.5494C987.967 15.1414 986.832 14 985.433 14C984.033 14 982.898 15.1414 982.898 16.5494V35.4506C982.898 36.8586 984.033 38 985.433 38C986.832 38 987.967 36.8586 987.967 35.4506V16.5494Z" fill="#94A3B8" />
                                                                        <path d="M999.011 19.5494C999.011 18.1414 997.876 17 996.477 17C995.077 17 993.942 18.1414 993.942 19.5494V32.4506C993.942 33.8586 995.077 35 996.477 35C997.876 35 999.011 33.8586 999.011 32.4506V19.5494Z" fill="#94A3B8" />
                                                                    </svg>
            
            
                                                                </div>
                                                                <div class="pink-bar progress progress-bar" id="progress">
                                                                    <svg width="800" preventdefault="none" height="52" viewBox="0 0 1000 52" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M6.06263 16.5495C6.06263 15.1414 4.92801 14 3.52839 14C2.12876 14 0.994141 15.1414 0.994141 16.5495V35.4505C0.994141 36.8586 2.12876 38 3.52839 38C4.92801 38 6.06263 36.8586 6.06263 35.4505V16.5495Z" fill="#F73C71" />
                                                                        <path d="M17.09 22.5495C17.09 21.1414 15.9554 20 14.5557 20C13.1561 20 12.0215 21.1414 12.0215 22.5495V29.4505C12.0215 30.8586 13.1561 32 14.5557 32C15.9554 32 17.09 30.8586 17.09 29.4505V22.5495Z" fill="#F73C71" />
                                                                        <path d="M28.1334 10.5495C28.1334 9.14143 26.9988 8 25.5992 8C24.1996 8 23.0649 9.14143 23.0649 10.5495V41.4505C23.0649 42.8586 24.1996 44 25.5992 44C26.9988 44 28.1334 42.8586 28.1334 41.4505V10.5495Z" fill="#F73C71" />
                                                                        <path d="M39.1608 14.5495C39.1608 13.1414 38.0262 12 36.6265 12C35.2269 12 34.0923 13.1414 34.0923 14.5495V37.4505C34.0923 38.8586 35.2269 40 36.6265 40C38.0262 40 39.1608 38.8586 39.1608 37.4505V14.5495Z" fill="#F73C71" />
                                                                        <path d="M50.1886 12.5495C50.1886 11.1414 49.054 10 47.6544 10C46.2547 10 45.1201 11.1414 45.1201 12.5495V39.4505C45.1201 40.8586 46.2547 42 47.6544 42C49.054 42 50.1886 40.8586 50.1886 39.4505V12.5495Z" fill="#F73C71" />
                                                                        <path d="M61.2316 16.5495C61.2316 15.1414 60.097 14 58.6973 14C57.2977 14 56.1631 15.1414 56.1631 16.5495V35.4505C56.1631 36.8586 57.2977 38 58.6973 38C60.097 38 61.2316 36.8586 61.2316 35.4505V16.5495Z" fill="#F73C71" />
                                                                        <path d="M72.2589 19.5495C72.2589 18.1414 71.1243 17 69.7247 17C68.325 17 67.1904 18.1414 67.1904 19.5495V32.4505C67.1904 33.8586 68.325 35 69.7247 35C71.1243 35 72.2589 33.8586 72.2589 32.4505V19.5495Z" fill="#F73C71" />
                                                                        <path d="M83.2868 16.5495C83.2868 15.1414 82.1521 14 80.7525 14C79.3529 14 78.2183 15.1414 78.2183 16.5495V35.4505C78.2183 36.8586 79.3529 38 80.7525 38C82.1521 38 83.2868 36.8586 83.2868 35.4505V16.5495Z" fill="#F73C71" />
                                                                        <path d="M94.3297 22.5495C94.3297 21.1414 93.1951 20 91.7955 20C90.3959 20 89.2612 21.1414 89.2612 22.5495V29.4505C89.2612 30.8586 90.3959 32 91.7955 32C93.1951 32 94.3297 30.8586 94.3297 29.4505V22.5495Z" fill="#F73C71" />
                                                                        <path d="M105.358 10.5495C105.358 9.14143 104.223 8 102.823 8C101.424 8 100.289 9.14143 100.289 10.5495V41.4505C100.289 42.8586 101.424 44 102.823 44C104.223 44 105.358 42.8586 105.358 41.4505V10.5495Z" fill="#F73C71" />
                                                                        <path d="M116.385 19.5495C116.385 18.1414 115.25 17 113.851 17C112.451 17 111.316 18.1414 111.316 19.5495V32.4505C111.316 33.8586 112.451 35 113.851 35C115.25 35 116.385 33.8586 116.385 32.4505V19.5495Z" fill="#F73C71" />
                                                                        <path d="M127.428 19.5495C127.428 18.1414 126.294 17 124.894 17C123.494 17 122.36 18.1414 122.36 19.5495V32.4505C122.36 33.8586 123.494 35 124.894 35C126.294 35 127.428 33.8586 127.428 32.4505V19.5495Z" fill="#F73C71" />
                                                                        <path d="M138.456 16.5495C138.456 15.1414 137.321 14 135.921 14C134.522 14 133.387 15.1414 133.387 16.5495V35.4505C133.387 36.8586 134.522 38 135.921 38C137.321 38 138.456 36.8586 138.456 35.4505V16.5495Z" fill="#F73C71" />
                                                                        <path d="M146.949 8H146.949C145.549 8 144.415 9.14142 144.415 10.5494V41.4506C144.415 42.8586 145.549 44 146.949 44C148.348 44 149.483 42.8586 149.483 41.4506V10.5494C149.483 9.14142 148.348 8 146.949 8Z" fill="#F73C71" />
                                                                        <path d="M157.992 8H157.992C156.593 8 155.458 9.14142 155.458 10.5494V41.4506C155.458 42.8586 156.593 44 157.992 44H157.992C159.392 44 160.527 42.8586 160.527 41.4506V10.5494C160.527 9.14142 159.392 8 157.992 8Z" fill="#F73C71" />
                                                                        <path d="M169.02 8H169.02C167.62 8 166.485 9.14142 166.485 10.5494V41.4506C166.485 42.8586 167.62 44 169.02 44C170.419 44 171.554 42.8586 171.554 41.4506V10.5494C171.554 9.14142 170.419 8 169.02 8Z" fill="#F73C71" />
                                                                        <path d="M180.047 8H180.047C178.648 8 177.513 9.14142 177.513 10.5494V41.4506C177.513 42.8586 178.648 44 180.047 44C181.447 44 182.582 42.8586 182.582 41.4506V10.5494C182.582 9.14142 181.447 8 180.047 8Z" fill="#F73C71" />
                                                                        <path d="M191.09 1H191.09C189.691 1 188.556 2.14142 188.556 3.54944V48.4506C188.556 49.8586 189.691 51 191.09 51C192.49 51 193.625 49.8586 193.625 48.4506V3.54944C193.625 2.14142 192.49 1 191.09 1Z" fill="#F73C71" />
                                                                        <path d="M202.119 8H202.119C200.719 8 199.584 9.14142 199.584 10.5494V41.4506C199.584 42.8586 200.719 44 202.119 44H202.119C203.518 44 204.653 42.8586 204.653 41.4506V10.5494C204.653 9.14142 203.518 8 202.119 8Z" fill="#F73C71" />
                                                                        <path d="M213.147 8H213.147C211.747 8 210.612 9.14142 210.612 10.5494V41.4506C210.612 42.8586 211.747 44 213.147 44C214.546 44 215.681 42.8586 215.681 41.4506V10.5494C215.681 9.14142 214.546 8 213.147 8Z" fill="#F73C71" />
                                                                        <path d="M224.189 8H224.189C222.789 8 221.654 9.14142 221.654 10.5494V41.4506C221.654 42.8586 222.789 44 224.189 44C225.588 44 226.723 42.8586 226.723 41.4506V10.5494C226.723 9.14142 225.588 8 224.189 8Z" fill="#F73C71" />
                                                                        <path d="M235.216 8H235.216C233.817 8 232.682 9.14142 232.682 10.5494V41.4506C232.682 42.8586 233.817 44 235.216 44C236.616 44 237.751 42.8586 237.751 41.4506V10.5494C237.751 9.14142 236.616 8 235.216 8Z" fill="#F73C71" />
                                                                        <path d="M246.244 8H246.244C244.845 8 243.71 9.14142 243.71 10.5494V41.4506C243.71 42.8586 244.845 44 246.244 44H246.244C247.644 44 248.778 42.8586 248.778 41.4506V10.5494C248.778 9.14142 247.644 8 246.244 8Z" fill="#F73C71" />
                                                                        <path d="M259.822 10.5494C259.822 9.14142 258.687 8 257.288 8C255.888 8 254.753 9.14142 254.753 10.5494V41.4506C254.753 42.8586 255.888 44 257.288 44C258.687 44 259.822 42.8586 259.822 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M270.85 10.5494C270.85 9.14142 269.715 8 268.315 8C266.916 8 265.781 9.14142 265.781 10.5494V41.4506C265.781 42.8586 266.916 44 268.315 44C269.715 44 270.85 42.8586 270.85 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M279.341 8H279.341C277.942 8 276.807 9.14142 276.807 10.5494V41.4506C276.807 42.8586 277.942 44 279.341 44H279.341C280.741 44 281.876 42.8586 281.876 41.4506V10.5494C281.876 9.14142 280.741 8 279.341 8Z" fill="#F73C71" />
                                                                        <path d="M290.385 8H290.385C288.986 8 287.851 9.14142 287.851 10.5494V41.4506C287.851 42.8586 288.986 44 290.385 44H290.385C291.785 44 292.92 42.8586 292.92 41.4506V10.5494C292.92 9.14142 291.785 8 290.385 8Z" fill="#F73C71" />
                                                                        <path d="M301.413 14H301.413C300.014 14 298.879 15.1414 298.879 16.5494V35.4506C298.879 36.8586 300.014 38 301.413 38H301.413C302.813 38 303.947 36.8586 303.947 35.4506V16.5494C303.947 15.1414 302.813 14 301.413 14Z" fill="#F73C71" />
                                                                        <path d="M312.441 8H312.441C311.041 8 309.907 9.14142 309.907 10.5494V41.4506C309.907 42.8586 311.041 44 312.441 44H312.441C313.841 44 314.975 42.8586 314.975 41.4506V10.5494C314.975 9.14142 313.841 8 312.441 8Z" fill="#F73C71" />
                                                                        <path d="M326.019 10.5494C326.019 9.14142 324.884 8 323.484 8C322.085 8 320.95 9.14142 320.95 10.5494V41.4506C320.95 42.8586 322.085 44 323.484 44C324.884 44 326.019 42.8586 326.019 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M334.51 8H334.51C333.111 8 331.976 9.14142 331.976 10.5494V41.4506C331.976 42.8586 333.111 44 334.51 44H334.51C335.91 44 337.045 42.8586 337.045 41.4506V10.5494C337.045 9.14142 335.91 8 334.51 8Z" fill="#F73C71" />
                                                                        <path d="M348.072 10.5494C348.072 9.14142 346.938 8 345.538 8C344.139 8 343.004 9.14142 343.004 10.5494V41.4506C343.004 42.8586 344.139 44 345.538 44C346.938 44 348.072 42.8586 348.072 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M356.582 8H356.582C355.182 8 354.048 9.14142 354.048 10.5494V41.4506C354.048 42.8586 355.182 44 356.582 44H356.582C357.982 44 359.116 42.8586 359.116 41.4506V10.5494C359.116 9.14142 357.982 8 356.582 8Z" fill="#F73C71" />
                                                                        <path d="M367.61 1H367.61C366.21 1 365.076 2.14142 365.076 3.54944V48.4506C365.076 49.8586 366.21 51 367.61 51H367.61C369.01 51 370.144 49.8586 370.144 48.4506V3.54944C370.144 2.14142 369.01 1 367.61 1Z" fill="#F73C71" />
                                                                        <path d="M381.172 10.5494C381.172 9.14142 380.037 8 378.638 8C377.238 8 376.104 9.14142 376.104 10.5494V41.4506C376.104 42.8586 377.238 44 378.638 44C380.037 44 381.172 42.8586 381.172 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M389.679 8H389.679C388.28 8 387.145 9.14142 387.145 10.5494V41.4506C387.145 42.8586 388.28 44 389.679 44H389.679C391.079 44 392.214 42.8586 392.214 41.4506V10.5494C392.214 9.14142 391.079 8 389.679 8Z" fill="#F73C71" />
                                                                        <path d="M403.241 10.5494C403.241 9.14142 402.107 8 400.707 8C399.307 8 398.173 9.14142 398.173 10.5494V41.4506C398.173 42.8586 399.307 44 400.707 44C402.107 44 403.241 42.8586 403.241 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M411.735 8H411.735C410.335 8 409.201 9.14142 409.201 10.5494V41.4506C409.201 42.8586 410.335 44 411.735 44H411.735C413.135 44 414.269 42.8586 414.269 41.4506V10.5494C414.269 9.14142 413.135 8 411.735 8Z" fill="#F73C71" />
                                                                        <path d="M422.779 8H422.779C421.379 8 420.245 9.14142 420.245 10.5494V41.4506C420.245 42.8586 421.379 44 422.779 44H422.779C424.179 44 425.313 42.8586 425.313 41.4506V10.5494C425.313 9.14142 424.179 8 422.779 8Z" fill="#F73C71" />
                                                                        <path d="M436.341 16.5494C436.341 15.1414 435.206 14 433.807 14C432.407 14 431.272 15.1414 431.272 16.5494V35.4506C431.272 36.8586 432.407 38 433.807 38C435.206 38 436.341 36.8586 436.341 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M447.369 3.54944C447.369 2.14142 446.234 1 444.835 1C443.435 1 442.3 2.14142 442.3 3.54944V48.4506C442.3 49.8586 443.435 51 444.835 51C446.234 51 447.369 49.8586 447.369 48.4506V3.54944Z" fill="#F73C71" />
                                                                        <path d="M458.41 10.5494C458.41 9.14142 457.276 8 455.876 8C454.476 8 453.342 9.14142 453.342 10.5494V41.4506C453.342 42.8586 454.476 44 455.876 44C457.276 44 458.41 42.8586 458.41 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M466.904 8H466.904C465.504 8 464.37 9.14142 464.37 10.5494V41.4506C464.37 42.8586 465.504 44 466.904 44H466.904C468.304 44 469.438 42.8586 469.438 41.4506V10.5494C469.438 9.14142 468.304 8 466.904 8Z" fill="#F73C71" />
                                                                        <path d="M477.932 1H477.932C476.532 1 475.397 2.14142 475.397 3.54944V48.4506C475.397 49.8586 476.532 51 477.932 51H477.932C479.331 51 480.466 49.8586 480.466 48.4506V3.54944C480.466 2.14142 479.331 1 477.932 1Z" fill="#F73C71" />
                                                                        <path d="M491.51 3.54944C491.51 2.14142 490.375 1 488.976 1C487.576 1 486.441 2.14142 486.441 3.54944V48.4506C486.441 49.8586 487.576 51 488.976 51C490.375 51 491.51 49.8586 491.51 48.4506V3.54944Z" fill="#F73C71" />
                                                                        <path d="M502.538 16.5494C502.538 15.1414 501.403 14 500.003 14C498.604 14 497.469 15.1414 497.469 16.5494V35.4506C497.469 36.8586 498.604 38 500.003 38C501.403 38 502.538 36.8586 502.538 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M513.564 10.5494C513.564 9.14142 512.429 8 511.029 8C509.63 8 508.495 9.14142 508.495 10.5494V41.4506C508.495 42.8586 509.63 44 511.029 44C512.429 44 513.564 42.8586 513.564 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M524.607 10.5494C524.607 9.14142 523.472 8 522.073 8C520.673 8 519.539 9.14142 519.539 10.5494V41.4506C519.539 42.8586 520.673 44 522.073 44C523.472 44 524.607 42.8586 524.607 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M535.635 10.5494C535.635 9.14142 534.5 8 533.101 8C531.701 8 530.566 9.14142 530.566 10.5494V41.4506C530.566 42.8586 531.701 44 533.101 44C534.5 44 535.635 42.8586 535.635 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M546.663 10.5494C546.663 9.14142 545.528 8 544.128 8C542.729 8 541.594 9.14142 541.594 10.5494V41.4506C541.594 42.8586 542.729 44 544.128 44C545.528 44 546.663 42.8586 546.663 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M555.156 8H555.156C553.757 8 552.622 9.14142 552.622 10.5494V41.4506C552.622 42.8586 553.757 44 555.156 44H555.156C556.556 44 557.691 42.8586 557.691 41.4506V10.5494C557.691 9.14142 556.556 8 555.156 8Z" fill="#F73C71" />
                                                                        <path d="M568.733 10.5494C568.733 9.14142 567.598 8 566.198 8C564.799 8 563.664 9.14142 563.664 10.5494V41.4506C563.664 42.8586 564.799 44 566.198 44C567.598 44 568.733 42.8586 568.733 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M579.76 10.5494C579.76 9.14142 578.626 8 577.226 8C575.827 8 574.692 9.14142 574.692 10.5494V41.4506C574.692 42.8586 575.827 44 577.226 44C578.626 44 579.76 42.8586 579.76 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M590.788 10.5494C590.788 9.14142 589.654 8 588.254 8C586.854 8 585.72 9.14142 585.72 10.5494V41.4506C585.72 42.8586 586.854 44 588.254 44C589.654 44 590.788 42.8586 590.788 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M601.832 16.5494C601.832 15.1414 600.698 14 599.298 14C597.898 14 596.764 15.1414 596.764 16.5494V35.4506C596.764 36.8586 597.898 38 599.298 38C600.698 38 601.832 36.8586 601.832 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M610.325 1H610.325C608.926 1 607.791 2.14142 607.791 3.54944V48.4506C607.791 49.8586 608.926 51 610.325 51H610.325C611.725 51 612.86 49.8586 612.86 48.4506V3.54944C612.86 2.14142 611.725 1 610.325 1Z" fill="#F73C71" />
                                                                        <path d="M621.354 8H621.354C619.954 8 618.819 9.14142 618.819 10.5494V41.4506C618.819 42.8586 619.954 44 621.354 44H621.354C622.753 44 623.888 42.8586 623.888 41.4506V10.5494C623.888 9.14142 622.753 8 621.354 8Z" fill="#F73C71" />
                                                                        <path d="M634.929 16.5494C634.929 15.1414 633.795 14 632.395 14C630.995 14 629.861 15.1414 629.861 16.5494V35.4506C629.861 36.8586 630.995 38 632.395 38C633.795 38 634.929 36.8586 634.929 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M645.957 10.5494C645.957 9.14142 644.823 8 643.423 8C642.023 8 640.889 9.14142 640.889 10.5494V41.4506C640.889 42.8586 642.023 44 643.423 44C644.823 44 645.957 42.8586 645.957 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M656.985 10.5494C656.985 9.14142 655.85 8 654.451 8C653.051 8 651.917 9.14142 651.917 10.5494V41.4506C651.917 42.8586 653.051 44 654.451 44C655.85 44 656.985 42.8586 656.985 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M665.494 8H665.494C664.095 8 662.96 9.14142 662.96 10.5494V41.4506C662.96 42.8586 664.095 44 665.494 44H665.494C666.894 44 668.028 42.8586 668.028 41.4506V10.5494C668.028 9.14142 666.894 8 665.494 8Z" fill="#F73C71" />
                                                                        <path d="M676.523 14H676.523C675.123 14 673.988 15.1414 673.988 16.5494V35.4506C673.988 36.8586 675.123 38 676.523 38H676.523C677.922 38 679.057 36.8586 679.057 35.4506V16.5494C679.057 15.1414 677.922 14 676.523 14Z" fill="#F73C71" />
                                                                        <path d="M690.082 10.5494C690.082 9.14142 688.948 8 687.548 8C686.148 8 685.014 9.14142 685.014 10.5494V41.4506C685.014 42.8586 686.148 44 687.548 44C688.948 44 690.082 42.8586 690.082 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M701.126 10.5494C701.126 9.14142 699.991 8 698.592 8C697.192 8 696.058 9.14142 696.058 10.5494V41.4506C696.058 42.8586 697.192 44 698.592 44C699.991 44 701.126 42.8586 701.126 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M712.154 16.5494C712.154 15.1414 711.019 14 709.62 14C708.22 14 707.085 15.1414 707.085 16.5494V35.4506C707.085 36.8586 708.22 38 709.62 38C711.019 38 712.154 36.8586 712.154 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M723.182 16.5494C723.182 15.1414 722.047 14 720.648 14C719.248 14 718.113 15.1414 718.113 16.5494V35.4506C718.113 36.8586 719.248 38 720.648 38C722.047 38 723.182 36.8586 723.182 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M731.692 8H731.691C730.292 8 729.157 9.14142 729.157 10.5494V41.4506C729.157 42.8586 730.292 44 731.691 44H731.692C733.091 44 734.226 42.8586 734.226 41.4506V10.5494C734.226 9.14142 733.091 8 731.692 8Z" fill="#F73C71" />
                                                                        <path d="M745.252 10.5494C745.252 9.14142 744.117 8 742.717 8C741.318 8 740.183 9.14142 740.183 10.5494V41.4506C740.183 42.8586 741.318 44 742.717 44C744.117 44 745.252 42.8586 745.252 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M756.279 16.5494C756.279 15.1414 755.145 14 753.745 14C752.346 14 751.211 15.1414 751.211 16.5494V35.4506C751.211 36.8586 752.346 38 753.745 38C755.145 38 756.279 36.8586 756.279 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M767.323 10.5494C767.323 9.14142 766.188 8 764.789 8C763.389 8 762.254 9.14142 762.254 10.5494V41.4506C762.254 42.8586 763.389 44 764.789 44C766.188 44 767.323 42.8586 767.323 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M778.351 16.5494C778.351 15.1414 777.216 14 775.816 14C774.417 14 773.282 15.1414 773.282 16.5494V35.4506C773.282 36.8586 774.417 38 775.816 38C777.216 38 778.351 36.8586 778.351 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M789.379 10.5494C789.379 9.14142 788.244 8 786.844 8C785.445 8 784.31 9.14142 784.31 10.5494V41.4506C784.31 42.8586 785.445 44 786.844 44C788.244 44 789.379 42.8586 789.379 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M800.421 10.5494C800.421 9.14142 799.286 8 797.886 8C796.487 8 795.352 9.14142 795.352 10.5494V41.4506C795.352 42.8586 796.487 44 797.886 44C799.286 44 800.421 42.8586 800.421 41.4506V10.5494Z" fill="#F73C71" />
                                                                        <path d="M811.448 16.5494C811.448 15.1414 810.314 14 808.914 14C807.514 14 806.38 15.1414 806.38 16.5494V35.4506C806.38 36.8586 807.514 38 808.914 38C810.314 38 811.448 36.8586 811.448 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M822.476 16.5494C822.476 15.1414 821.342 14 819.942 14C818.542 14 817.408 15.1414 817.408 16.5494V35.4506C817.408 36.8586 818.542 38 819.942 38C821.342 38 822.476 36.8586 822.476 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M833.52 16.5494C833.52 15.1414 832.385 14 830.985 14C829.586 14 828.451 15.1414 828.451 16.5494V35.4506C828.451 36.8586 829.586 38 830.985 38C832.385 38 833.52 36.8586 833.52 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M844.548 22.5494C844.548 21.1414 843.413 20 842.013 20C840.614 20 839.479 21.1414 839.479 22.5494V29.4506C839.479 30.8586 840.614 32 842.013 32C843.413 32 844.548 30.8586 844.548 29.4506V22.5494Z" fill="#F73C71" />
                                                                        <path d="M853.041 20H853.041C851.641 20 850.507 21.1414 850.507 22.5494V29.4506C850.507 30.8586 851.641 32 853.041 32H853.041C854.441 32 855.575 30.8586 855.575 29.4506V22.5494C855.575 21.1414 854.441 20 853.041 20Z" fill="#F73C71" />
                                                                        <path d="M866.617 22.5494C866.617 21.1414 865.483 20 864.083 20C862.683 20 861.549 21.1414 861.549 22.5494V29.4506C861.549 30.8586 862.683 32 864.083 32C865.483 32 866.617 30.8586 866.617 29.4506V22.5494Z" fill="#F73C71" />
                                                                        <path d="M877.645 22.5494C877.645 21.1414 876.511 20 875.111 20C873.711 20 872.577 21.1414 872.577 22.5494V29.4506C872.577 30.8586 873.711 32 875.111 32C876.511 32 877.645 30.8586 877.645 29.4506V22.5494Z" fill="#F73C71" />
                                                                        <path d="M888.673 16.5494C888.673 15.1414 887.538 14 886.139 14C884.739 14 883.604 15.1414 883.604 16.5494V35.4506C883.604 36.8586 884.739 38 886.139 38C887.538 38 888.673 36.8586 888.673 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M899.716 16.5494C899.716 15.1414 898.582 14 897.182 14C895.783 14 894.648 15.1414 894.648 16.5494V35.4506C894.648 36.8586 895.783 38 897.182 38C898.582 38 899.716 36.8586 899.716 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M908.21 4H908.21C906.81 4 905.676 5.14142 905.676 6.54944V45.4506C905.676 46.8586 906.81 48 908.21 48H908.21C909.61 48 910.744 46.8586 910.744 45.4506V6.54944C910.744 5.14142 909.61 4 908.21 4Z" fill="#F73C71" />
                                                                        <path d="M919.238 20H919.238C917.838 20 916.704 21.1414 916.704 22.5494V29.4506C916.704 30.8586 917.838 32 919.238 32H919.238C920.638 32 921.772 30.8586 921.772 29.4506V22.5494C921.772 21.1414 920.638 20 919.238 20Z" fill="#F73C71" />
                                                                        <path d="M932.814 16.5494C932.814 15.1414 931.679 14 930.28 14C928.88 14 927.746 15.1414 927.746 16.5494V35.4506C927.746 36.8586 928.88 38 930.28 38C931.679 38 932.814 36.8586 932.814 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M943.842 22.5494C943.842 21.1414 942.707 20 941.308 20C939.908 20 938.773 21.1414 938.773 22.5494V29.4506C938.773 30.8586 939.908 32 941.308 32C942.707 32 943.842 30.8586 943.842 29.4506V22.5494Z" fill="#F73C71" />
                                                                        <path d="M954.87 16.5494C954.87 15.1414 953.735 14 952.336 14C950.936 14 949.801 15.1414 949.801 16.5494V35.4506C949.801 36.8586 950.936 38 952.336 38C953.735 38 954.87 36.8586 954.87 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M963.379 17H963.379C961.979 17 960.845 18.1414 960.845 19.5494V32.4506C960.845 33.8586 961.979 35 963.379 35H963.379C964.779 35 965.913 33.8586 965.913 32.4506V19.5494C965.913 18.1414 964.779 17 963.379 17Z" fill="#F73C71" />
                                                                        <path d="M974.407 8H974.407C973.007 8 971.873 9.14142 971.873 10.5494V41.4506C971.873 42.8586 973.007 44 974.407 44H974.407C975.806 44 976.941 42.8586 976.941 41.4506V10.5494C976.941 9.14142 975.806 8 974.407 8Z" fill="#F73C71" />
                                                                        <path d="M987.967 16.5494C987.967 15.1414 986.832 14 985.433 14C984.033 14 982.898 15.1414 982.898 16.5494V35.4506C982.898 36.8586 984.033 38 985.433 38C986.832 38 987.967 36.8586 987.967 35.4506V16.5494Z" fill="#F73C71" />
                                                                        <path d="M999.011 19.5494C999.011 18.1414 997.876 17 996.477 17C995.077 17 993.942 18.1414 993.942 19.5494V32.4506C993.942 33.8586 995.077 35 996.477 35C997.876 35 999.011 33.8586 999.011 32.4506V19.5494Z" fill="#F73C71" />
                                                                    </svg>
            
            
            
            
                                                                </div>
                                                                <div class="progress-container" id="progress-container">
                                                                    <div class="progress" id="progress"></div>
                                                                </div>
                                                            </div>
                                                            <audio class="audio audio_player recordedAudio" id="recordedAudio" controls style="display: none;"></audio>
                                                        </div>
                                                        <div class="time">
                                                            <span class="time-elapsed">00:00</span>
            
                                                            <span class="time-duration d-none"></span>
                                                        </div>
                                                    </div>
                                                </div>
                                                <button class="close-audio-btn"><svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M8.4974 0.666016C3.90573 0.666016 0.164062 4.40768 0.164062 8.99935C0.164062 13.591 3.90573 17.3327 8.4974 17.3327C13.0891 17.3327 16.8307 13.591 16.8307 8.99935C16.8307 4.40768 13.0891 0.666016 8.4974 0.666016ZM11.2974 10.916C11.5391 11.1577 11.5391 11.5577 11.2974 11.7993C11.1724 11.9243 11.0141 11.9827 10.8557 11.9827C10.6974 11.9827 10.5391 11.9243 10.4141 11.7993L8.4974 9.88268L6.58073 11.7993C6.45573 11.9243 6.2974 11.9827 6.13906 11.9827C5.98073 11.9827 5.8224 11.9243 5.6974 11.7993C5.45573 11.5577 5.45573 11.1577 5.6974 10.916L7.61406 8.99935L5.6974 7.08268C5.45573 6.84102 5.45573 6.44102 5.6974 6.19935C5.93906 5.95768 6.33906 5.95768 6.58073 6.19935L8.4974 8.11602L10.4141 6.19935C10.6557 5.95768 11.0557 5.95768 11.2974 6.19935C11.5391 6.44102 11.5391 6.84102 11.2974 7.08268L9.38073 8.99935L11.2974 10.916Z" fill="#F73C71" />
                                                    </svg></button>
                                            </div>
                                        </div>
                                        <div class="message-perent">
                                            <textarea type="text" placeholder="Write message here..." id="message-box"  rows="1" style="overflow:hidden; resize:none;" class="send-message"></textarea>
                                            <div class="d-flex ms-auto">
                                                <div class="dropdown">
                                                    <button type="button" class="btn btn-primary dropdown-toggle p-0" data-bs-toggle="dropdown">
                                                        <svg class="me-3" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M12.7028 11.8002L11.2928 13.2102C10.5128 13.9902 10.5128 15.2602 11.2928 16.0402C12.0728 16.8202 13.3428 16.8202 14.1228 16.0402L16.3428 13.8202C17.9028 12.2602 17.9028 9.73023 16.3428 8.16023C14.7828 6.60023 12.2528 6.60023 10.6828 8.16023L8.26281 10.5802C6.92281 11.9202 6.92281 14.0902 8.26281 15.4302" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M12.5 22C18.0228 22 22.5 17.5228 22.5 12C22.5 6.47715 18.0228 2 12.5 2C6.97715 2 2.5 6.47715 2.5 12C2.5 17.5228 6.97715 22 12.5 22Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                    <form id="upload-files">
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <button class="button-wrapper">
                                                                <span class="label">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M14.8667 18.9577H6.1334C3.80006 18.9577 2.31673 17.566 2.1834 15.241L1.75006 8.36602C1.6834 7.32435 2.04173 6.32435 2.7584 5.56602C3.46673 4.80768 4.46673 4.37435 5.50006 4.37435C5.76673 4.37435 6.02506 4.21602 6.15006 3.96602L6.75006 2.77435C7.24173 1.79935 8.47506 1.04102 9.55006 1.04102H11.4584C12.5334 1.04102 13.7584 1.79935 14.2501 2.76602L14.8501 3.98268C14.9751 4.21602 15.2251 4.37435 15.5001 4.37435C16.5334 4.37435 17.5334 4.80768 18.2417 5.56602C18.9584 6.33268 19.3167 7.32435 19.2501 8.36602L18.8167 15.2493C18.6667 17.6077 17.2251 18.9577 14.8667 18.9577ZM9.55006 2.29102C8.9334 2.29102 8.15006 2.77435 7.86673 3.33268L7.26673 4.53268C6.91673 5.20768 6.24173 5.62435 5.50006 5.62435C4.80006 5.62435 4.15006 5.90768 3.66673 6.41602C3.19173 6.92435 2.95006 7.59102 3.00006 8.28268L3.4334 15.166C3.5334 16.8493 4.44173 17.7077 6.1334 17.7077H14.8667C16.5501 17.7077 17.4584 16.8493 17.5667 15.166L18.0001 8.28268C18.0417 7.59102 17.8084 6.92435 17.3334 6.41602C16.8501 5.90768 16.2001 5.62435 15.5001 5.62435C14.7584 5.62435 14.0834 5.20768 13.7334 4.54935L13.1251 3.33268C12.8501 2.78268 12.0667 2.29935 11.4501 2.29935H9.55006V2.29102Z" fill="#64748B" />
                                                                        <path d="M11.75 7.29102H9.25C8.90833 7.29102 8.625 7.00768 8.625 6.66602C8.625 6.32435 8.90833 6.04102 9.25 6.04102H11.75C12.0917 6.04102 12.375 6.32435 12.375 6.66602C12.375 7.00768 12.0917 7.29102 11.75 7.29102Z" fill="#64748B" />
                                                                        <path d="M10.4974 15.6257C8.65573 15.6257 7.16406 14.134 7.16406 12.2923C7.16406 10.4507 8.65573 8.95898 10.4974 8.95898C12.3391 8.95898 13.8307 10.4507 13.8307 12.2923C13.8307 14.134 12.3391 15.6257 10.4974 15.6257ZM10.4974 10.209C9.3474 10.209 8.41406 11.1423 8.41406 12.2923C8.41406 13.4423 9.3474 14.3757 10.4974 14.3757C11.6474 14.3757 12.5807 13.4423 12.5807 12.2923C12.5807 11.1423 11.6474 10.209 10.4974 10.209Z" fill="#64748B" />
                                                                    </svg> Camera
                                                                </span>
                                                                <input type="file" name="upload" id="file1"  class="upload-box upload" accept="image/*" capture="camera" aria-label="Upload Camera File">
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="button-wrapper">
                                                                <span class="label">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M12.9974 18.9577H7.9974C3.4724 18.9577 1.53906 17.0243 1.53906 12.4993V7.49935C1.53906 2.97435 3.4724 1.04102 7.9974 1.04102H12.9974C17.5224 1.04102 19.4557 2.97435 19.4557 7.49935V12.4993C19.4557 17.0243 17.5224 18.9577 12.9974 18.9577ZM7.9974 2.29102C4.15573 2.29102 2.78906 3.65768 2.78906 7.49935V12.4993C2.78906 16.341 4.15573 17.7077 7.9974 17.7077H12.9974C16.8391 17.7077 18.2057 16.341 18.2057 12.4993V7.49935C18.2057 3.65768 16.8391 2.29102 12.9974 2.29102H7.9974Z" fill="#64748B" />
                                                                        <path d="M8.0026 8.95833C6.73594 8.95833 5.71094 7.93333 5.71094 6.66667C5.71094 5.4 6.73594 4.375 8.0026 4.375C9.26927 4.375 10.2943 5.4 10.2943 6.66667C10.2943 7.93333 9.26927 8.95833 8.0026 8.95833ZM8.0026 5.625C7.4276 5.625 6.96094 6.09167 6.96094 6.66667C6.96094 7.24167 7.4276 7.70833 8.0026 7.70833C8.5776 7.70833 9.04427 7.24167 9.04427 6.66667C9.04427 6.09167 8.5776 5.625 8.0026 5.625Z" fill="#64748B" />
                                                                        <path d="M2.724 16.4168C2.524 16.4168 2.324 16.3168 2.20733 16.1418C2.01566 15.8585 2.09066 15.4668 2.38233 15.2751L6.49066 12.5168C7.39066 11.9085 8.63233 11.9835 9.449 12.6751L9.724 12.9168C10.1407 13.2751 10.849 13.2751 11.2573 12.9168L14.724 9.9418C15.6073 9.18346 16.999 9.18346 17.8907 9.9418L19.249 11.1085C19.5073 11.3335 19.5407 11.7251 19.3157 11.9918C19.0907 12.2501 18.699 12.2835 18.4323 12.0585L17.074 10.8918C16.6573 10.5335 15.949 10.5335 15.5323 10.8918L12.0657 13.8668C11.1823 14.6251 9.79066 14.6251 8.899 13.8668L8.624 13.6251C8.24066 13.3001 7.60733 13.2668 7.18233 13.5585L3.074 16.3168C2.96566 16.3835 2.84066 16.4168 2.724 16.4168Z" fill="#64748B" />
                                                                    </svg> Gallery
                                                                </span>
                                                                <input type="file" name="upload" id="file2"  class="upload-box upload" accept="image/*,video/*" aria-label="Upload Gallery File">
                                                            </button>
                                                        </li>
                                                        <li>
                                                            <button class="button-wrapper">
                                                                <span class="label">
                                                                    <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M8.00052 14.7917C7.65885 14.7917 7.37552 14.5084 7.37552 14.1667V10.6751L6.77552 11.2751C6.53385 11.5167 6.13385 11.5167 5.89219 11.2751C5.65052 11.0334 5.65052 10.6334 5.89219 10.3917L7.55885 8.72507C7.73385 8.55007 8.00885 8.49174 8.24219 8.59174C8.47552 8.68341 8.62552 8.91674 8.62552 9.16674V14.1667C8.62552 14.5084 8.34219 14.7917 8.00052 14.7917Z" fill="#64748B" />
                                                                        <path d="M9.66458 11.4576C9.50625 11.4576 9.34792 11.3992 9.22292 11.2742L7.55625 9.60755C7.31458 9.36589 7.31458 8.96589 7.55625 8.72422C7.79792 8.48255 8.19792 8.48255 8.43958 8.72422L10.1062 10.3909C10.3479 10.6326 10.3479 11.0326 10.1062 11.2742C9.98125 11.3992 9.82292 11.4576 9.66458 11.4576Z" fill="#64748B" />
                                                                        <path d="M12.9974 18.9577H7.9974C3.4724 18.9577 1.53906 17.0243 1.53906 12.4993V7.49935C1.53906 2.97435 3.4724 1.04102 7.9974 1.04102H12.1641C12.5057 1.04102 12.7891 1.32435 12.7891 1.66602C12.7891 2.00768 12.5057 2.29102 12.1641 2.29102H7.9974C4.15573 2.29102 2.78906 3.65768 2.78906 7.49935V12.4993C2.78906 16.341 4.15573 17.7077 7.9974 17.7077H12.9974C16.8391 17.7077 18.2057 16.341 18.2057 12.4993V8.33268C18.2057 7.99102 18.4891 7.70768 18.8307 7.70768C19.1724 7.70768 19.4557 7.99102 19.4557 8.33268V12.4993C19.4557 17.0243 17.5224 18.9577 12.9974 18.9577Z" fill="#64748B" />
                                                                        <path d="M18.8307 8.95841H15.4974C12.6474 8.95841 11.5391 7.85007 11.5391 5.00007V1.66674C11.5391 1.41674 11.6891 1.18341 11.9224 1.09174C12.1557 0.991739 12.4224 1.05007 12.6057 1.22507L19.2724 7.89174C19.4474 8.06674 19.5057 8.34174 19.4057 8.57507C19.3057 8.8084 19.0807 8.95841 18.8307 8.95841ZM12.7891 3.17507V5.00007C12.7891 7.15007 13.3474 7.70841 15.4974 7.70841H17.3224L12.7891 3.17507Z" fill="#64748B" />
                                                                    </svg> File
                                                                </span>
                                                                <input type="file" name="upload" id="file3"  class="upload-box upload" accept=".doc,.docx,.pdf,.txt" aria-label="Upload File">
                                                            </button>
                                                        </li>
                                                    </ul>
                                                    </form>
                                                </div>
                                                <div>
                                                    <div id="audioControls">
                                                        <button type="button" class="close-song" style="display: none;">
                                                            <svg width="17" height="18" viewBox="0 0 17 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M8.4974 0.666016C3.90573 0.666016 0.164062 4.40768 0.164062 8.99935C0.164062 13.591 3.90573 17.3327 8.4974 17.3327C13.0891 17.3327 16.8307 13.591 16.8307 8.99935C16.8307 4.40768 13.0891 0.666016 8.4974 0.666016ZM11.2974 10.916C11.5391 11.1577 11.5391 11.5577 11.2974 11.7993C11.1724 11.9243 11.0141 11.9827 10.8557 11.9827C10.6974 11.9827 10.5391 11.9243 10.4141 11.7993L8.4974 9.88268L6.58073 11.7993C6.45573 11.9243 6.2974 11.9827 6.13906 11.9827C5.98073 11.9827 5.8224 11.9243 5.6974 11.7993C5.45573 11.5577 5.45573 11.1577 5.6974 10.916L7.61406 8.99935L5.6974 7.08268C5.45573 6.84102 5.45573 6.44102 5.6974 6.19935C5.93906 5.95768 6.33906 5.95768 6.58073 6.19935L8.4974 8.11602L10.4141 6.19935C10.6557 5.95768 11.0557 5.95768 11.2974 6.19935C11.5391 6.44102 11.5391 6.84102 11.2974 7.08268L9.38073 8.99935L11.2974 10.916Z" fill="#F73C71">
                                                            </svg>
                                                        </button>
            
                                                        <button id="stopRecording" style="display: none;"><i class="fa-solid fa-pause"></i></button>
                                                        <button id="playRecording" style="display: none;">Play Recording</button>
                                                        <button id="stopPlayback" style="display: none;">Stop Playback</button>
                                                    </div>
            
            
            
                                                    <div>
                                                        <span id="startRecording">
                                                            <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M12.5 15.5C14.71 15.5 16.5 13.71 16.5 11.5V6C16.5 3.79 14.71 2 12.5 2C10.29 2 8.5 3.79 8.5 6V11.5C8.5 13.71 10.29 15.5 12.5 15.5Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M4.85156 9.65039V11.3504C4.85156 15.5704 8.28156 19.0004 12.5016 19.0004C16.7216 19.0004 20.1516 15.5704 20.1516 11.3504V9.65039" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M11.1094 6.42914C12.0094 6.09914 12.9894 6.09914 13.8894 6.42914" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M11.7031 8.55031C12.2331 8.41031 12.7831 8.41031 13.3131 8.55031" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M12.5 19V22" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
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
                        <input type="hidden" value="{{($user_id!=null)?encrypt($user_id):""}}" name="user_id" id="user_id"/>
                        <input type="hidden" value="{{encrypt($event_id)}}" name="event_id" id="event_id"/>
                        <input type="hidden" value="{{($sync_contact_user_id!="")?encrypt($sync_contact_user_id):""}}" name="sync_id" id="sync_id"/>
                        <input type="hidden" value="{{($event_invited_user_id!="")?encrypt($event_invited_user_id):""}}" name="event_invited_user_id" id="event_invited_user_id"/>

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
                                                    <input type="text" name="firstname" value="{{$user_firstname}}" id="firstname" autocomplete="off" class="form-control inputText firstname yes_firstname" maxlength="50" >
                                                    <label for="Fname" class="form-label input-field floating-label">First Name</label>
                                                </div>
                                                <label id="firstnameErrorLabel" class="error" for="firstname"></label>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="input-form">
                                                    <input type="text"  name="lastname" value="{{$user_lastname}}" id="lastname" autocomplete="off" class="form-control inputText lastname yes_lastname" maxlength="50" >
                                                    <label for="Fname" class="form-label input-field floating-label">Last Name</label>
                                                </div>
                                                <label id="lastnameErrorLabel" class="error" for="lastname"></label>

                                            </div>
                                            <div class="col-lg-12">
                                                <div class="input-form">                                                                
                                                    <input type="email"  name="email" id="email" value="{{(isset($email)&&$email!="")?$email:""}}" class="form-control inputText" {{$email!=""?"readonly":''}}>
                                                    <label for="Fname" class="form-label input-field floating-label">Email Address</label>
                                                    
                                                </div>
                                                <label id="emailErrorLabel" class="error" for="email"></label>
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
                                            <input type="number" name="adults" id="adults" value="0" class="input-qty" autocomplete="off" readonly>
                                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        <div>
                                            <h6>Kids</h6>
                                            <div class="qty-container ms-auto">
                                            <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                            <input type="number" name="kids" id="kids" value="0" class="input-qty" readonly>
                                            <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                            </div>
                                        </div>
                                        </div>
                                    </div>
                                    <div class="rsvp-msgbox">
                                        <h5>Message</h5>
                                        <div class="input-form">
                                            <textarea class="form-control inputText message_to_host" id="message_to_host" autocomplete="off" name="message_to_host" message=""  maxlength="200"></textarea>
                                            <label for="Fname" class="form-label input-field floating-label">Message to send with your RSVP</label>
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
                                            <input class="form-check-input notifications" type="checkbox" name="notifications[]"  value="1" autocomplete="off" id="flexCheckDefault" checked>
                                            <label class="form-check-label" for="flexCheckDefault">
                                                All event activity <br>
                                                (Wall posts, potluck activity,  photo uploads, event updates, messages)
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input notifications" name="notifications[]" type="checkbox" value="wall_post"  autocomplete="off" id="flexCheckDefault1" >
                                            <label class="form-check-label" for="flexCheckDefault1">
                                                Wall Posts & Updates<br>
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input notifications" name="notifications[]" type="checkbox" value="guest_rsvp" autocomplete="off" id="flexCheckDefault1" >
                                            <label class="form-check-label" for="flexCheckDefault1">
                                                Guests RSVP Responses <br>
                                            </label>
                                        </div>
                                        {{-- <div class="form-check">
                                            <input class="form-check-input notifications" name="notifications[]" type="checkbox" value="1" id="flexCheckDefault1">
                                            <label class="form-check-label" for="flexCheckDefault1">
                                                Special offers ( From Yesvite & partners) <br>
                                            </label>
                                        </div> --}}
                                    </div>
                    </div>
                                <div class="modal-footer">
                                    {{-- <button type="button" class="btn btn-secondary yes_rsvp_btn" data-bs-dismiss="modal">RSVP</button> --}}
                                    <button type="button" class="btn btn-secondary yes_rsvp_btn">RSVP</button>
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
                    <input type="hidden" value="{{($user_id!=null)?encrypt($user_id):""}}" name="user_id" id="user_id"/>
                    <input type="hidden" value="{{encrypt($event_id)}}" name="event_id" id="event_id"/>
                    <input type="hidden" value="{{($sync_contact_user_id!="")?encrypt($sync_contact_user_id):""}}" name="sync_id" id="sync_id"/>
                        <input type="hidden" value="{{($event_invited_user_id!="")?encrypt($event_invited_user_id):""}}" name="event_invited_user_id" id="event_invited_user_id"/>

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
                                            <input type="text" name="firstname" value="{{$user_firstname}}" id="firstname" class="form-control inputText firstname no_firstname" autocomplete="off" maxlength="50" >
                                            <label for="Fname" class="form-label input-field floating-label">First Name</label>
                                        </div>
                                        <label id="firstnameErrorLabelno" class="error" for="firstname"></label>

                                    </div>
                                    <div class="col-lg-6">
                                        <div class="input-form">
                                            <input type="text" name="lastname" value="{{$user_lastname}}" id="lastname" class="form-control inputText lastname no_lastname" autocomplete="off" maxlength="50" >
                                            <label for="Lname" class="form-label input-field floating-label">Last Name</label>
                                        </div>
                                        <label id="lastnameErrorLabelno" class="error" for="lastname"></label>

                                    </div>
                                    <div class="col-lg-12">
                                        <div class="input-form">
                                                                @if($email!="")
                                                                    <input type="email"  name="email" id="email" value="{{(isset($email)&&$email!="")?$email:""}}" class="form-control inputText" readonly>
                                                                 @else
                                                                    <input type="email"  name="email" id="email" value="{{(isset($email)&&$email!="")?$email:""}}" class="form-control inputText">
                                                                @endif
                                            <!-- <input type="email" name="email" value="{{(isset($email)&&$email!="")?$email:""}}" class="form-control inputText" readonly> -->
                                            <label for="email" class="form-label input-field floating-label">Email Address</label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <div class="rsvp-msgbox">
                                <h5>Message</h5>
                                <div class="input-form">
                                    <textarea class="form-control inputText message_to_host" id="message_to_host" autocomplete="off" name="message_to_host"  maxlength="200"></textarea>
                                    <label for="Fname" class="form-label input-field floating-label">Message to send with your RSVP</label>
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
                            <button type="button" class="btn btn-secondary no_rsvp_btn">Cancel RSVP</button>
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
                    <!-- <h4 class="modal-title" id="aboutsuccessLabel">Guest List ({{ count($getInvitedusers['all_invited_users']?? []) }}
                        Guests)</h4> -->
                    <h4 class="modal-title" id="aboutsuccessLabel">Guest List</h4>
                  </div>
              </div>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <div class="guest-user-list rsvp-guest-user-list-wrp">
                @foreach ($getInvitedusers['all_invited_users'] as $guest_data )
                    @php
                                            $yes_modal="";
                                            $no_modal="";
                                            if($user_id==$guest_data['id'])
                                            {
                                                    $yes_modal="#rsvp-yes-modal";
                                                    $no_modal="#rsvp-no-modal"; 
                                            }
                                            if($guest_data['rsvp_status']=="1"){
                                                $open_modal=$no_modal;
                                            }elseif($guest_data['rsvp_status']=="0"){
                                                $open_modal=$yes_modal;
                                            }else{
                                                $open_modal="";
                                            }
                    @endphp
                      {{-- @if($is_host == "1" || 
                    ($user_id == $guest_data['id']) || 
                    ($user_id != $guest_data['id'] && ($guest_data['rsvp_status'] == "1" || $guest_data['rsvp_status'] == "0"))) --}}
                        <div class="guest-user-box">
                            <div class="guest-list-data">
                            <div class="guest-img">
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
                            </div>
                            <div class="w-100">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div class="d-flex flex-column">
                                    <p href="#" class="guest-name">{{$guest_data['first_name']}} {{$guest_data['last_name']}}</p>
                                    <span class="guest-email">{{($guest_data['email']!="")?$guest_data['email'] :$guest_data['phone_number']}}</span>
                                    </div>
                                    @if($rsvp_status!="" &&($user_id==$guest_data['id']))
                                        <button class="guest-list-edit-btn" data-bs-toggle="modal" data-bs-target={{$open_modal}}>
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.16406 1.66602H7.4974C3.33073 1.66602 1.66406 3.33268 1.66406 7.49935V12.4993C1.66406 16.666 3.33073 18.3327 7.4974 18.3327H12.4974C16.6641 18.3327 18.3307 16.666 18.3307 12.4993V10.8327" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            <path d="M12.4219 3.45898C12.9802 5.45065 14.5385 7.00898 16.5385 7.57565" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    @endif
                                    @if($rsvp_status!="" && empty($user_id) && !empty($sync_contact_user_id) && $sync_contact_user_id == $guest_data['id'])
                                        <button class="guest-list-edit-btn" data-bs-toggle="modal" data-bs-target={{$open_modal}}>
                                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M9.16406 1.66602H7.4974C3.33073 1.66602 1.66406 3.33268 1.66406 7.49935V12.4993C1.66406 16.666 3.33073 18.3327 7.4974 18.3327H12.4974C16.6641 18.3327 18.3307 16.666 18.3307 12.4993V10.8327" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M12.4219 3.45898C12.9802 5.45065 14.5385 7.00898 16.5385 7.57565" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                                </svg>
                                            </button>
                                    @endif
                                </div>
                                @if($is_host=="1")
                                    @if($guest_data['rsvp_status']=="1")
                                        <div class="sucess-yes"  data-bs-toggle="modal" data-bs-target="{{$no_modal}}">
                                        <h5 class="green">RSVP'd YES</h5>
                                        <div class="sucesss-cat ms-auto">
                                            <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                            <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                            <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                            <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                            </svg>
                                            {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                            <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                            <h5>
                                                {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                            </h5>
                                            <h5>
                                                {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                            </h5>
                                        </div>
                                        </div>
                                    @elseif ($guest_data['rsvp_status']=="0")
                                        <div class="sucess-no" sucess-yes data-bs-toggle="modal" data-bs-target="{{$yes_modal}}">
                                            <h5>NO</h5>
                                        </div>
                                    @else
                                        <div class="no-reply">
                                            <h5>RSVP Not Received</h5>
                                        </div>
                                    @endif
                                @elseif($user_id==$guest_data['id'])
                                        @if($guest_data['rsvp_status']=="1")
                                            <div class="sucess-yes"  data-bs-toggle="modal" data-bs-target="{{$no_modal}}">
                                            <h5 class="green">RSVP'd YES</h5>
                                            <div class="sucesss-cat ms-auto">
                                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                </svg>
                                                {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                                <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                                <h5>
                                                    {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                </h5>
                                                <h5>
                                                    {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                </h5>
                                            </div>
                                            </div>
                                        @elseif ($guest_data['rsvp_status']=="0")
                                            <div class="sucess-no" sucess-yes data-bs-toggle="modal" data-bs-target="{{$yes_modal}}">
                                                <h5>NO</h5>
                                            </div>
                                        @else
                                            <div class="no-reply">
                                                <h5>RSVP Not Received</h5>
                                            </div>
                                        @endif
                                @elseif($rsvp_status!="" && empty($user_id) && !empty($sync_contact_user_id) && $sync_contact_user_id == $guest_data['id']) 
                                            @if($guest_data['rsvp_status']=="1")
                                            <div class="sucess-yes"  data-bs-toggle="modal" data-bs-target="{{$no_modal}}">
                                            <h5 class="green">RSVP'd YES</h5>
                                            <div class="sucesss-cat ms-auto">
                                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                </svg>
                                                {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                                <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                                <h5>
                                                    {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                </h5>
                                                <h5>
                                                    {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                </h5>
                                            </div>
                                            </div>
                                            @elseif ($guest_data['rsvp_status']=="0")
                                            <div class="sucess-no" sucess-yes data-bs-toggle="modal" data-bs-target="{{$yes_modal}}">
                                                <h5>NO</h5>
                                            </div>
                                            @else
                                            <div class="no-reply">
                                                <h5>RSVP Not Received</h5>
                                            </div>
                                            @endif   
                                @else
                                    @if($guest_data['rsvp_status']=="1")
                                            <div class="sucess-yes"  data-bs-toggle="modal" data-bs-target="{{$no_modal}}">
                                            <h5 class="green">RSVP'd YES</h5>
                                            <div class="sucesss-cat ms-auto">
                                                <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                </svg>
                                                {{-- <h5>{{$guest_data['adults']}} Adults</h5>
                                                <h5>{{$guest_data['kids']}} Kids</h5> --}}
                                                <h5>
                                                    {{$guest_data['adults'] == 1 ? $guest_data['adults'] . ' Adult' : $guest_data['adults'] . ' Adults'}}
                                                </h5>
                                                <h5>
                                                    {{$guest_data['kids'] == 1 ? $guest_data['kids'] . ' Kid' : $guest_data['kids'] . ' Kids'}}
                                                </h5>
                                            </div>
                                            </div>
                                            @elseif ($guest_data['rsvp_status']=="0")
                                            <div class="sucess-no" sucess-yes data-bs-toggle="modal" data-bs-target="{{$yes_modal}}">
                                                <h5>NO</h5>
                                            </div>
                                    @endif       
                                    @endif           
                                <div class="rsvp-guest-user-replay">
                                    @if($guest_data['message_to_host']!="")
                                    <h6>“ {{$guest_data['message_to_host']}} “</h6>
                                @endif
                            </div>
                            </div>
                            </div>
                            
                        </div>
                    {{-- @endif --}}
                @endforeach

                {{-- @foreach ($getInvitedusers['invited_guests'] as $guest_data )

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
                        @elseif ($guest_data['rsvp_status']=="0")
                        <div class="sucess-no">
                            <h5>NO</h5>
                        </div>
                        @else
                        <div class="no-reply">
                            <h5>RSVP Not Received</h5>
                          </div>
                        @endif
                        <div class="rsvp-guest-user-replay">
                            @if($guest_data['message_to_host']!="")
                            <h6>“ {{$guest_data['message_to_host']}} “</h6>
                        @endif
                     
                        <div class="rsvp-guest-user-replay">
                            @if(isset($guest_data['message_to_host'])&&$guest_data['message_to_host']!="")
                            <h6>“ {{$guest_data['message_to_host']}} “</h6>
                        @endif
                     </div>
                    </div>
                    </div>
                    
                </div>
            @endforeach --}}
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

@if ($userId!=0)

@push('scripts')
    

    <script src="https://cdnjs.cloudflare.com/ajax/libs/timeago.js/4.0.2/timeago.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
    <script type="module" src="{{asset('assets/front/js/audio.js')}}"></script>
    <script type="module" src="{{asset('assets/front/js/chat.js')}}"></script>
    <script type="module" src="{{asset('assets/front/js/message.js')}}"></script>
  
@endpush
@endif
@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/ical.js/1.4.0/ical.min.js"></script>
<script>
// $(document).ready(function () {
//     toastr.options = {
//                     "closeButton": true,
//                     "progressBar": true,
//                     "positionClass": "toast-top-right",
//                 };
//             @if (session('msg'))
//                 toastr.options = {
//                     "closeButton": true,
//                     "progressBar": true,
//                     "positionClass": "toast-top-right",
//                 };
//                 toastr.success("{{ session('msg') }}");
//             @endif
//         });

    // const createICSFile = (
    //             start,
    //             end,
    //             title,
    //             description,
    //             location
    //         ) => {
    //             // Create a new calendar object
    //             const calendar = new ICAL.Component(["vcalendar", [], []]);

    //             // Add calendar metadata
    //             calendar.updatePropertyWithValue("version", "2.0");
    //             calendar.updatePropertyWithValue(
    //                 "prodid",
    //                 "-//Your Company//Your App//EN"
    //             );

    //             // Create an event component
    //             const event = new ICAL.Component("vevent");

    //             // Convert JavaScript Date objects to iCalendar format
    //             const startICAL = ICAL.Time.fromJSDate(start, true); // UTC
    //             const endICAL = ICAL.Time.fromJSDate(end, true);

    //             // Add event details
    //             event.addPropertyWithValue(
    //                 "uid",
    //                 `${Date.now()}@yourdomain.com`
    //             );
    //             event.addPropertyWithValue(
    //                 "dtstamp",
    //                 ICAL.Time.now().toString()
    //             );
    //             event.addPropertyWithValue("dtstart", startICAL.toString());
    //             event.addPropertyWithValue("dtend", endICAL.toString());
    //             event.addPropertyWithValue("summary", title);
    //             event.addPropertyWithValue("description", description);
    //             event.addPropertyWithValue("location", location);

    //             // Add the event to the calendar
    //             calendar.addSubcomponent(event);

    //             // Generate the ICS file content
    //             return calendar.toString();
    //         };

    //                 // Get event details from the form (using vanilla JS)
    //         const eventDate = document.querySelector("#eventDate").value;
    //         const eventEndDate = document.querySelector("#eventEndDate").value;
    //         const eventTime = document.querySelector("#eventTime").value;
    //         const eventEndTime = document.querySelector("#eventEndTime").value || "12:00 PM"; // Default value if end time is empty
    //         const eventName = document.querySelector("#eventName").value;
    //        // Function to convert 12-hour format time to 24-hour format
    //             const convertTo24Hour = (time) => {
    //                 const [hoursMinutes, period] = time.split(" ");
    //                 let [hours, minutes] = hoursMinutes.split(":");
    //                 if (period === "PM" && hours !== "12") hours = (parseInt(hours) + 12).toString();
    //                 if (period === "AM" && hours === "12") hours = "00";
    //                 return `${hours}:${minutes}`;
    //             };

    //             // Convert eventDate and eventEndDate to Date objects
    //             const startTime24 = convertTo24Hour(eventTime);
    //             const endTime24 = convertTo24Hour(eventEndTime);

    //             const startDateTime = new Date(`${eventDate}T${startTime24}`); // Ensure the time and date are correctly combined
    //             const endDateTime = new Date(`${eventEndDate}T${endTime24}`); // Ensure the time and date are correctly combined

    //             // Check if the Date objects are valid
    //             if (isNaN(startDateTime)) {
    //                 console.error("Invalid startDateTime");
    //             }
    //             if (isNaN(endDateTime)) {
    //                 console.error("Invalid endDateTime");
    //             }
    //             const address1 = "{{ $eventInfo['guest_view']['address_1'] }}";
    //             const city = "{{ $eventInfo['guest_view']['city'] }}";
    //             const state = "{{ $eventInfo['guest_view']['state'] }}";
    //             const zipCode = "{{ $eventInfo['guest_view']['zip_code'] }}";

    //             // Check if address1 is not empty
    //             let el = "";  // Default location if no address is provided

    //             if (address1 && city && state && zipCode) {
    //                 el = `${address1} ${city}, ${state} ${zipCode}`;  // Combine the address components
    //             }

    //         const eventDetails = {
    //             title: eventName,
    //             description: `${eventName} is scheduled on ${eventDate}.`, // Customize the description if needed
    //             location:el, // You can customize the location based on input or hardcode
    //         };
    //         console.log({eventDetails})

    //         // Generate the ICS file using the extracted details
    //         const icsData = createICSFile(
    //             startDateTime,
    //             endDateTime,
    //             eventDetails.title,
    //             eventDetails.description,
    //             eventDetails.location
    //         );

    //         // Create a downloadable link
    //         const icsBlob = new Blob([icsData], {
    //             type: "text/calendar;charset=utf-8",
    //         });
    //         const downloadLink = document.createElement("a");
    //         downloadLink.href = URL.createObjectURL(icsBlob);
    //         downloadLink.download = "event.ics";
    //         downloadLink.textContent = "Download Event (.ics)";
    //         downloadLink.style.display = "block";
    //         downloadLink.style.margin = "20px";
    //         downloadLink.style.color = "blue";
    //         downloadLink.style.textDecoration = "underline";
    //         // document.body.appendChild(downloadLink);
    //         // downloadLink.click();
    //         // document.querySelector(".author-title").appendChild(link);


    //                     // Get the existing <a> tag with class .add-calendar
    //         const calendarLink = document.querySelector("#openGoogle");

    //         // Set the href attribute to the URL of the .ics file
    //         calendarLink.href = URL.createObjectURL(icsBlob);
         
    //         let down = eventName.replace(" ", "_");

    //         calendarLink.download = down+".ics";

</script>
@endpush