<div class="guest-user-list-wrp invite-contact-wrp yesvite_contact see_all_invite_data">
@if(!empty($yesvite_all_invite))
    @foreach ($yesvite_all_invite['all_invited_users'] as $user)
    <div class="invite-contact yes-contact">
                    <a href="#" class="invite-img">
                    @if ($user['profile'] != '')
                                    <img src="{{ $user['profile']}}" alt="user-img">
                                @else
                                    @php
                                        $firstInitial = !empty($user['first_name'])
                                            ? strtoupper($user['first_name'][0])
                                            : '';
                                        $lastInitial = !empty($user['last_name'])
                                            ? strtoupper($user['last_name'][0])
                                            : '';
                                        $initials = $firstInitial . $lastInitial;
                                        $fontColor = 'fontcolor' . $firstInitial;
                                    @endphp
                                    <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                @endif
                                                                </a>
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#" class="invite-user-name yesvite-search" data-bs-toggle="modal" data-bs-target="#editguest" data-profile="https://yesvite.cmexpertiseinfotech.in/public/storage/profile/100_profile.jpg?v=82" data-search="fitsz Simon">
                                {{$user['first_name']}}{{$user['last_name']}}</a>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <div class="invite-mail-data faild-content">
                                <div class="d-flex align-items-center">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z" stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25" stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                        @if($user['prefer_by']=="email")
                                            <h6>{{$user['email']}}</h6>
                                        @else
                                            <h6>{{$user['phone_number']}}</h6>
                                        @endif

                                                                </div>
                            </div>
                            <!-- <div class="ms-auto">
                                <input class="form-check-input failed-checkout contact-checkbox email-checkbox" type="checkbox" data-id="100" data-name="fitsz" data-prefer="email" data-last="Simon" data-email="fitsz@yopmail.com" data-profile="https://yesvite.cmexpertiseinfotech.in/public/storage/profile/100_profile.jpg?v=82" data-phone="" data-type="email" checked="" disabled="">
                            </div> -->
                        </div>
                                        </div>
    </div>
    @endforeach
@endif

@if(!empty($users_data))
    @foreach ($users_data as $user)
    <div class="invite-contact yes-contact">
                    <a href="#" class="invite-img">
                    @if ($user['profile'] != '')
                                    <img src="{{ $user['profile']}}" alt="user-img">
                                @else
                                    @php
                                        $firstInitial = !empty($user['first_name'])
                                            ? strtoupper($user['first_name'][0])
                                            : '';
                                        $lastInitial = !empty($user['last_name'])
                                            ? strtoupper($user['last_name'][0])
                                            : '';
                                        $initials = $firstInitial . $lastInitial;
                                        $fontColor = 'fontcolor' . $firstInitial;
                                    @endphp
                                    <h5 class="{{ $fontColor }}"> {{ $initials }}</h5>
                                @endif
                                                                </a>
                    <div class="w-100">
                        <div class="d-flex justify-content-between align-items-center">
                            <a href="#" class="invite-user-name yesvite-search" data-bs-toggle="modal" data-bs-target="#editguest" data-profile="https://yesvite.cmexpertiseinfotech.in/public/storage/profile/100_profile.jpg?v=82" data-search="fitsz Simon">
                                {{$user['first_name']}}{{$user['last_name']}}</a>
                        </div>
                        <div class="d-flex align-items-center mt-1">
                            <div class="invite-mail-data faild-content">
                                <div class="d-flex align-items-center">
                                    <svg width="14" height="14" viewBox="0 0 14 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z" stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                        <path d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25" stroke="black" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                        @if($user['prefer_by']=="email")
                                            <h6>{{$user['email']}}</h6>
                                        @else
                                            <h6>{{$user['phone_number']}}</h6>
                                        @endif                                                                       

                                                                </div>
                            </div>
                            <!-- <div class="ms-auto">
                                <input class="form-check-input failed-checkout contact-checkbox email-checkbox" type="checkbox" data-id="100" data-name="fitsz" data-prefer="email" data-last="Simon" data-email="fitsz@yopmail.com" data-profile="https://yesvite.cmexpertiseinfotech.in/public/storage/profile/100_profile.jpg?v=82" data-phone="" data-type="email" checked="" disabled="">
                            </div> -->
                        </div>
                                        </div>
    </div>
    @endforeach
@endif
</div>