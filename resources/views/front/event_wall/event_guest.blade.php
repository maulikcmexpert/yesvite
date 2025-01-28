{{-- {{dd( $eventDetails  )}} --}}
<main class="new-main-content">

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
                                    Guest
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

                        <!-- ===tab-content-start=== -->
                        <div class="tab-content" id="nav-tabContent">
                            @php
                                $guest_active = '';
                                $guest_show = '';
                                if ($current_page == 'guest') {
                                    $guest_active = 'active';
                                    $guest_show = 'show';
                                }
                            @endphp
                            <div class="tab-pane fade {{ $guest_active }} {{ $guest_show }}" id="nav-guests"
                                role="tabpanel" aria-labelledby="nav-guests-tab">
                                <div class="guest-main-wrp">
                                    <div class="summer-progress cmn-card">
                                        <div class="attendence-data">
                                            @php
                                                // Sample guests array from the provided data
                                                $hostView = $eventInfo['host_view'];

                                                // Initialize totals
                                                $totalAdults = 0;
                                                $totalKids = 0;

                                                // Sum up adults and kids
                                                if ($hostView) {
                                                    $totalAdults += $hostView['adults'] ?? 0;
                                                    $totalKids += $hostView['kids'] ?? 0;
                                                }

                                                // Total attending
                                                $totalAttending = $totalAdults + $totalKids;
                                            @endphp
                                            <div class="d-flex align-items-center">
                                                <span class="d-flex align-items-center me-2">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M5.24984 1.16797C3.7215 1.16797 2.479 2.41047 2.479 3.9388C2.479 5.43797 3.6515 6.6513 5.17984 6.7038C5.2265 6.69797 5.27317 6.69797 5.30817 6.7038C5.31984 6.7038 5.32567 6.7038 5.33734 6.7038C5.34317 6.7038 5.34317 6.7038 5.349 6.7038C6.84234 6.6513 8.01484 5.43797 8.02067 3.9388C8.02067 2.41047 6.77817 1.16797 5.24984 1.16797Z"
                                                            fill="#0DAD5F" />
                                                        <path
                                                            d="M8.21356 8.25516C6.58606 7.17016 3.93189 7.17016 2.29272 8.25516C1.55189 8.75099 1.14355 9.42182 1.14355 10.1393C1.14355 10.8568 1.55189 11.5218 2.28689 12.0118C3.10355 12.5602 4.17689 12.8343 5.25022 12.8343C6.32356 12.8343 7.39689 12.5602 8.21356 12.0118C8.94856 11.516 9.35689 10.851 9.35689 10.1277C9.35106 9.41016 8.94856 8.74516 8.21356 8.25516Z"
                                                            fill="#0DAD5F" />
                                                        <path
                                                            d="M11.661 4.28254C11.7543 5.41421 10.9493 6.40587 9.83512 6.54004C9.82929 6.54004 9.82929 6.54004 9.82346 6.54004H9.80596C9.77096 6.54004 9.73596 6.54004 9.70679 6.5517C9.14096 6.58087 8.62179 6.40004 8.23096 6.06754C8.83179 5.53087 9.17596 4.72587 9.10596 3.85087C9.06512 3.37837 8.90179 2.94671 8.65679 2.57921C8.87846 2.46837 9.13512 2.39837 9.39762 2.37504C10.541 2.27587 11.5618 3.12754 11.661 4.28254Z"
                                                            fill="#0DAD5F" />
                                                        <path
                                                            d="M12.8273 9.67903C12.7806 10.2449 12.419 10.7349 11.8123 11.0674C11.229 11.3882 10.494 11.5399 9.76481 11.5224C10.1848 11.1432 10.4298 10.6707 10.4765 10.169C10.5348 9.4457 10.1906 8.75153 9.50231 8.19737C9.11148 7.8882 8.65648 7.6432 8.16064 7.46237C9.44981 7.08903 11.0715 7.33987 12.069 8.14487C12.6056 8.57653 12.8798 9.11903 12.8273 9.67903Z"
                                                            fill="#0DAD5F" />
                                                    </svg>
                                                </span>
                                                <h4> {{ $totalAttending }} Attending</h4>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <h5>{{ $totalAdults }} <span>Adults</span></h5>
                                                <h5>{{ $totalKids }} <span>Kids</span></h5>
                                            </div>
                                        </div>

                                        <div id="chartData" data-attending="{{ $hostView['attending'] }}"
                                            data-no-reply="{{ $hostView['pending'] }}"
                                            data-declined="{{ $hostView['not_attending'] }}"
                                            data-invitation_sent="{{ $hostView['total_invite'] }}">
                                        </div>
                                        <div id="chart1" class=""></div>

                                    </div>
                                    <div class="total-item-cat total-rate cmn-card">
                                        <div class="row">
                                            <div class="col-lg-6 col-md-6 col-sm-6 mb-sm-0 mb-3">
                                                <div class="item-cards">
                                                    <div class="item-value">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.44547 1.16699H4.55714C2.4338 1.16699 1.16797 2.43283 1.16797 4.55616V9.43866C1.16797 11.5678 2.4338 12.8337 4.55714 12.8337H9.43964C11.563 12.8337 12.8288 11.5678 12.8288 9.44449V4.55616C12.8346 2.43283 11.5688 1.16699 9.44547 1.16699ZM5.31547 9.85866C5.31547 10.022 5.18714 10.1503 5.0238 10.1503H3.3963C3.23297 10.1503 3.10464 10.022 3.10464 9.85866V7.16366C3.10464 6.79616 3.40214 6.49866 3.76964 6.49866H5.0238C5.18714 6.49866 5.31547 6.62699 5.31547 6.79033V9.85866ZM8.1038 9.85866C8.1038 10.022 7.97547 10.1503 7.81214 10.1503H6.18464C6.0213 10.1503 5.89297 10.022 5.89297 9.85866V4.51533C5.89297 4.14783 6.19047 3.85033 6.55797 3.85033H7.44464C7.81214 3.85033 8.10963 4.14783 8.10963 4.51533V9.85866H8.1038ZM10.898 9.85866C10.898 10.022 10.7696 10.1503 10.6063 10.1503H8.9788C8.81547 10.1503 8.68714 10.022 8.68714 9.85866V7.78783C8.68714 7.62449 8.81547 7.49616 8.9788 7.49616H10.233C10.6005 7.49616 10.898 7.79366 10.898 8.16116V9.85866Z"
                                                                fill="#F73C71" />
                                                        </svg>
                                                        <h6>Total RSVP Rate</h6>
                                                    </div>
                                                    <h3>{{ $eventInfo['host_view']['rsvp_rate_percent'] }}</h3>
                                                    <h5>{{ $eventInfo['host_view']['rsvp_rate'] }}/{{ $eventInfo['host_view']['total_invite'] }}
                                                    </h5>

                                                    <div class="d-flex align-items-center uptick">
                                                        <svg width="8" height="4" viewBox="0 0 8 4"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M7.5 3.875L3.75 0.125L0 3.875H7.5Z"
                                                                fill="#00C222" />
                                                        </svg>
                                                        <h4>{{ $eventInfo['host_view']['today_upstick'] }}</h4>
                                                        <span>Uptick Today</span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-lg-6 col-md-6 col-sm-6">
                                                <div class="item-cards">
                                                    <div class="item-value">
                                                        <svg width="14" height="14" viewBox="0 0 14 14"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.0013 1.16699C3.78714 1.16699 1.16797 3.78616 1.16797 7.00033C1.16797 10.2145 3.78714 12.8337 7.0013 12.8337C10.2155 12.8337 12.8346 10.2145 12.8346 7.00033C12.8346 3.78616 10.2155 1.16699 7.0013 1.16699ZM9.33463 7.43783H7.4388V9.33366C7.4388 9.57283 7.24047 9.77116 7.0013 9.77116C6.76214 9.77116 6.5638 9.57283 6.5638 9.33366V7.43783H4.66797C4.4288 7.43783 4.23047 7.23949 4.23047 7.00033C4.23047 6.76116 4.4288 6.56283 4.66797 6.56283H6.5638V4.66699C6.5638 4.42783 6.76214 4.22949 7.0013 4.22949C7.24047 4.22949 7.4388 4.42783 7.4388 4.66699V6.56283H9.33463C9.5738 6.56283 9.77213 6.76116 9.77213 7.00033C9.77213 7.23949 9.5738 7.43783 9.33463 7.43783Z"
                                                                fill="#F73C71" />
                                                        </svg>
                                                        <h6>Invite view rate</h6>
                                                    </div>
                                                    <h3>{{ $eventInfo['host_view']['invite_view_percent'] }}</h3>
                                                    <h5>{{ $eventInfo['host_view']['invite_view_rate'] }}/{{ $eventInfo['host_view']['total_invite'] }}
                                                    </h5>
                                                    <div class="d-flex align-items-center uptick">
                                                        <svg width="8" height="4" viewBox="0 0 8 4"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path d="M7.5 3.875L3.75 0.125L0 3.875H7.5Z"
                                                                fill="#00C222" />
                                                        </svg>
                                                        <h4>{{ $eventInfo['host_view']['today_invite_view_percent'] }}
                                                        </h4>
                                                        <span>New views today</span>
                                                    </div>
                                                </div>
                                            </div>
                                            {{-- <div class="col-lg-12">
                                                <div class="failed-invite">
                                                    <a type="button" class="faild-data" data-bs-toggle="modal"
                                                        data-bs-target="#failed">
                                                        <div class="d-flex align-items-center failed-data-left">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M18.134 13.267L12.8007 3.66699C12.084 2.37533 11.0924 1.66699 10.0007 1.66699C8.90905 1.66699 7.91738 2.37533 7.20071 3.66699L1.86738 13.267C1.19238 14.492 1.11738 15.667 1.65905 16.592C2.20071 17.517 3.26738 18.0253 4.66738 18.0253H15.334C16.734 18.0253 17.8007 17.517 18.3424 16.592C18.884 15.667 18.809 14.4837 18.134 13.267ZM9.37571 7.50033C9.37571 7.15866 9.65905 6.87533 10.0007 6.87533C10.3424 6.87533 10.6257 7.15866 10.6257 7.50033V11.667C10.6257 12.0087 10.3424 12.292 10.0007 12.292C9.65905 12.292 9.37571 12.0087 9.37571 11.667V7.50033ZM10.5924 14.7587C10.5507 14.792 10.509 14.8253 10.4674 14.8587C10.4174 14.892 10.3674 14.917 10.3174 14.9337C10.2674 14.9587 10.2174 14.9753 10.159 14.9837C10.109 14.992 10.0507 15.0003 10.0007 15.0003C9.95071 15.0003 9.89238 14.992 9.83405 14.9837C9.78405 14.9753 9.73405 14.9587 9.68405 14.9337C9.63405 14.917 9.58405 14.892 9.53405 14.8587C9.49238 14.8253 9.45071 14.792 9.40905 14.7587C9.25905 14.6003 9.16738 14.3837 9.16738 14.167C9.16738 13.9503 9.25905 13.7337 9.40905 13.5753C9.45071 13.542 9.49238 13.5087 9.53405 13.4753C9.58405 13.442 9.63405 13.417 9.68405 13.4003C9.73405 13.3753 9.78405 13.3587 9.83405 13.3503C9.94238 13.3253 10.059 13.3253 10.159 13.3503C10.2174 13.3587 10.2674 13.3753 10.3174 13.4003C10.3674 13.417 10.4174 13.442 10.4674 13.4753C10.509 13.5087 10.5507 13.542 10.5924 13.5753C10.7424 13.7337 10.834 13.9503 10.834 14.167C10.834 14.3837 10.7424 14.6003 10.5924 14.7587Z"
                                                                    fill="#E03137" />
                                                            </svg>
                                                            <h5>Failed Invites (5)</h5>
                                                        </div>
                                                        <span class="arrow d-flex align-items-center">
                                                            <svg width="15" height="16" viewBox="0 0 9 16"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M1.4248 14.5984L6.85814 9.1651C7.49981 8.52344 7.49981 7.47344 6.85814 6.83177L1.4248 1.39844"
                                                                    stroke="#CBD5E1" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                    </a>
                                                </div>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#addguest">Add Guest</button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#cancelevent">Cancel
                                                    event</button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#eventcanceled">Event
                                                    canceled</button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#eventcopied">Event
                                                    copied</button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#blockempty">Block </button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#blockaccount">Block
                                                    accouts</button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#submitreport">Submit
                                                    Report</button>
                                                <button class="add-guest" data-bs-toggle="modal"
                                                    data-bs-target="#uploadcsv">upload
                                                    csv</button>
                                            </div> --}}
                                        </div>
                                    </div>
                                    <div class="guest-user-list cmn-card">
                                        <div>
                                            <h5 class="heading">Guest List</h5>

                                        </div>
                                        <div>
                                            {{-- {{ dd($eventDetails) }} --}}
                                            @php
                                                $host_id = $eventDetails['host_id'];
                                                $userid = $login_user_id;
                                                //   dd($host_id);if ($guestString !== null) {
                                                $guestArray = $eventDetails['event_detail']['guests'] ?? null;
                                                $totalAdults = 0;
                                                $totalKids = 0;
// dd( $guestArray);

                                                if ($guestArray) {
                                                    foreach ($guestArray as $guest) {
                                                        $totalAdults += $guest['adults'] ?? 0;
                                                        $totalKids += $guest['kids'] ?? 0;; // Accessing related user data
                                                    }
                                                } else {
                                                    echo 'No guests found.';
                                                }

                                                //  dd($guests,$host_id);
                                                // Initialize totals

                                                // Total attending
                                                $totalAttending = $totalAdults + $totalKids;
                                            @endphp
@if (!empty($guestArray))
                                            @foreach($guestArray as $guest)
                                       {{-- {{     dd($guest['id'])}} --}}
                                                @if (isset($guest['user']))
                                                    @php
                                                        $user = $guest['user']; // Fetch user array
                                                        // dd($user['id']);
                                                        $isDisabled =
                                                            $eventDetails['host_id'] == $user['id'] ? 'd-none' : '';
                                                    @endphp
                                                    <div class="guest-user-box {{ $isDisabled }}" data-guest-id="{{ $guest['id'] }}">
                                                        <div class="guest-list-data">
                                                            <a href="#" class="guest-img">
                                                                @if ($user['profile'] != '')
                                                                <img src="{{ $user['profile'] }}"
                                                                    alt="guest-img">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $firstInitial = isset($user['firstname'][0])
                                                                        ? strtoupper($user['firstname'][0])
                                                                        : '';
                                                                    $secondInitial = isset($user['lastname'][0])
                                                                        ? strtoupper($user['lastname'][0])
                                                                        : '';
                                                                    $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                                                    $fontColor = 'fontcolor' . strtoupper($firstInitial);
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif

<input type="hidden" id="eventID" value="{{$guest['event_id']}}">
<input type="hidden" id="user_id" value="{{$guest['user_id']}}">

                                                            </a>
                                                            <div class="d-flex flex-column">
                                                                <a href="#"
                                                                    class="guest-name">{{ $user['firstname'] }}
                                                                    {{ $user['lastname'] }}</a>
                                                                <span class="guest-email">{{ $user['email'] }}</span>
                                                            </div>
                                                            <div class="d-flex align-items-center ms-auto">
                                                                @php
                                                                    $isDisabled =
                                                                        $eventDetails['host_id'] != $login_user_id
                                                                            ? 'd-none'
                                                                            : '';
                                                                    // dd($login_user_id);
                                                                @endphp
                                                                <button class="edit-btn {{ $isDisabled }}"
                                                                    data-bs-toggle="modal" data-bs-target="#editrsvp"
                                                                    data-guest-id="{{ $guest['id'] }}">
                                                                    <svg width="20" height="20"
                                                                        viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M9.16797 1.66699H7.5013C3.33464 1.66699 1.66797 3.33366 1.66797 7.50033V12.5003C1.66797 16.667 3.33464 18.3337 7.5013 18.3337H12.5013C16.668 18.3337 18.3346 16.667 18.3346 12.5003V10.8337"
                                                                            stroke="#94A3B8" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path
                                                                            d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z"
                                                                            stroke="#94A3B8" stroke-width="1.5"
                                                                            stroke-miterlimit="10"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path
                                                                            d="M12.4258 3.45801C12.9841 5.44967 14.5424 7.00801 16.5424 7.57467"
                                                                            stroke="#94A3B8" stroke-width="1.5"
                                                                            stroke-miterlimit="10"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </button>
                                                                <a href="#" class="msg-btn">
                                                                    <svg width="24" height="24"
                                                                        viewBox="0 0 24 24" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M17 20.5H7C4 20.5 2 19 2 15.5V8.5C2 5 4 3.5 7 3.5H17C20 3.5 22 5 22 8.5V15.5C22 19 20 20.5 17 20.5Z"
                                                                            stroke="#F73C71" stroke-width="1.5"
                                                                            stroke-miterlimit="10"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path
                                                                            d="M17 9L13.87 11.5C12.84 12.32 11.15 12.32 10.12 11.5L7 9"
                                                                            stroke="#F73C71" stroke-width="1.5"
                                                                            stroke-miterlimit="10"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </a>
                                                            </div>
                                                        </div>
                                                        <div class="sucess-rsvp-wrp">
                                                            <div class="d-flex align-items-center">
                                                                <h5 class="green d-flex align-items-center">
                                                                    <svg width="16" height="16"
                                                                        viewBox="0 0 16 16" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M11.3335 14.1654H4.66683C4.3935 14.1654 4.16683 13.9387 4.16683 13.6654C4.16683 13.392 4.3935 13.1654 4.66683 13.1654H11.3335C13.2402 13.1654 14.1668 12.2387 14.1668 10.332V5.66536C14.1668 3.7587 13.2402 2.83203 11.3335 2.83203H4.66683C2.76016 2.83203 1.8335 3.7587 1.8335 5.66536C1.8335 5.9387 1.60683 6.16536 1.3335 6.16536C1.06016 6.16536 0.833496 5.9387 0.833496 5.66536C0.833496 3.23203 2.2335 1.83203 4.66683 1.83203H11.3335C13.7668 1.83203 15.1668 3.23203 15.1668 5.66536V10.332C15.1668 12.7654 13.7668 14.1654 11.3335 14.1654Z"
                                                                            fill="#23AA26" />
                                                                        <path
                                                                            d="M7.99969 8.57998C7.43969 8.57998 6.87302 8.40665 6.43969 8.05331L4.35302 6.38665C4.13969 6.21331 4.09969 5.89998 4.27302 5.68665C4.44636 5.47331 4.75968 5.43332 4.97302 5.60665L7.05969 7.27332C7.56635 7.67998 8.42635 7.67998 8.93302 7.27332L11.0197 5.60665C11.233 5.43332 11.553 5.46665 11.7197 5.68665C11.893 5.89998 11.8597 6.21998 11.6397 6.38665L9.55301 8.05331C9.12635 8.40665 8.55969 8.57998 7.99969 8.57998Z"
                                                                            fill="#23AA26" />
                                                                        <path
                                                                            d="M5.3335 11.5H1.3335C1.06016 11.5 0.833496 11.2733 0.833496 11C0.833496 10.7267 1.06016 10.5 1.3335 10.5H5.3335C5.60683 10.5 5.8335 10.7267 5.8335 11C5.8335 11.2733 5.60683 11.5 5.3335 11.5Z"
                                                                            fill="#23AA26" />
                                                                        <path
                                                                            d="M3.3335 8.83203H1.3335C1.06016 8.83203 0.833496 8.60536 0.833496 8.33203C0.833496 8.0587 1.06016 7.83203 1.3335 7.83203H3.3335C3.60683 7.83203 3.8335 8.0587 3.8335 8.33203C3.8335 8.60536 3.60683 8.83203 3.3335 8.83203Z"
                                                                            fill="#23AA26" />
                                                                    </svg> Succesful
                                                                </h5>
                                                                <h5 class="ms-auto">Read</h5>
                                                            </div>
                                                        </div>

                                                        @if ($guest['rsvp_status'] == '1')
                                                            <div class="sucess-yes" data-guest-id="{{ $guest['id'] }}">
                                                                <h5 class="green">YES</h5>
                                                                <div class="sucesss-cat ms-auto">
                                                                    <svg width="15" height="15"
                                                                        viewBox="0 0 15 15" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                        <path
                                                                            d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                        <path
                                                                            d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                        <path
                                                                            d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z"
                                                                            fill="black" fill-opacity="0.2" />
                                                                    </svg>
                                                                    <h5 id="adults{{ $guest['id'] }}">
                                                                        {{ $guest['adults'] }}Adults
                                                                    </h5>
                                                                    <h5 id="kids{{ $guest['id'] }}">
                                                                        {{ $guest['kids'] }} Kids</h5>
                                                                </div>
                                                            </div>
                                                        @elseif ($guest['rsvp_status'] == '0')
                                                            <div class="sucess-no"  data-guest-id="{{ $guest['id'] }}">
                                                                <h5>NO</h5>
                                                            </div>
                                                        @elseif ($guest['rsvp_status'] == null)
                                                            <div class="no-reply"  data-guest-id="{{ $guest['id'] }}">
                                                                <h5>NO REPLY</h5>
                                                            </div>
                                                        @endif
                                                    </div>
                                                @endif
                                            @endforeach
                                            @endif


                                        </div>
                                        <div class="loader"></div>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <!-- ===tab-content-end=== -->

                    </div>
                    <!-- ===event-center-tabs-main-end=== -->
                </div>
            </div>
            <div class="col-xl-3 col-lg-0">
                <x-event_wall.wall_right_menu :eventInfo="$eventInfo" :login_user_id="$login_user_id" />
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
    {{-- {{      dd($eventDetails['event_detail'][2]);}} --}}
    <!-- ========= edit-rsvp ======== -->
    <div class="modal fade cmn-modal guest-edit-incress-modal" id="editrsvp" tabindex="-1" aria-labelledby="editrsvpLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">

                <div class="modal-header">
                    <h4 class="modal-title" id="editrsvpLabel">Edit RSVP Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">

                    <div class="guest-rsvp-head">
                        <div class="rsvp-img">
                            <img src="{{ asset('assets/front/img/rs-img.png') }}" alt="rs-img">
                        </div>
                        <h5></h5>
                    </div>
                    <div class="guest-rsvp-incres">
                        <h6>Guests</h6>
                        <div class="guest-edit-wrp">
                            <div class="guest-edit-box">
                                <p>Adults</p>
                                <div class="qty-container ms-auto">
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="adults" value="0"
                                        class="input-qty adult-count" />

                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="guest-edit-box">
                                <p>Kids</p>
                                <div class="qty-container ms-auto">
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="kids" value="0"
                                        class="input-qty kid-count" />
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="guest-rsvp-attend">
                        <h6>RSVP</h6>
                        <div class="input-form">
                            <input type="radio" id="option1" name="rsvp_status" class="rsvp_status_yes"
                                value="1" />
                            <label for="option1"><svg class="me-2" width="21" height="20"
                                    viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.5001 18.3334C15.0834 18.3334 18.8334 14.5834 18.8334 10.0001C18.8334 5.41675 15.0834 1.66675 10.5001 1.66675C5.91675 1.66675 2.16675 5.41675 2.16675 10.0001C2.16675 14.5834 5.91675 18.3334 10.5001 18.3334Z"
                                        stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M6.95825 9.99993L9.31659 12.3583L14.0416 7.6416" stroke="#23AA26"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>Attending</label>
                        </div>
                        <div class="input-form">
                            <input type="radio" id="option2" name="rsvp_status" value="0"
                                class="rsvp_status_no" />
                            <label for="option2"><svg class="me-2" width="21" height="20"
                                    viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z"
                                        stroke="#E03137" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>Not Attending</label>
                        </div>
                    </div>

                </div>
                <div class="modal-footer rsvp-button-wrp">
                    <button type="button" class="btn btn-secondary remove-btn" data-bs-dismiss="modal" data-event-id="{{$event}}" >Remove
                        Guest</button>
                    <button type="button" class="btn btn-secondary save-btn" data-bs-dismiss="modal">Update</button>

                </div>

            </div>
        </div>
    </div>



    <div class="modal fade cmn-modal about-rsvp" id="aboutsuccess" tabindex="-1"
        aria-labelledby="aboutsuccessLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <div class="d-flex align-items-center">
                        <img src="{{ asset('assets/front/img/birth-img.png') }}" alt="rs-img"
                            class="about-rs-img">
                        <div>
                            <h4 class="modal-title" id="aboutsuccessLabel">Aaron Loeb’s 5th Birthday</h4>
                            <span>Hosted by: <span>The Walton Family</span></span>
                        </div>
                    </div>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="rsvp-custom-radio guest-rsvp-attend">
                        <h6>RSVP</h6>
                        <div class="rsvp-input-form">
                            <div class="input-form">
                                <input type="radio" id="option5" name="foo">
                                <label for="option5">
                                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.0013 18.3346C14.5846 18.3346 18.3346 14.5846 18.3346 10.0013C18.3346 5.41797 14.5846 1.66797 10.0013 1.66797C5.41797 1.66797 1.66797 5.41797 1.66797 10.0013C1.66797 14.5846 5.41797 18.3346 10.0013 18.3346Z"
                                            stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M6.45703 9.99896L8.81536 12.3573L13.5404 7.64062" stroke="#23AA26"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                    YES</label>
                            </div>
                            <div class="input-form rsvp-no-checkbox">
                                <input type="radio" id="option6" name="foo">
                                <label for="option6"><svg class="me-2" width="21" height="20"
                                        viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z"
                                            stroke="#E03137" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
                                        </path>
                                        <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round">
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
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty">
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div>
                                <h6>Kids</h6>
                                <div class="qty-container ms-auto">
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty">
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
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

    <!-- blockempty Modal -->
    <div class="modal fade cmn-modal" id="blockempty" tabindex="-1" aria-labelledby="blockemptyLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="blockemptyLabel">Blocked Accounts</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <span class="block-empty-img">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.9987 9.99935C12.2999 9.99935 14.1654 8.13387 14.1654 5.83268C14.1654 3.5315 12.2999 1.66602 9.9987 1.66602C7.69751 1.66602 5.83203 3.5315 5.83203 5.83268C5.83203 8.13387 7.69751 9.99935 9.9987 9.99935Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M2.83984 18.3333C2.83984 15.1083 6.04817 12.5 9.99817 12.5C10.7982 12.5 11.5732 12.6083 12.2982 12.8083"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M18.3346 14.9993C18.3346 15.266 18.3013 15.5243 18.2346 15.7743C18.1596 16.1077 18.0263 16.4327 17.8513 16.716C17.2763 17.6827 16.218 18.3327 15.0013 18.3327C14.143 18.3327 13.368 18.0077 12.7846 17.4743C12.5346 17.2577 12.318 16.9993 12.1513 16.716C11.843 16.216 11.668 15.6243 11.668 14.9993C11.668 14.0993 12.0263 13.2744 12.6096 12.6744C13.218 12.0494 14.068 11.666 15.0013 11.666C15.9846 11.666 16.8763 12.091 17.4763 12.7744C18.0096 13.366 18.3346 14.1493 18.3346 14.9993Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M15.8599 14.1172L14.1016 15.8755" stroke="#94A3B8" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M14.1172 14.1348L15.8839 15.8931" stroke="#94A3B8" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                    <h5>No blocked accounts found</h5>
                </div>
                <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Apply</button>
              </div> -->
            </div>
        </div>
    </div>

    <!-- block account Modal -->
    <div class="modal fade cmn-modal" id="blockaccount" tabindex="-1" aria-labelledby="blockaccountLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="blockaccountLabel">Blocked Accounts</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="block-acc-list-wrp">
                        <div class="block-acc-list">
                            <a href="#" class="block-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="block-img">
                            </a>
                            <div class="block-title">
                                <a href="#">Giana Baptista</a>
                                <span>giana@gmail.com</span>
                            </div>
                            <button class="unblock-btn">Unblock</button>
                        </div>
                        <div class="block-acc-list">
                            <a href="#" class="block-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="block-img">
                            </a>
                            <div class="block-title">
                                <a href="#">Kianna Lipshutz</a>
                                <span>kianna@gmail.com</span>
                            </div>
                            <button class="unblock-btn">Unblock</button>
                        </div>
                        <div class="block-acc-list">
                            <a href="#" class="block-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="block-img">
                            </a>
                            <div class="block-title">
                                <a href="#">Kianna Lipshutz</a>
                                <span>kianna@gmail.com</span>
                            </div>
                            <button class="unblock-btn">Unblock</button>
                        </div>
                        <div class="block-acc-list">
                            <a href="#" class="block-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="block-img">
                            </a>
                            <div class="block-title">
                                <a href="#">Kianna Lipshutz</a>
                                <span>kianna@gmail.com</span>
                            </div>
                            <button class="unblock-btn">Unblock</button>
                        </div>
                    </div>
                </div>
                <!-- <div class="modal-footer">
                  <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Apply</button>
              </div> -->
            </div>
        </div>
    </div>

    <!-- submit report Modal -->
    <div class="modal fade cmn-modal" id="submitreport" tabindex="-1" aria-labelledby="submitreportLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="submitreportLabel">Submit a Report</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="report-pr">Thank you for helping to keep our Yesvite community safe by reporting any
                        rule
                        violations.</p>
                    <div class="submit-blocks">
                        <span class="active">Harassment</span>
                        <span>Hate</span>
                        <span>Threatening Violence</span>
                        <span>Spam</span>
                        <span>Inappropriate Content</span>
                        <span>Violating Platform Policies</span>
                    </div>

                    <div class="textbox-container">
                        <textarea id="violation-textbox" placeholder="Details of Violation (Optional)"></textarea>
                        <div class="resize-icon" role="button"><svg width="8" height="8"
                                viewBox="0 0 8 8" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M8 7L8 1.41421C8 0.523308 6.92286 0.0771395 6.29289 0.707104L0.707107 6.29289C0.0771418 6.92286 0.523309 8 1.41421 8L7 8C7.55229 8 8 7.55228 8 7Z"
                                    fill="#CBD5E1" />
                            </svg>
                        </div>
                    </div>

                    <div class="review-text">
                        <p>Not sure if they broke the rules? Review our rules <a href="#">here</a>.</p>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" disabled>Submit
                        Report</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Submit
                        Report</button>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade cmn-modal" id="deletemodal" tabindex="-1" aria-labelledby="deletemodalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="delete-modal">
                        <div class="delete-icon">
                            <img src="{{ asset('assets/front/img/deleteicon.svg') }}" alt="delete">
                        </div>
                        <h4>Delete Potluck Category</h4>
                        <h4>Deleting this category will delete all items under this category.</h4>
                        <h5>Category deletion is not reversible.</h5>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========= edit-rsvp ======== -->


    <!-- ========= failed-invite ======== -->
    <div class="modal fade cmn-modal" id="failed" tabindex="-1" aria-labelledby="failedLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="failedLabel">Failed Invites (5)</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="failed-box">
                        <span class="d-flex align-items-center">
                            <svg width="18" height="18" viewBox="0 0 18 18" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.0013 0.666016C4.40964 0.666016 0.667969 4.40768 0.667969 8.99935C0.667969 13.591 4.40964 17.3327 9.0013 17.3327C13.593 17.3327 17.3346 13.591 17.3346 8.99935C17.3346 4.40768 13.593 0.666016 9.0013 0.666016ZM8.3763 5.66602C8.3763 5.32435 8.65964 5.04102 9.0013 5.04102C9.34297 5.04102 9.6263 5.32435 9.6263 5.66602V9.83268C9.6263 10.1743 9.34297 10.4577 9.0013 10.4577C8.65964 10.4577 8.3763 10.1743 8.3763 9.83268V5.66602ZM9.76797 12.6493C9.7263 12.7577 9.66797 12.841 9.59297 12.9243C9.50964 12.9993 9.41797 13.0577 9.31797 13.0993C9.21797 13.141 9.10964 13.166 9.0013 13.166C8.89297 13.166 8.78464 13.141 8.68464 13.0993C8.58464 13.0577 8.49297 12.9993 8.40964 12.9243C8.33464 12.841 8.2763 12.7577 8.23464 12.6493C8.19297 12.5493 8.16797 12.441 8.16797 12.3327C8.16797 12.2243 8.19297 12.116 8.23464 12.016C8.2763 11.916 8.33464 11.8243 8.40964 11.741C8.49297 11.666 8.58464 11.6077 8.68464 11.566C8.88464 11.4827 9.11797 11.4827 9.31797 11.566C9.41797 11.6077 9.50964 11.666 9.59297 11.741C9.66797 11.8243 9.7263 11.916 9.76797 12.016C9.80964 12.116 9.83464 12.2243 9.83464 12.3327C9.83464 12.441 9.80964 12.5493 9.76797 12.6493Z"
                                    fill="#F73C71" />
                            </svg>
                        </span>
                        <h5>These invites failed. Please check the details for each contact or choose alternate send
                            method. You
                            need to fix all errors before you can re-send.</h5>
                    </div>
                    <div class="success-box">
                        <span class="d-flex align-items-center">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.0013 18.3327C14.5846 18.3327 18.3346 14.5827 18.3346 9.99935C18.3346 5.41602 14.5846 1.66602 10.0013 1.66602C5.41797 1.66602 1.66797 5.41602 1.66797 9.99935C1.66797 14.5827 5.41797 18.3327 10.0013 18.3327Z"
                                    stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6.45703 10.0009L8.81536 12.3592L13.5404 7.64258" stroke="#23AA26"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span>
                        <h5>All clear, invites ready to be re-sent!</h5>
                    </div>
                    <div class="invite-contact-wrp">
                        <div class="invite-contact">
                            <a href="#" class="invite-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="invite-img">
                            </a>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="invite-user-name">Silvia Alegra</a>
                                    <div class="ms-auto">
                                        <button class="edit-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.16797 1.66602H7.5013C3.33464 1.66602 1.66797 3.33268 1.66797 7.49935V12.4993C1.66797 16.666 3.33464 18.3327 7.5013 18.3327H12.5013C16.668 18.3327 18.3346 16.666 18.3346 12.4993V10.8327"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M12.4258 3.45898C12.9841 5.45065 14.5424 7.00898 16.5424 7.57565"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button class="delete-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M7.08203 4.14102L7.26536 3.04935C7.3987 2.25768 7.4987 1.66602 8.90703 1.66602H11.0904C12.4987 1.66602 12.607 2.29102 12.732 3.05768L12.9154 4.14102"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M15.7096 7.61719L15.168 16.0089C15.0763 17.3172 15.0013 18.3339 12.6763 18.3339H7.3263C5.0013 18.3339 4.9263 17.3172 4.83464 16.0089L4.29297 7.61719"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M8.60938 13.75H11.3844" stroke="#F73C71" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7.91797 10.416H12.0846" stroke="#F73C71"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="invite-call-data mt-1">
                                    <div class="d-flex align-items-center">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                stroke="#0F172A" stroke-miterlimit="10" />
                                        </svg>
                                        <h6>1-800-5587</h6>
                                    </div>
                                    <input class="form-check-input failed-checkout" type="checkbox" value=""
                                        id="coming">
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="invite-mail-data faild-content">
                                        <div class="d-flex align-items-center">
                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <h6>silvia@gmail.com</h6>
                                        </div>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="fix-updat">Fix/update needed</span>
                                        <button type="button" class="danger-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 7.5V11.6667" stroke="#FB1C11" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.0008 17.8414H4.95084C2.05917 17.8414 0.850839 15.7747 2.25084 13.2497L4.85084 8.56641L7.30084 4.16641C8.78417 1.49141 11.2175 1.49141 12.7008 4.16641L15.1508 8.57474L17.7508 13.2581C19.1508 15.7831 17.9342 17.8497 15.0508 17.8497H10.0008V17.8414Z"
                                                    stroke="#FB1C11" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M9.99609 14.166H10.0036" stroke="#FB1C11" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invite-contact">
                            <a href="#" class="invite-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="invite-img">
                            </a>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="invite-user-name">Alena Geidt</a>
                                    <div class="ms-auto">
                                        <button class="edit-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.16797 1.66602H7.5013C3.33464 1.66602 1.66797 3.33268 1.66797 7.49935V12.4993C1.66797 16.666 3.33464 18.3327 7.5013 18.3327H12.5013C16.668 18.3327 18.3346 16.666 18.3346 12.4993V10.8327"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M12.4258 3.45898C12.9841 5.45065 14.5424 7.00898 16.5424 7.57565"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button class="delete-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M7.08203 4.14102L7.26536 3.04935C7.3987 2.25768 7.4987 1.66602 8.90703 1.66602H11.0904C12.4987 1.66602 12.607 2.29102 12.732 3.05768L12.9154 4.14102"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M15.7096 7.61719L15.168 16.0089C15.0763 17.3172 15.0013 18.3339 12.6763 18.3339H7.3263C5.0013 18.3339 4.9263 17.3172 4.83464 16.0089L4.29297 7.61719"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M8.60938 13.75H11.3844" stroke="#F73C71" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7.91797 10.416H12.0846" stroke="#F73C71"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="invite-mail-data ">
                                        <div class="d-flex align-items-center">
                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <h6>silvia@gmail.com</h6>
                                        </div>

                                    </div>
                                    <input class="form-check-input failed-checkout ms-auto" type="checkbox"
                                        value="" id="coming">
                                </div>
                                <div class="invite-call-data mt-1">
                                    <div class="d-flex align-items-center faild-content">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                stroke="#0F172A" stroke-miterlimit="10" />
                                        </svg>
                                        <h6>1-800-5587</h6>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="fix-updat">Fix/update needed</span>
                                        <button type="button" class="danger-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 7.5V11.6667" stroke="#FB1C11" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.0008 17.8414H4.95084C2.05917 17.8414 0.850839 15.7747 2.25084 13.2497L4.85084 8.56641L7.30084 4.16641C8.78417 1.49141 11.2175 1.49141 12.7008 4.16641L15.1508 8.57474L17.7508 13.2581C19.1508 15.7831 17.9342 17.8497 15.0508 17.8497H10.0008V17.8414Z"
                                                    stroke="#FB1C11" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M9.99609 14.166H10.0036" stroke="#FB1C11" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="invite-contact">
                            <a href="#" class="invite-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="invite-img">
                            </a>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="invite-user-name">Alena Geidt</a>
                                    <div class="ms-auto">
                                        <button class="edit-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.16797 1.66602H7.5013C3.33464 1.66602 1.66797 3.33268 1.66797 7.49935V12.4993C1.66797 16.666 3.33464 18.3327 7.5013 18.3327H12.5013C16.668 18.3327 18.3346 16.666 18.3346 12.4993V10.8327"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M12.4258 3.45898C12.9841 5.45065 14.5424 7.00898 16.5424 7.57565"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                        <button class="delete-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5 4.98307C14.725 4.70807 11.9333 4.56641 9.15 4.56641C7.5 4.56641 5.85 4.64974 4.2 4.81641L2.5 4.98307"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M7.08203 4.14102L7.26536 3.04935C7.3987 2.25768 7.4987 1.66602 8.90703 1.66602H11.0904C12.4987 1.66602 12.607 2.29102 12.732 3.05768L12.9154 4.14102"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M15.7096 7.61719L15.168 16.0089C15.0763 17.3172 15.0013 18.3339 12.6763 18.3339H7.3263C5.0013 18.3339 4.9263 17.3172 4.83464 16.0089L4.29297 7.61719"
                                                    stroke="#F73C71" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M8.60938 13.75H11.3844" stroke="#F73C71" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M7.91797 10.416H12.0846" stroke="#F73C71"
                                                    stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="invite-mail-data ">
                                        <div class="d-flex align-items-center">
                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <h6>silvia@gmail.com</h6>
                                        </div>

                                    </div>
                                    <input class="form-check-input failed-checkout ms-auto" type="checkbox"
                                        value="" id="coming">
                                </div>
                                <div class="invite-call-data mt-1">
                                    <div class="d-flex align-items-center faild-content">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                stroke="#0F172A" stroke-miterlimit="10" />
                                        </svg>
                                        <h6>1-800-5587</h6>
                                    </div>
                                    <div class="ms-auto">
                                        <span class="fix-updat">Fix/update needed</span>
                                        <button type="button" class="danger-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M10 7.5V11.6667" stroke="#FB1C11" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M10.0008 17.8414H4.95084C2.05917 17.8414 0.850839 15.7747 2.25084 13.2497L4.85084 8.56641L7.30084 4.16641C8.78417 1.49141 11.2175 1.49141 12.7008 4.16641L15.1508 8.57474L17.7508 13.2581C19.1508 15.7831 17.9342 17.8497 15.0508 17.8497H10.0008V17.8414Z"
                                                    stroke="#FB1C11" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M9.99609 14.166H10.0036" stroke="#FB1C11" stroke-width="2"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>

                            </div>
                        </div>
                        <div class="invite-contact">
                            <a href="#" class="invite-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="invite-img">
                            </a>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="invite-user-name">Alena Geidt</a>
                                    <div class="ms-auto">
                                        <button class="edit-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.16797 1.66602H7.5013C3.33464 1.66602 1.66797 3.33268 1.66797 7.49935V12.4993C1.66797 16.666 3.33464 18.3327 7.5013 18.3327H12.5013C16.668 18.3327 18.3346 16.666 18.3346 12.4993V10.8327"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M12.4258 3.45898C12.9841 5.45065 14.5424 7.00898 16.5424 7.57565"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="invite-mail-data ">
                                        <div class="d-flex align-items-center">
                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <h6>silvia@gmail.com</h6>
                                        </div>

                                    </div>
                                    <input class="form-check-input success-checkout ms-auto" type="checkbox"
                                        value="" id="coming">
                                </div>
                                <div class="invite-call-data mt-1">
                                    <div class="d-flex align-items-center">
                                        <svg width="14" height="14" viewBox="0 0 14 14" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                stroke="#0F172A" stroke-miterlimit="10" />
                                        </svg>
                                        <h6>1-800-5587</h6>
                                    </div>
                                    <div class="ms-auto">
                                        <input class="form-check-input success-checkout ms-auto" type="checkbox"
                                            value="" id="coming">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="invite-contact">
                            <a href="#" class="invite-img">
                                <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="invite-img">
                            </a>
                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <a href="#" class="invite-user-name">Alena Geidt</a>
                                    <div class="ms-auto">
                                        <button class="edit-btn">
                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.16797 1.66602H7.5013C3.33464 1.66602 1.66797 3.33268 1.66797 7.49935V12.4993C1.66797 16.666 3.33464 18.3327 7.5013 18.3327H12.5013C16.668 18.3327 18.3346 16.666 18.3346 12.4993V10.8327"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M13.3675 2.51639L6.80088 9.08306C6.55088 9.33306 6.30088 9.82472 6.25088 10.1831L5.89254 12.6914C5.75921 13.5997 6.40088 14.2331 7.30921 14.1081L9.81754 13.7497C10.1675 13.6997 10.6592 13.4497 10.9175 13.1997L17.4842 6.63306C18.6175 5.49972 19.1509 4.18306 17.4842 2.51639C15.8175 0.849722 14.5009 1.38306 13.3675 2.51639Z"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M12.4258 3.45898C12.9841 5.45065 14.5424 7.00898 16.5424 7.57565"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center mt-1">
                                    <div class="invite-mail-data ">
                                        <div class="d-flex align-items-center">
                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                    stroke="black" stroke-miterlimit="10" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <h6>silvia@gmail.com</h6>
                                        </div>
                                    </div>
                                    <input class="form-check-input success-checkout ms-auto" type="checkbox"
                                        value="" id="coming">
                                </div>
                                <div class="invite-call-data mt-1">
                                    <div class="d-flex align-items-center faild-content">
                                        <span class="d-flex align-items-center faild-serve">
                                            <svg width="14" height="14" viewBox="0 0 14 14"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M12.6934 9.28602L8.96011 2.56602C8.45844 1.66185 7.76428 1.16602 7.00011 1.16602C6.23594 1.16602 5.54178 1.66185 5.04011 2.56602L1.30678 9.28602C0.834276 10.1435 0.781776 10.966 1.16094 11.6135C1.54011 12.261 2.28678 12.6168 3.26678 12.6168H10.7334C11.7134 12.6168 12.4601 12.261 12.8393 11.6135C13.2184 10.966 13.1659 10.1377 12.6934 9.28602ZM6.56261 5.24935C6.56261 5.01018 6.76094 4.81185 7.00011 4.81185C7.23928 4.81185 7.43761 5.01018 7.43761 5.24935V8.16602C7.43761 8.40518 7.23928 8.60352 7.00011 8.60352C6.76094 8.60352 6.56261 8.40518 6.56261 8.16602V5.24935ZM7.41428 10.3302C7.38511 10.3535 7.35594 10.3768 7.32678 10.4002C7.29178 10.4235 7.25678 10.441 7.22178 10.4527C7.18678 10.4702 7.15177 10.4818 7.11094 10.4877C7.07594 10.4935 7.03511 10.4993 7.00011 10.4993C6.96511 10.4993 6.92428 10.4935 6.88344 10.4877C6.84844 10.4818 6.81344 10.4702 6.77844 10.4527C6.74344 10.441 6.70844 10.4235 6.67344 10.4002C6.64427 10.3768 6.61511 10.3535 6.58594 10.3302C6.48094 10.2193 6.41678 10.0677 6.41678 9.91602C6.41678 9.76435 6.48094 9.61268 6.58594 9.50185C6.61511 9.47852 6.64427 9.45518 6.67344 9.43185C6.70844 9.40852 6.74344 9.39102 6.77844 9.37935C6.81344 9.36185 6.84844 9.35018 6.88344 9.34435C6.95928 9.32685 7.04094 9.32685 7.11094 9.34435C7.15177 9.35018 7.18678 9.36185 7.22178 9.37935C7.25678 9.39102 7.29178 9.40852 7.32678 9.43185C7.35594 9.45518 7.38511 9.47852 7.41428 9.50185C7.51928 9.61268 7.58344 9.76435 7.58344 9.91602C7.58344 10.0677 7.51928 10.2193 7.41428 10.3302Z"
                                                    fill="#E03137" />
                                            </svg>
                                        </span>
                                        <h6>1-800-5587</h6>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary failed-btn"
                        data-bs-dismiss="modal">Re-send</button>
                    <button type="button" class="btn btn-secondary success-btn"
                        data-bs-dismiss="modal">Re-send</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========= Add-guest ======== -->
    <div class="modal fade cmn-modal" id="uploadcsv" tabindex="-1" aria-labelledby="addguestLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addguestLabel">Upload CSV</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body guest-tab">
                    <div class="uploadcsv-wrp">
                        <img src="{{ asset('assets/front/img/uploadcsv-drag-img.png') }}" alt="">
                        <h3>Drag CSV Here</h3>
                        <p>Drag file here or click to upload </p>
                        <input type="file">
                    </div>
                    <div class="uploadedcvs-file-wrp">
                        <div class="uploadedcvs-file-card home-latest-draf-card">
                            <div class="uploadedcvs-file-card-head">
                                <div class="uploadedcvs-file-card-icon">
                                    <img src="{{ asset('assets/front/img/file-format-icon.svg') }}"
                                        alt="">
                                </div>
                                <div class="uploadedcvs-file-card-content">
                                    <h4>contact.csv</h4>
                                    <p>60 KB of 120 KB
                                        <span>
                                            <svg class="uploading-progress-icon" viewBox="0 0 13 13"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.5 0.25C6.65913 0.25 6.81174 0.313214 6.92426 0.425736C7.03679 0.538258 7.1 0.69087 7.1 0.85V2.65C7.1 2.80913 7.03679 2.96174 6.92426 3.07426C6.81174 3.18679 6.65913 3.25 6.5 3.25C6.34087 3.25 6.18826 3.18679 6.07574 3.07426C5.96321 2.96174 5.9 2.80913 5.9 2.65V0.85C5.9 0.69087 5.96321 0.538258 6.07574 0.425736C6.18826 0.313214 6.34087 0.25 6.5 0.25V0.25ZM6.5 9.25C6.65913 9.25 6.81174 9.31321 6.92426 9.42574C7.03679 9.53826 7.1 9.69087 7.1 9.85V11.65C7.1 11.8091 7.03679 11.9617 6.92426 12.0743C6.81174 12.1868 6.65913 12.25 6.5 12.25C6.34087 12.25 6.18826 12.1868 6.07574 12.0743C5.96321 11.9617 5.9 11.8091 5.9 11.65V9.85C5.9 9.69087 5.96321 9.53826 6.07574 9.42574C6.18826 9.31321 6.34087 9.25 6.5 9.25V9.25ZM12.5 6.25C12.5 6.40913 12.4368 6.56174 12.3243 6.67426C12.2117 6.78679 12.0591 6.85 11.9 6.85H10.1C9.94087 6.85 9.78826 6.78679 9.67574 6.67426C9.56321 6.56174 9.5 6.40913 9.5 6.25C9.5 6.09087 9.56321 5.93826 9.67574 5.82574C9.78826 5.71321 9.94087 5.65 10.1 5.65H11.9C12.0591 5.65 12.2117 5.71321 12.3243 5.82574C12.4368 5.93826 12.5 6.09087 12.5 6.25ZM3.5 6.25C3.5 6.40913 3.43679 6.56174 3.32426 6.67426C3.21174 6.78679 3.05913 6.85 2.9 6.85H1.1C0.94087 6.85 0.788258 6.78679 0.675736 6.67426C0.563214 6.56174 0.5 6.40913 0.5 6.25C0.5 6.09087 0.563214 5.93826 0.675736 5.82574C0.788258 5.71321 0.94087 5.65 1.1 5.65H2.9C3.05913 5.65 3.21174 5.71321 3.32426 5.82574C3.43679 5.93826 3.5 6.09087 3.5 6.25ZM10.7426 10.4926C10.6301 10.6051 10.4775 10.6683 10.3184 10.6683C10.1593 10.6683 10.0067 10.6051 9.8942 10.4926L8.6216 9.22C8.51231 9.10684 8.45183 8.95528 8.4532 8.79796C8.45456 8.64064 8.51766 8.49015 8.62891 8.37891C8.74015 8.26766 8.89064 8.20456 9.04796 8.2032C9.20528 8.20183 9.35684 8.26231 9.47 8.3716L10.7426 9.6436C10.7984 9.69932 10.8426 9.7655 10.8728 9.83834C10.903 9.91118 10.9186 9.98925 10.9186 10.0681C10.9186 10.147 10.903 10.225 10.8728 10.2979C10.8426 10.3707 10.7984 10.4369 10.7426 10.4926V10.4926ZM4.3784 4.1284C4.26588 4.24088 4.1133 4.30407 3.9542 4.30407C3.7951 4.30407 3.64252 4.24088 3.53 4.1284L2.258 2.8564C2.14542 2.7439 2.08213 2.59127 2.08208 2.43211C2.08202 2.27295 2.1452 2.12028 2.2577 2.0077C2.3702 1.89512 2.52283 1.83183 2.68199 1.83178C2.84115 1.83172 2.99382 1.8949 3.1064 2.0074L4.3784 3.28C4.49088 3.39252 4.55407 3.5451 4.55407 3.7042C4.55407 3.8633 4.49088 4.01588 4.3784 4.1284V4.1284ZM2.258 10.4926C2.14552 10.3801 2.08233 10.2275 2.08233 10.0684C2.08233 9.9093 2.14552 9.75672 2.258 9.6442L3.5306 8.3716C3.58595 8.31429 3.65216 8.26858 3.72536 8.23714C3.79856 8.20569 3.87729 8.18914 3.95696 8.18845C4.03663 8.18776 4.11563 8.20294 4.18937 8.23311C4.26311 8.26328 4.3301 8.30783 4.38644 8.36416C4.44277 8.4205 4.48732 8.48749 4.51749 8.56123C4.54766 8.63497 4.56284 8.71397 4.56215 8.79364C4.56146 8.87331 4.54491 8.95204 4.51346 9.02524C4.48202 9.09845 4.43631 9.16465 4.379 9.22L3.107 10.4926C3.05128 10.5484 2.9851 10.5926 2.91226 10.6228C2.83943 10.653 2.76135 10.6686 2.6825 10.6686C2.60365 10.6686 2.52557 10.653 2.45274 10.6228C2.3799 10.5926 2.31372 10.5484 2.258 10.4926V10.4926ZM8.6216 4.1284C8.50912 4.01588 8.44593 3.8633 8.44593 3.7042C8.44593 3.5451 8.50912 3.39252 8.6216 3.28L9.8936 2.0074C10.0061 1.89482 10.1587 1.83153 10.3179 1.83148C10.4771 1.83142 10.6297 1.8946 10.7423 2.0071C10.8549 2.11961 10.9182 2.27223 10.9182 2.43139C10.9183 2.59055 10.8551 2.74322 10.7426 2.8558L9.47 4.1284C9.35748 4.24088 9.2049 4.30407 9.0458 4.30407C8.8867 4.30407 8.73412 4.24088 8.6216 4.1284V4.1284Z"
                                                    fill="#F73C71" />
                                            </svg>
                                            Uploading...</span>
                                    </p>
                                </div>
                                <button class="cancel-uploading-btn"><svg viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M4.66797 4.41669L16.3339 16.0826" stroke="#64748B"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M4.66615 16.0826L16.332 4.41669" stroke="#64748B"
                                            stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                    </svg></button>
                            </div>
                            <div class="progress-bar__wrapper">
                                <progress id="progress-bar" value="75" max="100"></progress>
                            </div>
                        </div>
                        <div class="uploadedcvs-file-card home-latest-draf-card">
                            <div class="uploadedcvs-file-card-head">
                                <div class="uploadedcvs-file-card-icon">
                                    <img src="{{ asset('assets/front/img/file-format-icon.svg') }}"
                                        alt="">
                                </div>
                                <div class="uploadedcvs-file-card-content">
                                    <h4>contact.csv</h4>
                                    <p>60 KB of 120 KB
                                        <span>
                                            <svg viewBox="0 0 13 13" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M6.5 12.25C3.1862 12.25 0.5 9.5638 0.5 6.25C0.5 2.9362 3.1862 0.25 6.5 0.25C9.8138 0.25 12.5 2.9362 12.5 6.25C12.5 9.5638 9.8138 12.25 6.5 12.25ZM5.9018 8.65L10.1438 4.4074L9.2954 3.559L5.9018 6.9532L4.2044 5.2558L3.356 6.1042L5.9018 8.65Z"
                                                    fill="#23AA26" />
                                            </svg>
                                            Completed</span>
                                    </p>
                                </div>
                                <button class="cancel-uploading-btn"><svg viewBox="0 0 21 21" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M18 5.23307C15.225 4.95807 12.4333 4.81641 9.65 4.81641C8 4.81641 6.35 4.89974 4.7 5.06641L3 5.23307"
                                            stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M7.58203 4.39102L7.76536 3.29935C7.8987 2.50768 7.9987 1.91602 9.40703 1.91602H11.5904C12.9987 1.91602 13.107 2.54102 13.232 3.30768L13.4154 4.39102"
                                            stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M16.2096 7.86719L15.668 16.2589C15.5763 17.5672 15.5013 18.5839 13.1763 18.5839H7.8263C5.5013 18.5839 5.4263 17.5672 5.33464 16.2589L4.79297 7.86719"
                                            stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M9.10938 14H11.8844" stroke="#64748B" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M8.41797 10.666H12.5846" stroke="#64748B" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                            <div class="progress-bar__wrapper">
                                <progress id="progress-bar" value="75" max="100"></progress>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer-wrp">
                    <div class="modal-footer rsvp-button-wrp">
                        <button type="button" class="cmn-btn download-csv-btn" data-bs-dismiss="modal">Download
                            CSV</button>
                        <button type="button" class="cmn-btn click-to-upload-btn"><input type="file"> Click to
                            Upload</button>
                    </div>
                    <p>Support text/csv, maximum file size of 10Mb</p>
                </div>
            </div>
        </div>
    </div>
    <!-- ========= edit-guest ======== -->
    <div class="modal fade cmn-modal" id="editguest" tabindex="-1" aria-labelledby="editguestLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editguestLabel">Update Contact</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="">
                        <div class="row">
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="input-form">
                                    <input type="text" class="form-control inputText" id="Fname"
                                        name="Fname" required="">
                                    <label for="Fname" class="form-label input-field floating-label">First
                                        Name<span class="required">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                <div class="input-form">
                                    <input type="text" class="form-control inputText" id="Lname"
                                        name="Lname" required="">
                                    <label for="Lname" class="form-label input-field floating-label">Last
                                        Name<span class="required">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="input-form">
                                    <input type="tel" class="form-control inputText" id="phone"
                                        name="phone" required="">
                                    <label for="phone" class="form-label input-field floating-label">Phone
                                        Number<span class="required">*</span></label>
                                </div>
                            </div>
                            <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                <div class="input-form">
                                    <input type="email" class="form-control inputText" id="email"
                                        name="email" required="">
                                    <label for="email" class="form-label input-field floating-label">Email
                                        Address<span class="required">*</span></label>
                                </div>
                            </div>
                            <div class="guest-updat-info">
                                <h5>All information is correct (no changes needed)</h5>
                                <div class="toggle-button-cover toggle-custom">
                                    <div class="buttons-cover">
                                        <div class="button r" id="button-1">
                                            <input type="checkbox" class="checkbox" checked="">
                                            <div class="knobs"></div>
                                            <div class="layer"></div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer rsvp-button-wrp">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== cancel event ======== -->
    <div class="modal fade cmn-modal cancel-event" id="cancelevent" tabindex="-1"
        aria-labelledby="canceleventLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
                <div class="modal-body">
                    <div class="delete-modal-head text-center">
                        <div class="delete-icon">
                            <img src="{{ asset('assets/front/img/deleteicon.svg') }}" alt="delete">
                        </div>
                        <h4>Cancel Event</h4>
                        <p>Cancelling this event will delete everything in this event including but not limited to all
                            comments,
                            photos, and settings associated with this event for you and your guests.</p>
                    </div>
                    <div class="guest-msg">
                        <h5>Message to guests</h5>
                        <textarea name="" id="">*Let your guests know why you are cancelling event.......</textarea>
                    </div>
                    <div class="cancel-event-text text-center">
                        <h6>Event cancellation is not reversible.</h6>
                        <p>Please confirm by typing <strong></strong> below.</p>
                        <input type="text" placeholder="CANCEL" class="form-control">
                    </div>
                </div>
                <div class="modal-footer cancel-confirm-btn">
                    <button type="button" class="btn btn-secondary cancel-btn"
                        data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-secondary confirm-btn"
                        data-bs-dismiss="modal">Confirm</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== event canceled ======== -->
    <div class="modal fade cmn-modal cancel-event" id="eventcanceled" tabindex="-1"
        aria-labelledby="eventcanceledLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
                <div class="modal-body">
                    <div class="delete-modal-head text-center">
                        <div class="delete-icon">
                            <img src="{{ asset('assets/front/img/event.svg') }}" alt="delete">
                        </div>
                        <h4>Event Cancelled</h4>
                        <p class="mb-0">If you had guests invited, they have been notified that the event was
                            cancelled.</p>
                    </div>
                </div>
                <div class="modal-footer cancel-confirm-btn">
                    <button type="button" class="btn btn-secondary home-btn"
                        data-bs-dismiss="modal">Home</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ====== event copied ======== -->
    <div class="modal fade cmn-modal cancel-event" id="eventcopied" tabindex="-1"
        aria-labelledby="eventcopiedLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <!-- <div class="modal-header">
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div> -->
                <div class="modal-body">
                    <div class="delete-modal-head text-center">
                        <div class="delete-icon">
                            <img src="{{ asset('assets/front/img/event.svg') }}" alt="delete">
                        </div>
                        <h4>Event Copied</h4>
                        <p class="mb-0">You can start to edit the event</p>
                    </div>
                </div>
                <div class="modal-footer cancel-confirm-btn">
                    <button type="button" class="btn btn-secondary cancel-btn"
                        data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-secondary confirm-btn"
                        data-bs-dismiss="modal">Edit</button>
                </div>
            </div>
        </div>
    </div>

</main>
