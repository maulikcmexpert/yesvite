@php
$counter = 0;
@endphp


@foreach ( $user_list as $guest_user)
@php
$counter++;
if($counter > 4){
break;
}
$isDisplaynone  = (isset($guest_user['isAlready']) && $guest_user['isAlready']=="1")?'display:none':'';
@endphp
@if($guest_user['prefer_by']=="email")
<div class="guest-contact invited_user user_id-{{$guest_user['id']}} responsive_invite_user" data-id={{$guest_user['id']}} id="">
    <div class="guest-img mobile-guest-icon">
        <!-- <img src="./assets/image/user-img.svg" alt="guest-img"> -->
        @if ($guest_user['profile'] != '')
        <img src="{{ asset('storage/profile/' . $guest_user['profile']) }}" alt="user-img">
        @else
        @php
        $firstInitial = !empty($guest_user['firstname']) ? strtoupper($guest_user['firstname'][0]) : '';
        $lastInitial = !empty($guest_user['lastname']) ? strtoupper($guest_user['lastname'][0]) : '';
        $initials = $firstInitial . $lastInitial;
        $fontColor = 'fontcolor' . $firstInitial;
        @endphp
        <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
        @endif
        <a href="#" class="close" id="delete_invited_user" data-id="user-{{$guest_user['id']}}" data-userid="{{$guest_user['id']}}" style="{{$isDisplaynone}}">
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
    </div>
    <h6 class="guest-name">{{$guest_user['firstname']}} {{$guest_user['lastname']}}</h6>
</div>
@elseif ($guest_user['prefer_by']=="phone")
<div class="guest-contact invited_user user_id_tel-{{$guest_user['id']}} responsive_invite_user" data-id={{$guest_user['id']}} id="">
    <div class="guest-img mobile-guest-icon">
        <!-- <img src="./assets/image/user-img.svg" alt="guest-img"> -->
        @if ($guest_user['profile'] != '')
        <img src="{{ asset('storage/profile/' . $guest_user['profile']) }}" alt="user-img">
        @else
        @php
        $firstInitial = !empty($guest_user['firstname']) ? strtoupper($guest_user['firstname'][0]) : '';
        $lastInitial = !empty($guest_user['lastname']) ? strtoupper($guest_user['lastname'][0]) : '';
        $initials = $firstInitial . $lastInitial;
        $fontColor = 'fontcolor' . $firstInitial;
        @endphp
        <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
        @endif
        <a href="#" class="close" id="delete_invited_user_tel" data-id="user_tel-{{$guest_user['id']}}" data-userid="{{$guest_user['id']}}" style="{{$isDisplaynone}}">
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
    </div>
    <h6 class="guest-name">{{$guest_user['firstname']}} {{$guest_user['lastname']}}</h6>
</div>
@endif
@endforeach

@if($counter > 4)
@php
$counter = count($user_list) - 4;
@endphp
<div class="guest-contact all_user_list">
    <div class="guest-img">
        <span class="update_user_count">+{{$counter}}</span>
    </div>
    <span class="all-contact">See all</span>
</div>
@endif