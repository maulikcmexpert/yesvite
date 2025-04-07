<x-front.advertise />
<section class="new-create-account-section new_login">
    <div class="container">
        <div class="col-12 mb-5 mt-3">
            <div class="new-create-account-head">
                <h2>Kids Birthday</h2>
                <button type="button" class="new-create-account-close-btn"
                    onclick="window.location.href='{{ route('front.home') }}'">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M1.00098 1L15 14.9991" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                        <path d="M0.999964 14.9991L14.999 1" stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                            stroke-linejoin="round" />
                    </svg>
                </button>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-lg-6">
                    <div class="new-create-account-left">
                        <div class="rsvp-slider owl-carousel owl-theme create-account-slider slider_login">
                            <div class="item">
                                <div class="rsvp-img login_img">
                                    <img src="" alt="birth-card">
                                </div>
                                <button class="image-zoom-icon zoom"><img
                                        src="{{ asset('assets/front/img/image-zoom-icon.png') }}"
                                        alt=""></button>
                            </div>
                            {{-- <div class="item">
                                <div class="rsvp-img">
                                    <img src="./assets/img/host-by-template-img.png" alt="birth-card">
                                </div>
                                <button class="image-zoom-icon"><img
                                        src="{{ asset('assets/front/img/image-zoom-icon.png') }}"
                                        alt=""></button>

                            </div>
                            <div class="item">
                                <div class="rsvp-img">
                                    <img src="./assets/img/host-by-template-img.png" alt="birth-card">
                                </div>
                                <button class="image-zoom-icon"><img
                                        src="{{ asset('assets/front/img/image-zoom-icon.png') }}"
                                        alt=""></button>
                            </div>
                            <div class="item">
                                <div class="rsvp-img">
                                    <img src="./assets/img/host-by-template-img.png" alt="birth-card">
                                </div>
                                <button class="image-zoom-icon"><img
                                        src="{{ asset('assets/front/img/image-zoom-icon.png') }}"
                                        alt=""></button>
                            </div>
                            <div class="item">
                                <div class="rsvp-img">
                                    <img src="./assets/img/host-by-template-img.png" alt="birth-card">
                                </div>
                                <button class="image-zoom-icon"><img
                                        src="{{ asset('assets/front/img/image-zoom-icon.png') }}"
                                        alt=""></button>
                            </div> --}}
                        </div>
                    </div>
                </div>
                <div class="col-lg-6">
                    <div class="new-create-account-right">
                        <div class="new-create-account-form login-form-wrap">

                            <div class="new-create-account-form-title">
                                <h2>Welcome to Yesvite</h2>
                                <h5>Create an account/login to continue event creation</h5>
                                <h6>Each new account gets 30 free credits</h6>
                                <h6>1 Credit = 1 invite</h6>
                            </div>
                            <form method="POST" id="crateEventLogin" class="d-none"
                                action="{{ route('auth.checkLogin') }}" autocomplete="off">
                                @csrf
                                <input type="hidden" name="is_login" value="false">


                                <div class="input-form">
                                    <!-- <input type="email" class="form-control inputText" id="email" name="email">
                                            <label for="email" class="form-label input-field floating-label">Email Address <span class="required">*</span></label> -->

                                    <input type="email" class="form-control" id="email" name="email"
                                        value="" autocomplete="off">
                                    <label for="email" class="floating-label">Email Address <span>*</span></label>
                                    <div class="label-error">
                                        <label id="email-error" class="error" for="email"></label>
                                    </div>
                                </div>

                                <div class="input-form">
                                    <input type="password" class="form-control inputText" id="password" name="password"
                                        value="" autocomplete="new-password">
                                    <label for="password" class="form-label input-field floating-label">Password <span
                                            class="required">*</span></label>
                                    <span toggle="#password-field"
                                        class="fa-regular fa-fw fa-eye-slash field-icon toggle-password"></span>
                                    <div class="label-error">
                                        <label id="password-error" class="error" for="password"></label>
                                    </div>
                                </div>
                                <div class="form-check mb-3">
                                    <div>
                                        <label class="form-check-label">
                                            <input class="form-check-input" type="checkbox" name="remember"> Remember
                                            me
                                        </label>
                                    </div>
                                    <a href="{{ route('auth.forgetpassword') }}">Forgot Password ?</a>
                                </div>
                                @if ($errors->any())
                                    <div class="alert alert-danger">
                                        <ul>
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <button type="button" class="btn btn-primary create_event_login_btn"
                                    id="login_user">Sign
                                    In</button>

                                <ul class="new-create-account-social">
                                    {{-- <li>
                                        <a href="#" class="facebook-color-wrp">
                                            <svg width="800px" height="800px" viewBox="0 0 100 100" version="1.1"
                                                xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <style type="text/css">
                                                    .st0 {
                                                        fill: #ffffff;
                                                    }

                                                    .st1 {
                                                        fill: #f5bb41;
                                                    }

                                                    .st2 {
                                                        fill: #2167d1;
                                                    }

                                                    .st3 {
                                                        fill: #3d84f3;
                                                    }

                                                    .st4 {
                                                        fill: #4ca853;
                                                    }

                                                    .st5 {
                                                        fill: #398039;
                                                    }

                                                    .st6 {
                                                        fill: #d74f3f;
                                                    }

                                                    .st7 {
                                                        fill: #d43c89;
                                                    }

                                                    .st8 {
                                                        fill: #b2005f;
                                                    }

                                                    .st9 {
                                                        fill: none;
                                                        stroke: #000000;
                                                        stroke-width: 3;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st10 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: none;
                                                        stroke: #000000;
                                                        stroke-width: 3;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st11 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: none;
                                                        stroke: #040404;
                                                        stroke-width: 3;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st12 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                    }

                                                    .st13 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #040404;
                                                    }

                                                    .st14 {
                                                        fill: url(#SVGID_1_);
                                                    }

                                                    .st15 {
                                                        fill: url(#SVGID_2_);
                                                    }

                                                    .st16 {
                                                        fill: url(#SVGID_3_);
                                                    }

                                                    .st17 {
                                                        fill: url(#SVGID_4_);
                                                    }

                                                    .st18 {
                                                        fill: url(#SVGID_5_);
                                                    }

                                                    .st19 {
                                                        fill: url(#SVGID_6_);
                                                    }

                                                    .st20 {
                                                        fill: url(#SVGID_7_);
                                                    }

                                                    .st21 {
                                                        fill: url(#SVGID_8_);
                                                    }

                                                    .st22 {
                                                        fill: url(#SVGID_9_);
                                                    }

                                                    .st23 {
                                                        fill: url(#SVGID_10_);
                                                    }

                                                    .st24 {
                                                        fill: url(#SVGID_11_);
                                                    }

                                                    .st25 {
                                                        fill: url(#SVGID_12_);
                                                    }

                                                    .st26 {
                                                        fill: url(#SVGID_13_);
                                                    }

                                                    .st27 {
                                                        fill: url(#SVGID_14_);
                                                    }

                                                    .st28 {
                                                        fill: url(#SVGID_15_);
                                                    }

                                                    .st29 {
                                                        fill: url(#SVGID_16_);
                                                    }

                                                    .st30 {
                                                        fill: url(#SVGID_17_);
                                                    }

                                                    .st31 {
                                                        fill: url(#SVGID_18_);
                                                    }

                                                    .st32 {
                                                        fill: url(#SVGID_19_);
                                                    }

                                                    .st33 {
                                                        fill: url(#SVGID_20_);
                                                    }

                                                    .st34 {
                                                        fill: url(#SVGID_21_);
                                                    }

                                                    .st35 {
                                                        fill: url(#SVGID_22_);
                                                    }

                                                    .st36 {
                                                        fill: url(#SVGID_23_);
                                                    }

                                                    .st37 {
                                                        fill: url(#SVGID_24_);
                                                    }

                                                    .st38 {
                                                        fill: url(#SVGID_25_);
                                                    }

                                                    .st39 {
                                                        fill: url(#SVGID_26_);
                                                    }

                                                    .st40 {
                                                        fill: url(#SVGID_27_);
                                                    }

                                                    .st41 {
                                                        fill: url(#SVGID_28_);
                                                    }

                                                    .st42 {
                                                        fill: url(#SVGID_29_);
                                                    }

                                                    .st43 {
                                                        fill: url(#SVGID_30_);
                                                    }

                                                    .st44 {
                                                        fill: url(#SVGID_31_);
                                                    }

                                                    .st45 {
                                                        fill: url(#SVGID_32_);
                                                    }

                                                    .st46 {
                                                        fill: url(#SVGID_33_);
                                                    }

                                                    .st47 {
                                                        fill: url(#SVGID_34_);
                                                    }

                                                    .st48 {
                                                        fill: url(#SVGID_35_);
                                                    }

                                                    .st49 {
                                                        fill: url(#SVGID_36_);
                                                    }

                                                    .st50 {
                                                        fill: url(#SVGID_37_);
                                                    }

                                                    .st51 {
                                                        fill: url(#SVGID_38_);
                                                    }

                                                    .st52 {
                                                        fill: url(#SVGID_39_);
                                                    }

                                                    .st53 {
                                                        fill: url(#SVGID_40_);
                                                    }

                                                    .st54 {
                                                        fill: url(#SVGID_41_);
                                                    }

                                                    .st55 {
                                                        fill: url(#SVGID_42_);
                                                    }

                                                    .st56 {
                                                        fill: url(#SVGID_43_);
                                                    }

                                                    .st57 {
                                                        fill: url(#SVGID_44_);
                                                    }

                                                    .st58 {
                                                        fill: url(#SVGID_45_);
                                                    }

                                                    .st59 {
                                                        fill: #040404;
                                                    }

                                                    .st60 {
                                                        fill: url(#SVGID_46_);
                                                    }

                                                    .st61 {
                                                        fill: url(#SVGID_47_);
                                                    }

                                                    .st62 {
                                                        fill: url(#SVGID_48_);
                                                    }

                                                    .st63 {
                                                        fill: url(#SVGID_49_);
                                                    }

                                                    .st64 {
                                                        fill: url(#SVGID_50_);
                                                    }

                                                    .st65 {
                                                        fill: url(#SVGID_51_);
                                                    }

                                                    .st66 {
                                                        fill: url(#SVGID_52_);
                                                    }

                                                    .st67 {
                                                        fill: url(#SVGID_53_);
                                                    }

                                                    .st68 {
                                                        fill: url(#SVGID_54_);
                                                    }

                                                    .st69 {
                                                        fill: url(#SVGID_55_);
                                                    }

                                                    .st70 {
                                                        fill: url(#SVGID_56_);
                                                    }

                                                    .st71 {
                                                        fill: url(#SVGID_57_);
                                                    }

                                                    .st72 {
                                                        fill: url(#SVGID_58_);
                                                    }

                                                    .st73 {
                                                        fill: url(#SVGID_59_);
                                                    }

                                                    .st74 {
                                                        fill: url(#SVGID_60_);
                                                    }

                                                    .st75 {
                                                        fill: url(#SVGID_61_);
                                                    }

                                                    .st76 {
                                                        fill: url(#SVGID_62_);
                                                    }

                                                    .st77 {
                                                        fill: none;
                                                        stroke: #000000;
                                                        stroke-width: 3;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st78 {
                                                        fill: none;
                                                        stroke: #ffffff;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st79 {
                                                        fill: #4bc9ff;
                                                    }

                                                    .st80 {
                                                        fill: #5500dd;
                                                    }

                                                    .st81 {
                                                        fill: #ff3a00;
                                                    }

                                                    .st82 {
                                                        fill: #e6162d;
                                                    }

                                                    .st83 {
                                                        fill: #f1f1f1;
                                                    }

                                                    .st84 {
                                                        fill: #ff9933;
                                                    }

                                                    .st85 {
                                                        fill: #b92b27;
                                                    }

                                                    .st86 {
                                                        fill: #00aced;
                                                    }

                                                    .st87 {
                                                        fill: #bd2125;
                                                    }

                                                    .st88 {
                                                        fill: #1877f2;
                                                    }

                                                    .st89 {
                                                        fill: #6665d2;
                                                    }

                                                    .st90 {
                                                        fill: #ce3056;
                                                    }

                                                    .st91 {
                                                        fill: #5bb381;
                                                    }

                                                    .st92 {
                                                        fill: #61c3ec;
                                                    }

                                                    .st93 {
                                                        fill: #e4b34b;
                                                    }

                                                    .st94 {
                                                        fill: #181ef2;
                                                    }

                                                    .st95 {
                                                        fill: #ff0000;
                                                    }

                                                    .st96 {
                                                        fill: #fe466c;
                                                    }

                                                    .st97 {
                                                        fill: #fa4778;
                                                    }

                                                    .st98 {
                                                        fill: #ff7700;
                                                    }

                                                    .st99 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #1f6bf6;
                                                    }

                                                    .st100 {
                                                        fill: #520094;
                                                    }

                                                    .st101 {
                                                        fill: #4477e8;
                                                    }

                                                    .st102 {
                                                        fill: #3d1d1c;
                                                    }

                                                    .st103 {
                                                        fill: #ffe812;
                                                    }

                                                    .st104 {
                                                        fill: #344356;
                                                    }

                                                    .st105 {
                                                        fill: #00cc76;
                                                    }

                                                    .st106 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #345e90;
                                                    }

                                                    .st107 {
                                                        fill: #1f65d8;
                                                    }

                                                    .st108 {
                                                        fill: #eb3587;
                                                    }

                                                    .st109 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #603a88;
                                                    }

                                                    .st110 {
                                                        fill: #e3ce99;
                                                    }

                                                    .st111 {
                                                        fill: #783af9;
                                                    }

                                                    .st112 {
                                                        fill: #ff515e;
                                                    }

                                                    .st113 {
                                                        fill: #ff4906;
                                                    }

                                                    .st114 {
                                                        fill: #503227;
                                                    }

                                                    .st115 {
                                                        fill: #4c7bd9;
                                                    }

                                                    .st116 {
                                                        fill: #69c9d0;
                                                    }

                                                    .st117 {
                                                        fill: #1b92d1;
                                                    }

                                                    .st118 {
                                                        fill: #eb4f4a;
                                                    }

                                                    .st119 {
                                                        fill: #513728;
                                                    }

                                                    .st120 {
                                                        fill: #ff6600;
                                                    }

                                                    .st121 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #b61438;
                                                    }

                                                    .st122 {
                                                        fill: #fffc00;
                                                    }

                                                    .st123 {
                                                        fill: #141414;
                                                    }

                                                    .st124 {
                                                        fill: #94d137;
                                                    }

                                                    .st125 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #f1f1f1;
                                                    }

                                                    .st126 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #66e066;
                                                    }

                                                    .st127 {
                                                        fill: #2d8cff;
                                                    }

                                                    .st128 {
                                                        fill: #f1a300;
                                                    }

                                                    .st129 {
                                                        fill: #4ba2f2;
                                                    }

                                                    .st130 {
                                                        fill: #1a5099;
                                                    }

                                                    .st131 {
                                                        fill: #ee6060;
                                                    }

                                                    .st132 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #f48120;
                                                    }

                                                    .st133 {
                                                        fill: #222222;
                                                    }

                                                    .st134 {
                                                        fill: url(#SVGID_63_);
                                                    }

                                                    .st135 {
                                                        fill: #0077b5;
                                                    }

                                                    .st136 {
                                                        fill: #ffcc00;
                                                    }

                                                    .st137 {
                                                        fill: #eb3352;
                                                    }

                                                    .st138 {
                                                        fill: #f9d265;
                                                    }

                                                    .st139 {
                                                        fill: #f5b955;
                                                    }

                                                    .st140 {
                                                        fill: #dd2a7b;
                                                    }

                                                    .st141 {
                                                        fill: #66e066;
                                                    }

                                                    .st142 {
                                                        fill: #eb4e00;
                                                    }

                                                    .st143 {
                                                        fill: #ffc794;
                                                    }

                                                    .st144 {
                                                        fill: #b5332a;
                                                    }

                                                    .st145 {
                                                        fill: #4e85eb;
                                                    }

                                                    .st146 {
                                                        fill: #58a45c;
                                                    }

                                                    .st147 {
                                                        fill: #f2bc42;
                                                    }

                                                    .st148 {
                                                        fill: #d85040;
                                                    }

                                                    .st149 {
                                                        fill: #464eb8;
                                                    }

                                                    .st150 {
                                                        fill: #7b83eb;
                                                    }
                                                </style>

                                                <g id="Layer_1" />

                                                <g id="Layer_2">
                                                    <g>
                                                        <path class="st88"
                                                            d="M50,2.5c-58.892,1.725-64.898,84.363-7.46,95l0,0h0H50h7.46l0,0C114.911,86.853,108.879,4.219,50,2.5z" />

                                                        <path class="st83"
                                                            d="M57.46,64.104h11.125l2.117-13.814H57.46v-8.965c0-3.779,1.85-7.463,7.781-7.463h6.021    c0,0,0-11.761,0-11.761c-12.894-2.323-28.385-1.616-28.722,17.66V50.29H30.417v13.814H42.54c0,0,0,33.395,0,33.396H50h7.46l0,0h0    V64.104z" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </a>
                                    </li> --}}
                                    <li>
                                        <a href="{{ url('login/google') }}?event_login=1" class="google-color-wrp">

                                            <svg width="800px" height="800px" viewBox="-3 0 262 262"
                                                xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                                                <path
                                                    d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
                                                    fill="#4285F4" />
                                                <path
                                                    d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
                                                    fill="#34A853" />
                                                <path
                                                    d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
                                                    fill="#FBBC05" />
                                                <path
                                                    d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
                                                    fill="#EB4335" />
                                            </svg>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="#" class="insta-color-wrp">

                                            <svg width="800px" height="800px" viewBox="0 0 32 32" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="2" width="28" height="28" rx="6"
                                                    fill="url(#paint0_radial_87_7153)" />
                                                <rect x="2" y="2" width="28" height="28" rx="6"
                                                    fill="url(#paint1_radial_87_7153)" />
                                                <rect x="2" y="2" width="28" height="28" rx="6"
                                                    fill="url(#paint2_radial_87_7153)" />
                                                <path
                                                    d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z"
                                                    fill="white" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z"
                                                    fill="white" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z"
                                                    fill="white" />
                                                <defs>
                                                    <radialGradient id="paint0_radial_87_7153" cx="0"
                                                        cy="0" r="1" gradientUnits="userSpaceOnUse"
                                                        gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)">
                                                        <stop stop-color="#B13589" />
                                                        <stop offset="0.79309" stop-color="#C62F94" />
                                                        <stop offset="1" stop-color="#8A3AC8" />
                                                    </radialGradient>
                                                    <radialGradient id="paint1_radial_87_7153" cx="0"
                                                        cy="0" r="1" gradientUnits="userSpaceOnUse"
                                                        gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)">
                                                        <stop stop-color="#E0E8B7" />
                                                        <stop offset="0.444662" stop-color="#FB8A2E" />
                                                        <stop offset="0.71474" stop-color="#E2425C" />
                                                        <stop offset="1" stop-color="#E2425C" stop-opacity="0" />
                                                    </radialGradient>
                                                    <radialGradient id="paint2_radial_87_7153" cx="0"
                                                        cy="0" r="1" gradientUnits="userSpaceOnUse"
                                                        gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)">
                                                        <stop offset="0.156701" stop-color="#406ADC" />
                                                        <stop offset="0.467799" stop-color="#6A45BE" />
                                                        <stop offset="1" stop-color="#6A45BE" stop-opacity="0" />
                                                    </radialGradient>
                                                </defs>
                                            </svg>
                                        </a>
                                    </li> --}}
                                </ul>
                                <div class="new-create-account-form-foot">

                                    <p>New to Yesvite?</p>
                                    <a href="#" id="create_event_register">Create an account</a>
                                </div>
                            </form>

                            <form method="POST" action="{{ route('store.register') }}" id="registerEvent"
                                autocomplete="off">
                                @csrf
                                <input type="hidden" name="is_login" value="false">
                                <input type="hidden" id="account_type" name="account_type" value="0">
                                <div class="row">
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                        <div class="input-form">
                                            <input type="text" class="form-control" id="firstname"
                                                name="firstname" value="{{ old('firstname') }}">
                                            <label for="firstname" class="floating-label">First Name
                                                <span>*</span></label>
                                            <div class="label-error">
                                                <label id="firstname-error" class="error" for="firstname"
                                                    style="color: red;"></label>
                                                @error('firstname')
                                                    <label class="error">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-6 col-sm-6 col-6">
                                        <div class="input-form">
                                            <input type="text" class="form-control" id="lastname"
                                                name="lastname" value="{{ old('lastname') }}">
                                            <label for="lastname" class="floating-label">Last Name
                                                <span>*</span></label>
                                            <div class="label-error">
                                                <label id="lastname-error" class="error" for="lastname"
                                                    style="color: red;"></label>
                                                @error('lastname')
                                                    <label class="error">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="input-form">
                                            <input type="email" class="form-control" id="email_c" name="email"
                                                value="{{ old('email') }}" autocomplete="off">
                                            <label for="email" class="floating-label">Email Address
                                                <span>*</span></label>

                                            <div class="label-error">
                                                <label id="email-error" class="error" for="email"
                                                    style="color: red;"></label>
                                                @error('email')
                                                    <label class="error">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="input-form">
                                            <input type="text" class="form-control" id="zip_code"
                                                name="zip_code" value="{{ old('zip_code') }}"
                                                oninput="this.value = this.value.replace(/[^0-9]/g, '');">
                                            <label for="Zcode" class="floating-label">Zip Code
                                                <span>*</span></label>
                                            <div class="label-error">
                                                <label id="zip_code-error" class="error" for="zip_code"
                                                    style="color: red;"></label>
                                                @error('zip_code')
                                                    <label class="error">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="input-form">
                                            <input type="password" class="form-control" id="password_c"
                                                name="password" value="{{ old('password') }}"
                                                autocomplete="new-password">
                                            <label for="password" class="floating-label">Password
                                                <span>*</span></label>
                                            <span toggle="#password-field"
                                                class="fa-regular fa-fw fa-eye-slash field-icon toggle-password"></span>
                                            <div class="label-error">
                                                <label id="password-error" class="error" for="password"
                                                    style="color: red;"></label>
                                                @error('password')
                                                    <label class="error">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="input-form mb-0">
                                            <input type="password" class="form-control" id="cpassword"
                                                name="cpassword" value="{{ old('cpassword') }}">
                                            <label for="password" class="floating-label">Confirm Password
                                                <span>*</span></label>
                                            <span toggle="#password-field"
                                                class="fa-regular fa-fw fa-eye-slash field-icon toggle-password"></span>
                                            <div class="label-error">
                                                <label id="cpassword-error" class="error" for="cpassword"
                                                    style="color: red;"></label>
                                                @error('cpassword')
                                                    <label class="error">{{ $message }}</label>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12">
                                        <div class="text-start mt-1" id="passValidation">
                                        </div>
                                    </div>
                                    <div class="col-lg-12 col-md-12 col-sm-12 col-12 mt-4 text-center">
                                        <div class="g-recaptcha" style="display: inline-block" data-sitekey="{{ env('RECAPTCHA_SITE_KEY') }}"></div>
                                        <script src="https://www.google.com/recaptcha/api.js"></script>

                                    </div>
                                    <div class="col-lg-12 mt-2">
                                        <button type="button" class="btn btn-primary createEventUser"
                                            id="createEventUser">Create account</button>
                                    </div>
                                </div>
                                <ul class="new-create-account-social">
                                    {{-- <li>
                                        <a href="#" class="facebook-color-wrp">
                                            <svg width="800px" height="800px" viewBox="0 0 100 100" version="1.1"
                                                xml:space="preserve" xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <style type="text/css">
                                                    .st0 {
                                                        fill: #ffffff;
                                                    }

                                                    .st1 {
                                                        fill: #f5bb41;
                                                    }

                                                    .st2 {
                                                        fill: #2167d1;
                                                    }

                                                    .st3 {
                                                        fill: #3d84f3;
                                                    }

                                                    .st4 {
                                                        fill: #4ca853;
                                                    }

                                                    .st5 {
                                                        fill: #398039;
                                                    }

                                                    .st6 {
                                                        fill: #d74f3f;
                                                    }

                                                    .st7 {
                                                        fill: #d43c89;
                                                    }

                                                    .st8 {
                                                        fill: #b2005f;
                                                    }

                                                    .st9 {
                                                        fill: none;
                                                        stroke: #000000;
                                                        stroke-width: 3;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st10 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: none;
                                                        stroke: #000000;
                                                        stroke-width: 3;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st11 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: none;
                                                        stroke: #040404;
                                                        stroke-width: 3;
                                                        stroke-linecap: round;
                                                        stroke-linejoin: round;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st12 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                    }

                                                    .st13 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #040404;
                                                    }

                                                    .st14 {
                                                        fill: url(#SVGID_1_);
                                                    }

                                                    .st15 {
                                                        fill: url(#SVGID_2_);
                                                    }

                                                    .st16 {
                                                        fill: url(#SVGID_3_);
                                                    }

                                                    .st17 {
                                                        fill: url(#SVGID_4_);
                                                    }

                                                    .st18 {
                                                        fill: url(#SVGID_5_);
                                                    }

                                                    .st19 {
                                                        fill: url(#SVGID_6_);
                                                    }

                                                    .st20 {
                                                        fill: url(#SVGID_7_);
                                                    }

                                                    .st21 {
                                                        fill: url(#SVGID_8_);
                                                    }

                                                    .st22 {
                                                        fill: url(#SVGID_9_);
                                                    }

                                                    .st23 {
                                                        fill: url(#SVGID_10_);
                                                    }

                                                    .st24 {
                                                        fill: url(#SVGID_11_);
                                                    }

                                                    .st25 {
                                                        fill: url(#SVGID_12_);
                                                    }

                                                    .st26 {
                                                        fill: url(#SVGID_13_);
                                                    }

                                                    .st27 {
                                                        fill: url(#SVGID_14_);
                                                    }

                                                    .st28 {
                                                        fill: url(#SVGID_15_);
                                                    }

                                                    .st29 {
                                                        fill: url(#SVGID_16_);
                                                    }

                                                    .st30 {
                                                        fill: url(#SVGID_17_);
                                                    }

                                                    .st31 {
                                                        fill: url(#SVGID_18_);
                                                    }

                                                    .st32 {
                                                        fill: url(#SVGID_19_);
                                                    }

                                                    .st33 {
                                                        fill: url(#SVGID_20_);
                                                    }

                                                    .st34 {
                                                        fill: url(#SVGID_21_);
                                                    }

                                                    .st35 {
                                                        fill: url(#SVGID_22_);
                                                    }

                                                    .st36 {
                                                        fill: url(#SVGID_23_);
                                                    }

                                                    .st37 {
                                                        fill: url(#SVGID_24_);
                                                    }

                                                    .st38 {
                                                        fill: url(#SVGID_25_);
                                                    }

                                                    .st39 {
                                                        fill: url(#SVGID_26_);
                                                    }

                                                    .st40 {
                                                        fill: url(#SVGID_27_);
                                                    }

                                                    .st41 {
                                                        fill: url(#SVGID_28_);
                                                    }

                                                    .st42 {
                                                        fill: url(#SVGID_29_);
                                                    }

                                                    .st43 {
                                                        fill: url(#SVGID_30_);
                                                    }

                                                    .st44 {
                                                        fill: url(#SVGID_31_);
                                                    }

                                                    .st45 {
                                                        fill: url(#SVGID_32_);
                                                    }

                                                    .st46 {
                                                        fill: url(#SVGID_33_);
                                                    }

                                                    .st47 {
                                                        fill: url(#SVGID_34_);
                                                    }

                                                    .st48 {
                                                        fill: url(#SVGID_35_);
                                                    }

                                                    .st49 {
                                                        fill: url(#SVGID_36_);
                                                    }

                                                    .st50 {
                                                        fill: url(#SVGID_37_);
                                                    }

                                                    .st51 {
                                                        fill: url(#SVGID_38_);
                                                    }

                                                    .st52 {
                                                        fill: url(#SVGID_39_);
                                                    }

                                                    .st53 {
                                                        fill: url(#SVGID_40_);
                                                    }

                                                    .st54 {
                                                        fill: url(#SVGID_41_);
                                                    }

                                                    .st55 {
                                                        fill: url(#SVGID_42_);
                                                    }

                                                    .st56 {
                                                        fill: url(#SVGID_43_);
                                                    }

                                                    .st57 {
                                                        fill: url(#SVGID_44_);
                                                    }

                                                    .st58 {
                                                        fill: url(#SVGID_45_);
                                                    }

                                                    .st59 {
                                                        fill: #040404;
                                                    }

                                                    .st60 {
                                                        fill: url(#SVGID_46_);
                                                    }

                                                    .st61 {
                                                        fill: url(#SVGID_47_);
                                                    }

                                                    .st62 {
                                                        fill: url(#SVGID_48_);
                                                    }

                                                    .st63 {
                                                        fill: url(#SVGID_49_);
                                                    }

                                                    .st64 {
                                                        fill: url(#SVGID_50_);
                                                    }

                                                    .st65 {
                                                        fill: url(#SVGID_51_);
                                                    }

                                                    .st66 {
                                                        fill: url(#SVGID_52_);
                                                    }

                                                    .st67 {
                                                        fill: url(#SVGID_53_);
                                                    }

                                                    .st68 {
                                                        fill: url(#SVGID_54_);
                                                    }

                                                    .st69 {
                                                        fill: url(#SVGID_55_);
                                                    }

                                                    .st70 {
                                                        fill: url(#SVGID_56_);
                                                    }

                                                    .st71 {
                                                        fill: url(#SVGID_57_);
                                                    }

                                                    .st72 {
                                                        fill: url(#SVGID_58_);
                                                    }

                                                    .st73 {
                                                        fill: url(#SVGID_59_);
                                                    }

                                                    .st74 {
                                                        fill: url(#SVGID_60_);
                                                    }

                                                    .st75 {
                                                        fill: url(#SVGID_61_);
                                                    }

                                                    .st76 {
                                                        fill: url(#SVGID_62_);
                                                    }

                                                    .st77 {
                                                        fill: none;
                                                        stroke: #000000;
                                                        stroke-width: 3;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st78 {
                                                        fill: none;
                                                        stroke: #ffffff;
                                                        stroke-miterlimit: 10;
                                                    }

                                                    .st79 {
                                                        fill: #4bc9ff;
                                                    }

                                                    .st80 {
                                                        fill: #5500dd;
                                                    }

                                                    .st81 {
                                                        fill: #ff3a00;
                                                    }

                                                    .st82 {
                                                        fill: #e6162d;
                                                    }

                                                    .st83 {
                                                        fill: #f1f1f1;
                                                    }

                                                    .st84 {
                                                        fill: #ff9933;
                                                    }

                                                    .st85 {
                                                        fill: #b92b27;
                                                    }

                                                    .st86 {
                                                        fill: #00aced;
                                                    }

                                                    .st87 {
                                                        fill: #bd2125;
                                                    }

                                                    .st88 {
                                                        fill: #1877f2;
                                                    }

                                                    .st89 {
                                                        fill: #6665d2;
                                                    }

                                                    .st90 {
                                                        fill: #ce3056;
                                                    }

                                                    .st91 {
                                                        fill: #5bb381;
                                                    }

                                                    .st92 {
                                                        fill: #61c3ec;
                                                    }

                                                    .st93 {
                                                        fill: #e4b34b;
                                                    }

                                                    .st94 {
                                                        fill: #181ef2;
                                                    }

                                                    .st95 {
                                                        fill: #ff0000;
                                                    }

                                                    .st96 {
                                                        fill: #fe466c;
                                                    }

                                                    .st97 {
                                                        fill: #fa4778;
                                                    }

                                                    .st98 {
                                                        fill: #ff7700;
                                                    }

                                                    .st99 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #1f6bf6;
                                                    }

                                                    .st100 {
                                                        fill: #520094;
                                                    }

                                                    .st101 {
                                                        fill: #4477e8;
                                                    }

                                                    .st102 {
                                                        fill: #3d1d1c;
                                                    }

                                                    .st103 {
                                                        fill: #ffe812;
                                                    }

                                                    .st104 {
                                                        fill: #344356;
                                                    }

                                                    .st105 {
                                                        fill: #00cc76;
                                                    }

                                                    .st106 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #345e90;
                                                    }

                                                    .st107 {
                                                        fill: #1f65d8;
                                                    }

                                                    .st108 {
                                                        fill: #eb3587;
                                                    }

                                                    .st109 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #603a88;
                                                    }

                                                    .st110 {
                                                        fill: #e3ce99;
                                                    }

                                                    .st111 {
                                                        fill: #783af9;
                                                    }

                                                    .st112 {
                                                        fill: #ff515e;
                                                    }

                                                    .st113 {
                                                        fill: #ff4906;
                                                    }

                                                    .st114 {
                                                        fill: #503227;
                                                    }

                                                    .st115 {
                                                        fill: #4c7bd9;
                                                    }

                                                    .st116 {
                                                        fill: #69c9d0;
                                                    }

                                                    .st117 {
                                                        fill: #1b92d1;
                                                    }

                                                    .st118 {
                                                        fill: #eb4f4a;
                                                    }

                                                    .st119 {
                                                        fill: #513728;
                                                    }

                                                    .st120 {
                                                        fill: #ff6600;
                                                    }

                                                    .st121 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #b61438;
                                                    }

                                                    .st122 {
                                                        fill: #fffc00;
                                                    }

                                                    .st123 {
                                                        fill: #141414;
                                                    }

                                                    .st124 {
                                                        fill: #94d137;
                                                    }

                                                    .st125 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #f1f1f1;
                                                    }

                                                    .st126 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #66e066;
                                                    }

                                                    .st127 {
                                                        fill: #2d8cff;
                                                    }

                                                    .st128 {
                                                        fill: #f1a300;
                                                    }

                                                    .st129 {
                                                        fill: #4ba2f2;
                                                    }

                                                    .st130 {
                                                        fill: #1a5099;
                                                    }

                                                    .st131 {
                                                        fill: #ee6060;
                                                    }

                                                    .st132 {
                                                        fill-rule: evenodd;
                                                        clip-rule: evenodd;
                                                        fill: #f48120;
                                                    }

                                                    .st133 {
                                                        fill: #222222;
                                                    }

                                                    .st134 {
                                                        fill: url(#SVGID_63_);
                                                    }

                                                    .st135 {
                                                        fill: #0077b5;
                                                    }

                                                    .st136 {
                                                        fill: #ffcc00;
                                                    }

                                                    .st137 {
                                                        fill: #eb3352;
                                                    }

                                                    .st138 {
                                                        fill: #f9d265;
                                                    }

                                                    .st139 {
                                                        fill: #f5b955;
                                                    }

                                                    .st140 {
                                                        fill: #dd2a7b;
                                                    }

                                                    .st141 {
                                                        fill: #66e066;
                                                    }

                                                    .st142 {
                                                        fill: #eb4e00;
                                                    }

                                                    .st143 {
                                                        fill: #ffc794;
                                                    }

                                                    .st144 {
                                                        fill: #b5332a;
                                                    }

                                                    .st145 {
                                                        fill: #4e85eb;
                                                    }

                                                    .st146 {
                                                        fill: #58a45c;
                                                    }

                                                    .st147 {
                                                        fill: #f2bc42;
                                                    }

                                                    .st148 {
                                                        fill: #d85040;
                                                    }

                                                    .st149 {
                                                        fill: #464eb8;
                                                    }

                                                    .st150 {
                                                        fill: #7b83eb;
                                                    }
                                                </style>

                                                <g id="Layer_1" />

                                                <g id="Layer_2">
                                                    <g>
                                                        <path class="st88"
                                                            d="M50,2.5c-58.892,1.725-64.898,84.363-7.46,95l0,0h0H50h7.46l0,0C114.911,86.853,108.879,4.219,50,2.5z" />

                                                        <path class="st83"
                                                            d="M57.46,64.104h11.125l2.117-13.814H57.46v-8.965c0-3.779,1.85-7.463,7.781-7.463h6.021    c0,0,0-11.761,0-11.761c-12.894-2.323-28.385-1.616-28.722,17.66V50.29H30.417v13.814H42.54c0,0,0,33.395,0,33.396H50h7.46l0,0h0    V64.104z" />
                                                    </g>
                                                </g>
                                            </svg>
                                        </a>
                                    </li> --}}
                                    <li>
                                        <a href="{{ url('login/google') }}?event_login=1" class="google-color-wrp">

                                            <svg width="800px" height="800px" viewBox="-3 0 262 262"
                                                xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMidYMid">
                                                <path
                                                    d="M255.878 133.451c0-10.734-.871-18.567-2.756-26.69H130.55v48.448h71.947c-1.45 12.04-9.283 30.172-26.69 42.356l-.244 1.622 38.755 30.023 2.685.268c24.659-22.774 38.875-56.282 38.875-96.027"
                                                    fill="#4285F4" />
                                                <path
                                                    d="M130.55 261.1c35.248 0 64.839-11.605 86.453-31.622l-41.196-31.913c-11.024 7.688-25.82 13.055-45.257 13.055-34.523 0-63.824-22.773-74.269-54.25l-1.531.13-40.298 31.187-.527 1.465C35.393 231.798 79.49 261.1 130.55 261.1"
                                                    fill="#34A853" />
                                                <path
                                                    d="M56.281 156.37c-2.756-8.123-4.351-16.827-4.351-25.82 0-8.994 1.595-17.697 4.206-25.82l-.073-1.73L15.26 71.312l-1.335.635C5.077 89.644 0 109.517 0 130.55s5.077 40.905 13.925 58.602l42.356-32.782"
                                                    fill="#FBBC05" />
                                                <path
                                                    d="M130.55 50.479c24.514 0 41.05 10.589 50.479 19.438l36.844-35.974C195.245 12.91 165.798 0 130.55 0 79.49 0 35.393 29.301 13.925 71.947l42.211 32.783c10.59-31.477 39.891-54.251 74.414-54.251"
                                                    fill="#EB4335" />
                                            </svg>
                                        </a>
                                    </li>
                                    {{-- <li>
                                        <a href="#" class="insta-color-wrp">

                                            <svg width="800px" height="800px" viewBox="0 0 32 32" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect x="2" y="2" width="28" height="28" rx="6"
                                                    fill="url(#paint0_radial_87_7153)" />
                                                <rect x="2" y="2" width="28" height="28" rx="6"
                                                    fill="url(#paint1_radial_87_7153)" />
                                                <rect x="2" y="2" width="28" height="28" rx="6"
                                                    fill="url(#paint2_radial_87_7153)" />
                                                <path
                                                    d="M23 10.5C23 11.3284 22.3284 12 21.5 12C20.6716 12 20 11.3284 20 10.5C20 9.67157 20.6716 9 21.5 9C22.3284 9 23 9.67157 23 10.5Z"
                                                    fill="white" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M16 21C18.7614 21 21 18.7614 21 16C21 13.2386 18.7614 11 16 11C13.2386 11 11 13.2386 11 16C11 18.7614 13.2386 21 16 21ZM16 19C17.6569 19 19 17.6569 19 16C19 14.3431 17.6569 13 16 13C14.3431 13 13 14.3431 13 16C13 17.6569 14.3431 19 16 19Z"
                                                    fill="white" />
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M6 15.6C6 12.2397 6 10.5595 6.65396 9.27606C7.2292 8.14708 8.14708 7.2292 9.27606 6.65396C10.5595 6 12.2397 6 15.6 6H16.4C19.7603 6 21.4405 6 22.7239 6.65396C23.8529 7.2292 24.7708 8.14708 25.346 9.27606C26 10.5595 26 12.2397 26 15.6V16.4C26 19.7603 26 21.4405 25.346 22.7239C24.7708 23.8529 23.8529 24.7708 22.7239 25.346C21.4405 26 19.7603 26 16.4 26H15.6C12.2397 26 10.5595 26 9.27606 25.346C8.14708 24.7708 7.2292 23.8529 6.65396 22.7239C6 21.4405 6 19.7603 6 16.4V15.6ZM15.6 8H16.4C18.1132 8 19.2777 8.00156 20.1779 8.0751C21.0548 8.14674 21.5032 8.27659 21.816 8.43597C22.5686 8.81947 23.1805 9.43139 23.564 10.184C23.7234 10.4968 23.8533 10.9452 23.9249 11.8221C23.9984 12.7223 24 13.8868 24 15.6V16.4C24 18.1132 23.9984 19.2777 23.9249 20.1779C23.8533 21.0548 23.7234 21.5032 23.564 21.816C23.1805 22.5686 22.5686 23.1805 21.816 23.564C21.5032 23.7234 21.0548 23.8533 20.1779 23.9249C19.2777 23.9984 18.1132 24 16.4 24H15.6C13.8868 24 12.7223 23.9984 11.8221 23.9249C10.9452 23.8533 10.4968 23.7234 10.184 23.564C9.43139 23.1805 8.81947 22.5686 8.43597 21.816C8.27659 21.5032 8.14674 21.0548 8.0751 20.1779C8.00156 19.2777 8 18.1132 8 16.4V15.6C8 13.8868 8.00156 12.7223 8.0751 11.8221C8.14674 10.9452 8.27659 10.4968 8.43597 10.184C8.81947 9.43139 9.43139 8.81947 10.184 8.43597C10.4968 8.27659 10.9452 8.14674 11.8221 8.0751C12.7223 8.00156 13.8868 8 15.6 8Z"
                                                    fill="white" />
                                                <defs>
                                                    <radialGradient id="paint0_radial_87_7153" cx="0"
                                                        cy="0" r="1" gradientUnits="userSpaceOnUse"
                                                        gradientTransform="translate(12 23) rotate(-55.3758) scale(25.5196)">
                                                        <stop stop-color="#B13589" />
                                                        <stop offset="0.79309" stop-color="#C62F94" />
                                                        <stop offset="1" stop-color="#8A3AC8" />
                                                    </radialGradient>
                                                    <radialGradient id="paint1_radial_87_7153" cx="0"
                                                        cy="0" r="1" gradientUnits="userSpaceOnUse"
                                                        gradientTransform="translate(11 31) rotate(-65.1363) scale(22.5942)">
                                                        <stop stop-color="#E0E8B7" />
                                                        <stop offset="0.444662" stop-color="#FB8A2E" />
                                                        <stop offset="0.71474" stop-color="#E2425C" />
                                                        <stop offset="1" stop-color="#E2425C" stop-opacity="0" />
                                                    </radialGradient>
                                                    <radialGradient id="paint2_radial_87_7153" cx="0"
                                                        cy="0" r="1" gradientUnits="userSpaceOnUse"
                                                        gradientTransform="translate(0.500002 3) rotate(-8.1301) scale(38.8909 8.31836)">
                                                        <stop offset="0.156701" stop-color="#406ADC" />
                                                        <stop offset="0.467799" stop-color="#6A45BE" />
                                                        <stop offset="1" stop-color="#6A45BE" stop-opacity="0" />
                                                    </radialGradient>
                                                </defs>
                                            </svg>
                                        </a>
                                    </li> --}}
                                </ul>
                                <div class="new-create-account-form-foot">

                                    <p>Already have an account? <a href="#" id="login_event">Sign in</a></p>
                                    <p>By signing up you agree to Yesvite's <span><a
                                                href="{{ route('term_and_condition') }}">Terms & Conditions </a>
                                            and <a href="{{ route('privacy_policy') }}"> Privacy Policy</a></span></p>
                                </div>
                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <input type="hidden" id="cohostFname" value="" />
    <input type="hidden" id="cohostLname" value="" />
</section>

@push('scripts')
    <script>
        $(document).ready(function() {
            $.validator.addMethod(
                "passwordCheck",
                function(value, element) {
                    return (
                        this.optional(element) ||
                        /^(?=.*[A-Za-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{6,}$/.test(value)
                    );
                },
                "At least 6 characters with letters, numbers, and a special character"
            );

            $("#registerEvent").validate({
                rules: {
                    firstname: {
                        required: true
                    },
                    lastname: {
                        required: true
                    },
                    email: {
                        required: true,
                        email: true,
                        remote: {
                            url: base_url + "check-email",
                            type: "POST",
                            headers: {
                                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                            },
                            data: {
                                email: function() {
                                    return $("#email_c").val();
                                },
                            },
                        },
                    },
                    zip_code: {
                        required: true
                    },
                    password: {
                        required: true,
                        passwordCheck: true, // Custom password validation
                    },
                    cpassword: {
                        required: true,
                        equalTo: "#password_c",
                    },
                    'g-recaptcha-response':{
                        required:true
                    }
                },
                messages: {
                    firstname: {
                        required: "Please enter your first name"
                    },
                    lastname: {
                        required: "Please enter your last name"
                    },
                    email: {
                        required: "Please enter your email",
                        email: "Enter a valid email",
                        remote: "Email already exists",
                    },
                    zip_code: {
                        required: "Please enter your zip code"
                    },
                    password: {
                        required: "Enter your password",
                        passwordCheck: "Must contain letters, numbers, and a special character",
                    },
                    cpassword: {
                        required: "Confirm your password",
                        equalTo: "Passwords do not match",
                    },
                },
                errorPlacement: function(error, element) {
                    error.insertAfter(element).css("color", "red");
                },
                submitHandler: function(form) {
                    registerUser(); // Call the AJAX function on submit
                },
            });

            $(".createEventUser").on("click", function(e) {
                e.preventDefault(); // Prevent default form submission

                if ($("#registerEvent").valid()) {
                    registerUser();
                }
            });

            function registerUser() {
                let formData = {
                    firstname: $("#firstname").val(),
                    lastname: $("#lastname").val(),
                    email: $("#email_c").val(),
                    zip_code: $("#zip_code").val(),
                    password: $("#password_c").val(),
                    cpassword: $("#cpassword").val(),
                    account_type: $("#account_type").val(),
                    is_login: false,
                };

                $.ajax({
                    url: base_url + "store_register",
                    type: "POST",
                    data: formData,
                    headers: {
                        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                    },
                    beforeSend: function() {
                        $(".createEventUser").prop("disabled", true).text("Registering...");
                    },
                    success: function(response) {
                        console.log(response);
                        if (response.success) {
                            toastr.success("Registration successful!");
                            $("#registerEvent").addClass("d-none");
                            $("#crateEventLogin").removeClass("d-none");
                        } else {
                            toastr.error(response.message);
                        }
                    },
                    error: function(xhr) {
                        $(".createEventUser").prop("disabled", false).text("Create Account");
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $("#firstname-error").text(errors.firstname?.[0] || "");
                            $("#lastname-error").text(errors.lastname?.[0] || "");
                            $("#email-error").text(errors.email?.[0] || "");
                            $("#zip_code-error").text(errors.zip_code?.[0] || "");
                            $("#password-error").text(errors.password?.[0] || "");
                            $("#cpassword-error").text(errors.cpassword?.[0] || "");
                        } else {
                            toastr.error("Registration failed! Please try again.");
                        }
                    },
                });
            }


        });
    </script>
@endpush
