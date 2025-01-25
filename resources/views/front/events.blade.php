{{-- {{dd($filter)}} --}}

<x-front.advertise />
<!-- ============= contact-details ============ -->
<section class="contact-details profile-details">
    <div class="container mb-5">
        <div class="row">
            <x-front.sidebar :profileData="[]" />
            <div class="col-xl-6 col-lg-9 col-md-8">
                <div class="home-center-main events-center-main">
                    <div class="home-center-content">
                      <div class="home-center-content-head">
                          <h1>All Events</h1>
                          <span class="filter_open" type="button" data-bs-toggle="modal" data-bs-target="#all-event-filter-modal">
                              <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                  <path d="M22 7H16" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M6 7H2" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M10 10.5C11.933 10.5 13.5 8.933 13.5 7C13.5 5.067 11.933 3.5 10 3.5C8.067 3.5 6.5 5.067 6.5 7C6.5 8.933 8.067 10.5 10 10.5Z" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M22 18H18" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M8 18H2" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                  <path d="M14 21.5C15.933 21.5 17.5 19.933 17.5 18C17.5 16.067 15.933 14.5 14 14.5C12.067 14.5 10.5 16.067 10.5 18C10.5 19.933 12.067 21.5 14 21.5Z" stroke="black" stroke-opacity="0.8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                              </svg>
                          </span>
                      </div>
                      <div class="event-center-tabs-main all-events-center-tabs">
                          <nav>
                            <div class="nav nav-tabs" id="nav-tab" role="tablist">
                              <button class="nav-link event_nav active" id="nav-upcoming-tab" data-page="upcoming" data-bs-toggle="tab" data-bs-target="#nav-upcoming" type="button" role="tab" aria-controls="nav-upcoming" aria-selected="true">
                                  Upcoming <span class="d-sm-flex d-none">{{$filter['total_upcoming']}}</span> <span class="d-sm-none d-flex">({{$filter['total_upcoming']}})</span>
                              </button>
                              <button class="nav-link event_nav" data-page="draft" id="nav-drafts-tab" onclick="sticky_relocate1()" data-bs-toggle="tab" data-bs-target="#nav-drafts" type="button" role="tab" aria-controls="nav-drafts" aria-selected="false" tabindex="-1">
                                  Drafts <span class="d-sm-flex d-none">{{$filter['total_draft']}}</span> <span class="d-sm-none d-flex">({{$filter['total_draft']}})</span>
                              </button>
                              <button class="nav-link event_nav" data-page="past" id="nav-past-tab" data-bs-toggle="tab" data-bs-target="#nav-past" type="button" role="tab" aria-controls="nav-past" aria-selected="false" tabindex="-1">
                                  Past <span class="d-sm-flex d-none">{{$filter['past_event']}}</span> <span class="d-sm-none d-flex">({{$filter['past_event']}})</span>
                              </button>
                            </div>
                          </nav>
          
                          <!-- ===tab-content-start=== -->
                          <div class="tab-content" id="nav-tabContent">
                                <!-- ===tab-1-start=== -->
                                <x-main_menu.events.event_upcoming :eventList="$eventList" />
                                <!-- ===tab-1-end=== -->
              
                                <!-- ===tab-2-start=== -->
                                <x-main_menu.events.event_draft :eventDraftdata="$eventDraftdata" />
                                <!-- ===tab-2-end=== -->
              
                                <!-- ===tab-3-start=== -->
                                <x-main_menu.events.event_past :eventPasttList="$eventPasttList" />
                                <!-- ===tab-3-end=== -->
                          </div>
                          <!-- ===tab-content-end=== -->
                        </div>
                    </div>
                </div>
                <button type="button" class="mobile-calender-btn">
                  <span class="responsive-text">Calendar</span>
                  <span class="responsive-icon">
                    <svg viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M7.16406 1.66602V4.16602" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.8359 1.66602V4.16602" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M3.41406 7.57422H17.5807" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M18 7.08268V14.166C18 16.666 16.75 18.3327 13.8333 18.3327H7.16667C4.25 18.3327 3 16.666 3 14.166V7.08268C3 4.58268 4.25 2.91602 7.16667 2.91602H13.8333C16.75 2.91602 18 4.58268 18 7.08268Z" stroke="white" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.5762 11.4167H13.5836" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M13.5762 13.9167H13.5836" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.498 11.4167H10.5055" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M10.498 13.9167H10.5055" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.41209 11.4167H7.41957" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    <path d="M7.41209 13.9167H7.41957" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    
                    {{-- <svg class="d-none" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                      <path d="M3 5.83398H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                      <path d="M3 10H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                      <path d="M3 14.166H18" stroke="white" stroke-width="1.5" stroke-linecap="round"/>
                    </svg> --}}
                  </span>
                </button>
                <input type="hidden" id="totalmonths" value="{{$numMonths}}"/>
                <input type="hidden" id="startmonths" value="{{$startMonth}}"/>
                <input type="hidden" id="diffmonth" value="{{$diffmonth}}"/>
                <input type="hidden" id="calender_json" value="{{$events_calender_json}}"/>
            </div>
            <x-main_menu.events.calender :profileData="$profileData" />
            <div id="responsive-calendar" class="responsive-calendar" style="display:none;">
              <h2 class="calendar-heading">All Events</h2>
              <div class="weekdays" style="position: sticky;"><div class="day">S</div><div class="day">M</div><div class="day">T</div><div class="day">W</div><div class="day">T</div><div class="day">F</div><div class="day">S</div></div>
              <div id="responsive-calender-months" class="responsive-calender-months"></div>
            </div>
            <div class="responsive-calender-month-wrp">
              <h3 class="responsive-calender-month-text" style="display:none;">{{$startMonthCalender}}</h3>
            </div>
        </div>
    </div>
<section>

    <div class="modal fade create-post-modal all-events-filtermodal" id="all-event-filter-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Filter</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="all-events-filter-wrp">
                    <form action="" id="event_filter">
                      <div class="form-check">
                        <input class="form-check-input hosting_chk" type="checkbox" value="" id="flexCheckDefault1" checked>
                        <label class="form-check-label hosting_chk_lbl" for="flexCheckDefault1">
                           Hosting <strong>({{$filter['hosting']}})</strong>
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input invited_to_chk" type="checkbox" value="" id="flexCheckDefault2"checked>
                        <label class="form-check-label invited_to_chk_lbl" for="flexCheckDefault2">
                          Invited To <strong>({{$filter['invitedTo_count_upcoming']}})</strong>
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input need_to_rsvp_chk" type="checkbox" value="" id="flexCheckDefault3">
                        <label class="form-check-label need_to_rsvp_chk_lbl" for="flexCheckDefault3">
                          Need to RSVP <strong>({{$filter['need_to_rsvp']}})</strong>
                        </label>
                      </div>

                      <div class="loader_filter"></div>

                      {{-- <div class="form-check">
                        <input class="form-check-input past_event_chk" type="checkbox" value="" id="flexCheckDefault4">
                        <label class="form-check-label" for="flexCheckDefault4">
                           Past Events <strong>({{$filter['past_event']}})</strong>
                        </label>
                      </div> --}}
                    </form>
                </div>
            </div>
            <div class="modal-footer">
              <button type="button" class="cmn-btn reset-btn all-event-filter-reset">Reset</button>
              <button type="button" class="cmn-btn filter_apply_btn">Apply</button>
            </div>
          </div>
        </div>
  </div>


      <!-- ====== cancel event ======== -->
      <!-- <div class="modal fade cmn-modal cancel-event cancel_event_mainmenu" id="cancelevent" tabindex="-1" aria-labelledby="canceleventLabel">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
              
                <div class="modal-body">
                    <div class="delete-modal-head text-center">
                      <div class="delete-icon">
                          <img src="{{asset('assets/front/image/info-circle.png')}}" alt="delete">
                      </div>
                      <input type="hidden" id="cancel_event_id"/>
                      <h4>Cancel Event</h4>
                      <p>Cancelling this event will delete everything in this event including but not limited to all comments, photos, and settings associated with this event for you and your guests.</p>
                    </div>
                    <div class="guest-msg">
                      <h5>Message to guests</h5>
                      <textarea name="" id="reason_to_cancel_event" placeholder="*Let your guests know why you are cancelling event......."></textarea>
                    </div>
                    <div class="cancel-event-text text-center">
                      <h6>Event cancellation is not reversible.</h6>
                      <p>Please confirm by typing <strong>“CANCEL”</strong> below.</p>
                      <input type="text" placeholder="CANCEL" id="type_cancel" class="form-control">
                    </div>
                </div>
                <div class="modal-footer cancel-confirm-btn">
                  <button type="button" class="btn btn-secondary cancel-btn" data-bs-dismiss="modal">Cancel</button>
                  <button type="button" class="btn btn-secondary confirm-btn confirm_cancel_event_btn" id="confirm_cancel_event_btn">Confirm</button>
                </div>
            </div>
        </div>
      </div> -->