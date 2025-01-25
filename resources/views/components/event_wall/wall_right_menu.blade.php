{{-- {{dd($eventInfo)}} --}}
@php

    $guestArray = $eventInfo['guest_view']['event_detail']['guests'] ?? null;
    $totalAdults = 0;
    $totalKids = 0;
    // dd( $guestArray);

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
        <div class="main-right-guests-head">
            <h3>Guests</h3>
            <p>20 Active</p>
        </div>
        @php
            // Safely fetch the value of 'event_detail[2]' or set it to null if it doesn't exist
$guestArray = $eventInfo['guest_view']['event_detail']['guests'] ?? null;

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
                    <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                <h4>{{ $totalAdults }} <span>Adults</span></h4>
                <h4>{{ $totalKids }} <span>Kids</span></h4>
            </div>
        </div>
        <div class="all-events-searchbar-wrp">
            <form>
                <div class="position-relative">
                    <input type="text" class="form-control" id="text" placeholder="Search name">
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
        <div class="guests-listing-wrp">
            <ul>
                @if (!empty($guestArray))
                @foreach ($guestArray as $guest)
                    @if (!empty($guest['user']))
                        @php
                            $user = $guest['user']; // Fetch user array
                            $firstInitial = isset($user['firstname'][0]) ? strtoupper($user['firstname'][0]) : '';
                            $secondInitial = isset($user['lastname'][0]) ? strtoupper($user['lastname'][0]) : '';
                            $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                            $fontColor = 'fontcolor' . strtoupper($firstInitial);
                        @endphp
                        <li class="guests-listing-info">
                            <div class="posts-card-head-left guests-listing-left">
                                <div class="posts-card-head-left-img">
                                    @if (!empty($user['profile']))
                                        <img src="{{ asset('storage/profile/' . $user['profile']) }}" alt="">
                                    @else
                                        <h5 class="{{ $fontColor }}">
                                            {{ $initials }}
                                        </h5>
                                    @endif
                                    <span class="active-dot"></span>
                                </div>
                                <div class="posts-card-head-left-content">
                                    <h3>{{ $user['firstname'] }} {{ $user['lastname'] }}</h3>
                                    @if (!empty($user['city']) || !empty($user['state']))
                                        <p>
                                            {{ $user['city'] ?? '' }}{{ !empty($user['city']) && !empty($user['state']) ? ',' : '' }}{{ $user['state'] ?? '' }}
                                        </p>
                                    @endif


                                </div>
                            </div>
                            <div class="guests-listing-right">
                                @if ($guest['rsvp_status'] == '1')
                                    <!-- Approved -->
                                    <span id="approve">
                                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                    <span id="cancel">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect width="20" height="20" rx="10" fill="#E03137" />
                                            <path d="M5.91797 5.91663L14.0841 14.0827" stroke="white"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M5.91787 14.0827L14.084 5.91663" stroke="white"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                @else
                                    <!-- Pending -->
                                    <span id="pending">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <rect width="20" height="20" rx="10" fill="#94A3B8" />
                                            <path
                                                d="M15.8327 10C15.8327 13.22 13.2193 15.8334 9.99935 15.8334C6.77935 15.8334 4.16602 13.22 4.16602 10C4.16602 6.78002 6.77935 4.16669 9.99935 4.16669C13.2193 4.16669 15.8327 6.78002 15.8327 10Z"
                                                stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                            <path
                                                d="M12.1632 11.855L10.3549 10.7759C10.0399 10.5892 9.7832 10.14 9.7832 9.77253V7.38086"
                                                stroke="white" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                @endif
                                <button type="button" data-bs-toggle="modal" data-bs-target="#editrsvp"><i
                                        class="fa-solid fa-ellipsis-vertical"></i></button>
                            </div>
                        </li>
                    @endif
                @endforeach
                @endif



            </ul>
            <div class="guests-listing-buttons">
                <a href="" class="cmn-btn see-all-btn">See All</a>
                <button class="cmn-btn" type="button" id="allcontact" data-bs-toggle="modal" data-bs-target="#addguest"><i
                        class="fa-solid fa-plus"></i> Add Guest</button>
            </div>
        </div>
    </div>

</div>
{{-- <div class="modal fade cmn-modal" id="editrsvp" tabindex="-1" aria-labelledby="editrsvpLabel" aria-hidden="true">
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
                    <h5>Tiana Dokidis</h5>
                </div>
                <div class="guest-rsvp-incres">
                    <h6>Guests</h6>
                    <div class="guest-edit-wrp">
                        <div class="guest-edit-box">
                            <p>Adults</p>
                            <div class="qty-container ms-auto">
                                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                <input type="number" name="qty" value="0" class="input-qty" />
                                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                        <div class="guest-edit-box">
                            <p>Kids</p>
                            <div class="qty-container ms-auto">
                                <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                <input type="number" name="qty" value="0" class="input-qty" />
                                <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="guest-rsvp-attend">
                    <h6>RSVP</h6>
                    <div class="input-form">
                        <input type="radio" id="option1" name="foo" checked />
                        <label for="option1"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20"
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
                        <input type="radio" id="option2" name="foo" />
                        <label for="option2"><svg class="me-2" width="21" height="20" viewBox="0 0 21 20"
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
                <button type="button" class="btn btn-secondary remove-btn" data-bs-dismiss="modal">Remove
                    Guest</button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Update</button>
            </div>
        </div>
    </div>
</div> --}}
<!-- ========= Add-guest ======== -->
<div class="modal fade cmn-modal" id="addguest" tabindex="-1" aria-labelledby="addguestLabel" aria-hidden="true" >
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title" id="addguestLabel">Add Guests</h4>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body guest-tab">
                <ul class="nav nav-tabs" id="myTab" role="tablist">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="home-tab" data-bs-toggle="tab" data-bs-target="#home"
                            type="button" role="tab" aria-controls="home" aria-selected="true">Yestive
                            Contacts</button>
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
                        <div class="guest-users-wrp selected-contacts-list">
                            <div class="guest-user">
                                <div class="guest-user-img">
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="guest-user-list-wrp invite-contact-wrp yesvite_contact">
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
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="" checked>
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
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="" checked>
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
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="" checked>
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
                                        <input class="form-check-input failed-checkout" type="checkbox"
                                            value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                        <div class="guest-users-wrp selected-phone-list" >
                            <div class="guest-user">
                                <div class="guest-user-img">
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <img src="{{ asset('assets/front/img/event-story-img-1.png') }}" alt="guest-img">
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
                                    <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </span>
                        </div>
                        <div class="guest-user-list-wrp invite-contact-wrp phone_contact">
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
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="" checked>
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
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="" checked>
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
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="" checked>
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
                                        <input class="form-check-input failed-checkout" type="checkbox"
                                            value="">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            {{-- <input type="hidden" id="event_id" value=""> --}}
            <div class="modal-footer rsvp-button-wrp">
                <button type="button" class="btn btn-secondary success-btn add_guest" data-bs-dismiss="modal">Re-send</button>
            </div>
        </div>
    </div>
</div>
