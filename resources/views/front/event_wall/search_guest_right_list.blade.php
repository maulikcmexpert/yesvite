@foreach ($guestArray as $index => $guest)
                        @if ($index == 7)
                        @break
                        @endif
                            @php
                                //$user = $guest['user']; // Fetch user array
                                $firstInitial = isset($guest['first_name'][0]) ? strtoupper($guest['first_name'][0]) : '';
                                $secondInitial = isset($guest['last_name'][0]) ? strtoupper($guest['last_name'][0]) : '';
                                $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                $fontColor = 'fontcolor' . strtoupper($firstInitial);
                            @endphp
<li class="guests-listing-info contact contactslist" data-guest-id="{{ $guest['guest_id'] }}" data-is_sync="{{ $guest['is_sync'] }}">
                            <div class="d-flex align-items-center justify-content-between w-100 gap-2">
                                    <div class="posts-card-head-left guests-listing-left">
                                        <div class="posts-card-head-left-img">
                                            @if (!empty($guest['profile']))
                                                <img src="{{ $guest['profile'] }}"
                                                    alt="">
                                            @else
                                                <h5 class="{{ $fontColor }}">
                                                    {{ $initials }}
                                                </h5>
                                            @endif
                                            <span class="active-dot"></span>
                                        </div>
                                        <div class="posts-card-head-left-content contact_search"
                                            data-search = "{{ $guest['first_name'] }} {{ $guest['last_name'] }}">
                                            <h3>{{ $guest['first_name'] }} {{ $guest['last_name'] }}</h3>
                                            @if($guest['prefer_by']=="email")
                                                                            <p>{{ $guest['email'] }}</p>
                                                                        @else
                                                                            <p>{{ $guest['phone_number'] }}</p>
                                                                        @endif


                                            <input type="hidden" id="eventID" value="{{ $eventId }}">
                                            <input type="hidden" id="user_id" value="{{ $guest['id'] }}">
                                            <input type="hidden" id="sync" value="{{  $guest['is_sync']}}">
                                        </div>
                                    </div>
                                    <div class="guests-listing-right" data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}">
                                        <div class="guest_rsvp_icon" data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}">
                                        @if($guest['is_sync']=="0")
                                            @if ($guest['rsvp_status'] == '1')
                                                <!-- Approved -->
                                                <span id="approve" data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}">
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
                                                <span id="cancel" data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}">
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
                                                <span id="pending" data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}">
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
                                        @endif
                                        </div>
                                        @if ($is_host == 1)
                                          
                                                    @if($guest['is_sync']=="1")
                                                    <button type="button" ><i
                                                    class="fa-solid fa-ellipsis-vertical"
                                                    data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}"></i></button>
                                                    @else
                                                    <button type="button" data-bs-toggle="modal" data-bs-target="#editrsvp3"><i
                                                    class="fa-solid fa-ellipsis-vertical edit_rsvp_guest"
                                                    data-guest-id="{{ $guest['guest_id'] }}"  data-is_sync="{{ $guest['is_sync'] }}"></i></button>
                                                    @endif
                                        
                                        @endif
                                    </div>
                            </div>
                            @if($guest['is_sync']=="0")
                                    @if ($guest['rsvp_status'] == '1')

                                        <div class="sucess-yes">
                                                <h5 class="green">YES</h5>
                                                <div class="sucesss-cat ms-auto">
                                                    <svg width="15" height="15" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z" fill="black" fill-opacity="0.2"></path>
                                                    <path d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z" fill="black" fill-opacity="0.2"></path>
                                                    <path d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z" fill="black" fill-opacity="0.2"></path>
                                                    <path d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z" fill="black" fill-opacity="0.2"></path>
                                                    </svg>
                                                    <h5>{{$guest['adults']}} Adults</h5>
                                                    <h5>{{$guest['kids']}} Kids</h5>
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
                            @endif                
                            </li>
@endforeach