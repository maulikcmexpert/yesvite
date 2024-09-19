<div class="step_1">
    <div class="main-content-right">
        <div class="new_event_detail_form">
            <form action="">
                <h3>Detail Pages</h3>
                <div class="row">
                    <input type="hidden" value="{{ $user->id }}" id="user_id">
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <select class="form-select" id="event-type" onchange="clearError(this)">
                                <option value="">Select Event Type</option>
                                @foreach ($event_type as $type)
                                <option value="{{ $type->id }}">{{ $type->event_type }}</option>
                                @endforeach
                            </select>
                            <label for="select-label"
                                class="form-label input-field floating-label select-label floatingfocus">Event
                                Type</label>
                            <lable for="event-type" id="event-type-error" class="error"></lable>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="event-name"
                                name="event-name" oninput="clearError(this)" required="">
                            <label for="event-name" class="form-label input-field floating-label">Event Name
                                *</label>
                            <lable for="event-name" id="event-name-error" class="error"></lable>

                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="hostedby" name="hostedby"
                                oninput="clearError(this)" required=""
                                value="{{ $user->firstname }} {{ $user->lastname }}">
                            <label for="hostedby" class="form-label input-field floating-label">Hosted By
                                *</label>
                            <lable for="hostedby" id="event-host-error" class="error"></lable>

                        </div>
                    </div>
                    <div class="col-lg-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="event-date" name="event-date" onblur="clearError(this)" readonly>
                            <label for="birthday" class="form-label input-field floating-label select-label">Date of event</label>
                            <lable for="event-date" id="event-date-error" class="error"></lable>


                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        {{-- <div class="input-form">
                            <input type="time" class="form-control inputText" id="start-time"
                                name="start-time" oninput="clearError(this)" required="">
                            <label for="start-time"
                                class="form-label input-field floating-label select-label">Start
                                *</label>
                            <lable for="start-time" id="event-start_time-error" class="error"></lable>

                        </div> --}}

                        <div class="form-group">
                            <label>Start Time</label>
                            <div class="input-group time start-time">
                                <input type="text" class="form-control timepicker" placeholder="HH:MM AM/PM" id="start-time" name="start-time" onblur="clearError(this)" readonly /><span class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg></span></span>
                            </div>
                            <lable for="start-time" id="event-start_time-error" class="error"></lable>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="input-form">
                            <select class="form-select" id="start-time-zone" name="start_time_zone">
                                <option value="PST" selected>PST</option>
                                <option value="MST">MST</option>
                                <option value="CST">CST</option>
                                <option value="EST">EST</option>

                            </select>
                            <label for="time-zone"
                                class="form-label input-field floating-label select-label">Time
                                Zone *</label>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Add activity schedule to event </h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox" id="schedule">
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="add-activity-schedule" onclick="toggleSidebar('sidebar_activity_schedule')" style="display:none;">
                            <h5 class="step_1_activity"><span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity
                                schedule
                            </h5>
                            <i class="fa-solid fa-angle-right"></i>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Add end time?</h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox" id="end_time">
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-6 mb-4 end_time" style="display: none;">
                        {{-- <div class="input-form">
                            <input type="time" class="form-control inputText" id="end-time" name="end-time"
                                oninput="clearError(this)" required="">
                            <label for="start-time" class="form-label input-field floating-label select-label">End
                                *</label>
                            <lable for="start-time" id="event-end_time-error" class="error"></lable>

                        </div> --}}
                        <div class="form-group end-time-wrp">
                            <label>End Time</label>
                            <div class="input-group time ">
                                <input type="text" class="form-control timepicker" placeholder="HH:MM AM/PM" id="end-time" name="end-time" onblur="clearError(this)" readonly /><span class="input-group-append input-group-addon"><span class="input-group-text"><svg width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                        </svg></span></span>
                            </div>
                        </div>
                    </div>


                    <div class="col-6 mb-4 end_time" style="display: none">
                        <div class="input-form">
                            <select class="form-select" id="end-time-zone" name="end_time_zone">
                                <option value="PST" selected>PST</option>
                                <option value="MST">MST</option>
                                <option value="CST">CST</option>
                                <option value="EST">EST</option>
                            </select>
                            <label for="select-label"
                                class="form-label input-field floating-label select-label">Time
                                Zone *</label>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>RSVP By Date</h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox" id="rsvp_by_date">
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 mb-4 rsvp_by_date" style="display: none;">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="rsvp-by-date"
                                name="rsvp-by-date" onblur="clearError(this)" readonly autocomplete="off">
                            <label for="birthday" class="form-label input-field floating-label select-label">Rsvp
                                By Date</label>
                            <lable for="event-rsvpby" id="event-rsvpby-error" class="error"></lable>


                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="description"
                                name="description" required="">
                            <label for="description" class="form-label input-field floating-label">Event
                                Location
                                Description</label>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Add Address</h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox">
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form location-icon">
                            <input type="text" class="form-control inputText" id="address1" name="address1"
                                oninput="clearError(this)" required="">
                            <label for="address1" class="form-label input-field floating-label">Address 1
                                *</label>
                            <lable for="address1" id="event-address1-error" class="error"></lable>

                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.99999 11.1917C11.4359 11.1917 12.6 10.0276 12.6 8.5917C12.6 7.15576 11.4359 5.9917 9.99999 5.9917C8.56405 5.9917 7.39999 7.15576 7.39999 8.5917C7.39999 10.0276 8.56405 11.1917 9.99999 11.1917Z"
                                    stroke="#64748B" stroke-width="1.5" />
                                <path
                                    d="M3.01666 7.07484C4.65832 -0.141827 15.35 -0.133494 16.9833 7.08317C17.9417 11.3165 15.3083 14.8998 13 17.1165C11.325 18.7332 8.67499 18.7332 6.99166 17.1165C4.69166 14.8998 2.05832 11.3082 3.01666 7.07484Z"
                                    stroke="#64748B" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form location-icon">
                            <input type="text" class="form-control inputText" id="address2" name="address2"
                                required="">
                            <label for="address2" class="form-label input-field floating-label">Address
                                2</label>
                            <svg width="20" height="20" viewBox="0 0 20 20" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M9.99999 11.1917C11.4359 11.1917 12.6 10.0276 12.6 8.5917C12.6 7.15576 11.4359 5.9917 9.99999 5.9917C8.56405 5.9917 7.39999 7.15576 7.39999 8.5917C7.39999 10.0276 8.56405 11.1917 9.99999 11.1917Z"
                                    stroke="#64748B" stroke-width="1.5" />
                                <path
                                    d="M3.01666 7.07484C4.65832 -0.141827 15.35 -0.133494 16.9833 7.08317C17.9417 11.3165 15.3083 14.8998 13 17.1165C11.325 18.7332 8.67499 18.7332 6.99166 17.1165C4.69166 14.8998 2.05832 11.3082 3.01666 7.07484Z"
                                    stroke="#64748B" stroke-width="1.5" />
                            </svg>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            {{-- <select class="form-select">
                                <option value="1">New York</option>
                                <option value="2">New York</option>
                                <option value="3">New York</option>
                            </select> --}}
                            <input type="text" class="form-control inputText" id="city" name="city"
                                oninput="clearError(this)" required="">
                            <label for="select-label"
                                class="form-label input-field floating-label select-label">City
                                *</label>
                            <lable for="city" id="event-city-error" class="error"></lable>

                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="input-form">
                            {{-- <select class="form-select">
                                <option value="1">NY</option>
                                <option value="2">NY</option>
                                <option value="3">NY</option>
                            </select> --}}
                            <input type="text" class="form-control inputText" id="state" name="state"
                                oninput="clearError(this)" required="">

                            <label for="select-label"
                                class="form-label input-field floating-label select-label">State
                                *</label>
                            <lable for="city" id="event-state-error" class="error"></lable>

                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="input-form">
                            {{-- <select class="form-select">
                                <option value="1">8479</option>
                                <option value="2">8479</option>
                                <option value="3">8479</option>
                            </select> --}}
                            <input type="number" class="form-control inputText" id="zipcode" name="zipcode"
                                oninput="clearError(this)" required="">

                            <label for="select-label"
                                class="form-label input-field floating-label select-label">Zip
                                Code *</label>
                            <lable for="city" id="event-zipcode-error" class="error"></lable>

                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="input-form">
                            <textarea name="message_to_guests" class="inputText" id="message_to_guests"></textarea>
                            <label for="code"
                                class="form-label input-field floating-label textarea-label">Message
                                to Guests</label>
                        </div>
                    </div>




                    <!-- Modal -->
                    <div class="modal fade deleteModal" id="deleteModal" tabindex="-1"
                        aria-labelledby="deleteModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header justify-content-center">
                                    <div class="delete-img">
                                        <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z"
                                                stroke="white" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M12 8V13" stroke="white" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M11.9946 16H12.0036" stroke="white" stroke-width="2"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </div>

                                    <!-- <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button> -->
                                </div>
                                <div class="modal-body">
                                    <h5>Draft Not Saved</h5>
                                    <p>Please fill in data all the way to " Date of Event " for draft to be
                                        saved. exit will delete event</p>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn cancel-btn-createEvent"
                                        data-bs-dismiss="modal" data-url="{{ route('profile') }}">Exit</button>
                                    <button type="button" class="btn continue-btn"
                                        data-bs-dismiss="modal">Continue Editing</button>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="col-lg-12">
                        <div class="design-seting">
                            <a href="#" class="d-flex">
                                {{-- <span>
                                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M10.0002 13.2797L5.65355 8.93306C5.14022 8.41973 5.14022 7.57973 5.65355 7.06639L10.0002 2.71973" stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                                <h5 class="ms-2">Edit Design</h5> --}}
                            </a>
                            <button type="button" class="d-flex footer-bottom-btn" id="next_design">
                                <h5 class="me-2">Next: Design</h5>
                                <span><svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M5.93994 13.2797L10.2866 8.93306C10.7999 8.41973 10.7999 7.57973 10.2866 7.06639L5.93994 2.71973"
                                            stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<div id="sidebar_activity_schedule_overlay" class="overlay" onclick="toggleSidebar()"></div>
<div id="sidebar_activity_schedule" class="sidebar new-event-sidebar" style="">
    <div class="sidebar-content">
        <!-- Sidebar content -->
        <div class="d-flex align-items-center justify-content-between toggle-wrp new-event-sidebar-head">
            <h5>Activity Schedule</h5>
            <button class="close-btn" onclick="toggleSidebar()">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path d="M5.00098 5L19 18.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path d="M4.99996 18.9991L18.999 5" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <input type="hidden" id="firstActivityTime" value="">
        </div>
        <!-- Add your sidebar content here -->
        <div class="supportive-div activity_bar">
            <div class="activity-schedule-wrp">
                <div class="activity-schedule-head">
                    <h3>Friday - March 4, 2024</h3>
                </div>
                <div class="activity-schedule-inner new_event_detail_form">
                    <form action="" class="scheduleform">
                        <h4>Event Start</h4>
                        <div class="row">
                            <div class="col-12 mb-4">
                                <div class="input-form">
                                    <input type="time" class="form-control inputText"
                                        name="start-time" required="">
                                    <label for="start-time"
                                        class="form-label input-field floating-label select-label">Start *</label>
                                </div>
                            </div>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseOne">
                                            <div>
                                                Activities <span>(3)</span>
                                            </div>
                                            <i class="fa-solid fa-angle-down"></i>
                                        </button>
                                        <div class="accordion-button-icons">
                                            <i class="fa-solid fa-circle-plus"></i>

                                        </div>
                                    </div>
                                    <div id="collapseOne" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="activity-main-wrp mb-3">
                                                <h3>Activity 1
                                                    <span>
                                                        <svg width="20" height="20" viewBox="0 0 20 20"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M17.5 4.98356C14.725 4.70856 11.9333 4.56689 9.15 4.56689C7.5 4.56689 5.85 4.65023 4.2 4.81689L2.5 4.98356"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M7.08325 4.1415L7.26659 3.04984C7.39992 2.25817 7.49992 1.6665 8.90825 1.6665H11.0916C12.4999 1.6665 12.6083 2.2915 12.7333 3.05817L12.9166 4.1415"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M15.7084 7.6167L15.1667 16.0084C15.0751 17.3167 15.0001 18.3334 12.6751 18.3334H7.32508C5.00008 18.3334 4.92508 17.3167 4.83341 16.0084L4.29175 7.6167"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M8.6084 13.75H11.3834" stroke="#94A3B8"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7.91675 10.4165H12.0834" stroke="#94A3B8"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </h3>
                                                <div class="row">
                                                    <div class="col-12 mb-4">
                                                        <div class="input-form">
                                                            <input type="text" class="form-control inputText"
                                                                id="description" name="description" required="">
                                                            <label for="description"
                                                                class="form-label input-field floating-label">Description</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-4">
                                                        <div class="input-form">
                                                            <input type="time" class="form-control inputText"
                                                                id="start-time" name="start-time" required="">
                                                            <label for="start-time"
                                                                class="form-label input-field floating-label select-label">Start
                                                                Time</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-4">
                                                        <div class="input-form">
                                                            <input type="time" class="form-control inputText"
                                                                id="start-time" name="start-time" required="">
                                                            <label for="start-time"
                                                                class="form-label input-field floating-label select-label">End
                                                                Time</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="activity-main-wrp mb-3">
                                                <h3>Activity 2
                                                    <span>
                                                        <svg width="20" height="20" viewBox="0 0 20 20"
                                                            fill="none" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M17.5 4.98356C14.725 4.70856 11.9333 4.56689 9.15 4.56689C7.5 4.56689 5.85 4.65023 4.2 4.81689L2.5 4.98356"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M7.08325 4.1415L7.26659 3.04984C7.39992 2.25817 7.49992 1.6665 8.90825 1.6665H11.0916C12.4999 1.6665 12.6083 2.2915 12.7333 3.05817L12.9166 4.1415"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M15.7084 7.6167L15.1667 16.0084C15.0751 17.3167 15.0001 18.3334 12.6751 18.3334H7.32508C5.00008 18.3334 4.92508 17.3167 4.83341 16.0084L4.29175 7.6167"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M8.6084 13.75H11.3834" stroke="#94A3B8"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7.91675 10.4165H12.0834" stroke="#94A3B8"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </h3>
                                                <div class="row">
                                                    <div class="col-12 mb-4">
                                                        <div class="input-form">
                                                            <input type="text" class="form-control inputText"
                                                                id="description" name="description" required="">
                                                            <label for="description"
                                                                class="form-label input-field floating-label">Description</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-4">
                                                        <div class="input-form">
                                                            <input type="time" class="form-control inputText"
                                                                id="start-time" name="start-time" required="">
                                                            <label for="start-time"
                                                                class="form-label input-field floating-label select-label">Start
                                                                Time</label>
                                                        </div>
                                                    </div>
                                                    <div class="col-6 mb-4">
                                                        <div class="input-form">
                                                            <input type="time" class="form-control inputText"
                                                                id="start-time" name="start-time" required="">
                                                            <label for="start-time"
                                                                class="form-label input-field floating-label select-label">End
                                                                Time</label>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h4 class="mt-3">Event Ends</h4>
                            <div class="col-12">
                                <div class="input-form">
                                    <input type="time" class="form-control inputText" id="start-time"
                                        name="start-time" required="">
                                    <label for="start-time" class="form-label input-field floating-label select-label">End
                                        Time</label>
                                </div>
                            </div>

                        </div>

                        <div class="other-activity-schedule">
                            <div class="extra-border"></div>
                            <div class="activity-schedule-head">
                                <h3>Saturday - March 5, 2024</h3>
                            </div>
                            <div class="accordion" id="accordionExample2">
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            <div>
                                                Other Activities <span>(0 Activities)</span>
                                            </div>
                                            <i class="fa-solid fa-angle-down"></i>
                                        </button>
                                        <div class="accordion-button-icons">
                                            <i class="fa-solid fa-circle-plus"></i>

                                        </div>
                                    </div>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample2">
                                        <div class="accordion-body">
                                            <div class="activity-main-wrp mb-3">
                                                <div class="row">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="other-activity-schedule">
                            <div class="extra-border"></div>
                            <div class="activity-schedule-head">
                                <h3>Saturday - March 5, 2024</h3>
                            </div>
                            <div class="accordion" id="accordionExample2">
                                <div class="accordion-item">
                                    <div class="accordion-header">
                                        <button class="accordion-button collapsed" type="button"
                                            data-bs-toggle="collapse" data-bs-target="#collapseTwo">
                                            <div>
                                                Other Activities <span>(0 Activities)</span>
                                            </div>
                                            <i class="fa-solid fa-angle-down"></i>
                                        </button>
                                        <div class="accordion-button-icons">
                                            <i class="fa-solid fa-circle-plus"></i>

                                        </div>
                                    </div>
                                    <div id="collapseTwo" class="accordion-collapse collapse"
                                        data-bs-parent="#accordionExample2">
                                        <div class="accordion-body">
                                            <div class="activity-main-wrp mb-3">
                                                <div class="row">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="activity-schedule-inner-btn">
                            <button class="cmn-btn">
                                Create New Event
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>