@php

use Carbon\Carbon;
$i = 0;
$message['unReadCount'] = @$message['unRead']==true && @$message['unReadCount']==0 ?1 : @$message['unReadCount'];
@endphp

@if(!isset($message['contactName']))

@endisset
{{-- @if ($i == 0)
    <input type="hidden" class="selected_id" value="{{$k}}"/>
<input type="hidden" class="selected_message" value="{{@$message['contactId']}}" />
<input type="hidden" class="selected_name" value="" />

@endif --}}
{{-- @dd($message); --}}
<li class="{{$i == 0 ?'active':''}} msg-list conversation-{{$message['conversationId']}} {{@$message['isPin']=='1'?'pinned':''}}  unarchived-list" data-search="{{$message['contactName']}}" data-userId="{{@$message['contactId']}}" data-msgKey={{$message['conversationId']}} data-group={{@$message['group']}}>
    <div class="me-2 d-none bulk-check">
        <input class="form-check-input" type="checkbox" name="checked_conversation[]" value="{{$message['conversationId']}}" isGroup="{{@$message['group']}}">
    </div>
    <div class="chat-data d-flex align-items-center">
        {{-- <div class="me-2">
            <input class="form-check-input" type="checkbox" name="Guest RSVP’s" checked="">
        </div> --}}
        <div class="user-img position-relative">
            @if($message['receiverProfile']!=="")
            <img class="img-fluid user-image user-img-{{@$message['contactId']}}" data-id={{@$message['contactId']}} src="{{$message['receiverProfile']}}" alt="user img">
            @else
            @php
            $contactName = $message['contactName'];
            $words = explode(' ', $contactName);
            $initials = '';

            foreach ($words as $word) {
            $initials .= strtoupper(substr($word, 0, 1));
            }
            $initials = substr($initials, 0, 2);
            $fontColor = "fontcolor" . strtoupper($initials[0]);
            @endphp
            <h5 class="{{$fontColor}}">{{$initials}}</h5>
            @endif

            <span class="active"></span>
        </div>
        <a href="#" class="user-detail d-flex ms-3">
            <div class="d-flex align-items-start flex-column">
                <h3>{{$message['contactName']}}</h3>
                @php
                $timestamp = $message['timeStamp'] ?? now()->timestamp;
                $timeAgo = Carbon::createFromTimestampMs($timestamp)->diffForHumans();
                @endphp
                <div class="d-flex align-items-center justify-content-between">
                    <span class="last-message">{{$message['lastMessage']}}</span>

                </div>
            </div>

        </a>
        <div class="ms-auto">
            <h6 class="ms-2 time-ago"> {{ $timeAgo }}</h6>
            <div class="d-flex align-items-center justify-content-end">
                <span class="badge ms-2 {{@$message['unReadCount'] == 0 ? 'd-none' : ''}}">{{@$message['unReadCount']}}</span>
                <span class="ms-2 d-flex mt-1 align-items-start justify-content-end pin-svg {{@$message['isPin']=='1'?'':'d-none'}}">
                    <svg width="11" height="17" viewBox="0 0 11 17" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M8.83333 0.5C9.04573 0.500236 9.25003 0.581566 9.40447 0.727374C9.55892 0.873181 9.65186 1.07246 9.66431 1.2845C9.67676 1.49653 9.60777 1.70532 9.47145 1.86819C9.33512 2.03107 9.14175 2.13575 8.93083 2.16083L8.83333 2.16667V6.13667L10.4117 9.29417C10.4552 9.38057 10.4834 9.47391 10.495 9.57L10.5 9.66667V11.3333C10.5 11.5374 10.425 11.7344 10.2894 11.887C10.1538 12.0395 9.96688 12.137 9.76417 12.1608L9.66667 12.1667H6.33333V15.5C6.3331 15.7124 6.25177 15.9167 6.10596 16.0711C5.96015 16.2256 5.76087 16.3185 5.54884 16.331C5.3368 16.3434 5.12802 16.2744 4.96514 16.1381C4.80226 16.0018 4.69759 15.8084 4.6725 15.5975L4.66667 15.5V12.1667H1.33333C1.12922 12.1666 0.932219 12.0917 0.77969 11.9561C0.627161 11.8204 0.529714 11.6335 0.505833 11.4308L0.5 11.3333V9.66667C0.500114 9.57004 0.517032 9.47416 0.55 9.38333L0.588333 9.29417L2.16667 6.135V2.16667C1.95427 2.16643 1.74997 2.0851 1.59553 1.93929C1.44108 1.79349 1.34814 1.59421 1.33569 1.38217C1.32324 1.17014 1.39223 0.96135 1.52855 0.798473C1.66488 0.635595 1.85825 0.53092 2.06917 0.505833L2.16667 0.5H8.83333Z" fill="#94A3B8" />
                    </svg>
                </span>
            </div>
        </div>
        {{-- <div class="dropdown ms-auto">
            <button type="button" class="btn btn-primary dropdown-toggle"
                data-bs-toggle="dropdown">
                <svg width="5" height="18" viewBox="0 0 5 18" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z"
                        stroke="#64748B" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z"
                        stroke="#64748B" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                    <path
                        d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z"
                        stroke="#64748B" stroke-width="2" stroke-linecap="round"
                        stroke-linejoin="round" />
                </svg>
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#"><svg class="me-1" width="11"
                            height="17" viewBox="0 0 11 17" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M8.83333 0.5C9.04573 0.500236 9.25003 0.581566 9.40447 0.727374C9.55892 0.873181 9.65186 1.07246 9.66431 1.2845C9.67676 1.49653 9.60777 1.70532 9.47145 1.86819C9.33512 2.03107 9.14175 2.13575 8.93083 2.16083L8.83333 2.16667V6.13667L10.4117 9.29417C10.4552 9.38057 10.4834 9.47391 10.495 9.57L10.5 9.66667V11.3333C10.5 11.5374 10.425 11.7344 10.2894 11.887C10.1538 12.0395 9.96688 12.137 9.76417 12.1608L9.66667 12.1667H6.33333V15.5C6.3331 15.7124 6.25177 15.9167 6.10596 16.0711C5.96015 16.2256 5.76087 16.3185 5.54884 16.331C5.3368 16.3434 5.12802 16.2744 4.96514 16.1381C4.80226 16.0018 4.69759 15.8084 4.6725 15.5975L4.66667 15.5V12.1667H1.33333C1.12922 12.1666 0.932219 12.0917 0.77969 11.9561C0.627161 11.8204 0.529714 11.6335 0.505833 11.4308L0.5 11.3333V9.66667C0.500114 9.57004 0.517032 9.47416 0.55 9.38333L0.588333 9.29417L2.16667 6.135V2.16667C1.95427 2.16643 1.74997 2.0851 1.59553 1.93929C1.44108 1.79349 1.34814 1.59421 1.33569 1.38217C1.32324 1.17014 1.39223 0.96135 1.52855 0.798473C1.66488 0.635595 1.85825 0.53092 2.06917 0.505833L2.16667 0.5H8.83333Z"
                                fill="#94A3B8" />
                        </svg> Pin Message</a></li>
                <li><a class="dropdown-item" href="#">
                        <svg class="me-1" width="21" height="20" viewBox="0 0 21 20"
                            fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M18.003 5.60742C17.9863 5.60742 17.9613 5.60742 17.9363 5.60742C13.528 5.16575 9.12798 4.99909 4.76965 5.44075L3.06965 5.60742C2.71965 5.64075 2.41131 5.39075 2.37798 5.04075C2.34465 4.69075 2.59465 4.39075 2.93631 4.35742L4.63631 4.19075C9.06965 3.74075 13.5613 3.91575 18.0613 4.35742C18.403 4.39075 18.653 4.69909 18.6196 5.04075C18.5946 5.36575 18.3196 5.60742 18.003 5.60742Z"
                                fill="#94A3B8" />
                            <path
                                d="M7.58656 4.76602C7.55322 4.76602 7.51989 4.76602 7.47822 4.75768C7.14489 4.69935 6.91156 4.37435 6.96989 4.04102L7.15322 2.94935C7.28656 2.14935 7.46989 1.04102 9.41156 1.04102H11.5949C13.5449 1.04102 13.7282 2.19102 13.8532 2.95768L14.0366 4.04102C14.0949 4.38268 13.8616 4.70768 13.5282 4.75768C13.1866 4.81602 12.8616 4.58268 12.8116 4.24935L12.6282 3.16602C12.5116 2.44102 12.4866 2.29935 11.6032 2.29935H9.41989C8.53656 2.29935 8.51989 2.41602 8.39489 3.15768L8.20322 4.24102C8.15322 4.54935 7.88656 4.76602 7.58656 4.76602Z"
                                fill="#94A3B8" />
                            <path
                                d="M13.174 18.9577H7.82402C4.91569 18.9577 4.79902 17.3493 4.70735 16.0493L4.16569 7.65766C4.14069 7.316 4.40735 7.016 4.74902 6.991C5.09902 6.97433 5.39069 7.23266 5.41569 7.57433L5.95735 15.966C6.04902 17.2327 6.08235 17.7077 7.82402 17.7077H13.174C14.924 17.7077 14.9574 17.2327 15.0407 15.966L15.5824 7.57433C15.6074 7.23266 15.9074 6.97433 16.249 6.991C16.5907 7.016 16.8574 7.30766 16.8324 7.65766L16.2907 16.0493C16.199 17.3493 16.0824 18.9577 13.174 18.9577Z"
                                fill="#94A3B8" />
                            <path
                                d="M11.8844 14.375H9.10938C8.76771 14.375 8.48438 14.0917 8.48438 13.75C8.48438 13.4083 8.76771 13.125 9.10938 13.125H11.8844C12.226 13.125 12.5094 13.4083 12.5094 13.75C12.5094 14.0917 12.226 14.375 11.8844 14.375Z"
                                fill="#64748B" />
                            <path
                                d="M12.5807 11.041H8.41406C8.0724 11.041 7.78906 10.7577 7.78906 10.416C7.78906 10.0743 8.0724 9.79102 8.41406 9.79102H12.5807C12.9224 9.79102 13.2057 10.0743 13.2057 10.416C13.2057 10.7577 12.9224 11.041 12.5807 11.041Z"
                                fill="#64748B" />
                        </svg>
                        Delete Message</a></li>
            </ul>
        </div> --}}
        <div class="dropdown ms-auto text-end">
            <button type="button" class="btn btn-primary dropdown-toggle usr-list-more" data-bs-toggle="dropdown" aria-expanded="false">
                <svg width="5" height="18" viewBox="0 0 5 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                    <path d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                </svg>
            </button>
            <ul class="dropdown-menu" style="">
                
                <li>

                    <a class="dropdown-item pin-single-conversation" href="#" changewith="{{@$message['isPin']=='1'?'0':'1'}}">

                        <svg class="me-2 pin-self-icn {{@$message['isPin']=='1'?'':'d-none'}}" width="25" height="24" viewBox="0 0 25 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.3908 4.36734L12.1481 8.60998L7.90549 10.0242L6.49128 11.4384L13.5623 18.5095L14.9766 17.0953L16.3908 12.8526L20.6334 8.60998" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M10.0234 14.9746L6.4879 18.5101" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M15.6797 3.66211L21.3365 9.31896" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>

                        <svg class="me-2 unpin-single-conversation unpin-self-icn {{@$message['isPin']=='1'?'d-none':''}}" xmlns="http://www.w3.org/2000/svg" height="24px" viewBox="0 -960 960 960" width="24px" fill="#5f6368" style="display: none;">
                            <path d="M680-840v80h-40v327l-80-80v-247H400v87l-87-87-33-33v-47h400ZM480-40l-40-40v-240H240v-80l80-80v-46L56-792l56-56 736 736-58 56-264-264h-6v240l-40 40ZM354-400h92l-44-44-2-2-46 46Zm126-193Zm-78 149Z"></path>
                        </svg>

                        <span>{{@$message['isPin']=='1'?'pin':'unpin'}}</span>
                    </a>

                </li>
                <li><a class="dropdown-item mute-conversation" href="#" changewith="1">
                        <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M6.33073 14.7926H4.66406C2.6474 14.7926 1.53906 13.6843 1.53906 11.6676V8.33428C1.53906 6.31762 2.6474 5.20928 4.66406 5.20928H5.85573C6.0474 5.20928 6.23906 5.15095 6.40573 5.05095L8.83906 3.52595C10.0557 2.76762 11.2391 2.62595 12.1724 3.14262C13.1057 3.65928 13.6141 4.73428 13.6141 6.17595V6.97595C13.6141 7.31762 13.3307 7.60095 12.9891 7.60095C12.6474 7.60095 12.3641 7.31762 12.3641 6.97595V6.17595C12.3641 5.22595 12.0724 4.51762 11.5641 4.24262C11.0557 3.95928 10.3057 4.08428 9.4974 4.59262L7.06406 6.10928C6.70573 6.34262 6.28073 6.45928 5.85573 6.45928H4.66406C3.3474 6.45928 2.78906 7.01762 2.78906 8.33428V11.6676C2.78906 12.9843 3.3474 13.5426 4.66406 13.5426H6.33073C6.6724 13.5426 6.95573 13.826 6.95573 14.1676C6.95573 14.5093 6.6724 14.7926 6.33073 14.7926Z" fill="#94A3B8"></path>
                            <path d="M10.9577 17.1577C10.2993 17.1577 9.57434 16.9244 8.84934 16.466C8.55767 16.2827 8.46601 15.8993 8.64934 15.6077C8.83267 15.316 9.21601 15.2243 9.50767 15.4077C10.316 15.9077 11.066 16.041 11.5743 15.7577C12.0827 15.4743 12.3743 14.766 12.3743 13.8243V10.791C12.3743 10.4493 12.6577 10.166 12.9993 10.166C13.341 10.166 13.6243 10.4493 13.6243 10.791V13.8243C13.6243 15.2577 13.1077 16.341 12.1827 16.8577C11.8077 17.0577 11.391 17.1577 10.9577 17.1577Z" fill="#94A3B8"></path>
                            <path d="M15.5002 13.9586C15.3669 13.9586 15.2419 13.9169 15.1252 13.8336C14.8502 13.6253 14.7919 13.2336 15.0002 12.9586C16.0502 11.5586 16.2752 9.70026 15.6002 8.09193C15.4669 7.77526 15.6169 7.4086 15.9336 7.27526C16.2502 7.14193 16.6169 7.29193 16.7502 7.6086C17.6002 9.62526 17.3086 11.9669 16.0002 13.7169C15.8752 13.8753 15.6919 13.9586 15.5002 13.9586Z" fill="#94A3B8"></path>
                            <path d="M17.0237 16.0423C16.8903 16.0423 16.7653 16.0007 16.6487 15.9173C16.3737 15.709 16.3153 15.3173 16.5237 15.0423C18.307 12.6673 18.6987 9.48399 17.5487 6.74232C17.4153 6.42565 17.5653 6.05899 17.882 5.92565C18.207 5.79232 18.5653 5.94232 18.6987 6.25899C20.0237 9.40899 19.5737 13.059 17.5237 15.7923C17.407 15.959 17.2153 16.0423 17.0237 16.0423Z" fill="#94A3B8"></path>
                            <path d="M2.16979 18.9576C2.01146 18.9576 1.85313 18.8992 1.72812 18.7742C1.48646 18.5326 1.48646 18.1325 1.72812 17.8909L18.3948 1.22422C18.6365 0.982552 19.0365 0.982552 19.2781 1.22422C19.5198 1.46589 19.5198 1.86589 19.2781 2.10755L2.61146 18.7742C2.48646 18.8992 2.32812 18.9576 2.16979 18.9576Z" fill="#94A3B8"></path>
                        </svg>
                        <span>Mute</span></a>
                </li>
                <li><a class="dropdown-item archive-conversation" href="#" changewith="1">
                        <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M16.75 7.68359V15.0003C16.75 16.6669 16.3333 17.5003 14.25 17.5003H6.75C4.66667 17.5003 4.25 16.6669 4.25 15.0003V7.68359" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M4.66406 3.33398H16.3307C17.9974 3.33398 18.8307 3.85482 18.8307 4.89648V5.93815C18.8307 6.97982 17.9974 7.50065 16.3307 7.50065H4.66406C2.9974 7.50065 2.16406 6.97982 2.16406 5.93815V4.89648C2.16406 3.85482 2.9974 3.33398 4.66406 3.33398Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                            <path d="M8.98438 10.834H12.0177" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"></path>
                        </svg>
                        <span>Archive</span></a>
                </li>
                <li><a class="dropdown-item delete-conversation" href="#">
                        <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M18.003 5.60742C17.9863 5.60742 17.9613 5.60742 17.9363 5.60742C13.528 5.16575 9.12798 4.99909 4.76965 5.44075L3.06965 5.60742C2.71965 5.64075 2.41131 5.39075 2.37798 5.04075C2.34465 4.69075 2.59465 4.39075 2.93631 4.35742L4.63631 4.19075C9.06965 3.74075 13.5613 3.91575 18.0613 4.35742C18.403 4.39075 18.653 4.69909 18.6196 5.04075C18.5946 5.36575 18.3196 5.60742 18.003 5.60742Z" fill="#94A3B8"></path>
                            <path d="M7.58656 4.76602C7.55322 4.76602 7.51989 4.76602 7.47822 4.75768C7.14489 4.69935 6.91156 4.37435 6.96989 4.04102L7.15322 2.94935C7.28656 2.14935 7.46989 1.04102 9.41156 1.04102H11.5949C13.5449 1.04102 13.7282 2.19102 13.8532 2.95768L14.0366 4.04102C14.0949 4.38268 13.8616 4.70768 13.5282 4.75768C13.1866 4.81602 12.8616 4.58268 12.8116 4.24935L12.6282 3.16602C12.5116 2.44102 12.4866 2.29935 11.6032 2.29935H9.41989C8.53656 2.29935 8.51989 2.41602 8.39489 3.15768L8.20322 4.24102C8.15322 4.54935 7.88656 4.76602 7.58656 4.76602Z" fill="#94A3B8"></path>
                            <path d="M13.174 18.9577H7.82402C4.91569 18.9577 4.79902 17.3493 4.70735 16.0493L4.16569 7.65766C4.14069 7.316 4.40735 7.016 4.74902 6.991C5.09902 6.97433 5.39069 7.23266 5.41569 7.57433L5.95735 15.966C6.04902 17.2327 6.08235 17.7077 7.82402 17.7077H13.174C14.924 17.7077 14.9574 17.2327 15.0407 15.966L15.5824 7.57433C15.6074 7.23266 15.9074 6.97433 16.249 6.991C16.5907 7.016 16.8574 7.30766 16.8324 7.65766L16.2907 16.0493C16.199 17.3493 16.0824 18.9577 13.174 18.9577Z" fill="#94A3B8"></path>
                            <path d="M11.8844 14.375H9.10938C8.76771 14.375 8.48438 14.0917 8.48438 13.75C8.48438 13.4083 8.76771 13.125 9.10938 13.125H11.8844C12.226 13.125 12.5094 13.4083 12.5094 13.75C12.5094 14.0917 12.226 14.375 11.8844 14.375Z" fill="#94A3B8"></path>
                            <path d="M12.5807 11.041H8.41406C8.0724 11.041 7.78906 10.7577 7.78906 10.416C7.78906 10.0743 8.0724 9.79102 8.41406 9.79102H12.5807C12.9224 9.79102 13.2057 10.0743 13.2057 10.416C13.2057 10.7577 12.9224 11.041 12.5807 11.041Z" fill="#94A3B8"></path>
                        </svg>
                        Delete</a>
                </li>
                <li><a class="dropdown-item block-conversation" href="#" blocked="false" user="294">
                        <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12.9141 18.9577H8.08073C7.33907 18.9577 6.38906 18.566 5.87239 18.041L2.45573 14.6243C1.93073 14.0993 1.53906 13.1493 1.53906 12.416V7.58269C1.53906 6.84102 1.93073 5.89103 2.45573 5.37436L5.87239 1.95769C6.39739 1.43269 7.3474 1.04102 8.08073 1.04102H12.9141C13.6557 1.04102 14.6057 1.43269 15.1224 1.95769L18.5391 5.37436C19.0641 5.89936 19.4557 6.84935 19.4557 7.58269V12.416C19.4557 13.1577 19.0641 14.1077 18.5391 14.6243L15.1224 18.041C14.5974 18.566 13.6557 18.9577 12.9141 18.9577ZM8.08073 2.29102C7.6724 2.29102 7.03906 2.54935 6.75572 2.84102L3.33907 6.25769C3.05573 6.54936 2.78906 7.17435 2.78906 7.58269V12.416C2.78906 12.8243 3.0474 13.4577 3.33907 13.741L6.75572 17.1577C7.04739 17.441 7.6724 17.7077 8.08073 17.7077H12.9141C13.3224 17.7077 13.9557 17.4493 14.2391 17.1577L17.6557 13.741C17.9391 13.4493 18.2057 12.8243 18.2057 12.416V7.58269C18.2057 7.17435 17.9474 6.54102 17.6557 6.25769L14.2391 2.84102C13.9474 2.55769 13.3224 2.29102 12.9141 2.29102H8.08073Z" fill="#94A3B8"></path>
                            <path d="M4.6151 16.5254C4.45677 16.5254 4.29844 16.467 4.17344 16.342C3.93177 16.1004 3.93177 15.7004 4.17344 15.4587L15.9568 3.67539C16.1984 3.43372 16.5984 3.43372 16.8401 3.67539C17.0818 3.91706 17.0818 4.31706 16.8401 4.55872L5.05677 16.342C4.93177 16.467 4.77344 16.5254 4.6151 16.5254Z" fill="#94A3B8"></path>
                        </svg>
                        <span>Block User</span></a>
                </li>
                <li><a class="dropdown-item report-conversation" href="#">
                        <svg class="me-2" width="21" height="20" viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path d="M10.4974 18.9577C5.55573 18.9577 1.53906 14.941 1.53906 9.99935C1.53906 5.05768 5.55573 1.04102 10.4974 1.04102C15.4391 1.04102 19.4557 5.05768 19.4557 9.99935C19.4557 14.941 15.4391 18.9577 10.4974 18.9577ZM10.4974 2.29102C6.2474 2.29102 2.78906 5.74935 2.78906 9.99935C2.78906 14.2493 6.2474 17.7077 10.4974 17.7077C14.7474 17.7077 18.2057 14.2493 18.2057 9.99935C18.2057 5.74935 14.7474 2.29102 10.4974 2.29102Z" fill="#94A3B8"></path>
                            <path d="M10.5 11.4577C10.1583 11.4577 9.875 11.1743 9.875 10.8327V6.66602C9.875 6.32435 10.1583 6.04102 10.5 6.04102C10.8417 6.04102 11.125 6.32435 11.125 6.66602V10.8327C11.125 11.1743 10.8417 11.4577 10.5 11.4577Z" fill="#94A3B8"></path>
                            <path d="M10.4974 14.1664C10.3891 14.1664 10.2807 14.1414 10.1807 14.0997C10.0807 14.0581 9.98906 13.9997 9.90573 13.9247C9.83073 13.8414 9.7724 13.7581 9.73073 13.6497C9.68906 13.5497 9.66406 13.4414 9.66406 13.3331C9.66406 13.2247 9.68906 13.1164 9.73073 13.0164C9.7724 12.9164 9.83073 12.8247 9.90573 12.7414C9.98906 12.6664 10.0807 12.6081 10.1807 12.5664C10.3807 12.4831 10.6141 12.4831 10.8141 12.5664C10.9141 12.6081 11.0057 12.6664 11.0891 12.7414C11.1641 12.8247 11.2224 12.9164 11.2641 13.0164C11.3057 13.1164 11.3307 13.2247 11.3307 13.3331C11.3307 13.4414 11.3057 13.5497 11.2641 13.6497C11.2224 13.7581 11.1641 13.8414 11.0891 13.9247C11.0057 13.9997 10.9141 14.0581 10.8141 14.0997C10.7141 14.1414 10.6057 14.1664 10.4974 14.1664Z" fill="#94A3B8"></path>
                        </svg>
                        Report</a>
                </li>
            </ul>
        </div>
    </div>
</li>
@php
$i++;
@endphp