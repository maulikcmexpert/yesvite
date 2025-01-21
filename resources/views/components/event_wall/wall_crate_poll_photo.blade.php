<div class="wall-main-create-photo-poll-wrp common-div-wrp">
    <div class="wall-creat-photo-poll-inner">
        <div class="wall-creat-photo-poll-head ">
            <div>
                @if ($users->profile != '')
                    <img src="{{ $users->profile ? $users->profile : asset('images/default-profile.png') }}" alt="user-img"
                        class="profile-pic" id="profile-pic">
                @else
                    @php
                        $name = $users->firstname;
                        // $parts = explode(" ", $name);
                        $firstInitial = isset($users->firstname[0]) ? strtoupper($users->firstname[0]) : '';
                        $secondInitial = isset($users->lastname[0]) ? strtoupper($users->lastname[0]) : '';
                        $initials = strtoupper($firstInitial) . strtoupper($secondInitial);
                        $fontColor = 'fontcolor' . strtoupper($firstInitial);
                    @endphp
                    <h5 class="{{ $fontColor }}" class="profile-pic" id="profile-pic">
                        {{ $initials }}
                    </h5>
                @endif
            </div>

                <input type="text" class="form-control" id="text" placeholder="What’s on your mind?">
        </div>
        <div class="wall-creat-photo-poll-wrp">
            <button type="button" data-bs-toggle="modal" data-bs-target="#creatpostmodal">
                <span><svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M8.0013 18.3333H13.0013C17.168 18.3333 18.8346 16.6666 18.8346 12.5V7.49996C18.8346 3.33329 17.168 1.66663 13.0013 1.66663H8.0013C3.83464 1.66663 2.16797 3.33329 2.16797 7.49996V12.5C2.16797 16.6666 3.83464 18.3333 8.0013 18.3333Z"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M7.9987 8.33333C8.91917 8.33333 9.66536 7.58714 9.66536 6.66667C9.66536 5.74619 8.91917 5 7.9987 5C7.07822 5 6.33203 5.74619 6.33203 6.66667C6.33203 7.58714 7.07822 8.33333 7.9987 8.33333Z"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M2.72656 15.7917L6.8349 13.0333C7.49323 12.5917 8.44323 12.6417 9.0349 13.15L9.3099 13.3917C9.9599 13.95 11.0099 13.95 11.6599 13.3917L15.1266 10.4167C15.7766 9.85834 16.8266 9.85834 17.4766 10.4167L18.8349 11.5833"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                Photos/ Videos
            </button>
            <button type="button" data-bs-toggle="modal" data-bs-target="#creatpostmodal">
                <span>
                    <svg width="21" height="20" viewBox="0 0 21 20" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <path d="M2.16797 18.3334H18.8346" stroke="#94A3B8" stroke-width="1.5" stroke-miterlimit="10"
                            stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M8.625 3.33329V18.3333H12.375V3.33329C12.375 2.41663 12 1.66663 10.875 1.66663H10.125C9 1.66663 8.625 2.41663 8.625 3.33329Z"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M3 8.33329V18.3333H6.33333V8.33329C6.33333 7.41663 6 6.66663 5 6.66663H4.33333C3.33333 6.66663 3 7.41663 3 8.33329Z"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M14.668 12.5V18.3334H18.0013V12.5C18.0013 11.5834 17.668 10.8334 16.668 10.8334H16.0013C15.0013 10.8334 14.668 11.5834 14.668 12.5Z"
                            stroke="#94A3B8" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </span>
                Poll
            </button>
        </div>
    </div>
</div>
