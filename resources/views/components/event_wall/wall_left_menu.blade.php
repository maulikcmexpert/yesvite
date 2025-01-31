{{-- {{dd($postList)}} --}}
<div class="main-content-left">
    <div class="hosted-by-title">
        <div class="hosted-by-info">
            <div class="hosted-by-info-img">
                @if ($eventDetails['user_profile'] != '')
                    <img src="{{ $eventDetails['user_profile'] }}" alt="" />
                @else
                    @php
                        $name = $eventDetails['hosted_by'];
                        // $parts = explode(" ", $name);
                        $firstInitial = isset($eventDetails['hosted_by'][0])
                            ? strtoupper($eventDetails['hosted_by'][0])
                            : '';
                        $secondInitial = isset($eventDetails['hosted_by'][5])
                            ? strtoupper($eventDetails['hosted_by'][5])
                            : '';
                        $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                        $fontColor = 'fontcolor' . strtoupper($firstInitial);
                    @endphp
                    <h5 class="{{ $fontColor }}">
                        {{ $initials }}
                    </h5>
                @endif

            </div>
            <div class="hosted-by-info-content">
                <h3>Hosted by <span>{{ $eventDetails['hosted_by'] }}</span></h3>
            </div>
        </div>
        <div class="dropdown hosted-by-title-dropdown">
            <button class="hosted-by-title-menu dropdown-toggle" type="button" data-bs-toggle="dropdown"
                aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>

        </div>
    </div>

    <div class="hosted-by-template-slider">
        <div class="swiper mySwiper">
            <div class="swiper-wrapper">
                <!-- Slides -->
                @if ($eventDetails['event_images'])
                    @foreach ($eventDetails['event_images'] as $image)
                        <div class="swiper-slide">
                            <div class="hosted-by-template-slider-img">
                                <img src="{{ $image }}" alt="Event Image" />
                            </div>
                        </div>
                    @endforeach
                @else

                    <div class="swiper-slide">
                        <div class="hosted-by-template-slider-img">
                            <img src="{{ asset('assets/front/img/host-by-template-img.png') }}" alt="No Event Image" />
                        </div>
                    </div>
                @endif
            </div>


            <div class="custom-pagination"></div>
        </div>
    </div>


    @if ($page !== 'about')
        <div class="hosted-by-detail">
            <div class="hosted-by-detail-inner">
                <h3>Details</h3>
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
                @if (!empty($eventDetails['allow_limit'] ))
                    <li>Can Bring Gursts ({{ $eventDetails['allow_limit'] }})</li>
                    @endif
                </ul>
            </div>
            <div class="hosted-by-date-time">
                <div class="hosted-by-date-time-left">
                    <div class="hosted-by-date-time-left-icon">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.66797 1.66663V4.16663" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M13.332 1.66663V4.16663" stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M2.91797 7.57495H17.0846" stroke="#64748B" stroke-width="1.5"
                                stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            <path
                                d="M17.5 7.08329V14.1666C17.5 16.6666 16.25 18.3333 13.3333 18.3333H6.66667C3.75 18.3333 2.5 16.6666 2.5 14.1666V7.08329C2.5 4.58329 3.75 2.91663 6.66667 2.91663H13.3333C16.25 2.91663 17.5 4.58329 17.5 7.08329Z"
                                stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M13.0801 11.4167H13.0875" stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M13.0801 13.9167H13.0875" stroke="#64748B" stroke-width="2" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M9.99803 11.4167H10.0055" stroke="#64748B" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M9.99803 13.9167H10.0055" stroke="#64748B" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6.91209 11.4167H6.91957" stroke="#64748B" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M6.91209 13.9167H6.91957" stroke="#64748B" stroke-width="2"
                                stroke-linecap="round" stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="hosted-by-date-time-content">
                        <h6>Date</h6>
                        <h3>{{ \Carbon\Carbon::parse($eventDetails['event_date'])->format('F d, Y') }}
                            @if (!empty($eventDetails['end_date'] ))
                            to
                            {{ \Carbon\Carbon::parse($eventDetails['end_date'])->format('F d, Y') }}
                            @endif
                        </h3>
                    </div>
                </div>
                <div class="hosted-by-date-time-left">
                    <div class="hosted-by-date-time-left-icon">
                        <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.8346 9.99996C18.8346 14.6 15.1013 18.3333 10.5013 18.3333C5.9013 18.3333 2.16797 14.6 2.16797 9.99996C2.16797 5.39996 5.9013 1.66663 10.5013 1.66663C15.1013 1.66663 18.8346 5.39996 18.8346 9.99996Z"
                                stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M13.5914 12.65L11.0081 11.1083C10.5581 10.8416 10.1914 10.2 10.1914 9.67497V6.2583"
                                stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </div>
                    <div class="hosted-by-date-time-content">
                        <h6>Time</h6>
                        <h3>{{ $eventDetails['event_time'] }}
                            @if (!empty($eventDetails['end_time'] ))
                            to
                            {{ $eventDetails['end_time'] }}
                        @endif
                        </h3>
                    </div>
                </div>
            </div>
            <div class="hosted-by-event-stats">
                <h3>Event Stats</h3>
                <div class="hosted-by-event-stats-wrp">
                    <div class="hosted-by-event-stats-inner">
                        <h5>
                            <svg viewBox="0 0 14 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.77162 2.82589V1.91589C9.77162 1.67673 9.57328 1.47839 9.33412 1.47839C9.09495 1.47839 8.89662 1.67673 8.89662 1.91589V2.79089H5.10495V1.91589C5.10495 1.67673 4.90662 1.47839 4.66745 1.47839C4.42828 1.47839 4.22995 1.67673 4.22995 1.91589V2.82589C2.65495 2.97173 1.89078 3.91089 1.77412 5.30506C1.76245 5.47423 1.90245 5.61423 2.06578 5.61423H11.9358C12.1049 5.61423 12.245 5.46839 12.2275 5.30506C12.1108 3.91089 11.3466 2.97173 9.77162 2.82589Z"
                                    fill="#F73C71" />
                                <path
                                    d="M11.0833 9.5C9.79417 9.5 8.75 10.5442 8.75 11.8333C8.75 12.2708 8.8725 12.685 9.08833 13.035C9.49083 13.7117 10.2317 14.1667 11.0833 14.1667C11.935 14.1667 12.6758 13.7117 13.0783 13.035C13.2942 12.685 13.4167 12.2708 13.4167 11.8333C13.4167 10.5442 12.3725 9.5 11.0833 9.5ZM12.2908 11.5825L11.0483 12.7317C10.9667 12.8075 10.8558 12.8483 10.7508 12.8483C10.64 12.8483 10.5292 12.8075 10.4417 12.72L9.86417 12.1425C9.695 11.9733 9.695 11.6933 9.86417 11.5242C10.0333 11.355 10.3133 11.355 10.4825 11.5242L10.7625 11.8042L11.6958 10.9408C11.8708 10.7775 12.1508 10.7892 12.3142 10.9642C12.4775 11.1392 12.4658 11.4133 12.2908 11.5825Z"
                                    fill="#F73C71" />
                                <path
                                    d="M11.6667 6.48853H2.33333C2.0125 6.48853 1.75 6.75103 1.75 7.07186V10.6652C1.75 12.4152 2.625 13.5819 4.66667 13.5819H7.5425C7.945 13.5819 8.225 13.191 8.09667 12.8119C7.98 12.4735 7.88083 12.1002 7.88083 11.8319C7.88083 10.0644 9.32167 8.62353 11.0892 8.62353C11.2583 8.62353 11.4275 8.63519 11.5908 8.66436C11.9408 8.71686 12.2558 8.44269 12.2558 8.09269V7.07769C12.25 6.75103 11.9875 6.48853 11.6667 6.48853ZM5.3725 11.371C5.26167 11.476 5.11 11.5402 4.95833 11.5402C4.80667 11.5402 4.655 11.476 4.54417 11.371C4.43917 11.2602 4.375 11.1085 4.375 10.9569C4.375 10.8052 4.43917 10.6535 4.54417 10.5427C4.6025 10.4902 4.66083 10.4494 4.73667 10.4202C4.9525 10.3269 5.20917 10.3794 5.3725 10.5427C5.4775 10.6535 5.54167 10.8052 5.54167 10.9569C5.54167 11.1085 5.4775 11.2602 5.3725 11.371ZM5.3725 9.32936C5.34333 9.35269 5.31417 9.37603 5.285 9.39936C5.25 9.42269 5.215 9.44019 5.18 9.45186C5.145 9.46936 5.11 9.48102 5.075 9.48686C5.03417 9.49269 4.99333 9.49853 4.95833 9.49853C4.80667 9.49853 4.655 9.43436 4.54417 9.32936C4.43917 9.21853 4.375 9.06686 4.375 8.91519C4.375 8.76353 4.43917 8.61186 4.54417 8.50103C4.67833 8.36686 4.8825 8.30269 5.075 8.34353C5.11 8.34936 5.145 8.36103 5.18 8.37853C5.215 8.39019 5.25 8.40769 5.285 8.43103C5.31417 8.45436 5.34333 8.47769 5.3725 8.50103C5.4775 8.61186 5.54167 8.76353 5.54167 8.91519C5.54167 9.06686 5.4775 9.21853 5.3725 9.32936ZM7.41417 9.32936C7.30333 9.43436 7.15167 9.49853 7 9.49853C6.84833 9.49853 6.69667 9.43436 6.58583 9.32936C6.48083 9.21853 6.41667 9.06686 6.41667 8.91519C6.41667 8.76353 6.48083 8.61186 6.58583 8.50103C6.8075 8.28519 7.19833 8.28519 7.41417 8.50103C7.51917 8.61186 7.58333 8.76353 7.58333 8.91519C7.58333 9.06686 7.51917 9.21853 7.41417 9.32936Z"
                                    fill="#F73C71" />
                            </svg>
                            Comments
                        </h5>
                        <h3 id="comment_{{ $postList['id'] }}">{{ $postList['total_comment'] }}</h3>
                    </div>
                    <div class="hosted-by-event-stats-inner">
                        <h5>
                            <svg viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path opacity="0.4"
                                    d="M11.2021 12.23C10.8812 13.0525 10.0996 13.5833 9.21875 13.5833H5.77708C4.89041 13.5833 4.11458 13.0525 3.79375 12.23C3.47291 11.4017 3.69458 10.4858 4.34791 9.89083L6.71041 7.75H8.29125L10.6479 9.89083C11.3012 10.4858 11.5171 11.4017 11.2021 12.23Z"
                                    fill="#F73C71" />
                                <path
                                    d="M8.56297 11.3313H6.43964C6.21797 11.3313 6.04297 11.1505 6.04297 10.9346C6.04297 10.713 6.2238 10.538 6.43964 10.538H8.56297C8.78464 10.538 8.95964 10.7188 8.95964 10.9346C8.95964 11.1505 8.7788 11.3313 8.56297 11.3313Z"
                                    fill="#F73C71" />
                                <path
                                    d="M11.2032 3.26996C10.8824 2.44746 10.1007 1.91663 9.21991 1.91663H5.77824C4.89741 1.91663 4.11574 2.44746 3.79491 3.26996C3.47991 4.09829 3.69574 5.01413 4.35491 5.60913L6.71158 7.74996H8.29241L10.6491 5.60913C11.3024 5.01413 11.5182 4.09829 11.2032 3.26996ZM8.56074 4.96746H6.43741C6.21574 4.96746 6.04074 4.78663 6.04074 4.57079C6.04074 4.35496 6.22158 4.17413 6.43741 4.17413H8.56074C8.78241 4.17413 8.95741 4.35496 8.95741 4.57079C8.95741 4.78663 8.77658 4.96746 8.56074 4.96746Z"
                                    fill="#F73C71" />
                            </svg>
                            Days till event
                        </h5>
                        <h3>{{ $eventDetails['days_till_event'] }}</h3>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
<!-- ====== cancel event ======== -->
<div class="modal fade cmn-modal cancel-event" id="cancelevent2" tabindex="-1" aria-labelledby="canceleventLabel"
    aria-hidden="true">
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
                <button type="button" class="btn btn-secondary cancel-btn" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-secondary confirm-btn" data-bs-dismiss="modal">Confirm</button>
            </div>
        </div>
    </div>
</div>
