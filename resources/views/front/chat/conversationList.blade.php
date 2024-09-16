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
<li class="{{$i == 0 ?'active':''}} msg-list conversation-{{$message['conversationId']}} {{@$message['isPin']=='1'?'pinned':''}}  unarchived-list" data-userId="{{@$message['contactId']}}" data-msgKey={{$message['conversationId']}} data-group={{@$message['group']}}>
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
                <h3>{{$message['contactName']}}xzxsxdsddssd</h3>
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
    </div>
</li>
@php
$i++;
@endphp