<x-front.advertise />

<!-- ============ contact-details ========== -->
<section class="contact-details">
    <div class="container">
        <div class="row">

            <x-front.sidebar1 :profileData="$user" />
            <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-7">
                <div class="contact-list">
                    <nav class="breadcrumb-nav" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Account Settings</li>
                        </ol>
                    </nav>
                    <div class="contact-title">
                        <h3>Account Settings</h3>

                    </div>
                </div>
                <div class="account-setting">
                    <div class="account-data">
                        <div class="d-flex align-items-center">
                            <div class="user-img">
                                @if($user->profile!="")
                                <img src="{{$user->profile}}" alt="user-img">
                                @else
                                @php $initials = strtoupper($user->firstname[0]) . strtoupper($user->lastname[0]);
                                $fontColor = "fontcolor" . strtoupper($user->firstname[0]);
                                @endphp
                                <h5 class="{{$fontColor}}"> {{ $initials }}</h5>
                                @endif
                            </div>
                            <div class="user-name">
                                <div class="d-flex align-items-center">
                                    <h3>{{$user->firstname.' '.$user->lastname}}</h3>
                                    {{-- @if($user->subscribe_status == false)
                                    <span class="free">Free</span>
                                    @else
                                    <span>Pro Year</span>
                                    @endif --}}
                                </div>
                                <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                                <p>Member Since: {{$user->join_date}}</p>
                            </div>
                        </div>

                    </div>
                    <div class="pro-account d-flex align-items-center justify-content-between">
                        <div class="w-100">
                            <div>
                                <h4>Available Credits:</h4>
                                {{-- <div class="d-flex align-items-center mb-1">
                                    <span>
                                        <svg width="16" height="13" viewBox="0 0 16 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M2 12.3385C0.895431 12.3385 0 11.4431 0 10.3385V3.27195C0 2.90315 0.38563 2.66127 0.717651 2.8218L3.99023 4.40415C4.20677 4.50885 4.46716 4.44454 4.61006 4.25107L7.59782 0.206116C7.79767 -0.0644616 8.20232 -0.0644622 8.40218 0.206115L11.3899 4.25107C11.5328 4.44454 11.7932 4.50885 12.0098 4.40415L15.2823 2.8218C15.6144 2.66127 16 2.90315 16 3.27195V10.3385C16 11.4431 15.1046 12.3385 14 12.3385H2Z" fill="#FCCD1E" />
                                        </svg>
                                    </span>


                                    @if($user->subscribe_status == false)
                                    <h5>No Plan Found</h5>
                                    @else
                                    <h5>Pro Account</h5>
                                    @endif
                                </div> --}}
                                {{-- <div class="exp-wrp">
                                    <span>Exp: {{$user->join_date}}</span>
                                    <!-- <a href="#">Click to change plan</a> -->
                                </div> --}}
                                <div class="account-setting-credit-wrp">
                                    <div class="credits_balance_amout_wrp">
                                        <h5>
                                            <img src="{{asset('assets/front/image/credit-coin-img.png')}}" alt="">
                                            <span class="available-coins">{{$user->coins}}</span>
                                        </h5>
                                        <h6>Last Recharge : <span id="lastRecharge">{{$user->lastRecharge}}</span></h6>
                                    </div>
                                </div>
                                <div class="account-setting-credit-button-wrp">
                                    <a href="{{ route('profile.transaction')}}" class="cmn-btn credit-hisotry-btn">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.9974 1.66797C5.40573 1.66797 1.66406 5.40964 1.66406 10.0013C1.66406 14.593 5.40573 18.3346 9.9974 18.3346C14.5891 18.3346 18.3307 14.593 18.3307 10.0013C18.3307 5.40964 14.5891 1.66797 9.9974 1.66797ZM13.6224 12.9763C13.5057 13.1763 13.2974 13.2846 13.0807 13.2846C12.9724 13.2846 12.8641 13.2596 12.7641 13.193L10.1807 11.6513C9.53906 11.268 9.06406 10.4263 9.06406 9.68464V6.26797C9.06406 5.9263 9.3474 5.64297 9.68906 5.64297C10.0307 5.64297 10.3141 5.9263 10.3141 6.26797V9.68464C10.3141 9.98464 10.5641 10.4263 10.8224 10.5763L13.4057 12.118C13.7057 12.293 13.8057 12.6763 13.6224 12.9763Z" fill="white" fill-opacity="0.5"/>
                                        </svg>
                                        Transactions
                                    </a>
                                    <button id="buycredits" type="button" class="cmn-btn">
                                        <svg width="20" height="20" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.9974 1.66797C5.40573 1.66797 1.66406 5.40964 1.66406 10.0013C1.66406 14.593 5.40573 18.3346 9.9974 18.3346C14.5891 18.3346 18.3307 14.593 18.3307 10.0013C18.3307 5.40964 14.5891 1.66797 9.9974 1.66797ZM13.3307 10.6263H10.6224V13.3346C10.6224 13.6763 10.3391 13.9596 9.9974 13.9596C9.65573 13.9596 9.3724 13.6763 9.3724 13.3346V10.6263H6.66406C6.3224 10.6263 6.03906 10.343 6.03906 10.0013C6.03906 9.65964 6.3224 9.3763 6.66406 9.3763H9.3724V6.66797C9.3724 6.3263 9.65573 6.04297 9.9974 6.04297C10.3391 6.04297 10.6224 6.3263 10.6224 6.66797V9.3763H13.3307C13.6724 9.3763 13.9557 9.65964 13.9557 10.0013C13.9557 10.343 13.6724 10.6263 13.3307 10.6263Z" fill="#CF3CB0"/>
                                        </svg>
                                        Buy Credits
                                    </button>
                                </div>
                            </div>
                        </div>
                        {{-- <span>
                            <svg width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#fff" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </span> --}}
                    </div>
                    {{-- <div class="general-wrap mb-0">
                        <h5 class="border-bottom pt-0">PURCHASE INFO</h5>
                        <a href="{{ route('profile.transaction')}}" class="d-flex align-items-center public-view border-bottom">
                            <h6>Transactions</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a> --}}
                        {{-- <a href="{{route('account_settings.messagePrivacy')}}" class="d-flex align-items-center public-view border-bottom">
                            <h6>Messaging Privacy</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a> --}}
                    {{-- </div> --}}
                    <div class="general-wrap mb-0">
                        <h5 class="border-bottom">General</h5>
                        <div class="d-flex align-items-center justify-content-between border-bottom">
                            <h6>Upload photos only via Wi-Fi</h6>
                            <div class="toggle-button-cover ">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">

                                        <input type="checkbox" name="photo_via_wifi" value="1" id="photo_via_wifi" class="checkbox" {{($user->photo_via_wifi == '1')?'checked':''}} />
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex align-items-center justify-content-between border-bottom">
                            <h6>Show profile photo only to friends</h6>
                            <div class="toggle-button-cover">
                                <div class="button-cover">
                                    <div class="button r" id="button-1">

                                        <input type="checkbox" name="show_profile_photo_only_frds" value="1" id="show_profile_photo_only_frds" class="checkbox" {{($user->show_profile_photo_only_frds == '1')?'checked':''}} />
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <a href="{{route('account_settings.notificationSetting')}}" class="d-flex align-items-center public-view border-bottom">
                            {{-- <h6>Notifications & Reminders Settings</h6> --}}
                            <h6>Notifications Settings</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                    {{-- <div class="general-wrap privacy-wrap m-0">
                        <h5 class="border-bottom pt-0">Privacy</h5>
                        <div class="radio-wrapper">
                            <div class="form-check d-flex align-items-center justify-content-between border-bottom">
                                <label class="form-check-label mb-0" for="flexRadioDefault1">
                                    <h6>Anyone</h6>
                                </label>
                                <input class="form-check-input visible" type="radio" name="visible" value="3" id="flexRadioDefault1" {{ ($user->visible == '3')?'checked':'' }}>
                            </div>
                            <div class="form-check d-flex align-items-center justify-content-between border-bottom">
                                <label class="form-check-label mb-0" for="flexRadioDefault2">
                                    <h6>Only guest from event</h6>
                                </label>
                                <input class="form-check-input visible" type="radio" name="visible" value="1" id="flexRadioDefault2" {{ ($user->visible == '1')?'checked':'' }}>
                            </div>
                            <div class="form-check d-flex align-items-center justify-content-between border-bottom">
                                <label class="form-check-label mb-0" for="flexRadioDefault3">
                                    <h6>No One</h6>
                                </label>
                                <input class="form-check-input visible" type="radio" name="visible" value="2" id="flexRadioDefault3" {{ ($user->visible == '2')?'checked':'' }}>
                            </div>
                        </div>
                    </div> --}}
                    {{-- <div class="general-wrap mb-0">
                        <h5 class="border-bottom pt-0">About</h5>
                        <a href="#" class="d-flex align-items-center public-view border-bottom">
                            <h6>Do not sell my account</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div> --}}
                    <div class="general-wrap mb-0">
                        <h5 class="border-bottom pt-0">PROFILE | MESSAGING PRIVACY</h5>
                        <a href="{{ route('profile.privacy')}}" class="d-flex align-items-center public-view border-bottom">
                            <h6>Profile Privacy</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="{{route('account_settings.messagePrivacy')}}" class="d-flex align-items-center public-view border-bottom">
                            <h6>Messaging Privacy</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>

                    <div class="general-wrap mb-0">
                        <h5 class="border-bottom pt-0">SECURITY</h5>
                        <a href="{{route('profile.change_password')}}" class="d-flex align-items-center public-view border-bottom">
                            <h6>Change Password</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                        <a href="javascript:;" class="d-flex align-items-center public-view border-bottom  delete-btn" data-bs-toggle="modal" data-bs-target="#myModal">
                            <h6>Delete Account</h6>
                            <svg class="ms-auto" width="7" height="14" viewBox="0 0 7 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M0.939941 12.2802L5.28661 7.93355C5.79994 7.42021 5.79994 6.58021 5.28661 6.06688L0.939941 1.72021" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========== delete-model ========== -->
<div class="modal fade" id="myModal">
    <div class="modal-dialog delete-model-wrap">
        <div class="modal-content">
            <!-- Modal Header -->
            <div class="modal-header">
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>

            <!-- Modal body -->
            <div class="modal-body">
                <div class="delete-icon">
                    <svg width="24" height="25" viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path d="M12 22.5C17.5 22.5 22 18 22 12.5C22 7 17.5 2.5 12 2.5C6.5 2.5 2 7 2 12.5C2 18 6.5 22.5 12 22.5Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M12 8.5V13.5" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path d="M11.9946 16.5H12.0036" stroke="white" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>
                <form method="GET" action="{{route('account.delete')}}" id="DeleteAccount">
                    <div class="contents">
                        <h4>Delete Account</h4>
                        <p>Are you sure want to delete your account? You will lose all your data, photos, messages. and
                            can’t be recovered.</p>
                        <p>Please confirm by typing <strong>“DELETE”</strong> in the text box below then tapping
                            Confirm.</p>
                        <input type="text" placeholder="DELETE" name="type_word" id="type_word">
                    </div>
                    <div class="d-flex justify-content-between gap-3">
                        <button class="cmn-btn cancel-btn" type="button">Cancel</button>
                        <button class="cmn-btn confirm-btn loaderbtn" id="DeleteBtn" type="submit">Delete Account</button>
                    </div>
            </div>
        </div>
    </div>
</div>
