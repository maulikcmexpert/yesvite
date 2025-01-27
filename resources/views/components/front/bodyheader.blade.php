
@if (Auth::guard('web')->check())

<header>
  <div class="mobile-menu-overlay"></div>
  <div class="container">
    <div class="header-wrp">
      <a href="{{route('home')}}" class="header-logo">
        {{-- <img src="{{asset('assets/front/image/header_logo.png')}}" alt="logo">
        <span>beta</span> --}}
        <img src="{{asset('assets/front/image/yesvite_logo.png')}}" alt="logo">
      </a>
      @if (Auth::guard('web')->check())
      <div class="header-right">
        <a href="#" class="add_new_event_btn create_event_with_plan"
          ><i class="fa-solid fa-plus"></i> New Event</a
        >

        <div class="header-msg-wrp">
          <a href="{{route('message.list')}}">
            <svg
              viewBox="0 0 25 26"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M8.85286 20.2916H8.33203C4.16536 20.2916 2.08203 19.25 2.08203 14.0416L2.08203 8.83331C2.08203 4.66665 4.16536 2.58331 8.33203 2.58331L16.6654 2.58331C20.832 2.58331 22.9154 4.66665 22.9154 8.83331L22.9154 14.0416C22.9154 18.2083 20.832 20.2916 16.6654 20.2916H16.1445C15.8216 20.2916 15.5091 20.4479 15.3112 20.7083L13.7487 22.7916C13.0612 23.7083 11.9362 23.7083 11.2487 22.7916L9.6862 20.7083C9.51953 20.4791 9.13411 20.2916 8.85286 20.2916Z"
                stroke="#0F172A"
                stroke-width="1.5"
                stroke-miterlimit="10"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M7.29297 8.83331L17.7096 8.83331"
                stroke="#0F172A"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
              <path
                d="M7.29297 14.0417L13.543 14.0417"
                stroke="#0F172A"
                stroke-width="1.5"
                stroke-linecap="round"
                stroke-linejoin="round"
              />
            </svg>
            @php
            $count = getTotalUnreadMessageCount();
            @endphp

            <span class="g-badge" style="display: {{$count>0 ? 'block':'none'}}">{{$count}}</span>

            {{-- <span class="g-badge">10</span> --}}
          </a>
        </div>

        <div class="header-notification-wrp dropdown">
          <button class="dropdown-toggle notification-toggle-menu" type="button" id="dropdownButton">
            <svg
              viewBox="0 0 25 26"
              fill="none"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path
                d="M12.5194 2.58331C8.68605 2.58331 5.58188 5.68748 5.58188 9.52081V11.7083C5.58188 12.4166 5.29022 13.4791 4.92563 14.0833L3.60272 16.2916C2.79022 17.6562 3.35272 19.1771 4.85272 19.6771C9.83188 21.3333 15.2173 21.3333 20.1965 19.6771C21.6027 19.2083 22.2069 17.5625 21.4465 16.2916L20.1235 14.0833C19.759 13.4791 19.4673 12.4062 19.4673 11.7083V9.52081C19.4569 5.70831 16.3319 2.58331 12.5194 2.58331Z"
                stroke="#0F172A"
                stroke-width="1.5"
                stroke-miterlimit="10"
                stroke-linecap="round"
              />
              <path
                d="M15.9688 20.1042C15.9688 22.0104 14.4063 23.5729 12.5 23.5729C11.5521 23.5729 10.6771 23.1771 10.0521 22.5521C9.42708 21.9271 9.03125 21.0521 9.03125 20.1042"
                stroke="#0F172A"
                stroke-width="1.5"
                stroke-miterlimit="10"
              />
            </svg>
            @php
            $user = Auth::guard('web')->user();
            $notification = getTotalUnreadNotification($user->id);

            if ($notification != 0) {
                echo '<span class="n-badge notification_count_display">' . $notification . '</span>';
            }
        @endphp


          </button>
          <ul
            class="notification-dropdown-menu dropdown-menu"
            aria-labelledby="dropdownButton"
          >
            <div class="notification-dropdown-header">

              <h3>Notifications <span class="notification_count_display">{{$notification}}</span></h3>
              <h5 class="notification_read" data-user_id="{{$user->id}}"style="cursor: pointer;">
                Mark All Read
                {{-- <span type="button" data-bs-toggle="modal" data-bs-target="#"> --}}
                  <span type="button" data-bs-toggle="modal" data-bs-target="#all-notification-filter-modal">

                  <svg
                    viewBox="0 0 20 21"
                    fill="none"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path
                      d="M18.3359 5.91669H13.3359"
                      stroke="#F73C71"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M4.9974 5.91669H1.66406"
                      stroke="#F73C71"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M8.33073 8.83333C9.94156 8.83333 11.2474 7.5275 11.2474 5.91667C11.2474 4.30584 9.94156 3 8.33073 3C6.7199 3 5.41406 4.30584 5.41406 5.91667C5.41406 7.5275 6.7199 8.83333 8.33073 8.83333Z"
                      stroke="#F73C71"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M18.3333 15.0833H15"
                      stroke="#F73C71"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M6.66406 15.0833H1.66406"
                      stroke="#F73C71"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                    <path
                      d="M11.6667 18C13.2775 18 14.5833 16.6942 14.5833 15.0834C14.5833 13.4725 13.2775 12.1667 11.6667 12.1667C10.0558 12.1667 8.75 13.4725 8.75 15.0834C8.75 16.6942 10.0558 18 11.6667 18Z"
                      stroke="#F73C71"
                      stroke-width="1.5"
                      stroke-miterlimit="10"
                      stroke-linecap="round"
                      stroke-linejoin="round"
                    />
                  </svg>
                </span>
              </h5>
            </div>
            <div class="notification-dropdown-content">

              <div class="accordion notification_div" id="accordionExample">
              @php
                  $notification_list=getNotificationList();
                  $i=0;
              @endphp

              @foreach ($notification_list as $key=>$value)
              @php
                $i++;
              @endphp
                <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button collapsed"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#collapseOne{{$i}}"
                      aria-expanded="true"
                      aria-controls="collapseOne"
                    >
                      <div class="accordion-button-wrp">
                        <div class="accordion-button-img-wrp">
                          <img
                            src="{{$value[0]['event_image']}}"
                            alt=""
                            loading="lazy"
                          />
                        </div>
                        <div class="accordion-button-content-wrp">
                          <h3>{{$key}}</h3>
                          <p>{{$value[0]['event_date']}}</p>
                        </div>
                      </div>

                    </button>
                  </h2>
                  <div
                    id="collapseOne{{$i}}"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample">

                    @foreach ($value as $inner_data )

                          <div class="accordion-body">
                            @if($inner_data['notification_type']=="invite")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php

                                             
                                                        $firstInitial = isset($inner_data['first_name'][0]) ? strtoupper($inner_data['first_name'][0]) : '';
                                                        $secondInitial = isset($inner_data['last_name'][1]) ? strtoupper($inner_data['last_name'][0]) : '';
                                                        $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                                        $fontColor = "fontcolor" . strtoupper($firstInitial);

                                                  //$initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  //$fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>

                                      @if($inner_data['co_host_notification']=="1")
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          <span>You have been made a co-host for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                       @else
                                        <h3>
                                            {{-- {{$inner_data['notification_message']}} --}}
                                            {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                            <span> has invited you to <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                          </h3>
                                       @endif 

                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data['read']=="0")
                                              <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>

                                      
                                      </div>
                                      @if($inner_data['co_host_notification']=="1")
                                        <div>
                                            <button class="notification-rsvp-btn" type="button">RSVP</button>
                                        </div>
                                      @endif  
                                     <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p>
                                          @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif -->
                                      </div>
                                      {{--  <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="update_event")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Has updated the event details for</span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>{{$inner_data['event_name']}}</p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                                                              </div>
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="update_date")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Has updated the event date for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data['read']=="0")  
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      <div class="d-block">
                                        <h3 class="mb-1">Date from : <span style="font-family: var(--SFProDisplay-Regular);"> {{$inner_data['old_start_end_date']}}</span></h3>
                                        <h3>Date To : <span style="font-family: var(--SFProDisplay-Regular);"> {{$inner_data['new_start_end_date']}}</span></h3>
                                      </div>
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>  
                            @elseif($inner_data['notification_type']=="update_potluck")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Has updated the event potluck for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data=="0")
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div> 
                            @elseif($inner_data['notification_type']=="update_time")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> has updated the event time for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data=="0")
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      <div class="d-block">
                                        <h3 class="mb-1">Time : <span style="font-family: var(--SFProDisplay-Regular);">From {{$inner_data['from_time']}} To {{$inner_data['to_time']}}</span></h3>
                                      </div>
                                      
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>  
                            @elseif($inner_data['notification_type']=="update_address")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> has updated the event address for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span></span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data=="0")
                                          <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}}</p> -->
                                      </div>
                                      <div class="d-block">
                                        <h3 class="mb-1">Location</h3>
                                        <h3 class="mb-1">From : <span style="font-family: var(--SFProDisplay-Regular);">{{$inner_data['from_addr']}}</span></h3>
                                        <h3 class="mb-1">To: <span style="font-family: var(--SFProDisplay-Regular);">{{$inner_data['to_addr']}}</span></h3>
                                      </div>
                                      
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>                       
                            @elseif($inner_data['notification_type']=="potluck_bring")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                     @else
                                       {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                      {{-- <span class="active-dot"></span> --}}
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> will bring the item below for <span style="font-family: var(--SFProDisplay-Bold);font-size: 14px;line-height:normal;color: #F73C71;">{{$inner_data['event_name']}} </span>Potluck
                                          </span>
                                        </h3>
                                        <div>
                                          <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                          @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot mt-1 text-right"></h6>
                                          @endif
                                        </div>
                                      </div>
                                      <div>
                                        <!-- <p>{{$inner_data['event_name']}} Potluck</p> -->
                                            
                                      </div>
                                      <div class="d-flex align-items-center justify-content-between">
                                        <h3>{{$inner_data['potluck_item']}}</h3>
                                        <h3>Count: {{$inner_data['count']}}</h3>
                                      </div>
                                      {{-- <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>            
                            @elseif($inner_data['notification_type']=="update_event"&& $inner_data['is_co_host']=="1"&&$inner_data['accept_as_co_host']=="0")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      <img
                                        src="{{$inner_data['profile']}}"
                                        alt=""
                                        loading="lazy"

                                      />
                                      <span class="active-dot"></span>
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>
                                        <h3>
                                          {{$inner_data['notification_message']}}
                                          {{-- James Clark
                                          <span> Invited you to co-host</span> --}}
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>{{$inner_data['event_name']}} <span>Accept? </span></p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                                                              </div>
                                      <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div>
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="comment_post")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                          $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                          $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                          $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                          <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                          <span class="active-dot"></span>

                                      @else
                                          {!! $userProfile !!}
                                        <span class="active-dot"></span>
                                      @endif
                                    </div>
                                    <div
                                      class="notification-drodown-body-inner-content"
                                    >
                                      <div>
                                        <h3>
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> commented on your post on </span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>
                                          {{$inner_data['event_name']}}
                                          <span><strong>Wall</strong></span>
                                        </p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                      {{-- <div class="notification-video-comment-wrp">
                                        <h6>That’s was great! love it ❤️</h6>
                                        <div class="notification-video-wrp">
                                          <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                                            <video>
                                              <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                                            </video>
                                            <span class="notification-video_play-icon"
                                              ><img
                                                src="./assets/img/notification-video_play-icon.png"
                                                alt=""
                                                loading="lazy"

                                            /></span>
                                          </a>
                                          <div class="notification-video-content">
                                            <p>
                                              Thanks everyone for RSVP'ing on time. I
                                              hope everyone can make it to this special
                                              day of ours”
                                            </p>
                                          </div>
                                        </div>
                                      </div> --}}
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="upload_post")
                                <div class="notification-drodown-body-inner">
                                  <div class="notification-drodown-body-inner-img">
                                    @php
                                        $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                        $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                        $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                    @endphp
                                    @if($inner_data['profile']!="")
                                        <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                        <span class="active-dot"></span>

                                    @else
                                        {!! $userProfile !!}
                                      <span class="active-dot"></span>
                                    @endif
                                  </div>
                                  <div
                                    class="notification-drodown-body-inner-content"
                                  >
                                    <div>
                                      <h3>
                                        {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                        {{-- <span> posted on wall at </span> --}}
                                        @if($inner_data['media_type']=="photo")
                                            <span> posted new photo on wall at </span>
                                        @elseif($inner_data['media_type']=="video")
                                          <span> posted video on wall at </span>
                                        @else
                                        <span> posted on wall at </span>
                                        @endif
                                      </h3>
                                      <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                    </div>
                                    <div>
                                      <p>
                                        {{$inner_data['event_name']}}

                                        <span><strong>Wall</strong></span>
                                      </p>
                                      @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                    </div>
                                    {{-- <div class="notification-video-comment-wrp"> --}}
                                      {{-- <h6>That’s was great! love it ❤️</h6> --}}
                                      <div class="notification-video-wrp">
                                        @if($inner_data['media_type']!="")
                                          <div class="notification-video">
                                                @if($inner_data['media_type']=="photo")
                                                  <img src="{{$inner_data['post_image']}}" alt=""/>
                                                @elseif($inner_data['media_type']=="video")
                                                  <a href="{{$inner_data['post_image']}}" class="notification-video popup-videos">
                                                  <video>
                                                    <source src="{{$inner_data['post_image']}}" type="video/mp4" />
                                                  </video>
                                                  <span class="notification-video_play-icon"
                                                    ><img
                                                      src="{{asset('assets/front/image/notification-video_play-icon.png')}}"
                                                      alt=""
                                                      loading="lazy"

                                                  /></span>
                                                </a>
                                              @endif
                                          </div>
                                          <div class="notification-video-content">
                                            <p>
                                              {{($inner_data['post_message']!="")?$inner_data['post_message']:"See detail post"}}
                                            </p>
                                          </div>
                                        @elseif($inner_data['media_type']=="")
                                            <div class="noification-simple-text-wrp">
                                              <p>{{($inner_data['post_message']!="")?$inner_data['post_message']:"See detail post"}}</p>
                                            </div>
                                        @endif
                                      </div>
                                    {{-- </div> --}}
                                  </div>
                                </div>
                            @elseif($inner_data['notification_type']=="first")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      <img
                                        src="./assets/img/header-profile-img.png"
                                        alt=""
                                        loading="lazy"

                                      />
                                      <span class="active-dot"></span>
                                    </div>
                                    <div
                                      class="notification-drodown-body-inner-content"
                                    >
                                      <div>
                                        <h3>
                                          James Clark
                                          <span> RSVP’d <strong>NO</strong> for </span>
                                        </h3>
                                        <h6 class="notification-time-count">10min</h6>
                                      </div>
                                      <div>
                                        <p>
                                          Sarah’s Birthday
                                          <span><strong>Wall</strong></span>
                                        </p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                      <div class="notification-rsvp-wrp">
                                        <h4>RSVP’d <span>NO</span></h4>
                                        <a href="#" class="chat-icon">
                                          <svg
                                            viewBox="0 0 24 24"
                                            fill="none"
                                            xmlns="http://www.w3.org/2000/svg"
                                          >
                                            <path
                                              d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                              stroke="#94A3B8"
                                              stroke-width="1.5"
                                              stroke-miterlimit="10"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                            />
                                            <path
                                              d="M7 8H17"
                                              stroke="#94A3B8"
                                              stroke-width="1.5"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                            />
                                            <path
                                              d="M7 13H13"
                                              stroke="#94A3B8"
                                              stroke-width="1.5"
                                              stroke-linecap="round"
                                              stroke-linejoin="round"
                                            />
                                          </svg>
                                        </a>
                                      </div>
                                    </div>
                                  </div>
                            @elseif($inner_data['notification_type']=="sent_rsvp" && $inner_data['rsvp_detail']['rsvpd_status']=="1" )
                              <div class="notification-drodown-body-inner">
                                <div class="notification-drodown-body-inner-img">
                                  @php
                                              $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                              $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                              $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                  @endphp
                                  @if($inner_data['profile']!="")
                                  <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                @else
                                  {!! $userProfile !!}
                                <span class="active-dot"></span>
                                @endif
                                  <span class="active-dot"></span>
                                </div>
                                <div class="notification-drodown-body-inner-content">
                                  <div>
                                    <h3>
                                      {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                      @if($inner_data['rsvp_attempt']=="no_to_yes")
                                        <span> changed RSVP response from <strong>NO</strong> to <strong>YES</strong> for </span>
                                      @else
                                        <span> RSVP’d <strong>YES</strong> for </span>
                                      @endif                                    </h3>
                                    <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                  </div>
                                  <div>
                                    <p>
                                      {{$inner_data['event_name']}}
                                      <span><strong>Wall</strong></span>
                                    </p>
                                    @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                  </div>
                                </div>
                              </div>
                            @elseif($inner_data['notification_type']=="sent_rsvp" && $inner_data['rsvp_detail']['rsvpd_status']=="0" )
                              <div class="notification-drodown-body-inner">
                                <div class="notification-drodown-body-inner-img">
                                  @php
                                    $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                    $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                    $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                  @endphp
                                  @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>
                                @else
                                        {!! $userProfile !!}
                                      <span class="active-dot"></span>
                                @endif
                                </div>
                                <div
                                  class="notification-drodown-body-inner-content">
                                  <div>
                                    <h3>
                                      {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                      @if($inner_data['rsvp_attempt']=="yes_to_no")
                                        <span> changed RSVP response from <strong>YES</strong> to <strong>NO</strong> for </span>
                                      @else
                                        <span> RSVP’d <strong>NO</strong> for </span>
                                      @endif
                                    </h3>
                                    <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                  </div>
                                  <div>
                                    <p>
                                      {{$inner_data['event_name']}}
                                      <span><strong>Wall</strong></span>
                                    </p>
                                    @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                  </div>
                                  <div class="notification-rsvp-wrp">
                                    <h4>RSVP’d <span>NO</span></h4>
                                    <a href="#" class="chat-icon">
                                      <svg
                                        viewBox="0 0 24 24"
                                        fill="none"
                                        xmlns="http://www.w3.org/2000/svg"
                                      >
                                        <path
                                          d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                          stroke="#94A3B8"
                                          stroke-width="1.5"
                                          stroke-miterlimit="10"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                        />
                                        <path
                                          d="M7 8H17"
                                          stroke="#94A3B8"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                        />
                                        <path
                                          d="M7 13H13"
                                          stroke="#94A3B8"
                                          stroke-width="1.5"
                                          stroke-linecap="round"
                                          stroke-linejoin="round"
                                        />
                                      </svg>
                                    </a>
                                  </div>
                                </div>
                              </div>
                            @elseif($inner_data['notification_type']=="photos")
                                <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                                  $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                                  $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                                  $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                      <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                      <span class="active-dot"></span>

                                    @else
                                      {!! $userProfile !!}
                                    <span class="active-dot"></span>
                                    @endif
                                    </div>
                                    <div class="notification-drodown-body-inner-content">
                                      <div>

                                        <h3>
                                          {{-- {{$inner_data['notification_message']}} --}}
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> posted new photo on photos for</span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                    <div>
                                        <p>{{$inner_data['event_name']}}</p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                        {{-- <div class="notification-video-comment-wrp">
                                        <div class="notification-video-wrp">
                                          <a href="#" class="notification-video popup-videos">
                                              <img src="{{$inner_data['post_image']}}" />
                                          </a>
                                          <div class="notification-video-content">
                                            <p>
                                                See detail post
                                            </p>
                                          </div>
                                        </div>
                                      </div> --}}
                                      {{--  <div class="notification-accept-invite-btn-wrp">
                                        <button class="accept-btn">
                                          <i class="fa-regular fa-circle-check"></i>
                                          Accept
                                        </button>
                                        <button class="decline-btn">
                                          <i class="fa-regular fa-circle-xmark"></i>
                                          Decline
                                        </button>
                                      </div> --}}
                                    </div>
                                  </div>

                            @elseif($inner_data['notification_type']=="accept_reject_co_host")
                                  <div class="notification-drodown-body-inner">
                                    <div class="notification-drodown-body-inner-img">
                                      @php
                                          $initials = strtoupper($inner_data['first_name'][0]) . strtoupper($inner_data['last_name'][0]);
                                          $fontColor = "fontcolor" . strtoupper($inner_data['first_name'][0]);
                                          $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                                      @endphp
                                      @if($inner_data['profile']!="")
                                          <img src="{{$inner_data['profile']}}" alt=""loading="lazy" />
                                          <span class="active-dot"></span>

                                      @else
                                          {!! $userProfile !!}
                                        <span class="active-dot"></span>
                                      @endif
                                    </div>
                                    <div
                                      class="notification-drodown-body-inner-content"
                                    >
                                      <div>
                                        <h3>
                                          {{$inner_data['first_name']}} {{$inner_data['last_name']}}
                                          <span> Accepted your invitation to co-host</span>
                                        </h3>
                                        <h6 class="notification-time-count">{{$inner_data['post_time']}}</h6>
                                      </div>
                                      <div>
                                        <p>
                                          {{$inner_data['event_name']}}
                                          <span><strong>Wall</strong></span>
                                        </p>
                                        @if($inner_data['read']=="0")
                                            <h6 class="notification-read-dot"></h6>
                                          @endif
                                      </div>
                                      {{-- <div class="notification-video-comment-wrp">
                                        <h6>That’s was great! love it ❤️</h6>
                                        <div class="notification-video-wrp">
                                          <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                                            <video>
                                              <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                                            </video>
                                            <span class="notification-video_play-icon"
                                              ><img
                                                src="./assets/img/notification-video_play-icon.png"
                                                alt=""
                                                loading="lazy"

                                            /></span>
                                          </a>
                                          <div class="notification-video-content">
                                            <p>
                                              Thanks everyone for RSVP'ing on time. I
                                              hope everyone can make it to this special
                                              day of ours”
                                            </p>
                                          </div>
                                        </div>
                                      </div> --}}
                                    </div>
                                  </div>
                        @endif



                                  {{-- <div class="notification-drodown-body-inner">
                              <div class="notification-drodown-body-inner-img">
                                <img
                                  src="./assets/img/header-profile-img.png"
                                  alt=""
                                />
                                <span class="active-dot"></span>
                              </div>
                              <div
                                class="notification-drodown-body-inner-content"
                              >
                                <div>
                                  <h3>
                                    James Clark
                                    <span> posted a comment with video on </span>
                                  </h3>
                                  <h6 class="notification-time-count">10min</h6>
                                </div>
                                <div>
                                  <p>
                                    Sarah’s Birthday
                                    <span><strong>Wall</strong></span>
                                  </p>
                                  <h6 class="notification-read-dot"></h6>
                                </div>
                                <div class="notification-video-wrp">
                                  <a href="{{asset('assets/front/image/sample-video.mp4')}}" class="notification-video popup-videos">
                                    <video>
                                      <source src="{{asset('assets/front/image/sample-video.mp4')}}" type="video/mp4" />
                                    </video>
                                    <span class="notification-video_play-icon"
                                      ><img
                                        src="{{asset('assets/front/image/notification-video_play-icon.png')}}"
                                        alt=""
                                    /></span>
                                  </a>
                                  <div class="notification-video-content">
                                    <p>
                                      Thanks everyone for RSVP'ing on time. I hope
                                      everyone can make it to this special day of
                                      ours”
                                    </p>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="notification-drodown-body-inner">
                              <div class="notification-drodown-body-inner-img">
                                <img
                                  src="./assets/img/header-profile-img.png"
                                  alt=""
                                />
                                <span class="active-dot"></span>
                              </div>
                              <div
                                class="notification-drodown-body-inner-content"
                              >
                                <div>
                                  <h3>
                                    James Clark
                                    <span> commented on your post on </span>
                                  </h3>
                                  <h6 class="notification-time-count">10min</h6>
                                </div>
                                <div>
                                  <p>
                                    Sarah’s Birthday
                                    <span><strong>Wall</strong></span>
                                  </p>
                                  <h6 class="notification-read-dot"></h6>
                                </div>
                                <div class="notification-video-comment-wrp">
                                  <h6>That’s was great! love it ❤️</h6>
                                  <div class="notification-video-wrp">
                                    <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                                      <video>
                                        <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                                      </video>
                                      <span class="notification-video_play-icon"
                                        ><img
                                          src="./assets/img/notification-video_play-icon.png"
                                          alt=""
                                      /></span>
                                    </a>
                                    <div class="notification-video-content">
                                      <p>
                                        Thanks everyone for RSVP'ing on time. I
                                        hope everyone can make it to this special
                                        day of ours”
                                      </p>
                                    </div>
                                  </div>
                                </div>
                              </div>
                            </div>
                            <div class="notification-drodown-body-inner">
                              <div class="notification-drodown-body-inner-img">
                                <img
                                  src="./assets/img/header-profile-img.png"
                                  alt=""
                                />
                                <span class="active-dot"></span>
                              </div>
                              <div
                                class="notification-drodown-body-inner-content"
                              >
                                <div>
                                  <h3>
                                    James Clark
                                    <span> RSVP’d <strong>NO</strong> for </span>
                                  </h3>
                                  <h6 class="notification-time-count">10min</h6>
                                </div>
                                <div>
                                  <p>
                                    Sarah’s Birthday
                                    <span><strong>Wall</strong></span>
                                  </p>
                                  <h6 class="notification-read-dot"></h6>
                                </div>
                                <div class="notification-rsvp-wrp">
                                  <h4>RSVP’d <span>NO</span></h4>
                                  <a href="#" class="chat-icon">
                                    <svg
                                      viewBox="0 0 24 24"
                                      fill="none"
                                      xmlns="http://www.w3.org/2000/svg"
                                    >
                                      <path
                                        d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                        stroke="#94A3B8"
                                        stroke-width="1.5"
                                        stroke-miterlimit="10"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                      />
                                      <path
                                        d="M7 8H17"
                                        stroke="#94A3B8"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                      />
                                      <path
                                        d="M7 13H13"
                                        stroke="#94A3B8"
                                        stroke-width="1.5"
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                      />
                                    </svg>
                                  </a>
                                </div>
                              </div>
                            </div> --}}
                          </div>
                    @endforeach

                  </div>
                </div>

                {{-- <div class="accordion-item">
                  <h2 class="accordion-header">
                    <button
                      class="accordion-button collapsed"
                      type="button"
                      data-bs-toggle="collapse"
                      data-bs-target="#collapseTwo"
                      aria-expanded="true"
                      aria-controls="collapseOne"
                    >
                      <div class="accordion-button-wrp">
                        <div class="accordion-button-img-wrp">
                          <img
                            src="./assets/img/accordion-button-img.png"
                            alt=""
                          />
                        </div>
                        <div class="accordion-button-content-wrp">
                          <h3>Sarah’s Birthday</h3>
                          <p>August 31, 2023</p>
                        </div>
                      </div>

                    </button>
                  </h2>
                  <div
                    id="collapseTwo"
                    class="accordion-collapse collapse"
                    data-bs-parent="#accordionExample"
                  >
                    <div class="accordion-body">
                      <div class="notification-drodown-body-inner">
                        <div class="notification-drodown-body-inner-img">
                          <img
                            src="./assets/img/header-profile-img.png"
                            alt=""
                          />
                          <span class="active-dot"></span>
                        </div>
                        <div
                          class="notification-drodown-body-inner-content"
                        >
                          <div>
                            <h3>
                              James Clark
                              <span> Invited you to co-host</span>
                            </h3>
                            <h6 class="notification-time-count">10min</h6>
                          </div>
                          <div>
                            <p>Sarah’s Birthday <span>Accept? </span></p>
                            <h6 class="notification-read-dot"></h6>
                          </div>
                          <div class="notification-accept-invite-btn-wrp">
                            <button class="accept-btn">
                              <i class="fa-regular fa-circle-check"></i>
                              Accept
                            </button>
                            <button class="decline-btn">
                              <i class="fa-regular fa-circle-xmark"></i>
                              Decline
                            </button>
                          </div>
                        </div>
                      </div>
                      <div class="notification-drodown-body-inner">
                        <div class="notification-drodown-body-inner-img">
                          <img
                            src="./assets/img/header-profile-img.png"
                            alt=""
                          />
                          <span class="active-dot"></span>
                        </div>
                        <div
                          class="notification-drodown-body-inner-content"
                        >
                          <div>
                            <h3>
                              James Clark
                              <span> posted a comment with video on </span>
                            </h3>
                            <h6 class="notification-time-count">10min</h6>
                          </div>
                          <div>
                            <p>
                              Sarah’s Birthday
                              <span><strong>Wall</strong></span>
                            </p>
                            <h6 class="notification-read-dot"></h6>
                          </div>
                          <div class="notification-video-wrp">
                            <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                              <video>
                                <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                              </video>
                              <span class="notification-video_play-icon"
                                ><img
                                  src="./assets/img/notification-video_play-icon.png"
                                  alt=""
                              /></span>
                            </a>
                            <div class="notification-video-content">
                              <p>
                                Thanks everyone for RSVP'ing on time. I hope
                                everyone can make it to this special day of
                                ours”
                              </p>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="notification-drodown-body-inner">
                        <div class="notification-drodown-body-inner-img">
                          <img
                            src="./assets/img/header-profile-img.png"
                            alt=""
                          />
                          <span class="active-dot"></span>
                        </div>
                        <div
                          class="notification-drodown-body-inner-content"
                        >
                          <div>
                            <h3>
                              James Clark
                              <span> commented on your post on </span>
                            </h3>
                            <h6 class="notification-time-count">10min</h6>
                          </div>
                          <div>
                            <p>
                              Sarah’s Birthday
                              <span><strong>Wall</strong></span>
                            </p>
                            <h6 class="notification-read-dot"></h6>
                          </div>
                          <div class="notification-video-comment-wrp">
                            <h6>That’s was great! love it ❤️</h6>
                            <div class="notification-video-wrp">
                              <a href="./assets/img/sample-video.mp4" class="notification-video popup-videos">
                                <video>
                                  <source src="./assets/img/sample-video.mp4" type="video/mp4" />
                                </video>
                                <span class="notification-video_play-icon"
                                  ><img
                                    src="./assets/img/notification-video_play-icon.png"
                                    alt=""
                                /></span>
                              </a>
                              <div class="notification-video-content">
                                <p>
                                  Thanks everyone for RSVP'ing on time. I
                                  hope everyone can make it to this special
                                  day of ours”
                                </p>
                              </div>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="notification-drodown-body-inner">
                        <div class="notification-drodown-body-inner-img">
                          <img
                            src="./assets/img/header-profile-img.png"
                            alt=""
                          />
                          <span class="active-dot"></span>
                        </div>
                        <div
                          class="notification-drodown-body-inner-content"
                        >
                          <div>
                            <h3>
                              James Clark
                              <span> RSVP’d <strong>NO</strong> for </span>
                            </h3>
                            <h6 class="notification-time-count">10min</h6>
                          </div>
                          <div>
                            <p>
                              Sarah’s Birthday
                              <span><strong>Wall</strong></span>
                            </p>
                            <h6 class="notification-read-dot"></h6>
                          </div>
                          <div class="notification-rsvp-wrp">
                            <h4>RSVP’d <span>NO</span></h4>
                            <a href="#" class="chat-icon">
                              <svg
                                viewBox="0 0 24 24"
                                fill="none"
                                xmlns="http://www.w3.org/2000/svg"
                              >
                                <path
                                  d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                  stroke="#94A3B8"
                                  stroke-width="1.5"
                                  stroke-miterlimit="10"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                />
                                <path
                                  d="M7 8H17"
                                  stroke="#94A3B8"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                />
                                <path
                                  d="M7 13H13"
                                  stroke="#94A3B8"
                                  stroke-width="1.5"
                                  stroke-linecap="round"
                                  stroke-linejoin="round"
                                />
                              </svg>
                            </a>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div> --}}
                @endforeach
              </div>


              <div class="notification-dropdown-footer">
                <a href="#" class="notification-showall-btn">Show All</a>
              </div>
            </div>
          </ul>
        </div>

        <div class="header-profile-wrp dropdown">
          <button class="profile-drop-down-menu dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
            @php
            if (Auth::guard('web')->user()) {
                    $userprofile = Auth::guard('web')->user();
                    if ($userprofile->profile != NULL || $userprofile->profile != "") {
                        $image = asset("storage/profile/" . $userprofile->profile);
                        $userProfile =  '<img src="' . $image . '" class="UserImg" alt="profile">';
                    } else {
                        $initials = strtoupper($userprofile->firstname[0]) . strtoupper($userprofile->lastname[0]);
                        $fontColor = "fontcolor" . strtoupper($userprofile->firstname[0]);
                        $userProfile = "<h5 class='<?= $fontColor ?>' >" . $initials . "</h5>";
                    }
            }
          @endphp
          {!! $userProfile !!}
        </button>
          <ul class="dropdown-menu header-profile-dropdown">
            <div class="home-center-profile-head">
              <div class="home-center-profile-img">
                {!! $userProfile !!}
              </div>
              <div class="home-center-profile-content">
                <h3>{{$userprofile->firstname.' '.$userprofile->lastname}}</h3>
                <h6>{{$userprofile->email}}</h6>
                <p>Member Since: {{empty($userprofile->created_at) ? "" :   str_replace(' ', ', ', date('F Y', strtotime($userprofile->created_at)))}}</p>
              </div>
            </div>
            <div class="profile-online-status-wrp">
                <h3>
                    <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M11.9216 3.09355C14.4029 5.5748 14.3591 9.62478 11.7966 12.056C9.42787 14.2998 5.58413 14.2998 3.20913 12.056C0.640376 9.62478 0.596618 5.5748 3.08412 3.09355C5.52162 0.649805 9.48413 0.649805 11.9216 3.09355Z" stroke="#1C8B5C" stroke-linecap="round" stroke-linejoin="round"/>
                    <path opacity="0.4" d="M9.90311 10.0439C8.57811 11.2939 6.42812 11.2939 5.10938 10.0439" stroke="#1C8B5C" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Online Status:
                    <span>On</span>
                </h3>
            </div>
            <ul>
              <li><a href="{{route('profile')}}">Profile</a></li>
              <li><a href="{{route('profile.account_settings')}}">Account Settings</a></li>
              {{-- <li><a href="#">Pro Subscription</a></li> --}}
            </ul>
            <div class="header-profile-button-wrp">
                <a href="#"><span><svg viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M11.8359 12.2868H9.16927L6.2026 14.2601C5.7626 14.5535 5.16927 14.2402 5.16927 13.7068V12.2868C3.16927 12.2868 1.83594 10.9535 1.83594 8.95349V4.95345C1.83594 2.95345 3.16927 1.62012 5.16927 1.62012H11.8359C13.8359 1.62012 15.1693 2.95345 15.1693 4.95345V8.95349C15.1693 10.9535 13.8359 12.2868 11.8359 12.2868Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M8.49727 7.57324V7.43327C8.49727 6.97993 8.77729 6.73992 9.05729 6.54659C9.33062 6.35992 9.60392 6.11993 9.60392 5.67993C9.60392 5.0666 9.11061 4.57324 8.49727 4.57324C7.88394 4.57324 7.39062 5.0666 7.39062 5.67993" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M8.4944 9.16683H8.5004" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg></span> Support</a>
                  <a href="{{route('logout')}}"><span>
                  <svg viewBox="0 0 17 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6.42969 5.04016C6.63635 2.64016 7.86969 1.66016 10.5697 1.66016H10.6564C13.6364 1.66016 14.8297 2.85349 14.8297 5.83349V10.1802C14.8297 13.1602 13.6364 14.3535 10.6564 14.3535H10.5697C7.88969 14.3535 6.65635 13.3868 6.43635 11.0268" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M1.83594 8H10.4226" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  <path d="M8.92969 5.7666L11.163 7.99994L8.92969 10.2333" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                  </svg></span> Logout</a>
            </div>
          </ul>
        </div>

        <button class="moblie-menu-bar">
          <!-- <i class="fa-solid fa-bars"></i> -->
          <svg width="30" height="30" viewBox="0 0 100 100"><path class="false line line1" d="M 20,29.000046 H 80.000231 C 80.000231,29.000046 94.498839,28.817352 94.532987,66.711331 94.543142,77.980673 90.966081,81.670246 85.259173,81.668997 79.552261,81.667751 75.000211,74.999942 75.000211,74.999942 L 25.000021,25.000058"></path><path class="false line line2" d="M 20,50 H 80"></path><path class="false line line3" d="M 20,70.999954 H 80.000231 C 80.000231,70.999954 94.498839,71.182648 94.532987,33.288669 94.543142,22.019327 90.966081,18.329754 85.259173,18.331003 79.552261,18.332249 75.000211,25.000058 75.000211,25.000058 L 25.000021,74.999942"></path></svg>
        </button>
      </div>
      @endif
      <div class="main-menu-wrp mobile-menu-wrp">
          <ul>
            <li><a href="{{route('home')}}" class="{{ request()->segment(1) === 'home' ? 'active' : '' }}" class="active"><span><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M14.8224 18.9581H5.1724C2.88906 18.9581 1.03906 17.0998 1.03906 14.8165V8.64148C1.03906 7.50815 1.73906 6.08315 2.63906 5.38315L7.13073 1.88315C8.48073 0.833149 10.6391 0.783149 12.0391 1.76648L17.1891 5.37481C18.1807 6.06648 18.9557 7.54982 18.9557 8.75815V14.8248C18.9557 17.0998 17.1057 18.9581 14.8224 18.9581ZM7.8974 2.86648L3.40573 6.36648C2.81406 6.83315 2.28906 7.89148 2.28906 8.64148V14.8165C2.28906 16.4081 3.58073 17.7081 5.1724 17.7081H14.8224C16.4141 17.7081 17.7057 16.4165 17.7057 14.8248V8.75815C17.7057 7.95815 17.1307 6.84981 16.4724 6.39982L11.3224 2.79148C10.3724 2.12482 8.80573 2.15815 7.8974 2.86648Z" fill="black"/>
              <path d="M10 15.625C9.65833 15.625 9.375 15.3417 9.375 15V12.5C9.375 12.1583 9.65833 11.875 10 11.875C10.3417 11.875 10.625 12.1583 10.625 12.5V15C10.625 15.3417 10.3417 15.625 10 15.625Z" fill="black"/>
              </svg>
              </span> Home</a>
            </li>
            <li><a href="{{route('event.event_lists')}}" class="{{ request()->segment(1) === 'event_lists' ? 'active' : '' }}"><span><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6.66406 4.7915C6.3224 4.7915 6.03906 4.50817 6.03906 4.1665V1.6665C6.03906 1.32484 6.3224 1.0415 6.66406 1.0415C7.00573 1.0415 7.28906 1.32484 7.28906 1.6665V4.1665C7.28906 4.50817 7.00573 4.7915 6.66406 4.7915Z" fill="black"/>
              <path d="M13.3359 4.7915C12.9943 4.7915 12.7109 4.50817 12.7109 4.1665V1.6665C12.7109 1.32484 12.9943 1.0415 13.3359 1.0415C13.6776 1.0415 13.9609 1.32484 13.9609 1.6665V4.1665C13.9609 4.50817 13.6776 4.7915 13.3359 4.7915Z" fill="black"/>
              <path d="M7.08333 12.0834C6.975 12.0834 6.86666 12.0584 6.76666 12.0168C6.65833 11.9751 6.57501 11.9167 6.49167 11.8417C6.34167 11.6834 6.25 11.4667 6.25 11.2501C6.25 11.0334 6.34167 10.8167 6.49167 10.6584C6.57501 10.5834 6.66666 10.5251 6.76666 10.4834C6.96666 10.4001 7.2 10.4001 7.4 10.4834C7.5 10.5251 7.59166 10.5834 7.67499 10.6584C7.70833 10.7001 7.74999 10.7417 7.77499 10.7834C7.80832 10.8334 7.83334 10.8834 7.85001 10.9334C7.87501 10.9834 7.89168 11.0334 7.90001 11.0834C7.90834 11.1417 7.91667 11.2001 7.91667 11.2501C7.91667 11.4667 7.82499 11.6834 7.67499 11.8417C7.59166 11.9167 7.5 11.9751 7.4 12.0168C7.3 12.0584 7.19167 12.0834 7.08333 12.0834Z" fill="black"/>
              <path d="M9.9974 12.0835C9.88906 12.0835 9.78072 12.0585 9.68072 12.0168C9.57239 11.9752 9.48907 11.9168 9.40574 11.8418C9.25574 11.6835 9.16406 11.4668 9.16406 11.2502C9.16406 11.2002 9.17239 11.1418 9.18072 11.0835C9.18905 11.0335 9.20572 10.9835 9.23072 10.9335C9.24738 10.8835 9.27241 10.8335 9.30574 10.7835C9.33907 10.7418 9.3724 10.7002 9.40574 10.6585C9.71407 10.3502 10.2724 10.3502 10.5891 10.6585C10.6224 10.7002 10.6557 10.7418 10.6891 10.7835C10.7224 10.8335 10.7474 10.8835 10.7641 10.9335C10.7891 10.9835 10.8057 11.0335 10.8141 11.0835C10.8224 11.1418 10.8307 11.2002 10.8307 11.2502C10.8307 11.4668 10.7391 11.6835 10.5891 11.8418C10.4307 11.9918 10.2224 12.0835 9.9974 12.0835Z" fill="black"/>
              <path d="M7.08333 14.9999C6.975 14.9999 6.86666 14.9749 6.76666 14.9333C6.66666 14.8916 6.57501 14.8332 6.49167 14.7582C6.34167 14.5999 6.25 14.3832 6.25 14.1666C6.25 14.1166 6.25832 14.0582 6.26666 14.0082C6.27499 13.9499 6.29166 13.8999 6.31666 13.8499C6.33332 13.7999 6.35834 13.7499 6.39168 13.6999C6.41668 13.6582 6.45834 13.6166 6.49167 13.5749C6.57501 13.4999 6.66666 13.4416 6.76666 13.3999C6.96666 13.3166 7.2 13.3166 7.4 13.3999C7.5 13.4416 7.59166 13.4999 7.67499 13.5749C7.70833 13.6166 7.74999 13.6582 7.77499 13.6999C7.80832 13.7499 7.83334 13.7999 7.85001 13.8499C7.87501 13.8999 7.89168 13.9499 7.90001 14.0082C7.90834 14.0582 7.91667 14.1166 7.91667 14.1666C7.91667 14.3832 7.82499 14.5999 7.67499 14.7582C7.59166 14.8332 7.5 14.8916 7.4 14.9333C7.3 14.9749 7.19167 14.9999 7.08333 14.9999Z" fill="black"/>
              <path d="M17.0807 8.20019H2.91406C2.5724 8.20019 2.28906 7.91686 2.28906 7.5752C2.28906 7.23353 2.5724 6.9502 2.91406 6.9502H17.0807C17.4224 6.9502 17.7057 7.23353 17.7057 7.5752C17.7057 7.91686 17.4224 8.20019 17.0807 8.20019Z" fill="black"/>
              <path d="M13.1804 18.9833C12.8637 18.9833 12.5637 18.8667 12.347 18.65C12.0887 18.3917 11.972 18.0167 12.0304 17.625L12.1887 16.5C12.2304 16.2083 12.4054 15.8583 12.6137 15.65L15.5637 12.7C15.9637 12.3 16.3554 12.0917 16.7804 12.05C17.3054 12 17.8137 12.2167 18.297 12.7C18.8054 13.2083 19.4887 14.2417 18.297 15.4333L15.347 18.3833C15.1387 18.5917 14.7887 18.7667 14.497 18.8083L13.372 18.9667C13.3054 18.975 13.247 18.9833 13.1804 18.9833ZM16.922 13.2917C16.9137 13.2917 16.9054 13.2917 16.897 13.2917C16.7804 13.3 16.622 13.4083 16.447 13.5833L13.497 16.5333C13.472 16.5583 13.4304 16.6417 13.4304 16.675L13.2804 17.7167L14.322 17.5667C14.3554 17.5583 14.4387 17.5167 14.4637 17.4917L17.4137 14.5417C17.7804 14.175 17.8304 13.9917 17.4137 13.575C17.2804 13.45 17.0887 13.2917 16.922 13.2917Z" fill="black"/>
              <path d="M17.4338 16.0418C17.3755 16.0418 17.3171 16.0334 17.2671 16.0168C16.1671 15.7084 15.2922 14.8334 14.9838 13.7334C14.8922 13.4001 15.0838 13.0584 15.4171 12.9584C15.7505 12.8668 16.0921 13.0584 16.1921 13.3918C16.3838 14.0751 16.9255 14.6168 17.6088 14.8084C17.9421 14.9001 18.1338 15.2501 18.0421 15.5834C17.9588 15.8584 17.7088 16.0418 17.4338 16.0418Z" fill="black"/>
              <path d="M10 18.9582H6.66667C3.625 18.9582 1.875 17.2082 1.875 14.1665V7.08317C1.875 4.0415 3.625 2.2915 6.66667 2.2915H13.3333C16.375 2.2915 18.125 4.0415 18.125 7.08317V9.99984C18.125 10.3415 17.8417 10.6248 17.5 10.6248C17.1583 10.6248 16.875 10.3415 16.875 9.99984V7.08317C16.875 4.69984 15.7167 3.5415 13.3333 3.5415H6.66667C4.28333 3.5415 3.125 4.69984 3.125 7.08317V14.1665C3.125 16.5498 4.28333 17.7082 6.66667 17.7082H10C10.3417 17.7082 10.625 17.9915 10.625 18.3332C10.625 18.6748 10.3417 18.9582 10 18.9582Z" fill="black"/>
              </svg></span> Events </a>
            </li>
            <li><a href="{{route('profile.contact')}}"><span>
              <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M14.5391 18.9582C13.5974 18.9582 12.6057 18.7332 11.5807 18.2998C10.5807 17.8748 9.5724 17.2915 8.58906 16.5832C7.61406 15.8665 6.6724 15.0665 5.78073 14.1915C4.8974 13.2998 4.0974 12.3582 3.38906 11.3915C2.6724 10.3915 2.0974 9.3915 1.68906 8.42484C1.25573 7.3915 1.03906 6.3915 1.03906 5.44984C1.03906 4.79984 1.15573 4.18317 1.38073 3.60817C1.61406 3.0165 1.98906 2.4665 2.4974 1.9915C3.13906 1.35817 3.8724 1.0415 4.65573 1.0415C4.98073 1.0415 5.31406 1.1165 5.5974 1.24984C5.9224 1.39984 6.1974 1.62484 6.3974 1.92484L8.33073 4.64984C8.50573 4.8915 8.63906 5.12484 8.73073 5.35817C8.83906 5.60817 8.8974 5.85817 8.8974 6.09984C8.8974 6.4165 8.80573 6.72484 8.63073 7.0165C8.50573 7.2415 8.31406 7.48317 8.0724 7.72484L7.50573 8.3165C7.51406 8.3415 7.5224 8.35817 7.53073 8.37484C7.63073 8.54984 7.83073 8.84984 8.21406 9.29984C8.6224 9.7665 9.00573 10.1915 9.38906 10.5832C9.88073 11.0665 10.2891 11.4498 10.6724 11.7665C11.1474 12.1665 11.4557 12.3665 11.6391 12.4582L11.6224 12.4998L12.2307 11.8998C12.4891 11.6415 12.7391 11.4498 12.9807 11.3248C13.4391 11.0415 14.0224 10.9915 14.6057 11.2332C14.8224 11.3248 15.0557 11.4498 15.3057 11.6248L18.0724 13.5915C18.3807 13.7998 18.6057 14.0665 18.7391 14.3832C18.8641 14.6998 18.9224 14.9915 18.9224 15.2832C18.9224 15.6832 18.8307 16.0832 18.6557 16.4582C18.4807 16.8332 18.2641 17.1582 17.9891 17.4582C17.5141 17.9832 16.9974 18.3582 16.3974 18.5998C15.8224 18.8332 15.1974 18.9582 14.5391 18.9582ZM4.65573 2.2915C4.1974 2.2915 3.7724 2.4915 3.36406 2.8915C2.98073 3.24984 2.71406 3.6415 2.5474 4.0665C2.3724 4.49984 2.28906 4.95817 2.28906 5.44984C2.28906 6.22484 2.4724 7.0665 2.83906 7.93317C3.21406 8.8165 3.73906 9.73317 4.40573 10.6498C5.0724 11.5665 5.83073 12.4582 6.66406 13.2998C7.4974 14.1248 8.3974 14.8915 9.3224 15.5665C10.2224 16.2248 11.1474 16.7582 12.0641 17.1415C13.4891 17.7498 14.8224 17.8915 15.9224 17.4332C16.3474 17.2582 16.7224 16.9915 17.0641 16.6082C17.2557 16.3998 17.4057 16.1748 17.5307 15.9082C17.6307 15.6998 17.6807 15.4832 17.6807 15.2665C17.6807 15.1332 17.6557 14.9998 17.5891 14.8498C17.5641 14.7998 17.5141 14.7082 17.3557 14.5998L14.5891 12.6332C14.4224 12.5165 14.2724 12.4332 14.1307 12.3748C13.9474 12.2998 13.8724 12.2248 13.5891 12.3998C13.4224 12.4832 13.2724 12.6082 13.1057 12.7748L12.4724 13.3998C12.1474 13.7165 11.6474 13.7915 11.2641 13.6498L11.0391 13.5498C10.6974 13.3665 10.2974 13.0832 9.85573 12.7082C9.45573 12.3665 9.0224 11.9665 8.4974 11.4498C8.08906 11.0332 7.68073 10.5915 7.25573 10.0998C6.86406 9.6415 6.58073 9.24984 6.40573 8.92484L6.30573 8.67484C6.25573 8.48317 6.23906 8.37484 6.23906 8.25817C6.23906 7.95817 6.3474 7.6915 6.55573 7.48317L7.18073 6.83317C7.3474 6.6665 7.4724 6.50817 7.55573 6.3665C7.6224 6.25817 7.6474 6.1665 7.6474 6.08317C7.6474 6.0165 7.6224 5.9165 7.58073 5.8165C7.5224 5.68317 7.43073 5.53317 7.31406 5.37484L5.38073 2.6415C5.2974 2.52484 5.1974 2.4415 5.0724 2.38317C4.93906 2.32484 4.7974 2.2915 4.65573 2.2915ZM11.6224 12.5082L11.4891 13.0748L11.7141 12.4915C11.6724 12.4832 11.6391 12.4915 11.6224 12.5082Z" fill="#0F172A"/>
              </svg></span> Contacts</a>
            </li>
            <li><a href="{{route('event.event_drafts')}}" class="{{ request()->segment(1) === 'event_drafts' ? 'active' : '' }}"><span><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
              <path d="M6.66406 4.7915C6.3224 4.7915 6.03906 4.50817 6.03906 4.1665V1.6665C6.03906 1.32484 6.3224 1.0415 6.66406 1.0415C7.00573 1.0415 7.28906 1.32484 7.28906 1.6665V4.1665C7.28906 4.50817 7.00573 4.7915 6.66406 4.7915Z" fill="#0F172A"/>
              <path d="M13.3359 4.7915C12.9943 4.7915 12.7109 4.50817 12.7109 4.1665V1.6665C12.7109 1.32484 12.9943 1.0415 13.3359 1.0415C13.6776 1.0415 13.9609 1.32484 13.9609 1.6665V4.1665C13.9609 4.50817 13.6776 4.7915 13.3359 4.7915Z" fill="#0F172A"/>
              <path d="M7.08333 12.0834C6.975 12.0834 6.86666 12.0584 6.76666 12.0168C6.65833 11.9751 6.57501 11.9167 6.49167 11.8417C6.34167 11.6834 6.25 11.4667 6.25 11.2501C6.25 11.0334 6.34167 10.8167 6.49167 10.6584C6.57501 10.5834 6.66666 10.5251 6.76666 10.4834C6.96666 10.4001 7.2 10.4001 7.4 10.4834C7.5 10.5251 7.59166 10.5834 7.67499 10.6584C7.70833 10.7001 7.74999 10.7417 7.77499 10.7834C7.80832 10.8334 7.83334 10.8834 7.85001 10.9334C7.87501 10.9834 7.89168 11.0334 7.90001 11.0834C7.90834 11.1417 7.91667 11.2001 7.91667 11.2501C7.91667 11.4667 7.82499 11.6834 7.67499 11.8417C7.59166 11.9167 7.5 11.9751 7.4 12.0168C7.3 12.0584 7.19167 12.0834 7.08333 12.0834Z" fill="#0F172A"/>
              <path d="M9.9974 12.0835C9.88906 12.0835 9.78072 12.0585 9.68072 12.0168C9.57239 11.9752 9.48907 11.9168 9.40574 11.8418C9.25574 11.6835 9.16406 11.4668 9.16406 11.2502C9.16406 11.2002 9.17239 11.1418 9.18072 11.0835C9.18905 11.0335 9.20572 10.9835 9.23072 10.9335C9.24738 10.8835 9.27241 10.8335 9.30574 10.7835C9.33907 10.7418 9.3724 10.7002 9.40574 10.6585C9.71407 10.3502 10.2724 10.3502 10.5891 10.6585C10.6224 10.7002 10.6557 10.7418 10.6891 10.7835C10.7224 10.8335 10.7474 10.8835 10.7641 10.9335C10.7891 10.9835 10.8057 11.0335 10.8141 11.0835C10.8224 11.1418 10.8307 11.2002 10.8307 11.2502C10.8307 11.4668 10.7391 11.6835 10.5891 11.8418C10.4307 11.9918 10.2224 12.0835 9.9974 12.0835Z" fill="#0F172A"/>
              <path d="M7.08333 14.9999C6.975 14.9999 6.86666 14.9749 6.76666 14.9333C6.66666 14.8916 6.57501 14.8332 6.49167 14.7582C6.34167 14.5999 6.25 14.3832 6.25 14.1666C6.25 14.1166 6.25832 14.0582 6.26666 14.0082C6.27499 13.9499 6.29166 13.8999 6.31666 13.8499C6.33332 13.7999 6.35834 13.7499 6.39168 13.6999C6.41668 13.6582 6.45834 13.6166 6.49167 13.5749C6.57501 13.4999 6.66666 13.4416 6.76666 13.3999C6.96666 13.3166 7.2 13.3166 7.4 13.3999C7.5 13.4416 7.59166 13.4999 7.67499 13.5749C7.70833 13.6166 7.74999 13.6582 7.77499 13.6999C7.80832 13.7499 7.83334 13.7999 7.85001 13.8499C7.87501 13.8999 7.89168 13.9499 7.90001 14.0082C7.90834 14.0582 7.91667 14.1166 7.91667 14.1666C7.91667 14.3832 7.82499 14.5999 7.67499 14.7582C7.59166 14.8332 7.5 14.8916 7.4 14.9333C7.3 14.9749 7.19167 14.9999 7.08333 14.9999Z" fill="#0F172A"/>
              <path d="M17.0807 8.20019H2.91406C2.5724 8.20019 2.28906 7.91686 2.28906 7.5752C2.28906 7.23353 2.5724 6.9502 2.91406 6.9502H17.0807C17.4224 6.9502 17.7057 7.23353 17.7057 7.5752C17.7057 7.91686 17.4224 8.20019 17.0807 8.20019Z" fill="#0F172A"/>
              <path d="M13.1804 18.9833C12.8637 18.9833 12.5637 18.8667 12.347 18.65C12.0887 18.3917 11.972 18.0167 12.0304 17.625L12.1887 16.5C12.2304 16.2083 12.4054 15.8583 12.6137 15.65L15.5637 12.7C15.9637 12.3 16.3554 12.0917 16.7804 12.05C17.3054 12 17.8137 12.2167 18.297 12.7C18.8054 13.2083 19.4887 14.2417 18.297 15.4333L15.347 18.3833C15.1387 18.5917 14.7887 18.7667 14.497 18.8083L13.372 18.9667C13.3054 18.975 13.247 18.9833 13.1804 18.9833ZM16.922 13.2917C16.9137 13.2917 16.9054 13.2917 16.897 13.2917C16.7804 13.3 16.622 13.4083 16.447 13.5833L13.497 16.5333C13.472 16.5583 13.4304 16.6417 13.4304 16.675L13.2804 17.7167L14.322 17.5667C14.3554 17.5583 14.4387 17.5167 14.4637 17.4917L17.4137 14.5417C17.7804 14.175 17.8304 13.9917 17.4137 13.575C17.2804 13.45 17.0887 13.2917 16.922 13.2917Z" fill="#0F172A"/>
              <path d="M17.4338 16.0418C17.3755 16.0418 17.3171 16.0334 17.2671 16.0168C16.1671 15.7084 15.2922 14.8334 14.9838 13.7334C14.8922 13.4001 15.0838 13.0584 15.4171 12.9584C15.7505 12.8668 16.0921 13.0584 16.1921 13.3918C16.3838 14.0751 16.9255 14.6168 17.6088 14.8084C17.9421 14.9001 18.1338 15.2501 18.0421 15.5834C17.9588 15.8584 17.7088 16.0418 17.4338 16.0418Z" fill="#0F172A"/>
              <path d="M10 18.9582H6.66667C3.625 18.9582 1.875 17.2082 1.875 14.1665V7.08317C1.875 4.0415 3.625 2.2915 6.66667 2.2915H13.3333C16.375 2.2915 18.125 4.0415 18.125 7.08317V9.99984C18.125 10.3415 17.8417 10.6248 17.5 10.6248C17.1583 10.6248 16.875 10.3415 16.875 9.99984V7.08317C16.875 4.69984 15.7167 3.5415 13.3333 3.5415H6.66667C4.28333 3.5415 3.125 4.69984 3.125 7.08317V14.1665C3.125 16.5498 4.28333 17.7082 6.66667 17.7082H10C10.3417 17.7082 10.625 17.9915 10.625 18.3332C10.625 18.6748 10.3417 18.9582 10 18.9582Z" fill="#0F172A"/>
              </svg></span> Drafts</a>
            </li>
            <li><a href="{{route('profile')}}">Profile</a>
            </li>
            <div class="mobile-menu-inner-btns">
                <a href="#" class="add_new_event_btn create_event_with_plan"><i class="fa-solid fa-plus"></i> New Event</a>
                <div class="header-msg-wrp">
                  <a href="{{route('message.list')}}">
                    <svg viewBox="0 0 25 26" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M8.85286 20.2916H8.33203C4.16536 20.2916 2.08203 19.25 2.08203 14.0416L2.08203 8.83331C2.08203 4.66665 4.16536 2.58331 8.33203 2.58331L16.6654 2.58331C20.832 2.58331 22.9154 4.66665 22.9154 8.83331L22.9154 14.0416C22.9154 18.2083 20.832 20.2916 16.6654 20.2916H16.1445C15.8216 20.2916 15.5091 20.4479 15.3112 20.7083L13.7487 22.7916C13.0612 23.7083 11.9362 23.7083 11.2487 22.7916L9.6862 20.7083C9.51953 20.4791 9.13411 20.2916 8.85286 20.2916Z" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M7.29297 8.83331L17.7096 8.83331" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                      <path d="M7.29297 14.0417L13.543 14.0417" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                    @php
                    $count = getTotalUnreadMessageCount();
                    @endphp
                    @if ($count>0)
                    <span class="g-badge">{{$count}}</span>
                    @endif
                  </a>
                </div>
            </div>
          </ul>
      </div>
    </div>
  </div>
</header>
@else
<header class="login-header">
  <div class="container-fluid">
      <nav class="navbar navbar-expand-md navbar-dark">
          <a class="navbar-brand" href="{{(Auth::guard('web')->check())?route('profile'):route('front.home')}}">
             <svg width="140" height="65" viewBox="0 0 140 65" fill="none" xmlns="http://www.w3.org/2000/svg">
                 <rect width="128.118" height="36" fill="white"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M115.196 20.3691C115.196 24.4363 117.955 26.896 121.952 26.896C123.963 26.896 126.045 26.0264 127.337 24.1377L124.332 23.0484C123.858 23.593 123.015 23.9708 121.846 23.9708C120.458 23.9708 119.114 23.1274 118.991 21.3881H128.101C128.347 17.0397 125.843 13.9387 121.794 13.9387C118.192 13.9387 115.188 16.4423 115.188 20.3691H115.196ZM121.899 16.741C123.366 16.741 124.552 17.558 124.605 19.148H118.991C119.211 17.4614 120.485 16.741 121.899 16.741Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M105.517 17.1647H107.555V23.1207C107.555 26.0284 108.6 26.5994 111.622 26.5994H114.003V23.5248H112.913C111.701 23.5248 111.323 23.3052 111.323 22.1368V17.1735H114.003V14.2921H111.323V11.2175H107.546V14.2921H105.508V17.1735L105.517 17.1647Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M102.212 12.2452C103.45 12.2452 104.443 11.2262 104.443 10.0139C104.443 8.80161 103.45 7.78259 102.212 7.78259C100.973 7.78259 99.9805 8.77525 99.9805 10.0139C99.9805 11.2525 100.973 12.2452 102.212 12.2452Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M97.9688 17.1632H100.305V26.5979H104.1V14.2819H97.9688V17.1632Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M88.2907 26.5979H92.5337L96.9524 14.2819H93.0608L90.4254 22.6712L87.7988 14.2819H83.8984L88.2907 26.5979Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M77.1942 26.8979C80.4972 26.8979 83.2292 25.4836 83.2292 22.5759C83.2292 20.5115 81.8413 19.6506 78.8633 19.0972L76.5793 18.6755C75.49 18.4735 75.0683 18.1836 75.0683 17.6829C75.0683 17.0416 75.7623 16.6902 77.1064 16.6902C78.222 16.6902 79.1708 16.9362 79.4167 17.8322L82.5967 16.6638C81.6744 14.7312 79.4958 13.9318 77.1327 13.9318C74.0757 13.9318 71.4754 15.2495 71.4754 17.8322C71.4754 19.8966 73.1182 21.1089 75.4988 21.5042L77.7037 21.8731C79.118 22.1191 79.6188 22.4705 79.6188 22.9888C79.6188 23.7618 78.5031 24.1308 77.2821 24.1308C75.8677 24.1308 74.7521 23.63 74.3744 22.5935L71.168 23.7618C72.1079 25.8262 74.3216 26.8891 77.203 26.8891L77.1942 26.8979Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M57.1807 20.3691C57.1807 24.4363 59.939 26.896 63.936 26.896C65.9477 26.896 68.0297 26.0264 69.321 24.1377L66.3167 23.0484C65.8423 23.593 64.999 23.9708 63.8306 23.9708C62.4426 23.9708 61.0986 23.1274 60.9756 21.3881H70.0853C70.3312 17.0397 67.8276 13.9387 63.7779 13.9387C60.1762 13.9387 57.1719 16.4423 57.1719 20.3691H57.1807ZM63.8833 16.741C65.3504 16.741 66.5363 17.558 66.589 19.148H60.9756C61.1952 17.4614 62.469 16.741 63.8833 16.741Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M43.9766 14.2819L48.6939 26.5188L48.5709 26.8439C48.202 27.7838 47.9296 27.8629 46.3396 27.8629H45.5929V30.5949H46.8052C50.0116 30.5949 50.9252 29.7252 52.1199 26.5979L56.8372 14.2819H52.7875L50.4333 21.9772L48.0263 14.2819H43.9766Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M8.86739 6.81743H8.48087C8.25247 6.79986 8.3491 6.58024 8.3491 6.58024C8.49844 6.22886 8.61264 6.09709 8.42816 5.94775C8.09435 5.68421 7.81324 5.39432 7.58484 5.08686C6.42527 3.5232 6.71516 1.58179 7.79567 0.835103C8.41938 0.404657 9.33298 0.404657 9.95668 0.835103C11.0372 1.58179 11.3183 3.5232 10.1675 5.08686C9.93911 5.39432 9.66679 5.68421 9.32419 5.94775C9.13093 6.09709 9.25391 6.22007 9.40325 6.58024C9.40325 6.58024 9.49988 6.79107 9.27148 6.81743H8.88496H8.86739Z" fill="#ECB015"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M25.9174 9.75155C25.9174 9.04878 26.1634 8.4075 26.5851 7.91557C27.1121 7.28307 27.9028 6.88777 28.79 6.88777C30.3712 6.88777 31.6626 8.17032 31.6626 9.76034H34.6581C34.6581 6.51881 32.0315 3.89221 28.79 3.89221C26.6993 3.89221 24.8721 4.9815 23.8267 6.62423C23.774 6.71207 23.7213 6.79992 23.6686 6.88777C23.1942 7.73987 22.9219 8.71497 22.9219 9.76034H25.9174V9.75155Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M30.3548 22.1554C29.7223 27.5492 25.1455 31.7307 19.5848 31.7307C14.0242 31.7307 9.30681 27.4262 8.78852 21.9182L12.0125 21.3472L11.2658 17.1482L0.00390625 19.1423L0.750599 23.3413L4.57191 22.6649C5.45037 30.1758 11.8368 36 19.5848 36C27.3328 36 34.0004 29.9122 34.6504 22.1554C34.6856 21.7338 34.7031 21.3121 34.7031 20.8817C34.7031 20.4512 34.6856 20.0471 34.6504 19.643H30.3548C30.3987 20.0471 30.425 20.46 30.425 20.8817C30.425 21.3033 30.3987 21.7338 30.3548 22.1554Z" fill="black"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M3.12968 9.63361C3.36687 9.67753 3.32294 9.83566 3.3493 10.2222C3.3493 10.2222 3.36687 10.4506 3.5777 10.3627L3.72704 10.2837L6.16038 15.2645H6.22187L6.27458 15.2382L3.83245 10.231L3.77975 10.2573L3.98179 10.1607C4.17505 10.0465 4.00814 9.89715 4.00814 9.89715C3.72704 9.64239 3.56891 9.5809 3.67433 9.36129C3.86759 8.98355 3.99936 8.60581 4.06964 8.24564C4.45616 6.36573 3.39322 4.74058 2.11067 4.52096C1.7505 4.45947 1.38154 4.50339 1.01259 4.68787C-0.91124 5.64539 -0.0327782 9.06261 3.12968 9.63361Z" fill="#3ABEEA"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M9.31286 9.63361C9.07568 9.67753 9.1196 9.83566 9.09325 10.2222C9.09325 10.2222 9.07568 10.4506 8.86485 10.3627L8.71551 10.2837L6.28217 15.2645H6.22068L6.16797 15.2382L8.61009 10.231L8.6628 10.2573L8.46075 10.1607C8.26749 10.0465 8.4344 9.89715 8.4344 9.89715C8.71551 9.64239 8.87363 9.5809 8.76821 9.36129C8.57495 8.98355 8.44318 8.60581 8.37291 8.24564C7.98638 6.36573 9.04932 4.74058 10.3319 4.52096C10.692 4.45947 11.061 4.50339 11.43 4.68787C13.3538 5.64539 12.4753 9.06261 9.31286 9.63361Z" fill="#27B076"/>
                 <path opacity="0.21" fill-rule="evenodd" clip-rule="evenodd" d="M8.10188 3.07171C8.21608 2.97508 8.46205 3.0805 8.66409 3.3089C8.85735 3.5373 8.92763 3.80084 8.81343 3.89747C8.69923 3.9941 8.45326 3.88868 8.25122 3.66028C8.05795 3.43188 7.98768 3.16835 8.10188 3.07171Z" fill="white"/>
                 <path opacity="0.4" fill-rule="evenodd" clip-rule="evenodd" d="M8.51696 4.21295C8.56967 4.16903 8.68387 4.21295 8.77171 4.31837C8.85956 4.42378 8.8947 4.54677 8.84199 4.59069C8.78928 4.63461 8.67508 4.59069 8.58724 4.48528C8.49939 4.37986 8.47304 4.25688 8.51696 4.21295Z" fill="white"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M6.02755 6.30516H5.64103C5.41263 6.28759 5.50926 6.06797 5.50926 6.06797C5.65859 5.71659 5.77279 5.58482 5.58832 5.43548C5.2545 5.17194 4.97339 4.88205 4.74499 4.57459C3.58543 3.01093 3.87532 1.06953 4.95583 0.322835C5.57953 -0.107612 6.49313 -0.107612 7.11684 0.322835C8.19735 1.06953 8.47846 3.01093 7.32767 4.57459C7.09927 4.88205 6.82695 5.17194 6.48435 5.43548C6.29109 5.58482 6.41407 5.7078 6.56341 6.06797C6.56341 6.06797 6.66004 6.2788 6.43164 6.30516H6.04512H6.02755Z" fill="#ECB015"/>
                 <path d="M6.32807 10.9709H6.22266V15.2666H6.32807V10.9709Z" fill="#ECB015"/>
                 <path fill-rule="evenodd" clip-rule="evenodd" d="M4.28799 9.54683C4.88534 10.0476 5.47391 10.4868 5.92193 10.8118C6.17668 11.0051 6.02734 11.172 5.84286 11.6464C5.84286 11.6464 5.72866 11.9275 6.02734 11.9538H6.63348C6.94094 11.9362 6.81796 11.6464 6.81796 11.6464C6.6247 11.172 6.47536 11.0051 6.7389 10.8118C7.18691 10.4868 7.77548 10.0476 8.37283 9.54683C9.26886 8.78257 10.2527 7.86018 10.771 6.79725C12.1766 3.94225 9.48848 -0.0108312 6.3348 2.46643C3.18113 -0.0108312 0.484249 3.94225 1.88979 6.79725C2.40808 7.8514 3.40074 8.78257 4.29677 9.54683H4.28799Z" fill="#EA555C"/>
                 <g filter="url(#filter0_d_7906_43769)">
                 <path d="M88 39.5C88 34.8056 91.8056 31 96.5 31H128V38C128 43.5228 123.523 48 118 48H96.5C91.8056 48 88 44.1944 88 39.5Z" fill="#FF4F84" shape-rendering="crispEdges"/>
                 <path d="M95.5859 43V35.9541H98.6523C99.9805 35.9541 100.815 36.6377 100.815 37.7217V37.7314C100.815 38.5029 100.229 39.1621 99.4531 39.2646V39.2939C100.435 39.3672 101.143 40.0605 101.143 40.9834V40.9932C101.143 42.2285 100.21 43 98.7061 43H95.5859ZM98.2715 37.0479H97.0605V38.8984H98.0908C98.9209 38.8984 99.3652 38.5518 99.3652 37.9414V37.9316C99.3652 37.3701 98.96 37.0479 98.2715 37.0479ZM98.2666 39.8994H97.0605V41.9014H98.3301C99.1748 41.9014 99.6387 41.5547 99.6387 40.9004V40.8906C99.6387 40.2461 99.1699 39.8994 98.2666 39.8994ZM102.482 43V35.9541H107.15V37.1699H103.957V38.8594H106.97V40.0117H103.957V41.7842H107.15V43H102.482ZM110.399 43V37.1699H108.363V35.9541H113.915V37.1699H111.874V43H110.399ZM113.824 43L116.285 35.9541H118.019L120.475 43H118.927L118.395 41.291H115.904L115.372 43H113.824ZM117.135 37.3213L116.246 40.1875H118.053L117.164 37.3213H117.135Z" fill="white"/>
                 </g>
                 <defs>
                 <filter id="filter0_d_7906_43769" x="76" y="24" width="64" height="41" filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                 <feFlood flood-opacity="0" result="BackgroundImageFix"/>
                 <feColorMatrix in="SourceAlpha" type="matrix" values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha"/>
                 <feOffset dy="5"/>
                 <feGaussianBlur stdDeviation="6"/>
                 <feComposite in2="hardAlpha" operator="out"/>
                 <feColorMatrix type="matrix" values="0 0 0 0 0.809701 0 0 0 0 0.14034 0 0 0 0 0.341909 0 0 0 0.24 0"/>
                 <feBlend mode="normal" in2="BackgroundImageFix" result="effect1_dropShadow_7906_43769"/>
                 <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_7906_43769" result="shape"/>
                 </filter>
                 </defs>
                 </svg>
                 </a>


                         @php
                         $userprofile = Auth::guard('web')->user();
                         @endphp

                         @if($userprofile==null)
                         <button class="navbar-toggler toggle" id="ChangeToggle" type="button" data-bs-toggle="collapse" data-bs-target="#mynavbar">
                             <div id="navbar-hamburger">
                                 <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                     <path d="M3.5 7H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" />
                                     <path d="M3.5 12H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" />
                                     <path d="M3.5 17H21.5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" />
                                 </svg>
                             </div>
                             <div id="navbar-close" class="hidden">
                                 <span class="glyphicon glyphicon-remove">
                                     <svg width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                         <path d="M5.5 5L19.4991 18.9991" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                         <path d="M5.50094 18.9991L19.5 5" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                     </svg>
                                 </span>
                             </div>
                         </button>
                         @endif



                             <div class="collapse navbar-collapse" id="mynavbar">
                                 <ul class="navbar-nav align-items-center ms-auto">
                                     <li class="nav-item {{ (Request::segment(1) == '')? 'active':'' }}">
                                         <a class="nav-link" href="{{ route('front.home')}}">Home</a>
                                     </li>
                                     <li class="nav-item {{ (Request::segment(1) == 'about-us')? 'active':'' }}">
                                         <a class="nav-link" href="{{ route('about')}}">About</a>
                                     </li>

                                     @if(Request::segment(1) !== 'rsvp')
                                          {{-- <li class="nav-item d-flex align-items-center gap-3">
                                            <a class="nav-link signIn-btn" href="{{route('auth.login')}}">Sign In</a>
                                            <a class="nav-link signIn-btn" href="{{route('auth.register')}}">
                                                <span><svg width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M4.15743 14.6397C3.69993 14.6397 3.27243 14.4822 2.96493 14.1897C2.57493 13.8222 2.38743 13.2672 2.45493 12.6672L2.73243 10.2372C2.78493 9.77967 3.06243 9.17217 3.38493 8.84217L9.54243 2.32467C11.0799 0.69717 12.6849 0.652171 14.3124 2.18967C15.9399 3.72717 15.9849 5.33217 14.4474 6.95967L8.28993 13.4772C7.97493 13.8147 7.38993 14.1297 6.93243 14.2047L4.51743 14.6172C4.38993 14.6247 4.27743 14.6397 4.15743 14.6397ZM11.9499 2.18217C11.3724 2.18217 10.8699 2.54217 10.3599 3.08217L4.20243 9.60717C4.05243 9.76467 3.87993 10.1397 3.84993 10.3572L3.57243 12.7872C3.54243 13.0347 3.60243 13.2372 3.73743 13.3647C3.87243 13.4922 4.07493 13.5372 4.32243 13.4997L6.73743 13.0872C6.95493 13.0497 7.31493 12.8547 7.46493 12.6972L13.6224 6.17967C14.5524 5.18967 14.8899 4.27467 13.5324 2.99967C12.9324 2.42217 12.4149 2.18217 11.9499 2.18217Z" fill="white" />
                                                        <path d="M13.0044 8.21165C12.9894 8.21165 12.9669 8.21165 12.9519 8.21165C10.6119 7.97915 8.72935 6.20165 8.36935 3.87665C8.32435 3.56915 8.53435 3.28415 8.84185 3.23165C9.14935 3.18665 9.43435 3.39665 9.48685 3.70415C9.77185 5.51916 11.2419 6.91415 13.0719 7.09415C13.3794 7.12415 13.6044 7.40165 13.5744 7.70915C13.5369 7.99415 13.2894 8.21165 13.0044 8.21165Z" fill="white" />
                                                        <path d="M15.75 17.0625H2.25C1.9425 17.0625 1.6875 16.8075 1.6875 16.5C1.6875 16.1925 1.9425 15.9375 2.25 15.9375H15.75C16.0575 15.9375 16.3125 16.1925 16.3125 16.5C16.3125 16.8075 16.0575 17.0625 15.75 17.0625Z" fill="white" />
                                                    </svg></span>
                                                Sign Up
                                            </a>
                                        </li> --}}
                                     @endif

                                 </ul>
                             </div>

                     </nav>
                 </div>

</header>
@endif


<div class="modal fade create-post-modal all-events-filtermodal" id="all-notification-filter-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h1 class="modal-title fs-5" id="exampleModalLabel"><i class="fa-solid fa-arrow-left mr-1 d-none notification-back"></i> Filter</h1>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
          <div class="all-events-filter-wrp all-events-filter-info">
              <form action="" id="event_filter">
                <div class="notification-filter-events">
                  <h4>Events <i class="fa-solid fa-angle-right"></i></h4>
                  <div class="notification-selected-events-wrp pb-2">
                    {{-- <span>All Events</span>
                    <span>All Events</span>
                    <span>All Events</span> --}}
                  </div>
                </div>

                <div class="notification-filter-sub">
                  <h3>Notification Type</h3>
                  <div class="form-check">
                    <input class="form-check-input " data-name="read" name="notificationTypes[]" type="checkbox" value="" id="flexCheckDefault1">
                    <label class="form-check-label " for="flexCheckDefault1">
                      Read
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input " data-name="unread" name="notificationTypes[]" type="checkbox" value="" id="flexCheckDefault2">
                    <label class="form-check-label " for="flexCheckDefault2">
                      Unread
                    </label>
                  </div>
                </div>
                <div class="notification-filter-sub">
                  <h3>Activity</h3>
                  <div class="form-check">
                    <input class="form-check-input hosting_chk" data-name="all" name="activityTypes[]" type="checkbox" value="" id="flexCheckDefault1">
                    <label class="form-check-label hosting_chk_lbl" for="flexCheckDefault1">
                      All
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input invited_to_chk" data-name="tag&mentions" name="activityTypes[]" type="checkbox" value="" id="flexCheckDefault2">
                    <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                      Tag & Mentions
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input invited_to_chk" data-name="comments" name="activityTypes[]" type="checkbox" value="" id="flexCheckDefault2">
                    <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                      Comments
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input invited_to_chk" data-name="rsvp" name="activityTypes[]" type="checkbox" value="" id="flexCheckDefault2">
                    <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                      RSVP's
                    </label>
                  </div>
                  <div class="form-check">
                    <input class="form-check-input invited_to_chk" data-name="like" name="activityTypes[]" type="checkbox" value="" id="flexCheckDefault2">
                    <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                      Likes
                    </label>
                  </div>
                </div>
                {{-- <div class="form-check">
                  <input class="form-check-input past_event_chk" type="checkbox" value="" id="flexCheckDefault4">
                  <label class="form-check-label" for="flexCheckDefault4">
                     Past Events <strong>({{$filter['past_event']}})</strong>
                  </label>
                </div> --}}
              </form>
          </div>
          <div class="notification-all-event-wrp d-none">
            <div class="all-events-searchbar-wrp mb-2">
              <form>
                <div class="position-relative">
                  <input type="text" class="form-control" id="search_upcoming_event" placeholder="Search event name">
                  <span class="search-icon">
                    <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M9.58366 17.5C13.9559 17.5 17.5003 13.9555 17.5003 9.58329C17.5003 5.21104 13.9559 1.66663 9.58366 1.66663C5.2114 1.66663 1.66699 5.21104 1.66699 9.58329C1.66699 13.9555 5.2114 17.5 9.58366 17.5Z" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M18.3337 18.3333L16.667 16.6666" stroke="#0F172A" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                    </svg>
                  </span>
                </div>
              </form>
            </div>
            <div class="all-events-filter-wrp">
              <form action="" id="event_filter">
                  <div class="notification-filter-sub">

                    @if (Auth::guard('web')->check())

                    @php
                      $user  = Auth::guard('web')->user()->id;
                      $data=getAllEventList($user);
                    @endphp

                    @foreach ($data as $event )
                    <div class="form-check">
                      <input class="form-check-input" name="selectedEvents[]" data-event_name="{{$event['event_name']}}"data-event_id="{{$event['id']}}" type="checkbox" value="" id="flexCheckDefault2">
                      <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                        {{$event['event_name']}}
                      </label>
                    </div>
                    @endforeach
                    @endif
                  </div>
              </form>
            </div>
          </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="cmn-btn reset-btn all-event-notification-filter-reset">Reset</button>
        <button type="button" class="cmn-btn notification_filter_apply_btn">Apply</button>
      </div>
    </div>
  </div>
</div>
