
@php
// dd($eventDraftdata);
          $month="";
          $current_month = "";
          $month_change = $get_current_month;
          $series = ['blue', 'pink', 'yellow'];
          $colorIndex = 0;

@endphp 
@foreach ($eventPasttList as $pastEvent )
@php
    $current_month = $pastEvent['event_month'];   
    $colorClass = $series[$colorIndex % count($series)];
    $colorIndex++;
@endphp
<div class="all-events-month-wise-support" data-month="{{$pastEvent['event_month']}}">
    <div class="home-center-upcoming-events-card all-event-{{$colorClass}} mb-3">
        <div class="home-upcoming-events-card-left">
            <a href="{{ route('event.event_wall', encrypt($pastEvent['id']))  }}" class="home-upcoming-events-card-left-profile">
                <div class="home-upcoming-events-card-left-profile-img">
                    {{-- <img src="{{$pastEvent['host_profile']}}" class="lazy" alt=""> --}}
                @if($pastEvent['host_profile'] != "")
                    <img src="{{$pastEvent['host_profile']}}" class="lazy" alt="">
                @else
                @php 
                    $name = $pastEvent['host_name'];
                    $parts = explode(" ", $name); 
                    $firstInitial = isset($parts[0]) ? strtoupper($parts[0][0]) : '';
                    $secondInitial = isset($parts[1]) ? strtoupper($parts[1][0]) : '';
                    $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                    $fontColor = "fontcolor" . strtoupper($firstInitial);
                @endphp
                <h5 class="{{$fontColor}}"> {{ $initials }}</h5>
                @endif
                </div>
                <div class="home-upcoming-events-card-left-profile-content">
                    <h3>{{$pastEvent['event_name']}}</h3>
                    <p class="host-name-{{$colorClass}}">{{$pastEvent['host_name']}} <span><i class="fa-solid fa-circle"></i> {{$pastEvent['post_time']}}</span></p>
                </div>
              </a>
              <a href="{{ route('event.event_wall', encrypt($pastEvent['id']))  }}" >
                    <ul class="home-upcoming-events-card-left-detail">
                        @if($pastEvent['is_event_owner']==1)
                            <li><span>Hosting</span> </li>
                        @elseif($pastEvent['is_co_host'] == "1")
                            <li><span>Co-Hosting</span></li>
                        @endif        
                                @if($pastEvent['is_event_owner']!=1)      
                                @if($pastEvent['rsvp_status'] == '1')
                                    <li><span>Guest : </span> RSVP - Yes</li>
                                @elseif($pastEvent['rsvp_status'] == '2')
                                    <li><span>Guest : </span> RSVP - No</li>
                                @else
                                    <li><span>Guest : </span> RSVP - Pending</li>
                                @endif
                        @endif
                    <li><span>{{$pastEvent['event_date_mon']}} <i class="fa-solid fa-circle"></i> {{$pastEvent['event_day']}}</span> {{$pastEvent['start_time']}}</li>
                    </ul>
            </a>
            <div class="home-upcoming-events-card-left-foot disable-class">
                <div class="home-upcoming-events-card-rsvp-data">
                    <h6 class="card-rsvp-done"><i class="fa-regular fa-circle-check"></i> {{$pastEvent['total_accept_event_user']}}</h6>
                    <h6 class="card-rsvp-pending"><i class="fa-regular fa-circle-question"></i> {{$pastEvent['total_invited_user']}}</h6>
                    <h6 class="card-rsvp-cancel"><i class="fa-regular fa-circle-xmark"></i> {{$pastEvent['total_refuse_event_user']}}</h6>
                </div>
                <div class="d-flex align-items-center gap-4">
                    <div class="upcoming-events-card-notification-wrp dropdown">
                      <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <svg viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                          <path d="M12.5194 2.58331C8.68605 2.58331 5.58188 5.68748 5.58188 9.52081V11.7083C5.58188 12.4166 5.29022 13.4791 4.92563 14.0833L3.60272 16.2916C2.79022 17.6562 3.35272 19.1771 4.85272 19.6771C9.83188 21.3333 15.2173 21.3333 20.1965 19.6771C21.6027 19.2083 22.2069 17.5625 21.4465 16.2916L20.1235 14.0833C19.759 13.4791 19.4673 12.4062 19.4673 11.7083V9.52081C19.4569 5.70831 16.3319 2.58331 12.5194 2.58331Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"></path>
                          <path d="M15.9688 20.1042C15.9688 22.0104 14.4063 23.5729 12.5 23.5729C11.5521 23.5729 10.6771 23.1771 10.0521 22.5521C9.42708 21.9271 9.03125 21.0521 9.03125 20.1042" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"></path>
                        </svg>
                        <span class="g-badge">{{$pastEvent['total_notification']}}</span>
                      </button>
                      {{-- @if($pastEvent['is_notification_on_off']=='1')
                      <ul class="upcoming-events-card-notification-info dropdown-menu">
                          <li><button class="notification-btn" data-status="1"  data-event_id="{{$pastEvent['id']}}"><i class="fa-regular fa-bell"></i> Silence Notifications</button></li>
                      </ul>
                      @else
                      <ul class="upcoming-events-card-notification-info dropdown-menu">
                          <li><button class="notification-btn" data-status="0" data-event_id="{{$pastEvent['id']}}"><i class="fa-regular fa-bell-slash"></i> Enable Notifications</button></li>
                      </ul>
                      @endif --}}
                    </div>
                {{-- <div class="dropdown upcoming-card-dropdown">
                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-ellipsis-vertical"></i>
                    </button>
                    <ul class="dropdown-menu">
                        <li>
                        <a class="dropdown-item" href="#">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M11.0475 4.66603L4.20585 11.9077C3.94752 12.1827 3.69752 12.7244 3.64752 13.0994L3.33918 15.7994C3.23085 16.7744 3.93085 17.441 4.89752 17.2744L7.58085 16.816C7.95585 16.7494 8.48085 16.4744 8.73918 16.191L15.5809 8.94937C16.7642 7.69937 17.2975 6.27437 15.4558 4.5327C13.6225 2.8077 12.2308 3.41603 11.0475 4.66603Z" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M9.90625 5.87451C10.2646 8.17451 12.1312 9.93284 14.4479 10.1662" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            Edit Event
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item" href="#">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M13.3307 10.7493V14.2494C13.3307 17.166 12.1641 18.3327 9.2474 18.3327H5.7474C2.83073 18.3327 1.66406 17.166 1.66406 14.2494V10.7493C1.66406 7.83268 2.83073 6.66602 5.7474 6.66602H9.2474C12.1641 6.66602 13.3307 7.83268 13.3307 10.7493Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M18.3307 5.74935V9.24935C18.3307 12.166 17.1641 13.3327 14.2474 13.3327H13.3307V10.7493C13.3307 7.83268 12.1641 6.66602 9.2474 6.66602H6.66406V5.74935C6.66406 2.83268 7.83073 1.66602 10.7474 1.66602H14.2474C17.1641 1.66602 18.3307 2.83268 18.3307 5.74935Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            Duplicate Event
                        </a>
                        </li>
                        <li>
                        <a class="dropdown-item" href="#">
                            <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M9.9974 18.9577C5.05573 18.9577 1.03906 14.941 1.03906 9.99935C1.03906 5.05768 5.05573 1.04102 9.9974 1.04102C14.9391 1.04102 18.9557 5.05768 18.9557 9.99935C18.9557 14.941 14.9391 18.9577 9.9974 18.9577ZM9.9974 2.29102C5.7474 2.29102 2.28906 5.74935 2.28906 9.99935C2.28906 14.2493 5.7474 17.7077 9.9974 17.7077C14.2474 17.7077 17.7057 14.2493 17.7057 9.99935C17.7057 5.74935 14.2474 2.29102 9.9974 2.29102Z" fill="#F73C71"></path>
                            <path d="M10 11.4577C9.65833 11.4577 9.375 11.1743 9.375 10.8327V6.66602C9.375 6.32435 9.65833 6.04102 10 6.04102C10.3417 6.04102 10.625 6.32435 10.625 6.66602V10.8327C10.625 11.1743 10.3417 11.4577 10 11.4577Z" fill="#F73C71"></path>
                            <path d="M9.9974 14.1664C9.88906 14.1664 9.78073 14.1414 9.68073 14.0997C9.58073 14.0581 9.48906 13.9997 9.40573 13.9247C9.33073 13.8414 9.2724 13.7581 9.23073 13.6497C9.18906 13.5497 9.16406 13.4414 9.16406 13.3331C9.16406 13.2247 9.18906 13.1164 9.23073 13.0164C9.2724 12.9164 9.33073 12.8247 9.40573 12.7414C9.48906 12.6664 9.58073 12.6081 9.68073 12.5664C9.88073 12.4831 10.1141 12.4831 10.3141 12.5664C10.4141 12.6081 10.5057 12.6664 10.5891 12.7414C10.6641 12.8247 10.7224 12.9164 10.7641 13.0164C10.8057 13.1164 10.8307 13.2247 10.8307 13.3331C10.8307 13.4414 10.8057 13.5497 10.7641 13.6497C10.7224 13.7581 10.6641 13.8414 10.5891 13.9247C10.5057 13.9997 10.4141 14.0581 10.3141 14.0997C10.2141 14.1414 10.1057 14.1664 9.9974 14.1664Z" fill="#F73C71"></path>
                            </svg>
                            Cancel Event
                        </a>
                        </li>
                    </ul>
                    </div> --}}
                </div>
            </div>
        </div>
        <a href="{{ route('event.event_wall', encrypt($pastEvent['id']))  }}" class="home-upcoming-events-card-right">
          <img src="{{$pastEvent['event_images']}}" class="lazy" alt="">
      </a>
    </div>
    <h6 class="all-events-date-show">{{$pastEvent['event_date_only']}}</h6>
    @if($month_change !=$pastEvent['event_month'])
    <h6 class="all-events-inner-month-show">{{$pastEvent['event_month']}}</h6>
    @php
        $month_change = $pastEvent['event_month'];
    @endphp
    <input type="hidden" class="latest_month_draft" value="{{$month_change}}"/>
  @endif
</div>
@endforeach