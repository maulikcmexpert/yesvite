{{-- {{dd($users)}} --}}
<div class="wall-main-story-wrp">
    <div class="swiper story-slide-slider">
        <div class="swiper-wrapper">
            <div class="swiper-slide">
                <li class="wall-main-story-item ">
                    <button>
                        <div class="wall-story-item-img">
                            @if ($users->profile  != '')
                            <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                            alt="user-img" class="profile-pic" id="profile-pic-{{ $users->id }}"
                            onclick="showStories( {{ $event }},{{ $users->id }})">
                        @else
                            @php
                                $name = $users->firstname;
                                // $parts = explode(" ", $name);
                                $firstInitial = isset($users->firstname[0])
                                    ? strtoupper($users->firstname[0])
                                    : '';
                                $secondInitial = isset($users->lastname[0]) ? strtoupper($users->lastname[0]) : '';
                                $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                                $fontColor = 'fontcolor' . strtoupper($firstInitial);
                            @endphp
                            <h5 class="{{ $fontColor }}" class="profile-pic" id="profile-pic-{{ $users->id }}"
                                onclick="showStories( {{ $event }},{{ $users->id }})">
                                {{ $initials }}
                            </h5>
                        @endif
                            {{-- <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                                alt="user-img" class="profile-pic" id="profile-pic-{{ $users->id }}"
                                onclick="showStories( {{ $event }},{{ $users->id }})"> --}}

                            <span class="wall-add-story-btn">
                                <input type="file" id="story-upload-{{ $users->id }}" class="file-input"
                                    accept="image/*,video/*" onchange="previewStoryImage(event, {{ $users->id }})"
                                    multiple>
                                <i class="fa-solid fa-circle-plus"
                                    onclick="document.getElementById('story-upload-{{ $users->id }}').click()"></i>
                            </span>
                        </div>
                        <h4>You</h4>
                    </button>
                </li>

            </div>

            @foreach ($storiesList as $Allstory)
                <div class="swiper-slide">
                    <li class="wall-main-story-item story-unseen">
                        <button>
                            <div class="wall-story-item-img">
                                @if ($Allstory['profile']  != '')

                                  <img id="story-profile-pic-{{ $Allstory['id'] }} "src="{{ $Allstory['profile'] ? $Allstory['profile'] : asset('images/default-profile.png') }} "    class="story-profile-pic-{{ $Allstory['id'] }}" alt=""
                                  onclick="AllUserStory( {{ $event }},'{{ $Allstory['id'] }}')" />
                                {{-- <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                                alt="user-img" class="profile-pic" id="profile-pic-{{ $users->id }}"
                                onclick="showStories( {{ $event }},{{ $users->id }})"> --}}
                            @else
                                @php
                                  $name = $Allstory['username'] ?? ''; // Ensure username is set

// Split the username into words (assuming first and last names)
$parts = explode(' ', trim($name));

// Get first and second initials
$firstInitial = isset($parts[0][0]) ? strtoupper($parts[0][0]) : '';
$secondInitial = isset($parts[1][0]) ? strtoupper($parts[1][0]) : '';

$initials = $firstInitial . $secondInitial;

                                    $fontColor = 'fontcolor' . strtoupper($firstInitial);
                                @endphp
                                <h5 class="{{ $fontColor }}" class="profile-pic" id="profile-pic-{{ $users->id }}"
                                    onclick="showStories( {{ $event }},{{ $users->id }})">
                                    {{ $initials }}
                                </h5>
                            @endif


                            </div>
                            <h4>{{ $Allstory['username'] }}</h4>
                        </button>
                    </li>
                </div>
            @endforeach

        </div>
    </div>
</div>

<div id="previewModel-{{ $users->id }}" class="modal story_seen_modal story-preview-modal" style="display: none;">
    <div class="modal-content">
        <h3 class="story-preview-title">Status Upload</h3>
        <div class="preview" id="preview-{{ $users->id }}"></div>
        <button id="upload-button-{{ $users->id }}" class="upload-button"
            onclick="uploadStoryImage( {{ $event }},{{ $users->id }})" style="display: none;">
            Upload Story
        </button>

        <button class="btn btn-danger" onclick="closePreviewModal({{ $users->id }})">
            Cancel
        </button>
    </div>
</div>

<div id="loader" class="loader"></div>
{{-- {{dd($wallData)}} --}}
{{-- @if ($wallData['owner_stories']) --}}

<div id="storyModal-{{ $users->id }}" class="modal story_seen_modal" style="display: none;">
    {{-- @foreach ($wallData['owner_stories'] as $own) --}}
    <div class="modal-content">
        <div class="story-seen-profile-wrp">
            <div class="story-seen-profile-img">
                @if ($users->profile  != '')
                <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}"
                alt="user-img" class="profile-pic" id="profile-pic-{{ $users->id }}"
                onclick="showStories( {{ $event }},{{ $users->id }})">
            @else
                @php
                    $name = $users->firstname;
                    // $parts = explode(" ", $name);
                    $firstInitial = isset($users->firstname[0])
                        ? strtoupper($users->firstname[0])
                        : '';
                    $secondInitial = isset($users->lastname[0]) ? strtoupper($users->lastname[0]) : '';
                    $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                    $fontColor = 'fontcolor' . strtoupper($firstInitial);
                @endphp
                <h5 class="{{ $fontColor }}" class="profile-pic" id="profile-pic-{{ $users->id }}"
                    onclick="showStories( {{ $event }},{{ $users->id }})">
                    {{ $initials }}
                </h5>
            @endif
            </div>
            <div class="story-seen-profile-content">
                <h3>you</h3>




            </div>
        </div>
        <!-- Story Display -->
        <div id="story-display-{{ $users->id }}" class="story-display">
            <div class="progress-bar-container">
            </div>
            <div class="story-content">
            </div>
        </div>
        <button class="modal-close" data-id="{{ $users->id }}">
            <i class="fas fa-times"></i> <!-- FontAwesome X Icon -->
        </button>
    </div>
    <!-- Close Button -->

</div>

{{-- @endforeach --}}

{{-- {{dd($storiesList)}} --}}
@foreach ($storiesList as $Allstory)
    {{-- {{dd($Allstory)}} --}}
    @if ($Allstory['id'] !== $users->id)
        <!-- Ensure we only show modals for different stories -->
        <div id="storyModal-{{ $Allstory['id'] }}" class="modal story_seen_modal" style="display: none;">
            <div class="modal-content">
                <div class="story-seen-profile-wrp">
                    <div class="story-seen-profile-img">
                        <img src="{{ $Allstory['profile'] }}">
                    </div>
                    <div class="story-seen-profile-content">
                        <h3>{{ $Allstory['username'] }}</h3>
                        <div class="story-seen-profile-content">
                            @foreach ($Allstory['story'] as $story)
                                <div class="story-item" data-story-id="{{ $story['id'] }}">
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
                <div id="story-display-{{ $Allstory['id'] }}" class="story-display">
                    <div class="progress-bar-container"></div>
                    <div class="story-content"></div>
                </div>
                <button class="modal-close" data-id="{{ $Allstory['id'] }}">
                    <i class="fas fa-times"></i> <!-- FontAwesome X Icon -->
                </button>
            </div>

        </div>
    @endif
@endforeach
