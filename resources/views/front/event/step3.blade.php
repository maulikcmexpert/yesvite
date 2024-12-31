<div class="step_3" style="display: none;width:100%;">
    <div class="main-content-wrp guest-main-content">
        <div class="guest-wrapper position-relative">
            <div class="guest-content new-guest-content-wrp">
                <h4>Guests</h4>
                <div class="contact-tab contact-user">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="contact-tab" data-bs-toggle="tab"
                                data-bs-target="#contact" type="button" role="tab" aria-controls="#contact"
                                aria-selected="true">Yestive</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="phone-tab" data-bs-toggle="tab" data-bs-target="#phone"
                                type="button" role="tab" aria-controls="phone"
                                aria-selected="false">Phone</button>
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
                            <div class="guest-contacts-wrp user-list-responsive">
                                
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
                                        @foreach ($groups as $group )
                                        <div class="swiper-slide">
                                            <div class="group-card view_members" data-id="{{$group->id}}">
                                                <div>
                                                    <h4>{{$group->name}}</h4>
                                                    <p>{{$group->group_members_count}} Guests</p>
                                                </div>
                                                <span class="ms-auto">
                                                    <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973" stroke="#E2E8F0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
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
                                <input type="search" class="search_user search_user_ajax" placeholder="Search name" class="form-control">
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
                        <h5 class="ms-2 li_design">Edit Design</h5>
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
            </div>
            <div class="tab-pane fade" id="phone" role="tabpanel" aria-labelledby="phone-tab">

                <!-- ======= guest-users mobile ======== -->
                <div class="guest-contacts-wrp user-list-responsive">

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
                            @foreach ($groups as $group )
                            <div class="swiper-slide">
                                <div class="group-card view_members" data-id="{{$group->id}}">
                                    <div>
                                        <h4>{{$group->name}}</h4>
                                        <p>{{$group->group_members_count}} Guests</p>
                                    </div>
                                    <span class="ms-auto">
                                        <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973" stroke="#E2E8F0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
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
                    <input type="search" id="search_contacts" placeholder="Search name" class="form-control">
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
            <h5 class="ms-2 li_design">Edit Design</h5>
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
</div>
<div class="tab-pane fade" id="group" role="tabpanel"
    aria-labelledby="group-tab">

    <!-- ======= guest-users mobile ======== -->
    <div class="guest-contacts-wrp  user-list-responsive">

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
                @foreach ($groups as $group )
                <div class="swiper-slide">
                    <div class="group-card view_members" data-id="{{$group->id}}">
                        <div>
                            <h4>{{$group->name}}</h4>
                            <p>{{$group->group_members_count}} Guests</p>
                        </div>
                        <span class="ms-auto">
                            <svg width="16" height="17" viewBox="0 0 16 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M5.93994 13.7797L10.2866 9.43306C10.7999 8.91973 10.7999 8.07973 10.2866 7.56639L5.93994 3.21973" stroke="#E2E8F0" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
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
        <input type="search" placeholder="Search group names" class="form-control" id="group_search_ajax" name="group_search_ajax">
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
        @foreach ($groups as $group)
        <div class="group-card added_group{{ $group->id }} listgroups view_members" data-id="{{ $group->id }}">
            <div class="view_members" data-id="{{ $group->id }}">
                <h4>{{ $group->name }}</h4>
                <p>{{ $group->group_members_count }} Guests</p>
            </div>
            <span class="ms-auto me-3">
             
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

    </div>
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
            <h5 class="ms-2 li_design">Edit Design</h5>
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
</div>
</div>
</div>
<div class="invite-pro">
    <div class="invite-left">
        <h6 class="invite-count">0</h6>
        <p class="invite-left_d"><strong>Invites | {{$user->coins}}</strong> Left</p>
    </div>
    <div class="invite-right">
        <span><strong>15</strong>Guests or less</span>
    </div>
    <a href="#" class="edit-icon">
        <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
            xmlns="http://www.w3.org/2000/svg">
            <path
                d="M11.05 4.66701L4.20829 11.9087C3.94996 12.1837 3.69996 12.7253 3.64996 13.1003L3.34162 15.8003C3.23329 16.7753 3.93329 17.442 4.89996 17.2753L7.58329 16.817C7.95829 16.7503 8.48329 16.4753 8.74162 16.192L15.5833 8.95034C16.7666 7.70034 17.3 6.27534 15.4583 4.53368C13.625 2.80868 12.2333 3.41701 11.05 4.66701Z"
                stroke="black" stroke-width="1.5" stroke-miterlimit="10"
                stroke-linecap="round" stroke-linejoin="round" />
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
        <div class="guest-wrp">
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
                            stroke="black" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M9.9082 5.875C10.2665 8.175 12.1332 9.93333 14.4499 10.1667"
                            stroke="black" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </a>
            </div>
            <div class="invite-pro">
                <div class="invite-left">
                    <h6 class="invite-count">0</h6>
                    {{-- <p id="invite-left"><strong>0</strong>Left</p> --}}
                    <input type="hidden" id="event_guest_left_count" />
                    <p class="invite-left_d"><strong>Invites | {{$user->coins}}</strong> Left</p>
                    <input type="hidden" id="currentInviteCount" value="0">
                </div>
                <div class="invite-right">
                    <span><strong>15</strong>Guests or less</span>
                </div>
            </div>
        </div>
        <div class="user-contacts inivted_user_list">

        </div>
    </div>
</div>
</div>
</div>