{{-- {{dd($postPhotoList)}} --}}
<main class="new-main-content">

    <div class="container">
        <div class="row">
            <div class="col-xl-3 col-lg-4">
                <!-- =============mainleft-====================== -->

                <x-event_wall.wall_left_menu :page="$current_page" :eventDetails="$eventDetails"  />
            </div>
            <div class="col-xl-9 col-lg-8">
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
                                    Photos
                                </li>
                            </ol>
                        </nav>
                    </div>
                    <!-- ===event-breadcrumb-wrp-end=== -->
                    {{-- <x-event_wall.wall_title /> --}}
                    <!-- ===event-center-title-start=== -->

                    <!-- ===event-center-title-end=== -->

                    <!-- ===event-center-tabs-main-start=== -->
                    <div class="event-center-tabs-main">
                        <!-- ====================navbar-============================= -->
                        {{-- <x-event_wall.wall_navbar :event="$event" :current_page="$current_page"/> --}}
                        <x-event_wall.wall_navbar :event="$event" :page="$current_page" :eventDetails="$eventDetails" />

                        <!-- ===tab-content-start=== -->
                        <div class="tab-content" id="nav-tabContent">
                            @php
                                $photos_active = '';
                                $photos_show = '';
                                if ($current_page == 'photos') {
                                    $photos_active = 'active';
                                    $photos_show = 'show';
                                }
                            @endphp
                            <div class="tab-pane fade {{ $photos_show }} {{ $photos_active }}" id="nav-photos"
                                role="tabpanel" aria-labelledby="nav-photos-tab">
                                <div class="photos-main-wrp">
                                    <div class="row">
                                        {{-- <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6">
                                            <div class="photos-card-wrp">
                                                <div class="photo-card-head">
                                                    <div class="photo-card-head-left">
                                                        <div class="photo-card-head-left-img">
                                                            <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="photo-card-head-left-content">
                                                            <h3>Aspen Dorwart</h3>
                                                            <p>10 min ago</p>
                                                        </div>
                                                    </div>
                                                    <div class="photo-card-head-right">
                                                        <!-- <button class="posts-card-like-btn" id="likeButton">
                                                      <i class="fa-regular fa-heart"></i>
                                                    </button>

                                                    <div class="photos-likes-options-wrp" id="emojiDropdown" style="display: none;">
                                                      <img src="img/heart-emoji.png" alt="Heart Emoji" class="emoji" data-emoji="❤️">
                                                      <img src="/thumb-icon.png" alt="Thumb Emoji" class="emoji" data-emoji="👍">
                                                      <img src="ont/img/smily-emoji.png" alt="Smiley Emoji" class="emoji" data-emoji="😊">
                                                      <img src="ets/front/img/eye-heart-emoji.png" alt="Eye Heart Emoji" class="emoji" data-emoji="😍">
                                                      <img src="./assets/img/clap-icon.png" alt="Clap Emoji" class="emoji" data-emoji="👏">
                                                    </div>

                                                    <div class="photos-card-dropdown dropdown">
                                                      <button class="photos-card-dropdown-btn dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false"><i class="fa-solid fa-ellipsis-vertical"></i></button>
                                                      <ul class="dropdown-menu" >
                                                        <li><button class="dropdown-item"><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                          <path d="M7.4987 8.33333C8.41917 8.33333 9.16536 7.58714 9.16536 6.66667C9.16536 5.74619 8.41917 5 7.4987 5C6.57822 5 5.83203 5.74619 5.83203 6.66667C5.83203 7.58714 6.57822 8.33333 7.4987 8.33333Z" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M10.8346 1.66699H7.5013C3.33464 1.66699 1.66797 3.33366 1.66797 7.50033V12.5003C1.66797 16.667 3.33464 18.3337 7.5013 18.3337H12.5013C16.668 18.3337 18.3346 16.667 18.3346 12.5003V8.33366" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M15 1.66699V6.66699L16.6667 5.00033" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M14.9987 6.66667L13.332 5" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M2.22656 15.7918L6.3349 13.0335C6.99323 12.5918 7.94323 12.6418 8.5349 13.1501L8.8099 13.3918C9.4599 13.9501 10.5099 13.9501 11.1599 13.3918L14.6266 10.4168C15.2766 9.85846 16.3266 9.85846 16.9766 10.4168L18.3349 11.5835" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          </svg> Download </button></li>
                                                        <li><button class="dropdown-item">
                                                          <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                          <path d="M17.5 4.98332C14.725 4.70832 11.9333 4.56665 9.15 4.56665C7.5 4.56665 5.85 4.64998 4.2 4.81665L2.5 4.98332" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M7.08203 4.14169L7.26536 3.05002C7.3987 2.25835 7.4987 1.66669 8.90703 1.66669H11.0904C12.4987 1.66669 12.607 2.29169 12.732 3.05835L12.9154 4.14169" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M15.7096 7.61658L15.168 16.0082C15.0763 17.3166 15.0013 18.3332 12.6763 18.3332H7.3263C5.0013 18.3332 4.9263 17.3166 4.83464 16.0082L4.29297 7.61658" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M8.60938 13.75H11.3844" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          <path d="M7.91797 10.4167H12.0846" stroke="#64748B" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                                          </svg> Delete </button></li>
                                                        <li><button class="dropdown-item" ><svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                          <path d="M10.0013 18.9587C5.05964 18.9587 1.04297 14.942 1.04297 10.0003C1.04297 5.05866 5.05964 1.04199 10.0013 1.04199C14.943 1.04199 18.9596 5.05866 18.9596 10.0003C18.9596 14.942 14.943 18.9587 10.0013 18.9587ZM10.0013 2.29199C5.7513 2.29199 2.29297 5.75033 2.29297 10.0003C2.29297 14.2503 5.7513 17.7087 10.0013 17.7087C14.2513 17.7087 17.7096 14.2503 17.7096 10.0003C17.7096 5.75033 14.2513 2.29199 10.0013 2.29199Z" fill="#94A3B8"/>
                                                          <path d="M10 11.4587C9.65833 11.4587 9.375 11.1753 9.375 10.8337V6.66699C9.375 6.32533 9.65833 6.04199 10 6.04199C10.3417 6.04199 10.625 6.32533 10.625 6.66699V10.8337C10.625 11.1753 10.3417 11.4587 10 11.4587Z" fill="#94A3B8"/>
                                                          <path d="M10.0013 14.1664C9.89297 14.1664 9.78464 14.1414 9.68464 14.0997C9.58464 14.0581 9.49297 13.9997 9.40964 13.9247C9.33464 13.8414 9.2763 13.7581 9.23464 13.6497C9.19297 13.5497 9.16797 13.4414 9.16797 13.3331C9.16797 13.2247 9.19297 13.1164 9.23464 13.0164C9.2763 12.9164 9.33464 12.8247 9.40964 12.7414C9.49297 12.6664 9.58464 12.6081 9.68464 12.5664C9.88464 12.4831 10.118 12.4831 10.318 12.5664C10.418 12.6081 10.5096 12.6664 10.593 12.7414C10.668 12.8247 10.7263 12.9164 10.768 13.0164C10.8096 13.1164 10.8346 13.2247 10.8346 13.3331C10.8346 13.4414 10.8096 13.5497 10.768 13.6497C10.7263 13.7581 10.668 13.8414 10.593 13.9247C10.5096 13.9997 10.418 14.0581 10.318 14.0997C10.218 14.1414 10.1096 14.1664 10.0013 14.1664Z" fill="#94A3B8"/>
                                                          </svg> Report</button></li>
                                                      </ul>
                                                    </div> -->
                                                        <div class="form-check">
                                                            <input class="form-check-input" type="checkbox"
                                                                value="" id="flexCheckDefault">
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="photo-card-photos-wrp">
                                                    <div class="photo-card-photos-main-img">
                                                        <img src="{{ asset('assets/front/img/photos-main-img.png') }}"
                                                            alt="">
                                                    </div>
                                                    <button class="total-photos-count-btn" type="button"
                                                    >+10</button>
                                                    <ul>
                                                        <li><img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                alt=""></li>
                                                        <li><img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                alt=""></li>
                                                        <li><img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                alt=""></li>
                                                        <p>105</p>
                                                    </ul>
                                                    <h5>
                                                        <svg viewBox="0 0 14 14" fill="none"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M9.91602 1.16669H4.08268C2.47268 1.16669 1.16602 2.46752 1.16602 4.07169V7.56002V8.14335C1.16602 9.74752 2.47268 11.0484 4.08268 11.0484H4.95768C5.11518 11.0484 5.32518 11.1534 5.42435 11.2817L6.29935 12.4425C6.68435 12.9559 7.31435 12.9559 7.69935 12.4425L8.57435 11.2817C8.68518 11.1359 8.86018 11.0484 9.04102 11.0484H9.91602C11.526 11.0484 12.8327 9.74752 12.8327 8.14335V4.07169C12.8327 2.46752 11.526 1.16669 9.91602 1.16669ZM7.58268 8.02085H4.08268C3.84352 8.02085 3.64518 7.82252 3.64518 7.58335C3.64518 7.34419 3.84352 7.14585 4.08268 7.14585H7.58268C7.82185 7.14585 8.02018 7.34419 8.02018 7.58335C8.02018 7.82252 7.82185 8.02085 7.58268 8.02085ZM9.91602 5.10419H4.08268C3.84352 5.10419 3.64518 4.90585 3.64518 4.66669C3.64518 4.42752 3.84352 4.22919 4.08268 4.22919H9.91602C10.1552 4.22919 10.3535 4.42752 10.3535 4.66669C10.3535 4.90585 10.1552 5.10419 9.91602 5.10419Z"
                                                                fill="white" fill-opacity="0.5" />
                                                        </svg>
                                                        24
                                                    </h5>
                                                    <button class="selected-photo-btn"><i
                                                            class="fa-solid fa-check"></i></button>
                                                </div>
                                            </div>
                                        </div> --}}
                                        @foreach ($postPhotoList as $photo)
                                            <div class="col-xl-4 col-lg-6 col-md-4 col-sm-6 delete_post_container">
                                                <div class="photos-card-wrp">
                                                    <div class="photo-card-head">
                                                        <div class="photo-card-head-left">
                                                            <div class="photo-card-head-left-img">
                                                                @if ($photo['profile'] != '')
                                                                    <img src="{{ $photo['profile'] }}" alt="">
                                                                @else
                                                                    @php
                                                                        $name = $photo['firstname'];
                                                                        // $parts = explode(" ", $name);
                                                                        $firstInitial = isset($photo['firstname'][0])
                                                                            ? strtoupper($photo['firstname'][0][0])
                                                                            : '';
                                                                        $secondInitial = isset($photo['lastname'][0])
                                                                            ? strtoupper($photo['lastname'][0][0])
                                                                            : '';
                                                                        $initials =
                                                                            strtoupper($firstInitial) .
                                                                            strtoupper($secondInitial);
                                                                        $fontColor =
                                                                            'fontcolor' . strtoupper($firstInitial);
                                                                    @endphp
                                                                    <h5 class="{{ $fontColor }}">
                                                                        {{ $initials }}
                                                                    </h5>
                                                                @endif

                                                            </div>
                                                            <div class="photo-card-head-left-content">
                                                                <h3>{{ $photo['firstname'] }} {{ $photo['lastname'] }}
                                                                </h3>
                                                                <p>{{ $photo['post_time'] }}</p>
                                                            </div>
                                                        </div>
                                                        <div class="photo-card-head-right set_emoji_like">
                                                            <button class="posts-card-like-btn like-btn" id="likeButton"
                                                                data-event-id="{{ $event }}"
                                                                data-event-post-id="{{ $photo['id'] }} "
                                                                data-user-id="{{ $login_user_id }}">
                                                                @if ($photo['self_reaction'] == '\u{2764}')
                                                                    <i class="fa-solid fa-heart" id="show_Emoji"></i>
                                                                @elseif($photo['self_reaction'] == '\u{1F494}')
                                                                    <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                @elseif($photo['self_reaction'] == '\u{1F44D}')
                                                                    <i id="show_Emoji"> <img
                                                                            src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                            alt="Thumb Emoji" class="emoji"
                                                                            data-emoji="👍"
                                                                            data-unicode="\\u{1F44D}"></i>
                                                                @elseif($photo['self_reaction'] == '\u{1F604}')
                                                                    <i id="show_Emoji"> <img
                                                                            src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                            alt="Smiley Emoji" class="emoji"
                                                                            data-emoji="😊"
                                                                            data-unicode="\\u{1F604}"></i>
                                                                @elseif($photo['self_reaction'] == '\u{1F60D}')
                                                                    <i id="show_Emoji"> <img
                                                                            src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                            alt="Eye Heart Emoji" class="emoji"
                                                                            data-emoji="😍"
                                                                            data-unicode="\\u{1F60D}"></i>
                                                                @elseif($photo['self_reaction'] == '\u{1F44F}')
                                                                    <i id="show_Emoji"> <img
                                                                            src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                            alt="Clap Emoji" class="emoji"
                                                                            data-emoji="👏"
                                                                            data-unicode="\\u{1F44F}"></i>
                                                                @else
                                                                    <i class="fa-regular fa-heart" id="show_Emoji"></i>
                                                                @endif
                                                            </button>

                                                            <div class="photos-likes-options-wrp emoji-picker"
                                                                id="emojiDropdown" style="display: none;">
                                                                <img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                    alt="Heart Emoji" class="emoji" data-emoji="❤️"
                                                                    data-unicode="\\u{2764}">
                                                                <img src="{{ asset('assets/front/img/thumb-icon.png') }}"
                                                                    alt="Thumb Emoji" class="emoji" data-emoji="👍"
                                                                    data-unicode="\\u{1F44D}">
                                                                <img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                    alt="Smiley Emoji" class="emoji" data-emoji="😊"
                                                                    data-unicode="\\u{1F604}">
                                                                <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                                    alt="Eye Heart Emoji" class="emoji" data-emoji="😍"
                                                                    data-unicode="\\u{1F60D}">
                                                                <img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                    alt="Clap Emoji" class="emoji" data-emoji="👏"
                                                                    data-unicode="\\u{1F44F}">
                                                            </div>

                                                            <div class="photos-card-dropdown dropdown">
                                                                <button class="photos-card-dropdown-btn dropdown-toggle"
                                                                    type="button" data-bs-toggle="dropdown"
                                                                    aria-expanded="false"><i
                                                                        class="fa-solid fa-ellipsis-vertical"></i></button>
                                                                <ul class="dropdown-menu">
                                                                    <li><button
                                                                            class="dropdown-item download_img_single"><svg
                                                                                viewBox="0 0 20 20" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M7.4987 8.33333C8.41917 8.33333 9.16536 7.58714 9.16536 6.66667C9.16536 5.74619 8.41917 5 7.4987 5C6.57822 5 5.83203 5.74619 5.83203 6.66667C5.83203 7.58714 6.57822 8.33333 7.4987 8.33333Z"
                                                                                    stroke="#94A3B8"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path
                                                                                    d="M10.8346 1.66699H7.5013C3.33464 1.66699 1.66797 3.33366 1.66797 7.50033V12.5003C1.66797 16.667 3.33464 18.3337 7.5013 18.3337H12.5013C16.668 18.3337 18.3346 16.667 18.3346 12.5003V8.33366"
                                                                                    stroke="#94A3B8"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path
                                                                                    d="M15 1.66699V6.66699L16.6667 5.00033"
                                                                                    stroke="#94A3B8"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path d="M14.9987 6.66667L13.332 5"
                                                                                    stroke="#94A3B8"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path
                                                                                    d="M2.22656 15.7918L6.3349 13.0335C6.99323 12.5918 7.94323 12.6418 8.5349 13.1501L8.8099 13.3918C9.4599 13.9501 10.5099 13.9501 11.1599 13.3918L14.6266 10.4168C15.2766 9.85846 16.3266 9.85846 16.9766 10.4168L18.3349 11.5835"
                                                                                    stroke="#94A3B8"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg> Download </button></li>
                                                                    <li><button class="dropdown-item" id="delete_post"
                                                                            data-event-post-id="{{ $photo['id'] }}"
                                                                            data-event-id="{{ $event }}">
                                                                            <svg viewBox="0 0 20 20" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M17.5 4.98332C14.725 4.70832 11.9333 4.56665 9.15 4.56665C7.5 4.56665 5.85 4.64998 4.2 4.81665L2.5 4.98332"
                                                                                    stroke="#64748B"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path
                                                                                    d="M7.08203 4.14169L7.26536 3.05002C7.3987 2.25835 7.4987 1.66669 8.90703 1.66669H11.0904C12.4987 1.66669 12.607 2.29169 12.732 3.05835L12.9154 4.14169"
                                                                                    stroke="#64748B"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path
                                                                                    d="M15.7096 7.61658L15.168 16.0082C15.0763 17.3166 15.0013 18.3332 12.6763 18.3332H7.3263C5.0013 18.3332 4.9263 17.3166 4.83464 16.0082L4.29297 7.61658"
                                                                                    stroke="#64748B"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path d="M8.60938 13.75H11.3844"
                                                                                    stroke="#64748B"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                                <path d="M7.91797 10.4167H12.0846"
                                                                                    stroke="#64748B"
                                                                                    stroke-width="1.5"
                                                                                    stroke-linecap="round"
                                                                                    stroke-linejoin="round" />
                                                                            </svg> Delete </button></li>
                                                                    <li><button class="dropdown-item"><svg
                                                                                viewBox="0 0 20 20" fill="none"
                                                                                xmlns="http://www.w3.org/2000/svg">
                                                                                <path
                                                                                    d="M10.0013 18.9587C5.05964 18.9587 1.04297 14.942 1.04297 10.0003C1.04297 5.05866 5.05964 1.04199 10.0013 1.04199C14.943 1.04199 18.9596 5.05866 18.9596 10.0003C18.9596 14.942 14.943 18.9587 10.0013 18.9587ZM10.0013 2.29199C5.7513 2.29199 2.29297 5.75033 2.29297 10.0003C2.29297 14.2503 5.7513 17.7087 10.0013 17.7087C14.2513 17.7087 17.7096 14.2503 17.7096 10.0003C17.7096 5.75033 14.2513 2.29199 10.0013 2.29199Z"
                                                                                    fill="#94A3B8" />
                                                                                <path
                                                                                    d="M10 11.4587C9.65833 11.4587 9.375 11.1753 9.375 10.8337V6.66699C9.375 6.32533 9.65833 6.04199 10 6.04199C10.3417 6.04199 10.625 6.32533 10.625 6.66699V10.8337C10.625 11.1753 10.3417 11.4587 10 11.4587Z"
                                                                                    fill="#94A3B8" />
                                                                                <path
                                                                                    d="M10.0013 14.1664C9.89297 14.1664 9.78464 14.1414 9.68464 14.0997C9.58464 14.0581 9.49297 13.9997 9.40964 13.9247C9.33464 13.8414 9.2763 13.7581 9.23464 13.6497C9.19297 13.5497 9.16797 13.4414 9.16797 13.3331C9.16797 13.2247 9.19297 13.1164 9.23464 13.0164C9.2763 12.9164 9.33464 12.8247 9.40964 12.7414C9.49297 12.6664 9.58464 12.6081 9.68464 12.5664C9.88464 12.4831 10.118 12.4831 10.318 12.5664C10.418 12.6081 10.5096 12.6664 10.593 12.7414C10.668 12.8247 10.7263 12.9164 10.768 13.0164C10.8096 13.1164 10.8346 13.2247 10.8346 13.3331C10.8346 13.4414 10.8096 13.5497 10.768 13.6497C10.7263 13.7581 10.668 13.8414 10.593 13.9247C10.5096 13.9997 10.418 14.0581 10.318 14.0997C10.218 14.1414 10.1096 14.1664 10.0013 14.1664Z"
                                                                                    fill="#94A3B8" />
                                                                            </svg> Report</button></li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    @php
                                                    $postMedia = [];
                                                        foreach($photo['mediaData'] as $k =>$v){


                                                                $postMedia[] = $photo['mediaData'][$k]['post_media'];

                                                        }
                                                    @endphp

                                                    <div class="photo-card-photos-wrp imagePress">
                                                        <div class="photo-card-photos-main-img open_photo_model img_click"
                                                            data-bs-toggle="modal"
                                                            data-bs-target="#detail-photo-modal"
                                                            data-post-id="{{ $photo['id'] }}"
                                                            data-event-id="{{ $photo['event_id'] }}"
                                                            data-image="{{ json_encode($postMedia ) }}">


                                                            @if (!empty($photo['mediaData']) && isset($photo['mediaData'][0]['type']) && $photo['mediaData'][0]['type'] === 'image')
                                                            <img src="{{ $photo['mediaData'][0]['post_media'] }}" loading="lazy" alt="Post Image">
                                                        @else
                                                            <p>No image available</p>
                                                        @endif

                                                        </div>
                                                        @if ($photo['total_media'] != '')
                                                            <button class="total-photos-count-btn photo_model"
                                                                type="button">{{ $photo['total_media'] }}</button>
                                                        @endif
                                                        {{-- {{dd($photo['reactionList'])}} --}}
                                                        <ul>
                                                            @if (!empty($photo['reactionList']) && is_array($photo['reactionList']))
                                                                @foreach ($photo['reactionList'] as $reaction)
                                                                    <li>
                                                                        <span class="reaction-emoji">
                                                                            {{ preg_replace_callback(
                                                                                '/\\\\u\{([0-9A-F]+)\}/',
                                                                                function ($matches) {
                                                                                    return mb_convert_encoding('&#x' . $matches[1] . ';', 'UTF-8', 'HTML-ENTITIES');
                                                                                },
                                                                                $reaction,
                                                                            ) }}
                                                                        </span>

                                                                    </li>
                                                                @endforeach
                                                            @endif

                                                            {{-- <li><img src="{{ asset('assets/front/img/heart-emoji.png') }}"
                                                                    alt=""></li>
                                                            <li><img src="{{ asset('assets/front/img/smily-emoji.png') }}"
                                                                    alt=""></li>
                                                            <li><img src="{{ asset('assets/front/img/clap-icon.png') }}"
                                                                    alt=""></li> --}}
                                                            <p id="likeCount_{{ $photo['id'] }}">
                                                                {{ $photo['total_likes'] }} Likes</p>
                                                        </ul>
                                                        <h5>
                                                            <svg viewBox="0 0 14 14" fill="none"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M9.91602 1.16669H4.08268C2.47268 1.16669 1.16602 2.46752 1.16602 4.07169V7.56002V8.14335C1.16602 9.74752 2.47268 11.0484 4.08268 11.0484H4.95768C5.11518 11.0484 5.32518 11.1534 5.42435 11.2817L6.29935 12.4425C6.68435 12.9559 7.31435 12.9559 7.69935 12.4425L8.57435 11.2817C8.68518 11.1359 8.86018 11.0484 9.04102 11.0484H9.91602C11.526 11.0484 12.8327 9.74752 12.8327 8.14335V4.07169C12.8327 2.46752 11.526 1.16669 9.91602 1.16669ZM7.58268 8.02085H4.08268C3.84352 8.02085 3.64518 7.82252 3.64518 7.58335C3.64518 7.34419 3.84352 7.14585 4.08268 7.14585H7.58268C7.82185 7.14585 8.02018 7.34419 8.02018 7.58335C8.02018 7.82252 7.82185 8.02085 7.58268 8.02085ZM9.91602 5.10419H4.08268C3.84352 5.10419 3.64518 4.90585 3.64518 4.66669C3.64518 4.42752 3.84352 4.22919 4.08268 4.22919H9.91602C10.1552 4.22919 10.3535 4.42752 10.3535 4.66669C10.3535 4.90585 10.1552 5.10419 9.91602 5.10419Z"
                                                                    fill="white" fill-opacity="0.5" />
                                                            </svg>
                                                            {{ $photo['total_comments'] }}
                                                        </h5>
                                                        <button class="selected-photo-btn" style="display:none;">
                                                            <input class="form-check-input selected_image"
                                                                type="checkbox" value="" id="flexCheckDefault">
                                                        </button>
                                                    </div>


                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                    {{-- // @if (empty($postPhotoList)) --}}
                                    <div class="no-photos-screen">
                                        @if (empty($postPhotoList))
                                            <div class="no-photos-screen-inner">

                                                <div class="no-photos-screen-img">
                                                    <img src="{{ asset('assets/front/img/no-photo-screen.png') }}"
                                                        alt="" loading="lazy">
                                                </div>

                                                <div class="no-photos-screen-content">
                                                    <h3>It’s lonely in here!</h3>
                                                    <p>No one has uploaded photos yet, upload some below</p>
                                                </div>
                                            </div>
                                        @endif
                                        <div class="phototab-add-new-photos-wrp">
                                            <div class="phototab-add-new-photos-img">
                                                @if ($photos != '')
                                                    <img src="{{ $photos }}" alt="" loading="lazy">
                                                @else
                                                    @php

                                                        // $parts = explode(" ", $name);
                                                        $firstInitial = isset($firstname[0])
                                                            ? strtoupper($firstname[0][0])
                                                            : '';
                                                        $secondInitial = isset($lastname[0])
                                                            ? strtoupper($lastname[0][0])
                                                            : '';
                                                        $initials =
                                                            strtoupper($firstInitial) . strtoupper($secondInitial);
                                                        $fontColor = 'fontcolor' . strtoupper($firstInitial);
                                                    @endphp
                                                    <h5 class="{{ $fontColor }}">
                                                        {{ $initials }}
                                                    </h5>
                                                @endif

                                                <p>Let’s share a moment</p>
                                            </div>
                                            <button class="add-new-photos-btn cmn-btn" type="button"
                                                data-bs-toggle="modal" data-bs-target="#add-new-photomodal"><i
                                                    class="fa-solid fa-plus"></i> Add Photo</button>
                                        </div>
                                    </div>

                                    <div class="phototab-add-new-photos-wrp bulk-select-photo-wrp d-none">
                                        <div class="phototab-add-new-photos-img">
                                            <i class="fa-solid fa-angle-left"></i>
                                            <p>3 Photos Selected</p>
                                        </div>
                                        <button class="add-new-photos-btn cmn-btn download_img" type="button"><svg
                                                viewBox="0 0 16 16" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M8.0013 10.667L4.66797 7.33366L5.6013 6.36699L7.33464 8.10033V2.66699H8.66797V8.10033L10.4013 6.36699L11.3346 7.33366L8.0013 10.667ZM4.0013 13.3337C3.63464 13.3337 3.32075 13.2031 3.05964 12.942C2.79852 12.6809 2.66797 12.367 2.66797 12.0003V10.0003H4.0013V12.0003H12.0013V10.0003H13.3346V12.0003C13.3346 12.367 13.2041 12.6809 12.943 12.942C12.6819 13.2031 12.368 13.3337 12.0013 13.3337H4.0013Z"
                                                    fill="white" />
                                            </svg> Download</button>
                                    </div>
                                    {{-- @endif --}}
                                </div>

                            </div>

                        </div>
                        <!-- ===tab-content-end=== -->

                    </div>
                    <!-- ===event-center-tabs-main-end=== -->
                </div>
            </div>

        </div>
    </div>
</main>
<!-- ===add-new-photo=== -->
<div class="modal fade create-post-modal" id="add-new-photomodal" tabindex="-1"
    aria-labelledby="add-new-photomodal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="create-post-main-body">
                <div class="modal-header">
                    <h1 class="modal-title" id="exampleModalLabel">Create New Post</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="create-post-profile">
                        <div class="create-post-profile-wrp">

                            @if ($photos != '')
                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}" alt=""
                                    loading="lazy">
                            @else
                                @php

                                    // $parts = explode(" ", $name);
                                    $firstInitial = isset($firstname[0]) ? strtoupper($firstname[0][0]) : '';
                                    $secondInitial = isset($lastname[0]) ? strtoupper($lastname[0][0]) : '';
                                    $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                    $fontColor = 'fontcolor' . strtoupper($firstInitial);
                                @endphp
                                <h5 class="{{ $fontColor }}">
                                    {{ $initials }}
                                </h5>
                            @endif

                            <div id="savedSettingsDisplay">
                                <h4> <i class="fa-solid fa-angle-down"></i></h4>
                            </div>
                        </div>
                    </div>
                    <form action="{{ route('event_photo.eventPost') }}" id="textform" method="POST"
                        enctype="multipart/form-data">
                        <input type="hidden" id="hiddenVisibility" name="post_privacys" value="">

                        <input type="hidden" id="hiddenAllowComments" name="commenting_on_off" value="">

                        <input type="hidden" name="post_type" id="textPostType" value="0">
                        @csrf
                        <div class="create-post-textcontent">
                            <textarea class="form-control" rows="3" placeholder="What's on your mind?" id="postContent"></textarea>
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
                            <form action="{{ route('event_photo.eventPost') }}" id="photoForm" method="POST"
                                enctype="multipart/form-data">

                                @csrf
                                <div class="create-post-upload-img-inner">
                                    <input type="hidden" name="event_id" id="event_id"
                                        value="{{ $event }}">
                                    <input type="hidden" name="content" id="photoContent">
                                    <input type="hidden" id="hiddenVisibility" name="post_privacys"
                                    value="1">
                                <input type="hidden" name="post_type" id="photoPostType" value="1">
                                <input type="hidden" id="hiddenAllowComments" name="commenting_on_off"
                                    value="1">
                                    <span>
                                        <svg viewBox="0 0 24 25" fill="none" xmlns="http://www.w3.org/2000/svg">
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

                    {{-- <div class="create-post-poll-wrp d-none">
                        <div class="create-post-upload-img-head">
                            <h4>POLL</h4>
                            <div>
                                <span class="upload-poll-delete">
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

                        </div>
                    </div> --}}
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
                        <button type ="button" class="cmn-btn create_post">
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

<!-- ===details-photo=== -->
<div class="modal fade create-post-modal all-events-filtermodal" id="detail-photo-modal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">Detail Photo</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="event-posts-main-wrp common-div-wrp">
                    <div class="posts-card-wrp">
                        <div class="posts-card-head">
                            <div class="posts-card-head-left">
                                <div class="posts-card-head-left-img">
                                    <img src="{{ asset('assets/front/img/header-profile-img.png') }}" alt=""
                                        loading="lazy">
                                    <span class="active-dot"></span>
                                </div>
                                <div class="posts-card-head-left-content">
                                    <h3 id="post_name">Chance Curtis</h3>
                                    <p id="location">New York, NY</p>
                                </div>
                            </div>
                            <div class="posts-card-head-right">
                                <div class="dropdown post-card-dropdown upcoming-card-dropdown">
                                    <button class="dropdown-toggle" type="button" data-bs-toggle="dropdown"
                                        aria-expanded="false"><i class="fa-solid fa-ellipsis"></i></button>
                                    <ul class="dropdown-menu">
                                        <li>
                                            <button class="dropdown-item active hide-post-btn" id="hidePostButton">
                                                <svg id="icon" class="hide-post-svg-icon" viewBox="0 0 20 20"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M9.99896 13.6084C8.00729 13.6084 6.39062 11.9917 6.39062 10.0001C6.39062 8.00839 8.00729 6.39172 9.99896 6.39172C11.9906 6.39172 13.6073 8.00839 13.6073 10.0001C13.6073 11.9917 11.9906 13.6084 9.99896 13.6084ZM9.99896 7.64172C8.69896 7.64172 7.64062 8.70006 7.64062 10.0001C7.64062 11.3001 8.69896 12.3584 9.99896 12.3584C11.299 12.3584 12.3573 11.3001 12.3573 10.0001C12.3573 8.70006 11.299 7.64172 9.99896 7.64172Z"
                                                        fill="#94A3B8" />
                                                    <path
                                                        d="M9.99844 17.5166C6.8651 17.5166 3.90677 15.6833 1.87344 12.4999C0.990104 11.1249 0.990104 8.88328 1.87344 7.49994C3.9151 4.31661 6.87344 2.48328 9.99844 2.48328C13.1234 2.48328 16.0818 4.31661 18.1151 7.49994C18.9984 8.87494 18.9984 11.1166 18.1151 12.4999C16.0818 15.6833 13.1234 17.5166 9.99844 17.5166ZM9.99844 3.73328C7.30677 3.73328 4.73177 5.34994 2.93177 8.17494C2.30677 9.14994 2.30677 10.8499 2.93177 11.8249C4.73177 14.6499 7.30677 16.2666 9.99844 16.2666C12.6901 16.2666 15.2651 14.6499 17.0651 11.8249C17.6901 10.8499 17.6901 9.14994 17.0651 8.17494C15.2651 5.34994 12.6901 3.73328 9.99844 3.73328Z"
                                                        fill="#94A3B8" />
                                                </svg>
                                                <span id="buttonText" class="buttonText">Hide Post</span>
                                            </button>
                                        </li>
                                        <li>
                                            <button class="dropdown-item mute-post-btn" id="mutePostButton">
                                                <svg id="muteIcon" class="muteIcon" viewBox="0 0 20 20"
                                                    fill="none" xmlns="http://www.w3.org/2000/svg">
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
                                                <span id="buttonText" class="mute-post-btn-text">Mute</span>
                                            </button>
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
                                <h5><span class="positive-ans"><i class="fa-solid fa-circle-check"></i>Yes</span> 10m
                                </h5>
                            </div>
                        </div>
                        <div class="posts-card-inner-wrp">
                            <h3 class="posts-card-inner-questions " id="post_message">Join for some drinks upstairs?
                                Anyone?</h3>

                            <div class="posts-card-show-post-wrp">
<<<<<<< Updated upstream
                                <div class="swiper photo-detail-slider">
=======
                                {{-- <div class="swiper mySwiper photo-detail-slider"  > --}}
                                <div class="swiper mySwiper photo-detail-slider"  >
>>>>>>> Stashed changes
                                    <div class="swiper-wrapper" id="media_post">
                                        <!-- Slides -->
                                        <div class="swiper-slide">
                                            <div class="posts-card-show-post-img">
                                                <img src="{{ asset('assets/front/img/photo-detail-img.png') }}"
                                                    alt="" loading="lazy" />
                                            </div>
                                        </div>
                                        {{-- <div class="swiper-slide">
                                            <div class="posts-card-show-post-img">
                                                <img src="{{ asset('assets/front/img/photo-detail-img.png') }}"
                                                    alt=""  loading="lazy"/>
                                            </div>
                                        </div>   <div class="swiper-slide">
                                            <div class="posts-card-show-post-img">
                                                <img src="{{ asset('assets/front/img/photo-detail-img.png') }}"
                                                    alt=""  loading="lazy"/>
                                            </div>
                                        </div> --}}
                                        {{-- <div class="swiper-slide">
                                            <div class="posts-card-show-post-img">
                                                <img src="{{ asset('assets/front/img/photo-detail-img.png') }}"
                                                    alt="" />
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="posts-card-show-post-img">
                                                <img src="{{ asset('assets/front/img/photo-detail-img.png') }}"
                                                    alt="" />
                                            </div>
                                        </div>
                                        <div class="swiper-slide">
                                            <div class="posts-card-show-post-img">
                                                <img src="{{ asset('assets/front/img/photo-detail-img.png') }}"
                                                    alt="" />
                                            </div>
                                        </div> --}}
                                    </div>
                                    <div class="swiper-button-next">
                                        <i class="fa-solid fa-angle-right"></i>
                                    </div>
                                    <div class="swiper-button-prev">
                                        <i class="fa-solid fa-angle-left"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="posts-card-like-commnet-wrp">
                            <div class="posts-card-like-comment-left">
                                <ul type="button" data-bs-toggle="modal" data-bs-target="#reaction-modal">
                                    <li><img src="{{ asset('assets/front/img/smily-emoji.png') }}" alt=""
                                            loading="lazy">
                                    </li>
                                    <li><img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}" alt=""
                                            loading="lazy"></li>
                                    <li><img src="{{ asset('assets/front/img/heart-emoji.png') }}" alt=""
                                            loading="lazy">
                                    </li>
                                    <p id="likes">5k Likes</p>
                                </ul>
                                <h6 id="comments">354 Comments</h6>
                            </div>
                            <div class="posts-card-like-comment-right emoji_set">
                                <button class="posts-card-like-btn likeModel " id="likeButtonModel"
                                    data-event-id="{{ $event }}" data-parent-id="" data-event-post-id=""
                                    data-user-id="{{ $login_user_id }}">
                                    <i class="fa-regular fa-heart" id="show_comment_emoji"></i></button>

                                <div class="photos-likes-options-wrp emoji-picker" id="emojiDropdown1"
                                    style="display: none;">
                                    <img src="{{ asset('assets/front/img/heart-emoji.png') }}" alt="Heart Emoji"
                                        class="emoji model_emoji" data-emoji="❤️" data-unicode="\\u{2764}">
                                    <img src="{{ asset('assets/front/img/thumb-icon.png') }}" alt="Thumb Emoji"
                                        class="emoji  model_emoji" data-emoji="👍" data-unicode="\\u{1F44D}">
                                    <img src="{{ asset('assets/front/img/smily-emoji.png') }}" alt="Smiley Emoji"
                                        class="emoji model_emoji" data-emoji="😊" data-unicode="\\u{1F604}">
                                    <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                        alt="Eye Heart Emoji" class="emoji model_emoji" data-emoji="😍"
                                        data-unicode="\\u{1F60D}">
                                    <img src="{{ asset('assets/front/img/clap-icon.png') }}" alt="Clap Emoji"
                                        class="emoji" data-emoji="👏" data-unicode="\\u{1F44F}">
                                </div>


                                <button class="posts-card-comm show-comments-btn">
                                    <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M8.5 19H8C4 19 2 18 2 13V8C2 4 4 2 8 2H16C20 2 22 4 22 8V13C22 17 20 19 16 19H15.5C15.19 19 14.89 19.15 14.7 19.4L13.2 21.4C12.54 22.28 11.46 22.28 10.8 21.4L9.3 19.4C9.14 19.18 8.77 19 8.5 19Z"
                                            stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M7 8H17" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M7 13H13" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                </button>
                            </div>
                        </div>

                        <div class="posts-card-show-all-comments-wrp d-none">

                            <div class="posts-card-show-all-comments-inner">
                                <ul>
                                    <li class="commented-user-wrp" data-comment-id="" data-replay-comment-id="">
                                        <input type="hidden" id="parent_comment_id" value="">
                                        <input type="hidden" id="reply_comment_id" value="">
                                        <div class="commented-user-head">
                                            <div class="commented-user-profile">
                                                <div class="commented-user-profile-img">
                                                    <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                        alt="">
                                                </div>
                                                <div class="commented-user-profile-content">
                                                    <h3>Angel Geidt</h3>
                                                    <p>New York</p>
                                                </div>
                                            </div>
                                            <div class="posts-card-like-comment-right">
                                                <p>2h</p>
                                                <button class="posts-card-like-btn"><i
                                                        class="fa-regular fa-heart"></i></button>
                                            </div>
                                        </div>
                                        <div class="commented-user-content">
                                            <p>Quisque ipsum nisl, cursus non metus vel, auctor
                                                iaculis massa. Phasellus et odio a
                                                augue rutrum iaculis. Nulla id nisl in tortor
                                                accumsan auctor id vel elit.</p>
                                        </div>
                                        <div class="commented-user-reply-wrp">
                                            <div class="position-relative d-flex align-items-center gap-2">
                                                <button class="posts-card-like-btn"><i
                                                        class="fa-regular fa-heart"></i></button>
                                                <p>121</p>
                                            </div>
                                            <button class="commented-user-reply-btn">Reply</button>
                                        </div>
                                        <ul>
                                            <li class="reply-on-comment" data-comment-id="">
                                                <div class="commented-user-head">
                                                    <div class="commented-user-profile">
                                                        <div class="commented-user-profile-img">
                                                            <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                                alt="">
                                                        </div>
                                                        <div class="commented-user-profile-content">
                                                            <h3>Angel Geidt</h3>
                                                            <p>New York</p>
                                                        </div>
                                                    </div>
                                                    <div class="posts-card-like-comment-right">
                                                        <p>2h</p>
                                                        <button class="posts-card-like-btn"><i
                                                                class="fa-regular fa-heart"></i></button>
                                                    </div>
                                                </div>
                                                <div class="commented-user-content">
                                                    <p>Quisque ipsum nisl, cursus non metus vel,
                                                        auctor iaculis massa. Phasellus et
                                                        odio a augue rutrum iaculis. Nulla id
                                                        nisl
                                                        in tortor accumsan auctor id vel
                                                        elit.</p>
                                                </div>
                                                <div class="commented-user-reply-wrp">
                                                    <div class="position-relative d-flex align-items-center gap-2">
                                                        <button class="posts-card-like-btn"><i
                                                                class="fa-regular fa-heart"></i></button>
                                                        <p>121</p>
                                                    </div>
                                                    <button class="commented-user-reply-btn">Reply</button>
                                                </div>
                                            </li>

                                            <button class="show-comment-reply-btn">Show 3
                                                reply</button>
                                        </ul>

                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <div class="posts-card-main-comment all-comments-textbox">
                    <input type="text" class="form-control" id="post_comment" placeholder="Add Comment">
                    <span class="comment-send-icon send_comment">
                        <svg viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M7.92473 3.52499L15.0581 7.09166C18.2581 8.69166 18.2581 11.3083 15.0581 12.9083L7.92473 16.475C3.12473 18.875 1.1664 16.9083 3.5664 12.1167L4.2914 10.675C4.47473 10.3083 4.47473 9.69999 4.2914 9.33332L3.5664 7.88332C1.1664 3.09166 3.13306 1.12499 7.92473 3.52499Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M4.5332 10H9.0332" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="comment-microphone-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12 15.5C14.21 15.5 16 13.71 16 11.5V6C16 3.79 14.21 2 12 2C9.79 2 8 3.79 8 6V11.5C8 13.71 9.79 15.5 12 15.5Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M4.34961 9.65002V11.35C4.34961 15.57 7.77961 19 11.9996 19C16.2196 19 19.6496 15.57 19.6496 11.35V9.65002"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path d="M10.6094 6.43C11.5094 6.1 12.4894 6.1 13.3894 6.43" stroke="#94A3B8"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M11.1992 8.55001C11.7292 8.41001 12.2792 8.41001 12.8092 8.55001" stroke="#94A3B8"
                                stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                            <path d="M12 19V22" stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                    <span class="comment-attech-icon">
                        <svg viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M12.2009 11.8L10.7908 13.21C10.0108 13.99 10.0108 15.26 10.7908 16.04C11.5708 16.82 12.8408 16.82 13.6208 16.04L15.8409 13.82C17.4009 12.26 17.4009 9.72999 15.8409 8.15999C14.2809 6.59999 11.7508 6.59999 10.1808 8.15999L7.76086 10.58C6.42086 11.92 6.42086 14.09 7.76086 15.43"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                            <path
                                d="M12 22C17.5228 22 22 17.5228 22 12C22 6.47715 17.5228 2 12 2C6.47715 2 2 6.47715 2 12C2 17.5228 6.47715 22 12 22Z"
                                stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round"
                                stroke-linejoin="round" />
                        </svg>
                    </span>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
<div class="modal fade create-post-modal all-events-filtermodal" id="reaction-modal" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
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
                            <button class="nav-link active" id="nav-all-reaction-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-all-reaction" type="button" role="tab"
                                aria-controls="nav-all-reaction" aria-selected="true">
                                All 106
                            </button>
                            <button class="nav-link" id="nav-heart-reaction-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-heart-reaction" type="button" role="tab"
                                aria-controls="nav-heart-reaction" aria-selected="false" tabindex="-1">
                                <img src="{{ asset('assets/front/img/heart-emoji.png') }}" alt=""
                                    loading="lazy"> 50
                            </button>
                            <button class="nav-link" id="nav-thumb-reaction-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-thumb-reaction" type="button" role="tab"
                                aria-controls="nav-thumb-reaction" aria-selected="false" tabindex="-1">
                                <img src="{{ asset('assets/front/img/thumb-icon.png') }}" alt=""
                                    loading="lazy"> 50
                            </button>
                            <button class="nav-link" id="nav-smily-reaction-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-smily-reaction" type="button" role="tab"
                                aria-controls="nav-smily-reaction" aria-selected="false" tabindex="-1">
                                <img src="{{ asset('assets/front/img/smily-emoji.png') }}" alt=""
                                    loading="lazy"> 50
                            </button>
                            <button class="nav-link" id="nav-eye-heart-reaction-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-eye-heart-reaction" type="button" role="tab"
                                aria-controls="nav-eye-heart-reaction" aria-selected="false" tabindex="-1">
                                <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}" alt=""
                                    loading="lazy"> 50
                            </button>
                            <button class="nav-link" id="nav-clap-reaction-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-clap-reaction" type="button" role="tab"
                                aria-controls="nav-clap-reaction" aria-selected="false" tabindex="-1">
                                <img src="{{ asset('assets/front/img/clap-icon.png') }}" alt=""
                                    loading="lazy"> 50
                            </button>
                        </div>
                    </nav>
                    <!-- ===tabs=== -->

                    <!-- ===Tab-content=== -->
                    <div class="tab-content" id="myTabContent">

                        <div class="tab-pane fade active show" id="nav-all-reaction" role="tabpanel"
                            aria-labelledby="nav-all-reaction-tab">
                            <ul>
                                <li class="reaction-info-wrp">
                                    <div class="commented-user-head">
                                        <div class="commented-user-profile">
                                            <div class="commented-user-profile-img">
                                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                    alt="" loading="lazy">
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>Angel Geidt</h3>
                                                <p>New York</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="{{ asset('assets/front/img/heart-emoji.png') }}" alt=""
                                                loading="lazy">
                                        </div>
                                    </div>
                                </li>

                            </ul>
                        </div>

                        <div class="tab-pane fade" id="nav-heart-reaction" role="tabpanel"
                            aria-labelledby="nav-heart-reaction-tab">
                            <ul>
                                <li class="reaction-info-wrp">
                                    <div class="commented-user-head">
                                        <div class="commented-user-profile">
                                            <div class="commented-user-profile-img">
                                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                    alt="" loading="lazy">
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>Angel Geidt</h3>
                                                <p>New York</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="{{ asset('assets/front/img/heart-emoji.png') }}" alt=""
                                                loading="lazy">
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
                                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                    alt="" loading="lazy">
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>Angel Geidt</h3>
                                                <p>New York</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="{{ asset('assets/front/img/thumb-icon.png') }}" alt=""
                                                loading="lazy">
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
                                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                    alt="" loading="lazy">
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>Angel Geidt</h3>
                                                <p>New York</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="{{ asset('assets/front/img/smily-emoji.png') }}" alt=""
                                                loading="lazy">
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
                                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                    loading="lazy">
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>Angel Geidt</h3>
                                                <p>New York</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="{{ asset('assets/front/img/eye-heart-emoji.png') }}"
                                                alt="" loading="lazy">
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
                                                <img src="{{ asset('assets/front/img/header-profile-img.png') }}"
                                                    alt="" loading="lazy">
                                            </div>
                                            <div class="commented-user-profile-content">
                                                <h3>Angel Geidt</h3>
                                                <p>New York</p>
                                            </div>
                                        </div>
                                        <div class="posts-card-like-comment-right reaction-profile-reaction-img">
                                            <img src="{{ asset('assets/front/img/clap-icon.png') }}" alt=""
                                                loading="lazy">
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
