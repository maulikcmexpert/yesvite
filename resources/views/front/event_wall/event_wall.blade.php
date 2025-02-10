{{-- {{dd($postList)}} --}}
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
                                <li class="breadcrumb-item"><a href="{{ route('event.event_lists') }}">Events</a></li>
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
                    <input type="hidden" id="is_filter_applied" value="{{$is_filter_applied}}"/>
                    <!-- ===event-breadcrumb-wrp-end=== -->
                    <x-event_wall.wall_title :eventDetails="$eventDetails" :selectedFilters="$selectedFilters" />
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
                                @if ($eventDetails['event_wall'] == 1)



                                    <div class="event-center-wall-main">
                                        <!-- ================story================= -->
                                        <x-event_wall.wall_story :users="$users" :event="$event" :storiesList="$storiesList"
                                            :wallData="$wallData" />
                                        <x-event_wall.wall_crate_poll_photo :users="$users" :event="$event" />
                                        {{-- {{                                    dd($postList)}} --}}
                                        <div class="wall-post-content">
                                            @foreach ($postList as $post)
                                                <div class="event-posts-main-wrp common-div-wrp hidden_post"
                                                    data-post-id="{{ $post['id'] }}">

                                                    <div class="posts-card-wrp guest-user-list">
                                                        <div class="posts-card-head">
                                                            <div class="posts-card-head-left">
                                                                <div class="posts-card-head-left-img">
                                                                    @if ($post['profile'] != '')
                                                                        <img src="{{ $post['profile'] }}"
                                                                            alt="">
                                                                    @else
                                                                        @php

                                                                            // $parts = explode(" ", $name);
                                                                            $nameParts = explode(
                                                                                ' ',
                                                                                $post['username'],
                                                                            );
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
                                                                    <h3>{{ $post['username'] }}
                                                                        @if ($post['is_host'] == '1')
                                                                            <span class="host">Host</span>
                                                                        @endif
                                                                        @if ($post['is_co_host'] == '1')
                                                                            <span class="host">Co Host</span>
                                                                        @endif
                                                                    </h3>

                                                                    <p>{{ $post['location'] }}</p>
                                                                </div>
                                                            </div>
                                                            <div class="posts-card-head-right">
                                                                <div
                                                                    class="dropdown post-card-dropdown upcoming-card-dropdown">
                                                                    <button class="dropdown-toggle " type="button"
                                                                        data-bs-toggle="dropdown"
                                                                        aria-expanded="false"><i
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
                                                                                <svg id="icon"
                                                                                    class="hide-post-svg-icon"
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
                                                                                    style="display: none;"
                                                                                    width="29" height="21"
                                                                                    viewBox="0 0 29 21" fill="none"
                                                                                    xmlns="http://www.w3.org/2000/svg">
                                                                                    <path
                                                                                        d="M7.20731 15.7494C4.47457 15.7494 2.9046 15.1641 2.00923 14.3078C1.12413 13.4613 0.75 12.2067 0.75 10.5015C0.75 8.84742 1.22523 7.57593 2.18617 6.70634C3.15977 5.82528 4.75144 5.24768 7.20732 5.24768C8.60469 5.24768 9.64267 4.93873 10.4595 4.43239C11.2651 3.93308 11.7983 3.27536 12.2278 2.69506C12.3136 2.57923 12.3937 2.46892 12.4701 2.36383C12.7993 1.9111 13.0581 1.55507 13.3877 1.27035C13.7441 0.962547 14.1797 0.749998 14.8902 0.749998C15.6144 0.749998 16.1755 0.971438 16.6345 1.36555C17.1081 1.77229 17.5128 2.39853 17.8375 3.26305C18.4922 5.00592 18.75 7.51847 18.75 10.5015C18.75 13.4845 18.4922 15.9963 17.8376 17.7383C17.5129 18.6024 17.1082 19.2283 16.6346 19.6348C16.1757 20.0287 15.6146 20.25 14.8902 20.25C14.1828 20.25 13.7911 20.0401 13.4836 19.7507C13.206 19.4894 12.9995 19.1758 12.7298 18.7663C12.6405 18.6308 12.5444 18.4848 12.4365 18.3267C12.0307 17.7324 11.5088 17.0641 10.6659 16.5592C9.81957 16.0521 8.71825 15.7494 7.20731 15.7494Z"
                                                                                        stroke="#94A3B8"
                                                                                        stroke-width="1.5" />
                                                                                    <path
                                                                                        d="M24.5649 3C24.5649 3 25.7321 4.37314 26.2792 5.5C27.1055 7.20198 27.5649 8.4146 27.5649 10.5C27.5649 12.5854 27.1055 13.798 26.2792 15.5C25.7321 16.6269 24.5649 18 24.5649 18"
                                                                                        stroke="#94A3B8"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                    <path
                                                                                        d="M22.5 7C22.5 7 22.8891 7.6408 23.0714 8.16667C23.3469 8.96092 23.5 9.52681 23.5 10.5C23.5 11.4732 23.3469 12.0391 23.0714 12.8333C22.8891 13.3592 22.5 14 22.5 14"
                                                                                        stroke="#94A3B8"
                                                                                        stroke-width="1.5"
                                                                                        stroke-linecap="round"
                                                                                        stroke-linejoin="round" />
                                                                                </svg>
                                                                                <span class="muteClass">Mute</span>
                                                                                <span style="display:none"
                                                                                    class="unmuteClass">Unmute</span>
                                                                            </button>
                                                                        </li>
                                                                        <li>
                                                                            <button
                                                                                class="dropdown-item postControlButton"
                                                                                href="#"
                                                                                data-event-id="{{ $event }}"
                                                                                data-event-post-id="{{ $post['id'] }}"
                                                                                data-user-id="{{ $login_user_id }}"
                                                                                data-post-control="report">
                                                                                <svg viewBox="0 0 20 20"
                                                                                    fill="none"
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
                                                                    @if ($post['rsvp_status'] == '1' && $post['is_co_host'] == '0')
                                                                        <span class="positive-ans">
                                                                            <i
                                                                                class="fa-solid fa-circle-check"></i>Yes</span>
                                                                    @elseif($post['rsvp_status'] == '0' && $post['is_co_host'] == '0')
                                                                        <span class="positive-ans not-ans"><i
                                                                                class="fa-solid fa-circle-question"></i>No
                                                                            Answer</span>
                                                                    @elseif($post['rsvp_status'] == '2' && $post['is_co_host'] == '0')
                                                                        <span class="positive-ans nagative-ans">
                                                                            <i class="fa-solid fa-circle-xmark"></i>Not
                                                                            Coming
                                                                        </span>
                                                                    @endif

                                                                    {{ \Carbon\Carbon::parse($post['posttime'])->shortAbsoluteDiffForHumans() }}



                                                                </h5>
                                                            </div>
                                                        </div>

                                                        <div class="posts-card-inner-wrp">
                                                            <h3 class="posts-card-inner-questions">
                                                                {{ $post['post_message'] }}
                                                            </h3>
                                                        </div>


                                                        {{-- {{  dd($post['post_image'])}} --}}
                                                        @if ($post['post_type'] == '1')
                                                            <div class="posts-card-show-post-wrp">
                                                                <div class="swiper posts-card-post">
                                                                    <div class="swiper-wrapper">
                                                                        <!-- Slides -->
                                                                        @if (!empty($post['post_image']))
                                                                            @foreach ($post['post_image'] as $image)
                                                                                @if ($image['type'] == 'video')
                                                                                    <div class="swiper-slide">
                                                                                        <div
                                                                                            class="posts-card-show-post-img">
                                                                                            <video
                                                                                                src="{{ $image['media_url'] }}"
                                                                                                alt=""
                                                                                                loading="lazy"
                                                                                                controls="true"
                                                                                                muted />
                                                                                        </div>
                                                                                    </div>
                                                                                @else
                                                                                    <div class="swiper-slide">
                                                                                        <div
                                                                                            class="posts-card-show-post-img">
                                                                                            <img src="{{ $image['media_url'] }}"
                                                                                                alt=""
                                                                                                loading="lazy" />
                                                                                        </div>
                                                                                    </div>
                                                                                @endif
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
                                                        @endif

                                                        @if ($post['post_type'] == '4')
                                                            @if ($post['rsvp_status'] == '1')
                                                                {{-- <div class="success-yes">
                                                                    <h5 class="green">YES</h5>
                                                                    <div class="success-cat ms-auto">

                                                                        <h5>{{ $post['adults'] }} Adults</h5>
                                                                        <h5>{{ $post['kids'] }} Kids</h5>
                                                                    </div>
                                                                </div> --}}
                                                                <div class="sucess-yes">
                                                                    <h5 class="green">YES</h5>
                                                                    <div class="sucesss-cat ms-auto">
                                                                        <svg width="15" height="15"
                                                                            viewBox="0 0 15 15" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z"
                                                                                fill="black" fill-opacity="0.2">
                                                                            </path>
                                                                            <path
                                                                                d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z"
                                                                                fill="black" fill-opacity="0.2">
                                                                            </path>
                                                                            <path
                                                                                d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z"
                                                                                fill="black" fill-opacity="0.2">
                                                                            </path>
                                                                            <path
                                                                                d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z"
                                                                                fill="black" fill-opacity="0.2">
                                                                            </path>
                                                                        </svg>
                                                                        <h5>{{ $post['adults'] }} Adults</h5>
                                                                        <h5>{{ $post['kids'] }} Kids</h5>
                                                                    </div>
                                                                </div>

                                                            @else
                                                            <div class="success-no">
                                                                <h5>NO</h5>
                                                            </div>
                                                            @endif
                                                        @endif

                                                        @if ($post['post_type'] == '2')
                                                            @if($post['post_message']=="")
                                                                <div class="posts-card-inner-wrp">
                                                                    <h3 class="posts-card-inner-questions">
                                                                        {{ $post['poll_question'] }}
                                                                    </h3>
                                                                </div>
                                                            @endif
                                                            <input type="hidden" name="event_post_id"
                                                                id="event_post_id" value="{{ $post['id'] }}">
                                                            <div class="post-card-poll-wrp">
                                                            @if($post['post_message']!="")
                                                                    <h3 class="posts-card-inner-questions">
                                                                        {{ $post['poll_question'] }}</h3>
                                                             @endif
                                                                <div class="post-card-poll-inner">
                                                                <div class="d-flex align-items-center justify-content-between w-100 mb-1">
                                                                <h5>
                                                                    {{ $post['total_poll_vote'] }} Votes
                                                                    <span>{{ $post['poll_duration'] }} left</span>
                                                                </h5>
                                                                    <svg style="width: 18px;height:18px" width="18" height="18" viewBox="0 0 18 18" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                        <path d="M16.5 16.5H1.5C1.1925 16.5 0.9375 16.245 0.9375 15.9375C0.9375 15.63 1.1925 15.375 1.5 15.375H16.5C16.8075 15.375 17.0625 15.63 17.0625 15.9375C17.0625 16.245 16.8075 16.5 16.5 16.5Z" fill="#94A3B8"></path>
                                                                        <path d="M7.3125 3V16.5H10.6875V3C10.6875 2.175 10.35 1.5 9.3375 1.5H8.6625C7.65 1.5 7.3125 2.175 7.3125 3Z" fill="#94A3B8"></path>
                                                                        <path d="M2.25 7.5V16.5H5.25V7.5C5.25 6.675 4.95 6 4.05 6H3.45C2.55 6 2.25 6.675 2.25 7.5Z" fill="#94A3B8"></path>
                                                                        <path d="M12.75 11.25V16.5H15.75V11.25C15.75 10.425 15.45 9.75 14.55 9.75H13.95C13.05 9.75 12.75 10.425 12.75 11.25Z" fill="#94A3B8"></path>
                                                                    </svg>
                                                                    </div>
                                                                    @foreach ($post['poll_option'] as $index => $option)
                                                                        <div class="poll-click-wrp poll-progress-one"
                                                                            data-poll-id ="{{ $post['poll_id'] }}"
                                                                            data-option-id="{{ $option['id'] }}">
                                                                            <button class="option-button"
                                                                                data-poll-id="{{ $post['poll_id'] }}"
                                                                                data-option-id="{{ $option['id'] }}">
                                                                                {{ $option['option'] }}
                                                                                <span>{{ $option['total_vote'] }}</span>
                                                                            </button>
                                                                            <span class="poll-click-progress"
                                                                                style="width: {{ rtrim($option['total_vote'], '%') }}%;"></span>
                                                                        </div>
                                                                    @endforeach
                                                                    <div class="expired-message"
                                                                        style="color: red; display: none;"
                                                                        id="errorMessage-{{ $post['poll_id'] }}">
                                                                    </div>
                                                                    {{-- <div class="poll-click-wrp poll-progress-two">
                                                        <h4>Yeah, Fine! 🙌 <span>80%</span></h4>
                                                        <span class="poll-click-progress" style="width: 50%;"></span>
                                                    </div> --}}
                                                                </div>
                                                            </div>
                                                        @endif
                                                        <div class="posts-card-like-commnet-wrp photo-card-head-right">
                                                            <div class="posts-card-like-comment-left">
                                                            <!-- data-bs-target="#reaction-modal" data-post="{{$post['id']}}"> -->
                                                                <ul type="button" data-bs-toggle="modal"
                                                                    data-bs-target="#reaction-modal" data-post="{{$post['id']}}">
                                                                    @php $i=0; $j = 0; @endphp
                                                                    @foreach ($post['reactionList'] as $reaction)


                                                                    <!-- Smiley Emoji -->


                                                                    @if ($j==0 && ($post['self_reaction'] == $reaction))
                                                                        <li id="reactionImage_{{ $post['id'] }}">

                                                                        @php $j++; @endphp
                                                                    @else
                                                                    <li>
                                                                    @endif
                                                                            @if ($reaction == '\u{1F604}')
                                                                                <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                                    alt="Smiley Emoji">
                                                                            @elseif ($reaction == '\u{1F60D}')
                                                                                <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                                    alt="Eye Heart Emoji">
                                                                            @elseif ($reaction == '\u{2764}')
                                                                                <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                                    alt="Heart Emoji">

                                                                            @elseif($reaction == '\u{1F44D}')
                                                                            <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                                    loading="lazy" alt="Thumb Emoji"
                                                                                    class="emoji" data-emoji="👍"
                                                                                    data-unicode="\\u{1F44D}">

                                                                            @elseif($reaction == '\u{1F44F}')
                                                                                <img
                                                                                    src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                                    loading="lazy" alt="Clap Emoji"
                                                                                    class="emoji" data-emoji="👏"
                                                                                    data-unicode="\\u{1F44F}">
                                                                                @endif

                                                                        </li>
                                                                        @php $i++;  if($i==3){break;} @endphp
                                                                    @endforeach
                                                                    {{-- @if($j==0)
                                                                    <li id="reactionImage_{{ $post['id'] }}">
                                                                    </li>
                                                                    @endif --}}
                                                                    <p id="likeCount_{{ $post['id'] }}">
                                                                        {{ $post['total_likes'] }} Likes</p>
                                                                </ul>
                                                                @if ($post['commenting_on_off'] == '1')
                                                                    <h6 id="comment_{{ $post['id'] }}">
                                                                        {{ $post['total_comment'] }} Comments</h6>
                                                                @endif
                                                            </div>
                                                            <div
                                                                class="posts-card-like-comment-right photo-card-head-right set_emoji_like emoji_display_like">
                                                                @php
                                                                    if ($post['self_reaction'] == '\u{2764}') {
                                                                        $liked = 'liked';
                                                                    } else {
                                                                        $liked = '1';
                                                                    }
                                                                @endphp

                                                                <button class="posts-card-like-btn  set_emoji_like"
                                                                    id="likeButton"
                                                                    data-event-id="{{ $event }}"
                                                                    data-event-post-id="{{ $post['id'] }}"
                                                                    data-user-id="{{ $login_user_id }}">
                                                                    @if ($post['self_reaction'] == '\u{2764}')
                                                                        <i class="fa-solid fa-heart"
                                                                            id="show_Emoji"></i>
                                                                    @elseif($post['self_reaction'] == '\u{1F494}')
                                                                        <i class="fa-regular fa-heart"
                                                                            id="show_Emoji"></i>
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
                                                                        <i class="fa-regular fa-heart"
                                                                            id="show_Emoji"></i>
                                                                    @endif
                                                                </button>

                                                                <div class="photos-likes-options-wrp emoji-picker"
                                                                    id="emojiDropdown" style="display: none;">
                                                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                        alt="Heart Emoji" class="emoji model_emoji"
                                                                        data-emoji="❤️" data-unicode="\\u{2764}">
                                                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                        alt="Thumb Emoji" class="emoji  model_emoji"
                                                                        data-emoji="👍" data-unicode="\\u{1F44D}">
                                                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                        alt="Smiley Emoji" class="emoji model_emoji"
                                                                        data-emoji="😊" data-unicode="\\u{1F604}">
                                                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                        alt="Eye Heart Emoji"
                                                                        class="emoji model_emoji" data-emoji="😍"
                                                                        data-unicode="\\u{1F60D}">
                                                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                        alt="Clap Emoji" class="emoji"
                                                                        data-emoji="👏" data-unicode="\\u{1F44F}">
                                                                </div>

                                                                @if ($post['commenting_on_off'] == '1')
                                                                    <button
                                                                        class="posts-card-comm show-comments-btn show-btn-comment comment_btn"
                                                                        event_p_id="{{ $post['id'] }}">
                                                                        <svg viewBox="0 0 24 24" fill="none"
                                                                            xmlns="http://www.w3.org/2000/svg">
                                                                            <path
                                                                                d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                                                                stroke="#94A3B8" stroke-width="1.5"
                                                                                stroke-miterlimit="10"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M7 8H17" stroke="#94A3B8"
                                                                                stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                            <path d="M7 13H13" stroke="#94A3B8"
                                                                                stroke-width="1.5"
                                                                                stroke-linecap="round"
                                                                                stroke-linejoin="round" />
                                                                        </svg>
                                                                    </button>
                                                                @endif

                                                            </div>
                                                        </div>

                                                        <div class="posts-card-main-comment">
                                                            @if ($post['commenting_on_off'] == '1')
                                                                <input type="text"
                                                                    class="form-control post_comment"
                                                                    id="post_comment" placeholder="Add Comment">
                                                                <span class="comment-send-icon send_comment"
                                                                    data-event-id="{{ $event }}"
                                                                    data-event-post-id="{{ $post['id'] }}">
                                                                    <svg viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                                                            stroke="#94A3B8" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path d="M4.5332 10H9.0332" stroke="#94A3B8"
                                                                            stroke-width="1.5" stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </span>
                                                            @else
                                                                <input type="text"
                                                                    class="form-control post_comment"
                                                                    id="post_comment" placeholder="Add Comment"
                                                                    style="display:none;">
                                                                <span class="comment-send-icon send_comment"
                                                                    style="display:none;"
                                                                    data-event-id="{{ $event }}"
                                                                    data-event-post-id="{{ $post['id'] }}">
                                                                    <svg viewBox="0 0 20 20" fill="none"
                                                                        xmlns="http://www.w3.org/2000/svg">
                                                                        <path
                                                                            d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                                                            stroke="#94A3B8" stroke-width="1.5"
                                                                            stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                        <path d="M4.5332 10H9.0332" stroke="#94A3B8"
                                                                            stroke-width="1.5" stroke-linecap="round"
                                                                            stroke-linejoin="round" />
                                                                    </svg>
                                                                </span>
                                                            @endif
                                                            <input type="hidden" id="comment_on_of"
                                                                value="{{ $post['commenting_on_off'] }}">

                                                        </div>
                                                        {{-- {{dd($post['post_comment'] )}} --}}
                                                        <div
                                                            class="posts-card-show-all-comments-wrp d-none show_{{ $post['id'] }}">

                                                            <div class="posts-card-show-all-comments-inner">
                                                                <ul class="top-level-comments">

                                                                    <input type="hidden" class="parent_comment_id"
                                                                        value="">



                                                                    @foreach ($post['post_comment'] as $key => $comment)
                                                                        <li class="commented-user-wrp"
                                                                            data-comment-id="{{ $comment['id'] }}">
                                                                            <input type="hidden"
                                                                                class="data_comment_id"
                                                                                value="{{ $comment['id'] }}">
                                                                            <div class="commented-user-head">
                                                                                <div class="commented-user-profile">
                                                                                    <div
                                                                                        class="commented-user-profile-img">
                                                                                        @if ($comment['profile'] != '')
                                                                                            <img src="{{ $comment['profile'] }}"
                                                                                                alt=""
                                                                                                loading="lazy">
                                                                                        @else
                                                                                            @php
                                                                                                $nameParts = explode(
                                                                                                    ' ',
                                                                                                    $comment[
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
                                                                                        <h3>{{ $comment['username'] }}
                                                                                        </h3>
                                                                                        <p>{{ $comment['location'] ?? '' }}
                                                                                        </p>
                                                                                    </div>
                                                                                </div>
                                                                                <div
                                                                                    class="posts-card-like-comment-right">
                                                                                    @php
                                                                                        if ($comment['is_like'] == 1) {
                                                                                            $liked = 'liked';
                                                                                        } else {
                                                                                            $liked = '';
                                                                                        }
                                                                                    @endphp
                                                                                    <input type="hidden"
                                                                                        id="login_user_id"
                                                                                        value="{{ $login_user_id }}">
                                                                                    <p>{{ $comment['posttime'] }}</p>
                                                                                    <button
                                                                                        class="posts-card-like-btn {{ $liked }}"
                                                                                        id="CommentlikeButton"
                                                                                        data-event-id="{{ $event }}"
                                                                                        data-event-post-comment-id="{{ $comment['id'] }}"
                                                                                        data-user-id="{{ $login_user_id }}">
                                                                                        @if ($comment['is_like'] == 1)
                                                                                            <i class="fa-solid fa-heart"
                                                                                                id="show_Emoji"></i>
                                                                                        @elseif($comment['is_like'] == 0)
                                                                                            <i class="fa-regular fa-heart"
                                                                                                id="show_Emoji"></i>
                                                                                        @endif
                                                                                    </button>
                                                                                </div>
                                                                            </div>
                                                                            <div class="commented-user-content">
                                                                                <p>{{ $comment['comment'] }}</p>
                                                                            </div>
                                                                            <div class="commented-user-reply-wrp">
                                                                                <div
                                                                                    class="position-relative d-flex align-items-center gap-2">
                                                                                    <button class="posts-card-like-btn"
                                                                                        id="CommentlikeButton"
                                                                                        data-event-id="{{ $event }}"
                                                                                        data-event-post-comment-id="{{ $comment['id'] }}"
                                                                                        data-user-id="{{ $login_user_id }}">
                                                                                        @if ($comment['is_like'] == 1)
                                                                                            <i class="fa-solid fa-heart"
                                                                                                id="show_Emoji"></i>
                                                                                        @elseif($comment['is_like'] == 0)
                                                                                            <i class="fa-regular fa-heart"
                                                                                                id="show_Emoji"></i>
                                                                                        @endif
                                                                                    </button>
                                                                                    <p
                                                                                        id="commentTotalLike_{{ $comment['id'] }}">
                                                                                        {{ isset($comment['comment_total_likes']) ? $comment['comment_total_likes'] : 0 }}
                                                                                    </p>
                                                                                </div>
                                                                                <button
                                                                                    data-comment-id="{{ $comment['id'] }}"
                                                                                    class="commented-user-reply-btn">Reply</button>
                                                                            </div>
                                                                            <ul class="primary-comment-replies">
                                                                                @if ($comment['total_replies'] > 0)
                                                                                    @foreach ($comment['comment_replies'] as $reply)
                                                                                        <li class="reply-on-comment"
                                                                                            data-comment-id="{{ $reply['id'] }}">
                                                                                            <div
                                                                                                class="commented-user-head">
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
                                                                                                        class="posts-card-like-btn"
                                                                                                        id="CommentlikeButton"
                                                                                                        data-event-id="{{ $event }}"
                                                                                                        data-event-post-comment-id="{{ $reply['id'] }}"
                                                                                                        data-user-id="{{ $login_user_id }}">
                                                                                                        @if ($reply['is_like'] == 1)
                                                                                                            <i class="fa-solid fa-heart"
                                                                                                                id="show_Emoji"></i>
                                                                                                        @elseif($reply['is_like'] == 0)
                                                                                                            <i class="fa-regular fa-heart"
                                                                                                                id="show_Emoji"></i>
                                                                                                        @endif
                                                                                                    </button>
                                                                                                </div>
                                                                                            </div>
                                                                                            <div
                                                                                                class="commented-user-content">
                                                                                                <p>{{ $reply['comment'] }}
                                                                                                </p>
                                                                                            </div>
                                                                                            <div
                                                                                                class="commented-user-reply-wrp">
                                                                                                <div
                                                                                                    class="position-relative d-flex align-items-center gap-2">
                                                                                                    <button
                                                                                                        class="posts-card-like-btn"
                                                                                                        id="CommentlikeButton"
                                                                                                        data-event-id="{{ $event }}"
                                                                                                        data-event-post-comment-id="{{ $reply['id'] }}"
                                                                                                        data-user-id="{{ $login_user_id }}">

                                                                                                        @if ($reply['is_like'] == 1)
                                                                                                            <i class="fa-solid fa-heart"
                                                                                                                id="show_Emoji"></i>
                                                                                                        @elseif($reply['is_like'] == 0)
                                                                                                            <i class="fa-regular fa-heart"
                                                                                                                id="show_Emoji"></i>
                                                                                                        @endif
                                                                                                    </button>
                                                                                                    <p
                                                                                                        id="commentTotalLike_{{ $reply['id'] }}">
                                                                                                        {{ isset($reply['comment_total_likes']) ? $reply['comment_total_likes'] : 0 }}
                                                                                                    </p>

                                                                                                </div>
                                                                                                <button
                                                                                                    class="commented-user-reply-btn"
                                                                                                    data-comment-id="{{ $reply['id'] }}">Reply</button>
                                                                                            </div>
                                                                                        </li>
                                                                                    @endforeach

                                                                                    
                                                                                    @if ($comment['total_replies'] > 0)
                                                                                        <button
                                                                                            class="show-comment-reply-btn">Hide
                                                                                            reply</button>
                                                                                    @endif
                                                                                @endif
                                                                            </ul>


                                                                        </li>
                                                                    @endforeach
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>

                                                </div>

                                                
                                            @endforeach
                                        </div>
                                    </div>
                                @else
                                    <div class="off-by-host-wrp">
                                        <div class="off-by-host-inner">
                                            <div class="off-by-host-inner-left">
                                                <svg width="15" height="15" viewBox="0 0 15 15"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M5.625 1.25C3.9875 1.25 2.65625 2.58125 2.65625 4.21875C2.65625 5.825 3.9125 7.125 5.55 7.18125C5.6 7.175 5.65 7.175 5.6875 7.18125C5.7 7.18125 5.70625 7.18125 5.71875 7.18125C5.725 7.18125 5.725 7.18125 5.73125 7.18125C7.33125 7.125 8.5875 5.825 8.59375 4.21875C8.59375 2.58125 7.2625 1.25 5.625 1.25Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M8.8001 8.84453C7.05635 7.68203 4.2126 7.68203 2.45635 8.84453C1.6626 9.37578 1.2251 10.0945 1.2251 10.8633C1.2251 11.632 1.6626 12.3445 2.4501 12.8695C3.3251 13.457 4.4751 13.7508 5.6251 13.7508C6.7751 13.7508 7.9251 13.457 8.8001 12.8695C9.5876 12.3383 10.0251 11.6258 10.0251 10.8508C10.0188 10.082 9.5876 9.36953 8.8001 8.84453Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M12.4938 4.58732C12.5938 5.79982 11.7313 6.86232 10.5376 7.00607C10.5313 7.00607 10.5313 7.00607 10.5251 7.00607H10.5063C10.4688 7.00607 10.4313 7.00607 10.4001 7.01857C9.79385 7.04982 9.2376 6.85607 8.81885 6.49982C9.4626 5.92482 9.83135 5.06232 9.75635 4.12482C9.7126 3.61857 9.5376 3.15607 9.2751 2.76232C9.5126 2.64357 9.7876 2.56857 10.0688 2.54357C11.2938 2.43732 12.3876 3.34982 12.4938 4.58732Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                    <path
                                                        d="M13.7437 10.369C13.6937 10.9752 13.3062 11.5002 12.6562 11.8565C12.0312 12.2002 11.2437 12.3627 10.4624 12.344C10.9124 11.9377 11.1749 11.4315 11.2249 10.894C11.2874 10.119 10.9187 9.37525 10.1812 8.7815C9.7624 8.45025 9.2749 8.18775 8.74365 7.994C10.1249 7.594 11.8624 7.86275 12.9312 8.72525C13.5062 9.18775 13.7999 9.769 13.7437 10.369Z"
                                                        fill="black" fill-opacity="0.2"></path>
                                                </svg>
                                            </div>
                                            <div class="off-by-host-inner-right">
                                                <svg width="21" height="21" viewBox="0 0 21 21"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M12.6092 8.26855L8.39258 12.4852C7.85091 11.9436 7.51758 11.2019 7.51758 10.3769C7.51758 8.72689 8.85091 7.39355 10.5009 7.39355C11.3259 7.39355 12.0676 7.72689 12.6092 8.26855Z"
                                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M15.3499 5.18535C13.8915 4.08535 12.2249 3.48535 10.4999 3.48535C7.5582 3.48535 4.81654 5.21868 2.9082 8.21868C2.1582 9.39368 2.1582 11.3687 2.9082 12.5437C3.56654 13.577 4.3332 14.4687 5.16654 15.1854"
                                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M7.51758 16.652C8.46758 17.052 9.47591 17.2687 10.5009 17.2687C13.4426 17.2687 16.1842 15.5354 18.0926 12.5354C18.8426 11.3604 18.8426 9.38535 18.0926 8.21035C17.8176 7.77702 17.5176 7.36868 17.2092 6.98535"
                                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path
                                                        d="M13.4242 10.9604C13.2076 12.1354 12.2492 13.0938 11.0742 13.3104"
                                                        stroke="#64748B" stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M8.39102 12.4854L2.16602 18.7104" stroke="#64748B"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                    <path d="M18.8324 2.04346L12.6074 8.26846" stroke="#64748B"
                                                        stroke-width="1.5" stroke-linecap="round"
                                                        stroke-linejoin="round" />
                                                </svg>
                                                <h4>Wall made private by host</h4>
                                            </div>
                                        </div>
                                    </div>
                                @endif

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
                        <form action="{{ route('event_wall.eventPost') }}" id="photoForm" method="POST"
                            enctype="multipart/form-data">
                            <input type="hidden" name="event_id" id="event_id" value="{{ $event }}">
                            <input type="hidden" class="hiddenVisibility" name="post_privacys" value="">

                            <input type="hidden" class="hiddenAllowComments" name="commenting_on_off"
                                value="">


                            <input type="hidden" name="post_type" id="textPostType" value="0">
                            @csrf
                            <div class="create-post-textcontent">
                                <textarea class="form-control post_message" rows="3" name="postContent" placeholder="What's on your mind?"></textarea>
                            </div>

                            <div class="create-post-upload-img-wrp d-none">
                                <div class="create-post-upload-img-head">
                                    <h4>PHOTOS</h4>
                                    <div>
                                        <button type="button"
                                            class="uploadButton create-post-head-upload-btn d-none"><i
                                                class="fa-solid fa-plus"></i> Add Photos/video
                                            <input type="file" id="fileInput2" name="files[]"
                                                class="fileInputtype" accept="image/* video/*" multiple></button>
                                        <span class="upload-img-delete">
                                            <svg viewBox="0 0 25 25" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
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


                                    <div class="create-post-upload-img-inner">
                                        <input type="hidden" name="event_id" id="event_id"
                                            value="{{ $event }}">
                                        <input type="hidden" class="hiddenVisibility" name="post_privacys"
                                            value="1">
                                        <input type="hidden" name="post_type" id="photoPostType" value="1">
                                        <input type="hidden" class="hiddenAllowComments" name="commenting_on_off"
                                            value="">

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

                                </div>
                            </div>
                        </form>
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
                                    <input type="hidden" class="hiddenVisibility" name="post_privacys"
                                        value="1">
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
                                        id="flexRadioDefault4" value="4">
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





    <!-- ========= edit-rsvp ======== -->
    <div class="modal fade cmn-modal" id="editrsvp" tabindex="-1" aria-labelledby="editrsvpLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="editrsvpLabel">Edit RSVP Details</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
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
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty" />
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
                                </div>
                            </div>
                            <div class="guest-edit-box">
                                <p>Kids</p>
                                <div class="qty-container ms-auto">
                                    <button class="qty-btn-minus" type="button"><i class="fa fa-minus"></i></button>
                                    <input type="number" name="qty" value="0" class="input-qty" />
                                    <button class="qty-btn-plus" type="button"><i class="fa fa-plus"></i></button>
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

    <div class="modal fade create-post-modal all-events-filtermodal" id="reaction-modal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content">
            <div class="modal-header">
              <h1 class="modal-title fs-5" id="exampleModalLabel">Reactions</h1>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="reactions-info-main event-center-tabs-main">
                    <!-- ===tabs=== -->
                    <nav>
                      <div class="nav nav-tabs reaction-nav-tabs" id="nav-tab" role="tablist">
                        <button class="nav-link active" id="nav-all-reaction-tab" data-bs-toggle="tab" data-bs-target="#nav-all-reaction" type="button" role="tab" aria-controls="nav-all-reaction" aria-selected="true">
                            All 106
                        </button>
                        <button class="nav-link" id="nav-heart-reaction-tab" data-bs-toggle="tab" data-bs-target="#nav-heart-reaction" type="button" role="tab" aria-controls="nav-heart-reaction" aria-selected="false" tabindex="-1">
                            <img src="{{asset('assets/front/img/heart-emoji.png')}}" alt=""> 50
                        </button>
                        <button class="nav-link" id="nav-thumb-reaction-tab" data-bs-toggle="tab" data-bs-target="#nav-thumb-reaction" type="button" role="tab" aria-controls="nav-thumb-reaction" aria-selected="false" tabindex="-1">
                            <img src="{{asset('assets/front/img/thumb-icon.png')}}" alt=""> 50
                        </button>
                        <button class="nav-link" id="nav-smily-reaction-tab" data-bs-toggle="tab" data-bs-target="#nav-smily-reaction" type="button" role="tab" aria-controls="nav-smily-reaction" aria-selected="false" tabindex="-1">
                            <img src="{{asset('assets/front/img/smily-emoji.png')}}" alt=""> 50
                        </button>
                        <button class="nav-link" id="nav-eye-heart-reaction-tab" data-bs-toggle="tab" data-bs-target="#nav-eye-heart-reaction" type="button" role="tab" aria-controls="nav-eye-heart-reaction" aria-selected="false" tabindex="-1">
                            <img src="{{asset('assets/front/img/eye-heart-emoji.png')}}" alt=""> 50
                        </button>
                        <button class="nav-link" id="nav-clap-reaction-tab" data-bs-toggle="tab" data-bs-target="#nav-clap-reaction" type="button" role="tab" aria-controls="nav-clap-reaction" aria-selected="false" tabindex="-1">
                            <img src="{{asset('assets/front/img/clap-icon.png')}}" alt=""> 50
                        </button>
                      </div>
                    </nav>
                    <!-- ===tabs=== -->

                    <!-- ===Tab-content=== -->
                    <div class="tab-content" id="myTabContent">

                      <div class="tab-pane fade active show" id="nav-all-reaction" role="tabpanel" aria-labelledby="nav-all-reaction-tab">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                  <img src="./assets/img/smily-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                  <img src="./assets/img/eye-heart-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                  <img src="./assets/img/eye-heart-emoji.png" alt="">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>

                      <div class="tab-pane fade" id="nav-heart-reaction" role="tabpanel" aria-labelledby="nav-heart-reaction-tab">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/heart-emoji.png" alt="">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>

                      <div class="tab-pane fade" id="nav-thumb-reaction" role="tabpanel" aria-labelledby="nav-thumb-reaction-tab">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/thumb-icon.png" alt="">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>

                      <div class="tab-pane fade" id="nav-smily-reaction" role="tabpanel" aria-labelledby="nav-smily-reaction-tab">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                  <img src="./assets/img/smily-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/smily-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/smily-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/smily-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/smily-emoji.png" alt="">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>

                      <div class="tab-pane fade" id="nav-eye-heart-reaction" role="tabpanel" aria-labelledby="nav-eye-heart-reaction-tab">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                  <img src="./assets/img/eye-heart-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/eye-heart-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/eye-heart-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/eye-heart-emoji.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/eye-heart-emoji.png" alt="">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>


                      <div class="tab-pane fade" id="nav-clap-reaction" role="tabpanel" aria-labelledby="nav-clap-reaction-tab">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                  <img src="./assets/img/clap-icon.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/clap-icon.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/clap-icon.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/clap-icon.png" alt="">
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
                              <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                <img src="./assets/img/clap-icon.png" alt="">
                              </div>
                            </div>
                          </li>
                        </ul>
                      </div>
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


</main>

<script>
    use Carbon\ Carbon;

    public
    function getShortTimeAttribute() {
        $time = Carbon::parse($this - > posttime);

        return str_replace(
            [' second', ' seconds', ' minute', ' minutes', ' hour', ' hours', ' day', ' days', ' week', ' weeks',
                ' month', ' months', ' year', ' years'
            ],
            ['s', 's', 'm', 'm', 'h', 'h', 'd', 'd', 'w', 'w', 'mo', 'mo', 'y', 'y'],
            $time - > diffForHumans()
        );
    }
</script>
