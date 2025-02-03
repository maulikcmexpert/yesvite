
@if(!empty($yesvite_all_invite))
@php
        $listing=array_merge($yesvite_all_invite['invited_user_id'],$yesvite_users_data);
$counter = 0;

@endphp
@foreach($listing as $guest_user)
@php
$counter++;
if($counter > 4){
break;
}
@endphp
    <div class="guest-users" data-id="{{$guest_user['id']}}">
                            <div class="guest-user-img">
                            @if ($guest_user['profile'] != '')
                                    <img src="{{ $guest_user['profile'] }}" alt="user-img">
                            @else
                                    @php
                                        $firstInitial = !empty($guest_user['first_name'])
                                            ? strtoupper($guest_user['first_name'][0])
                                            : '';
                                        $lastInitial = !empty($guest_user['last_name'])
                                            ? strtoupper($guest_user['last_name'][0])
                                            : '';
                                        $initials = $firstInitial . $lastInitial;
                                        $fontColor = 'fontcolor' . $firstInitial;
                                    @endphp
                                    <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                            @endif
                                <!-- <img src="./assets/image/user-img.svg" alt="guest-img"> -->
                                                                <!-- <img src="https://yesvite.cmexpertiseinfotech.in/storage/profile/73_profile.jpg?v=29" alt="user-img"> -->
                                                            <!-- <a href="#" class="close">
                                    <svg width="19" height="18" viewBox="0 0 19 18" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <rect x="1.20312" y="1" width="16" height="16" rx="8"
                                            fill="#F73C71" />
                                        <rect x="1.20312" y="1" width="16" height="16" rx="8" stroke="white"
                                            stroke-width="2" />
                                        <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </a> -->
                            </div>
                           
                            <h6 class="guest-name">{{$guest_user['first_name']}} {{$guest_user['last_name']}}</h6>
    </div>
@endforeach
@endif

@if($counter > 4)
@php
$counter = count($user_list) - 4;
@endphp
<a href="#" class="guest-user d-block yesvite add_guest_seeall">
                    <div class="guest-user-img guest-total">
                        <span class="number" id="total-selected-email" data-count="1">+{{$counter}}</span>
                        <span class="content">Total</span>
                 </div>
                 <h6>Sell all</h6>
                </a>
@endif
