<x-front.advertise />
<!-- ============ contact-details ========== -->
<section class="contact-details">
    <div class="container">
        <div class="row">
            <x-front.sidebar :profileData="$user" />

            <div class="col-xxl-9 col-xl-8 col-lg-8 col-md-7">
                <div class="contact-list notification-wrap">
                    <nav class="breadcrumb-nav" aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                            <li class="breadcrumb-item"><a href="{{route('profile.account_settings')}}">Account Setting</a></li>
                            <li class="breadcrumb-item active" aria-current="page">Notification & Reminders</li>
                        </ol>
                    </nav>
                    <div class="contact-title">
                        <h3>Notification & Reminders</h3>
                    </div>

                    @if($user->user_notification_type->isNotEmpty())
                    <div class="notification">

                        <div>
                            <h6 class="title">Notifications</h6>
                            <div class="title-wrp pt-0 border-bottom d-flex align-items-center justify-content-between">
                                <div class="left-note">
                                    <h5>Type</h5>
                                </div>
                                <div class="right-note">
                                    <h5>Push</h5>
                                    <h5>Email</h5>
                                </div>
                            </div>
                            @php
                            $type = "";
                            @endphp
                            @foreach($user->user_notification_type as $value)

                            @if($value->type == 'guest_rsvp')
                            @php $type = "Guest RSVP’s";@endphp
                            @elseif($value->type == 'private_message')
                            @php $type = "Private Messages";@endphp
                            @elseif($value->type == 'potluck_activity')
                            @php $type = "Potluck Activity";@endphp
                            @elseif($value->type == 'invitations')
                            @php $type = "Invitations";@endphp
                            @else
                            @php $type = "Wall Posts";@endphp
                            @endif
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>{{ $type}}</h5>
                                </div>
                                <div class="right-note">

                                    <input type="hidden" class="type" name="type" value="{{ $value->type }}">
                                    <div>
                                        <input class="form-check-input push" type="checkbox" value="1" name="notificationSetting[$value->type][push]" {{($value->push== '1')?'checked':''}}>
                                    </div>
                                    <div>
                                        <input class="form-check-input email" type="checkbox" value="1" name="notificationSetting[$value->type][email]" {{($value->email== '1')?'checked':''}}>
                                    </div>
                                </div>
                            </div>

                            @endforeach
                        </div>
                        <div class="reminder">
                            <div>
                                <h6 class="title border-bottom">Reminders</h6>
                                <div class="d-flex align-items-center justify-content-between border-bottom">
                                    <h6>Ask guests to upload their photos</h6>
                                    <div class="toggle-button-cover ">
                                        <div class="button-cover">
                                            <div class="button r" id="button-1">
                                                <input type="checkbox" class="checkbox" />
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between border-bottom">
                                    <h6>Complete unfinished drafts</h6>
                                    <div class="toggle-button-cover ">
                                        <div class="button-cover">
                                            <div class="button r" id="button-1">
                                                <input type="checkbox" class="checkbox" />
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @else
                    <div class="notification">

                        <div>
                            <h6 class="title">Notifications</h6>
                            <div class="title-wrp pt-0 border-bottom d-flex align-items-center justify-content-between">
                                <div class="left-note">
                                    <h5>Type</h5>
                                </div>
                                <div class="right-note">
                                    <h5>Push</h5>
                                    <h5>Email</h5>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>Guest RSVP’s</h5>
                                </div>
                                <div class="right-note">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s" checked="">
                                    </div>
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>Private Messages</h5>
                                </div>
                                <div class="right-note">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>Potluck Activity</h5>
                                </div>
                                <div class="right-note">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>Invitations</h5>
                                </div>
                                <div class="right-note">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>Wall Posts</h5>
                                </div>
                                <div class="right-note">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                </div>
                            </div>
                            <div class="d-flex align-items-center border-bottom">
                                <div class="left-note">
                                    <h5>Thank You Cards</h5>
                                </div>
                                <div class="right-note">
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                    <div>
                                        <input class="form-check-input" type="checkbox" name="Guest RSVP’s">
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="reminder">
                            <div>
                                <h6 class="title border-bottom">Reminders</h6>
                                <div class="d-flex align-items-center justify-content-between border-bottom">
                                    <h6>Ask guests to upload their photos</h6>
                                    <div class="toggle-button-cover ">
                                        <div class="button-cover">
                                            <div class="button r" id="button-1">
                                                <input type="checkbox" class="checkbox" />
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="d-flex align-items-center justify-content-between border-bottom">
                                    <h6>Complete unfinished drafts</h6>
                                    <div class="toggle-button-cover ">
                                        <div class="button-cover">
                                            <div class="button r" id="button-1">
                                                <input type="checkbox" class="checkbox" />
                                                <div class="knobs"></div>
                                                <div class="layer"></div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>