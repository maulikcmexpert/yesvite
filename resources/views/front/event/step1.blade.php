@php

    use Carbon\Carbon;
@endphp

<div class="step_1" style="display: none;">
    <div class="main-content-right">
        <div class="new_event_detail_form">
            <form action="">
                <h3>Detail Pages</h3>
                <div class="row">

                    {{-- <div class="col-12 mb-4">
                        <div class="input-form">
                            <select class="form-select" id="event-type" onchange="clearError(this)">
                                <option value="">Select Event Type</option>
                                @foreach ($event_type as $type)
                                @php
                                    $event_type_id = '';
                                    if(isset($eventDetail['event_type_id']) && $eventDetail['event_type_id']!=''){
                                        $event_type_id = $eventDetail['event_type_id']; 
                                    }
                                @endphp
                                <option value="{{ $type->id }}" {{($event_type_id == $type->id)?'selected':''}}>{{ $type->event_type }}</option>
                                @endforeach
                            </select>
                            <label for="select-label"
                                class="form-label input-field floating-label select-label floatingfocus">Event
                                Type</label>
                        </div>
                            <lable for="event-type" id="event-type-error" class="error"></lable>
                    </div> --}}
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText"
                                value="{{ isset($eventDetail['event_name']) && $eventDetail['event_name'] != null ? $eventDetail['event_name'] : '' }}"
                                id="event-name" name="event-name" oninput="clearError(this)" required="">
                            <label for="event-name" class="form-label input-field floating-label">Event Name
                                *</label>
                        </div>
                        <lable for="event-name" id="event-name-error" class="error"></lable>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="hostedby" name="hostedby"
                                oninput="clearError(this)" required=""
                                value="{{ isset($eventDetail['hosted_by']) && $eventDetail['hosted_by'] != null ? $eventDetail['hosted_by'] : $user->firstname . ' ' . $user->lastname }}">
                            <label for="hostedby" class="form-label input-field floating-label">Hosted By
                                *</label>
                        </div>
                        <lable for="hostedby" id="event-host-error" class="error"></lable>
                    </div>
                    <div class="col-lg-12 mb-4">
                        <div class="input-form">
                            @php
                                $start_date = '';
                                $end_date = '';
                                $event_date = '';
                                if (isset($eventDetail['start_date']) && $eventDetail['start_date'] != '') {
                                    $start_date = Carbon::parse($eventDetail['start_date'])->format('m-d-Y');
                                    $event_date = $start_date;
                                }
                                if (isset($eventDetail['end_date']) && $eventDetail['end_date'] != '') {
                                    $end_date = Carbon::parse($eventDetail['end_date'])->format('m-d-Y');
                                    if ($start_date != $end_date) {
                                        $event_date = $start_date . ' To ' . $end_date;
                                    }
                                }
                            @endphp
                            <div class="position-relative z-2">
                                <input type="text" class="form-control inputText" style="background: transparent"
                                    id="event-date" data-isDate="{{ $event_date }}" name="event-date"
                                    onblur="clearError(this)" value="{{ $event_date }}" readonly>
                                <label for="birthday" class="form-label input-field floating-label select-label">Date of
                                    event * </label>
                                <svg width="21" class="input-calender-icon" height="20" viewBox="0 0 21 20"
                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.16797 1.66602V4.16602" stroke="#64748B" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.832 1.66602V4.16602" stroke="#64748B" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M3.41797 7.57422H17.5846" stroke="#64748B" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                                    <path
                                        d="M18 7.08268V14.166C18 16.666 16.75 18.3327 13.8333 18.3327H7.16667C4.25 18.3327 3 16.666 3 14.166V7.08268C3 4.58268 4.25 2.91602 7.16667 2.91602H13.8333C16.75 2.91602 18 4.58268 18 7.08268Z"
                                        stroke="#64748B" stroke-width="1.5" stroke-miterlimit="10"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.5801 11.4167H13.5875" stroke="#64748B" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M13.5801 13.9167H13.5875" stroke="#64748B" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.498 11.4167H10.5055" stroke="#64748B" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M10.498 13.9167H10.5055" stroke="#64748B" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.41209 11.4167H7.41957" stroke="#64748B" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M7.41209 13.9167H7.41957" stroke="#64748B" stroke-width="2"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>
                            </div>

                        </div>
                        <lable for="event-date" id="event-date-error" class="error"></lable>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="form-group">
                            <label>Start Time *</label>
                            <div class="input-group time start-time timepicker start-time-create">
                                <input type="text" class="form-control start_timepicker" placeholder="HH:MM AM/PM"
                                    id="start-time" name="start-time" onblur="clearError(this)" readonly
                                    value="{{ isset($eventDetail['rsvp_start_time']) && $eventDetail['rsvp_start_time'] != '' ? $eventDetail['rsvp_start_time'] : '' }}" /><span
                                    class="input-group-append input-group-addon"><span class="input-group-text"><svg
                                            width="21" height="20" viewBox="0 0 21 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg></span></span>
                            </div>
                            <lable for="start-time" id="event-start_time-error" class="error"></lable>
                        </div>
                    </div>
                    <div class="col-6 mb-4">
                        <div class="input-form">
                            <select class="form-select" name="start_time_zone" id="start-time-zone"
                                onchange="getStartEndTimeZone()">
                                @php
                                    $start_time_zone = '';
                                    if (
                                        isset($eventDetail['rsvp_start_timezone']) &&
                                        $eventDetail['rsvp_start_timezone'] != ''
                                    ) {
                                        $start_time_zone = $eventDetail['rsvp_start_timezone'];
                                    }
                                @endphp
                                {{-- <option value="PST" {{($start_time_zone =='' || $start_time_zone == 'PST')?'selected':''}}>PST</option> --}}
                                <option value="PST" {{ $start_time_zone == 'PST' ? 'selected' : '' }}>PST</option>
                                <option value="MST" {{ $start_time_zone == 'MST' ? 'selected' : '' }}>MST</option>
                                <option value="CST" {{ $start_time_zone == 'CST' ? 'selected' : '' }}>CST</option>
                                <option value="EST" {{ $start_time_zone == 'EST' ? 'selected' : '' }}>EST</option>
                                <option value="GMT+5:30" {{ $start_time_zone == 'GMT+5:30' ? 'selected' : '' }}>GMT+5:30
                                </option>

                                {{-- <option value="PST">PST</option>
                                <option value="MST">MST</option>
                                <option value="CST">CST</option>
                                <option value="EST">EST</option>
                                <option value="GMT05:30">GMT+05:30</option> --}}
                            </select>
                            <label for="select-label"
                                class="form-label input-field floating-label select-label floatingfocus">Time
                                Zone *</label>
                        </div>

                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>Add activity schedule to event </h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox" id="schedule"
                                            {{ isset($eventDetail['event_setting']['events_schedule']) && $eventDetail['event_setting']['events_schedule'] == '1' ? 'checked' : '' }}>
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        @php
                            $style = 'display:none';
                            if (
                                isset($eventDetail['event_setting']['events_schedule']) &&
                                $eventDetail['event_setting']['events_schedule'] == '1'
                            ) {
                                $style = '';
                            }
                        @endphp
                        <div class="add-activity-schedule" style="{{ $style }}">
                            <h5 class="step_1_activity">
                                @if (isset($eventDetail['events_schedule_list']->data) && count($eventDetail['events_schedule_list']->data) > 0)
                            <input type="hidden" id="TotalSedulare" value="{{ count($eventDetail['events_schedule_list']->data) }}">
                                    <p id="isolddata"> {{ count($eventDetail['events_schedule_list']->data) }}
                                        Activity </p>
                                @else
                                    <span><i class="fa-solid fa-triangle-exclamation"></i></span>Setup activity
                                    schedule
                                @endif
                                <p id="isnewdata" style="display: none"> <span><i
                                            class="fa-solid fa-triangle-exclamation"></i></span>Setup activity schedule
                                </p>
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
                                        <input type="checkbox" class="checkbox" id="end_time"
                                            {{ isset($eventDetail['rsvp_end_time_set']) && $eventDetail['rsvp_end_time_set'] == '1' ? 'checked' : '' }}>
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-6 mb-4 end_time"
                        style="{{ isset($eventDetail['rsvp_end_time_set']) && $eventDetail['rsvp_end_time_set'] !== '0' ? '' : 'display: none;' }}">
                        <div class="form-group end-time-wrp">
                            <label>End Time</label>
                            <div class="input-group time timepicker">
                                <input type="text" class="form-control end_timepicker end-time-create"
                                    placeholder="HH:MM AM/PM" id="end-time"
                                    value="{{ isset($eventDetail['rsvp_end_time']) && $eventDetail['rsvp_end_time'] != '' ? $eventDetail['rsvp_end_time'] : '' }}"
                                    name="end-time" onblur="clearError(this)" readonly /><span
                                    class="input-group-append input-group-addon"><span class="input-group-text"><svg
                                            width="21" height="20" viewBox="0 0 21 20" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </svg></span></span>
                            </div>
                            <label for="end-time" id="end-time-error" class="error"></label>
                        </div>
                    </div>
                    <div class="col-6 mb-4 end_time"
                        style="{{ isset($eventDetail['rsvp_end_time_set']) && $eventDetail['rsvp_end_time_set'] !== '0' ? '' : 'display: none;' }}">
                        <div class="input-form">
                            <select class="form-select" name="end-time-zone" onchange="getStartEndTimeZone()"
                                id="end-time-zone">
                                @php
                                    $end_time_zone = '';
                                    if (
                                        isset($eventDetail['rsvp_end_time_set']) &&
                                        $eventDetail['rsvp_end_time_set'] != ''
                                    ) {
                                        $end_time_zone = $eventDetail['rsvp_end_timezone'];
                                    }
                                @endphp

                                <option value="PST" {{ $end_time_zone == 'PST' ? 'selected' : '' }}>PST</option>
                                <option value="MST" {{ $end_time_zone == 'MST' ? 'selected' : '' }}>MST</option>
                                <option value="CST" {{ $end_time_zone == 'CST' ? 'selected' : '' }}>CST</option>
                                <option value="EST" {{ $end_time_zone == 'EST' ? 'selected' : '' }}>EST</option>
                                <option value="GMT+5:30" {{ $end_time_zone == 'GMT+5:30' ? 'selected' : '' }}>GMT+5:30
                                </option>

                                {{-- <option value="PST" {{($end_time_zone =='' || $end_time_zone == 'PST')?'selected':''}}>PST</option>
                                <option value="MST" {{($end_time_zone == 'MST')?'selected':''}}>MST</option>
                                <option value="CST" {{($end_time_zone == 'CST')?'selected':''}}>CST</option>
                                <option value="EST" {{($end_time_zone == 'EST')?'selected':''}}>EST</option> --}}
                            </select>
                            <label for="select-label"
                                class="form-label input-field floating-label select-label floatingfocus">Time
                                Zone *</label>
                        </div>
                        <label for="end-time-zone" id="end-time-zone-error" class="error"></label>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6>RSVP By Date</h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox" id="rsvp_by_date"
                                            {{ isset($eventDetail['rsvp_by_date_set']) && $eventDetail['rsvp_by_date_set'] == '1' ? 'checked' : '' }}>
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @php
                        $style = 'display:none';
                        if (isset($eventDetail['rsvp_by_date_set']) && $eventDetail['rsvp_by_date_set'] == '1') {
                            $style = '';
                        }
                    @endphp
                    <div class="col-lg-12 mb-4 rsvp_by_date" style="{{ $style }}">
                        <div class="input-form">

                            <input type="text" class="form-control inputText " id="rsvp-by-date"
                                name="rsvp-by-date" onblur="clearError(this)"
                                value="{{ isset($eventDetail['rsvp_by_date']) && $eventDetail['rsvp_by_date'] != '' ? Carbon::parse($eventDetail['rsvp_by_date'])->format('m-d-Y') : '' }}"
                                readonly autocomplete="off">
                            <label for="birthday" class="form-label input-field floating-label select-label">RSVP By
                                Date</label>
                        </div>
                        <lable for="event-rsvpby" id="event-rsvpby-error" class="error"></lable>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="description" name="description"
                                value="{{ isset($eventDetail['event_location_name']) && $eventDetail['event_location_name'] != '' ? $eventDetail['event_location_name'] : '' }}">
                            <label for="description" class="form-label input-field floating-label">Event
                                Location Description</label>
                        </div>
                    </div>
                    <div class="col-12 mb-4">
                        <div class="d-flex align-items-center justify-content-between">
                            <h6 class="mb-0">Add Address</h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">
                                        <input type="checkbox" class="checkbox" id="isCheckAddress"
                                            {{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? 'checked' : '' }}>
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-12 mb-4 ckeckedAddress"
                        style="{{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? '' : 'display:none' }}">
                        <div class="input-form location-icon">
                            <input type="text" class="form-control inputText" id="address1" name="address1"
                                oninput="clearError(this)"
                                value="{{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? $eventDetail['address_1'] : '' }}"
                                required="">
                            <label for="address1" class="form-label input-field floating-label">Address 1
                                *</label>
                            <input type="hidden" id="latitude"
                                value="{{ isset($eventDetail['latitude']) && $eventDetail['latitude'] != '' ? $eventDetail['latitude'] : '' }}" />
                            <input type="hidden" id="longitude"
                                value="{{ isset($eventDetail['longitude']) && $eventDetail['longitude'] != '' ? $eventDetail['longitude'] : '' }}" />
                            <div id="map"></div>

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
                        <lable for="address1" id="event-address1-error" class="error"></lable>
                    </div>
                    <div class="col-12 mb-4 ckeckedAddress"
                        style="{{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? '' : 'display:none' }}">
                        <div class="input-form location-icon">
                            <input type="text" class="form-control inputText" id="address2" name="address2"
                                required=""
                                value="{{ isset($eventDetail['address_2']) && $eventDetail['address_2'] != '' ? $eventDetail['address_2'] : '' }}">
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
                    <div class="col-12 mb-4 ckeckedAddress"
                        style="{{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? '' : 'display:none' }}">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="city" name="city"
                                oninput="clearError(this)" required=""
                                value="{{ isset($eventDetail['city']) && $eventDetail['city'] != '' ? $eventDetail['city'] : '' }}">
                            <label for="select-label" class="form-label input-field floating-label select-label">City
                                *</label>

                        </div>
                        <lable for="city" id="event-city-error" class="error"></lable>
                    </div>
                    <div class="col-6 mb-4 ckeckedAddress"
                        style="{{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? '' : 'display:none' }}">
                        <div class="input-form">
                            <input type="text" class="form-control inputText" id="state" name="state"
                                oninput="clearError(this)"
                                value="{{ isset($eventDetail['state']) && $eventDetail['state'] != '' ? $eventDetail['state'] : '' }}"
                                required="">

                            <label for="select-label" class="form-label input-field floating-label select-label">State
                                *</label>

                        </div>
                        <lable for="city" id="event-state-error" class="error"></lable>
                    </div>
                    <div class="col-6 mb-4 ckeckedAddress"
                        style="{{ isset($eventDetail['address_1']) && $eventDetail['address_1'] != '' ? '' : 'display:none' }}">
                        <div class="input-form">
                            {{-- <input type="number" class="form-control inputText" id="zipcode" name="zipcode"
                                oninput="clearError(this)" required="" value="{{(isset($eventDetail['zip_code']) && $eventDetail['zip_code'] != '')?$eventDetail['zip_code']:''}}"> --}}
                            <input type="text" class="form-control inputText" id="zipcode" name="zipcode"
                                oninput="this.value = this.value.replace(/[^0-9]/g, ''); clearError(this)"
                                required=""
                                value="{{ isset($eventDetail['zip_code']) && $eventDetail['zip_code'] != '' ? $eventDetail['zip_code'] : '' }}">

                            <label for="select-label" class="form-label input-field floating-label select-label">Zip
                                Code *</label>

                        </div>
                        <lable for="city" id="event-zipcode-error" class="error"></lable>
                    </div>
                    <div class="col-lg-12">
                        <div class="input-form">
                            <textarea name="message_to_guests" class="form-control inputText" id="message_to_guests" style="resize:none;">{{ isset($eventDetail['message_to_guests']) && $eventDetail['message_to_guests'] != '' ? $eventDetail['message_to_guests'] : '' }}</textarea>
                            <label for="code" class="form-label input-field floating-label textarea-label">Message
                                to Guests</label>
                        </div>
                    </div>
                    <!-- Modal -->




                    <div class="col-lg-12 mt-3">
                        @if (isset($eventDetail['is_draft_save']) &&
                                $eventDetail['is_draft_save'] == '0' &&
                                (isset($eventDetail['id']) && $eventDetail['id'] != ''))
                            <div class="guest-checkout new-edit-save-btn">
                                <div>
                                    <a href="#" class="cmn-btn edit_checkout">Save Changes</a>
                                </div>
                            </div>
                        @else
                            <div class="design-seting">
                                <a href="#" class="d-flex" id="next_design">
                                    <span>
                                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M10.0002 13.2797L5.65355 8.93306C5.14022 8.41973 5.14022 7.57973 5.65355 7.06639L10.0002 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                    <h5 class="ms-2">Edit Design</h5>
                                </a>
                                <button type="button" class="d-flex footer-bottom-btn" id="next_guest_step">
                                    <h5 class="me-2 guestBtn"  style="color: #b5b8bf !important;">Next: Guests</h5>
                                    <span><svg class="guestBtn" style="color: #b5b8bf !important;" width="16" height="16" viewBox="0 0 16 16" fill="none"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M5.93994 13.2797L10.2866 8.93306C10.7999 8.41973 10.7999 7.57973 10.2866 7.06639L5.93994 2.71973"
                                                stroke="#0F172A" stroke-width="1.5" stroke-miterlimit="10"
                                                stroke-linecap="round" stroke-linejoin="round"></path>
                                        </svg>
                                    </span>
                                </button>
                            </div>
                        @endif
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
            <input type="hidden" id="firstActivityTime"
                value="{{ isset($eventDetail['events_schedule_list']->event_start_date) && $eventDetail['events_schedule_list']->event_start_date != '' ? Carbon::parse($eventDetail['events_schedule_list']->event_start_date)->format('Ymd') : '' }}">
        </div>
        <!-- Add your sidebar content here -->
        <div class="supportive-div activity_bar">
            @if (isset($eventDetail['events_schedule_list']) && !empty($eventDetail['events_schedule_list']))
                @php
                    $currentDate = $eventDetail['events_schedule_list']->event_start_date;
                    $i = 0;
                @endphp
                @while (strtotime($currentDate) <= strtotime($eventDetail['events_schedule_list']->event_end_date))
                    <div class="activity-schedule-wrp">
                        <div class="activity-schedule-head">
                            @php
                                $date = Carbon::parse($eventDetail['events_schedule_list']->event_start_date);
                                $schedule_start_time = Carbon::parse($eventDetail['events_schedule_list']->start_time);
                                $schedule_end_time = Carbon::parse($eventDetail['rsvp_end_time']);
                                $endDate = new DateTime($eventDetail['end_date']);
                                $formattedDate = $endDate->format('Ymd');
                                $i++;
                            @endphp
                            <h3>{{ $date->format('l - F j, Y') }}</h3>
                        </div>
                        <div class="activity-schedule-inner new_event_detail_form">
                            {{-- <form> --}}
                            @if ($eventDetail['events_schedule_list']->event_start_date == $currentDate)
                                <h4>Event Start</h4>
                            @endif
                            <div class="row">
                                @if ($eventDetail['events_schedule_list']->event_start_date == $currentDate)
                                    <div class="col-12 mb-4">
                                        <div class="form-group">
                                            <label>Start Time</label>
                                            <div class="input-group time ">
                                                <input class="form-control timepicker" placeholder="HH:MM AM/PM"
                                                    id="ac-start-time" name="ac-start-time" oninput="clearError()"
                                                    value="{{ $schedule_start_time->format('g:i A') }}"
                                                    required="" readonly />
                                                <span class="input-group-append input-group-addon"><span
                                                        class="input-group-text"><svg width="21" height="20"
                                                            viewBox="0 0 21 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                                stroke="#64748B" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path
                                                                d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                                stroke="#64748B" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg></span></span>
                                            </div>
                                        </div>
                                    </div>
                                @endif
                                <div class="accordion" id="accordionExample">
                                    <div class="accordion-item">
                                        <div class="accordion-header">
                                            <button class="accordion-button collapsed" type="button"
                                                data-bs-toggle="collapse"
                                                data-bs-target="#collapse{{ Carbon::parse($currentDate)->format('Ymd') }}">
                                                <div>
                                                    Activities <span
                                                        class="total_activity-{{ Carbon::parse($currentDate)->format('Ymd') }}">({{ count($eventDetail['events_schedule_list']->data) }})</span>
                                                </div>
                                                <i class="fa-solid fa-angle-down"></i>
                                            </button>
                                            <div class="accordion-button-icons add_more_activity"
                                                data-activity="add_activity_{{ $i }}"
                                                data-id="{{ Carbon::parse($currentDate)->format('Ymd') }}">
                                                <i class="fa-solid fa-circle-plus"></i>
                                            </div>
                                        </div>
                                        {{-- <div id="collapseOne" class="accordion-collapse collapse" data-bs-parent="#accordionExample"> --}}
                                        <div id="collapse{{ Carbon::parse($currentDate)->format('Ymd') }}"
                                            class="accordion-collapse collapse show"
                                            data-bs-parent="#accordionExample">
                                            <div class="accordion-body new_activity"
                                                id="{{ Carbon::parse($currentDate)->format('Ymd') }}"
                                                data-id="{{ Carbon::parse($currentDate)->format('Y-m-d') }}">
                                                @php
                                                    $i = 1;
                                                    $count = 1;
                                                @endphp
                                                @if (!empty($eventDetail['events_schedule_list']->data))
                                                    @foreach ($eventDetail['events_schedule_list']->data as $data)
                                                        @if ($currentDate == $data['event_date'])
                                                            <div class="activity-main-wrp mb-3 {{ Carbon::parse($currentDate)->format('Y-m-d') }} event_all_activity_list"
                                                                data-id="{{ $data['id'] }}"
                                                                id="{{ $data['id'] }}">
                                                                <h3>Activity <span
                                                                        class="activity-count-{{ Carbon::parse($currentDate)->format('Y-m-d') }} activity-count">{{ $count }}</span>
                                                                    <span class="ms-auto">
                                                                        <svg class="delete_activity"
                                                                            data-id="{{ $data['id'] }}"
                                                                            data-class="{{ Carbon::parse($currentDate)->format('Y-m-d') }}"
                                                                            data-total_activity="{{ Carbon::parse($currentDate)->format('Ymd') }}"
                                                                            width="20" height="20"
                                                                            viewBox="0 0 20 20" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M17.5 4.98356C14.725 4.70856 11.9333 4.56689 9.15 4.56689C7.5 4.56689 5.85 4.65023 4.2 4.81689L2.5 4.98356"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M7.08325 4.1415L7.26659 3.04984C7.39992 2.25817 7.49992 1.6665 8.90825 1.6665H11.0916C12.4999 1.6665 12.6083 2.2915 12.7333 3.05817L12.9166 4.1415"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M15.7084 7.6167L15.1667 16.0084C15.0751 17.3167 15.0001 18.3334 12.6751 18.3334H7.32508C5.00008 18.3334 4.92508 17.3167 4.83341 16.0084L4.29175 7.6167"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M8.6084 13.75H11.3834"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M7.91675 10.4165H12.0834"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                    </span>
                                                                </h3>
                                                                <div class="row all_activity">
                                                                    <div class="col-12 mb-4">
                                                                        <div class="input-form position-relative">
                                                                            <input type="text"
                                                                                class="form-control inputText"
                                                                                id="description" name="description[]"
                                                                                required=""
                                                                                value="{{ $data['activity_title'] }}" />
                                                                            <label for="description"
                                                                                class="input-field floating-label select-label">Description</label>
                                                                        </div>
                                                                        <label class="error-message"
                                                                            id="desc-error-{{ Carbon::parse($currentDate)->format('Y-m-d') }}"></label>
                                                                    </div>
                                                                    <div class="col-6 mb-4">
                                                                        <div class="form-group">
                                                                            <label>Start Time</label>
                                                                            <div class="input-group time ">
                                                                                <input
                                                                                    class="form-control timepicker activity_start_time"
                                                                                    id="activity-start-time"
                                                                                    name="activity-start-time[]"
                                                                                    placeholder="HH:MM AM/PM"
                                                                                    required="" readonly
                                                                                    value="{{ $data['start_time'] }}" /><span
                                                                                    class="input-group-append input-group-addon"><span
                                                                                        class="input-group-text">
                                                                                        <svg width="21"
                                                                                            height="20"
                                                                                            viewBox="0 0 21 20"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                                                                stroke="#64748B"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path
                                                                                                d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                                                                stroke="#64748B"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                        </svg></span></span>
                                                                            </div>
                                                                            <label class="error-message"
                                                                                id="start-error-{{ Carbon::parse($currentDate)->format('Y-m-d') }}"></label>
                                                                        </div>
                                                                        {{-- <div class="input-form">
                                                                        <input type="text" class="form-control timepicker inputText" id="start-time" name="start-time" required="" value="{{$data['start_time']}}">
                                                                        <label for="start-time" class="form-label input-field floating-label select-label">Start Time</label>
                                                                    </div> --}}
                                                                    </div>
                                                                    <div class="col-6 mb-4">
                                                                        <div class="form-group">
                                                                            <label>End Time</label>
                                                                            <div class="input-group time ">
                                                                                <input
                                                                                    class="form-control timepicker activity_end_time"
                                                                                    name="activity-end-time[]"
                                                                                    placeholder="HH:MM AM/PM"
                                                                                    required="" readonly
                                                                                    value="{{ $data['end_time'] }}" /><span
                                                                                    class="input-group-append input-group-addon"><span
                                                                                        class="input-group-text"><svg
                                                                                            width="21"
                                                                                            height="20"
                                                                                            viewBox="0 0 21 20"
                                                                                            fill="none"
                                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                                            <path
                                                                                                d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                                                                stroke="#64748B"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                            <path
                                                                                                d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                                                                stroke="#64748B"
                                                                                                stroke-width="1.5"
                                                                                                stroke-linecap="round"
                                                                                                stroke-linejoin="round" />
                                                                                        </svg></span></span>
                                                                            </div>
                                                                            <label class="error-message"
                                                                                id="end-error-{{ Carbon::parse($currentDate)->format('Y-m-d') }}"></label>
                                                                        </div>
                                                                        {{-- <div class="input-form">
                                                                        <input type="text" class="form-control timepicker inputText"
                                                                            id="start-time" name="start-time" required="" value="{{$data['end_time']}}">
                                                                        <label for="start-time"
                                                                            class="form-label input-field floating-label select-label">End
                                                                            Time</label>
                                                                    </div> --}}
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            @php
                                                                $i++;
                                                                $count++;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                @else
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                         
                            <div class="ac-end-time" style="display: block;">
                                <input type="hidden" id="LastEndTime" value="{{ $formattedDate }}">
                                <h4 class="mt-3 ">Event Ends</h4>
                                <div class="col-12 ac-end-time" style="display: block;">
                                    <div class="form-group">
                                        <label>End Time</label>
                                        <div class="input-group time ">
                                            <input class="form-control end_timepicker"
                                                placeholder="HH:MM AM/PM" id="ac-end-time" name="ac-end-time"
                                                oninput="clearError()"
                                                value="{{ $schedule_end_time->format('g:i A') }}"
                                                required="" readonly=""><span
                                                class="input-group-append input-group-addon"><span
                                                    class="input-group-text"><svg width="21"
                                                        height="20" viewBox="0 0 21 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                            stroke="#64748B" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                        </path>
                                                        <path
                                                            d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                            stroke="#64748B" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round">
                                                        </path>
                                                    </svg></span></span>
                                        </div>
                                    </div>


                                </div>
                            </div>

                            <div class="activity-schedule-inner-btn">
                                <button class="cmn-btn" id="save_activity_schedule">
                                    Save
                                </button>
                            </div>
                            {{-- </form> --}}
                        </div>
                    </div>
                    @php
                        $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                    @endphp
                @endwhile
            @else
                @php
                    $start_date = '';
                    $end_date = '';
                    $event_date = '';
                    if (isset($eventDetail['start_date']) && $eventDetail['start_date'] != '') {
                        $start_date = Carbon::parse($eventDetail['start_date'])->format('m-d-Y');
                    }
                    if (isset($eventDetail['end_date']) && $eventDetail['end_date'] != '') {
                        $end_date = Carbon::parse($eventDetail['end_date'])->format('m-d-Y');
                    }
                @endphp
                @php

                    $currentDate =
                        isset($eventDetail['start_date']) && $eventDetail['start_date'] != ''
                            ? $eventDetail['start_date']
                            : date('m-d-Y');
                    $i = 0;
                @endphp
                @if (isset($eventDetail['end_date']) && $eventDetail['end_date'] != '')


                    @while (strtotime($currentDate) <= strtotime($eventDetail['end_date']))
                        <div class="activity-schedule-wrp">
                            <div class="activity-schedule-head">
                                @php
                                    $date = Carbon::parse($currentDate);
                                    $schedule_start_time = Carbon::parse($eventDetail['rsvp_start_time']);
                                  
                                    $i++;
                                @endphp
                                <h3>{{ $date->format('l - F j, Y') }}</h3>
                            </div>
                            <div class="activity-schedule-inner new_event_detail_form">
                                @if ($eventDetail['start_date'] == $currentDate)
                                    <h4>Event Start</h4>
                                @endif
                                <div class="row">
                                    @if ($eventDetail['start_date'] == $currentDate)
                                        <div class="col-12 mb-4">
                                            <div class="form-group">
                                                <label>Start Time</label>
                                                <div class="input-group time ">
                                                    <input class="form-control timepicker" placeholder="HH:MM AM/PM"
                                                        id="ac-start-time" name="ac-start-time"
                                                        oninput="clearError()"
                                                        value="{{ $schedule_start_time->format('g:i A') }}"
                                                        required="" readonly />
                                                    <span class="input-group-append input-group-addon"><span
                                                            class="input-group-text"><svg width="21"
                                                                height="20" viewBox="0 0 21 20" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M18.8334 9.99984C18.8334 14.5998 15.1 18.3332 10.5 18.3332C5.90002 18.3332 2.16669 14.5998 2.16669 9.99984C2.16669 5.39984 5.90002 1.6665 10.5 1.6665C15.1 1.6665 18.8334 5.39984 18.8334 9.99984Z"
                                                                    stroke="#64748B" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path
                                                                    d="M13.5917 12.65L11.0083 11.1083C10.5583 10.8416 10.1917 10.2 10.1917 9.67497V6.2583"
                                                                    stroke="#64748B" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg></span></span>
                                                </div>
                                            </div>
                                        </div>
                                    @endif
                                    <div class="accordion" id="accordionExample">
                                        <div class="accordion-item">
                                            <div class="accordion-header">
                                                <button class="accordion-button collapsed" type="button"
                                                    data-bs-toggle="collapse"
                                                    data-bs-target="#collapse{{ Carbon::parse($currentDate)->format('Ymd') }}">
                                                    <div>
                                                        Activities <span
                                                            class="total_activity-{{ Carbon::parse($currentDate)->format('Ymd') }}">(0)</span>
                                                    </div>
                                                    <i class="fa-solid fa-angle-down"></i>
                                                </button>
                                                <div class="accordion-button-icons add_more_activity"
                                                    data-activity="add_activity_{{ $i }}"
                                                    data-id="{{ Carbon::parse($currentDate)->format('Ymd') }}">
                                                    <i class="fa-solid fa-circle-plus"></i>
                                                </div>
                                            </div>
                                            <div id="collapse{{ Carbon::parse($currentDate)->format('Ymd') }}"
                                                class="accordion-collapse collapse show"
                                                data-bs-parent="#accordionExample">
                                                <div class="accordion-body new_activity"
                                                    id="{{ Carbon::parse($currentDate)->format('Ymd') }}"
                                                    data-id="{{ Carbon::parse($currentDate)->format('Y-m-d') }}">
                                                    @php
                                                        $i = 1;
                                                        $count = 1;
                                                    @endphp
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                 
                                </div>
                                <div class="activity-schedule-inner-btn">
                                    <button class="cmn-btn" id="save_activity_schedule">
                                        Save
                                    </button>
                                </div>
                                {{-- </form> --}}
                            </div>
                        </div>
                        @php
                            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
                        @endphp
                    @endwhile
                @endif
            @endif
        </div>
    </div>
</div>

<div class="modal fade deleteModal" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header justify-content-center">
                <div class="delete-img">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22C17.5 22 22 17.5 22 12C22 6.5 17.5 2 12 2C6.5 2 2 6.5 2 12C2 17.5 6.5 22 12 22Z"
                            stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 8V13" stroke="white" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M11.9946 16H12.0036" stroke="white" stroke-width="2" stroke-linecap="round"
                            stroke-linejoin="round" />
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
                <button type="button" class="btn cancel-btn-createEvent" data-bs-dismiss="modal"
                    data-url="{{ route('home') }}">Exit</button>
                <button type="button" class="btn continue-btn" data-bs-dismiss="modal">Continue Editing</button>
            </div>
        </div>
    </div>
</div>
