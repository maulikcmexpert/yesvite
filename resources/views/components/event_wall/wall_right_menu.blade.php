@php

    $guestArray = $eventInfo['guest_view']['event_detail']['guests']['all_invited_users'] ?? null;

    $eventId = $eventInfo['guest_view']['event_detail']['id'] ?? null;
    $total_invite = $eventInfo['host_view']['total_invite'] ?? 0;
    $totalAdults = 0;
    $totalKids = 0;
    // dd( $eventInfo);

    if ($guestArray) {
        foreach ($guestArray as $guest) {
            $totalAdults += $guest['adults'] ?? 0;
            $totalKids += $guest['kids'] ?? 0; // Accessing related user data
        }
    } else {
        echo 'No guests found.';
    }

    //  dd($guests,$host_id);
    // Initialize totals

    // Total attending
    $totalAttending = $totalAdults + $totalKids;
@endphp

<div class="main-content-right">
    <div class="main-right-guests-wrp common-div-wrp">
        <div class="main-right-guests-top-rsvp">
            @if ($eventInfo['guest_view']['is_host'] == 1 || $eventInfo['guest_view']['is_co_host'] == '1')
                <div class="main-right-host-top-rsvp-card green">
                    <span>Hosting</span>
                </div>
            @else
                @if (!empty($rsvpSent) && $rsvpSent['rsvp_status'] == '0')
                    <div class="main-right-guests-top-rsvp-card red">
                        <span class="main-right-guests-top-rsvp-card-text">Guest</span>
                        <div class="main-right-guests-top-rvsp-icon-text">
                            <span> Not Attending</span>
                            <span><svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.9974 18.3327C14.5807 18.3327 18.3307 14.5827 18.3307 9.99935C18.3307 5.41602 14.5807 1.66602 9.9974 1.66602C5.41406 1.66602 1.66406 5.41602 1.66406 9.99935C1.66406 14.5827 5.41406 18.3327 9.9974 18.3327Z"
                                        stroke="#E03137" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M7.64062 12.3592L12.3573 7.64258" stroke="#E03137" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.3573 12.3592L7.64062 7.64258" stroke="#E03137" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </span>
                        </div>
                    </div>
                @elseif (!empty($rsvpSent) && $rsvpSent['rsvp_status'] == '1')
                    <div class="main-right-guests-top-rsvp-card green">
                        <span class="main-right-guests-top-rsvp-card-text">Guest</span>
                        <div class="main-right-guests-top-rvsp-icon-text">
                            <span>Attending</span>
                            <span>
                                <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M9.9974 18.4596C5.33187 18.4596 1.53906 14.6668 1.53906 10.0013C1.53906 5.33578 5.33187 1.54297 9.9974 1.54297C14.6629 1.54297 18.4557 5.33578 18.4557 10.0013C18.4557 14.6668 14.6629 18.4596 9.9974 18.4596ZM9.9974 1.79297C5.47125 1.79297 1.78906 5.47516 1.78906 10.0013C1.78906 14.5274 5.47125 18.2096 9.9974 18.2096C14.5235 18.2096 18.2057 14.5274 18.2057 10.0013C18.2057 5.47516 14.5235 1.79297 9.9974 1.79297Z"
                                        fill="#23AA26" stroke="#23AA26" />
                                    <path
                                        d="M8.46363 11.8299L8.81719 12.1834L9.17074 11.8299L13.4541 7.54652C13.4756 7.525 13.5063 7.51172 13.5422 7.51172C13.578 7.51172 13.6088 7.525 13.6303 7.54652C13.6518 7.56805 13.6651 7.59878 13.6651 7.63464C13.6651 7.67049 13.6518 7.70122 13.6303 7.72275L8.9053 12.4477C8.88133 12.4717 8.84974 12.4846 8.81719 12.4846C8.78464 12.4846 8.75304 12.4717 8.72907 12.4477L6.37074 10.0894C6.34921 10.0679 6.33594 10.0372 6.33594 10.0013C6.33594 9.96545 6.34921 9.93472 6.37074 9.91319C6.39227 9.89166 6.423 9.87839 6.45885 9.87839C6.49471 9.87839 6.52544 9.89166 6.54697 9.91319L8.46363 11.8299Z"
                                        fill="#23AA26" stroke="#23AA26" />
                                </svg>
                            </span>
                        </div>
                    </div>
                @else
                    <div class="main-right-guests-top-rsvp-card gray">
                        <span class="main-right-guests-top-rsvp-card-text">Guest</span>
                        <div class="main-right-guests-top-rvsp-icon-text">
                            <span>Need to RSVP</span>
                            <span><svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_5919_106207)">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M18.125 10C18.125 12.1549 17.269 14.2215 15.7452 15.7452C14.2215 17.269 12.1549 18.125 10 18.125C7.84512 18.125 5.77849 17.269 4.25476 15.7452C2.73102 14.2215 1.875 12.1549 1.875 10C1.875 7.84512 2.73102 5.77849 4.25476 4.25476C5.77849 2.73102 7.84512 1.875 10 1.875C12.1549 1.875 14.2215 2.73102 15.7452 4.25476C17.269 5.77849 18.125 7.84512 18.125 10ZM20 10C20 12.6522 18.9464 15.1957 17.0711 17.0711C15.1957 18.9464 12.6522 20 10 20C7.34784 20 4.8043 18.9464 2.92893 17.0711C1.05357 15.1957 0 12.6522 0 10C0 7.34784 1.05357 4.8043 2.92893 2.92893C4.8043 1.05357 7.34784 0 10 0C12.6522 0 15.1957 1.05357 17.0711 2.92893C18.9464 4.8043 20 7.34784 20 10ZM6.15875 6.2375C5.8025 6.77375 5.625 7.30375 5.625 7.825C5.625 8.07875 5.7375 8.315 5.9625 8.5325C6.1875 8.75 6.4625 8.8575 6.78875 8.8575C7.3425 8.8575 7.71875 8.5475 7.9175 7.925C8.1275 7.33125 8.38375 6.88125 8.6875 6.575C8.99125 6.27 9.4625 6.1175 10.105 6.1175C10.6537 6.1175 11.1013 6.2675 11.4488 6.57125C11.795 6.87375 11.9688 7.24625 11.9688 7.68625C11.9703 7.90699 11.9115 8.12396 11.7987 8.31375C11.6841 8.50724 11.5427 8.68357 11.3787 8.8375C11.1157 9.078 10.8456 9.3106 10.5688 9.535C10.1438 9.8875 9.805 10.1912 9.55375 10.4475C9.30375 10.7038 9.10125 11.0013 8.95 11.3387C8.5475 12.895 10.6375 13.02 11.12 11.9088C11.1788 11.8013 11.2675 11.6838 11.3863 11.5538C11.5063 11.425 11.665 11.275 11.8638 11.1038C12.3695 10.6825 12.8671 10.2516 13.3563 9.81125C13.6325 9.55625 13.8713 9.25125 14.0725 8.89875C14.28 8.52257 14.3843 8.09824 14.375 7.66875C14.375 7.075 14.1988 6.525 13.845 6.01875C13.4925 5.51125 12.9925 5.11125 12.345 4.81625C11.6975 4.5225 10.9512 4.375 10.105 4.375C9.195 4.375 8.39875 4.55 7.71625 4.90375C7.03375 5.25625 6.515 5.70125 6.15875 6.2375ZM8.83375 15.0875C8.83375 15.419 8.96545 15.737 9.19987 15.9714C9.43429 16.2058 9.75223 16.3375 10.0838 16.3375C10.4153 16.3375 10.7332 16.2058 10.9676 15.9714C11.2021 15.737 11.3338 15.419 11.3338 15.0875C11.3338 14.756 11.2021 14.438 10.9676 14.2036C10.7332 13.9692 10.4153 13.8375 10.0838 13.8375C9.75223 13.8375 9.43429 13.9692 9.19987 14.2036C8.96545 14.438 8.83375 14.756 8.83375 15.0875Z"
                                            fill="#94A3B8" />
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_5919_106207">
                                            <rect width="20" height="20" fill="white" />
                                        </clipPath>
                                    </defs>
                                </svg>
                            </span>
                        </div>
                    </div>
                @endif
            @endif

        </div>
        <div class="main-right-guests-head">
            <h3>Attending Guests</h3>
            <p>{{ $total_invite }} Total</p>
        </div>
        @php
            // Safely fetch the value of 'event_detail[2]' or set it to null if it doesn't exist
$guestArray = $eventInfo['guest_view']['event_detail']['guests']['all_invited_users'] ?? null;

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

        <div class="guest-attending-wrp">
            <div class="total-attending-guest">
                <h4>
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M7.5013 1.66669C5.31797 1.66669 3.54297 3.44169 3.54297 5.62502C3.54297 7.76669 5.21797 9.50002 7.4013 9.57502C7.46797 9.56669 7.53464 9.56669 7.58464 9.57502C7.6013 9.57502 7.60963 9.57502 7.6263 9.57502C7.63463 9.57502 7.63463 9.57502 7.64297 9.57502C9.7763 9.50002 11.4513 7.76669 11.4596 5.62502C11.4596 3.44169 9.68463 1.66669 7.5013 1.66669Z"
                            fill="#0DAD5F" />
                        <path
                            d="M11.7328 11.7917C9.40781 10.2417 5.61615 10.2417 3.27448 11.7917C2.21615 12.5 1.63281 13.4583 1.63281 14.4833C1.63281 15.5083 2.21615 16.4583 3.26615 17.1583C4.43281 17.9417 5.96615 18.3333 7.49948 18.3333C9.03281 18.3333 10.5661 17.9417 11.7328 17.1583C12.7828 16.45 13.3661 15.5 13.3661 14.4667C13.3578 13.4417 12.7828 12.4917 11.7328 11.7917Z"
                            fill="#0DAD5F" />
                        <path
                            d="M16.6578 6.11665C16.7911 7.73331 15.6411 9.14998 14.0495 9.34165C14.0411 9.34165 14.0411 9.34165 14.0328 9.34165H14.0078C13.9578 9.34165 13.9078 9.34165 13.8661 9.35831C13.0578 9.39998 12.3161 9.14165 11.7578 8.66665C12.6161 7.89998 13.1078 6.74998 13.0078 5.49998C12.9495 4.82498 12.7161 4.20831 12.3661 3.68331C12.6828 3.52498 13.0495 3.42498 13.4245 3.39165C15.0578 3.24998 16.5161 4.46665 16.6578 6.11665Z"
                            fill="#0DAD5F" />
                        <path
                            d="M18.3268 13.825C18.2602 14.6334 17.7435 15.3334 16.8768 15.8084C16.0435 16.2667 14.9935 16.4834 13.9518 16.4584C14.5518 15.9167 14.9018 15.2417 14.9685 14.525C15.0518 13.4917 14.5602 12.5 13.5768 11.7084C13.0185 11.2667 12.3685 10.9167 11.6602 10.6584C13.5018 10.125 15.8185 10.4834 17.2435 11.6334C18.0102 12.25 18.4018 13.025 18.3268 13.825Z"
                            fill="#0DAD5F" />
                    </svg>
                    {{ $totalAttending }} <span> Attending</span>
                </h4>
            </div>
            <div class="type-guset-wrp">
                {{-- <h4>{{ $totalAdults }} <span>Adults</span></h4>
                <h4>{{ $totalKids }} <span>Kids</span></h4> --}}
                <h4>
                    {{ $totalAdults }} <span>{{ $totalAdults == 1 ? 'Adult' : 'Adults' }}</span>
                </h4>
                <h4>
                    {{ $totalKids }} <span>{{ $totalKids == 1 ? 'Kid' : 'Kids' }}</span>
                </h4>
            </div>
        </div>
        <div class="all-events-searchbar-wrp">
            <form>
                <div class="position-relative">
                    <input type="text" class="form-control search_contact" data-see_all="0" id="text"
                        placeholder="Search name">
                    <span class="search-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M9.58268 17.5C13.9549 17.5 17.4993 13.9555 17.4993 9.58329C17.4993 5.21104 13.9549 1.66663 9.58268 1.66663C5.21043 1.66663 1.66602 5.21104 1.66602 9.58329C1.66602 13.9555 5.21043 17.5 9.58268 17.5Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M18.3327 18.3333L16.666 16.6666" stroke="#94A3B8" stroke-width="1.5"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
            </form>
        </div>
        <div class="guests-listing-wrp guest-user-list">
            <input type="hidden" id="eventId" value="{{ $event }}" />
            <ul id="guestList">
                @if (!empty($guestArray))
                    @foreach ($guestArray as $index => $guest)
                        @if (!empty($guest))
                            @if ($index == 7)
                                @break
                            @endif

                            @php
                                //$user = $guest['user']; // Fetch user array
                                $firstInitial = isset($guest['first_name'][0])
                                    ? strtoupper($guest['first_name'][0])
                                    : '';
                                $secondInitial = isset($guest['last_name'][0])
                                    ? strtoupper($guest['last_name'][0])
                                    : '';
                                $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                $fontColor = 'fontcolor' . strtoupper($firstInitial);
                            @endphp
                            <li class="guests-listing-info contact contactslist"
                                data-guest-id="{{ $guest['guest_id'] }}" data-is_sync="{{ $guest['is_sync'] }}">
                                <div class="d-flex align-items-center justify-content-between w-100 gap-2">
                                    <div class="posts-card-head-left guests-listing-left">
                                        <div class="posts-card-head-left-img guest-users active_slide_bar"
                                            data-userId="{{ $guest['id'] }}">
                                            @if (!empty($guest['profile']))
                                                <img src="{{ $guest['profile'] }}" alt="">
                                            @else
                                                <h5 class="{{ $fontColor }}">
                                                    {{ $initials }}
                                                </h5>
                                            @endif
                                            <span class="inactive-dot"></span>
                                        </div>
                                        <div class="posts-card-head-left-content contact_search"
                                            data-search = "{{ $guest['first_name'] }} {{ $guest['last_name'] }}">
                                            @php
                                                $username = $guest['first_name'] . ' ' . $guest['last_name'];
                                            @endphp

                                            @if ($guest['is_sync'] == '0')
                                                <h3 class="openProfileModal" data-bs-toggle="modal"
                                                    data-bs-target="#wall_profile" data-userid="{{ $guest['id'] }}">
                                                    {{ $username }}
                                                </h3>
                                            @else
                                                <h3 class="openProfileModal text-decoration-none"
                                                    data-bs-toggle="modal" data-userid="{{ $guest['id'] }}">
                                                    {{ $username }}
                                                </h3>
                                            @endif

                                            @if ($guest['prefer_by'] == 'email')
                                                <p>{{ $guest['email'] }}</p>
                                            @else
                                                <p>{{ $guest['phone_number'] }}</p>
                                            @endif


                                            <input type="hidden" id="eventID" value="{{ $eventId }}">
                                            <input type="hidden" id="user_id" value="{{ $guest['id'] }}">

                                            <input type="hidden" id="sync" value="{{ $guest['is_sync'] }}">
                                        </div>
                                    </div>
                                    @if ($guest['is_sync'] == '0')
                                        <div class="guests-listing-right" data-guest-id="{{ $guest['guest_id'] }}"
                                            data-is_sync="{{ $guest['is_sync'] }}">
                                            <div class="guest_rsvp_icon" data-guest-id="{{ $guest['guest_id'] }}"
                                                data-is_sync="{{ $guest['is_sync'] }}">
                                                @if ($guest['is_sync'] == '0')
                                                    @if ($guest['rsvp_status'] == '1')
                                                        <!-- Approved -->
                                                        <span id="approve" data-guest-id="{{ $guest['guest_id'] }}"
                                                            data-is_sync="{{ $guest['is_sync'] }}">
                                                            <svg viewBox="0 0 20 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M10.0013 18.4583C5.33578 18.4583 1.54297 14.6655 1.54297 9.99996C1.54297 5.33444 5.33578 1.54163 10.0013 1.54163C14.6668 1.54163 18.4596 5.33444 18.4596 9.99996C18.4596 14.6655 14.6668 18.4583 10.0013 18.4583ZM10.0013 1.79163C5.47516 1.79163 1.79297 5.47382 1.79297 9.99996C1.79297 14.5261 5.47516 18.2083 10.0013 18.2083C14.5274 18.2083 18.2096 14.5261 18.2096 9.99996C18.2096 5.47382 14.5274 1.79163 10.0013 1.79163Z"
                                                                    fill="#23AA26" stroke="#23AA26" />
                                                                <path
                                                                    d="M8.46363 11.8285L8.81719 12.1821L9.17074 11.8285L13.4541 7.54518C13.4756 7.52365 13.5063 7.51038 13.5422 7.51038C13.578 7.51038 13.6088 7.52365 13.6303 7.54518C13.6518 7.56671 13.6651 7.59744 13.6651 7.63329C13.6651 7.66914 13.6518 7.69988 13.6303 7.72141L8.9053 12.4464C8.88133 12.4704 8.84974 12.4833 8.81719 12.4833C8.78464 12.4833 8.75304 12.4704 8.72907 12.4464L6.37074 10.0881C6.34921 10.0665 6.33594 10.0358 6.33594 9.99996C6.33594 9.96411 6.34921 9.93337 6.37074 9.91185C6.39227 9.89032 6.423 9.87704 6.45885 9.87704C6.49471 9.87704 6.52544 9.89032 6.54697 9.91185L8.46363 11.8285Z"
                                                                    fill="#23AA26" stroke="#23AA26" />
                                                            </svg>
                                                        </span>
                                                    @elseif ($guest['rsvp_status'] == '0')
                                                        <!-- Cancelled -->
                                                        <span id="cancel" data-guest-id="{{ $guest['guest_id'] }}"
                                                            data-is_sync="{{ $guest['is_sync'] }}">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect width="20" height="20" rx="10"
                                                                    fill="#E03137" />
                                                                <path d="M5.91797 5.91663L14.0841 14.0827"
                                                                    stroke="white" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M5.91787 14.0827L14.084 5.91663"
                                                                    stroke="white" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                    @else
                                                        <!-- Pending -->
                                                        <span id="pending" data-guest-id="{{ $guest['guest_id'] }}"
                                                            data-is_sync="{{ $guest['is_sync'] }}">
                                                            <svg width="20" height="20" viewBox="0 0 20 20"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect width="20" height="20" rx="10"
                                                                    fill="#94A3B8" />
                                                                <path
                                                                    d="M15.8327 10C15.8327 13.22 13.2193 15.8334 9.99935 15.8334C6.77935 15.8334 4.16602 13.22 4.16602 10C4.16602 6.78002 6.77935 4.16669 9.99935 4.16669C13.2193 4.16669 15.8327 6.78002 15.8327 10Z"
                                                                    stroke="white" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path
                                                                    d="M12.1632 11.855L10.3549 10.7759C10.0399 10.5892 9.7832 10.14 9.7832 9.77253V7.38086"
                                                                    stroke="white" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                            </svg>
                                                        </span>
                                                    @endif
                                                @endif
                                            </div>
                                            @if ($eventInfo['guest_view']['is_host'] == 1 || $eventInfo['guest_view']['is_co_host'] == '1')
                                                @if ($guest['is_sync'] == '1')
                                                    <button type="button"><i class="fa-solid fa-ellipsis-vertical"
                                                            data-guest-id="{{ $guest['guest_id'] }}"
                                                            data-is_sync="{{ $guest['is_sync'] }}"></i></button>
                                                @else
                                                    <button type="button" data-bs-toggle="modal"
                                                        data-bs-target="#editrsvp3"><i
                                                            class="fa-solid fa-ellipsis-vertical edit_rsvp_guest"
                                                            data-guest-id="{{ $guest['guest_id'] }}"
                                                            data-is_sync="{{ $guest['is_sync'] }}"></i></button>
                                                @endif
                                            @endif
                                        </div>
                                    @endif
                                </div>
                                @if ($guest['is_sync'] == '0')
                                    @if ($guest['rsvp_status'] == '1')
                                        <div class="sucess-yes">
                                            <h5 class="green">YES</h5>
                                            <div class="sucesss-cat ms-auto">
                                                <svg width="15" height="15" viewBox="0 0 15 15"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                </svg>
                                                {{-- <h5>{{$guest['adults']}} Adults</h5>
                                                    <h5>{{$guest['kids']}} Kids</h5> --}}
                                                <h5>
                                                    {{ $guest['adults'] == 1 ? $guest['adults'] . ' Adult' : $guest['adults'] . ' Adults' }}
                                                </h5>
                                                <h5>
                                                    {{ $guest['kids'] == 1 ? $guest['kids'] . ' Kid' : $guest['kids'] . ' Kids' }}
                                                </h5>
                                            </div>
                                        </div>
                                    @elseif($guest['rsvp_status'] == '0')
                                        <div class="sucess-no">
                                            <h5>NO</h5>

                                        </div>
                                    @else
                                        <div class="no-reply">
                                            <h5>NO REPLY</h5>

                                        </div>
                                    @endif
                                @else
                                    @if ($guest['rsvp_status'] == '1')
                                        <div class="sucess-yes">
                                            <h5 class="green">YES</h5>
                                            <div class="sucesss-cat ms-auto">
                                                <svg width="15" height="15" viewBox="0 0 15 15"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                </svg>
                                                {{-- <h5>{{$guest['adults']}} Adults</h5>
                                                    <h5>{{$guest['kids']}} Kids</h5> --}}
                                                <h5>
                                                    {{ $guest['adults'] == 1 ? $guest['adults'] . ' Adult' : $guest['adults'] . ' Adults' }}
                                                </h5>
                                                <h5>
                                                    {{ $guest['kids'] == 1 ? $guest['kids'] . ' Kid' : $guest['kids'] . ' Kids' }}
                                                </h5>
                                            </div>
                                        </div>
                                    @elseif($guest['rsvp_status'] == '0')
                                        <div class="sucess-no">
                                            <h5>NO</h5>

                                        </div>
                                    @endif
                                @endif
                            </li>

                        @endif
                    @endforeach
                @endif



            </ul>
            <div class="guests-listing-buttons">
                <!-- <a href="javascript:void(0);" class="cmn-btn see-all-btn">See All</a> -->
                @if (count($guestArray) > 7)
                    <a href="javascript:void(0);" class="cmn-btn see-all-guest-right-btn"
                        data-eventId="{{ $eventId }}">See All</a>
                @endif
                @if ($eventInfo['guest_view']['is_host'] == 1 || $eventInfo['guest_view']['is_co_host'] == '1')
                    @php
                        $event_date = $eventInfo['guest_view']['event_date'];
                        $event_time = $eventInfo['guest_view']['event_time'];
                        $current_date = date('Y-m-d');
                    @endphp
                    @if ($event_date >= $current_date && strtotime($event_date . ' ' . $event_time) >= strtotime(date('Y-m-d g:i A')))
                        <button class="cmn-btn" type="button" id="allcontact"><i class="fa-solid fa-plus"></i> Add
                            Guest</button>
                    @endif
                    <!-- data-bs-toggle="modal"
                    data-bs-target="#addguest" -->
                @endif
            </div>
        </div>
    </div>

</div>

<div class="mx-auto adsense-center" id="ad-container" style="text-align: center;height:auto;min-height:auto;">  

    <ins id="my-adguest" class="adsbygoogle" style="display:block;height:100px"
        data-ad-client="ca-pub-7818976609984635" data-ad-slot="7204833835"
        data-ad-format="auto" data-full-width-responsive="true"></ins>
    <script>
        (adsbygoogle = window.adsbygoogle || []).push({});
    // if (!adElement.classList.contains('adsbygoogle-noablate')) {
    //     (adsbygoogle = window.adsbygoogle || []).push({});
    // }


    // Hide container if ad is not shown after 2 seconds
  
    </script>
    </div>
<div class="modal fade cmn-modal" id="editrsvp3" tabindex="-1" aria-labelledby="editrsvpLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="editrsvpLabel">Edit RSVP Details</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="guest-rsvp-head">
                    <div class="rsvp-img">
                        <img src="{{ asset('assets/front/img/rs-img.png') }}')}}" alt="rs-img">
                    </div>
                    <!-- <h5></h5> -->
                </div>
                <div class="guest-rsvp-incres">
                    <h6>Guests</h6>
                    <input type="hidden" id="user_id_rsvp" value="">
                    <input type="hidden" id="sync_rsvp" value="">
                    <div class="guest-edit-wrp">
                        <div class="guest-edit-box">
                            <p>Adults</p>
                            <div class="qty-container ms-auto">
                                <button class="qty-btn-minus side_menu_minus" type="button"><i
                                        class="fa fa-minus"></i></button>
                                <input type="number" name="adults" value="0" class="input-qty adultcount"
                                    readonly />
                                <button class="qty-btn-plus side_menu_plus" type="button"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="guest-edit-box">
                            <p>Kids</p>
                            <div class="qty-container ms-auto">
                                <button class="qty-btn-minus side_menu_minus" type="button"><i
                                        class="fa fa-minus"></i></button>
                                <input type="number" name="kids" value="0" class="input-qty kidcount"
                                    readonly />
                                <button class="qty-btn-plus  side_menu_plus" type="button"><i
                                        class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="guest-rsvp-attend">
                    <h6>RSVP</h6>
                    <div class="input-form">
                        <input type="radio" id="option4" name="rsvp_status" class="rsvp_yes" value="1" />
                        <label for="option4"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M10.5001 18.3334C15.0834 18.3334 18.8334 14.5834 18.8334 10.0001C18.8334 5.41675 15.0834 1.66675 10.5001 1.66675C5.91675 1.66675 2.16675 5.41675 2.16675 10.0001C2.16675 14.5834 5.91675 18.3334 10.5001 18.3334Z"
                                    stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M6.95825 9.99993L9.31659 12.3583L14.0416 7.6416" stroke="#23AA26"
                                    stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>Attending</label>
                    </div>
                    <div class="input-form">
                        <input type="radio" id="option3" name="rsvp_status" value="0" class="rsvp_no" />
                        <label for="option3"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20"
                                fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <button type="button" class="btn btn-secondary remove-btn remove-Rsvp-btn"
                    data-bs-dismiss="modal">Remove
                    Guest</button>
                <button type="button" class="btn btn-secondary save-rsvp">Update</button>
            </div>
        </div>
    </div>
</div>
<!-- ========= Add-guest ======== -->
<div class="modal fade cmn-modal" id="addguest" tabindex="-1" aria-labelledby="addguestLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addguestLabel">Add Guests</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body guest-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link see_invite_nav_yesvite active" id="home-tab" data-bs-toggle="tab"
                            data-bs-target="#home" type="button" role="tab" aria-controls="home"
                            aria-selected="true">Yestive
                            Contacts</button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link see_invite_nav_phone" id="profile-tab" data-bs-toggle="tab"
                            data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                            aria-selected="false">Phone
                            Contacts</button>
                    </li>
                </ul>
                <div class="tab-content GuestTabContent" id="myTabContent">

                </div>
            </div>
            {{-- <input type="hidden" id="event_id" value=""> --}}
            <div class="modal-footer rsvp-button-wrp">
                <button type="button" class="btn btn-secondary success-btn add_guest" data-bs-dismiss="modal"
                    disabled>Send New Invites</button>
            </div>
        </div>
    </div>
</div>

<div class="modal fade cmn-modal" id="seeAll" tabindex="-1" aria-labelledby="seeAllLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="seeAllLabel">Add Guests</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body guest-tab">
                <div class="tab-content seeAllTabContent" id="seeAllTabContent">

                </div>
            </div>
            {{-- <input type="hidden" id="event_id" value=""> --}}
            <!-- <div class="modal-footer rsvp-button-wrp">
                <button type="button" class="btn btn-secondary success-btn"
                    data-bs-dismiss="modal">Re-send</button>
            </div> -->
        </div>
    </div>
</div>
</div>
</div>



<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Get the "See All" button and the guest list container
        const seeAllBtn = document.getElementById('seeAllBtn');
        const guestList = document.getElementById('guestList');
        // alert(1);
        // Assuming $guestArray is passed as a JSON object to JavaScript
        const guests = @json($guestArray); // Make sure to use Blade to pass PHP data to JS
        console.log(guests)
        // Add an event listener for the "See All" button
        seeAllBtn.addEventListener('click', function() {
            // Hide the "See All" button after it's clicked
            seeAllBtn.style.display = 'none';

            // Loop through the guests array and append the remaining guests
            guests.slice(7).forEach((guest, index) => {
                if (guest.user) {
                    const user = guest.user;
                    const firstInitial = user.firstname ? user.firstname[0].toUpperCase() : '';
                    const secondInitial = user.lastname ? user.lastname[0].toUpperCase() : '';
                    const initials = firstInitial + secondInitial;
                    const fontColor = 'fontcolor' + firstInitial.toUpperCase();

                    // Create a new list item for the guest
                    const listItem = document.createElement('li');
                    listItem.classList.add('guests-listing-info', 'contact', 'contactslist');
                    listItem.setAttribute('data-guest-id', guest.id);

                    // Construct the HTML for the new guest list item
                    listItem.innerHTML = `
                    <div class="posts-card-head-left guests-listing-left">
                        <div class="posts-card-head-left-img">
                            ${user.profile ?
                                `<img src="/storage/profile/${user.profile}" alt="">` :
                                `<h5 class="${fontColor}">${initials}</h5>`
                            }
                            <span class="active-dot"></span>
                        </div>
                        <div class="posts-card-head-left-content contact_search" data-search="${user.firstname} ${user.lastname}">
                            <h3>${user.firstname} ${user.lastname}</h3>
                            ${user.city || user.state ?
                                `<p>${user.city || ''} ${user.city && user.state ? ',' : ''} ${user.state || ''}</p>` : ''}
                            <input type="hidden" id="eventID" value="${guest.event_id}">
                            <input type="hidden" id="user_id" value="${guest.user_id}">
                        </div>
                    </div>
                `;

                    // Append the new guest to the guest list
                    guestList.appendChild(listItem);
                }
            });
        });
    });
</script>
