{{-- {{dd($postList  )}} --}}
<main class="new-main-content">
    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <!-- =============mainleft-====================== -->

                <x-event_wall.wall_left_menu :page="$current_page" :eventDetails="$eventDetails" />
            </div>
            <div class="col-xl-6 col-lg-8">
                <div class="main-content-center">
                    <!-- ===event-breadcrumb-wrp-start=== -->
                    <div class="event-breadcrumb-wrp">
                        <nav style="
                    --bs-breadcrumb-divider: url(
                      &#34;data:image/svg + xml,
                      %3Csvgxmlns='http://www.w3.org/2000/svg'width='8'height='8'%3E%3Cpathd='M2.5 0L1 1.5 3.5 4 1 6.5 2.5 8l4-4-4-4z'fill='%236c757d'/%3E%3C/svg%3E&#34;
                    );
                  "
                            aria-label="breadcrumb">
                            <ol class="breadcrumb">
                                <li class="breadcrumb-item"><a href="{{ route('event.event_lists')}}">Events</a></li>
                                <li class="breadcrumb-item">
                                    <a
                                        href="{{ route('event.event_wall', encrypt($eventDetails['id'])) }}">{{ $eventDetails['event_name'] }}</a>
                                </li>
                                <li class="breadcrumb-item active" aria-current="page">
                                    Wall
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- ===event-breadcrumb-wrp-end=== -->
                    <x-event_wall.wall_title :eventDetails="$eventDetails" />
                    <!-- ===event-center-title-start=== -->

                    <!-- ===event-center-title-end=== -->

                    <!-- ===event-center-tabs-main-start=== -->
                    <div class="event-center-tabs-main">
                        {{-- {{dd($current_page)}} --}}
                        <!-- ====================navbar-============================= -->
                        <x-event_wall.wall_navbar :event="$event" :page="$current_page" :eventDetails="$eventDetails" />

                        <!-- ===tab-content-start=== -->
                        <div class="tab-content" id="nav-tabContent">
                            <!-- ===tab-1-start=== -->
                            <div class="tab-pane fade show active" id="nav-wall" role="tabpanel"
                                aria-labelledby="nav-wall-tab">
                                <div class="event-center-wall-main">
                                    <!-- ================story================= -->
                                    <x-event_wall.wall_story :users="$users" :event="$event" :storiesList="$storiesList"
                                        :wallData="$wallData" />
                                    <x-event_wall.wall_crate_poll_photo :users="$users" />
                                    @foreach ($postList as $post)
                                        <div class="event-posts-main-wrp common-div-wrp hidden_post"
                                            data-post-id="{{ $post['id'] }}">

                                            <div class="posts-card-wrp">
                                                <div class="posts-card-head">
                                                    <div class="posts-card-head-left">
                                                        <div class="posts-card-head-left-img">
                                                            @if ($post['profile'] != '')
                                                                <img src="{{ $post['profile'] }}" alt="">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $nameParts = explode(' ', $post['username']);
                                                                    $firstInitial = isset($nameParts[0][0])
                                                                        ? strtoupper($nameParts[0][0])
                                                                        : '';
                                                                    $secondInitial = isset($nameParts[1][0])
                                                                        ? strtoupper($nameParts[1][0])
                                                                        : '';
                                                                    $initials = $firstInitial . $secondInitial;

                                                                    // Generate a font color class based on the first initial
                                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif

                                                            <span class="active-dot"></span>
                                                        </div>
                                                        <div class="posts-card-head-left-content">
                                                            <h3>{{ $post['username'] }}</h3>
                                                            <p>{{ $post['location'] }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="posts-card-head-right">
                                                        <div class="dropdown post-card-dropdown upcoming-card-dropdown">
                                                            <button class="dropdown-toggle " type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                    class="fa-solid fa-ellipsis"></i></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item hide-post-btn postControlButton"
                                                                        id="hidePostButton "
                                                                        data-event-id="{{ $event }}"
                                                                        data-event-post-id="{{ $post['id'] }}"
                                                                        data-user-id="{{ $login_user_id }}"
                                                                        data-post-control="hide_post">
                                                                        <svg id="icon" class="hide-post-svg-icon"
                                                                            viewBox="0 0 20 20" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M9.99896 13.6084C8.00729 13.6084 6.39062 11.9917 6.39062 10.0001C6.39062 8.00839 8.00729 6.39172 9.99896 6.39172C11.9906 6.39172 13.6073 8.00839 13.6073 10.0001C13.6073 11.9917 11.9906 13.6084 9.99896 13.6084ZM9.99896 7.64172C8.69896 7.64172 7.64062 8.70006 7.64062 10.0001C7.64062 11.3001 8.69896 12.3584 9.99896 12.3584C11.299 12.3584 12.3573 11.3001 12.3573 10.0001C12.3573 8.70006 11.299 7.64172 9.99896 7.64172Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M9.99844 17.5166C6.8651 17.5166 3.90677 15.6833 1.87344 12.4999C0.990104 11.1249 0.990104 8.88328 1.87344 7.49994C3.9151 4.31661 6.87344 2.48328 9.99844 2.48328C13.1234 2.48328 16.0818 4.31661 18.1151 7.49994C18.9984 8.87494 18.9984 11.1166 18.1151 12.4999C16.0818 15.6833 13.1234 17.5166 9.99844 17.5166ZM9.99844 3.73328C7.30677 3.73328 4.73177 5.34994 2.93177 8.17494C2.30677 9.14994 2.30677 10.8499 2.93177 11.8249C4.73177 14.6499 7.30677 16.2666 9.99844 16.2666C12.6901 16.2666 15.2651 14.6499 17.0651 11.8249C17.6901 10.8499 17.6901 9.14994 17.0651 8.17494C15.2651 5.34994 12.6901 3.73328 9.99844 3.73328Z"
                                                                                fill="#94A3B8" />
                                                                        </svg>
                                                                        Hide Post
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item mute-post-btn postControlButton"
                                                                        id="mutePostButton"
                                                                        data-event-id="{{ $event }}"
                                                                        data-event-post-id="{{ $post['id'] }}"
                                                                        data-user-id="{{ $login_user_id }}"
                                                                        data-post-control="mute">
                                                                        <svg id="muteIcon" class="muteIcon"
                                                                            style="display: block;" viewBox="0 0 20 20"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M5.83464 14.7916H4.16797C2.1513 14.7916 1.04297 13.6833 1.04297 11.6666V8.33331C1.04297 6.31664 2.1513 5.20831 4.16797 5.20831H5.35964C5.5513 5.20831 5.74297 5.14997 5.90964 5.04997L8.34297 3.52497C9.55964 2.76664 10.743 2.62497 11.6763 3.14164C12.6096 3.65831 13.118 4.73331 13.118 6.17497V6.97497C13.118 7.31664 12.8346 7.59997 12.493 7.59997C12.1513 7.59997 11.868 7.31664 11.868 6.97497V6.17497C11.868 5.22497 11.5763 4.51664 11.068 4.24164C10.5596 3.95831 9.80964 4.08331 9.0013 4.59164L6.56797 6.10831C6.20964 6.34164 5.78464 6.45831 5.35964 6.45831H4.16797C2.8513 6.45831 2.29297 7.01664 2.29297 8.33331V11.6666C2.29297 12.9833 2.8513 13.5416 4.16797 13.5416H5.83464C6.1763 13.5416 6.45964 13.825 6.45964 14.1666C6.45964 14.5083 6.1763 14.7916 5.83464 14.7916Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M10.4577 17.1583C9.79934 17.1583 9.07434 16.925 8.34934 16.4666C8.05767 16.2833 7.96601 15.9 8.14934 15.6083C8.33267 15.3166 8.71601 15.225 9.00767 15.4083C9.81601 15.9083 10.566 16.0416 11.0743 15.7583C11.5827 15.475 11.8743 14.7666 11.8743 13.825V10.7916C11.8743 10.45 12.1577 10.1666 12.4993 10.1666C12.841 10.1666 13.1243 10.45 13.1243 10.7916V13.825C13.1243 15.2583 12.6077 16.3416 11.6827 16.8583C11.3077 17.0583 10.891 17.1583 10.4577 17.1583Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M15.0002 13.9584C14.8669 13.9584 14.7419 13.9167 14.6252 13.8334C14.3502 13.625 14.2919 13.2334 14.5002 12.9584C15.5502 11.5584 15.7752 9.70002 15.1002 8.09169C14.9669 7.77502 15.1169 7.40835 15.4336 7.27502C15.7502 7.14169 16.1169 7.29169 16.2502 7.60835C17.1002 9.62502 16.8086 11.9667 15.5002 13.7167C15.3752 13.875 15.1919 13.9584 15.0002 13.9584Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M16.5237 16.0417C16.3903 16.0417 16.2653 16 16.1487 15.9167C15.8737 15.7084 15.8153 15.3167 16.0237 15.0417C17.807 12.6667 18.1987 9.48338 17.0487 6.74171C16.9153 6.42504 17.0653 6.05838 17.382 5.92504C17.707 5.79171 18.0653 5.94171 18.1987 6.25838C19.5237 9.40838 19.0737 13.0584 17.0237 15.7917C16.907 15.9584 16.7153 16.0417 16.5237 16.0417Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M1.66589 18.9583C1.50755 18.9583 1.34922 18.9 1.22422 18.775C0.982552 18.5333 0.982552 18.1333 1.22422 17.8916L17.8909 1.22495C18.1326 0.983285 18.5326 0.983285 18.7742 1.22495C19.0159 1.46662 19.0159 1.86662 18.7742 2.10828L2.10755 18.775C1.98255 18.9 1.82422 18.9583 1.66589 18.9583Z"
                                                                                fill="#94A3B8" />
                                                                        </svg>
                                                                        <svg id="unmuteIcon" class="unmute-icon"
                                                                            style="display: none;" width="29"
                                                                            height="21" viewBox="0 0 29 21"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M7.20731 15.7494C4.47457 15.7494 2.9046 15.1641 2.00923 14.3078C1.12413 13.4613 0.75 12.2067 0.75 10.5015C0.75 8.84742 1.22523 7.57593 2.18617 6.70634C3.15977 5.82528 4.75144 5.24768 7.20732 5.24768C8.60469 5.24768 9.64267 4.93873 10.4595 4.43239C11.2651 3.93308 11.7983 3.27536 12.2278 2.69506C12.3136 2.57923 12.3937 2.46892 12.4701 2.36383C12.7993 1.9111 13.0581 1.55507 13.3877 1.27035C13.7441 0.962547 14.1797 0.749998 14.8902 0.749998C15.6144 0.749998 16.1755 0.971438 16.6345 1.36555C17.1081 1.77229 17.5128 2.39853 17.8375 3.26305C18.4922 5.00592 18.75 7.51847 18.75 10.5015C18.75 13.4845 18.4922 15.9963 17.8376 17.7383C17.5129 18.6024 17.1082 19.2283 16.6346 19.6348C16.1757 20.0287 15.6146 20.25 14.8902 20.25C14.1828 20.25 13.7911 20.0401 13.4836 19.7507C13.206 19.4894 12.9995 19.1758 12.7298 18.7663C12.6405 18.6308 12.5444 18.4848 12.4365 18.3267C12.0307 17.7324 11.5088 17.0641 10.6659 16.5592C9.81957 16.0521 8.71825 15.7494 7.20731 15.7494Z"
                                                                                stroke="#94A3B8" stroke-width="1.5" />
                                                                            <path
                                                                                d="M24.5649 3C24.5649 3 25.7321 4.37314 26.2792 5.5C27.1055 7.20198 27.5649 8.4146 27.5649 10.5C27.5649 12.5854 27.1055 13.798 26.2792 15.5C25.7321 16.6269 24.5649 18 24.5649 18"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M22.5 7C22.5 7 22.8891 7.6408 23.0714 8.16667C23.3469 8.96092 23.5 9.52681 23.5 10.5C23.5 11.4732 23.3469 12.0391 23.0714 12.8333C22.8891 13.3592 22.5 14 22.5 14"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        Mute
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item postControlButton"
                                                                        href="#"
                                                                        data-event-id="{{ $event }}"
                                                                        data-event-post-id="{{ $post['id'] }}"
                                                                        data-user-id="{{ $login_user_id }}"
                                                                        data-post-control="report">
                                                                        <svg viewBox="0 0 20 20" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M10.0013 18.9583C5.05964 18.9583 1.04297 14.9416 1.04297 9.99996C1.04297 5.05829 5.05964 1.04163 10.0013 1.04163C14.943 1.04163 18.9596 5.05829 18.9596 9.99996C18.9596 14.9416 14.943 18.9583 10.0013 18.9583ZM10.0013 2.29163C5.7513 2.29163 2.29297 5.74996 2.29297 9.99996C2.29297 14.25 5.7513 17.7083 10.0013 17.7083C14.2513 17.7083 17.7096 14.25 17.7096 9.99996C17.7096 5.74996 14.2513 2.29163 10.0013 2.29163Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M10 11.4583C9.65833 11.4583 9.375 11.175 9.375 10.8333V6.66663C9.375 6.32496 9.65833 6.04163 10 6.04163C10.3417 6.04163 10.625 6.32496 10.625 6.66663V10.8333C10.625 11.175 10.3417 11.4583 10 11.4583Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M10.0013 14.1667C9.89297 14.1667 9.78464 14.1417 9.68464 14.1C9.58464 14.0583 9.49297 14 9.40964 13.925C9.33464 13.8417 9.2763 13.7583 9.23464 13.65C9.19297 13.55 9.16797 13.4417 9.16797 13.3333C9.16797 13.225 9.19297 13.1167 9.23464 13.0167C9.2763 12.9167 9.33464 12.825 9.40964 12.7417C9.49297 12.6667 9.58464 12.6083 9.68464 12.5667C9.88464 12.4833 10.118 12.4833 10.318 12.5667C10.418 12.6083 10.5096 12.6667 10.593 12.7417C10.668 12.825 10.7263 12.9167 10.768 13.0167C10.8096 13.1167 10.8346 13.225 10.8346 13.3333C10.8346 13.4417 10.8096 13.55 10.768 13.65C10.7263 13.7583 10.668 13.8417 10.593 13.925C10.5096 14 10.418 14.0583 10.318 14.1C10.218 14.1417 10.1096 14.1667 10.0013 14.1667Z"
                                                                                fill="#94A3B8" />
                                                                        </svg>
                                                                        Report
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <h5>
                                                            @if ($post['rsvp_status'] == '1')
                                                                <span class="positive-ans">
                                                                    <i class="fa-solid fa-circle-check"></i>Yes</span>
                                                            @elseif($post['rsvp_status'] == '0')
                                                                <span class="positive-ans not-ans"><i
                                                                        class="fa-solid fa-circle-question"></i>No
                                                                    Answer</span>
                                                            @elseif($post['rsvp_status'] == '2')
                                                                <span class="positive-ans nagative-ans">
                                                                    <i class="fa-solid fa-circle-xmark"></i>Not Coming
                                                                </span>
                                                            @endif

                                                            {{ $post['posttime'] }}
                                                        </h5>
                                                    </div>
                                                </div>
                                                <div class="posts-card-inner-wrp">
                                                    <h3 class="posts-card-inner-questions">{{ $post['post_message'] }}
                                                    </h3>
                                                </div>
                                                {{-- {{  dd($post['post_image'])}} --}}

                                                <div class="posts-card-show-post-wrp">
                                                    <div class="swiper posts-card-post">
                                                        <div class="swiper-wrapper">
                                                            <!-- Slides -->
                                                            @if (!empty($post['post_image']))
                                                                @foreach ($post['post_image'] as $image)
                                                                    <div class="swiper-slide">
                                                                        <div class="posts-card-show-post-img">
                                                                            <img src="{{ $image['media_url'] }}"
                                                                                alt="" loading="lazy" />
                                                                        </div>
                                                                    </div>
                                                                    {{-- <div class="swiper-slide">
                                                                <div class="posts-card-show-post-img">
                                                                    <video width="320" height="240">
                                                                        <source
                                                                            src="{{ asset('assets/front/img/sample-video.mp4') }}"
                                                                            type="video/mp4">
                                                                    </video>
                                                                    <button class="video-play-icon"><img
                                                                            src="{{ asset('assets/front/img/video-play-icon.png') }}"
                                                                            alt=""></button>
                                                                </div>
                                                                </div> --}}
                                                                @endforeach
                                                            @endif

                                                        </div>
                                                        <div class="custom-pagination"></div>
                                                        <!-- Custom Pagination -->

                                                        <div class="custom-dots-container"></div>
                                                    </div>
                                                </div>


                                                <div class="posts-card-like-commnet-wrp">
                                                    <div class="posts-card-like-comment-left">
                                                        <ul type="button" data-bs-toggle="modal"
                                                            data-bs-target="#reaction-modal-{{ $post['id'] }}">

                                                            <!-- Smiley Emoji -->
                                                            @if ($post['self_reaction'] == '\u{1F604}')
                                                                <li>
                                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                        alt="Smiley Emoji">
                                                                </li>

                                                                <!-- Eye Heart Emoji -->
                                                            @elseif ($post['self_reaction'] == '\u{1F60D}')
                                                                <li>
                                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                        alt="Eye Heart Emoji">
                                                                </li>

                                                                <!-- Heart Emoji -->
                                                            @elseif ($post['self_reaction'] == '\u{2764}')
                                                                <li>
                                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                        alt="Heart Emoji">
                                                                </li>
                                                            @endif
                                                            <p id="likeCount_{{ $post['id'] }}">
                                                                {{ $post['total_likes'] }} Likes</p>
                                                        </ul>
                                                        @if($post['commenting_on_off'] == "1")
                                                        <h6 id="comment_{{ $post['id'] }}">{{ $post['total_comment'] }} Comments</h6>
                                                        @endif
                                                    </div>
                                                    <div class="posts-card-like-comment-right emoji_display_like">
                                                        <button class="posts-card-like-btn" id="likeButton"
                                                            data-event-id="{{ $event }}"
                                                            data-event-post-id="{{ $post['id'] }} "
                                                            data-user-id="{{ $login_user_id }}">
                                                            @if ($post['self_reaction'] == '\u{2764}')
                                                                <i class="fa-solid fa-heart" id="show_Emoji"></i>
                                                            @elseif($post['self_reaction'] == '\u{1F494}')
                                                                <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                            @elseif($post['self_reaction'] == '\u{1F44D}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                        loading="lazy" alt="Thumb Emoji"
                                                                        class="emoji" data-emoji="👍"
                                                                        data-unicode="\\u{1F44D}"></i>
                                                            @elseif($post['self_reaction'] == '\u{1F604}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                        loading="lazy" alt="Smiley Emoji"
                                                                        class="emoji" data-emoji="😊"
                                                                        data-unicode="\\u{1F604}"></i>
                                                            @elseif($post['self_reaction'] == '\u{1F60D}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                        loading="lazy" alt="Eye Heart Emoji"
                                                                        class="emoji" data-emoji="😍"
                                                                        data-unicode="\\u{1F60D}"></i>
                                                            @elseif($post['self_reaction'] == '\u{1F44F}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                        loading="lazy" alt="Clap Emoji"
                                                                        class="emoji" data-emoji="👏"
                                                                        data-unicode="\\u{1F44F}"></i>
                                                            @else
                                                                <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                            @endif
                                                        </button>

                                                   @if($post['commenting_on_off'] == "1")
                                                        <button
                                                            class="posts-card-comm show-comments-btn show-btn-comment comment_btn"
                                                            event_p_id="{{ $post['id'] }}">
                                                            <svg viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                                                    stroke="#94A3B8" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M7 8H17" stroke="#94A3B8" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M7 13H13" stroke="#94A3B8" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                        @endif
                                                        <div class="photos-likes-options-wrp emoji-picker"
                                                            id="emojiDropdown" style="display: none;">
                                                            <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                loading="lazy" alt="Heart Emoji" class="emoji"
                                                                data-emoji="❤️" data-unicode="\\u{2764}">
                                                            <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                loading="lazy" alt="Thumb Emoji" class="emoji"
                                                                data-emoji="👍" data-unicode="\\u{1F44D}">
                                                            <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                loading="lazy" alt="Smiley Emoji" class="emoji"
                                                                data-emoji="😊" data-unicode="\\u{1F604}">
                                                            <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                loading="lazy" alt="Eye Heart Emoji" class="emoji"
                                                                data-emoji="😍" data-unicode="\\u{1F60D}">
                                                            <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                loading="lazy" alt="Clap Emoji" class="emoji"
                                                                data-emoji="👏" data-unicode="\\u{1F44F}">
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="posts-card-main-comment">
                                                    @if($post['commenting_on_off'] == "1")
                                                    <input type="text" class="form-control post_comment" id="post_comment" placeholder="Add Comment">
                                                    <span class="comment-send-icon send_comment"
                                                        data-event-id="{{ $event }}"
                                                        data-event-post-id="{{ $post['id'] }}">
                                                        <svg viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M4.5332 10H9.0332" stroke="#94A3B8"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                @else
                                                    <input type="text" class="form-control post_comment" id="post_comment" placeholder="Add Comment" style="display:none;">
                                                    <span class="comment-send-icon send_comment"  style="display:none;"
                                                    data-event-id="{{ $event }}"
                                                    data-event-post-id="{{ $post['id'] }}">
                                                    <svg viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                                            stroke="#94A3B8" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M4.5332 10H9.0332" stroke="#94A3B8"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                                @endif
                                                        <input type="hidden" id="comment_on_of" value="{{$post['commenting_on_off']}}">

                                                </div>
                                                {{-- {{dd($post['post_comment'] )}} --}}
                                                <div
                                                    class="posts-card-show-all-comments-wrp d-none show_{{ $post['id'] }}">

                                                    <div class="posts-card-show-all-comments-inner">
                                                        <ul class="top-level-comments">


                                                            @foreach ($post['post_comment'] as $comment)
                                                                <li class="commented-user-wrp"
                                                                    data-comment-id="{{ $comment['id'] }}">
                                                                    <input type="hidden" id="parent_comment_id"
                                                                        value="{{ $comment['id'] }}">
                                                                    <input type="hidden" id="reply_comment_id"
                                                                        value="">
                                                                    <div class="commented-user-head">
                                                                        <div class="commented-user-profile">
                                                                            <div class="commented-user-profile-img">
                                                                                @if ($comment['profile'] != '')
                                                                                    <img src="{{ $comment['profile'] }}"
                                                                                        alt="" loading="lazy">
                                                                                @else
                                                                                    @php
                                                                                        $nameParts = explode(
                                                                                            ' ',
                                                                                            $comment['username'],
                                                                                        );
                                                                                        $firstInitial = isset(
                                                                                            $nameParts[0][0],
                                                                                        )
                                                                                            ? strtoupper(
                                                                                                $nameParts[0][0],
                                                                                            )
                                                                                            : '';
                                                                                        $secondInitial = isset(
                                                                                            $nameParts[1][0],
                                                                                        )
                                                                                            ? strtoupper(
                                                                                                $nameParts[1][0],
                                                                                            )
                                                                                            : '';
                                                                                        $initials =
                                                                                            $firstInitial .
                                                                                            $secondInitial;

                                                                                        // Generate a font color class based on the first initial
                                                                                        $fontColor =
                                                                                            'fontcolor' . $firstInitial;
                                                                                    @endphp

                                                                                    <h5 class="{{ $fontColor }}">
                                                                                        {{ $initials }}</h5>
                                                                                @endif

                                                                            </div>
                                                                            <div
                                                                                class="commented-user-profile-content">
                                                                                <h3>{{ $comment['username'] }}</h3>
                                                                                <p>{{ $comment['location'] ?? '' }}</p>
                                                                            </div>
                                                                        </div>
                                                                        <div class="posts-card-like-comment-right">
                                                                            <p>{{ $comment['posttime'] }}</p>
                                                                            <button class="posts-card-like-btn" id="likeButton"
                                                                            data-event-id="{{ $event }}"
                                                                            data-event-post-id="{{ $post['id'] }} "
                                                                            data-user-id="{{ $login_user_id }}"> @if ($post['self_reaction'] == '\u{2764}')
                                                                            <i class="fa-solid fa-heart" id="show_Emoji"></i>
                                                                        @elseif($post['self_reaction'] == '\u{1F494}')
                                                                            <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                        @elseif($post['self_reaction'] == '\u{1F44D}')
                                                                            <i id="show_Emoji"> <img
                                                                                    src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                                    loading="lazy" alt="Thumb Emoji"
                                                                                    class="emoji" data-emoji="👍"
                                                                                    data-unicode="\\u{1F44D}"></i>
                                                                        @elseif($post['self_reaction'] == '\u{1F604}')
                                                                            <i id="show_Emoji"> <img
                                                                                    src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                                    loading="lazy" alt="Smiley Emoji"
                                                                                    class="emoji" data-emoji="😊"
                                                                                    data-unicode="\\u{1F604}"></i>
                                                                        @elseif($post['self_reaction'] == '\u{1F60D}')
                                                                            <i id="show_Emoji"> <img
                                                                                    src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                                    loading="lazy" alt="Eye Heart Emoji"
                                                                                    class="emoji" data-emoji="😍"
                                                                                    data-unicode="\\u{1F60D}"></i>
                                                                        @elseif($post['self_reaction'] == '\u{1F44F}')
                                                                            <i id="show_Emoji"> <img
                                                                                    src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                                    loading="lazy" alt="Clap Emoji"
                                                                                    class="emoji" data-emoji="👏"
                                                                                    data-unicode="\\u{1F44F}"></i>
                                                                        @else
                                                                            <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                        @endif</button>
                                                                        </div>
                                                                    </div>
                                                                    <div class="commented-user-content">
                                                                        <p>{{ $comment['comment'] }}</p>
                                                                    </div>
                                                                    <div class="commented-user-reply-wrp">
                                                                        <div
                                                                            class="position-relative d-flex align-items-center gap-2">
                                                                            <button class="posts-card-like-btn" id="likeButton"
                                                                            data-event-id="{{ $event }}"
                                                                            data-event-post-id="{{ $post['id'] }} "
                                                                            data-user-id="{{ $login_user_id }}">
                                                                            <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                      </button>
                                                                            <p>{{ $comment['comment_total_likes'] }}
                                                                            </p>
                                                                        </div>
                                                                        <button
                                                                            class="commented-user-reply-btn">Reply</button>
                                                                    </div>
                                                                    @if ($comment['total_replies'] > 0)
                                                                        <ul>
                                                                            @foreach ($comment['comment_replies'] as $reply)
                                                                                <li class="reply-on-comment"
                                                                                    data-comment-id="{{ $reply['id'] }}">
                                                                                    <div class="commented-user-head">
                                                                                        <div
                                                                                            class="commented-user-profile">
                                                                                            <div
                                                                                                class="commented-user-profile-img">
                                                                                                @if ($reply['profile'] != '')
                                                                                                    <img src="{{ $reply['profile'] }}"
                                                                                                        alt=""
                                                                                                        loading="lazy">
                                                                                                @else
                                                                                                    @php
                                                                                                        $nameParts = explode(
                                                                                                            ' ',
                                                                                                            $reply[
                                                                                                                'username'
                                                                                                            ],
                                                                                                        );
                                                                                                        $firstInitial = isset(
                                                                                                            $nameParts[0][0],
                                                                                                        )
                                                                                                            ? strtoupper(
                                                                                                                $nameParts[0][0],
                                                                                                            )
                                                                                                            : '';
                                                                                                        $secondInitial = isset(
                                                                                                            $nameParts[1][0],
                                                                                                        )
                                                                                                            ? strtoupper(
                                                                                                                $nameParts[1][0],
                                                                                                            )
                                                                                                            : '';
                                                                                                        $initials =
                                                                                                            $firstInitial .
                                                                                                            $secondInitial;

                                                                                                        // Generate a font color class based on the first initial
                                                                                                        $fontColor =
                                                                                                            'fontcolor' .
                                                                                                            $firstInitial;
                                                                                                    @endphp

                                                                                                    <h5
                                                                                                        class="{{ $fontColor }}">
                                                                                                        {{ $initials }}
                                                                                                    </h5>
                                                                                                @endif
                                                                                            </div>
                                                                                            <div
                                                                                                class="commented-user-profile-content">
                                                                                                <h3>{{ $reply['username'] }}
                                                                                                </h3>
                                                                                                <p>{{ $reply['location'] ?? '' }}
                                                                                                </p>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div
                                                                                            class="posts-card-like-comment-right">
                                                                                            <p>{{ $reply['posttime'] }}
                                                                                            </p>
                                                                                            <button
                                                                                                class="posts-card-like-btn" id="likeButton"
                                                                                                data-event-id="{{ $event }}"
                                                                                                data-event-post-id="{{ $post['id'] }} "
                                                                                                data-user-id="{{ $login_user_id }}"><i
                                                                                                    class="fa-regular fa-heart"></i></button>
                                                                                        </div>
                                                                                    </div>
                                                                                    <div
                                                                                        class="commented-user-content">
                                                                                        <p>{{ $reply['comment'] }}</p>
                                                                                    </div>
                                                                                    <div
                                                                                        class="commented-user-reply-wrp">
                                                                                        <div
                                                                                            class="position-relative d-flex align-items-center gap-2">
                                                                                            <button
                                                                                                class="posts-card-like-btn" id="likeButton"
                                                                                                data-event-id="{{ $event }}"
                                                                                                data-event-post-id="{{ $post['id'] }} "
                                                                                                data-user-id="{{ $login_user_id }}"> @if ($post['self_reaction'] == '\u{2764}')
                                                                                                <i class="fa-solid fa-heart" id="show_Emoji"></i>
                                                                                            @elseif($post['self_reaction'] == '\u{1F494}')
                                                                                                <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                                            @elseif($post['self_reaction'] == '\u{1F44D}')
                                                                                                <i id="show_Emoji"> <img
                                                                                                        src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                                                        loading="lazy" alt="Thumb Emoji"
                                                                                                        class="emoji" data-emoji="👍"
                                                                                                        data-unicode="\\u{1F44D}"></i>
                                                                                            @elseif($post['self_reaction'] == '\u{1F604}')
                                                                                                <i id="show_Emoji"> <img
                                                                                                        src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                                                        loading="lazy" alt="Smiley Emoji"
                                                                                                        class="emoji" data-emoji="😊"
                                                                                                        data-unicode="\\u{1F604}"></i>
                                                                                            @elseif($post['self_reaction'] == '\u{1F60D}')
                                                                                                <i id="show_Emoji"> <img
                                                                                                        src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                                                        loading="lazy" alt="Eye Heart Emoji"
                                                                                                        class="emoji" data-emoji="😍"
                                                                                                        data-unicode="\\u{1F60D}"></i>
                                                                                            @elseif($post['self_reaction'] == '\u{1F44F}')
                                                                                                <i id="show_Emoji"> <img
                                                                                                        src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                                                        loading="lazy" alt="Clap Emoji"
                                                                                                        class="emoji" data-emoji="👏"
                                                                                                        data-unicode="\\u{1F44F}"></i>
                                                                                            @else
                                                                                                <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                                            @endif</button>
                                                                                            <p>{{ $reply['comment_total_likes'] }}
                                                                                            </p>
                                                                                        </div>
                                                                                        <button
                                                                                            class="commented-user-reply-btn">Reply</button>
                                                                                    </div>
                                                                                </li>
                                                                            @endforeach

                                                                            <!-- Button to show more replies if any -->
                                                                            <button class="show-comment-reply-btn">Show
                                                                                {{ $comment['total_replies'] }}
                                                                                reply</button>
                                                                        </ul>
                                                                    @endif


                                                                </li>
                                                            @endforeach
                                                        </ul>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    @endforeach
                                    {{-- <div class="event-posts-main-wrp common-div-wrp">
                                        <div class="posts-card-wrp">
                                            <div class="posts-card-head">
                                                <div class="posts-card-head-left">
                                                    <div class="posts-card-head-left-img">
                                                        <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                            alt="">
                                                        <span class="active-dot"></span>
                                                    </div>
                                                    <div class="posts-card-head-left-content">
                                                        <h3>Chance Curtis</h3>
                                                        <p>New York, NY</p>
                                                    </div>
                                                </div>
                                                <div class="posts-card-head-right">
                                                    <div class="dropdown post-card-dropdown upcoming-card-dropdown">
                                                        <button class="dropdown-toggle" type="button"
                                                            data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                class="fa-solid fa-ellipsis"></i></button>
                                                        <ul class="dropdown-menu">
                                                            <li>
                                                                <a class="dropdown-item" href="#">
                                                                    <svg viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M9.99896 13.6084C8.00729 13.6084 6.39062 11.9917 6.39062 10.0001C6.39062 8.00839 8.00729 6.39172 9.99896 6.39172C11.9906 6.39172 13.6073 8.00839 13.6073 10.0001C13.6073 11.9917 11.9906 13.6084 9.99896 13.6084ZM9.99896 7.64172C8.69896 7.64172 7.64062 8.70006 7.64062 10.0001C7.64062 11.3001 8.69896 12.3584 9.99896 12.3584C11.299 12.3584 12.3573 11.3001 12.3573 10.0001C12.3573 8.70006 11.299 7.64172 9.99896 7.64172Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M9.99844 17.5166C6.8651 17.5166 3.90677 15.6833 1.87344 12.4999C0.990104 11.1249 0.990104 8.88328 1.87344 7.49994C3.9151 4.31661 6.87344 2.48328 9.99844 2.48328C13.1234 2.48328 16.0818 4.31661 18.1151 7.49994C18.9984 8.87494 18.9984 11.1166 18.1151 12.4999C16.0818 15.6833 13.1234 17.5166 9.99844 17.5166ZM9.99844 3.73328C7.30677 3.73328 4.73177 5.34994 2.93177 8.17494C2.30677 9.14994 2.30677 10.8499 2.93177 11.8249C4.73177 14.6499 7.30677 16.2666 9.99844 16.2666C12.6901 16.2666 15.2651 14.6499 17.0651 11.8249C17.6901 10.8499 17.6901 9.14994 17.0651 8.17494C15.2651 5.34994 12.6901 3.73328 9.99844 3.73328Z"
                                                                            fill="#94A3B8" />
                                                                    </svg>
                                                                    Hide Post
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#">
                                                                    <svg viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M5.83464 14.7916H4.16797C2.1513 14.7916 1.04297 13.6833 1.04297 11.6666V8.33331C1.04297 6.31664 2.1513 5.20831 4.16797 5.20831H5.35964C5.5513 5.20831 5.74297 5.14997 5.90964 5.04997L8.34297 3.52497C9.55964 2.76664 10.743 2.62497 11.6763 3.14164C12.6096 3.65831 13.118 4.73331 13.118 6.17497V6.97497C13.118 7.31664 12.8346 7.59997 12.493 7.59997C12.1513 7.59997 11.868 7.31664 11.868 6.97497V6.17497C11.868 5.22497 11.5763 4.51664 11.068 4.24164C10.5596 3.95831 9.80964 4.08331 9.0013 4.59164L6.56797 6.10831C6.20964 6.34164 5.78464 6.45831 5.35964 6.45831H4.16797C2.8513 6.45831 2.29297 7.01664 2.29297 8.33331V11.6666C2.29297 12.9833 2.8513 13.5416 4.16797 13.5416H5.83464C6.1763 13.5416 6.45964 13.825 6.45964 14.1666C6.45964 14.5083 6.1763 14.7916 5.83464 14.7916Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M10.4577 17.1583C9.79934 17.1583 9.07434 16.925 8.34934 16.4666C8.05767 16.2833 7.96601 15.9 8.14934 15.6083C8.33267 15.3166 8.71601 15.225 9.00767 15.4083C9.81601 15.9083 10.566 16.0416 11.0743 15.7583C11.5827 15.475 11.8743 14.7666 11.8743 13.825V10.7916C11.8743 10.45 12.1577 10.1666 12.4993 10.1666C12.841 10.1666 13.1243 10.45 13.1243 10.7916V13.825C13.1243 15.2583 12.6077 16.3416 11.6827 16.8583C11.3077 17.0583 10.891 17.1583 10.4577 17.1583Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M15.0002 13.9584C14.8669 13.9584 14.7419 13.9167 14.6252 13.8334C14.3502 13.625 14.2919 13.2334 14.5002 12.9584C15.5502 11.5584 15.7752 9.70002 15.1002 8.09169C14.9669 7.77502 15.1169 7.40835 15.4336 7.27502C15.7502 7.14169 16.1169 7.29169 16.2502 7.60835C17.1002 9.62502 16.8086 11.9667 15.5002 13.7167C15.3752 13.875 15.1919 13.9584 15.0002 13.9584Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M16.5237 16.0417C16.3903 16.0417 16.2653 16 16.1487 15.9167C15.8737 15.7084 15.8153 15.3167 16.0237 15.0417C17.807 12.6667 18.1987 9.48338 17.0487 6.74171C16.9153 6.42504 17.0653 6.05838 17.382 5.92504C17.707 5.79171 18.0653 5.94171 18.1987 6.25838C19.5237 9.40838 19.0737 13.0584 17.0237 15.7917C16.907 15.9584 16.7153 16.0417 16.5237 16.0417Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M1.66589 18.9583C1.50755 18.9583 1.34922 18.9 1.22422 18.775C0.982552 18.5333 0.982552 18.1333 1.22422 17.8916L17.8909 1.22495C18.1326 0.983285 18.5326 0.983285 18.7742 1.22495C19.0159 1.46662 19.0159 1.86662 18.7742 2.10828L2.10755 18.775C1.98255 18.9 1.82422 18.9583 1.66589 18.9583Z"
                                                                            fill="#94A3B8" />
                                                                    </svg>
                                                                    Mute
                                                                </a>
                                                            </li>
                                                            <li>
                                                                <a class="dropdown-item" href="#">
                                                                    <svg viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M10.0013 18.9583C5.05964 18.9583 1.04297 14.9416 1.04297 9.99996C1.04297 5.05829 5.05964 1.04163 10.0013 1.04163C14.943 1.04163 18.9596 5.05829 18.9596 9.99996C18.9596 14.9416 14.943 18.9583 10.0013 18.9583ZM10.0013 2.29163C5.7513 2.29163 2.29297 5.74996 2.29297 9.99996C2.29297 14.25 5.7513 17.7083 10.0013 17.7083C14.2513 17.7083 17.7096 14.25 17.7096 9.99996C17.7096 5.74996 14.2513 2.29163 10.0013 2.29163Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M10 11.4583C9.65833 11.4583 9.375 11.175 9.375 10.8333V6.66663C9.375 6.32496 9.65833 6.04163 10 6.04163C10.3417 6.04163 10.625 6.32496 10.625 6.66663V10.8333C10.625 11.175 10.3417 11.4583 10 11.4583Z"
                                                                            fill="#94A3B8" />
                                                                        <path
                                                                            d="M10.0013 14.1667C9.89297 14.1667 9.78464 14.1417 9.68464 14.1C9.58464 14.0583 9.49297 14 9.40964 13.925C9.33464 13.8417 9.2763 13.7583 9.23464 13.65C9.19297 13.55 9.16797 13.4417 9.16797 13.3333C9.16797 13.225 9.19297 13.1167 9.23464 13.0167C9.2763 12.9167 9.33464 12.825 9.40964 12.7417C9.49297 12.6667 9.58464 12.6083 9.68464 12.5667C9.88464 12.4833 10.118 12.4833 10.318 12.5667C10.418 12.6083 10.5096 12.6667 10.593 12.7417C10.668 12.825 10.7263 12.9167 10.768 13.0167C10.8096 13.1167 10.8346 13.225 10.8346 13.3333C10.8346 13.4417 10.8096 13.55 10.768 13.65C10.7263 13.7583 10.668 13.8417 10.593 13.925C10.5096 14 10.418 14.0583 10.318 14.1C10.218 14.1417 10.1096 14.1667 10.0013 14.1667Z"
                                                                            fill="#94A3B8" />
                                                                    </svg>
                                                                    Report
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <h5><span class="positive-ans not-ans"><i
                                                                class="fa-solid fa-circle-question"></i>No
                                                            Answer</span> 10m</h5>
                                                </div>
                                            </div>
                                            <div class="posts-card-inner-wrp">
                                                <h3 class="posts-card-inner-questions">Join for some drinks upstairs?
                                                    Anyone?</h3>
                                            </div>
                                            <div class="posts-card-like-commnet-wrp">
                                                <div class="posts-card-like-comment-left">
                                                    <ul>
                                                        <li><img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                alt=""></li>
                                                        <li><img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                alt=""></li>
                                                        <li><img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                alt=""></li>
                                                        <p>5k Likes</p>
                                                    </ul>
                                                    <h6>354 Comments</h6>
                                                </div>
                                                <div class="posts-card-like-comment-right">
                                                    <button class="posts-card-like-btn"><i
                                                            class="fa-regular fa-heart"></i></button>
                                                    <button class="posts-card-comm show-comments-btn">
                                                        <svg viewBox="0 0 24 24" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-miterlimit="10" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                            <path d="M7 8H17" stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M7 13H13" stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>

                                            <div class="posts-card-main-comment">
                                                <input type="text" class="form-control" id="text"
                                                    placeholder="Add Comment">
                                                <span class="comment-send-icon">
                                                    <svg viewBox="0 0 20 20" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                                            stroke="#94A3B8" stroke-width="1.5"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path d="M4.5332 10H9.0332" stroke="#94A3B8"
                                                            stroke-width="1.5" stroke-linecap="round"
                                                            stroke-linejoin="round" />
                                                    </svg>
                                                </span>
                                            </div>
                                        </div>
                                    </div> --}}
                                    {{-- {{dd($pollsData)}} --}}
                                    @foreach ($pollsData as $poll)
                                        <div class="event-posts-main-wrp common-div-wrp hidden_post"
                                            data-post-id="{{ $poll['event_post_id'] }}">
                                            <div class="posts-card-wrp">
                                                <div class="posts-card-head">
                                                    <div class="posts-card-head-left">
                                                        <div class="posts-card-head-left-img">
                                                            @if ( $users->profile != '')
                                                                <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                                                                    alt="" loading="lazy">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $nameParts = explode(' ', $users->firstname);
                                                                    $lastname = explode(' ', $users->lastname);
                                                                    $firstInitial = isset($nameParts[0][0])
                                                                        ? strtoupper($nameParts[0][0])
                                                                        : '';
                                                                    $secondInitial = isset($lastname[0][0])
                                                                        ? strtoupper($lastname[0][0])
                                                                        : '';
                                                                    $initials = $firstInitial . $secondInitial;

                                                                    // Generate a font color class based on the first initial
                                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif

                                                            <span class="active-dot"></span>
                                                        </div>
                                                        <div class="posts-card-head-left-content">
                                                            <h3>{{ $users->firstname }} {{ $users->lastname }}</h3>
                                                            <p>{{ $users->city }},{{ $users->state }}</p>
                                                        </div>
                                                    </div>
                                                    <div class="posts-card-head-right">
                                                        <div
                                                            class="dropdown post-card-dropdown upcoming-card-dropdown">
                                                            <button class="dropdown-toggle" type="button"
                                                                data-bs-toggle="dropdown" aria-expanded="false"><i
                                                                    class="fa-solid fa-ellipsis"></i></button>
                                                            <ul class="dropdown-menu">
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item hide-post-btn postControlButton"
                                                                        id="hidePostButton "
                                                                        data-event-id="{{ $event }}"
                                                                        data-event-post-id="{{ $poll['event_post_id'] }}"
                                                                        data-user-id="{{ $login_user_id }}"
                                                                        data-post-control="hide_post">
                                                                        <svg id="icon" class="hide-post-svg-icon"
                                                                            viewBox="0 0 20 20" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M9.99896 13.6084C8.00729 13.6084 6.39062 11.9917 6.39062 10.0001C6.39062 8.00839 8.00729 6.39172 9.99896 6.39172C11.9906 6.39172 13.6073 8.00839 13.6073 10.0001C13.6073 11.9917 11.9906 13.6084 9.99896 13.6084ZM9.99896 7.64172C8.69896 7.64172 7.64062 8.70006 7.64062 10.0001C7.64062 11.3001 8.69896 12.3584 9.99896 12.3584C11.299 12.3584 12.3573 11.3001 12.3573 10.0001C12.3573 8.70006 11.299 7.64172 9.99896 7.64172Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M9.99844 17.5166C6.8651 17.5166 3.90677 15.6833 1.87344 12.4999C0.990104 11.1249 0.990104 8.88328 1.87344 7.49994C3.9151 4.31661 6.87344 2.48328 9.99844 2.48328C13.1234 2.48328 16.0818 4.31661 18.1151 7.49994C18.9984 8.87494 18.9984 11.1166 18.1151 12.4999C16.0818 15.6833 13.1234 17.5166 9.99844 17.5166ZM9.99844 3.73328C7.30677 3.73328 4.73177 5.34994 2.93177 8.17494C2.30677 9.14994 2.30677 10.8499 2.93177 11.8249C4.73177 14.6499 7.30677 16.2666 9.99844 16.2666C12.6901 16.2666 15.2651 14.6499 17.0651 11.8249C17.6901 10.8499 17.6901 9.14994 17.0651 8.17494C15.2651 5.34994 12.6901 3.73328 9.99844 3.73328Z"
                                                                                fill="#94A3B8" />
                                                                        </svg>
                                                                        Hide Post
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button
                                                                        class="dropdown-item mute-post-btn postControlButton"
                                                                        id="mutePostButton"
                                                                        data-event-id="{{ $event }}"
                                                                        data-event-post-id="{{ $poll['event_post_id'] }}"
                                                                        data-user-id="{{ $login_user_id }}"
                                                                        data-post-control="mute">
                                                                        <svg id="muteIcon" class="muteIcon"
                                                                            style="display: block;"
                                                                            viewBox="0 0 20 20" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M5.83464 14.7916H4.16797C2.1513 14.7916 1.04297 13.6833 1.04297 11.6666V8.33331C1.04297 6.31664 2.1513 5.20831 4.16797 5.20831H5.35964C5.5513 5.20831 5.74297 5.14997 5.90964 5.04997L8.34297 3.52497C9.55964 2.76664 10.743 2.62497 11.6763 3.14164C12.6096 3.65831 13.118 4.73331 13.118 6.17497V6.97497C13.118 7.31664 12.8346 7.59997 12.493 7.59997C12.1513 7.59997 11.868 7.31664 11.868 6.97497V6.17497C11.868 5.22497 11.5763 4.51664 11.068 4.24164C10.5596 3.95831 9.80964 4.08331 9.0013 4.59164L6.56797 6.10831C6.20964 6.34164 5.78464 6.45831 5.35964 6.45831H4.16797C2.8513 6.45831 2.29297 7.01664 2.29297 8.33331V11.6666C2.29297 12.9833 2.8513 13.5416 4.16797 13.5416H5.83464C6.1763 13.5416 6.45964 13.825 6.45964 14.1666C6.45964 14.5083 6.1763 14.7916 5.83464 14.7916Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M10.4577 17.1583C9.79934 17.1583 9.07434 16.925 8.34934 16.4666C8.05767 16.2833 7.96601 15.9 8.14934 15.6083C8.33267 15.3166 8.71601 15.225 9.00767 15.4083C9.81601 15.9083 10.566 16.0416 11.0743 15.7583C11.5827 15.475 11.8743 14.7666 11.8743 13.825V10.7916C11.8743 10.45 12.1577 10.1666 12.4993 10.1666C12.841 10.1666 13.1243 10.45 13.1243 10.7916V13.825C13.1243 15.2583 12.6077 16.3416 11.6827 16.8583C11.3077 17.0583 10.891 17.1583 10.4577 17.1583Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M15.0002 13.9584C14.8669 13.9584 14.7419 13.9167 14.6252 13.8334C14.3502 13.625 14.2919 13.2334 14.5002 12.9584C15.5502 11.5584 15.7752 9.70002 15.1002 8.09169C14.9669 7.77502 15.1169 7.40835 15.4336 7.27502C15.7502 7.14169 16.1169 7.29169 16.2502 7.60835C17.1002 9.62502 16.8086 11.9667 15.5002 13.7167C15.3752 13.875 15.1919 13.9584 15.0002 13.9584Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M16.5237 16.0417C16.3903 16.0417 16.2653 16 16.1487 15.9167C15.8737 15.7084 15.8153 15.3167 16.0237 15.0417C17.807 12.6667 18.1987 9.48338 17.0487 6.74171C16.9153 6.42504 17.0653 6.05838 17.382 5.92504C17.707 5.79171 18.0653 5.94171 18.1987 6.25838C19.5237 9.40838 19.0737 13.0584 17.0237 15.7917C16.907 15.9584 16.7153 16.0417 16.5237 16.0417Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M1.66589 18.9583C1.50755 18.9583 1.34922 18.9 1.22422 18.775C0.982552 18.5333 0.982552 18.1333 1.22422 17.8916L17.8909 1.22495C18.1326 0.983285 18.5326 0.983285 18.7742 1.22495C19.0159 1.46662 19.0159 1.86662 18.7742 2.10828L2.10755 18.775C1.98255 18.9 1.82422 18.9583 1.66589 18.9583Z"
                                                                                fill="#94A3B8" />
                                                                        </svg>
                                                                        <svg id="unmuteIcon" class="unmute-icon"
                                                                            style="display: none;" width="29"
                                                                            height="21" viewBox="0 0 29 21"
                                                                            fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M7.20731 15.7494C4.47457 15.7494 2.9046 15.1641 2.00923 14.3078C1.12413 13.4613 0.75 12.2067 0.75 10.5015C0.75 8.84742 1.22523 7.57593 2.18617 6.70634C3.15977 5.82528 4.75144 5.24768 7.20732 5.24768C8.60469 5.24768 9.64267 4.93873 10.4595 4.43239C11.2651 3.93308 11.7983 3.27536 12.2278 2.69506C12.3136 2.57923 12.3937 2.46892 12.4701 2.36383C12.7993 1.9111 13.0581 1.55507 13.3877 1.27035C13.7441 0.962547 14.1797 0.749998 14.8902 0.749998C15.6144 0.749998 16.1755 0.971438 16.6345 1.36555C17.1081 1.77229 17.5128 2.39853 17.8375 3.26305C18.4922 5.00592 18.75 7.51847 18.75 10.5015C18.75 13.4845 18.4922 15.9963 17.8376 17.7383C17.5129 18.6024 17.1082 19.2283 16.6346 19.6348C16.1757 20.0287 15.6146 20.25 14.8902 20.25C14.1828 20.25 13.7911 20.0401 13.4836 19.7507C13.206 19.4894 12.9995 19.1758 12.7298 18.7663C12.6405 18.6308 12.5444 18.4848 12.4365 18.3267C12.0307 17.7324 11.5088 17.0641 10.6659 16.5592C9.81957 16.0521 8.71825 15.7494 7.20731 15.7494Z"
                                                                                stroke="#94A3B8" stroke-width="1.5" />
                                                                            <path
                                                                                d="M24.5649 3C24.5649 3 25.7321 4.37314 26.2792 5.5C27.1055 7.20198 27.5649 8.4146 27.5649 10.5C27.5649 12.5854 27.1055 13.798 26.2792 15.5C25.7321 16.6269 24.5649 18 24.5649 18"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path
                                                                                d="M22.5 7C22.5 7 22.8891 7.6408 23.0714 8.16667C23.3469 8.96092 23.5 9.52681 23.5 10.5C23.5 11.4732 23.3469 12.0391 23.0714 12.8333C22.8891 13.3592 22.5 14 22.5 14"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                        Mute
                                                                    </button>
                                                                </li>
                                                                <li>
                                                                    <button class="dropdown-item postControlButton"
                                                                        href="#"
                                                                        data-event-id="{{ $event }}"
                                                                        data-event-post-id="{{ $poll['event_post_id'] }}"
                                                                        data-user-id="{{ $login_user_id }}"
                                                                        data-post-control="report">
                                                                        <svg viewBox="0 0 20 20" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M10.0013 18.9583C5.05964 18.9583 1.04297 14.9416 1.04297 9.99996C1.04297 5.05829 5.05964 1.04163 10.0013 1.04163C14.943 1.04163 18.9596 5.05829 18.9596 9.99996C18.9596 14.9416 14.943 18.9583 10.0013 18.9583ZM10.0013 2.29163C5.7513 2.29163 2.29297 5.74996 2.29297 9.99996C2.29297 14.25 5.7513 17.7083 10.0013 17.7083C14.2513 17.7083 17.7096 14.25 17.7096 9.99996C17.7096 5.74996 14.2513 2.29163 10.0013 2.29163Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M10 11.4583C9.65833 11.4583 9.375 11.175 9.375 10.8333V6.66663C9.375 6.32496 9.65833 6.04163 10 6.04163C10.3417 6.04163 10.625 6.32496 10.625 6.66663V10.8333C10.625 11.175 10.3417 11.4583 10 11.4583Z"
                                                                                fill="#94A3B8" />
                                                                            <path
                                                                                d="M10.0013 14.1667C9.89297 14.1667 9.78464 14.1417 9.68464 14.1C9.58464 14.0583 9.49297 14 9.40964 13.925C9.33464 13.8417 9.2763 13.7583 9.23464 13.65C9.19297 13.55 9.16797 13.4417 9.16797 13.3333C9.16797 13.225 9.19297 13.1167 9.23464 13.0167C9.2763 12.9167 9.33464 12.825 9.40964 12.7417C9.49297 12.6667 9.58464 12.6083 9.68464 12.5667C9.88464 12.4833 10.118 12.4833 10.318 12.5667C10.418 12.6083 10.5096 12.6667 10.593 12.7417C10.668 12.825 10.7263 12.9167 10.768 13.0167C10.8096 13.1167 10.8346 13.225 10.8346 13.3333C10.8346 13.4417 10.8096 13.55 10.768 13.65C10.7263 13.7583 10.668 13.8417 10.593 13.925C10.5096 14 10.418 14.0583 10.318 14.1C10.218 14.1417 10.1096 14.1667 10.0013 14.1667Z"
                                                                                fill="#94A3B8" />
                                                                        </svg>
                                                                        Report
                                                                    </button>
                                                                </li>
                                                            </ul>
                                                        </div>

                                                        <h5>

                                                                @if ($poll['rsvp_status'] == '1')
                                                                    <span class="positive-ans">
                                                                        <i
                                                                            class="fa-solid fa-circle-check"></i>Yes</span>
                                                                @elseif($poll['rsvp_status'] == '0')
                                                                    <span class="positive-ans not-ans"><i
                                                                            class="fa-solid fa-circle-question"></i>No
                                                                        Answer</span>
                                                                @elseif($poll['rsvp_status'] == '2')
                                                                    <span class="positive-ans nagative-ans">
                                                                        <i class="fa-solid fa-circle-xmark"></i>Not
                                                                        Coming
                                                                    </span>
                                                                @endif

                                                            {{ $poll['post_time'] }}
                                                        </h5>

                                                    </div>

                                                </div>
                                                <div class="posts-card-inner-wrp">
                                                    <h3 class="posts-card-inner-questions">
                                                        {{ $poll['poll_question'] }}</h3>
                                                </div>
                                                <input type="hidden" name="event_post_id" id="event_post_id"
                                                    value="{{ $poll['event_post_id'] }}">
                                                <div class="post-card-poll-wrp">
                                                    <div class="post-card-poll-inner">
                                                        <h5>{{ $poll['total_poll_vote'] }} Votes
                                                            <span>{{ $poll['poll_duration_left'] }} left</span>
                                                        </h5>
                                                        @foreach ($poll['poll_options'] as $index => $option)
                                                            <div class="poll-click-wrp poll-progress-one"
                                                                data-poll-id ="{{ $poll['poll_id'] }}"
                                                                data-option-id="{{ $option['id'] }}">
                                                                <button class="option-button"
                                                                    data-poll-id="{{ $poll['poll_id'] }}"
                                                                    data-option-id="{{ $option['id'] }}">
                                                                    {{ $option['option'] }}
                                                                    <span>{{ $option['total_vote_percentage'] }}</span>
                                                                </button>
                                                                <span class="poll-click-progress"
                                                                    style="width: {{ rtrim($option['total_vote_percentage'], '%') }}%;"></span>
                                                            </div>
                                                        @endforeach
                                                        <div class="expired-message"
                                                            style="color: red; display: none;"
                                                            id="errorMessage-{{ $poll['poll_id'] }}"></div>
                                                        {{-- <div class="poll-click-wrp poll-progress-two">
                                                        <h4>Yeah, Fine! 🙌 <span>80%</span></h4>
                                                        <span class="poll-click-progress" style="width: 50%;"></span>
                                                    </div> --}}
                                                    </div>
                                                </div>

                                                <div class="posts-card-like-commnet-wrp">
                                                    <div class="posts-card-like-comment-left">
                                                        <ul type="button" data-bs-toggle="modal"
                                                            data-bs-target="#reaction-modal-{{ $poll['event_post_id'] }}">

                                                            <!-- Smiley Emoji -->
                                                            @if ($poll['self_reaction'] == '\u{1F604}')
                                                                <li>
                                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                        alt="Smiley Emoji">
                                                                </li>

                                                                <!-- Eye Heart Emoji -->
                                                            @elseif ($poll['self_reaction'] == '\u{1F60D}')
                                                                <li>
                                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                        alt="Eye Heart Emoji">
                                                                </li>

                                                                <!-- Heart Emoji -->
                                                            @elseif ($poll['self_reaction'] == '\u{2764}')
                                                                <li>
                                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                        alt="Heart Emoji">
                                                                </li>
                                                            @endif
                                                            <p id="likeCount_{{ $poll['event_post_id'] }}">
                                                                {{ $poll['total_likes'] }} Likes</p>
                                                        </ul>
                                                        <h6>0 Comments</h6>
                                                    </div>
                                                    <div class="posts-card-like-comment-right">
                                                        <button class="posts-card-like-btn" id="likeButton"
                                                            data-event-id="{{ $event }}"
                                                            data-event-post-id="{{ $poll['event_post_id'] }} "
                                                            data-user-id="{{ $login_user_id }}">
                                                            @if ($poll['self_reaction'] == '\u{2764}')
                                                                <i class="fa-solid fa-heart" id="show_Emoji"></i>
                                                            @elseif($poll['self_reaction'] == '\u{1F494}')
                                                                <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                            @elseif($poll['self_reaction'] == '\u{1F44D}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                        loading="lazy" alt="Thumb Emoji"
                                                                        class="emoji" data-emoji="👍"
                                                                        data-unicode="\\u{1F44D}"></i>
                                                            @elseif($poll['self_reaction'] == '\u{1F604}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                        loading="lazy" alt="Smiley Emoji"
                                                                        class="emoji" data-emoji="😊"
                                                                        data-unicode="\\u{1F604}"></i>
                                                            @elseif($poll['self_reaction'] == '\u{1F60D}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                        loading="lazy" alt="Eye Heart Emoji"
                                                                        class="emoji" data-emoji="😍"
                                                                        data-unicode="\\u{1F60D}"></i>
                                                            @elseif($poll['self_reaction'] == '\u{1F44F}')
                                                                <i id="show_Emoji"> <img
                                                                        src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                        loading="lazy" alt="Clap Emoji"
                                                                        class="emoji" data-emoji="👏"
                                                                        data-unicode="\\u{1F44F}"></i>
                                                            @else
                                                                <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                            @endif
                                                        </button>
                                                        <button
                                                            class="posts-card-comm show-comments-btn show-btn-comment"
                                                            event_p_id="{{ $poll['event_post_id'] }}">
                                                            <svg viewBox="0 0 24 24" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                                                    stroke="#94A3B8" stroke-width="1.5"
                                                                    stroke-miterlimit="10" stroke-linecap="round"
                                                                    stroke-linejoin="round" />
                                                                <path d="M7 8H17" stroke="#94A3B8" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                                <path d="M7 13H13" stroke="#94A3B8" stroke-width="1.5"
                                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </div>
                                                <div class="posts-card-main-comment">
                                                    <input type="text" class="form-control" id="post_comment"
                                                        placeholder="Add Comment">
                                                    <span class="comment-send-icon send_comment poll-comment-send-icon"
                                                        data-event-id="{{ $event }}"
                                                        data-event-post-id="{{ $poll['event_post_id'] }}">
                                                        <svg viewBox="0 0 20 20" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                stroke-linecap="round" stroke-linejoin="round" />
                                                            <path d="M4.5332 10H9.0332" stroke="#94A3B8"
                                                                stroke-width="1.5" stroke-linecap="round"
                                                                stroke-linejoin="round" />
                                                        </svg>
                                                    </span>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                            <!-- ===tab-1-end=== -->

                            <!-- ===tab-2-start=== -->

                            <!-- ===tab-2-end=== -->

                            <!-- ===tab-3-start=== -->
                            <div class="tab-pane fade" id="nav-guests" role="tabpanel"
                                aria-labelledby="nav-guests-tab">
                                guests
                            </div>
                            <!-- ===tab-3-end=== -->

                            <!-- ===tab-4-start=== -->
                            <div class="tab-pane fade" id="nav-photos" role="tabpanel"
                                aria-labelledby="nav-photos-tab">
                                photos
                            </div>
                            <!-- ===tab-4-end=== -->

                            <!-- ===tab-5-start=== -->
                            <div class="tab-pane fade" id="nav-potluck" role="tabpanel"
                                aria-labelledby="nav-potluck-tab">
                                potluck
                            </div>
                            <!-- ===tab-5-en=== -->

                        </div>
                        <!-- ===tab-content-end=== -->

                    </div>
                    <!-- ===event-center-tabs-main-end=== -->
                </div>
            </div>
            <div class="col-xl-3 col-lg-0">
                <x-event_wall.wall_right_menu :eventInfo="$eventInfo" :event="$event" :login_user_id="$login_user_id" />
            </div>
        </div>
    </div>



    <!-- ===modals=== -->
    <div class="modal fade create-post-modal" id="creatpostmodal" tabindex="-1" aria-labelledby="exampleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="create-post-main-body">
                    <div class="modal-header">
                        <h1 class="modal-title" id="exampleModalLabel">Create New Post</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="create-post-profile">
                            <div class="create-post-profile-wrp">
                                @if ($users->profile != '')
                                    <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                                        alt="user-img" class="profile-pic" id="profile-pic-{{ $users->id }}"
                                        onclick="showStories( {{ $event }},{{ $users->id }})"
                                        loading="lazy">
                                @else
                                    @php
                                        $name = $users->firstname;
                                        // $parts = explode(" ", $name);
                                        $firstInitial = isset($users->firstname[0])
                                            ? strtoupper($users->firstname[0])
                                            : '';
                                        $secondInitial = isset($users->lastname[0])
                                            ? strtoupper($users->lastname[0])
                                            : '';
                                        $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                        $fontColor = 'fontcolor' . strtoupper($firstInitial);
                                    @endphp
                                    <h5 class="{{ $fontColor }}" class="profile-pic"
                                        id="profile-pic-{{ $users->id }}"
                                        onclick="showStories( {{ $event }},{{ $users->id }})">
                                        {{ $initials }}
                                    </h5>
                                @endif
                                {{-- <img src="{{ asset('assets/front/img/header-profile-img.png') }}" alt=""> --}}
                                <div id="savedSettingsDisplay">
                                    <h4> <i class="fa-solid fa-angle-down"></i></h4>
                                </div>
                            </div>
                        </div>
                        <form action="{{ route('event_wall.eventPost') }}" id="textform" method="POST"
                            enctype="multipart/form-data">
                            <input type="hidden" name="event_id" id="event_id" value="{{ $event }}">
                            <input type="hidden" class="hiddenVisibility" name="post_privacys" value="">

                            <input type="hidden" class="hiddenAllowComments" name="commenting_on_off" value="">

                            <input type="hidden" name="post_type" id="textPostType" value="0">
                            @csrf
                            <div class="create-post-textcontent">
                                <textarea class="form-control" rows="3" name="postContent" placeholder="What's on your mind?"
                                    id="postContent"></textarea>
                            </div>
                        </form>
                        <div class="create-post-upload-img-wrp d-none">
                            <div class="create-post-upload-img-head">
                                <h4>PHOTOS</h4>
                                <div>
                                    <button type="button" class="uploadButton create-post-head-upload-btn d-none"><i
                                            class="fa-solid fa-plus"></i> Add Photos/video
                                        <input type="file" id="fileInput2" class="fileInputtype"
                                            accept="image/*"></button>
                                    <span class="upload-img-delete">
                                        <svg viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21.875 6.22915C18.4062 5.8854 14.9167 5.70831 11.4375 5.70831C9.375 5.70831 7.3125 5.81248 5.25 6.02081L3.125 6.22915"
                                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M8.854 5.17706L9.08317 3.81248C9.24984 2.8229 9.37484 2.08331 11.1353 2.08331H13.8644C15.6248 2.08331 15.7603 2.86456 15.9165 3.8229L16.1457 5.17706"
                                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M19.6356 9.52081L18.9585 20.0104C18.8439 21.6458 18.7502 22.9166 15.8439 22.9166H9.15641C6.25016 22.9166 6.15641 21.6458 6.04183 20.0104L5.36475 9.52081"
                                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M10.7603 17.1875H14.229" stroke="#0F172A" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.896 13.0208H15.1043" stroke="#0F172A" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="create-post-upload-img-main">
                                <form action="{{ route('event_wall.eventPost') }}" id="photoForm" method="POST"
                                    enctype="multipart/form-data">
                                    @csrf
                                    <div class="create-post-upload-img-inner">
                                        <input type="hidden" name="event_id" id="event_id"
                                            value="{{ $event }}">
                                        <input type="hidden" class="hiddenVisibility" name="post_privacys"
                                            value="1">
                                        <input type="hidden" name="post_type" id="photoPostType" value="1">
                                        <input type="hidden" class="hiddenAllowComments" name="commenting_on_off"
                                            value="">
                                        <input type="hidden" name="postContent" id="photoContent">
                                        <span>
                                            <svg viewBox="0 0 24 25" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M9 10.5C10.1046 10.5 11 9.60457 11 8.5C11 7.39543 10.1046 6.5 9 6.5C7.89543 6.5 7 7.39543 7 8.5C7 9.60457 7.89543 10.5 9 10.5Z"
                                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M13 2.5H9C4 2.5 2 4.5 2 9.5V15.5C2 20.5 4 22.5 9 22.5H15C20 22.5 22 20.5 22 15.5V10.5"
                                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path d="M18 8.5V2.5L20 4.5" stroke="#64748B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M18 2.5L16 4.5" stroke="#64748B" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path
                                                    d="M2.66992 19.45L7.59992 16.14C8.38992 15.61 9.52992 15.67 10.2399 16.28L10.5699 16.57C11.3499 17.24 12.6099 17.24 13.3899 16.57L17.5499 13C18.3299 12.33 19.5899 12.33 20.3699 13L21.9999 14.4"
                                                    stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                        </span>
                                        <h3>Add Photos/Video</h3>
                                        <p>or drag and drop here</p>
                                        <button type="button" class="uploadButton">Upload
                                            <input type="file" id="fileInput" class="fileInputtype"
                                                accept="image/*,video/*" name="files[]" multiple></button>
                                    </div>
                                    <div class="create-post-uploaded-images">
                                        <div class="row" id="imagePreview">

                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>

                        <div class="create-post-poll-wrp d-none">
                            <div class="create-post-upload-img-head">
                                <h4>POLL</h4>
                                <div>
                                    <span class="upload-poll-delete delete_poll" id="delete_poll">
                                        <svg viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M21.875 6.22915C18.4062 5.8854 14.9167 5.70831 11.4375 5.70831C9.375 5.70831 7.3125 5.81248 5.25 6.02081L3.125 6.22915"
                                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M8.854 5.17706L9.08317 3.81248C9.24984 2.8229 9.37484 2.08331 11.1353 2.08331H13.8644C15.6248 2.08331 15.7603 2.86456 15.9165 3.8229L16.1457 5.17706"
                                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path
                                                d="M19.6356 9.52081L18.9585 20.0104C18.8439 21.6458 18.7502 22.9166 15.8439 22.9166H9.15641C6.25016 22.9166 6.15641 21.6458 6.04183 20.0104L5.36475 9.52081"
                                                stroke="#0F172A" stroke-width="1.5" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                            <path d="M10.7603 17.1875H14.229" stroke="#0F172A" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                            <path d="M9.896 13.0208H15.1043" stroke="#0F172A" stroke-width="1.5"
                                                stroke-linecap="round" stroke-linejoin="round" />
                                        </svg>
                                    </span>
                                </div>
                            </div>
                            <div class="create-post-poll-inner">
                                <form id="pollForm" action="{{ route('event_wall.createPoll') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="event_id" id="event_id"
                                        value="{{ $event }}">
                                    <input type="hidden" class="hiddenVisibility" name="post_privacys" value="1">
                                    <input type="hidden" class="hiddenAllowComments" name="commenting_on_off"
                                        value="1">
                                    {{-- <input type="hidden" name="post_type" id="pollPostType" value="2"> --}}
                                    <input type="hidden" name="content" id="pollContent">
                                    <div class="mb-3">
                                        <label for="yourquestion"
                                            class="form-label d-flex align-items-center justify-content-between">Your
                                            Question *
                                            <span class="char-count">0/140</span></label>
                                        <input type="text" name="question" class="form-control" id="yourquestion"
                                            placeholder="How is everyone doing this evening?" required>
                                    </div>
                                    <div class="mb-3">
                                        <label for="pollduration"
                                            class="form-label d-flex align-items-center justify-content-between">Poll
                                            Duration</label>
                                        <select name="duration" class="form-select"
                                            aria-label="Default select example" required>
                                            <option value="">Select Poll Duration</option>
                                            <option value="1 Hour">1 Hour</option>
                                            <option value="1 Day">1 Day</option>
                                            <option value="2 Day">2 Day</option>
                                            <option value="3 Day">3 Day</option>
                                            <option value="4 Day">4 Day</option>
                                            <option value="5 Day">5 Day</option>
                                            <option value="6 Day">6 Day</option>
                                            <option value="1 Week">1 Week</option>
                                            <option value="1 Month">1 Month</option>
                                        </select>
                                    </div>
                                    <div class="create-post-poll-option-wrp">
                                        <div class="create-post-poll-option-head mb-4">
                                            <h3>Options</h3>
                                            <span class="option-add-btn"><i class="fa-solid fa-plus"></i></span>
                                        </div>
                                        <div class="poll-options">
                                            <div class="mb-3">
                                                <label for="yourquestion"
                                                    class="form-label d-flex align-items-center justify-content-between">Option
                                                    1*
                                                    <span class="char-count">0/140</span></label>
                                                <input type="text" name="options[]" class="form-control"
                                                    id="yourquestion" placeholder="" required>
                                            </div>
                                            <div class="mb-3">
                                                <label for="yourquestion"
                                                    class="form-label d-flex align-items-center justify-content-between">Option
                                                    2*
                                                    <span class="char-count">0/140</span></label>
                                                <input type="text" name="options[]" class="form-control"
                                                    id="yourquestion" placeholder="" required>
                                            </div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <div>
                            <button class="upload-photo-poll photos" id="create-photo-btn">
                                <svg viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M8.0013 18.3333H13.0013C17.168 18.3333 18.8346 16.6666 18.8346 12.5V7.49996C18.8346 3.33329 17.168 1.66663 13.0013 1.66663H8.0013C3.83464 1.66663 2.16797 3.33329 2.16797 7.49996V12.5C2.16797 16.6666 3.83464 18.3333 8.0013 18.3333Z"
                                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M7.9987 8.33333C8.91917 8.33333 9.66536 7.58714 9.66536 6.66667C9.66536 5.74619 8.91917 5 7.9987 5C7.07822 5 6.33203 5.74619 6.33203 6.66667C6.33203 7.58714 7.07822 8.33333 7.9987 8.33333Z"
                                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M2.72656 15.7917L6.8349 13.0333C7.49323 12.5917 8.44323 12.6417 9.0349 13.15L9.3099 13.3917C9.9599 13.95 11.0099 13.95 11.6599 13.3917L15.1266 10.4167C15.7766 9.85834 16.8266 9.85834 17.4766 10.4167L18.8349 11.5833"
                                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </button>
                            <button class="upload-photo-poll polls" id="create-poll-btn">
                                <svg viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M2.16797 18.3334H18.8346" stroke="#94A3B8" stroke-width="1.5"
                                        stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"></path>
                                    <path
                                        d="M8.625 3.33329V18.3333H12.375V3.33329C12.375 2.41663 12 1.66663 10.875 1.66663H10.125C9 1.66663 8.625 2.41663 8.625 3.33329Z"
                                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M3 8.33329V18.3333H6.33333V8.33329C6.33333 7.41663 6 6.66663 5 6.66663H4.33333C3.33333 6.66663 3 7.41663 3 8.33329Z"
                                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                    <path
                                        d="M14.668 12.5V18.3334H18.0013V12.5C18.0013 11.5834 17.668 10.8334 16.668 10.8334H16.0013C15.0013 10.8334 14.668 11.5834 14.668 12.5Z"
                                        stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round"></path>
                                </svg>
                            </button>
                        </div>
                        <div class="">
                            <button type ="button" class="cmn-btn create_post_btn">
                                post
                            </button>
                        </div>
                    </div>
                </div>
                <div class="create-post-setting-main-body d-none">
                    <div class="modal-header">
                        <button type="button" class="btn-back"><i class="fa-solid fa-arrow-left"></i></button>
                        <h1 class="modal-title" id="exampleModalLabel">Post Settings</h1>
                    </div>
                    <form action="" id="postSettingsForm">
                        <div class="modal-body">
                            <div class="create-post-setting-inner">
                                <h3>Who can see your post?</h3>

                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_privacy"
                                        id="flexRadioDefault1" value="1">
                                    <label class="form-check-label" for="flexRadioDefault1">
                                        Everyone
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_privacy"
                                        id="flexRadioDefault2" value="2">
                                    <label class="form-check-label" for="flexRadioDefault2">
                                        RSVP’d - Yes
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_privacy"
                                        id="flexRadioDefault3" value="3">
                                    <label class="form-check-label" for="flexRadioDefault3">
                                        RSVP’d - No
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="post_privacy"
                                        id="flexRadioDefault4" value=" 4">
                                    <label class="form-check-label" for="flexRadioDefault4">
                                        RSVP’d - No Reply
                                    </label>
                                </div>

                                <div class="button-cover">
                                    <h3>Commenting</h3>
                                    <div class="button r" id="button-3">
                                        <input type="checkbox" id="allowComments" name="commenton" value="1"
                                            class="checkbox" checked />
                                        <div class="knobs"></div>
                                        <div class="layer"></div>
                                    </div>
                                </div>




                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" id="saveSettings" class="cmn-btn back-btn">
                                Save
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- ===modals=== -->



    <!-- Modal -->
    @foreach ($postList as $post)
        {{-- {{dd($post);}} --}}
        <div class="modal fade create-post-modal all-events-filtermodal" id="reaction-modal-{{ $post['id'] }}"
            tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reactions</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="reactions-info-main event-center-tabs-main">
                            <!-- ===tabs=== -->
                            <nav>
                                <div class="nav nav-tabs reaction-nav-tabs" id="nav-tab-{{ $post['id'] }}"
                                    role="tablist">
                                    <!-- All Reactions Tab -->
                                    <button class="nav-link active" id="nav-all-reaction-tab-{{ $post['id'] }}"
                                        data-bs-toggle="tab" data-bs-target="#nav-all-reaction-{{ $post['id'] }}"
                                        type="button" role="tab" aria-controls="nav-all-reaction"
                                        aria-selected="true">
                                        All {{ count($post['reactionList']) }}
                                    </button>

                                    <!-- Individual Reaction Tabs -->
                                    @php
                                        // Define icons for reactions
                                        $reactionIcons = [
                                            "\\u{2764}" => asset('assets/front/img/heart-emoji.png'), // ❤️
                                            "\\u{1F44D}" => asset('assets/front/img/thumb-icon.png'), // 👍
                                            "\\u{1F604}" => asset('assets/front/img/smily-emoji.png'), // 😄
                                            "\\u{1F60D}" => asset('assets/front/img/eye-heart-emoji.png'), // 😍
                                            "\\u{1F44F}" => asset('assets/front/img/clap-icon.png'), // 👏
                                        ];
                                    @endphp

                                    @foreach (array_count_values($post['reactionList']) as $reaction => $count)
                                        <button class="nav-link"
                                            id="nav-{{ $reaction }}-reaction-tab-{{ $post['id'] }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#nav-{{ $reaction }}-reaction-{{ $post['id'] }}"
                                            type="button" role="tab"
                                            aria-controls="nav-{{ $reaction }}-reaction" aria-selected="false">
                                            <img src="{{ $reactionIcons[$reaction] ?? asset('assets/front/img/default-icon.png') }}"
                                                alt="{{ $reaction }}" loading="lazy">
                                            {{ $count }}
                                        </button>
                                    @endforeach
                                </div>
                            </nav>

                            <!-- ===tabs=== -->

                            <!-- ===Tab-content=== -->
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade active show" id="nav-all-reaction" role="tabpanel"
                                    aria-labelledby="nav-all-reaction-tab">
                                    <ul>
                                        @php
                                            // Define reaction icons mapping
                                            $reactionIcons = [
                                                "\\u{2764}" => asset('assets/front/img/heart-emoji.png'), // ❤️
                                                "\\u{1F44D}" => asset('assets/front/img/thumb-icon.png'), // 👍
                                                "\\u{1F604}" => asset('assets/front/img/smily-emoji.png'), // 😄
                                                "\\u{1F60D}" => asset('assets/front/img/eye-heart-emoji.png'), // 😍
                                                "\\u{1F44F}" => asset('assets/front/img/clap-icon.png'), // 👏
                                            ];
                                        @endphp
                                        @foreach ($post['reactionList'] as $reaction)
                                            <li class="reaction-info-wrp">
                                                <div class="commented-user-head">
                                                    <!-- User Profile Section -->
                                                    <div class="commented-user-profile">
                                                        <div class="commented-user-profile-img">
                                                            @if ($post['profile'] != '')
                                                                <img src="{{ $post['profile'] ? asset($post['profile']) : asset('assets/front/img/default-profile.png') }}"
                                                                    alt="{{ $post['username'] }}" loading="lazy">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $nameParts = explode(' ', $post['username']);
                                                                    $firstInitial = isset($nameParts[0][0])
                                                                        ? strtoupper($nameParts[0][0])
                                                                        : '';
                                                                    $secondInitial = isset($nameParts[1][0])
                                                                        ? strtoupper($nameParts[1][0])
                                                                        : '';
                                                                    $initials = $firstInitial . $secondInitial;

                                                                    // Generate a font color class based on the first initial
                                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif

                                                        </div>
                                                        <div class="commented-user-profile-content">
                                                            <h3>{{ $post['username'] }}</h3>
                                                            <p>{{ $post['location'] }}</p>
                                                        </div>
                                                    </div>
                                                    <!-- Reaction Emoji Section -->
                                                    <div
                                                        class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                        <img src="{{ $reactionIcons[$reaction] ?? asset('assets/front/img/default-icon.png') }}"
                                                            alt="{{ $reaction }}" loading="lazy">
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>

                                {{-- <div class="tab-pane fade" id="nav-heart-reaction" role="tabpanel"
                                    aria-labelledby="nav-heart-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img" >
                                                    <img src="./assets/img/heart-emoji.png" alt="" loading="lazy">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/heart-emoji.png" alt="" loading="lazy">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/heart-emoji.png" alt="" loading="lazy">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-thumb-reaction" role="tabpanel"
                                    aria-labelledby="nav-thumb-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/thumb-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/thumb-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/thumb-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/thumb-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png" alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/thumb-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-smily-reaction" role="tabpanel"
                                    aria-labelledby="nav-smily-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/smily-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/smily-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/smily-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/smily-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/smily-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>

                                <div class="tab-pane fade" id="nav-eye-heart-reaction" role="tabpanel"
                                    aria-labelledby="nav-eye-heart-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/eye-heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/eye-heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/eye-heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/eye-heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/eye-heart-emoji.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div>


                                <div class="tab-pane fade" id="nav-clap-reaction" role="tabpanel"
                                    aria-labelledby="nav-clap-reaction-tab">
                                    <ul>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/clap-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/clap-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/clap-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/clap-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                        <li class="reaction-info-wrp">
                                            <div class="commented-user-head">
                                                <div class="commented-user-profile">
                                                    <div class="commented-user-profile-img">
                                                        <img src="./assets/img/header-profile-img.png"
                                                            alt="">
                                                    </div>
                                                    <div class="commented-user-profile-content">
                                                        <h3>Angel Geidt</h3>
                                                        <p>New York</p>
                                                    </div>
                                                </div>
                                                <div
                                                    class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                    <img src="./assets/img/clap-icon.png" alt="">
                                                </div>
                                            </div>
                                        </li>
                                    </ul>
                                </div> --}}
                            </div>
                            <!-- ===Tab-content=== -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cmn-btn reset-btn">Reset</button>
                        <button type="button" class="cmn-btn">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    @foreach ($pollsData as $post)
        {{-- {{dd($post);}} --}}
        <div class="modal fade create-post-modal all-events-filtermodal"
            id="reaction-modal-{{ $post['event_post_id'] }}" tabindex="-1" aria-labelledby="exampleModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h1 class="modal-title fs-5" id="exampleModalLabel">Reactions</h1>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                            aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="reactions-info-main event-center-tabs-main">
                            <!-- ===tabs=== -->
                            <nav>
                                <div class="nav nav-tabs reaction-nav-tabs"
                                    id="nav-tab-{{ $post['event_post_id'] }}" role="tablist">
                                    <!-- All Reactions Tab -->
                                    <button class="nav-link active"
                                        id="nav-all-reaction-tab-{{ $post['event_post_id'] }}" data-bs-toggle="tab"
                                        data-bs-target="#nav-all-reaction-{{ $post['event_post_id'] }}"
                                        type="button" role="tab" aria-controls="nav-all-reaction"
                                        aria-selected="true">
                                        All {{ count($post['reactionList']) }}
                                    </button>

                                    <!-- Individual Reaction Tabs -->
                                    @php
                                        // Define icons for reactions
                                        $reactionIcons = [
                                            "\\u{2764}" => asset('assets/front/img/heart-emoji.png'), // ❤️
                                            "\\u{1F44D}" => asset('assets/front/img/thumb-icon.png'), // 👍
                                            "\\u{1F604}" => asset('assets/front/img/smily-emoji.png'), // 😄
                                            "\\u{1F60D}" => asset('assets/front/img/eye-heart-emoji.png'), // 😍
                                            "\\u{1F44F}" => asset('assets/front/img/clap-icon.png'), // 👏
                                        ];
                                    @endphp

                                    @foreach (array_count_values($post['reactionList']) as $reaction => $count)
                                        <button class="nav-link"
                                            id="nav-{{ $reaction }}-reaction-tab-{{ $post['event_post_id'] }}"
                                            data-bs-toggle="tab"
                                            data-bs-target="#nav-{{ $reaction }}-reaction-{{ $post['event_post_id'] }}"
                                            type="button" role="tab"
                                            aria-controls="nav-{{ $reaction }}-reaction"
                                            aria-selected="false">
                                            <img src="{{ $reactionIcons[$reaction] ?? asset('assets/front/img/default-icon.png') }}"
                                                alt="{{ $reaction }}" loading="lazy">
                                            {{ $count }}
                                        </button>
                                    @endforeach
                                </div>
                            </nav>

                            <!-- ===tabs=== -->

                            <!-- ===Tab-content=== -->
                            <div class="tab-content" id="myTabContent">

                                <div class="tab-pane fade active show" id="nav-all-reaction" role="tabpanel"
                                    aria-labelledby="nav-all-reaction-tab">
                                    <ul>
                                        @php
                                            // Define reaction icons mapping
                                            $reactionIcons = [
                                                "\\u{2764}" => asset('assets/front/img/heart-emoji.png'), // ❤️
                                                "\\u{1F44D}" => asset('assets/front/img/thumb-icon.png'), // 👍
                                                "\\u{1F604}" => asset('assets/front/img/smily-emoji.png'), // 😄
                                                "\\u{1F60D}" => asset('assets/front/img/eye-heart-emoji.png'), // 😍
                                                "\\u{1F44F}" => asset('assets/front/img/clap-icon.png'), // 👏
                                            ];
                                        @endphp
                                        @foreach ($post['reactionList'] as $reaction)
                                            <li class="reaction-info-wrp">
                                                <div class="commented-user-head">
                                                    <!-- User Profile Section -->
                                                    <div class="commented-user-profile">
                                                        <div class="commented-user-profile-img">
                                                            @if ($users->profile != '')
                                                                <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                                                                    alt="" loading="lazy">
                                                            @else
                                                                @php

                                                                    // $parts = explode(" ", $name);
                                                                    $nameParts = explode(' ', $users->firstname);
                                                                    $lastname = explode(' ', $users->lastname);
                                                                    $firstInitial = isset($nameParts[0][0])
                                                                        ? strtoupper($nameParts[0][0])
                                                                        : '';
                                                                    $secondInitial = isset($lastname[0][0])
                                                                        ? strtoupper($lastname[0][0])
                                                                        : '';
                                                                    $initials = $firstInitial . $secondInitial;

                                                                    // Generate a font color class based on the first initial
                                                                    $fontColor = 'fontcolor' . $firstInitial;
                                                                @endphp
                                                                <h5 class="{{ $fontColor }}">
                                                                    {{ $initials }}
                                                                </h5>
                                                            @endif


                                                        </div>
                                                        <div class="commented-user-profile-content">
                                                            <h3>{{ $users->firstname }} {{ $users->lastname }}</h3>
                                                            <p>{{ $users->city }},{{ $users->state }}</p>
                                                        </div>
                                                    </div>
                                                    <!-- Reaction Emoji Section -->
                                                    <div
                                                        class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                        <img src="{{ $reactionIcons[$reaction] ?? asset('assets/front/img/default-icon.png') }}"
                                                            alt="{{ $reaction }}" loading="lazy">
                                                    </div>
                                                </div>
                                            </li>
                                        @endforeach

                                    </ul>
                                </div>

                                {{-- <div class="tab-pane fade" id="nav-heart-reaction" role="tabpanel"
                                aria-labelledby="nav-heart-reaction-tab">
                                <ul>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img" >
                                                <img src="./assets/img/heart-emoji.png" alt="" loading="lazy">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/heart-emoji.png" alt="" loading="lazy">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/heart-emoji.png" alt="" loading="lazy">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="" loading="lazy">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="nav-thumb-reaction" role="tabpanel"
                                aria-labelledby="nav-thumb-reaction-tab">
                                <ul>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/thumb-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/thumb-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/thumb-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/thumb-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png" alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/thumb-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="nav-smily-reaction" role="tabpanel"
                                aria-labelledby="nav-smily-reaction-tab">
                                <ul>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/smily-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/smily-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/smily-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/smily-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/smily-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>

                            <div class="tab-pane fade" id="nav-eye-heart-reaction" role="tabpanel"
                                aria-labelledby="nav-eye-heart-reaction-tab">
                                <ul>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/eye-heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/eye-heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/eye-heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/eye-heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/eye-heart-emoji.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div>


                            <div class="tab-pane fade" id="nav-clap-reaction" role="tabpanel"
                                aria-labelledby="nav-clap-reaction-tab">
                                <ul>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/clap-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/clap-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/clap-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/clap-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                    <li class="reaction-info-wrp">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="./assets/img/header-profile-img.png"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div
                                                class="posts-card-like-comment-right reaction-profile-reaction-img">
                                                <img src="./assets/img/clap-icon.png" alt="">
                                            </div>
                                        </div>
                                    </li>
                                </ul>
                            </div> --}}
                            </div>
                            <!-- ===Tab-content=== -->
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="cmn-btn reset-btn">Reset</button>
                        <button type="button" class="cmn-btn">Apply</button>
                    </div>
                </div>
            </div>
        </div>
    @endforeach
    <!-- ========= edit-rsvp ======== -->
    <div class="modal fade cmn-modal" id="editrsvp" tabindex="-1" aria-labelledby="editrsvpLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editrsvpLabel">Edit RSVP Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="guest-rsvp-head">
                        <div class="rsvp-img">
                            <img src="./assets/img/rs-img.png" alt="rs-img">
                        </div>
                        <h5>Tiana Dokidis</h5>
                    </div>
                    <div class="guest-rsvp-incres">
                        <h6>Guests</h6>
                        <div class="guest-edit-wrp">
                            <div class="guest-edit-box">
                                <p>Adults</p>
                                <div class="qty-container ms-auto">
                                    <button class="qty-btn-minus" type="button"><i
                                            class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty" />
                                    <button class="qty-btn-plus" type="button"><i
                                            class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="guest-edit-box">
                                <p>Kids</p>
                                <div class="qty-container ms-auto">
                                    <button class="qty-btn-minus" type="button"><i
                                            class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty" />
                                    <button class="qty-btn-plus" type="button"><i
                                            class="fa fa-plus"></i></button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="guest-rsvp-attend">
                        <h6>RSVP</h6>
                        <div class="input-form">
                            <input type="radio" id="option1" name="foo" checked />
                            <label for="option1"><svg class="me-2" width="21" height="20"
                                    viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.5001 18.3334C15.0834 18.3334 18.8334 14.5834 18.8334 10.0001C18.8334 5.41675 15.0834 1.66675 10.5001 1.66675C5.91675 1.66675 2.16675 5.41675 2.16675 10.0001C2.16675 14.5834 5.91675 18.3334 10.5001 18.3334Z"
                                        stroke="#23AA26" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M6.95825 9.99993L9.31659 12.3583L14.0416 7.6416" stroke="#23AA26"
                                        stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                                </svg>Attending</label>
                        </div>
                        <div class="input-form">
                            <input type="radio" id="option2" name="foo" />
                            <label for="option2"><svg class="me-2" width="21" height="20"
                                    viewBox="0 0 21 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M10.4974 18.3346C15.0807 18.3346 18.8307 14.5846 18.8307 10.0013C18.8307 5.41797 15.0807 1.66797 10.4974 1.66797C5.91406 1.66797 2.16406 5.41797 2.16406 10.0013C2.16406 14.5846 5.91406 18.3346 10.4974 18.3346Z"
                                        stroke="#E03137" stroke-width="1.5" stroke-linecap="round"
                                        stroke-linejoin="round" />
                                    <path d="M8.14062 12.3573L12.8573 7.64062" stroke="#E03137" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                    <path d="M12.8573 12.3573L8.14062 7.64062" stroke="#E03137" stroke-width="1.5"
                                        stroke-linecap="round" stroke-linejoin="round" />
                                </svg>Not Attending</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer rsvp-button-wrp">
                    <button type="button" class="btn btn-secondary remove-btn" data-bs-dismiss="modal">Remove
                        Guest</button>
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Update</button>
                </div>
            </div>
        </div>
    </div>

    <!-- ========= Add-guest ======== -->
    {{-- <div class="modal fade cmn-modal" id="addguest" tabindex="-1" aria-labelledby="addguestLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="addguestLabel">Add Guests</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body guest-tab">
                    <ul class="nav nav-tabs" id="myTab" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link active" id="home-tab" data-bs-toggle="tab"
                                data-bs-target="#home" type="button" role="tab" aria-controls="home"
                                aria-selected="true">Yestive Contacts</button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link" id="profile-tab" data-bs-toggle="tab"
                                data-bs-target="#profile" type="button" role="tab" aria-controls="profile"
                                aria-selected="false">Phone Contacts</button>
                        </li>
                    </ul>
                    <div class="tab-content" id="myTabContent">
                        <div class="tab-pane fade show active" id="home" role="tabpanel"
                            aria-labelledby="home-tab">
                            <div class="guest-users-wrp">
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Silvia Alegra</h6>
                                </div>
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Emery Vaccaro</h6>
                                </div>
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Alena Geidt</h6>
                                </div>
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Gerald Vincent</h6>
                                </div>
                                <a href="#" class="guest-user d-block">
                                    <div class="guest-user-img guest-total">
                                        <span class="number">10</span>
                                        <span class="content">Total</span>
                                    </div>
                                    <h6>Sell all</h6>
                                </a>
                            </div>
                            <div class="position-relative">
                                <input type="search" placeholder="Search name" class="form-control">
                                <span class="input-search">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="guest-user-list-wrp invite-contact-wrp">
                                <div class="invite-contact">
                                    <a href="#" class="invite-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="invite-img">
                                    </a>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="#" class="invite-user-name" data-bs-toggle="modal"
                                                data-bs-target="#editguest">Silvia
                                                Alegra</a>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="invite-mail-data faild-content">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <h6>silvia@gmail.com</h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="" checked>
                                            </div>
                                        </div>
                                        <div class="invite-call-data mt-1">
                                            <div class="d-flex align-items-center">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                        stroke="#0F172A" stroke-miterlimit="10" />
                                                </svg>
                                                <h6>1-800-5587</h6>
                                            </div>
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="invite-contact">
                                    <a href="#" class="invite-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="invite-img">
                                    </a>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="#" class="invite-user-name">Silvia Alegra</a>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="invite-mail-data faild-content">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <h6>silvia@gmail.com</h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="" checked>
                                            </div>
                                        </div>
                                        <div class="invite-call-data mt-1">
                                            <div class="d-flex align-items-center">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                        stroke="#0F172A" stroke-miterlimit="10" />
                                                </svg>
                                                <h6>1-800-5587</h6>
                                            </div>
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="invite-contact">
                                    <a href="#" class="invite-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="invite-img">
                                    </a>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="#" class="invite-user-name">Silvia Alegra</a>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="invite-mail-data faild-content">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <h6>silvia@gmail.com</h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="" checked>
                                            </div>
                                        </div>
                                        <div class="invite-call-data mt-1">
                                            <div class="d-flex align-items-center">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                        stroke="#0F172A" stroke-miterlimit="10" />
                                                </svg>
                                                <h6>1-800-5587</h6>
                                            </div>
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="profile" role="tabpanel" aria-labelledby="profile-tab">
                            <div class="guest-users-wrp">
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Silvia Alegra</h6>
                                </div>
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Emery Vaccaro</h6>
                                </div>
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Alena Geidt</h6>
                                </div>
                                <div class="guest-user">
                                    <div class="guest-user-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="guest-img">
                                        <a href="#" class="close">
                                            <svg width="19" height="18" viewBox="0 0 19 18"
                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" fill="#F73C71" />
                                                <rect x="1.20312" y="1" width="16" height="16"
                                                    rx="8" stroke="white" stroke-width="2" />
                                                <path d="M6.86719 6.66699L11.5335 11.3333" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                                <path d="M6.8649 11.3333L11.5312 6.66699" stroke="white"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </a>
                                    </div>
                                    <h6>Gerald Vincent</h6>
                                </div>
                                <a href="#" class="guest-user d-block">
                                    <div class="guest-user-img guest-total">
                                        <span class="number">10</span>
                                        <span class="content">Total</span>
                                    </div>
                                    <h6>Sell all</h6>
                                </a>
                            </div>
                            <div class="position-relative">
                                <input type="search" placeholder="Search name" class="form-control">
                                <span class="input-search">
                                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M11.5 21C16.7467 21 21 16.7467 21 11.5C21 6.25329 16.7467 2 11.5 2C6.25329 2 2 6.25329 2 11.5C2 16.7467 6.25329 21 11.5 21Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round"></path>
                                        <path d="M22 22L20 20" stroke="#94A3B8" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round"></path>
                                    </svg>
                                </span>
                            </div>
                            <div class="guest-user-list-wrp invite-contact-wrp">
                                <div class="invite-contact">
                                    <a href="#" class="invite-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="invite-img">
                                    </a>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="#" class="invite-user-name">Silvia Alegra</a>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="invite-mail-data faild-content">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <h6>silvia@gmail.com</h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="" checked>
                                            </div>
                                        </div>
                                        <div class="invite-call-data mt-1">
                                            <div class="d-flex align-items-center">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                        stroke="#0F172A" stroke-miterlimit="10" />
                                                </svg>
                                                <h6>1-800-5587</h6>
                                            </div>
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="invite-contact">
                                    <a href="#" class="invite-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="invite-img">
                                    </a>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="#" class="invite-user-name">Silvia Alegra</a>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="invite-mail-data faild-content">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <h6>silvia@gmail.com</h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="" checked>
                                            </div>
                                        </div>
                                        <div class="invite-call-data mt-1">
                                            <div class="d-flex align-items-center">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                        stroke="#0F172A" stroke-miterlimit="10" />
                                                </svg>
                                                <h6>1-800-5587</h6>
                                            </div>
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                                <div class="invite-contact">
                                    <a href="#" class="invite-img">
                                        <img src="./assets/img/event-story-img-1.png" alt="invite-img">
                                    </a>
                                    <div class="w-100">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <a href="#" class="invite-user-name">Silvia Alegra</a>
                                        </div>
                                        <div class="d-flex align-items-center mt-1">
                                            <div class="invite-mail-data faild-content">
                                                <div class="d-flex align-items-center">
                                                    <svg width="14" height="14" viewBox="0 0 14 14"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M9.91797 11.9577H4.08464C2.33464 11.9577 1.16797 11.0827 1.16797 9.04102V4.95768C1.16797 2.91602 2.33464 2.04102 4.08464 2.04102H9.91797C11.668 2.04102 12.8346 2.91602 12.8346 4.95768V9.04102C12.8346 11.0827 11.668 11.9577 9.91797 11.9577Z"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                        <path
                                                            d="M9.91536 5.25L8.08953 6.70833C7.4887 7.18667 6.50286 7.18667 5.90203 6.70833L4.08203 5.25"
                                                            stroke="black" stroke-miterlimit="10"
                                                            stroke-linecap="round" stroke-linejoin="round" />
                                                    </svg>
                                                    <h6>silvia@gmail.com</h6>
                                                </div>
                                            </div>
                                            <div class="ms-auto">
                                                <input class="form-check-input failed-checkout" type="checkbox"
                                                    value="" checked>
                                            </div>
                                        </div>
                                        <div class="invite-call-data mt-1">
                                            <div class="d-flex align-items-center">
                                                <svg width="14" height="14" viewBox="0 0 14 14"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.8171 10.6918C12.8171 10.9018 12.7705 11.1177 12.6713 11.3277C12.5721 11.5377 12.4438 11.736 12.2746 11.9227C11.9888 12.2377 11.6738 12.4652 11.318 12.611C10.968 12.7568 10.5888 12.8327 10.1805 12.8327C9.58547 12.8327 8.94963 12.6927 8.2788 12.4068C7.60797 12.121 6.93714 11.736 6.27214 11.2518C5.6013 10.7618 4.96547 10.2193 4.3588 9.61852C3.75797 9.01185 3.21547 8.37602 2.7313 7.71102C2.25297 7.04602 1.86797 6.38102 1.58797 5.72185C1.30797 5.05685 1.16797 4.42102 1.16797 3.81435C1.16797 3.41768 1.23797 3.03852 1.37797 2.68852C1.51797 2.33268 1.73964 2.00602 2.0488 1.71435C2.42214 1.34685 2.83047 1.16602 3.26214 1.16602C3.42547 1.16602 3.5888 1.20102 3.73464 1.27102C3.8863 1.34102 4.02047 1.44602 4.12547 1.59768L5.4788 3.50518C5.5838 3.65102 5.65964 3.78518 5.71214 3.91352C5.76464 4.03602 5.7938 4.15852 5.7938 4.26935C5.7938 4.40935 5.75297 4.54935 5.6713 4.68352C5.59547 4.81768 5.48464 4.95768 5.34464 5.09768L4.9013 5.55852C4.83714 5.62268 4.80797 5.69852 4.80797 5.79185C4.80797 5.83852 4.8138 5.87935 4.82547 5.92602C4.84297 5.97268 4.86047 6.00768 4.87214 6.04268C4.97714 6.23518 5.15797 6.48602 5.41464 6.78935C5.67714 7.09268 5.95713 7.40185 6.26047 7.71102C6.57547 8.02018 6.8788 8.30602 7.18797 8.56852C7.4913 8.82518 7.74213 9.00018 7.94047 9.10518C7.96963 9.11685 8.00464 9.13435 8.04547 9.15185C8.09214 9.16935 8.1388 9.17518 8.1913 9.17518C8.29047 9.17518 8.3663 9.14018 8.43047 9.07602L8.8738 8.63852C9.01964 8.49268 9.15964 8.38185 9.2938 8.31185C9.42797 8.23018 9.56213 8.18935 9.70797 8.18935C9.8188 8.18935 9.93547 8.21268 10.0638 8.26518C10.1921 8.31768 10.3263 8.39352 10.4721 8.49268L12.403 9.86352C12.5546 9.96852 12.6596 10.091 12.7238 10.2368C12.7821 10.3827 12.8171 10.5285 12.8171 10.6918Z"
                                                        stroke="#0F172A" stroke-miterlimit="10" />
                                                </svg>
                                                <h6>1-800-5587</h6>
                                            </div>
                                            <input class="form-check-input failed-checkout" type="checkbox"
                                                value="">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer rsvp-button-wrp">
                    <button type="button" class="btn btn-secondary success-btn"
                        data-bs-dismiss="modal">Re-send</button>
                </div>
            </div>
        </div>
    </div> --}}


</main>
