@php
    use Carbon\Carbon;
@endphp
@isset($eventDetails)
    <main class="new-main-content">
        {{-- {{ dd($eventDetails) }} --}}
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-4">
                    <!-- =============mainleft-====================== -->

                    <x-event_wall.wall_left_menu :page="$current_page" :eventDetails="$eventDetails" />
                </div>
                <div class="col-xl-6 col-lg-8">
                    <div class="main-content-center">
                        <!-- ===event-breadcrumb-wrp-start=== -->
                        <div class="event-breadcrumb-wrp">
                            <nav style="
                    --bs-breadcrumb-divider: url(
                      &#34;data:image/svg + xml,
                      %3Csvgxmlns='http://www.w3.org/2000/svg'width='8'height='8'%3E%3Cpathd='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z'fill='%236c757d'/%3E%3C/svg%3E&#34;
                    );
                  "
                                aria-label="breadcrumb">
                                <ol class="breadcrumb">
                                    <li class="breadcrumb-item"><a href="#">Events</a></li>
                                    <li class="breadcrumb-item">
                                        <a
                                            href="{{ route('event.event_wall', encrypt($eventDetails['id'])) }}">{{ $eventDetails['event_name'] }}</a>
                                    </li>
                                    <li class="breadcrumb-item active" aria-current="page">
                                        About
                                    </li>
                                </ol>
                            </nav>
                        </div>
                        <!-- ===event-breadcrumb-wrp-end=== -->
                        <x-event_wall.wall_title :eventDetails="$eventDetails" />
                        <!-- ===event-center-title-start=== -->

                        <!-- ===event-center-title-end=== -->

                        <!-- ===event-center-tabs-main-start=== -->
                        <div class="event-center-tabs-main">
                            <!-- ====================navbar-============================= -->
                            {{-- <x-event_wall.wall_navbar :event="$event" :current_page="$current_page"/> --}}
                            <x-event_wall.wall_navbar :event="$event" :page="$current_page" :eventDetails="$eventDetails" />
                            {{-- {{dd($page)}} --}}
                            <!-- ===tab-content-start=== -->
                            <div class="tab-content" id="nav-tabContent">
                                @php
                                    $about_active = '';
                                    $about_show = '';
                                    if ($current_page == 'about') {
                                        $about_active = 'active';
                                        $about_show = 'show';
                                    }
                                @endphp
                                <div class="tab-pane fade {{ $about_active }} {{ $about_show }}" id="nav-about"
                                    role="tabpanel" aria-labelledby="nav-about-tab">
                                    <div class="about-main-wrp">
                                        <div class="about-details cmn-card">
                                            <h4 class="title">Details</h4>
                                            <div class="hosted-by-template-slider about-slider">
                                                <div class="swiper mySwiper">
                                                    <div class="swiper-wrapper">
                                                        <!-- Slides -->
                                                        <div class="swiper-slide">
                                                            <div class="hosted-by-template-slider-img">
                                                                <img src="{{ asset('assets/front/img/host-by-template-img.png') }}"
                                                                    alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="swiper-slide">
                                                            <div class="hosted-by-template-slider-img">
                                                                <img src="{{ asset('assets/front/img/host-by-template-img.png') }}"
                                                                    alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="swiper-slide">
                                                            <div class="hosted-by-template-slider-img">
                                                                <img src="{{ asset('assets/front/img/host-by-template-img.png') }}"
                                                                    alt="" />
                                                            </div>
                                                        </div>
                                                        <div class="swiper-slide">
                                                            <div class="hosted-by-template-slider-img">
                                                                <img src="{{ asset('assets/front/img/host-by-template-img.png') }}"
                                                                    alt="" />
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <!-- Custom Pagination -->
                                                    <div class="custom-pagination"></div>
                                                    <button class="image-zoom-icon"><img
                                                            src="{{ asset('assets/front/img/image-zoom-icon.png') }}"
                                                            alt=""></button>
                                                </div>
                                            </div>
                                            <div class="birth-details">
                                                <div class="d-flex align-items-center">
                                                    <div class="birth-img">
                                                        @if ($eventDetails['user_profile'] != '')
                                                            <img src="{{ $eventDetails['user_profile'] }}" alt="birth-img">
                                                        @else
                                                            @php

                                                                // $parts = explode(" ", $name);
                                                                $nameParts = explode(' ', $eventDetails['hosted_by']);
                                                                $firstInitial = isset($nameParts[0][0])
                                                                    ? strtoupper($nameParts[0][0])
                                                                    : '';
                                                                $secondInitial = isset($nameParts[1][0])
                                                                    ? strtoupper($nameParts[1][0])
                                                                    : '';
                                                                $initials = $firstInitial . $secondInitial;

                                                                // Generate a font color class based on the first initial
                                                                $fontColor = 'fontcolor' . $firstInitial;
                                                            @endphp
                                                            <h5 class="{{ $fontColor }}">
                                                                {{ $initials }}
                                                            </h5>
                                                        @endif

                                                    </div>
                                                    <div class="birth-host">
                                                        <h5>{{ $eventDetails['event_name'] }}</h5>
                                                        <span>Hosted by:
                                                            <span>{{ $eventDetails['hosted_by'] }}</span></span>
                                                    </div>
                                                </div>
                                                <div class="host-detail">
                                                    <ul>
                                                        @if (!empty($eventDetails['rsvp_by']))
                                                            <li>RSVP By:
                                                                {{ \Carbon\Carbon::parse($eventDetails['rsvp_by'])->format('F d, Y') }}
                                                            </li>
                                                        @endif
                                                        @if ($eventDetails['podluck'] == 1)
                                                            <li>Potluck Event</li>
                                                        @endif
                                                        @if ($eventDetails['adult_only_party'] == 1)
                                                            <li>Adults Only</li>
                                                        @endif
                                                        @if (!empty($eventDetails['end_date']) && $eventDetails['event_date'] != $eventDetails['end_date'])
                                                            <li>Multiple Day Event</li>
                                                        @endif
                                                        @if (!empty($eventDetails['co_hosts']))
                                                            <li>Co-Host</li>
                                                        @endif
                                                        @if (!empty($eventDetails['gift_registry']))
                                                            <li>Gift Registry</li>
                                                        @endif
                                                        @if (!empty($eventDetails['event_schedule']))
                                                            <li>Event has schedule</li>
                                                        @endif
                                                        @if (!empty($eventDetails['allow_limit']))
                                                            <li>Can Bring Gursts ({{ $eventDetails['allow_limit'] }})</li>
                                                        @endif
                                                    </ul>
                                                </div>
                                                <div class="hosted-by-date-time">
                                                    <div class="hosted-by-date-time-left">
                                                        <div class="hosted-by-date-time-left-icon">
                                                            <svg width="30" height="30" viewBox="0 0 30 30"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path d="M10 2.5V6.25" stroke="black" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M20 2.5V6.25" stroke="black" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M4.375 11.3633H25.625" stroke="black"
                                                                    stroke-width="1.5" stroke-miterlimit="10"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M26.25 10.625V21.25C26.25 25 24.375 27.5 20 27.5H10C5.625 27.5 3.75 25 3.75 21.25V10.625C3.75 6.875 5.625 4.375 10 4.375H20C24.375 4.375 26.25 6.875 26.25 10.625Z"
                                                                    stroke="black" stroke-width="1.5" stroke-miterlimit="10"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M19.6181 17.125H19.6294" stroke="black"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M19.6181 20.875H19.6294" stroke="black"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M14.9951 17.125H15.0063" stroke="black"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M14.9951 20.875H15.0063" stroke="black"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M10.3681 17.125H10.3794" stroke="black"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M10.3681 20.875H10.3794" stroke="black"
                                                                    stroke-width="2" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                        <div class="hosted-by-date-time-content">
                                                            <h6>Date</h6>
                                                            <h3>{{ \Carbon\Carbon::parse($eventDetails['event_date'])->format('M d, Y') }}
                                                                @if (!empty($eventDetails['end_date']))
                                                                    to
                                                                    {{ \Carbon\Carbon::parse($eventDetails['end_date'])->format('M d, Y') }}
                                                                @endif

                                                            </h3>
                                                        </div>
                                                    </div>
                                                    <div class="hosted-by-date-time-left">
                                                        <div class="hosted-by-date-time-left-icon">
                                                            <svg width="31" height="30" viewBox="0 0 31 30"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M28 15C28 21.9 22.4 27.5 15.5 27.5C8.6 27.5 3 21.9 3 15C3 8.1 8.6 2.5 15.5 2.5C22.4 2.5 28 8.1 28 15Z"
                                                                    stroke="black" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M20.1371 18.9742L16.2621 16.6617C15.5871 16.2617 15.0371 15.2992 15.0371 14.5117V9.38672"
                                                                    stroke="black" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </div>
                                                        <div class="hosted-by-date-time-content">
                                                            <h6>Time</h6>
                                                            <h3>{{ $eventDetails['event_time'] }}
                                                                @if (!empty($eventDetails['end_time']))
                                                                    to {{ $eventDetails['end_time'] }}
                                                                @endif
                                                            </h3>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="countdown-event">
                                                    <div class="d-flex align-items-center countevent-head">
                                                        <span class="d-flex align-items-center">
                                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M7.00086 2.71094C4.21253 2.71094 1.94336 4.9801 1.94336 7.76844C1.94336 10.5568 4.21253 12.8318 7.00086 12.8318C9.78919 12.8318 12.0584 10.5626 12.0584 7.77427C12.0584 4.98594 9.78919 2.71094 7.00086 2.71094ZM7.43836 7.58177C7.43836 7.82094 7.24003 8.01927 7.00086 8.01927C6.76169 8.01927 6.56336 7.82094 6.56336 7.58177V4.6651C6.56336 4.42594 6.76169 4.2276 7.00086 4.2276C7.24003 4.2276 7.43836 4.42594 7.43836 4.6651V7.58177Z"
                                                                    fill="#F73C71" />
                                                                <path
                                                                    d="M8.6862 2.0138H5.31453C5.0812 2.0138 4.89453 1.82714 4.89453 1.5938C4.89453 1.36047 5.0812 1.16797 5.31453 1.16797H8.6862C8.91953 1.16797 9.1062 1.35464 9.1062 1.58797C9.1062 1.8213 8.91953 2.0138 8.6862 2.0138Z"
                                                                    fill="#F73C71" />
                                                            </svg>
                                                        </span>
                                                        <h5>Countdown till event</h5>
                                                    </div>
                                                    {{-- @php
                                                        // Parse the event date and time
                                                        $eventDateTime = Carbon::parse(
                                                            $eventDetails['event_date'] .
                                                                ' ' .
                                                                $eventDetails['event_time'],
                                                        );

                                                        // Get the current time
                                                        $now = Carbon::now();

                                                        // Calculate the difference in days, hours, and minutes
                                                        $days = $now->diffInDays($eventDateTime, false); // Negative if past the event
                                                        $hours = $now
                                                            ->copy()
                                                            ->addDays($days)
                                                            ->diffInHours($eventDateTime, false);
                                                        $minutes = $now
                                                            ->copy()
                                                            ->addDays($days)
                                                            ->addHours($hours)
                                                            ->diffInMinutes($eventDateTime, false);
                                                    @endphp  --}}

                                                    @php

                                                        // Parse the event date and time
                                                        // $eventDateTime = Carbon::createFromFormat('Y-m-d h:i A', $eventDetails['event_date'] . ' ' . $eventDetails['event_time']);

                                                        // Get the event timestamp
                                                        // $eventTimestamp = $eventDetails['event_time'];
                                                        $startdate = $eventDetails['event_date'];
                                                        $starttime = $eventDetails['event_time'];
                                                        // dd($eventDetails['event_date'])// Convert to milliseconds for JavaScript
                                                    @endphp

                                                    {{-- <div class="countevent-counter-box">
                                                        @if ($days >= 0 && $hours >= 0 && $minutes >= 0)
                                                            <div class="countevent-counter">
                                                                <h4>{{ $countdownDays }}</h4>
                                                                <span>Days</span>
                                                                <img src="{{ asset('assets/front/img/colon.svg') }}"
                                                                    alt="" class="colon-img">
                                                            </div>
                                                            <div class="countevent-counter">
                                                                <h4>{{ $countdownHours }}</h4>
                                                                <span>Hours</span>
                                                                <img src="{{ asset('assets/front/img/colon.svg') }}"
                                                                    alt="" class="colon-img">
                                                            </div>
                                                            <div class="countevent-counter">
                                                                <h4>{{ $countdownMinutes }}</h4>
                                                                <span>Minutes</span>
                                                                <img src="{{ asset('assets/front/img/colon.svg') }}"
                                                                    alt="" class="colon-img">
                                                            </div>
                                                            <div class="countevent-counter">
                                                                <h4>{{ $countdownSeconds }}</h4>
                                                                <span>Seconds</span>
                                                            </div>
                                                        @endif
                                                    </div> --}}

                                                    <div class="countevent-counter-box">
                                                        <div class="countevent-counter">
                                                            <h4 id="countdownDays">00</h4>
                                                            <span>Days</span>
                                                            <img src="{{ asset('assets/front/img/colon.svg') }}"
                                                                alt="" class="colon-img">
                                                        </div>
                                                        <div class="countevent-counter">
                                                            <h4 id="countdownHours">00</h4>
                                                            <span>Hours</span>
                                                            <img src="{{ asset('assets/front/img/colon.svg') }}"
                                                                alt="" class="colon-img">
                                                        </div>
                                                        <div class="countevent-counter">
                                                            <h4 id="countdownMinutes">00</h4>
                                                            <span>Minutes</span>

                                                        </div>

                                                    </div>

                                                </div>
                                                <div class="detail-btn-wrp">
                                                    @if (!($eventDetails['host_id'] == $login_user_id && $eventDetails['is_host'] == 1 && !empty($eventDetails['co_hosts'])))
                                                        <a href="#" class="add-calender btn" id="openGoogle">Add to
                                                            calendar
                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M7.9987 14.6668C11.6654 14.6668 14.6654 11.6668 14.6654 8.00016C14.6654 4.3335 11.6654 1.3335 7.9987 1.3335C4.33203 1.3335 1.33203 4.3335 1.33203 8.00016C1.33203 11.6668 4.33203 14.6668 7.9987 14.6668Z"
                                                                    stroke="#0F172A" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M5.33203 8H10.6654" stroke="#0F172A"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M8 10.6668V5.3335" stroke="#0F172A"
                                                                    stroke-width="1.5" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    @endif

                                                    <input type="hidden" id="eventDate"
                                                        value="{{ $eventDetails['event_date'] }}">
                                                    <input type="hidden" id="eventEndDate"
                                                        value="{{ $eventDetails['end_date'] }}">
                                                    <input type="hidden" id="eventTime"
                                                        value="{{ $eventDetails['event_time'] }}">
                                                    <input type="hidden" id="eventEndTime"
                                                        value="{{ $eventDetails['end_time'] }}">
                                                    <input type="hidden" id="eventName"
                                                        value="{{ $eventDetails['event_name'] }}">


                                                    @php
                                                        $isDisabled =
                                                            $eventDetails['host_id'] == $login_user_id ? 'd-none' : ''; // Using 'd-none' to hide the link
                                                    @endphp
                                                    {{-- {{ dd($rsvpSent)}} --}}

                                                    @if (!empty($rsvpSent) &&  $rsvpSent['rsvp_status'] == null)
                                                        <a href="#" class="rsvp-btn btn {{ $isDisabled }}"
                                                            data-bs-toggle="modal" data-bs-target="#aboutsuccess">
                                                            RSVP
                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M13.2807 5.96484L8.93404 10.3115C8.4207 10.8248 7.5807 10.8248 7.06737 10.3115L2.7207 5.96484"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    @if (!empty($rsvpSent) && $rsvpSent['rsvp_status'] == "0")
                                                        <a href="#"
                                                            class="rsvp-btn noattending-btn btn {{ $isDisabled }}"data-bs-toggle="modal"
                                                            data-bs-target="#aboutsuccess">Not Attending
                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M13.2807 5.96484L8.93404 10.3115C8.4207 10.8248 7.5807 10.8248 7.06737 10.3115L2.7207 5.96484"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                    @if ( !empty($rsvpSent) && $rsvpSent['rsvp_status'] == "1")
                                                        <a href="#"
                                                            class="rsvp-btn attending-btn btn {{ $isDisabled }}"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#aboutsuccess">Attending
                                                            <svg width="16" height="16" viewBox="0 0 16 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M13.2807 5.96484L8.93404 10.3115C8.4207 10.8248 7.5807 10.8248 7.06737 10.3115L2.7207 5.96484"
                                                                    stroke="white" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </a>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        {{-- {{      dd($eventDetails)}} --}}
                                        <div class="host-users-detail cmn-card">
                                            <h4 class="title">Your hosts</h4>
                                            <div class="host-user-con-box">
                                                @if ($eventDetails['hosted_by'])
                                                    <div class="host-user-con">
                                                        <div class="img-wrp">
                                                            @if ($eventDetails['user_profile'] != '')
                                                                <img src="{{ $eventDetails['user_profile'] }}"
                                                                    alt="host-img">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $nameParts = explode(
                                                                        ' ',
                                                                        $eventDetails['hosted_by'],
                                                                    );
                                                                    $firstInitial = isset($nameParts[0][0])
                                                                        ? strtoupper($nameParts[0][0])
                                                                        : '';
                                                                    $secondInitial = isset($nameParts[1][0])
                                                                        ? strtoupper($nameParts[1][0])
                                                                        : '';
                                                                    $initials = $firstInitial . $secondInitial;

                                                                    // Generate a font color class based on the first initial
                                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif

                                                        </div>
                                                        <h5>{{ $eventDetails['hosted_by'] }}</h5>
                                                        <span>Host</span>
                                                        <a href="#" class="msg-btn">Message</a>
                                                    </div>
                                                @endif
                                                @if (!empty($eventDetails['co_hosts']))
                                                    @foreach ($eventDetails['co_hosts'] as $co_host)
                                                        <div class="host-user-con">
                                                            <div class="img-wrp">
                                                                @if (!empty($co_host['profile']))
                                                                    <img src="{{ $co_host['profile'] }}"
                                                                        alt="cohost-img">
                                                                @else
                                                                    @php
                                                                        $nameParts = explode(' ', $co_host['name']);
                                                                        $firstInitial = isset($nameParts[0][0])
                                                                            ? strtoupper($nameParts[0][0])
                                                                            : '';
                                                                        $secondInitial = isset($nameParts[1][0])
                                                                            ? strtoupper($nameParts[1][0])
                                                                            : '';
                                                                        $initials = $firstInitial . $secondInitial;
                                                                        $fontColor = 'fontcolor' . $firstInitial;
                                                                    @endphp
                                                                    <h5 class="{{ $fontColor }}">{{ $initials }}
                                                                    </h5>
                                                                @endif
                                                            </div>
                                                            <h5>{{ $co_host['name'] }}</h5>
                                                            <span>Co-host</span>
                                                            <a href="#" class="msg-btn">Message</a>
                                                        </div>
                                                    @endforeach
                                                @endif

                                            </div>

                                            <p
                                                style="{{ empty($eventDetails['message_to_guests']) ? 'display: none;' : '' }}">
                                                “{{ $eventDetails['message_to_guests'] }}”
                                            </p>
                                        </div>
                                        @if (!empty($eventDetails['event_location_name']) || !empty($eventDetails['address_1']))
                                            <div class="location-wrp cmn-card">
                                                <h4 class="title">Location</h4>
                                                <h5>{{ $eventDetails['event_location_name'] ?: 'Tom’s House' }}</h5>
                                                <p>{{ $eventDetails['address_1'] }}, {{ $eventDetails['city'] }},
                                                    {{ $eventDetails['state'] }} {{ $eventDetails['zip_code'] }}</p>
                                                <div id="map">

                                                    <!-- Google Maps iframe with dynamic latitude and longitude -->



                                                    <img src="{{ asset('assets/front/img/location-marker.svg') }}"
                                                        alt="marker" class="marker">
                                                </div>


                                                @php
                                                    $latitude = !empty($eventDetails['latitude'])
                                                        ? $eventDetails['latitude']
                                                        : '23.020474099698593'; // Default latitude (Ahmedabad)
                                                    $longitude = !empty($eventDetails['logitude'])
                                                        ? $eventDetails['logitude']
                                                        : '72.41493076529625'; // Default longitude (Ahmedabad)
                                                @endphp

                                                <iframe
                                                    src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d235013.74843221347!2d{{ $longitude }}!3d{{ $latitude }}!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x395e848aba5bd449%3A0x4fcedd11614f6516!2sAhmedabad%2C%20Gujarat%2C%20India!5e0!3m2!1sen!2sus!4v1738165607121!5m2!1sen!2sus"
                                                    width="600" height="450" style="border:0;" allowfullscreen=""
                                                    loading="lazy" referrerpolicy="no-referrer-when-downgrade">
                                                </iframe>
                                                <a href="https://www.google.com/maps/dir/?api=1&destination={{ $latitude }},{{ $longitude }}"
                                                    target="_blank" class="direction-btn">
                                                    Directions
                                                </a>
                                            </div>
                                        @endif
                                        {{-- {{dd($eventDetails['event_schedule']);}} --}}
                                        @php
                                            $series = ['green', 'yellow', 'pink', 'blue', 'red'];
                                            $colorIndex = 0;
                                            $overallStartTime = null;
                                            $overallEndTime = null;
                                        @endphp
                                        @if (!empty($eventDetails['event_schedule']) && is_array($eventDetails['event_schedule']))
                                            @foreach ($eventDetails['event_schedule'] as $schedule)
                                                @php
                                                    // Calculate the earliest start time and the latest end time
                                                    $scheduleStartTime = strtotime($schedule['start_time']);
                                                    $scheduleEndTime = strtotime($schedule['end_time']);

                                                    if (
                                                        is_null($overallStartTime) ||
                                                        $scheduleStartTime < $overallStartTime
                                                    ) {
                                                        $overallStartTime = $scheduleStartTime;
                                                    }

                                                    if (
                                                        is_null($overallEndTime) ||
                                                        $scheduleEndTime > $overallEndTime
                                                    ) {
                                                        $overallEndTime = $scheduleEndTime;
                                                    }
                                                @endphp
                                            @endforeach
                                            {{-- {{ dd($eventDetails['event_schedule']) }} --}}
                                            <div class="schedule-wrp cmn-card">
                                                <h4 class="title">Schedule</h4>
                                                <span class="timing"> {{ date('h:i A', $overallStartTime) }} -
                                                    {{ date('h:i A', $overallEndTime) }}</span>
                                                <span class="shedule-img">
                                                    <img src="{{ asset('assets/front/img/shedule-img.svg') }}"
                                                        alt="schedule">
                                                </span>
                                                <div>
                                                    @foreach ($eventDetails['event_schedule'] as $schedule)
                                                        @php
                                                            if (
                                                                empty($schedule['start_time']) &&
                                                                empty($schedule['end_time'])
                                                            ) {
                                                                continue; // Skip this iteration if times are missing
                                                            }
                                                            $i = 0;
                                                            $colorClass = $series[$colorIndex % count($series)];
                                                            $colorIndex++;
                                                            $startTime = \Carbon\Carbon::parse($schedule['start_time']);
                                                            $endTime = \Carbon\Carbon::parse($schedule['end_time']);
                                                            $duration = $startTime->diffInHours($endTime) . 'h';
                                                        @endphp
                                                        <div class="shedule-manage-timing">
                                                            <div class="shedule-timing">
                                                                <h6>{{ $schedule['start_time'] }}</h6>
                                                            </div>
                                                            <div class="shedule-box {{ $colorClass }}">
                                                                <div class="shedule-box-left">
                                                                    <h6>{{ $schedule['activity_title'] }}</h6>
                                                                    <span>{{ $schedule['start_time'] }}
                                                                        @if (!empty($schedule['start_time']))
                                                                          @endif
                                                                            @if (!empty($schedule['end_time']))
                                                                            -
                                                                            {{ $schedule['end_time'] }}
                                                                        @endif
                                                                    </span>
                                                                </div>
                                                                <span class="hrs ms-auto">{{ $duration }}</span>
                                                            </div>
                                                            <img src="{{ asset('assets/front/img/timing-line.svg') }}"
                                                                alt="timing">
                                                        </div>
                                                    @endforeach
                                                </div>

                                            </div>
                                        @endif

                                        @if (!empty($eventDetails['gift_registry']) && is_array($eventDetails['gift_registry']))
                                            <div class="gift-register cmn-card">

                                                <h4 class="title">Sarah’s Gift Registries</h4>
                                                <span>Buy them the gift of their choice.</span>

                                                <div class="play-store">
                                                    @foreach ($eventDetails['gift_registry'] as $gift)
                                                        @if (str_contains(strtolower($gift['registry_recipient_name']), 'target'))
                                                            <a href="{{ $gift['registry_link'] }}"
                                                                class="play-store-btn target-btn" target="_blank">
                                                                <img src="{{ asset('assets/front/img/target.png') }}"
                                                                    alt="target">
                                                                <h6>Target</h6>
                                                            </a>
                                                        @elseif (str_contains(strtolower($gift['registry_recipient_name']), 'amzon'))
                                                            <a href="{{ $gift['registry_link'] }}"
                                                                class="play-store-btn amazon-btn" target="_blank">
                                                                <img src="{{ asset('assets/front/img/amazon.png') }}"
                                                                    alt="amazon">
                                                                {{-- <h6>Amazon</h6> --}}
                                                            </a>
                                                        @else
                                                            <a href="{{ $gift['registry_link'] }}"
                                                                class="play-store-btn other-btn" target="_blank">
                                                                <h6>{{ $gift['registry_recipient_name'] }}</h6>
                                                            </a>
                                                        @endif
                                                    @endforeach
                                                </div>


                                            </div>
                                        @endif

                                    </div>
                                </div>

                            </div>
                            <!-- ===tab-content-end=== -->

                        </div>
                        <!-- ===event-center-tabs-main-end=== -->
                    </div>
                </div>
                <div class="col-xl-3 col-lg-0">
                    <x-event_wall.wall_right_menu :eventInfo="$eventInfo" :event="$event" :login_user_id="$login_user_id" />
                </div>
            </div>
        </div>




        <!-- ===modals=== -->

        <!-- Modal -->
        <div class="modal fade create-post-modal all-events-filtermodal" id="main-center-modal-filter" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="all-events-filter-wrp">
                            <form action="">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault1">
                                    <label class="form-check-label" for="flexCheckDefault1">
                                        All
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault2">
                                    <label class="form-check-label" for="flexCheckDefault2">
                                        Host Updates/Posts
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault3">
                                    <label class="form-check-label" for="flexCheckDefault3">
                                        Video Uploads
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                        Photo Uploads
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                        Polls
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                        Comments
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" value=""
                                        id="flexCheckDefault4">
                                    <label class="form-check-label" for="flexCheckDefault4">
                                        RSVP ‘s
                                    </label>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cmn-btn reset-btn">Reset</button>
                        <button type="button" class="cmn-btn">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade create-post-modal all-events-filtermodal" id="reaction-modal" tabindex="-1"
            aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reactions</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="reactions-info-main event-center-tabs-main">
                            <!-- ===tabs=== -->
                            <nav>
                                <div class="nav nav-tabs reaction-nav-tabs" id="nav-tab" role="tablist">
                                    <button class="nav-link active" id="nav-all-reaction-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-all-reaction" type="button" role="tab"
                                        aria-controls="nav-all-reaction" aria-selected="true">
                                        All 106
                                    </button>
                                    <button class="nav-link" id="nav-heart-reaction-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-heart-reaction" type="button" role="tab"
                                        aria-controls="nav-heart-reaction" aria-selected="false" tabindex="-1">
                                        <img src="{{ asset('assets/front/img/heart-emoji.png') }}" alt=""> 50
                                    </button>
                                    <button class="nav-link" id="nav-thumb-reaction-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-thumb-reaction" type="button" role="tab"
                                        aria-controls="nav-thumb-reaction" aria-selected="false" tabindex="-1">
                                        <img src="{{ asset('assets/front/img/thumb-icon.png') }}" alt=""> 50
                                    </button>
                                    <button class="nav-link" id="nav-smily-reaction-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-smily-reaction" type="button" role="tab"
                                        aria-controls="nav-smily-reaction" aria-selected="false" tabindex="-1">
                                        <img src="{{ asset('assets/front/img/smily-emoji.png') }}" alt=""> 50
                                    </button>
                                    <button class="nav-link" id="nav-eye-heart-reaction-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-eye-heart-reaction" type="button" role="tab"
                                        aria-controls="nav-eye-heart-reaction" aria-selected="false" tabindex="-1">
                                        <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}" alt=""> 50
                                    </button>
                                    <button class="nav-link" id="nav-clap-reaction-tab" data-bs-toggle="tab"
                                        data-bs-target="#nav-clap-reaction" type="button" role="tab"
                                        aria-controls="nav-clap-reaction" aria-selected="false" tabindex="-1">
                                        <img src="{{ asset('assets/front/img/clap-icon.png') }}" alt=""> 50
                                    </button>
                                </div>
                            </nav>
                            <!-- ===tabs=== -->

                            <!-- ===Tab-content=== -->
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade active show" id="nav-all-reaction" role="tabpanel"
                                    aria-labelledby="nav-all-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-heart-reaction" role="tabpanel"
                                    aria-labelledby="nav-heart-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-thumb-reaction" role="tabpanel"
                                    aria-labelledby="nav-thumb-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-smily-reaction" role="tabpanel"
                                    aria-labelledby="nav-smily-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-eye-heart-reaction" role="tabpanel"
                                    aria-labelledby="nav-eye-heart-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>


                                <div class="tab-pane fade" id="nav-clap-reaction" role="tabpanel"
                                    aria-labelledby="nav-clap-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                        alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                            <!-- ===Tab-content=== -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cmn-btn reset-btn">Reset</button>
                        <button type="button" class="cmn-btn">Apply</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- ========= edit-rsvp ======== -->


        <!-- ========= Add-guest ======== -->
        <div class="modal fade cmn-modal" id="addguest" tabindex="-1" aria-labelledby="addguestLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addguestLabel">Add Guests</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body guest-tab">
                        <ul class="nav nav-tabs" id="myTab" role="tablist">
                            <li class="nav-item" role="presentation">
                                <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                    data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                    aria-selected="true">Yestive Contacts</button>
                            </li>
                            <li class="nav-item" role="presentation">
                                <button class="nav-link" id="profile-tab" data-bs-toggle="tab" data-bs-target="#profile"
                                    type="button" role="tab" aria-controls="profile" aria-selected="false">Phone
                                    Contacts</button>
                            </li>
                        </ul>
                        <div class="tab-content" id="myTabContent">
                            <div class="tab-pane fade show active" id="home" role="tabpanel"
                                aria-labelledby="home-tab">
                                <div class="guest-users-wrp">
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Silvia Alegra</h6>
                                    </div>
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Emery Vaccaro</h6>
                                    </div>
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Alena Geidt</h6>
                                    </div>
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                                    xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                                        stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Gerald Vincent</h6>
                                    </div>
                                    <a href="#" class="guest-user d-block">
                                        <div class="guest-user-img guest-total">
                                            <span class="number">10</span>
                                            <span class="content">Total</span>
                                        </div>
                                        <h6>Sell all</h6>
                                    </a>
                                </div>
                                <div class="position-relative">
                                    <input type="search" placeholder="Search name" class="form-control">
                                    <span class="input-search">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="guest-user-list-wrp invite-contact-wrp">
                                    <div class="invite-contact">
                                        <a href="#" class="invite-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="invite-img">
                                        </a>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="#" class="invite-user-name" data-bs-toggle="modal"
                                                    data-bs-target="#editguest">Silvia
                                                    Alegra</a>
                                            </div>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="invite-mail-data faild-content">
                                                    <div class="d-flex align-items-center">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <h6>silvia@gmail.com</h6>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <input class="form-check-input failed-checkout" type="checkbox"
                                                        value="" checked>
                                                </div>
                                            </div>
                                            <div class="invite-call-data mt-1">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                            stroke="#0F172A" stroke-miterlimit="10" />
                                                    </svg>
                                                    <h6>1-800-5587</h6>
                                                </div>
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invite-contact">
                                        <a href="#" class="invite-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="invite-img">
                                        </a>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="#" class="invite-user-name">Silvia Alegra</a>
                                            </div>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="invite-mail-data faild-content">
                                                    <div class="d-flex align-items-center">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <h6>silvia@gmail.com</h6>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <input class="form-check-input failed-checkout" type="checkbox"
                                                        value="" checked>
                                                </div>
                                            </div>
                                            <div class="invite-call-data mt-1">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                            stroke="#0F172A" stroke-miterlimit="10" />
                                                    </svg>
                                                    <h6>1-800-5587</h6>
                                                </div>
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invite-contact">
                                        <a href="#" class="invite-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="invite-img">
                                        </a>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="#" class="invite-user-name">Silvia Alegra</a>
                                            </div>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="invite-mail-data faild-content">
                                                    <div class="d-flex align-items-center">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <h6>silvia@gmail.com</h6>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <input class="form-check-input failed-checkout" type="checkbox"
                                                        value="" checked>
                                                </div>
                                            </div>
                                            <div class="invite-call-data mt-1">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                            stroke="#0F172A" stroke-miterlimit="10" />
                                                    </svg>
                                                    <h6>1-800-5587</h6>
                                                </div>
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                                <div class="guest-users-wrp">
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Silvia Alegra</h6>
                                    </div>
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Emery Vaccaro</h6>
                                    </div>
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Alena Geidt</h6>
                                    </div>
                                    <div class="guest-user">
                                        <div class="guest-user-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="guest-img">
                                            <a href="#" class="close">
                                                <svg width="19" height="18" viewBox="0 0 19 18"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" fill="#F73C71" />
                                                    <rect x="1.20312" y="1" width="16" height="16"
                                                        rx="8" stroke="white" stroke-width="2" />
                                                    <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                    <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </a>
                                        </div>
                                        <h6>Gerald Vincent</h6>
                                    </div>
                                    <a href="#" class="guest-user d-block">
                                        <div class="guest-user-img guest-total">
                                            <span class="number">10</span>
                                            <span class="content">Total</span>
                                        </div>
                                        <h6>Sell all</h6>
                                    </a>
                                </div>
                                <div class="position-relative">
                                    <input type="search" placeholder="Search name" class="form-control">
                                    <span class="input-search">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </div>
                                <div class="guest-user-list-wrp invite-contact-wrp">
                                    <div class="invite-contact">
                                        <a href="#" class="invite-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="invite-img">
                                        </a>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="#" class="invite-user-name">Silvia Alegra</a>
                                            </div>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="invite-mail-data faild-content">
                                                    <div class="d-flex align-items-center">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <h6>silvia@gmail.com</h6>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <input class="form-check-input failed-checkout" type="checkbox"
                                                        value="" checked>
                                                </div>
                                            </div>
                                            <div class="invite-call-data mt-1">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                            stroke="#0F172A" stroke-miterlimit="10" />
                                                    </svg>
                                                    <h6>1-800-5587</h6>
                                                </div>
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invite-contact">
                                        <a href="#" class="invite-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="invite-img">
                                        </a>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="#" class="invite-user-name">Silvia Alegra</a>
                                            </div>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="invite-mail-data faild-content">
                                                    <div class="d-flex align-items-center">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <h6>silvia@gmail.com</h6>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <input class="form-check-input failed-checkout" type="checkbox"
                                                        value="" checked>
                                                </div>
                                            </div>
                                            <div class="invite-call-data mt-1">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                            stroke="#0F172A" stroke-miterlimit="10" />
                                                    </svg>
                                                    <h6>1-800-5587</h6>
                                                </div>
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                    <div class="invite-contact">
                                        <a href="#" class="invite-img">
                                            <img src="{{ asset('assets/front/img/event-story-img-1.png') }}"
                                                alt="invite-img">
                                        </a>
                                        <div class="w-100">
                                            <div class="d-flex justify-content-between align-items-center">
                                                <a href="#" class="invite-user-name">Silvia Alegra</a>
                                            </div>
                                            <div class="d-flex align-items-center mt-1">
                                                <div class="invite-mail-data faild-content">
                                                    <div class="d-flex align-items-center">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                                stroke="black" stroke-miterlimit="10"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                        <h6>silvia@gmail.com</h6>
                                                    </div>
                                                </div>
                                                <div class="ms-auto">
                                                    <input class="form-check-input failed-checkout" type="checkbox"
                                                        value="" checked>
                                                </div>
                                            </div>
                                            <div class="invite-call-data mt-1">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                            stroke="#0F172A" stroke-miterlimit="10" />
                                                    </svg>
                                                    <h6>1-800-5587</h6>
                                                </div>
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer rsvp-button-wrp">
                        <button type="button" class="btn btn-secondary success-btn"
                            data-bs-dismiss="modal">Re-send</button>
                    </div>
                </div>
            </div>
        </div>
        <!-- about rsvp Modal -->
        <div class="modal fade cmn-modal about-rsvp" id="aboutrsvp" tabindex="-1" aria-labelledby="aboutrsvpLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <div class="d-flex align-items-center">
                            <img src="{{ $eventDetails['user_profile'] }}" alt="rs-img" class="about-rs-img">
                            <div>
                                <h4 class="modal-title" id="aboutrsvpLabel">{{ $eventDetails['event_name'] }}</h4>
                                <span>Hosted by: <span>{{ $eventDetails['hosted_by'] }}</span></span>
                            </div>
                        </div>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="host-detail">
                            <h5>Event Details</h5>
                            <ul>
                                <li>RSVP By Sept 20</li>
                                <li>+1 (Limit 5)</li>
                                <li>Adult Only Event</li>
                                <li>Potluck Event</li>
                            </ul>
                        </div>
                        <div class="rsvp-custom-radio guest-rsvp-attend">
                            <h6>RSVP</h6>
                            <div class="rsvp-input-form">
                                <div class="input-form">
                                    <input type="radio" id="option3" name="foo" checked="">
                                    <label for="option3">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.0013 18.3332C14.5846 18.3332 18.3346 14.5832 18.3346 9.99984C18.3346 5.4165 14.5846 1.6665 10.0013 1.6665C5.41797 1.6665 1.66797 5.4165 1.66797 9.99984C1.66797 14.5832 5.41797 18.3332 10.0013 18.3332Z"
                                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M6.45703 9.99993L8.81536 12.3583L13.5404 7.6416" stroke="#94A3B8"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                        YES</label>
                                </div>
                                <div class="input-form">
                                    <input type="radio" id="option4" name="foo">
                                    <label for="option4"><svg class="me-2" width="21" height="20"
                                            viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z"
                                                stroke="#E03137" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round"></path>
                                            <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                            <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137"
                                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                            </path>
                                        </svg>NO</label>
                                </div>
                            </div>
                        </div>
                        <div class="rsvp-msgbox">
                            <h5>Message</h5>
                            <div class="input-form">
                                <textarea name="" id="" class="form-control inputText" id="Fname" name="Fname"
                                    required="">Sorry I can’t make it!  Next time!</textarea>
                                <label for="Fname" class="form-label input-field floating-label">Message with your
                                    RSVP</label>
                            </div>
                            <div class="d-flex align-items-center">
                                <span class="d-flex align-items-center">
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M7.9987 1.3335C4.32536 1.3335 1.33203 4.32683 1.33203 8.00016C1.33203 11.6735 4.32536 14.6668 7.9987 14.6668C11.672 14.6668 14.6654 11.6735 14.6654 8.00016C14.6654 4.32683 11.672 1.3335 7.9987 1.3335ZM7.4987 5.3335C7.4987 5.06016 7.72536 4.8335 7.9987 4.8335C8.27203 4.8335 8.4987 5.06016 8.4987 5.3335V8.66683C8.4987 8.94016 8.27203 9.16683 7.9987 9.16683C7.72536 9.16683 7.4987 8.94016 7.4987 8.66683V5.3335ZM8.61203 10.9202C8.5787 11.0068 8.53203 11.0735 8.47203 11.1402C8.40536 11.2002 8.33203 11.2468 8.25203 11.2802C8.17203 11.3135 8.08536 11.3335 7.9987 11.3335C7.91203 11.3335 7.82536 11.3135 7.74536 11.2802C7.66536 11.2468 7.59203 11.2002 7.52536 11.1402C7.46536 11.0735 7.4187 11.0068 7.38536 10.9202C7.35203 10.8402 7.33203 10.7535 7.33203 10.6668C7.33203 10.5802 7.35203 10.4935 7.38536 10.4135C7.4187 10.3335 7.46536 10.2602 7.52536 10.1935C7.59203 10.1335 7.66536 10.0868 7.74536 10.0535C7.90536 9.98683 8.09203 9.98683 8.25203 10.0535C8.33203 10.0868 8.40536 10.1335 8.47203 10.1935C8.53203 10.2602 8.5787 10.3335 8.61203 10.4135C8.64536 10.4935 8.66536 10.5802 8.66536 10.6668C8.66536 10.7535 8.64536 10.8402 8.61203 10.9202Z"
                                            fill="#E2E8F0" />
                                    </svg></span>
                                <h6>This message will be visible to all guests.</h6>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Send</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- about success rsvp Modal -->
        <div class="modal fade cmn-modal about-rsvp" id="aboutsuccess" tabindex="-1"
            aria-labelledby="aboutsuccessLabel" aria-hidden="true">

            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content">

                    <form action="{{ route('event.sentRsvpData') }}" method="POST">
                        @csrf
                        <div class="modal-header">
                            <div class="d-flex align-items-center">
                                <img src="{{ $eventDetails['user_profile'] }}" alt="rs-img" class="about-rs-img">
                                <div>
                                    <h4 class="modal-title" id="aboutsuccessLabel">{{ $eventDetails['event_name'] }}
                                    </h4>
                                    <span>Hosted by: <span>{{ $eventDetails['hosted_by'] }}</span></span>
                                </div>
                            </div>
                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <div class="host-detail">
                                <h5>Event Details</h5>
                                <ul>
                                    @if (!empty($eventDetails['rsvp_by']))
                                        <li>RSVP By:
                                            {{ \Carbon\Carbon::parse($eventDetails['rsvp_by'])->format('F d, Y') }}
                                        </li>
                                    @endif
                                    @if ($eventDetails['podluck'] == 1)
                                        <li>Potluck Event</li>
                                    @endif
                                    @if ($eventDetails['adult_only_party'] == 1)
                                        <li>Adults Only</li>
                                    @endif
                                    @if (!empty($eventDetails['end_date']) && $eventDetails['event_date'] != $eventDetails['end_date'])
                                        <li>Multiple Day Event</li>
                                    @endif
                                    @if (!empty($eventDetails['co_hosts']))
                                        <li>Co-Host</li>
                                    @endif
                                    @if (!empty($eventDetails['gift_registry']))
                                        <li>Gift Registry</li>
                                    @endif
                                    @if (!empty($eventDetails['event_schedule']))
                                        <li>Event has schedule</li>
                                    @endif
                                    @if (!empty($eventDetails['allow_limit']))
                                        <li>Can Bring Gursts ({{ $eventDetails['allow_limit'] }})</li>
                                    @endif
                                </ul>
                            </div>
                            <input type ="hidden" id="event_id" name="event_id"
                                value="{{ $eventDetails['id'] }}">
                            <div class="rsvp-custom-radio guest-rsvp-attend">
                                <h6>RSVP</h6>

                                <div class="rsvp-input-form">
                                    <div class="input-form">
                                        <input type="radio" id="option5" name="rsvp_status" value="1"
                                            {{ isset($rsvpSent['rsvp_status']) && $rsvpSent['rsvp_status'] == 1 ? 'checked' : '' }}>
                                        <label for="option5">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.0013 18.3346C14.5846 18.3346 18.3346 14.5846 18.3346 10.0013C18.3346 5.41797 14.5846 1.66797 10.0013 1.66797C5.41797 1.66797 1.66797 5.41797 1.66797 10.0013C1.66797 14.5846 5.41797 18.3346 10.0013 18.3346Z"
                                                    stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M6.45703 9.99896L8.81536 12.3573L13.5404 7.64062"
                                                    stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            YES</label>
                                    </div>
                                    <div class="input-form rsvp-no-checkbox">
                                        <input type="radio" id="option6" name="rsvp_status" value="0"
                                            {{ isset($rsvpSent['rsvp_status']) && $rsvpSent['rsvp_status'] == 0 ? 'checked' : '' }}>
                                        <label for="option6"><svg class="me-2" width="21" height="20"
                                                viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z"
                                                    stroke="#E03137" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round"></path>
                                                <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137"
                                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                            </svg>NO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="rsvp-guest">
                                <h5>Guests</h5>
                                <div class="rsvp-guest-count">
                                    <div>
                                        <h6>Adults</h6>
                                        <div class="qty-container ms-auto">
                                            <button class="btn-minus rsvp_status_btn" type="button"><i
                                                    class="fa fa-minus"></i></button>
                                            <input type="number" id="rsvp_status_adults" name="adults"
                                                value="{{ $rsvpSent['adults'] ?? 0 }}" class="input-qty">
                                            <button class="btn-plus rsvp_plus" type="button"><i
                                                    class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                    <div>
                                        <h6>Kids</h6>
                                        <div class="qty-container ms-auto">
                                            <button class="btn-minus rsvp_status_btn" type="button"><i
                                                    class="fa fa-minus"></i></button>
                                            <input type="number" id="rsvp_status_kids" name="kids"
                                                value="{{ $rsvpSent['kids'] ?? 0 }}" class="input-qty">
                                            <button class="btn-plus rsvp_plus" type="button"><i
                                                    class="fa fa-plus"></i></button>
                                        </div>
                                    </div>
                                </div>
                                <div id="error-message"></div>

                            </div>

                            <div class="rsvp-msgbox">
                                <h5>Message</h5>
                                <div class="input-form">
                                    <textarea name="message_to_host" id="" class="form-control inputText" id="Fname" name="Fname"
                                        required=""> {{ $rsvpSent['message_to_host'] ?? '' }}</textarea>
                                    <label for="Fname" class="form-label input-field floating-label">Message with
                                        your
                                        RSVP</label>
                                </div>
                                <div class="d-flex align-items-center">
                                    <span class="d-flex align-items-center">
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M7.9987 1.3335C4.32536 1.3335 1.33203 4.32683 1.33203 8.00016C1.33203 11.6735 4.32536 14.6668 7.9987 14.6668C11.672 14.6668 14.6654 11.6735 14.6654 8.00016C14.6654 4.32683 11.672 1.3335 7.9987 1.3335ZM7.4987 5.3335C7.4987 5.06016 7.72536 4.8335 7.9987 4.8335C8.27203 4.8335 8.4987 5.06016 8.4987 5.3335V8.66683C8.4987 8.94016 8.27203 9.16683 7.9987 9.16683C7.72536 9.16683 7.4987 8.94016 7.4987 8.66683V5.3335ZM8.61203 10.9202C8.5787 11.0068 8.53203 11.0735 8.47203 11.1402C8.40536 11.2002 8.33203 11.2468 8.25203 11.2802C8.17203 11.3135 8.08536 11.3335 7.9987 11.3335C7.91203 11.3335 7.82536 11.3135 7.74536 11.2802C7.66536 11.2468 7.59203 11.2002 7.52536 11.1402C7.46536 11.0735 7.4187 11.0068 7.38536 10.9202C7.35203 10.8402 7.33203 10.7535 7.33203 10.6668C7.33203 10.5802 7.35203 10.4935 7.38536 10.4135C7.4187 10.3335 7.46536 10.2602 7.52536 10.1935C7.59203 10.1335 7.66536 10.0868 7.74536 10.0535C7.90536 9.98683 8.09203 9.98683 8.25203 10.0535C8.33203 10.0868 8.40536 10.1335 8.47203 10.1935C8.53203 10.2602 8.5787 10.3335 8.61203 10.4135C8.64536 10.4935 8.66536 10.5802 8.66536 10.6668C8.66536 10.7535 8.64536 10.8402 8.61203 10.9202Z"
                                                fill="#E2E8F0" />
                                        </svg></span>
                                    <h6>This message will be visible to all guests.</h6>
                                </div>
                            </div>


                        </div>

                        <div class="modal-footer">
                            <button type="submit" class="btn btn-secondary" data-bs-dismiss="modal">Send</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>


    </main>
@endisset
<script>
    // Get event date and time from PHP
    const startdate = "{{ $startdate }}"; // '2025-01-30' format
    const starttime = "{{ $starttime }}"; // '9:30 PM' format

    // Convert start date and time into a timestamp
    const eventTimestamp = new Date(`${startdate} ${starttime}`).getTime();

    function updateCountdown() {
        const currentDate = new Date();
        const currentTime = currentDate.getTime(); // Use currentDate.getTime() to get current time in milliseconds

        const remainingTime = eventTimestamp - currentTime; // Subtract current time from event timestamp

        if (remainingTime > 0) {
            // Calculate days, hours, minutes, and seconds
            const days = Math.floor(remainingTime / (1000 * 60 * 60 * 24));
            const hours = Math.floor((remainingTime % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            const minutes = Math.floor((remainingTime % (1000 * 60 * 60)) / (1000 * 60));
            // const seconds = Math.floor((remainingTime % (1000 * 60)) / 1000);

            // Update HTML elements
            document.getElementById('countdownDays').innerText = days.toString().padStart(2, '0');
            document.getElementById('countdownHours').innerText = hours.toString().padStart(2, '0');
            document.getElementById('countdownMinutes').innerText = minutes.toString().padStart(2, '0');
            // document.getElementById('countdownSeconds').innerText = seconds.toString().padStart(2, '0');
        } else {
            // Event has passed, set everything to "00"
            document.getElementById('countdownDays').innerText = "00";
            document.getElementById('countdownHours').innerText = "00";
            document.getElementById('countdownMinutes').innerText = "00";
            // document.getElementById('countdownSeconds').innerText = "00";
        }
    }

    // Update countdown every second
    setInterval(updateCountdown, 1000);
    updateCountdown(); // Initial call to set the countdown immediately
</script>
