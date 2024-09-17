{{dd($message);}}
<div class="chat-data d-flex align-items-start">
    <div class="user-img position-relative">
        @if($message['receiverProfile']!=="")
        <img class="img-fluid user-image user-img-{{@$message['contactId']}}" data-id={{@$message['contactId']}} src="{{@$message['receiverProfile']}}" alt="user img">
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
    <a href="javascript:" class="user-detail d-flex ms-3">
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
    <div class="dropdown ms-auto">
        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown">
            <svg width="5" height="18" viewBox="0 0 5 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M1.5 9C1.5 9.26522 1.60536 9.51957 1.79289 9.70711C1.98043 9.89464 2.23478 10 2.5 10C2.76522 10 3.01957 9.89464 3.20711 9.70711C3.39464 9.51957 3.5 9.26522 3.5 9C3.5 8.73478 3.39464 8.48043 3.20711 8.29289C3.01957 8.10536 2.76522 8 2.5 8C2.23478 8 1.98043 8.10536 1.79289 8.29289C1.60536 8.48043 1.5 8.73478 1.5 9Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.5 16C1.5 16.2652 1.60536 16.5196 1.79289 16.7071C1.98043 16.8946 2.23478 17 2.5 17C2.76522 17 3.01957 16.8946 3.20711 16.7071C3.39464 16.5196 3.5 16.2652 3.5 16C3.5 15.7348 3.39464 15.4804 3.20711 15.2929C3.01957 15.1054 2.76522 15 2.5 15C2.23478 15 1.98043 15.1054 1.79289 15.2929C1.60536 15.4804 1.5 15.7348 1.5 16Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                <path d="M1.5 2C1.5 2.26522 1.60536 2.51957 1.79289 2.70711C1.98043 2.89464 2.23478 3 2.5 3C2.76522 3 3.01957 2.89464 3.20711 2.70711C3.39464 2.51957 3.5 2.26522 3.5 2C3.5 1.73478 3.39464 1.48043 3.20711 1.29289C3.01957 1.10536 2.76522 1 2.5 1C2.23478 1 1.98043 1.10536 1.79289 1.29289C1.60536 1.48043 1.5 1.73478 1.5 2Z" stroke="#64748B" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
            </svg>
        </button>
        <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="#">Action</a></li>
            <li><a class="dropdown-item" href="#">Another action</a></li>
            <li><a class="dropdown-item" href="#">Something else here</a></li>
        </ul>
    </div>
</div>