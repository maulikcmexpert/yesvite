<x-front.advertise />

<section class="contact-details profile-details public-wrp">
    <div class="container">
        <div class="row">
            <div class="contact-list">
                <nav class="breadcrumb-nav" aria-label="breadcrumb">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{route('home')}}">Home</a></li>
                        <li class="breadcrumb-item"><a href="{{route('profile')}}">Profile</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Public Profile</li>
                    </ol>
                </nav>
                <div class="contact-title">
                    <h3>Public Profile</h3>
                </div>
                <div class="profile-wrapper">
                    <div class="profile-img">
                        <img src="{{$user->bg_profile}}" alt="bgImg" class="bg-img">
                        <div class="user-img">
                            <img src="{{$user->profile}}" alt="user-img">
                        </div>
                    </div>
                    <div class="profile-content">
                        <div class="user-name">
                            <div class="d-flex">
                                <h3>{{$user->firstname.' '.$user->lastname}}</h3>
                                <span>PRO</span>
                            </div>
                            <div class="user-location justify-content-start ">
                                <div>
                                    <span>
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M9.20898 12.0106H3.79232C1.81523 12.0106 0.677734 10.8731 0.677734 8.896V5.10433C0.677734 3.12725 1.81523 1.98975 3.79232 1.98975H9.20898C11.1861 1.98975 12.3236 3.12725 12.3236 5.10433V8.896C12.3236 10.8731 11.1861 12.0106 9.20898 12.0106ZM3.79232 2.80225C2.24315 2.80225 1.49023 3.55516 1.49023 5.10433V8.896C1.49023 10.4452 2.24315 11.1981 3.79232 11.1981H9.20898C10.7582 11.1981 11.5111 10.4452 11.5111 8.896V5.10433C11.5111 3.55516 10.7582 2.80225 9.20898 2.80225H3.79232Z" fill="#64748B" />
                                            <path d="M6.50082 7.47148C6.04582 7.47148 5.5854 7.33065 5.23332 7.04356L3.5379 5.6894C3.36456 5.54856 3.33207 5.29398 3.4729 5.12064C3.61373 4.94731 3.86832 4.91481 4.04165 5.05565L5.73706 6.40981C6.14873 6.74023 6.84748 6.74023 7.25915 6.40981L8.95457 5.05565C9.1279 4.91481 9.3879 4.94189 9.52332 5.12064C9.66415 5.29398 9.63707 5.55398 9.45832 5.6894L7.7629 7.04356C7.41623 7.33065 6.95582 7.47148 6.50082 7.47148Z" fill="#64748B" />
                                        </svg>
                                    </span>
                                    <a href="mailto:{{$user->email}}">{{$user->email}}</a>
                                </div>
                                @if($user->city != NULL)
                                <div>
                                    <span>
                                        <svg width="13" height="14" viewBox="0 0 13 14" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M7.98893 6.86475H5.00977C4.78768 6.86475 4.60352 6.68058 4.60352 6.4585C4.60352 6.23641 4.78768 6.05225 5.00977 6.05225H7.98893C8.21102 6.05225 8.39518 6.23641 8.39518 6.4585C8.39518 6.68058 8.21102 6.86475 7.98893 6.86475Z" fill="#64748B" />
                                            <path d="M6.50089 12.8285C5.69922 12.8285 4.89214 12.5252 4.26381 11.9239C2.66589 10.3856 0.900056 7.93183 1.56631 5.01225C2.16756 2.3635 4.48047 1.17725 6.50089 1.17725C6.50089 1.17725 6.50089 1.17725 6.50631 1.17725C8.52672 1.17725 10.8396 2.3635 11.4409 5.01766C12.1017 7.93725 10.3359 10.3856 8.73797 11.9239C8.10964 12.5252 7.30256 12.8285 6.50089 12.8285ZM6.50089 1.98975C4.92464 1.98975 2.89881 2.82933 2.36256 5.191C1.77756 7.74225 3.38089 9.94141 4.83256 11.3335C5.76964 12.2381 7.23756 12.2381 8.17464 11.3335C9.62089 9.94141 11.2242 7.74225 10.6501 5.191C10.1084 2.82933 8.07714 1.98975 6.50089 1.98975Z" fill="#64748B" />
                                        </svg>
                                    </span>
                                    <span>{{$user->city.','.$user->state}}</span>
                                </div>
                                @endif
                            </div>
                            <h6>{{$user->about_me}} </h6>
                            <p>Member Since: {{$user->join_date}}</p>
                        </div>
                        <div class="user-gallery">
                            <div>
                                <h4>{{formatNumber($user->events)}}</h4>
                                <p>Events</p>
                            </div>
                            <div>
                                <h4>{{formatNumber($user->photos)}}</h4>
                                <p>Photos</p>
                            </div>
                            <div>
                                <h4>{{formatNumber($user->comments)}}</h4>
                                <p>Comments</p>
                            </div>
                        </div>
                        @if($user->gender != NULL)
                        <div class="user-contact-data">
                            <div>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M7.99984 8.00016C9.84079 8.00016 11.3332 6.50778 11.3332 4.66683C11.3332 2.82588 9.84079 1.3335 7.99984 1.3335C6.15889 1.3335 4.6665 2.82588 4.6665 4.66683C4.6665 6.50778 6.15889 8.00016 7.99984 8.00016Z" fill="#CBD5E1" />
                                    <path d="M7.99994 9.6665C4.65994 9.6665 1.93994 11.9065 1.93994 14.6665C1.93994 14.8532 2.08661 14.9998 2.27327 14.9998H13.7266C13.9133 14.9998 14.0599 14.8532 14.0599 14.6665C14.0599 11.9065 11.3399 9.6665 7.99994 9.6665Z" fill="#CBD5E1" />
                                </svg>
                                <span>{{$user->gender}}</span>
                            </div>
                        </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>