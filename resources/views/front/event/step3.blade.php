<div class="step_3" style="display: none;width:100%;">
    <div class="main-content-wrp guest-main-content">
        <div class="guest-wrapper position-relative">
            <div class="guest-content new-guest-content-wrp">
                <h4>Guests</h4>
                <div class="contact-tab contact-user">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" data-isHost="0" id="contact-tab" data-bs-toggle="tab"
                                data-bs-target="#contact" type="button" role="tab" aria-controls="#contact"
                                aria-selected="true">Yestive</button>
                        </li>
                         <li class="nav-item" role="presentation">
                            <button class="nav-link" id="phone-tab" data-bs-toggle="tab" data-bs-target="#phone"
                                type="button" role="tab" aria-controls="phone" aria-selected="false">Phone</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="group-tab" data-bs-toggle="tab" data-bs-target="#group"
                                type="button" role="tab" aria-controls="group"
                                aria-selected="false">Groups</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="contact" role="tabpanel"
                            aria-labelledby="contact-tab">

                            <!-- ======= guest-users mobile ======== -->
                            <div class="guest-contacts-wrp user-list-responsive_yesvite">
                                @if (Session::get('user_ids') != null && count(Session::get('user_ids')) > 0)
                                    @php
                                        $counter = 0;
                                    @endphp


                                    @foreach (Session::get('user_ids') as $guest_user)
                                        @php
                                            $counter++;
                                            if ($counter > 4) {
                                                break;
                                            }
                                        @endphp
                                        @if ($guest_user['prefer_by'] == 'email')
                                            <div class="guest-contact invited_user user_id-{{ $guest_user['id'] }} responsive_invite_user"
                                                data-id={{ $guest_user['id'] }} id="">
                                                <div class="guest-img mobile-guest-icon">
                                                    <!-- <img src="./assets/image/user-img.svg" alt="guest-img"> -->
                                                    @if ($guest_user['profile'] != '')
                                                        <img src="{{ asset('storage/profile/' . $guest_user['profile']) }}"
                                                            alt="user-img">
                                                    @else
                                                        @php
                                                            $firstInitial = !empty($guest_user['firstname'])
                                                                ? strtoupper($guest_user['firstname'][0])
                                                                : '';
                                                            $lastInitial = !empty($guest_user['lastname'])
                                                                ? strtoupper($guest_user['lastname'][0])
                                                                : '';
                                                            $initials = $firstInitial . $lastInitial;
                                                            $fontColor = 'fontcolor' . $firstInitial;
                                                        @endphp
                                                        <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                    @endif
                                                    @if (!isset($guest_user['isAlready']))
                                                    <a href="#" class="close" id="delete_invited_user"
                                                        data-id="user-{{ $guest_user['id'] }}"
                                                        data-userid="{{ $guest_user['id'] }}">
                                                        <svg width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="1.8999" y="1" width="16" height="16"
                                                                rx="8" fill="#F73C71" />
                                                            <rect x="1.8999" y="1" width="16" height="16"
                                                                rx="8" stroke="white" stroke-width="2" />
                                                            <path d="M7.56689 6.66699L12.2332 11.3333" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M7.56656 11.3333L12.2329 6.66699" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                    @endif
                                                </div>
                                                <h6 class="guest-name">{{ $guest_user['firstname'] }}
                                                    {{ $guest_user['lastname'] }}</h6>
                                            </div>
                                        @elseif ($guest_user['prefer_by'] == 'phone')
                                            <div class="guest-contact invited_user user_id_tel-{{ $guest_user['id'] }} responsive_invite_user"
                                                data-id={{ $guest_user['id'] }} id="">
                                                <div class="guest-img mobile-guest-icon">
                                                    <!-- <img src="./assets/image/user-img.svg" alt="guest-img"> -->
                                                    @if ($guest_user['profile'] != '')
                                                        <img src="{{ asset('storage/profile/' . $guest_user['profile']) }}"
                                                            alt="user-img">
                                                    @else
                                                        @php
                                                            $firstInitial = !empty($guest_user['firstname'])
                                                                ? strtoupper($guest_user['firstname'][0])
                                                                : '';
                                                            $lastInitial = !empty($guest_user['lastname'])
                                                                ? strtoupper($guest_user['lastname'][0])
                                                                : '';
                                                            $initials = $firstInitial . $lastInitial;
                                                            $fontColor = 'fontcolor' . $firstInitial;
                                                        @endphp
                                                        <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                    @endif
                                                    @if (!isset($guest_user['isAlready']))
                                                    <a href="#" class="close" id="delete_invited_user_tel"
                                                        data-id="user_tel-{{ $guest_user['id'] }}"
                                                        data-userid="{{ $guest_user['id'] }}">
                                                        <svg width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="1.8999" y="1" width="16" height="16"
                                                                rx="8" fill="#F73C71" />
                                                            <rect x="1.8999" y="1" width="16" height="16"
                                                                rx="8" stroke="white" stroke-width="2" />
                                                            <path d="M7.56689 6.66699L12.2332 11.3333" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M7.56656 11.3333L12.2329 6.66699" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>
                                                    @endif
                                                </div>
                                                <h6 class="guest-name">{{ $guest_user['firstname'] }}
                                                    {{ $guest_user['lastname'] }}</h6>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if ($counter > 4)
                                        @php
                                            $counter = count(Session::get('user_ids')) - 4;
                                        @endphp
                                        <div class="guest-contact all_user_list">
                                            <div class="guest-img">
                                                <span class="update_user_count">+{{ $counter }}</span>
                                            </div>
                                            <span class="all-contact">See all</span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="popular-list">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h5>Groups</h5>
                                    <a href="#" class="see_all_group">See All</a>
                                </div>
                            </div>


                            <div>
                                <!-- <div class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($groups as $group)
                                            <div class="swiper-slide">
                                                <div class="group-card view_members" data-id="{{ $group->id }}">
                                                    <div>
                                                        <h4>{{ $group->name }}</h4>
                                                        <p>{{ $group->group_members_count }} Guests</p>
                                                    </div>
                                                    <span class="ms-auto">
                                                        <svg width="16" height="17" viewBox="0 0 16 17"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973"
                                                                stroke="#E2E8F0" stroke-width="1.5"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                                    <div class="swiper-button-prev"><i class="fa-solid fa-angle-left"></i></div>

                                </div> -->
                                <div class="slider-container">
    <button class="slider-button prev">&lt;</button>
    <div class="slider-wrapper">
        @foreach ($groups as $group)
            <div class="slider-slide">
                <h4>{{ $group->name }}</h4>
                <p>{{ $group->group_members_count }} Guests</p>
            </div>
        @endforeach
    </div>
    <button class="slider-button next">&gt;</button>
</div>


                            </div>

                            <div class="position-relative">
                                <input type="search" class="search_user search_user_ajax" placeholder="Search name"
                                    class="form-control">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            <div class="user-contacts" id="inviteUser">

                            </div>
                            <div class="user-contacts" id="YesviteUserAll">

                            </div>
                            @if ((isset($eventDetail['is_draft_save']) && $eventDetail['is_draft_save']=="0") && (isset($eventDetail['id']) && $eventDetail['id']!="") )
                            <div class="guest-checkout new-edit-save-btn">
                                <div>
                                    <a href="#" class="cmn-btn edit_checkout" onclick="savePage4Data()">Save Changes</a>
                                </div>
                            </div>
                            @else
                            <div class="design-seting">
                                <a href="#" class="d-flex">
                                    <span>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.0002 13.2797L5.65355 8.93306C5.14022 8.41973 5.14022 7.57973 5.65355 7.06639L10.0002 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <h5 class="ms-2 edit_event_details">Edit Event Details</h5>
                                </a>
                                <a href="#" class="d-flex" id="next_setting">
                                    <h5 class="me-2">Next: Settings</h5>
                                    <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.93994 13.2797L10.2866 8.93306C10.7999 8.41973 10.7999 7.57973 10.2866 7.06639L5.93994 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                                
                            @endif
                        </div>
                        <div class="tab-pane fade" id="phone" role="tabpanel" aria-labelledby="phone-tab">

                            <!-- ======= guest-users mobile ======== -->
                            <div class="guest-contacts-wrp user-list-responsive_phone">
                                @if (Session::get('contact_ids') != null && count(Session::get('contact_ids')) > 0)
                                    @php
                                        $counter = 0;
                                    @endphp


                                    @foreach (Session::get('contact_ids') as $guest_user)
                                        @php
                                            $counter++;
                                            if ($counter > 4) {
                                                break;
                                            }
                                        @endphp
                                        @if ($guest_user['prefer_by'] == 'email')
                                            <div class="guest-contact invited_user sync_id-{{ $guest_user['sync_id'] }} responsive_invite_user_contact"
                                                data-id={{ $guest_user['sync_id'] }} id="">
                                                <div class="guest-img mobile-guest-icon">
                                                    <!-- <img src="./assets/image/user-img.svg" alt="guest-img"> -->
                                                    @if ($guest_user['profile'] != '')
                                                        <img src="{{ $guest_user['profile'] }}" alt="user-img">
                                                    @else
                                                        @php
                                                            $firstInitial = !empty($guest_user['firstname'])
                                                                ? strtoupper($guest_user['firstname'][0])
                                                                : '';
                                                            $lastInitial = !empty($guest_user['lastname'])
                                                                ? strtoupper($guest_user['lastname'][0])
                                                                : '';
                                                            $initials = $firstInitial . $lastInitial;
                                                            $fontColor = 'fontcolor' . $firstInitial;
                                                        @endphp
                                                        <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                    @endif
                                                    @if (!isset($guest_user['isAlready']))
                                                        <a href="#" class="close" id="delete_invited_user_tel"
                                                            data-id="sync_-{{ $guest_user['sync_id'] }}" data-contact="1"
                                                            data-userid="{{ $guest_user['sync_id'] }}">
                                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <rect x="1.8999" y="1" width="16" height="16"
                                                                    rx="8" fill="#F73C71" />
                                                                <rect x="1.8999" y="1" width="16" height="16"
                                                                    rx="8" stroke="white" stroke-width="2" />
                                                                <path d="M7.56689 6.66699L12.2332 11.3333" stroke="white"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M7.56656 11.3333L12.2329 6.66699" stroke="white"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </a>  
                                                    @endif
                                                </div>
                                                <h6 class="guest-name">{{ $guest_user['firstname'] }}
                                                    {{ $guest_user['lastname'] }}</h6>
                                            </div>
                                        @elseif ($guest_user['prefer_by'] == 'phone')
                                            <div class="guest-contact invited_user sync_user_id_tel-{{ $guest_user['sync_id'] }} responsive_invite_user"
                                                data-id={{ $guest_user['sync_id'] }} id="">
                                                <div class="guest-img mobile-guest-icon">
                                                    @if ($guest_user['profile'] != '')
                                                        <img src="{{ $guest_user['profile'] }}" alt="user-img">
                                                    @else
                                                        @php
                                                            $firstInitial = !empty($guest_user['firstname'])
                                                                ? strtoupper($guest_user['firstname'][0])
                                                                : '';
                                                            $lastInitial = !empty($guest_user['lastname'])
                                                                ? strtoupper($guest_user['lastname'][0])
                                                                : '';
                                                            $initials = $firstInitial . $lastInitial;
                                                            $fontColor = 'fontcolor' . $firstInitial;
                                                        @endphp
                                                        <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                                    @endif
                                                    @if (!isset($guest_user['isAlready']))
                                                    <a href="#" class="close" id="delete_invited_user_tel"
                                                        data-id="sync_tel-{{ $guest_user['sync_id'] }}"
                                                        data-contact="1" data-userid="{{ $guest_user['sync_id'] }}">
                                                        <svg width="19" height="18" viewBox="0 0 19 18"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <rect x="1.8999" y="1" width="16" height="16"
                                                                rx="8" fill="#F73C71" />
                                                            <rect x="1.8999" y="1" width="16" height="16"
                                                                rx="8" stroke="white" stroke-width="2" />
                                                            <path d="M7.56689 6.66699L12.2332 11.3333" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M7.56656 11.3333L12.2329 6.66699" stroke="white"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </a>

                                                    @endif
                                                </div>
                                                <h6 class="guest-name">{{ $guest_user['firstname'] }}
                                                    {{ $guest_user['lastname'] }}</h6>
                                            </div>
                                        @endif
                                    @endforeach

                                    @if ($counter > 4)
                                        @php
                                            $counter = count(Session::get('contact_ids')) - 4;
                                        @endphp
                                        <div class="guest-contact all_user_list" data-contact="1">
                                            <div class="guest-img">
                                                <span class="update_user_count">+{{ $counter }}</span>
                                            </div>
                                            <span class="all-contact">See all</span>
                                        </div>
                                    @endif
                                @endif
                            </div>

                            <div class="popular-list">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h5>Groups</h5>
                                    <a href="#" class="see_all_group">See All</a>
                                </div>
                            </div>


                            <div>

                                <div class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($groups as $group)
                                            <div class="swiper-slide">
                                                <div class="group-card view_members" data-id="{{ $group->id }}">
                                                    <div>
                                                        <h4>{{ $group->name }}</h4>
                                                        <p>{{ $group->group_members_count }} Guests</p>
                                                    </div>
                                                    <span class="ms-auto">
                                                        <svg width="16" height="17" viewBox="0 0 16 17"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973"
                                                                stroke="#E2E8F0" stroke-width="1.5"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                    <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                                    <div class="swiper-button-prev"><i class="fa-solid fa-angle-left"></i></div>

                                </div>
                            </div>


                            <div class="position-relative">
                                <input type="search" id="search_contacts" placeholder="Search name"
                                    class="form-control">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>

                            <div class="user-contacts" id="YesviteContactsAll">


                            </div>

                            @if ((isset($eventDetail['is_draft_save']) && $eventDetail['is_draft_save']=="0") && (isset($eventDetail['id']) && $eventDetail['id']!="") )
                            <div class="guest-checkout new-edit-save-btn">
                                <div>
                                    <a href="#" class="cmn-btn edit_checkout" onclick="savePage4Data()">Save Changes</a>
                                </div>
                            </div> 
                            @else      
                            <div class="design-seting">
                                <a href="#" class="d-flex">
                                    <span>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.0002 13.2797L5.65355 8.93306C5.14022 8.41973 5.14022 7.57973 5.65355 7.06639L10.0002 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <h5 class="ms-2 edit_event_details">Edit Event Details</h5>
                                </a>
                                <a href="#" class="d-flex" id="next_setting">
                                    <h5 class="me-2">Next: Settings</h5>
                                    <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.93994 13.2797L10.2866 8.93306C10.7999 8.41973 10.7999 7.57973 10.2866 7.06639L5.93994 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                            @endif
                        </div>
                        <div class="tab-pane fade" id="group" role="tabpanel" aria-labelledby="group-tab">

                            <!-- ======= guest-users mobile ======== -->
                            {{-- <div class="guest-contacts-wrp  user-list-responsive">
3
    </div> --}}

                            <div class="popular-list">
                                <div class="d-flex justify-content-between align-items-center w-100">
                                    <h5>Groups</h5>
                                    <a href="#" class="see_all_group">See All</a>
                                </div>
                            </div>


                            <div>

                                <div class="swiper mySwiper">
                                    <div class="swiper-wrapper">
                                        @foreach ($groups as $group)
                                            <div class="swiper-slide">
                                                <div class="group-card view_members" data-id="{{ $group->id }}">
                                                    <div>
                                                        <h4>{{ $group->name }}</h4>
                                                        <p>{{ $group->group_members_count }} Guests</p>
                                                    </div>
                                                    <span class="ms-auto">
                                                        <svg width="16" height="17" viewBox="0 0 16 17"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973"
                                                                stroke="#E2E8F0" stroke-width="1.5"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        @endforeach

                                    </div>
                                    <div class="swiper-button-next"><i class="fa-solid fa-chevron-right"></i></div>
                                    <div class="swiper-button-prev"><i class="fa-solid fa-angle-left"></i></div>

                                </div>
                            </div>


                            <div class="position-relative">
                                <input type="search" placeholder="Search group names" class="form-control"
                                    id="group_search_ajax" name="group_search_ajax">
                                <span>
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                    </svg>
                                </span>
                            </div>
                            <div class="user-contacts group_list group_search_list">
                                @if($groups->isEmpty())
                                     No data found
                                @else
                                @foreach ($groups as $group)
                                    <div class="group-card added_group{{ $group->id }} listgroups view_members"
                                        data-id="{{ $group->id }}">
                                        <div class="view_members" data-id="{{ $group->id }}">
                                            <h4>{{ $group->name }}</h4>
                                            <p>{{ $group->group_members_count }} Guests</p>
                                        </div>
                                        <span class="ms-auto me-3">
                                            <svg width="16"  id="delete_group" data-id="{{$group->id}}" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path d="M14 4.48665C11.78 4.26665 9.54667 4.15332 7.32 4.15332C6 4.15332 4.68 4.21999 3.36 4.35332L2 4.48665" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path d="M5.66699 3.81301L5.81366 2.93967C5.92033 2.30634 6.00033 1.83301 7.12699 1.83301H8.87366C10.0003 1.83301 10.087 2.33301 10.187 2.94634L10.3337 3.81301" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path d="M12.5669 6.59375L12.1336 13.3071C12.0603 14.3537 12.0003 15.1671 10.1403 15.1671H5.86026C4.00026 15.1671 3.94026 14.3537 3.86693 13.3071L3.43359 6.59375" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round">
                                                </path>
                                                <path d="M6.88672 11.5H9.10672" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                                <path d="M6.33301 8.83301H9.66634" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                        <span class="view_members" data-id="{{ $group->id }}">
                                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M5.94043 13.7797L10.2871 9.43306C10.8004 8.91973 10.8004 8.07973 10.2871 7.56639L5.94043 3.21973"
                                                    stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                                    stroke-linecap="round" stroke-linejoin="round"></path>
                                            </svg>
                                        </span>
                                    </div>
                                @endforeach
                            @endif
                            </div>
                            @if ((isset($eventDetail['is_draft_save']) && $eventDetail['is_draft_save']=="0") && (isset($eventDetail['id']) && $eventDetail['id']!="") )
                            <div class="guest-checkout new-edit-save-btn">
                                <div>
                                    <a href="#" class="cmn-btn edit_checkout" onclick="savePage4Data()">Save Changes</a>
                                </div>
                            </div>
                            @else
                             
                            <div class="design-seting">
                                <a href="#" class="d-flex">
                                    <span>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.0002 13.2797L5.65355 8.93306C5.14022 8.41973 5.14022 7.57973 5.65355 7.06639L10.0002 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                    <h5 class="ms-2 edit_event_details">Edit Event Details</h5>
                                </a>
                                <a href="#" class="d-flex" id="next_setting">
                                    <h5 class="me-2">Next: Settings</h5>
                                    <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.93994 13.2797L10.2866 8.93306C10.7999 8.41973 10.7999 7.57973 10.2866 7.06639L5.93994 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </a>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                <div class="invite-pro">
                    <div class="invite-left">
                        <h6 class="invite-count">0</h6>
                        <p class="invite-left_d"><strong>Invites | <span class="available-coins">{{$coins}}</span></strong> Left</p>
                    </div>
                    <div class="invite-right">
                        <span><strong>15</strong>Guests or less</span>
                    </div>
                    <a href="#" class="edit-icon">
                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M11.05 4.66701L4.20829 11.9087C3.94996 12.1837 3.69996 12.7253 3.64996 13.1003L3.34162 15.8003C3.23329 16.7753 3.93329 17.442 4.89996 17.2753L7.58329 16.817C7.95829 16.7503 8.48329 16.4753 8.74162 16.192L15.5833 8.95034C16.7666 7.70034 17.3 6.27534 15.4583 4.53368C13.625 2.80868 12.2333 3.41701 11.05 4.66701Z"
                                stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M9.9082 5.875C10.2665 8.175 12.1332 9.93333 14.4499 10.1667" stroke="black"
                                stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </a>
                </div>

            </div>

        </div>
        <div class="guest-users">
            <div class="guest-user-content">
                <h4 id="event_guest_count">
                    0 Guests
                </h4>
                <div class="guest-wrp d-none">
                    <div class="guest-pro">
                        <div>
                            <h5>Free</h5>
                            <h6>free</h6>
                        </div>
                        <a href="#" class="edit-icon">
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M11.05 4.66701L4.20829 11.9087C3.94996 12.1837 3.69996 12.7253 3.64996 13.1003L3.34162 15.8003C3.23329 16.7753 3.93329 17.442 4.89996 17.2753L7.58329 16.817C7.95829 16.7503 8.48329 16.4753 8.74162 16.192L15.5833 8.95034C16.7666 7.70034 17.3 6.27534 15.4583 4.53368C13.625 2.80868 12.2333 3.41701 11.05 4.66701Z"
                                    stroke="black" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M9.9082 5.875C10.2665 8.175 12.1332 9.93333 14.4499 10.1667" stroke="black"
                                    stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                    <div class="invite-pro">
                        <div class="invite-left">
                            <h6 class="invite-count">0</h6>
                            {{-- <p id="invite-left"><strong>0</strong>Left</p> --}}
                            <input type="hidden" id="event_guest_left_count" />
                            <p class="invite-left_d"><strong>Invites |  <span class="available-coins">{{$coins}}</span></strong> Left</p>
                            <input type="hidden" id="currentInviteCount" value="0">
                        </div>
                        <div class="invite-right">
                            <span><strong>15</strong>Guests or less</span>
                        </div>
                    </div>
                </div>
                <div class="user-contacts inivted_user_list">
                    @if (Session::get('user_ids') != null && count(Session::get('user_ids')) > 0)

                        @foreach (Session::get('user_ids') as $guest_user)
                            @if ($guest_user['prefer_by'] == 'email')
                                <div class="users-data invited_users" data-id={{ $guest_user['id'] }}
                                    id="user-{{ $guest_user['id'] }}">
                                    {{-- <input type="hidden" class="duplicate" value="{{$is_duplicate}}"/> --}}
                                    <div class="d-flex align-items-start">
                                        <div class="contact-img">
                                            @if ($guest_user['profile'] != '')
                                                <img src="{{ asset('storage/profile/' . $guest_user['profile']) }}"
                                                    alt="user-img">
                                            @else
                                                @php
                                                    $firstInitial = !empty($guest_user['firstname'])
                                                        ? strtoupper($guest_user['firstname'][0])
                                                        : '';
                                                    $lastInitial = !empty($guest_user['lastname'])
                                                        ? strtoupper($guest_user['lastname'][0])
                                                        : '';
                                                    $initials = $firstInitial . $lastInitial;
                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                @endphp
                                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                            @endif
                                        </div>
                                        <div class="text-start">
                                            <h5>{{ $guest_user['firstname'] }} {{ $guest_user['lastname'] }}</h5>
                                            <div>
                                                <a href="#"><svg class="me-1" width="14" height="14"
                                                        viewBox="0 0 14 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path
                                                            d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    {{ $guest_user['invited_by'] }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!isset($guest_user['isAlready']))
                                    <div>
                                        <a href="#" id="delete_invited_user"
                                            data-id="user-{{ $guest_user['id'] }}"
                                            data-userid="{{ $guest_user['id'] }}">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5584 4.35866C16.2167 4.22533 14.875 4.12533 13.525 4.05033V4.04199L13.3417 2.95866C13.2167 2.19199 13.0334 1.04199 11.0834 1.04199H8.90005C6.95838 1.04199 6.77505 2.14199 6.64172 2.95033L6.46672 4.01699C5.69172 4.06699 4.91672 4.11699 4.14172 4.19199L2.44172 4.35866C2.09172 4.39199 1.84172 4.70033 1.87505 5.04199C1.90838 5.38366 2.20838 5.63366 2.55838 5.60033L4.25838 5.43366C8.62505 5.00033 13.0251 5.16699 17.4417 5.60866C17.4667 5.60866 17.4834 5.60866 17.5084 5.60866C17.8251 5.60866 18.1 5.36699 18.1334 5.04199C18.1584 4.70033 17.9084 4.39199 17.5584 4.35866Z"
                                                    fill="#F73C71"></path>
                                                <path
                                                    d="M16.025 6.78301C15.825 6.57467 15.55 6.45801 15.2666 6.45801H4.73329C4.44995 6.45801 4.16662 6.57467 3.97495 6.78301C3.78329 6.99134 3.67495 7.27467 3.69162 7.56634L4.20829 16.1163C4.29995 17.383 4.41662 18.9663 7.32495 18.9663H12.675C15.5833 18.9663 15.7 17.3913 15.7916 16.1163L16.3083 7.57467C16.325 7.27467 16.2166 6.99134 16.025 6.78301ZM11.3833 14.7913H8.60829C8.26662 14.7913 7.98329 14.508 7.98329 14.1663C7.98329 13.8247 8.26662 13.5413 8.60829 13.5413H11.3833C11.725 13.5413 12.0083 13.8247 12.0083 14.1663C12.0083 14.508 11.725 14.7913 11.3833 14.7913ZM12.0833 11.458H7.91662C7.57495 11.458 7.29162 11.1747 7.29162 10.833C7.29162 10.4913 7.57495 10.208 7.91662 10.208H12.0833C12.425 10.208 12.7083 10.4913 12.7083 10.833C12.7083 11.1747 12.425 11.458 12.0833 11.458Z"
                                                    fill="#F73C71"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            @elseif ($guest_user['prefer_by'] == 'phone')
                                <div class="users-data invited_users" data-id={{ $guest_user['id'] }}
                                    id="user_tel-{{ $guest_user['id'] }}">
                                    {{-- <input type="hidden" class="duplicate" value="{{$is_duplicate}}"/> --}}
                                    <div class="d-flex align-items-start">
                                        <div class="contact-img">
                                            @if ($guest_user['profile'] != '')
                                                <img src="{{ asset('storage/profile/' . $guest_user['profile']) }}"
                                                    alt="user-img">
                                            @else
                                                @php
                                                    $firstInitial = !empty($guest_user['firstname'])
                                                        ? strtoupper($guest_user['firstname'][0])
                                                        : '';
                                                    $lastInitial = !empty($guest_user['lastname'])
                                                        ? strtoupper($guest_user['lastname'][0])
                                                        : '';
                                                    $initials = $firstInitial . $lastInitial;
                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                @endphp
                                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                            @endif
                                        </div>
                                        <div class="text-start">
                                            <h5>{{ $guest_user['firstname'] }} {{ $guest_user['lastname'] }}</h5>
                                            <div>

                                                <a href="#">
                                                    <svg width="14" class="me-1" height="14"
                                                        viewBox="0 0 14 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                                            fill="black" />
                                                    </svg>
                                                    {{ $guest_user['invited_by'] }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!isset($guest_user['isAlready']))
                                    <div>
                                        <a href="#" id="delete_invited_user_tel"
                                            data-id="user_tel-{{ $guest_user['id'] }}"
                                            data-userid="{{ $guest_user['id'] }}">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5584 4.35866C16.2167 4.22533 14.875 4.12533 13.525 4.05033V4.04199L13.3417 2.95866C13.2167 2.19199 13.0334 1.04199 11.0834 1.04199H8.90005C6.95838 1.04199 6.77505 2.14199 6.64172 2.95033L6.46672 4.01699C5.69172 4.06699 4.91672 4.11699 4.14172 4.19199L2.44172 4.35866C2.09172 4.39199 1.84172 4.70033 1.87505 5.04199C1.90838 5.38366 2.20838 5.63366 2.55838 5.60033L4.25838 5.43366C8.62505 5.00033 13.0251 5.16699 17.4417 5.60866C17.4667 5.60866 17.4834 5.60866 17.5084 5.60866C17.8251 5.60866 18.1 5.36699 18.1334 5.04199C18.1584 4.70033 17.9084 4.39199 17.5584 4.35866Z"
                                                    fill="#F73C71"></path>
                                                <path
                                                    d="M16.025 6.78301C15.825 6.57467 15.55 6.45801 15.2666 6.45801H4.73329C4.44995 6.45801 4.16662 6.57467 3.97495 6.78301C3.78329 6.99134 3.67495 7.27467 3.69162 7.56634L4.20829 16.1163C4.29995 17.383 4.41662 18.9663 7.32495 18.9663H12.675C15.5833 18.9663 15.7 17.3913 15.7916 16.1163L16.3083 7.57467C16.325 7.27467 16.2166 6.99134 16.025 6.78301ZM11.3833 14.7913H8.60829C8.26662 14.7913 7.98329 14.508 7.98329 14.1663C7.98329 13.8247 8.26662 13.5413 8.60829 13.5413H11.3833C11.725 13.5413 12.0083 13.8247 12.0083 14.1663C12.0083 14.508 11.725 14.7913 11.3833 14.7913ZM12.0833 11.458H7.91662C7.57495 11.458 7.29162 11.1747 7.29162 10.833C7.29162 10.4913 7.57495 10.208 7.91662 10.208H12.0833C12.425 10.208 12.7083 10.4913 12.7083 10.833C12.7083 11.1747 12.425 11.458 12.0833 11.458Z"
                                                    fill="#F73C71"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif

                    @if (Session::get('contact_ids') != null && count(Session::get('contact_ids')) > 0)
                        @foreach (Session::get('contact_ids') as $guest_user)
                            @if ($guest_user['prefer_by'] == 'email')
                                <div class="users-data invited_users" data-id={{ 'sync_' . $guest_user['sync_id'] }}
                                    id="contact_tel-{{ $guest_user['sync_id'] }}">
                                    <div class="d-flex align-items-start">
                                        <div class="contact-img">
                                            @if ($guest_user['profile'] != '')
                                                <img src="{{ $guest_user['profile'] }}" alt="user-img">
                                            @else
                                                @php
                                                    $firstInitial = !empty($guest_user['firstname'])
                                                        ? strtoupper($guest_user['firstname'][0])
                                                        : '';
                                                    $lastInitial = !empty($guest_user['lastname'])
                                                        ? strtoupper($guest_user['lastname'][0])
                                                        : '';
                                                    $initials = $firstInitial . $lastInitial;
                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                @endphp
                                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                            @endif
                                        </div>
                                        <div class="text-start">
                                            <h5>{{ $guest_user['firstname'] }}
                                                {{ $guest_user['lastname'] }}</h5>
                                            <div>
                                                <a href="#"><svg class="me-1" width="14" height="14"
                                                        viewBox="0 0 14 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91602 11.9582H4.08268C2.33268 11.9582 1.16602 11.0832 1.16602 9.0415V4.95817C1.16602 2.9165 2.33268 2.0415 4.08268 2.0415H9.91602C11.666 2.0415 12.8327 2.9165 12.8327 4.95817V9.0415C12.8327 11.0832 11.666 11.9582 9.91602 11.9582Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                        <path
                                                            d="M9.91732 5.25L8.09148 6.70833C7.49065 7.18667 6.50482 7.18667 5.90398 6.70833L4.08398 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                                    </svg>
                                                    {{ $guest_user['invited_by'] }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!isset($guest_user['isAlready']))
                                    <div>
                                        <a href="#" id="delete_invited_user_tel"
                                            data-id="contact-{{ $guest_user['sync_id'] }}" data-contact="1"
                                            data-userid="{{ $guest_user['sync_id'] }}">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5584 4.35866C16.2167 4.22533 14.875 4.12533 13.525 4.05033V4.04199L13.3417 2.95866C13.2167 2.19199 13.0334 1.04199 11.0834 1.04199H8.90005C6.95838 1.04199 6.77505 2.14199 6.64172 2.95033L6.46672 4.01699C5.69172 4.06699 4.91672 4.11699 4.14172 4.19199L2.44172 4.35866C2.09172 4.39199 1.84172 4.70033 1.87505 5.04199C1.90838 5.38366 2.20838 5.63366 2.55838 5.60033L4.25838 5.43366C8.62505 5.00033 13.0251 5.16699 17.4417 5.60866C17.4667 5.60866 17.4834 5.60866 17.5084 5.60866C17.8251 5.60866 18.1 5.36699 18.1334 5.04199C18.1584 4.70033 17.9084 4.39199 17.5584 4.35866Z"
                                                    fill="#F73C71"></path>
                                                <path
                                                    d="M16.025 6.78301C15.825 6.57467 15.55 6.45801 15.2666 6.45801H4.73329C4.44995 6.45801 4.16662 6.57467 3.97495 6.78301C3.78329 6.99134 3.67495 7.27467 3.69162 7.56634L4.20829 16.1163C4.29995 17.383 4.41662 18.9663 7.32495 18.9663H12.675C15.5833 18.9663 15.7 17.3913 15.7916 16.1163L16.3083 7.57467C16.325 7.27467 16.2166 6.99134 16.025 6.78301ZM11.3833 14.7913H8.60829C8.26662 14.7913 7.98329 14.508 7.98329 14.1663C7.98329 13.8247 8.26662 13.5413 8.60829 13.5413H11.3833C11.725 13.5413 12.0083 13.8247 12.0083 14.1663C12.0083 14.508 11.725 14.7913 11.3833 14.7913ZM12.0833 11.458H7.91662C7.57495 11.458 7.29162 11.1747 7.29162 10.833C7.29162 10.4913 7.57495 10.208 7.91662 10.208H12.0833C12.425 10.208 12.7083 10.4913 12.7083 10.833C12.7083 11.1747 12.425 11.458 12.0833 11.458Z"
                                                    fill="#F73C71"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            @elseif ($guest_user['prefer_by'] == 'phone')
                                <div class="users-data invited_users" data-id={{ 'sync_' . $guest_user['sync_id'] }}
                                    id="contact_tel-{{ $guest_user['sync_id'] }}">
                                    <div class="d-flex align-items-start">
                                        <div class="contact-img">
                                            @if ($guest_user['profile'] != '')
                                                <img src="{{ $guest_user['profile'] }}" alt="user-img">
                                            @else
                                                @php
                                                    $firstInitial = !empty($guest_user['firstname'])
                                                        ? strtoupper($guest_user['firstname'][0])
                                                        : '';
                                                    $lastInitial = !empty($guest_user['lastname'])
                                                        ? strtoupper($guest_user['lastname'][0])
                                                        : '';
                                                    $initials = $firstInitial . $lastInitial;
                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                @endphp
                                                <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                            @endif
                                        </div>
                                        <div class="text-start">
                                            <h5>{{ $guest_user['firstname'] }}
                                                {{ $guest_user['lastname'] }}</h5>
                                            <div>

                                                <a href="#">
                                                    <svg width="14" class="me-1" height="14"
                                                        viewBox="0 0 14 14" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M10.1805 13.2712C9.5213 13.2712 8.82714 13.1137 8.10964 12.8103C7.40964 12.5128 6.7038 12.1045 6.01547 11.6087C5.33297 11.107 4.6738 10.547 4.04964 9.93449C3.4313 9.31033 2.8713 8.65116 2.37547 7.97449C1.8738 7.27449 1.4713 6.57449 1.18547 5.89783C0.882135 5.17449 0.730469 4.47449 0.730469 3.81533C0.730469 3.36033 0.812135 2.92866 0.969635 2.52616C1.13297 2.11199 1.39547 1.72699 1.7513 1.39449C2.20047 0.951159 2.7138 0.729492 3.26214 0.729492C3.48964 0.729492 3.72297 0.781992 3.9213 0.875326C4.1488 0.980326 4.3413 1.13783 4.4813 1.34783L5.83464 3.25533C5.95714 3.42449 6.05047 3.58783 6.11464 3.75116C6.19047 3.92616 6.2313 4.10116 6.2313 4.27033C6.2313 4.49199 6.16714 4.70783 6.04464 4.91199C5.95714 5.06949 5.82297 5.23866 5.6538 5.40783L5.25714 5.82199C5.26297 5.83949 5.2688 5.85116 5.27464 5.86283C5.34464 5.98533 5.48464 6.19533 5.75297 6.51033C6.0388 6.83699 6.30714 7.13449 6.57547 7.40866C6.91964 7.74699 7.20547 8.01533 7.4738 8.23699C7.8063 8.51699 8.02214 8.65699 8.15047 8.72116L8.1388 8.75033L8.56464 8.33033C8.74547 8.14949 8.92047 8.01533 9.08964 7.92783C9.41047 7.72949 9.8188 7.69449 10.2271 7.86366C10.3788 7.92783 10.5421 8.01533 10.7171 8.13783L12.6538 9.51449C12.8696 9.66033 13.0271 9.84699 13.1205 10.0687C13.208 10.2903 13.2488 10.4945 13.2488 10.6987C13.2488 10.9787 13.1846 11.2587 13.0621 11.5212C12.9396 11.7837 12.788 12.0112 12.5955 12.2212C12.263 12.5887 11.9013 12.8512 11.4813 13.0203C11.0788 13.1837 10.6413 13.2712 10.1805 13.2712ZM3.26214 1.60449C2.9413 1.60449 2.6438 1.74449 2.35797 2.02449C2.08964 2.27533 1.90297 2.54949 1.7863 2.84699C1.6638 3.15033 1.60547 3.47116 1.60547 3.81533C1.60547 4.35783 1.7338 4.94699 1.99047 5.55366C2.25297 6.17199 2.62047 6.81366 3.08714 7.45533C3.5538 8.09699 4.08464 8.72116 4.66797 9.31033C5.2513 9.88783 5.8813 10.4245 6.5288 10.897C7.1588 11.3578 7.8063 11.7312 8.44797 11.9995C9.44547 12.4253 10.3788 12.5245 11.1488 12.2037C11.4463 12.0812 11.7088 11.8945 11.948 11.6262C12.0821 11.4803 12.1871 11.3228 12.2746 11.1362C12.3446 10.9903 12.3796 10.8387 12.3796 10.687C12.3796 10.5937 12.3621 10.5003 12.3155 10.3953C12.298 10.3603 12.263 10.2962 12.1521 10.2203L10.2155 8.84366C10.0988 8.76199 9.9938 8.70366 9.89464 8.66283C9.7663 8.61033 9.7138 8.55783 9.51547 8.68033C9.3988 8.73866 9.2938 8.82616 9.17714 8.94283L8.7338 9.38033C8.5063 9.60199 8.1563 9.65449 7.88797 9.55533L7.73047 9.48533C7.4913 9.35699 7.2113 9.15866 6.90214 8.89616C6.62214 8.65699 6.3188 8.37699 5.9513 8.01533C5.66547 7.72366 5.37964 7.41449 5.08214 7.07033C4.80797 6.74949 4.60964 6.47533 4.48714 6.24783L4.41714 6.07283C4.38214 5.93866 4.37047 5.86283 4.37047 5.78116C4.37047 5.57116 4.4463 5.38449 4.59214 5.23866L5.02964 4.78366C5.1463 4.66699 5.2338 4.55616 5.29214 4.45699C5.3388 4.38116 5.3563 4.31699 5.3563 4.25866C5.3563 4.21199 5.3388 4.14199 5.30964 4.07199C5.2688 3.97866 5.20464 3.87366 5.12297 3.76283L3.76964 1.84949C3.7113 1.76783 3.6413 1.70949 3.5538 1.66866C3.46047 1.62783 3.3613 1.60449 3.26214 1.60449ZM8.1388 8.75616L8.04547 9.15283L8.20297 8.74449C8.1738 8.73866 8.15047 8.74449 8.1388 8.75616Z"
                                                            fill="black" />
                                                    </svg>
                                                    {{ $guest_user['invited_by'] }}</a>
                                            </div>
                                        </div>
                                    </div>
                                    @if (!isset($guest_user['isAlready']))
                                    <div>
                                        <a href="#" id="delete_invited_user_tel"
                                            data-id="contact_tel-{{ $guest_user['sync_id'] }}" data-contact="1"
                                            data-userid="{{ $guest_user['sync_id'] }}">
                                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M17.5584 4.35866C16.2167 4.22533 14.875 4.12533 13.525 4.05033V4.04199L13.3417 2.95866C13.2167 2.19199 13.0334 1.04199 11.0834 1.04199H8.90005C6.95838 1.04199 6.77505 2.14199 6.64172 2.95033L6.46672 4.01699C5.69172 4.06699 4.91672 4.11699 4.14172 4.19199L2.44172 4.35866C2.09172 4.39199 1.84172 4.70033 1.87505 5.04199C1.90838 5.38366 2.20838 5.63366 2.55838 5.60033L4.25838 5.43366C8.62505 5.00033 13.0251 5.16699 17.4417 5.60866C17.4667 5.60866 17.4834 5.60866 17.5084 5.60866C17.8251 5.60866 18.1 5.36699 18.1334 5.04199C18.1584 4.70033 17.9084 4.39199 17.5584 4.35866Z"
                                                    fill="#F73C71"></path>
                                                <path
                                                    d="M16.025 6.78301C15.825 6.57467 15.55 6.45801 15.2666 6.45801H4.73329C4.44995 6.45801 4.16662 6.57467 3.97495 6.78301C3.78329 6.99134 3.67495 7.27467 3.69162 7.56634L4.20829 16.1163C4.29995 17.383 4.41662 18.9663 7.32495 18.9663H12.675C15.5833 18.9663 15.7 17.3913 15.7916 16.1163L16.3083 7.57467C16.325 7.27467 16.2166 6.99134 16.025 6.78301ZM11.3833 14.7913H8.60829C8.26662 14.7913 7.98329 14.508 7.98329 14.1663C7.98329 13.8247 8.26662 13.5413 8.60829 13.5413H11.3833C11.725 13.5413 12.0083 13.8247 12.0083 14.1663C12.0083 14.508 11.725 14.7913 11.3833 14.7913ZM12.0833 11.458H7.91662C7.57495 11.458 7.29162 11.1747 7.29162 10.833C7.29162 10.4913 7.57495 10.208 7.91662 10.208H12.0833C12.425 10.208 12.7083 10.4913 12.7083 10.833C12.7083 11.1747 12.425 11.458 12.0833 11.458Z"
                                                    fill="#F73C71"></path>
                                            </svg>
                                        </a>
                                    </div>
                                    @endif
                                </div>
                            @endif
                        @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const sliderWrapper = document.querySelector(".slider-wrapper");
        const slides = document.querySelectorAll(".slider-slide");
        const prevButton = document.querySelector(".slider-button.prev");
        const nextButton = document.querySelector(".slider-button.next");

        let currentIndex = 0;
        let slideWidth = slides[0].offsetWidth + 20; // Including margin

        function updateSlider() {
            sliderWrapper.style.transform = `translateX(-${currentIndex * slideWidth}px)`;
        }

        nextButton.addEventListener("click", function () {
            if (currentIndex < slides.length - 1) {
                currentIndex++;
            } else {
                currentIndex = 0; // Loop back
            }
            updateSlider();
        });

        prevButton.addEventListener("click", function () {
            if (currentIndex > 0) {
                currentIndex--;
            } else {
                currentIndex = slides.length - 1; // Loop back
            }
            updateSlider();
        });

        // Auto-adjust slide width on window resize
        window.addEventListener("resize", function () {
            slideWidth = slides[0].offsetWidth + 20;
            updateSlider();
        });
    });
</script>